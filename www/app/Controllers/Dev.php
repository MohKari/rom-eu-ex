<?php

namespace App\Controllers;

class Dev extends BaseController
{

	public function index()
	{

		helper("cookie");

		$favs = get_cookie("mohkari_fav_rom_eu");

		if($favs != null){
			$favs = json_decode($favs, true);
		}else{
			$favs = [];
		}

		// connect to DB
		$db = db_connect();

		// query im going to run
		$sql = "SELECT i.id, i.name, i.display_name, s.price, s.stock , s.accurate_at, s.created_at
			FROM database.items as i
			RIGHT JOIN database.summary as s
			ON i.id = s.item_id
			ORDER BY s.created_at DESC";

		// run it
		$query = $db->query($sql);

		// parse it results as an array
		$data = $query->getResultArray();

		// data to be sent to view
		$data_ = [
			'items' => $data,
			'favs' => $favs,
		];

		return view('dev/index', $data_);

	}

}
