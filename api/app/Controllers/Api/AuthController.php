<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\API\ResponseTrait;

class AuthController extends BaseController
{
    use ResponseTrait;

    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function login()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $usuario = $this->usuarioModel->findByEmail($email);

        if (!$usuario) {
            return $this->failUnauthorized('E-mail ou senha inválidos');
        }

        if (!password_verify($password, $usuario['senha'])) {
            return $this->failUnauthorized('E-mail ou senha inválidos');
        }

        if (!$usuario['ativo']) {
            return $this->failUnauthorized('Usuário bloqueado');
        }

        $token = bin2hex(random_bytes(32));
        $this->usuarioModel->update($usuario['id'], ['api_token' => $token]);

        return $this->respond([
            'token' => $token,
            'user' => [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email'],
                'role' => $usuario['role']
            ]
        ], 200);
    }

    public function verificarToken()
    {
        $token = $this->getBearerToken();

        if (!$token) {
            return $this->failUnauthorized('Token não fornecido');
        }

        $usuario = $this->usuarioModel->where('api_token', $token)->first();

        if (!$usuario) {
            return $this->failUnauthorized('Token inválido');
        }

        if (!$usuario['ativo']) {
            return $this->failUnauthorized('Usuário bloqueado');
        }

        return $this->respond(['usuario' => $usuario], 200);
    }

    private function getBearerToken()
    {
        $header = $this->request->getHeader('Authorization');
        if ($header) {
            if (preg_match('/Bearer\s+(.+)/', $header, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}
