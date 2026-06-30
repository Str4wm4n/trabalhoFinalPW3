<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTabelaPedidosAddUsuario extends Migration
{
    public function up()
    {
        $fields = [
            'id_usuario' => [
                'type'       => 'int',
                'constraint' => 11,
                'null'       => true,
            ],
        ];

        $this->forge->addColumn('pedidos', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('pedidos', 'id_usuario');
    }
}
