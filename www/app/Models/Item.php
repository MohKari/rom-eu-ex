<?php

namespace App\Models;

use App\Models\StocksAndPrices;
use CodeIgniter\Model;

/**
 * TransactionLog Model.
 * @todo Write unit test.
 */
class Item extends Model
{

	// table name and primary key
	protected $table      = 'items';
    protected $primaryKey = 'id';

    // how should data be returned?
    protected $returnType = 'array';

    // is the table using soft delete?
    protected $useSoftDeletes = false;

    // editable fields
    protected $allowedFields = ['name', 'display_name'];

    // default created_at, updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // validation
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}
