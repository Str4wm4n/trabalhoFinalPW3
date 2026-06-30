<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoProdutoModel extends Model
{
    protected $table         = 'pedido_produtos';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
    'id_pedido', 
    'id_produto', 
    'quantidade', 
    'preco_unitario'
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

}
