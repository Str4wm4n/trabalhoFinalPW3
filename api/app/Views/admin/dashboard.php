<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Painel Administrativo</title>
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
        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
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
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
        }
        .card h3 {
            margin: 0 0 10px;
            color: #666;
            font-size: 0.95rem;
        }
        .card .value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #667eea;
            margin: 0;
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
        <a href="http://localhost/api/public/admin/dashboard" class="active">Dashboard</a>
        <a href="http://localhost/api/public/admin/vendas">Vendas</a>
        <a href="http://localhost/api/public/admin/consumo">Consumo</a>
        <a href="http://localhost/api/public/admin/usuarios">Usuários</a>
    </nav>
    <div class="container">
        <div class="cards" id="statsCards">
            <div class="card">
                <h3>Total de Vendas (Últimos 10 dias)</h3>
                <p class="value" id="totalVendas">R$ 0,00</p>
            </div>
            <div class="card">
                <h3>Pedidos Realizados</h3>
                <p class="value" id="totalPedidos">0</p>
            </div>
            <div class="card">
                <h3>Itens Vendidos</h3>
                <p class="value" id="totalItens">0</p>
            </div>
        </div>
    </div>

    <script>
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

        async function loadStats() {
            try {
                const response = await fetch('http://localhost/api/public/api/relatorios/vendas');
                const data = await response.json();

                document.getElementById('totalVendas').textContent = (data.meta.total_vendido || 0).toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });
                document.getElementById('totalPedidos').textContent = data.meta.pedidos || 0;
                document.getElementById('totalItens').textContent = data.meta.itens_vendidos || 0;

                const user = JSON.parse(localStorage.getItem('admin_user') || '{}');
                document.getElementById('userName').textContent = user.nome || 'Admin';
            } catch (error) {
                console.error(error);
            }
        }

        checkAuth();
        loadStats();
    </script>
</body>
</html>
