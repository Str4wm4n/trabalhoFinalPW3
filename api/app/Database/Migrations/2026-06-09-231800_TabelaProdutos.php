<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TabelaProdutos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'int',
                'constraint'     => 11,
                'auto_increment' => true,
                'unsigned'       => true,
            ],
            'nome' => [
                'type'       => 'varchar',
                'constraint' => 255,
                'null'       => false,
            ],
            'descricao' => [
                'type' => 'text',
                'null' => true,
            ],
            'categoria' => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'       => true,
            ],
            'preco' => [
                'type'       => 'decimal',
                'constraint' => '10,2',
                'default'    => 0,
                'null'       => false,
            ],
            'imagem' => [
                'type'       => 'varchar',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('produtos');
    }

    public function down()
    {
        $this->forge->dropTable('produtos');
    }
}
