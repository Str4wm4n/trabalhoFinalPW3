<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table         = 'usuarios';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'nome',
        'email',
        'senha',
        'role',
        'ativo',
        'api_token'
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    public function findByToken(string $token)
    {
        return $this->where('api_token', $token)->where('ativo', 1)->first();
    }
}
