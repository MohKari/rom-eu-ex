<?php

namespace App\Commands;

use App\Models\CronJobLog;
use App\Models\Items;
use App\Models\StocksAndPrices;
use App\Models\Summary;
use App\Services\InternalAPICallService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\I18n\Time;
use Config\Titan;

/**
 * @todo Write unit test.
 */
class Import extends BaseCommand
{

    /**
     * @var string
     */
    protected $group       = 'Import';

    /**
     * @var string
     */
    protected $name        = 'import:all';

    /**
     * @var string
     */
    protected $description = 'Imports blueprint data via 3rd party API.';

    /**
     * Array of items that already exist in the database.
     * Modeled from Item.php
     * @var array
     */
    private $existing_items = [];

    /**
     * Actually execute the command.
     */
    public function run(array $params = [])
    {

        CLI::write('Command started.');

        helper('enhanced_dump');

        // start curl
        $curl = curl_init();

        // $url = 'https://api-europe.poporing.life/get_all_latest_prices?includeCategory=:type_blueprint_all&mini=0';

        // get all items....
        $url = 'https://api-europe.poporing.life/get_all_latest_prices';

        // set a bunch of curl options
        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => ['Origin', 'https://poporing.life'],
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);

        // if body is provided, we need to encode and add it to the request
        if(!empty($body)){

            // json encode body
            $data = json_encode($body);

            // set the option
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        }

        // execute and get response from curl
        $response = curl_exec($curl);

        // if any errors occurred, put them here
        $error = curl_error($curl);

        // close curl
        curl_close($curl);

        // if error, log it somewhere...
        if($error != ""){

            log_message('error', 'import:blueprints error: ' . $error );

        // else!
        }else{

            // digest $response
            $data = json_decode($response);

            // if we dont have a "true" success, log it as an error
            if($data->success != true){

                 log_message('error', 'import:blueprints error: ' . $response);

            }else{

                // get all existing items
                $model = new Items();
                $this->existing_items = $model->findAll();

                // loop through data that we just got from API call
                $items = $data->data;

                // loop through all the items
                foreach($items as $item){

                    $name = $item->item_name;
                    $price = $item->data->price;
                    $stock = $item->data->volume;

                    // convert timestamp into datetime
                    $time = Time::createFromTimestamp($item->data->timestamp);
                    $accurate_at = $time->toDateTimeString();

                    // replace _ with " "
                    $display_name = str_replace('_', ' ', $name);
                    $display_name = ucwords($display_name);

                    // check if item already exists
                    $id = $this->findExistingItem($name);

                    // if its the first time we are pulling this item...
                    if($id == false){

                        // create a new item in our db
                        $item = new Items();
                        $item->insert([
                            'name' => $name,
                            'display_name' => $display_name,
                        ]);

                        // get id of the record we just inserted
                        $id = $item->getInsertID();

                    }

                    // data to be into DB
                    $data = [
                        'item_id' => $id,
                        'price' => $price,
                        'stock' => $stock,
                        'accurate_at' => $accurate_at,
                    ];

                    // add new entry to stocks and prices table
                    $stocks_and_prices = new StocksAndPrices();
                    $stocks_and_prices->insert($data);

                    //////////////////////
                    // populate summary //
                    //////////////////////

                    $summary = new Summary();
                    $exists = $summary->where('item_id', $id)->first();
                    if($exists == null){
                        $summary->insert($data);
                    }else{
                        if($data['price'] != 0 && $data['stock'] != 0){
                            $summary->where('item_id', $id)
                                ->set($data)
                                ->update();
                        }
                    }



                }

                // in the off chance there is no entry for an item we previous had... update the stock and price to 0                //
                foreach($this->existing_items as $item){

                    $stocks_and_prices = new StocksAndPrices();
                    $stocks_and_prices->insert([
                        'item_id' => $item['id'],
                        'price' => 0,
                        'stock' => 0,
                        'accurate_at' => Time::now()->toDateTimeString(),
                    ]);

                }

            }
        }

        CLI::write('Command ended.');

    }

    /**
     * Return ID of existing item that matches the name provided.
     * @param  string    $name Name of the item from the API
     * @return int/false       ID of matching item
     */
    private function findExistingItem(string $name)
    {

        // loop through all existing items
        foreach($this->existing_items as $k => $item){

            // when match is found, remove it from current existing items and return id
            if($item["name"] == $name){
                unset($this->existing_items[$k]);
                return $item["id"];
            }

        }

        // otherwise no match
        return false;

    }

}
