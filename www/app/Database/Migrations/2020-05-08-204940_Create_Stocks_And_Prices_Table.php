<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStocksAndPricesTable extends Migration
{
	public function up()
	{

        $this->db->disableForeignKeyChecks();

		// add auto increment id
        $this->forge->addField('id');

        // add table fields
        $this->forge->addField([
        	'item_id'            		=> ['type' => 'INT', 'constraint' => 9],
            'price'                     => ['type' => 'VARCHAR', 'constraint' => 255],
            'stock'                     => ['type' => 'VARCHAR', 'constraint' => 255],
            'accurate_at'               => ['type' => 'DATETIME'],
            // add created_at and updated_at, they seem to get populated via the model?
            'created_at'                => ['type' => 'DATETIME'],
            'updated_at'                => ['type' => 'DATETIME'],
        ]);

        // add foreign key
        $this->forge->addForeignKey('item_id','items','id');

        // create table
        $this->forge->createTable('stocks_and_prices');

        $this->db->enableForeignKeyChecks();

	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->db->disableForeignKeyChecks();
		$this->forge->dropTable('stocks_and_prices');
        $this->db->enableForeignKeyChecks();
	}
}
