<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
    <style>
        body { margin: 0; font-family: Arial, Helvetica, sans-serif; background: #f4f7fb; color: #17233d; }
        header { background: #1d3c72; color: #fff; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; }
        header h1 { margin: 0; font-size: 1.2rem; }
        .btn-top { background: #ffb03b; border: none; color: #1b1f2e; padding: 12px 18px; border-radius: 999px; text-decoration: none; font-weight: 700; cursor: pointer; }
        .page { max-width: 980px; margin: 0 auto; padding: 24px; }
        .cart-box { background: #fff; border-radius: 24px; padding: 24px; box-shadow: 0 18px 40px rgba(31, 57, 97, .12); }
        .cart-box h2 { margin-top: 0; }
        .cart-empty { padding: 42px; text-align: center; color: #566382; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 12px; text-align: left; }
        th { color: #55627c; font-weight: 700; font-size: .95rem; }
        td { border-top: 1px solid #e5ecf4; vertical-align: middle; }
        .qty-control { display: inline-flex; align-items: center; gap: 8px; }
        .qty-control button { width: 32px; height: 32px; border-radius: 10px; border: 1px solid #ced7e4; background: #fff; cursor: pointer; font-weight: 700; }
        .remove-btn { color: #d23f57; font-weight: 700; border: none; background: transparent; cursor: pointer; }
        .total-row td { font-size: 1.1rem; font-weight: 700; border-top: 2px solid #dce4ee; }
        .actions { display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-top: 24px; }
        .actions a, .actions button { padding: 14px 22px; border-radius: 999px; font-weight: 700; text-decoration: none; border: none; cursor: pointer; }
        .actions a { background: #ffffff; color: #1d3c72; border: 1px solid #dce4ee; }
        .actions button { background: #ffb03b; color: #1b1f2e; }
    </style>
</head>
<body>
    <header>
        <h1>Meu carrinho</h1>
        <a class="btn-top" href="<?= site_url('produtos') ?>">Continuar comprando</a>
    </header>
    <main class="page">
        <div class="cart-box">
            <h2>Itens no pedido</h2>
            <div id="cartContent"></div>
            <div class="actions" id="cartActions"></div>
        </div>
    </main>

    <script>
        const cartKey = 'pedidoCart';

        function getCart() {
            try {
                return JSON.parse(localStorage.getItem(cartKey) || '[]');
            } catch {
                return [];
            }
        }

        function saveCart(cart) {
            localStorage.setItem(cartKey, JSON.stringify(cart));
            renderCart();
        }

        function formatMoney(value) {
            return Number(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }

        function changeQuantity(id, delta) {
            const cart = getCart();
            const item = cart.find(row => row.id === id);
            if (!item) return;
            item.quantidade += delta;
            if (item.quantidade < 1) {
                removeItem(id);
                return;
            }
            saveCart(cart);
        }

        function removeItem(id) {
            const cart = getCart().filter(item => item.id !== id);
            saveCart(cart);
        }

        function renderCart() {
            const cart = getCart();
            const content = document.getElementById('cartContent');
            const actions = document.getElementById('cartActions');

            if (!cart.length) {
                content.innerHTML = '<div class="cart-empty">Seu carrinho está vazio. Adicione produtos na tela de produtos.</div>';
                actions.innerHTML = '<a href="<?= site_url('produtos') ?>">Abrir produtos</a>';
                return;
            }

            const rows = cart.map(item => `
                <tr>
                    <td>${item.nome}</td>
                    <td>${item.categoria || '—'}</td>
                    <td>${formatMoney(item.preco)}</td>
                    <td>
                        <div class="qty-control">
                            <button type="button" onclick="changeQuantity(${item.id}, -1)">−</button>
                            <span>${item.quantidade}</span>
                            <button type="button" onclick="changeQuantity(${item.id}, 1)">+</button>
                        </div>
                    </td>
                    <td>${formatMoney(item.preco * item.quantidade)}</td>
                    <td><button class="remove-btn" type="button" onclick="removeItem(${item.id})">Remover</button></td>
                </tr>
            `).join('');

            const total = cart.reduce((sum, item) => sum + item.preco * item.quantidade, 0);

            content.innerHTML = `
                <table>
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        ${rows}
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="4">Total</td>
                            <td colspan="2">${formatMoney(total)}</td>
                        </tr>
                    </tfoot>
                </table>
            `;

            actions.innerHTML = `
                <a href="<?= site_url('produtos') ?>">Adicionar mais</a>
                <button type="button" onclick="window.location.href='<?= site_url('checkout') ?>'">Finalizar pedido</button>
            `;
        }

        renderCart();
    </script>
</body>
</html>
