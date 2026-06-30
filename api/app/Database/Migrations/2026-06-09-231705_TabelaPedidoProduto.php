<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TabelaPedidoProduto extends Migration
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
            'id_pedido' => ['type' => 'int', 'constraint' => 11],
            'id_produto' => ['type' => 'int', 'constraint' => 11],
            'quantidade' => ['type' => 'int', 'constraint' => 11],
            'preco_unitario' => ['type' => 'float', 'constraint' => 11],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true]

        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pedido_produtos');
    }

    public function down()
    {
        $this->forge->dropTable('pedido_produtos');
    }
}

