<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body { margin: 0; font-family: Arial, Helvetica, sans-serif; background: #f4f7fb; color: #17233d; }
        header { background: #1d3c72; color: #fff; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; }
        header h1 { margin: 0; font-size: 1.2rem; }
        .btn-top { background: #ffb03b; border: none; color: #1b1f2e; padding: 12px 18px; border-radius: 999px; text-decoration: none; font-weight: 700; cursor: pointer; }
        .page { max-width: 820px; margin: 0 auto; padding: 24px; }
        .box { background: #fff; border-radius: 24px; padding: 28px; box-shadow: 0 18px 40px rgba(31, 57, 97, .12); }
        .box h2 { margin-top: 0; }
        .mesa-badge {
            display: inline-block;
            margin-bottom: 16px;
            background: #eef2ff;
            color: #1d3c72;
            border-radius: 999px;
            padding: 8px 14px;
            font-weight: 700;
            font-size: 0.9rem;
        }
        .field { display: grid; gap: 10px; margin-bottom: 18px; }
        .field label { font-weight: 700; }
        .field input { width: 100%; padding: 14px 16px; border-radius: 14px; border: 1px solid #dce4ee; }
        .summary { margin-top: 30px; }
        .summary-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #eef2f8; }
        .summary-item.total { font-weight: 700; }
        .submit-button { width: 100%; background: #ffb03b; border: none; color: #1b1f2e; padding: 16px 20px; border-radius: 14px; font-size: 1rem; font-weight: 700; cursor: pointer; }
        .empty { padding: 42px; text-align: center; color: #55627c; }
        .alert { margin-top: 18px; padding: 14px 16px; border-radius: 14px; background: #ffe9dd; color: #7a3a16; display: none; }
    </style>
</head>
<body>
    <header>
        <h1>Confirme seu pedido</h1>
        <a class="btn-top" href="<?= site_url('carrinho') ?>">Voltar ao carrinho</a>
    </header>
    <main class="page">
        <div class="box">
            <h2>Resumo do pedido</h2>
            <div id="totemBadge" class="mesa-badge"></div>
            <div id="checkoutContent"></div>
            <form id="checkoutForm">
                <div class="field">
                    <label for="customerName">Nome do cliente</label>
                    <input id="customerName" type="text" placeholder="Digite seu nome" required>
                </div>
                <div class="field">
                    <label for="customerPhone">Telefone ou e-mail</label>
                    <input id="customerPhone" type="text" placeholder="Telefone ou e-mail" required>
                </div>
                <button class="submit-button" type="submit">Finalizar pedido</button>
                <div id="alertBox" class="alert"></div>
            </form>
        </div>
    </main>

    <script>
        const cartKey = 'pedidoCart';
        const lastOrderKey = 'pedidoLastOrder';
        const totemKey = 'pedidoTotem';

        function getCart() {
            try {
                return JSON.parse(localStorage.getItem(cartKey) || '[]');
            } catch {
                return [];
            }
        }

        function saveLastOrder(order) {
            localStorage.setItem(lastOrderKey, JSON.stringify(order));
        }

        function clearCart() {
            localStorage.removeItem(cartKey);
        }

        function getOrCreateTotemNumero() {
            const totem = Number(localStorage.getItem(totemKey) || 0);
            if (Number.isInteger(totem) && totem > 0) {
                return totem;
            }

            const novoTotem = Math.floor(1000 + Math.random() * 9000);
            localStorage.setItem(totemKey, String(novoTotem));
            return novoTotem;
        }

        function formatMoney(value) {
            return Number(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }

        const content = document.getElementById('checkoutContent');
        const alertBox = document.getElementById('alertBox');
        const form = document.getElementById('checkoutForm');
        const totemBadge = document.getElementById('totemBadge');
        const totemNumero = getOrCreateTotemNumero();

        if (!totemNumero) {
            alert('Nao foi possivel identificar o totem deste navegador.');
            window.location.href = '<?= site_url('/') ?>';
        } else {
            totemBadge.textContent = `Totem ${totemNumero}`;
        }

        function renderCheckout() {
            const cart = getCart();
            if (!cart.length) {
                content.innerHTML = '<div class="empty">Seu carrinho está vazio. Adicione produtos antes de finalizar o pedido.</div>';
                form.style.display = 'none';
                return;
            }

            const total = cart.reduce((sum, item) => sum + item.preco * item.quantidade, 0);
            content.innerHTML = `
                <div class="summary">
                    ${cart.map(item => `
                        <div class="summary-item">
                            <div>${item.nome} x ${item.quantidade}</div>
                            <div>${formatMoney(item.preco * item.quantidade)}</div>
                        </div>
                    `).join('')}
                    <div class="summary-item total">
                        <div>Total</div>
                        <div>${formatMoney(total)}</div>
                    </div>
                </div>
            `;
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            alertBox.style.display = 'none';

            const cart = getCart();
            if (!cart.length) {
                return;
            }

            const payload = {
                mesa_numero: totemNumero,
                totem_numero: totemNumero,
                produtos: cart.map(item => ({
                    id_produto: Number(item.id),
                    quantidade: Number(item.quantidade),
                    preco_unitario: Number(item.preco)
                }))
            };

            try {
                const response = await fetch('http://localhost/api/public/api/checkout?_=' + Date.now(), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                let result = null;
                try {
                    result = await response.json();
                } catch (parseError) {
                    result = null;
                }

                if (!response.ok) {
                    const message = result?.messages?.error || result?.message || `Erro ao finalizar pedido. Status: ${response.status}`;
                    alertBox.innerHTML = `<strong>Erro da API:</strong><br>${message}<br><small>Payload enviado: ${JSON.stringify(payload)}</small>`;
                    alertBox.style.display = 'block';
                    return;
                }

                const total = cart.reduce((sum, item) => sum + item.preco * item.quantidade, 0);
                saveLastOrder({
                    id_pedido: result.id_pedido,
                    totem_numero: totemNumero,
                    mesa_numero: totemNumero,
                    itens: cart,
                    total,
                    cliente: document.getElementById('customerName').value.trim() || 'Cliente',
                    contato: document.getElementById('customerPhone').value.trim() || ''
                });

                clearCart();

                window.location.href = '<?= site_url('nota') ?>?id=' + encodeURIComponent(result.id_pedido);
            } catch (error) {
                alertBox.textContent = error.message || 'Erro ao finalizar pedido.';
                alertBox.style.display = 'block';
            }
        });

        renderCheckout();
    </script>
</body>
</html>
