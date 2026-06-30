<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdutosSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nome' => 'Café Expresso',
                'descricao' => 'Café fresco e encorpado, servido quente.',
                'categoria' => 'Bebidas',
                'preco' => '5.90',
                'imagem' => 'https://via.placeholder.com/260x180?text=Caf%C3%A9'
            ],
            [
                'nome' => 'Sanduíche Natural',
                'descricao' => 'Pão integral com peito de frango e salada.',
                'categoria' => 'Lanches',
                'preco' => '18.50',
                'imagem' => 'https://via.placeholder.com/260x180?text=Sandu%C3%ADche'
            ],
            [
                'nome' => 'Suco de Laranja',
                'descricao' => 'Suco natural de laranja espremido na hora.',
                'categoria' => 'Bebidas',
                'preco' => '7.20',
                'imagem' => 'https://via.placeholder.com/260x180?text=Suco'
            ],
            [
                'nome' => 'Porção de Batata Frita',
                'descricao' => 'Crocante e dourada, servida com molho especial.',
                'categoria' => 'Lanches',
                'preco' => '14.00',
                'imagem' => 'https://via.placeholder.com/260x180?text=Batata+Frita'
            ],
            [
                'nome' => 'Bolo de Chocolate',
                'descricao' => 'Fatia macia com cobertura cremosa de chocolate.',
                'categoria' => 'Sobremesas',
                'preco' => '12.90',
                'imagem' => 'https://via.placeholder.com/260x180?text=Bolo'
            ],
            [
                'nome' => 'Sundae de Morango',
                'descricao' => 'Sorvete com calda de morango e confeitos.',
                'categoria' => 'Sobremesas',
                'preco' => '11.50',
                'imagem' => 'https://via.placeholder.com/260x180?text=Sundae'
            ],
        ];

        $this->db->table('produtos')->insertBatch($data);
    }
}
