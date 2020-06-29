<?php

namespace App\Controllers;

use App\Models\Items;
use App\Models\StocksAndPrices;

class Dev extends BaseController
{

	public function index()
	{

		// get all items
		$items_model = new Items();
		// $items = $items_model->findAll();
		$items = $items_model->find();

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
			->whereNotIn('price', [0])
			->orderBy('created_at', 'DESC')
			->first();

			// lowest
			$r3 = $prices_model->where('item_id', $item['id'])
			->whereNotIn('price', [0])
			->orderBy('price', 'ASC')
			->orderBy('accurate_at', 'DESC')
			->first();

			// highest
			$r4 = $prices_model->where('item_id', $item['id'])
			->whereNotIn('price', [0])
			->orderBy('price', 'DESC')
			->orderBy('accurate_at', 'DESC')
			->first();


			// ddd($r3, false);
			$data[] = [
				'link' => 'https://europe.poporing.life/?search=:' . $item['name'],
				'name' => $item['display_name'],
				'id' => $item['id'],
				'price' => number_format($r['price']),
				'stock' => number_format($r['stock']),
				'accurate' => $r['accurate_at'],
				'r_price' => number_format($r2['price']),
				'r_stock' => number_format($r2['stock']),
				'r_accurate' => $r2['accurate_at'],
				'lowest_price' => number_format($r3['price']),
				'lowest_price_at' => $r3['accurate_at'],
				'highest_price' => number_format($r4['price']),
				'highest_price_at' => $r4['accurate_at'],
			];

		}

		// data to be sent to view
		$data_ = [
			'items' => $data,
		];

		return view('dev/index', $data_);
	}

}
