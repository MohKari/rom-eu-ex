<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class Fav extends BaseController
{

	use ResponseTrait;

	public function add($id)
	{
		$this->addToCookie($id);
		return $this->respond("added", 200);

	}

	public function remove($id)
	{
		$this->removeFromCookie($id);
		return $this->respond("removed", 200);
	}

	private function removeFromCookie(string $id){

		// load cookie helper
		helper('cookie');

		$name = "mohkari_fav_rom_eu";

		// get existing...
		$data = get_cookie($name);

		// if cookie currently exists, merge in data...
		if($data != null){
			$data = json_decode($data, true);
			$data[] = $id;
		}else{
			$data = [$id];
		}

		foreach($data as $k => $v){
			if($v == $id){
				unset($data[$k]);
			}
		}

		set_cookie(
			$name,
			json_encode($data),
			31000000,
			$_SERVER['HTTP_HOST'],
			"/",
			false,
			false
		);

	}

	private function addToCookie(string $id){

		// load cookie helper
		helper('cookie');

		$name = "mohkari_fav_rom_eu";

		// get existing...
		$data = get_cookie($name);

		// if cookie currently exists, merge in data...
		if($data != null){
			$data = json_decode($data, true);
			$data[] = $id;
		}else{
			$data = [$id];
		}

		set_cookie(
			$name,
			json_encode($data),
			31000000,
			$_SERVER['HTTP_HOST'],
			"/",
			false,
			false
		);

	}

}
