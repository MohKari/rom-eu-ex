<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class Cards extends BaseController
{

    public function white()
    {

        $url = 'https://api-europe.poporing.life/get_all_latest_prices?includeCategory=:type_card_white';
        return $this->getCards($url);

    }

    public function blue()
    {

        $url = 'https://api-europe.poporing.life/get_all_latest_prices?includeCategory=:type_card_blue';
        return $this->getCards($url);

    }

    public function green()
    {
        $url = 'https://api-europe.poporing.life/get_all_latest_prices?includeCategory=:type_card_green';
        return $this->getCards($url);

    }

    public function purple()
    {
        $url = 'https://api-europe.poporing.life/get_all_latest_prices?includeCategory=:type_card_purple';
        return $this->getCards($url);

    }

    private function getCards($url)
    {

        die("no more cards, sorry");

        // start curl
        $curl = curl_init();

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

        }

        $cards = [];
        foreach($data->data as $card){

            // convert timestamp into datetime
            if($card->data->timestamp != null){


                if($card->data->price == 0 || $card->data->volume == 0){
                    continue;
                }

	        	$time = Time::createFromTimestamp($card->data->timestamp);
	        	$accurate_at = $time->toDateTimeString();

	        	$cards[] = [
	        		"name" => $card->item_name,
	        		"price" => $card->data->price,
	        		"volume" => $card->data->volume,
	        		"accurate_at" => $accurate_at,
	        		"link" => 'https://europe.poporing.life/?search=:' . $card->item_name,
	        	];
        	}

        }

        // helper("enhanced_dump");
        // ddd($cards);
		// data to be sent to view
		$data_ = [
			'cards' => $cards,
		];

		return view('cards/index', $data_);
	}

}
