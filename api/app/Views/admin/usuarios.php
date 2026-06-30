<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - Painel Administrativo</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f4f7fb;
        }
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }
        nav {
            background: white;
            padding: 0;
            border-bottom: 1px solid #eee;
            display: flex;
            gap: 0;
        }
        nav a {
            padding: 16px 24px;
            text-decoration: none;
            color: #666;
            font-weight: 600;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
        }
        nav a:hover, nav a.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header-section h2 {
            margin: 0;
            color: #333;
        }
        .btn-criar {
            padding: 12px 24px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
        }
        .btn-criar:hover {
            background: #5568d3;
        }
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background: #f8f9fa;
            border-bottom: 2px solid #eee;
        }
        th {
            padding: 16px;
            text-align: left;
            font-weight: 700;
            color: #666;
        }
        td {
            padding: 16px;
            border-bottom: 1px solid #eee;
            color: #555;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .btn-action {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .btn-editar {
            background: #667eea;
            color: white;
        }
        .btn-bloquear {
            background: #dc3545;
            color: white;
        }
        .btn-desbloquear {
            background: #28a745;
            color: white;
        }
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-ativo {
            background: #c3e6cb;
            color: #155724;
        }
        .badge-inativo {
            background: #f8d7da;
            color: #721c24;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal.show {
            display: flex;
        }
        .modal-content {
            background: white;
            padding: 40px;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
        }
        .modal-content h3 {
            margin-top: 0;
        }
        .field {
            margin-bottom: 20px;
        }
        .field label {
            display: block;
            font-weight: 700;
            margin-bottom: 8px;
            color: #555;
        }
        .field input, .field select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        .field input:focus, .field select:focus {
            outline: none;
            border-color: #667eea;
        }
        .modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .modal-actions button {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-salvar {
            background: #667eea;
            color: white;
        }
        .btn-cancelar {
            background: #ddd;
            color: #333;
        }
    </style>
</head>
<body>
    <header>
        <h1>Painel Administrativo</h1>
        <div class="user-info">
            <span id="userName">Admin</span>
            <button class="btn-logout" onclick="logout()">Sair</button>
        </div>
    </header>
    <nav>
        <a href="http://localhost/api/public/admin/dashboard">Dashboard</a>
        <a href="http://localhost/api/public/admin/vendas">Vendas</a>
        <a href="http://localhost/api/public/admin/consumo">Consumo</a>
        <a href="http://localhost/api/public/admin/usuarios" class="active">Usuários</a>
    </nav>
    <div class="container">
        <div class="header-section">
            <h2>Gerenciamento de Usuários</h2>
            <button class="btn-criar" onclick="abrirNovoUsuario()">+ Novo Usuário</button>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Função</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="usuariosTable">
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalUsuario" class="modal">
        <div class="modal-content">
            <h3 id="modalTitulo">Novo Usuário</h3>
            <form id="usuarioForm">
                <div class="field">
                    <label for="nome">Nome</label>
                    <input id="nome" type="text" required>
                </div>
                <div class="field">
                    <label for="email">E-mail</label>
                    <input id="email" type="email" required>
                </div>
                <div class="field">
                    <label for="senha">Senha</label>
                    <input id="senha" type="password" required>
                </div>
                <div class="field">
                    <label for="role">Função</label>
                    <select id="role" required>
                        <option value="">Selecione uma função</option>
                        <option value="user">Usuário</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button class="btn-salvar" type="submit">Salvar</button>
                    <button class="btn-cancelar" type="button" onclick="fecharModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let usuarioEmEdicao = null;

        function checkAuth() {
            const token = localStorage.getItem('admin_token');
            if (!token) {
                window.location.href = 'http://localhost/api/public/admin/login';
            }
        }

        function logout() {
            localStorage.removeItem('admin_token');
            localStorage.removeItem('admin_user');
            window.location.href = 'http://localhost/api/public/admin/login';
        }

        async function loadUsuarios() {
            try {
                const token = localStorage.getItem('admin_token');
                const response = await fetch('http://localhost/api/public/api/usuarios', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                if (!response.ok) {
                    const error = await response.json().catch(() => ({}));
                    throw new Error(error.messages?.error || error.message || 'Você não tem permissão para listar usuários.');
                }

                const data = await response.json();

                const user = JSON.parse(localStorage.getItem('admin_user') || '{}');
                document.getElementById('userName').textContent = user.nome || 'Admin';

                renderTabela(data.data || data);
            } catch (error) {
                console.error(error);
                alert(error.message || 'Erro ao carregar usuários');
            }
        }

        function renderTabela(usuarios) {
            const tbody = document.getElementById('usuariosTable');
            tbody.innerHTML = '';

            usuarios.forEach(usuario => {
                const row = document.createElement('tr');
                const isAtivo = Number(usuario.ativo) === 1;
                const statusBadge = isAtivo
                    ? '<span class="badge badge-ativo">Ativo</span>'
                    : '<span class="badge badge-inativo">Inativo</span>';
                const btnToggle = isAtivo
                    ? '<button class="btn-action btn-bloquear" onclick="bloquearUsuario(' + usuario.id + ')">Bloquear</button>'
                    : '<button class="btn-action btn-desbloquear" onclick="desbloquearUsuario(' + usuario.id + ')">Desbloquear</button>';

                row.innerHTML = `
                    <td>${usuario.nome}</td>
                    <td>${usuario.email}</td>
                    <td>${usuario.role || 'N/A'}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <div class="actions">
                            <button class="btn-action btn-editar" onclick="editarUsuario(${usuario.id}, '${usuario.nome}', '${usuario.email}', '${usuario.role}')">Editar</button>
                            ${btnToggle}
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function abrirNovoUsuario() {
            usuarioEmEdicao = null;
            document.getElementById('modalTitulo').textContent = 'Novo Usuário';
            document.getElementById('usuarioForm').reset();
            document.getElementById('senha').required = true;
            document.getElementById('modalUsuario').classList.add('show');
        }

        function editarUsuario(id, nome, email, role) {
            usuarioEmEdicao = id;
            document.getElementById('modalTitulo').textContent = 'Editar Usuário';
            document.getElementById('nome').value = nome;
            document.getElementById('email').value = email;
            document.getElementById('senha').value = '';
            document.getElementById('senha').required = false;
            document.getElementById('role').value = role;
            document.getElementById('modalUsuario').classList.add('show');
        }

        function fecharModal() {
            document.getElementById('modalUsuario').classList.remove('show');
            usuarioEmEdicao = null;
        }

        document.getElementById('usuarioForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const token = localStorage.getItem('admin_token');
            const body = {
                nome: document.getElementById('nome').value,
                email: document.getElementById('email').value,
                role: document.getElementById('role').value
            };

            const senha = document.getElementById('senha').value;
            if (senha) {
                body.senha = senha;
            }

            try {
                const url = usuarioEmEdicao
                    ? `http://localhost/api/public/api/usuarios/${usuarioEmEdicao}`
                    : 'http://localhost/api/public/api/usuarios';
                const method = usuarioEmEdicao ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(body)
                });

                if (response.ok) {
                    fecharModal();
                    loadUsuarios();
                } else {
                    const error = await response.json().catch(() => ({}));
                    alert(error.messages?.error || error.message || 'Erro ao salvar usuário');
                }
            } catch (error) {
                console.error(error);
                alert('Erro ao salvar usuário');
            }
        });

        async function bloquearUsuario(id) {
            if (!confirm('Tem certeza que deseja bloquear este usuário?')) return;
            toggleUsuario(id, false);
        }

        async function desbloquearUsuario(id) {
            if (!confirm('Tem certeza que deseja desbloquear este usuário?')) return;
            toggleUsuario(id, true);
        }

        async function toggleUsuario(id, ativo) {
            const token = localStorage.getItem('admin_token');
            try {
                const response = await fetch(`http://localhost/api/public/api/usuarios/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({ ativo: ativo })
                });

                if (response.ok) {
                    loadUsuarios();
                } else {
                    const error = await response.json().catch(() => ({}));
                    alert(error.messages?.error || error.message || 'Erro ao atualizar usuário');
                }
            } catch (error) {
                console.error(error);
                alert('Erro ao atualizar usuário');
            }
        }

        checkAuth();
        loadUsuarios();
    </script>
</body>
</html>
