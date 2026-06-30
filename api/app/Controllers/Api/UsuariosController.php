<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\API\ResponseTrait;

class UsuariosController extends BaseController
{
    use ResponseTrait;

    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    private function verificarToken()
    {
        $token = $this->getBearerToken();
        if (!$token) {
            return null;
        }
        return $this->usuarioModel->where('api_token', $token)->first();
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

    public function index()
    {
        $usuario = $this->verificarToken();
        if (!$usuario) {
            return $this->failUnauthorized('Token inválido');
        }

        $usuarios = $this->usuarioModel->findAll();
        return $this->respond(['data' => $usuarios], 200);
    }

    public function store()
    {
        $usuario = $this->verificarToken();
        if (!$usuario) {
            return $this->failUnauthorized('Token inválido');
        }

        $rules = [
            'nome' => 'required|string',
            'email' => 'required|valid_email|is_unique[usuarios.email]',
            'senha' => 'required|min_length[6]',
            'role' => 'required|in_list[admin,gerente,operador]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'nome' => $this->request->getVar('nome'),
            'email' => $this->request->getVar('email'),
            'senha' => password_hash($this->request->getVar('senha'), PASSWORD_BCRYPT),
            'role' => $this->request->getVar('role'),
            'ativo' => 1
        ];

        $id = $this->usuarioModel->insert($data);

        return $this->respond(['id' => $id, 'message' => 'Usuário criado com sucesso'], 201);
    }

    public function update($id = null)
    {
        $usuario = $this->verificarToken();
        if (!$usuario) {
            return $this->failUnauthorized('Token inválido');
        }

        $usuarioExistente = $this->usuarioModel->find($id);
        if (!$usuarioExistente) {
            return $this->failNotFound('Usuário não encontrado');
        }

        $data = [];

        if ($this->request->getVar('nome')) {
            $data['nome'] = $this->request->getVar('nome');
        }

        if ($this->request->getVar('email')) {
            $data['email'] = $this->request->getVar('email');
        }

        if ($this->request->getVar('senha')) {
            $data['senha'] = password_hash($this->request->getVar('senha'), PASSWORD_BCRYPT);
        }

        if ($this->request->getVar('role')) {
            $data['role'] = $this->request->getVar('role');
        }

        if ($this->request->getVar('ativo') !== null) {
            $data['ativo'] = (int) $this->request->getVar('ativo');
        }

        if (empty($data)) {
            return $this->fail('Nenhum campo para atualizar');
        }

        $this->usuarioModel->update($id, $data);

        return $this->respond(['message' => 'Usuário atualizado com sucesso'], 200);
    }

    public function show($id = null)
    {
        $usuario = $this->verificarToken();
        if (!$usuario) {
            return $this->failUnauthorized('Token inválido');
        }

        $usuarioData = $this->usuarioModel->find($id);
        if (!$usuarioData) {
            return $this->failNotFound('Usuário não encontrado');
        }

        return $this->respond($usuarioData, 200);
    }

    public function delete($id = null)
    {
        $usuario = $this->verificarToken();
        if (!$usuario) {
            return $this->failUnauthorized('Token inválido');
        }

        $usuarioExistente = $this->usuarioModel->find($id);
        if (!$usuarioExistente) {
            return $this->failNotFound('Usuário não encontrado');
        }

        $this->usuarioModel->delete($id);

        return $this->respond(['message' => 'Usuário deletado com sucesso'], 200);
    }
}
