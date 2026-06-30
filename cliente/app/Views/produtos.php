<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #ffffff;
            color: #333;
        }
        header {
            background: #ffffff;
            color: #333;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            border-bottom: 1px solid #eee;
        }
        header .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .brand-logo {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            background: #333;
            font-weight: 800;
            color: #fff;
        }
        header h1 {
            margin: 0;
            font-size: 1.2rem;
        }
        .btn-top {
            background: #333;
            border: none;
            color: #fff;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }
        .page {
            max-width: 1180px;
            margin: 0 auto;
            padding: 24px;
        }
        .filtros {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 14px;
            margin: 24px 0;
        }
        .filtros select,
        .filtros input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid #dce4ee;
            background: #fff;
            font-size: 1rem;
        }
        #produtosGrid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }
        .produto-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 14px 36px rgba(46, 68, 112, .12);
            display: flex;
            flex-direction: column;
        }
        .produto-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .produto-card .info {
            padding: 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .produto-card .info h2 {
            margin: 0;
            font-size: 1.15rem;
        }
        .produto-card .info p {
            margin: 0;
            color: #55627c;
            flex: 1;
            line-height: 1.6;
        }
        .produto-card .tags {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .tag {
            background: #f5f5f5;
            color: #666;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: .85rem;
            font-weight: 600;
        }
        .btn-add {
            border: 1px solid #333;
            background: #fff;
            color: #333;
            padding: 12px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-add:hover {
            background: #333;
            color: #fff;
        }
        .btn-add:disabled,
        .btn-add[aria-disabled="true"] {
            background: #e5e7eb;
            color: #9ca3af;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }
        .btn-add:disabled:hover {
            background: #e5e7eb;
            color: #9ca3af;
        }
        .stock-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: .85rem;
            font-weight: 600;
        }
        .stock-on { background: #c3e6cb; color: #155724; }
        .stock-off { background: #f8d7da; color: #721c24; }
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: rgba(29, 60, 114, .95);
            color: #fff;
            padding: 14px 18px;
            border-radius: 12px;
            opacity: 0;
            transform: translateY(16px);
            transition: opacity .25s ease, transform .25s ease;
            pointer-events: none;
            z-index: 10;
        }
        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <header>
        <div class="brand">
            <div class="brand-logo">PED</div>
            <div>
                <h1>Escolha seus produtos</h1>
                <div>Filtre por categoria e adicione ao carrinho.</div>
            </div>
        </div>
        <a class="btn-top" href="<?= site_url('carrinho') ?>">Ver carrinho</a>
    </header>

    <main class="page">
        <div class="filtros">
            <input id="searchInput" type="search" placeholder="Buscar produto..." />
            <select id="categoriaFilter">
                <option value="">Todas as categorias</option>
            </select>
        </div>
        <div id="produtosGrid"></div>
    </main>

    <div id="toast" class="toast"></div>

    <script>
        const apiUrl = 'http://localhost/api/public/api/produtos';
        let produtos = [];

        const cartKey = 'pedidoCart';
        const toast = document.getElementById('toast');

        function getCart() {
            try {
                return JSON.parse(localStorage.getItem(cartKey) || '[]');
            } catch {
                return [];
            }
        }

        function saveCart(cart) {
            localStorage.setItem(cartKey, JSON.stringify(cart));
        }

        function showToast(message) {
            toast.textContent = message;
            toast.classList.add('show');
            clearTimeout(window.toastTimeout);
            window.toastTimeout = setTimeout(() => toast.classList.remove('show'), 2500);
        }

        async function loadProducts() {
            try {
                const response = await fetch(apiUrl);
                if (!response.ok) {
                    throw new Error('Erro ao carregar produtos');
                }
                produtos = await response.json();
                renderCategories();
                renderProducts();
            } catch (error) {
                document.getElementById('produtosGrid').innerHTML = '<div style="padding: 24px; color: #b42318;">Não foi possível carregar os produtos. Verifique se a API está acessível em ' + apiUrl + '.</div>';
            }
        }

        function renderCategories() {
            const select = document.getElementById('categoriaFilter');
            const categories = Array.from(new Set(produtos.map(p => p.categoria || 'Outros'))).sort();
            categories.forEach(categoria => {
                const option = document.createElement('option');
                option.value = categoria;
                option.textContent = categoria;
                select.appendChild(option);
            });
        }

        function formatMoney(value) {
            return Number(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }

        function renderProducts() {
            const grid = document.getElementById('produtosGrid');
            const searchText = document.getElementById('searchInput').value.toLowerCase();
            const category = document.getElementById('categoriaFilter').value;

            const filtered = produtos.filter(produto => {
                const matchesCategory = !category || produto.categoria === category;
                const matchesSearch = produto.nome.toLowerCase().includes(searchText) || (produto.descricao || '').toLowerCase().includes(searchText);
                return matchesCategory && matchesSearch;
            });

            grid.innerHTML = filtered.map(produto => `
                <article class="produto-card">
                    <img src="${produto.imagem || 'https://via.placeholder.com/260x180?text=Produto'}" alt="${produto.nome}">
                    <div class="info">
                        <h2>${produto.nome}</h2>
                        <p>${produto.descricao || 'Descrição não informada.'}</p>
                        <div class="tags">
                            <span class="tag">${produto.categoria || 'Outros'}</span>
                            <span class="tag">${formatMoney(produto.preco)}</span>
                            <span class="stock-badge ${Number(produto.stock_atual || 0) > 0 ? 'stock-on' : 'stock-off'}">
                                ${Number(produto.stock_atual || 0) > 0 ? 'Disponível' : 'Sem stock'}
                            </span>
                        </div>
                        <button class="btn-add" type="button" data-id="${produto.id}" ${Number(produto.stock_atual || 0) > 0 ? '' : 'disabled aria-disabled="true"'}>
                            ${Number(produto.stock_atual || 0) > 0 ? 'Adicionar' : 'Indisponível'}
                        </button>
                    </div>
                </article>
            `).join('');

            if (!filtered.length) {
                grid.innerHTML = '<div style="padding: 24px; color: #3f4d67;">Nenhum produto encontrado.</div>';
            }
        }

        function addToCart(produtoId) {
            try {
                const produto = produtos.find(item => String(item.id) === String(produtoId));
                const produtoIdNumber = Number(produto?.id);
                if (!produto || !produtoIdNumber) {
                    throw new Error('Produto não encontrado.');
                }

                if (Number(produto.stock_atual || 0) <= 0) {
                    throw new Error('Produto sem stock disponível.');
                }

                const cart = getCart();
                const existing = cart.find(item => String(item.id) === String(produtoIdNumber));
                if (existing) {
                    existing.quantidade += 1;
                } else {
                    cart.push({
                        id: produtoIdNumber,
                        nome: produto.nome,
                        categoria: produto.categoria,
                        preco: Number(produto.preco),
                        quantidade: 1
                    });
                }
                saveCart(cart);
                showToast(`${produto.nome} adicionado ao carrinho.`);
            } catch (error) {
                showToast(error.message || 'Não foi possível adicionar o produto.');
            }
        }

        document.getElementById('categoriaFilter').addEventListener('change', renderProducts);
        document.getElementById('searchInput').addEventListener('input', renderProducts);
        document.addEventListener('click', (event) => {
            const button = event.target.closest('.btn-add');
            if (!button) return;
            addToCart(button.getAttribute('data-id'));
        });

        loadProducts();
    </script>
</body>
</html>
