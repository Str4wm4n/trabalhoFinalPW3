<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTabelaPedidosAddMesaNumero extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('mesa_numero', 'pedidos')) {
            $this->forge->addColumn('pedidos', [
                'mesa_numero' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true,
                    'after' => 'status',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('mesa_numero', 'pedidos')) {
            $this->forge->dropColumn('pedidos', 'mesa_numero');
        }
    }
}
