<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateItemsTable extends Migration
{
	public function up()
	{
		// add auto increment id
        $this->forge->addField('id');

        // add table fields
        $this->forge->addField([
            'name'                                  => ['type' => 'VARCHAR', 'constraint' => 255],
            'display_name'                          => ['type' => 'VARCHAR', 'constraint' => 255],
            // add created_at and updated_at, they seem to get populated via the model?
            'created_at'                            => ['type' => 'DATETIME'],
            'updated_at'                            => ['type' => 'DATETIME'],
        ]);

        $this->forge->createTable('items');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('items');
	}
}
