<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Vendas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f4f7fb;
            color: #1f2937;
        }
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        header h1 { margin: 0; font-size: 1.4rem; }
        .user-info { display: flex; align-items: center; gap: 12px; }
        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
        }
        nav {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            flex-wrap: wrap;
        }
        nav a {
            padding: 16px 24px;
            text-decoration: none;
            color: #6b7280;
            font-weight: 700;
            border-bottom: 3px solid transparent;
        }
        nav a.active, nav a:hover {
            color: #4f46e5;
            border-bottom-color: #4f46e5;
        }
        .page {
            max-width: 1480px;
            margin: 0 auto;
            padding: 28px;
        }
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }
        .toolbar .filters,
        .toolbar .quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }
        .date, .select {
            border: 1px solid #d1d5db;
            background: white;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 0.95rem;
            min-height: 46px;
        }
        .select { width: auto; }
        .btn {
            border: none;
            border-radius: 10px;
            padding: 12px 16px;
            min-height: 46px;
            cursor: pointer;
            font-weight: 700;
        }
        .btn-primary { background: #4f46e5; color: white; }
        .btn-dark { background: #111827; color: white; }
        .btn-soft { background: #eef2ff; color: #3730a3; }
        .grid {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            gap: 18px;
        }
        .card {
            background: white;
            border-radius: 18px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
            padding: 22px;
        }
        .card h2 { margin: 0 0 16px; font-size: 1.35rem; }
        .meta {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }
        .meta .box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 14px;
        }
        .meta .box span {
            display: block;
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        .meta .box strong { font-size: 1.2rem; color: #111827; }
        .table-wrap {
            overflow: auto;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 640px;
        }
        thead th {
            background: #f8fafc;
            color: #4b5563;
            text-align: left;
            padding: 14px;
            font-size: 0.95rem;
            border-bottom: 1px solid #e5e7eb;
        }
        tbody td {
            padding: 14px;
            border-bottom: 1px solid #eef2f7;
            vertical-align: middle;
        }
        tbody tr:hover { background: #fafafa; }
        .money { font-weight: 800; color: #111827; }
        .small { color: #6b7280; font-size: 0.9rem; }
        .chart-box { height: 420px; position: relative; }
        .empty { padding: 28px; text-align: center; color: #6b7280; }
        .footer-row {
            display: flex;
            gap: 10px;
            justify-content: space-between;
            margin-top: 14px;
            flex-wrap: wrap;
            align-items: center;
        }
        .pagination {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .pagination button {
            border: 1px solid #d1d5db;
            background: white;
            color: #111827;
            padding: 10px 14px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
        }
        .pagination button:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }
        .pagination .page-info {
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 800;
        }
        .status.novo { background: #fff7ed; color: #c2410c; }
        .status.finalizado { background: #dcfce7; color: #166534; }
        .status.cancelado { background: #fee2e2; color: #991b1b; }
        @media (max-width: 1080px) {
            .grid { grid-template-columns: 1fr; }
            .meta { grid-template-columns: 1fr; }
        }
        @media (max-width: 720px) {
            .toolbar { align-items: stretch; }
            .toolbar .filters,
            .toolbar .quick-actions { width: 100%; }
            .date, .select { width: 100%; }
            .btn { width: 100%; }
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
        <a href="http://localhost/api/public/admin/vendas" class="active">Vendas</a>
        <a href="http://localhost/api/public/admin/consumo">Consumo</a>
        <a href="http://localhost/api/public/admin/usuarios">Usuários</a>
    </nav>

    <main class="page">
        <div class="toolbar">
            <div class="filters">
                <input id="inicio" class="date" type="date" title="Data inicial">
                <span>até</span>
                <input id="fim" class="date" type="date" title="Data final">
                <select id="limite" class="select" title="Máximo de registros">
                    <option value="10">10 registros</option>
                    <option value="25">25 registros</option>
                    <option value="50">50 registros</option>
                </select>
                <button class="btn btn-primary" onclick="aplicarFiltro()">Filtrar</button>
            </div>
            <div class="quick-actions">
                <button class="btn btn-soft" onclick="ultimos7Dias()">Últimos 7 dias</button>
                <button class="btn btn-dark" onclick="limparFiltro()">Desde sempre</button>
                <button class="btn btn-primary" onclick="carregarVendas()">Atualizar</button>
            </div>
        </div>

        <section class="grid">
            <div class="card">
                <h2>Pedidos com valor e data</h2>
                <div class="meta">
                    <div class="box">
                        <span>Total vendido</span>
                        <strong id="totalVendido">R$ 0,00</strong>
                    </div>
                    <div class="box">
                        <span>Pedidos</span>
                        <strong id="totalPedidos">0</strong>
                    </div>
                    <div class="box">
                        <span>Itens vendidos</span>
                        <strong id="totalItens">0</strong>
                    </div>
                    <div class="box">
                        <span>Mesa destaque</span>
                        <strong id="mesaDestaque">-</strong>
                    </div>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Pedido</th>
                                <th>Mesa</th>
                                <th>Data</th>
                                <th>Valor total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="tabelaVendas">
                            <tr><td colspan="5" class="empty">Carregando...</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="footer-row">
                    <span class="small" id="periodoLabel"></span>
                    <div class="pagination" id="paginationControls"></div>
                </div>
            </div>

            <div class="card">
                <h2>Gráfico de vendas dos últimos 10 dias</h2>
                <div class="chart-box">
                    <canvas id="vendaChart"></canvas>
                </div>
            </div>
        </section>
    </main>

    <script>
        let vendaChart = null;
        let linhasVendas = [];
        let paginaAtual = 1;

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

        function formatMoney(value) {
            return Number(value || 0).toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
        }

        function buildUrl() {
            const params = new URLSearchParams();
            const inicio = document.getElementById('inicio').value;
            const fim = document.getElementById('fim').value;
            const limite = document.getElementById('limite').value;

            if (inicio) params.set('inicio', inicio);
            if (fim) params.set('fim', fim);
            if (limite) params.set('limite', limite);

            const query = params.toString();
            return query ? `http://localhost/api/public/api/relatorios/vendas?${query}` : 'http://localhost/api/public/api/relatorios/vendas';
        }

        function renderTabela(linhas) {
            const tbody = document.getElementById('tabelaVendas');

            if (!linhas || linhas.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="empty">Nenhum registro encontrado.</td></tr>';
                return;
            }

            tbody.innerHTML = linhas.map((linha) => `
                <tr>
                    <td>#${linha.pedido_id}</td>
                    <td>${linha.mesa_numero || '-'}</td>
                    <td>${linha.data}</td>
                    <td class="money">${formatMoney(linha.valor_total)}</td>
                    <td><span class="status ${linha.status || 'novo'}">${(linha.status || 'novo').toUpperCase()}</span></td>
                </tr>
            `).join('');
        }

        function renderGrafico(labels, values) {
            const ctx = document.getElementById('vendaChart').getContext('2d');
            if (vendaChart) {
                vendaChart.destroy();
            }

            vendaChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Vendas (R$)',
                        data: values,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.12)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#4f46e5'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'R$ ' + Number(value).toLocaleString('pt-BR');
                                }
                            }
                        }
                    }
                }
            });
        }

        function renderPagination() {
            const perPage = Number(document.getElementById('limite').value || 10);
            const totalPages = Math.max(1, Math.ceil(linhasVendas.length / perPage));
            if (paginaAtual > totalPages) paginaAtual = totalPages;
            if (paginaAtual < 1) paginaAtual = 1;

            const start = (paginaAtual - 1) * perPage;
            const visible = linhasVendas.slice(start, start + perPage);
            renderTabela(visible);

            const controls = document.getElementById('paginationControls');
            controls.innerHTML = `
                <button type="button" onclick="changePage(-1)" ${paginaAtual <= 1 ? 'disabled' : ''}>Anterior</button>
                <span class="page-info">Página ${paginaAtual} de ${totalPages}</span>
                <button type="button" onclick="changePage(1)" ${paginaAtual >= totalPages ? 'disabled' : ''}>Próxima</button>
            `;
        }

        function changePage(step) {
            paginaAtual += step;
            renderPagination();
        }

        async function carregarVendas() {
            try {
                const response = await fetch(buildUrl());
                const data = await response.json();

                linhasVendas = data.linhas || [];
                paginaAtual = 1;

                document.getElementById('totalVendido').textContent = formatMoney(data.meta?.total_vendido || 0);
                document.getElementById('totalPedidos').textContent = data.meta?.pedidos || 0;
                document.getElementById('totalItens').textContent = data.meta?.itens_vendidos || 0;
                document.getElementById('mesaDestaque').textContent = data.meta?.mesa_lider ? `Mesa ${data.meta.mesa_lider}` : '-';
                document.getElementById('periodoLabel').textContent = data.meta?.periodo || '';

                renderPagination();
                renderGrafico(data.labels || [], data.vendas || []);

                const user = JSON.parse(localStorage.getItem('admin_user') || '{}');
                document.getElementById('userName').textContent = user.nome || 'Admin';
            } catch (error) {
                console.error(error);
                document.getElementById('tabelaVendas').innerHTML = '<tr><td colspan="5" class="empty">Erro ao carregar vendas.</td></tr>';
            }
        }

        function aplicarFiltro() {
            carregarVendas();
        }

        function formatDateInputLocal(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function ultimos7Dias() {
            const hoje = new Date();
            const seteDiasAtras = new Date();
            seteDiasAtras.setDate(hoje.getDate() - 6);
            document.getElementById('inicio').value = formatDateInputLocal(seteDiasAtras);
            document.getElementById('fim').value = formatDateInputLocal(hoje);
            document.getElementById('limite').value = '10';
            carregarVendas();
        }

        function limparFiltro() {
            document.getElementById('inicio').value = '';
            document.getElementById('fim').value = '';
            document.getElementById('limite').value = '10';
            carregarVendas();
        }

        checkAuth();
        carregarVendas();
    </script>
</body>
</html>
