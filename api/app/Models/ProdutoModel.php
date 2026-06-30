<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoModel extends Model
{
    protected $table         = 'produtos';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'nome',
        'descricao',
        'categoria',
        'preco',
        'imagem',
        'stock_atual',
        'quantidade'
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
}
