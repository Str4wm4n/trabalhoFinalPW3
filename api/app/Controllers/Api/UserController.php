<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\API\ResponseTrait;

class UserController extends BaseController
{
    use ResponseTrait;

    private function getToken(): ?string
    {
        $authHeader = $this->request->getHeaderLine('Authorization');

        if (! $authHeader) {
            return null;
        }

        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function getCurrentUser(): ?array
    {
        $token = $this->getToken();
        if (! $token) {
            return null;
        }

        $userModel = new UsuarioModel();
        return $userModel->findByToken($token);
    }

    private function requireAuth()
    {
        $user = $this->getCurrentUser();
        if (! $user) {
            return $this->failUnauthorized('Token de autenticação inválido ou usuário bloqueado.');
        }
        return $user;
    }

    private function requireSuperAdmin()
    {
        $user = $this->requireAuth();
        if (! is_array($user)) {
            return $user;
        }

        if ($user['role'] !== 'super_admin') {
            return $this->failForbidden('Ação permitida apenas para Super Admin.');
        }

        return $user;
    }

    public function login()
    {
        $data = $this->request->getJSON(true);
        $email = $data['email'] ?? null;
        $senha = $data['senha'] ?? null;

        if (! $email || ! $senha) {
            return $this->failValidationErrors('E-mail e senha são obrigatórios.');
        }

        $userModel = new UsuarioModel();
        $user = $userModel->findByEmail($email);

        if (! $user || ! password_verify($senha, $user['senha']) || ! (int) $user['ativo']) {
            return $this->failUnauthorized('E-mail ou senha incorretos.');
        }

        $token = bin2hex(random_bytes(24));
        $userModel->update($user['id'], ['api_token' => $token]);

        unset($user['senha']);
        $user['api_token'] = $token;

        return $this->respond(['status' => true, 'usuario' => $user], 200);
    }

    public function logout()
    {
        $user = $this->requireAuth();
        if (! is_array($user)) {
            return $user;
        }

        $userModel = new UsuarioModel();
        $userModel->update($user['id'], ['api_token' => null]);

        return $this->respond(['status' => true, 'message' => 'Logout realizado com sucesso.']);
    }

    public function me()
    {
        $user = $this->requireAuth();
        if (! is_array($user)) {
            return $user;
        }

        unset($user['senha']);
        unset($user['api_token']);

        return $this->respond($user, 200);
    }

    public function list()
    {
        $admin = $this->requireSuperAdmin();
        if (! is_array($admin)) {
            return $admin;
        }

        $userModel = new UsuarioModel();
        $users = $userModel->select('id, nome, email, role, ativo, created_at, updated_at')->findAll();

        return $this->respond($users, 200);
    }

    public function create()
    {
        $admin = $this->requireSuperAdmin();
        if (! is_array($admin)) {
            return $admin;
        }

        $data = $this->request->getJSON(true);
        $nome = trim($data['nome'] ?? '');
        $email = trim($data['email'] ?? '');
        $senha = $data['senha'] ?? null;
        $role = in_array($data['role'] ?? 'user', ['user', 'super_admin'], true) ? $data['role'] : 'user';
        $ativo = isset($data['ativo']) ? (int) $data['ativo'] : 1;

        if (! $nome || ! $email || ! $senha) {
            return $this->failValidationErrors('Nome, e-mail e senha são obrigatórios.');
        }

        $userModel = new UsuarioModel();
        if ($userModel->where('email', $email)->first()) {
            return $this->failValidationErrors('E-mail já está em uso.');
        }

        $userId = $userModel->insert([
            'nome' => $nome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'role' => $role,
            'ativo' => $ativo,
        ]);

        if (! $userId) {
            return $this->failServerError('Não foi possível cadastrar o usuário.');
        }

        return $this->respondCreated(['status' => true, 'id' => $userId]);
    }

    public function get($id)
    {
        $admin = $this->requireSuperAdmin();
        if (! is_array($admin)) {
            return $admin;
        }

        $userModel = new UsuarioModel();
        $user = $userModel->select('id, nome, email, role, ativo, created_at, updated_at')->find($id);

        if (! $user) {
            return $this->failNotFound('Usuário não encontrado.');
        }

        return $this->respond($user, 200);
    }

    public function update($id)
    {
        $user = $this->requireAuth();
        if (! is_array($user)) {
            return $user;
        }

        $isAdmin = $user['role'] === 'super_admin';
        $isSelf = (int) $user['id'] === (int) $id;

        if (! $isAdmin && ! $isSelf) {
            return $this->failForbidden('Você só pode editar seus próprios dados.');
        }

        $data = $this->request->getJSON(true);
        $update = [];

        if (! empty($data['nome'])) {
            $update['nome'] = trim($data['nome']);
        }

        if (! empty($data['email'])) {
            $update['email'] = trim($data['email']);
        }

        if (! empty($data['senha'])) {
            $update['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }

        if ($isAdmin) {
            if (isset($data['role']) && in_array($data['role'], ['user', 'super_admin'], true)) {
                $update['role'] = $data['role'];
            }
            if (isset($data['ativo'])) {
                $update['ativo'] = (int) $data['ativo'];
            }
        }

        if (empty($update)) {
            return $this->failValidationErrors('Nenhum dado válido para atualizar.');
        }

        $userModel = new UsuarioModel();

        if (isset($update['email'])) {
            $existing = $userModel->where('email', $update['email'])->where('id !=', $id)->first();
            if ($existing) {
                return $this->failValidationErrors('E-mail já está em uso por outro usuário.');
            }
        }

        if (! $userModel->update($id, $update)) {
            return $this->failServerError('Erro ao atualizar usuário.');
        }

        return $this->respond(['status' => true, 'message' => 'Usuário atualizado.']);
    }

    public function toggleStatus($id)
    {
        $admin = $this->requireSuperAdmin();
        if (! is_array($admin)) {
            return $admin;
        }

        $data = $this->request->getJSON(true);
        if (! isset($data['ativo'])) {
            return $this->failValidationErrors('Campo ativo é obrigatório.');
        }

        $userModel = new UsuarioModel();
        $user = $userModel->find($id);

        if (! $user) {
            return $this->failNotFound('Usuário não encontrado.');
        }

        $userModel->update($id, ['ativo' => (int) $data['ativo']]);
        return $this->respond(['status' => true, 'message' => 'Status de usuário atualizado.']);
    }
}
