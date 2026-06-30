<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nome' => 'Super Admin',
            'email' => 'admin@admin.com',
            'senha' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'super_admin',
            'ativo' => 1,
        ];

        $this->db->table('usuarios')->insert($data);
    }
}
