<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSummaryTable extends Migration
{
	public function up()
	{

        $this->db->disableForeignKeyChecks();

		// add auto increment id
        $this->forge->addField('id');

        // add table fields
        $this->forge->addField([
        	'item_id'            		=> ['type' => 'INT', 'constraint' => 9],
            'price'                     => ['type' => 'INT', 'constraint' => 15],
            'stock'                     => ['type' => 'INT', 'constraint' => 9],
            'accurate_at'               => ['type' => 'DATETIME'],
            // add created_at and updated_at, they seem to get populated via the model?
            'created_at'                => ['type' => 'DATETIME'],
            'updated_at'                => ['type' => 'DATETIME'],
        ]);

        // add foreign key
        $this->forge->addForeignKey('item_id','items','id');

        // create table
        $this->forge->createTable('summary');

        $this->db->enableForeignKeyChecks();

	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->db->disableForeignKeyChecks();
		$this->forge->dropTable('summary');
        $this->db->enableForeignKeyChecks();
	}
}
