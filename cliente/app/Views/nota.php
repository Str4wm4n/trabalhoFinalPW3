<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota do pedido</title>
    <style>
        body { margin: 0; font-family: Arial, Helvetica, sans-serif; background: #f4f7fb; color: #17233d; }
        header { background: #1d3c72; color: #fff; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; }
        header h1 { margin: 0; font-size: 1.2rem; }
        .btn-top { background: #ffb03b; border: none; color: #1b1f2e; padding: 12px 18px; border-radius: 999px; text-decoration: none; font-weight: 700; cursor: pointer; }
        .page { max-width: 820px; margin: 0 auto; padding: 24px; }
        .box { background: #fff; border-radius: 24px; padding: 28px; box-shadow: 0 18px 40px rgba(31, 57, 97, .12); }
        .box h2 { margin-top: 0; }
        .order-meta { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-top: 16px; }
        .order-meta span { display: block; background: #eef2ff; color: #1d3c72; padding: 12px 16px; border-radius: 14px; font-weight: 700; }
        .items { margin-top: 22px; }
        .item { display: grid; grid-template-columns: 1fr auto; gap: 12px; align-items: center; padding: 16px 0; border-bottom: 1px solid #eef2f8; }
        .item:last-child { border-bottom: none; }
        .item-name { font-weight: 700; }
        .total-box { margin-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .total-label { font-size: 1.1rem; font-weight: 700; }
        .empty { padding: 42px; text-align: center; color: #55627c; }
    </style>
</head>
<body>
    <header>
        <h1>Nota do pedido</h1>
        <button class="btn-top" type="button" onclick="startNewOrder()">Novo pedido</button>
    </header>
    <main class="page">
        <div class="box">
            <h2>Resumo da nota</h2>
            <div id="notaContent"></div>
        </div>
    </main>

    <script>
        const lastOrderKey = 'pedidoLastOrder';
        const cartKey = 'pedidoCart';

        function getLastOrder() {
            try {
                return JSON.parse(localStorage.getItem(lastOrderKey) || 'null');
            } catch {
                return null;
            }
        }

        function startNewOrder() {
            localStorage.removeItem(lastOrderKey);
            localStorage.removeItem(cartKey);
            window.location.href = '<?= site_url('produtos') ?>';
        }

        function formatMoney(value) {
            return Number(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }

        const content = document.getElementById('notaContent');
        const query = new URLSearchParams(window.location.search);
        const orderId = query.get('id');
        const pedido = getLastOrder();

        if (!pedido || String(pedido.id_pedido) !== String(orderId)) {
            content.innerHTML = '<div class="empty">Não foi possível encontrar a nota do pedido. Volte para a tela de produtos e tente novamente.</div>';
        } else {
            content.innerHTML = `
                <div class="order-meta">
                    <span>Pedido: ${pedido.id_pedido}</span>
                    <span>Mesa: ${pedido.mesa_numero || 'Não informada'}</span>
                    <span>Cliente: ${pedido.cliente || 'Não informado'}</span>
                    <span>Contato: ${pedido.contato || 'Não informado'}</span>
                </div>
                <div class="items">
                    ${pedido.itens.map(item => `
                        <div class="item">
                            <div>
                                <div class="item-name">${item.nome}</div>
                                <div>${item.categoria || 'Sem categoria'}</div>
                            </div>
                            <div>${formatMoney(item.preco * item.quantidade)}</div>
                        </div>
                    `).join('')}
                </div>
                <div class="total-box">
                    <div class="total-label">Valor total</div>
                    <div>${formatMoney(pedido.total)}</div>
                </div>
            `;
        }
    </script>
</body>
</html>
