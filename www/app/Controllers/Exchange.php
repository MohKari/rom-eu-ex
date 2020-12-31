<?php

namespace App\Controllers;

class Exchange extends BaseController
{

	public function index()
	{

		helper("cookie");

		$favs = get_cookie("mohkari_fav_rom_eu_borf");

		if($favs != null){
			$favs = json_decode($favs, true);
		}else{
			$favs = [];
		}

		// connect to DB
		$db = db_connect();

		// query im going to run
		$sql = "SELECT * FROM Items WHERE Type = 'Blueprint'";

		// run it
		$query = $db->query($sql);

		// parse it results as an array
		$data = $query->getResultArray();

		// data to be sent to view
		$data_ = [
			'items' => $data,
			'favs' => $favs,
		];

		return view('exchange/index', $data_);

	}

}
