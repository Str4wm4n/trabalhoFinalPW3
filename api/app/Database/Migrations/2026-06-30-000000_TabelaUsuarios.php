<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TabelaUsuarios extends Migration
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
            'email' => [
                'type'       => 'varchar',
                'constraint' => 255,
                'null'       => false,
            ],
            'senha' => [
                'type'       => 'varchar',
                'constraint' => 255,
                'null'       => false,
            ],
            'role' => [
                'type'       => 'enum',
                'constraint' => ['user', 'super_admin'],
                'default'    => 'user',
                'null'       => false,
            ],
            'ativo' => [
                'type'       => 'tinyint',
                'constraint' => 1,
                'default'    => 1,
                'null'       => false,
            ],
            'api_token' => [
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
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}
