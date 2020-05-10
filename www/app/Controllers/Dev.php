<?php namespace App\Controllers;

use App\Models\Items;
use App\Models\StocksAndPrices;

class Dev extends BaseController
{

	public function index()
	{

		// get all items
		$items_model = new Items();
		$items = $items_model->findAll();

		// get stocks and prices model...
		$prices_model = new StocksAndPrices();

		// data to hold items/stocks/prices
		$data = [];

		// loop through all items
		foreach($items as $item){

			// get most recent stock and price related to item
			$r = $prices_model->where('item_id', $item['id'])
			->orderBy('created_at', 'DESC')
			->first();

			// get most recent stock and price related to item
			$r2 = $prices_model->where('item_id', $item['id'])
			->whereNotIn('price', ['0'])
			->orderBy('created_at', 'DESC')
			->first();

			$data[] = [
				'name' => $item['display_name'],
				'price' => $r['price'],
				'stock' => $r['stock'],
				'accurate' => $r['accurate_at'],
				'r_price' => $r2['price'],
				'r_stock' => $r2['stock'],
				'r_accurate' => $r2['accurate_at'],		
			];

		}

		// data to be sent to view
		$data_ = [
			'items' => $data,
		];

		return view('dev/index', $data_);
	}

}
