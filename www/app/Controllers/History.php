<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class History extends BaseController
{

	use ResponseTrait;

	public function index($id)
	{

		// 880 = Stop Post Blueprint

		// connect to DB
		$db = db_connect();

		// get historic info
		$sql = "SELECT * FROM Prizes WHERE ItemId = ? order by `timestamp` desc LIMIT 2000";
		$query = $db->query($sql, [$id]);
		$data = $query->getResultArray();

		// get name of item from id
		$sql = "SELECT DisplayName FROM Items WHERE ItemId = ?";
		$query = $db->query($sql, [$id]);
		$name = $query->getFirstRow()->DisplayName;

		// data to be sent to view
		$data_ = [
			'name' => $name,
			'data' => $data,
		];

		return $this->respond($data_, 200);

	}

}
