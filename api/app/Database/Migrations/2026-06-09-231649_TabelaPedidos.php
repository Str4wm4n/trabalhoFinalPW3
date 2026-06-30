<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TabelaPedidos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'constraint' => 11,
                'auto_increment' => true,
                'unsigned' => true
            ],                
            'status' => [
	            'type' => 'varchar', 
	            'constraint' => 255, 
	            'null' => true],
            'created_at' => [
	            'type' => 'datetime', 
	            'null' => true],
	           'updated_at' => [
			            'type' => 'datetime', 
			            'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true]

        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pedidos');
    }

    public function down()
    {
        $this->forge->dropTable('pedidos');
    }
}
