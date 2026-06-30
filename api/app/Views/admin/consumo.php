<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Consumo</title>
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
        header h1 {
            margin: 0;
            font-size: 1.4rem;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
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
        nav a.active,
        nav a:hover {
            color: #4f46e5;
            border-bottom-color: #4f46e5;
        }
        .page {
            max-width: 1500px;
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
        .field,
        .select {
            border: 1px solid #d1d5db;
            background: white;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 0.95rem;
            min-height: 46px;
        }
        .field { width: 260px; }
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
        .btn-success { background: #059669; color: white; }
        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 18px;
        }
        .card {
            background: white;
            border-radius: 18px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
            padding: 22px;
        }
        .card h2 {
            margin: 0 0 8px;
            font-size: 1.35rem;
        }
        .subtitle {
            margin: 0 0 16px;
            color: #6b7280;
            font-size: 0.95rem;
        }
        .table-wrap {
            overflow: auto;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1020px;
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
        .produto-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .thumb {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid #e5e7eb;
            background: #f8fafc;
        }
        .produto-nome {
            font-weight: 800;
            color: #111827;
        }
        .produto-meta {
            color: #6b7280;
            font-size: 0.9rem;
        }
        .status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 94px;
            padding: 8px 12px;
            border-radius: 999px;
            font-weight: 800;
            font-size: 0.85rem;
        }
        .status.ok { background: #dcfce7; color: #166534; }
        .status.bad { background: #fee2e2; color: #991b1b; }
        .num-input {
            width: 100px;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            font-size: 0.95rem;
        }
        .btn-save {
            background: #111827;
            color: white;
            padding: 10px 14px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 700;
        }
        .small {
            color: #6b7280;
            font-size: 0.9rem;
        }
        .empty {
            padding: 28px;
            text-align: center;
            color: #6b7280;
        }
        .footer-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 14px;
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
        .toast {
            position: fixed;
            right: 20px;
            bottom: 20px;
            background: #111827;
            color: white;
            padding: 14px 16px;
            border-radius: 12px;
            opacity: 0;
            transform: translateY(16px);
            transition: .25s ease;
            pointer-events: none;
            z-index: 20;
        }
        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
            margin-top: 14px;
        }
        .form-grid .full {
            grid-column: 1 / -1;
        }
        .text-area {
            width: 100%;
            min-height: 92px;
            resize: vertical;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 0.95rem;
            background: white;
        }
        .add-panel {
            display: none;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px;
            margin-top: 14px;
            background: #f9fafb;
        }
        .add-panel.open {
            display: block;
        }
        .actions-row {
            margin-top: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        @media (max-width: 720px) {
            .toolbar { align-items: stretch; }
            .toolbar .filters,
            .toolbar .quick-actions { width: 100%; }
            .field { width: 100%; }
            .select,
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
        <a href="http://localhost/api/public/admin/vendas">Vendas</a>
        <a href="http://localhost/api/public/admin/consumo" class="active">Consumo</a>
        <a href="http://localhost/api/public/admin/usuarios">Usuários</a>
    </nav>

    <main class="page">
        <div class="toolbar">
            <div class="filters">
                <select id="categoryFilter" class="select" onchange="applyFilters()">
                    <option value="">Categorias: Todos</option>
                </select>
                <input id="searchInput" class="field" type="search" placeholder="Filtrar produto..." oninput="applyFilters()">
                <select id="perPage" class="select" onchange="applyFilters()">
                    <option value="10">Mostrando 10 registros por página</option>
                    <option value="25" selected>Mostrando 25 registros por página</option>
                    <option value="50">Mostrando 50 registros por página</option>
                </select>
            </div>
            <div class="quick-actions">
                <button class="btn btn-dark" onclick="loadProducts()">Atualizar</button>
                <button class="btn btn-success" onclick="toggleAddPanel()">Adicionar produto</button>
                <button class="btn btn-soft" onclick="showOnlyAvailable()">Só disponíveis</button>
            </div>
        </div>

        <section class="grid">
            <div class="card">
                <h2>Catálogo com estoque editável</h2>
                <p class="subtitle">Altere o stock atual e a quantidade diretamente na tabela. A disponibilidade só fica ativa quando o stock atual for maior que zero.</p>
                <div id="addProductPanel" class="add-panel">
                    <h3 style="margin:0 0 8px;">Novo produto</h3>
                    <p class="subtitle" style="margin-bottom: 10px;">Preencha os mesmos campos usados no arquivo produtos.json.</p>
                    <form id="addProductForm">
                        <div class="form-grid">
                            <input id="novoNome" class="field" type="text" placeholder="Nome" required>
                            <input id="novaCategoria" class="field" type="text" placeholder="Categoria" required>
                            <input id="novoPreco" class="field" type="number" min="0" step="0.01" placeholder="Preco" required>
                            <input id="novoStock" class="field" type="number" min="0" step="1" placeholder="Stock atual" value="0" required>
                            <input id="novaQuantidade" class="field" type="number" min="0" step="1" placeholder="Quantidade" value="0" required>
                            <input id="novaImagem" class="field full" type="url" placeholder="URL da imagem" required>
                            <textarea id="novaDescricao" class="text-area full" placeholder="Descricao" required></textarea>
                        </div>
                        <div class="actions-row">
                            <button class="btn btn-primary" type="submit">Salvar novo produto</button>
                            <button class="btn" style="background:#e5e7eb;color:#111827;" type="button" onclick="closeAddPanel()">Cancelar</button>
                        </div>
                    </form>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th>Disponível</th>
                                <th>Stock Atual</th>
                                <th>Quantidade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="consumoTable">
                            <tr><td colspan="6" class="empty">Carregando...</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="footer-row">
                    <span class="small" id="resultadoLabel"></span>
                    <span class="small">Os dados são salvos no catálogo JSON da API.</span>
                </div>
            </div>
        </section>
    </main>

    <div id="toast" class="toast"></div>

    <script>
        let allData = [];
        let filteredData = [];
        let onlyAvailable = false;
        let currentPage = 1;
        let addPanelOpen = false;

        const toast = document.getElementById('toast');

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

        function showToast(message) {
            toast.textContent = message;
            toast.classList.add('show');
            clearTimeout(window.toastTimeout);
            window.toastTimeout = setTimeout(() => toast.classList.remove('show'), 2200);
        }

        function formatMoney(value) {
            return Number(value || 0).toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
        }

        async function loadProducts() {
            try {
                const response = await fetch('http://localhost/api/public/api/produtos');
                const data = await response.json();

                allData = (data || []).map((item) => ({
                    ...item,
                    produto: item.nome,
                    stock_atual: Number(item.stock_atual || 0),
                    quantidade: Number(item.quantidade || 0),
                    disponivel: Number(item.stock_atual || 0) > 0
                }));

                const user = JSON.parse(localStorage.getItem('admin_user') || '{}');
                document.getElementById('userName').textContent = user.nome || 'Admin';

                renderCategories();
                applyFilters();
            } catch (error) {
                console.error(error);
                document.getElementById('consumoTable').innerHTML = '<tr><td colspan="6" class="empty">Erro ao carregar produtos.</td></tr>';
            }
        }

        function toggleAddPanel() {
            addPanelOpen = !addPanelOpen;
            const panel = document.getElementById('addProductPanel');
            panel.classList.toggle('open', addPanelOpen);
        }

        function closeAddPanel() {
            addPanelOpen = false;
            document.getElementById('addProductPanel').classList.remove('open');
        }

        function renderCategories() {
            const select = document.getElementById('categoryFilter');
            const current = select.value;
            const categories = [...new Set(allData.map(item => item.categoria).filter(Boolean))].sort();
            select.innerHTML = '<option value="">Categorias: Todos</option>';
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                select.appendChild(option);
            });
            select.value = current;
        }

        function applyFilters() {
            const category = document.getElementById('categoryFilter').value;
            const search = document.getElementById('searchInput').value.toLowerCase().trim();
            const perPage = Number(document.getElementById('perPage').value || 25);

            filteredData = allData.filter(item => {
                const matchesCategory = !category || item.categoria === category;
                const matchesSearch = !search || (item.nome || '').toLowerCase().includes(search) || (item.categoria || '').toLowerCase().includes(search);
                const matchesAvailability = !onlyAvailable || Number(item.stock_atual || 0) > 0;
                return matchesCategory && matchesSearch && matchesAvailability;
            });

            const totalPages = Math.max(1, Math.ceil(filteredData.length / perPage));
            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            const start = (currentPage - 1) * perPage;
            const visible = filteredData.slice(start, start + perPage);
            renderTable(visible);
            document.getElementById('resultadoLabel').textContent = `Mostrando ${visible.length} de ${filteredData.length} registros.`;
            renderPagination(totalPages, currentPage);
        }

        function renderPagination(totalPages, page) {
            const label = document.getElementById('resultadoLabel');
            const footerRow = document.querySelector('.footer-row');
            let pagination = document.getElementById('paginationControls');

            if (!pagination) {
                pagination = document.createElement('div');
                pagination.id = 'paginationControls';
                pagination.className = 'pagination';
                footerRow.appendChild(pagination);
            }

            pagination.innerHTML = `
                <button type="button" onclick="changePage(-1)" ${page <= 1 ? 'disabled' : ''}>Anterior</button>
                <span class="page-info">Página ${page} de ${totalPages}</span>
                <button type="button" onclick="changePage(1)" ${page >= totalPages ? 'disabled' : ''}>Próxima</button>
            `;

            if (label && filteredData.length === 0) {
                label.textContent = 'Nenhum registro encontrado.';
            }
        }

        function changePage(step) {
            const perPage = Number(document.getElementById('perPage').value || 25);
            const totalPages = Math.max(1, Math.ceil(filteredData.length / perPage));
            currentPage += step;
            if (currentPage < 1) currentPage = 1;
            if (currentPage > totalPages) currentPage = totalPages;
            applyFilters();
        }

        function showOnlyAvailable() {
            onlyAvailable = !onlyAvailable;
            document.querySelector('.btn-soft').textContent = onlyAvailable ? 'Mostrando só disponíveis' : 'Só disponíveis';
            currentPage = 1;
            applyFilters();
        }

        function renderTable(data) {
            const tbody = document.getElementById('consumoTable');

            if (!data.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="empty">Nenhum produto encontrado.</td></tr>';
                return;
            }

            tbody.innerHTML = data.map(item => {
                const disponivel = Number(item.stock_atual || 0) > 0;
                const statusClass = disponivel ? 'ok' : 'bad';
                const statusText = disponivel ? 'Sim' : 'Não';
                const imageSrc = item.imagem || 'https://via.placeholder.com/52x52?text=P';

                return `
                    <tr>
                        <td>
                            <div class="produto-cell">
                                <img class="thumb" src="${imageSrc}" alt="${item.nome}">
                                <div>
                                    <div class="produto-nome">${item.nome}</div>
                                    <div class="produto-meta">${formatMoney(item.preco)}</div>
                                </div>
                            </div>
                        </td>
                        <td>${item.categoria || '-'}</td>
                        <td><span class="status ${statusClass}">${statusText}</span></td>
                        <td><input class="num-input" type="number" min="0" value="${Number(item.stock_atual || 0)}" data-field="stock" data-id="${item.id}"></td>
                        <td><input class="num-input" type="number" min="0" value="${Number(item.quantidade || 0)}" data-field="quantidade" data-id="${item.id}"></td>
                        <td><button class="btn-save" onclick="salvarProduto(${item.id})">Salvar</button></td>
                    </tr>
                `;
            }).join('');
        }

        function getInputsById(id) {
            const stockInput = document.querySelector(`input[data-id="${id}"][data-field="stock"]`);
            const quantidadeInput = document.querySelector(`input[data-id="${id}"][data-field="quantidade"]`);
            return { stockInput, quantidadeInput };
        }

        async function salvarProduto(id) {
            const { stockInput, quantidadeInput } = getInputsById(id);
            if (!stockInput || !quantidadeInput) return;

            const payload = {
                stock_atual: Number(stockInput.value || 0),
                quantidade: Number(quantidadeInput.value || 0)
            };

            try {
                const response = await fetch(`http://localhost/api/public/api/produtos/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const result = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(result.messages?.error || result.message || 'Não foi possível salvar');
                }

                showToast('Produto atualizado com sucesso');
                await loadProducts();
            } catch (error) {
                console.error(error);
                showToast(error.message || 'Erro ao salvar produto');
            }
        }

        async function criarProduto(event) {
            event.preventDefault();

            const payload = {
                nome: document.getElementById('novoNome').value.trim(),
                descricao: document.getElementById('novaDescricao').value.trim(),
                categoria: document.getElementById('novaCategoria').value.trim(),
                preco: Number(document.getElementById('novoPreco').value || 0),
                imagem: document.getElementById('novaImagem').value.trim(),
                stock_atual: Number(document.getElementById('novoStock').value || 0),
                quantidade: Number(document.getElementById('novaQuantidade').value || 0)
            };

            if (!payload.nome || !payload.descricao || !payload.categoria || !payload.imagem) {
                showToast('Preencha todos os campos obrigatorios');
                return;
            }

            try {
                const response = await fetch('http://localhost/api/public/api/produtos', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const result = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(result.messages?.error || result.message || 'Nao foi possivel criar o produto');
                }

                document.getElementById('addProductForm').reset();
                document.getElementById('novoStock').value = '0';
                document.getElementById('novaQuantidade').value = '0';
                closeAddPanel();
                showToast('Produto criado com sucesso');
                await loadProducts();
            } catch (error) {
                console.error(error);
                showToast(error.message || 'Erro ao criar produto');
            }
        }

        document.getElementById('addProductForm').addEventListener('submit', criarProduto);

        checkAuth();
        loadProducts();
    </script>
</body>
</html>
