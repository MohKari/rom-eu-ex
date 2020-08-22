<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * TransactionLog Model.
 * @todo Write unit test.
 */
class Summary extends Model
{

	// table name and primary key
	protected $table      = 'summary';
    protected $primaryKey = 'id';

    // how should data be returned?
    protected $returnType = 'array';

    // is the table using soft delete?
    protected $useSoftDeletes = false;

    // editable fields
    protected $allowedFields = ['item_id', 'price', 'stock', 'accurate_at'];

    // default created_at, updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // validation
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}
