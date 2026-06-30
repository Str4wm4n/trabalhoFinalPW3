<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Cozinha - Pedidos</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #ffffff; margin: 0; padding: 40px; color: #333; }
        header { border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 30px; }
        h1 { margin: 0; font-size: 1.8rem; }
        .pedido-card { background: #fff; padding: 20px; border-radius: 12px; margin-bottom: 15px; border: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; transition: all 0.2s; }
        .pedido-card:hover { border-color: #333; }
        .status-novo { color: #d97706; font-weight: 600; }
        .status-finalizado { color: #059669; font-weight: 600; }
        .status-cancelado { color: #dc2626; font-weight: 600; }
        .btn { padding: 10px 20px; background: #333; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.9rem; }
    </style>
</head>
<body>
    <header>
        <h1>Painel da Cozinha</h1>
    </header>
    <main id="pedidosList">
        <p>Carregando pedidos...</p>
    </main>

    <script>
        const apiUrl = 'http://localhost/api/public/api/pedidos';

        async function loadPedidos() {
            const response = await fetch(apiUrl);
            const pedidos = await response.json();
            const list = document.getElementById('pedidosList');

            if (pedidos.length === 0) {
                list.innerHTML = '<p>Nenhum pedido realizado ainda.</p>';
                return;
            }

            list.innerHTML = pedidos.map(p => `
                <div class="pedido-card">
                    <div>
                        <strong>Pedido #${p.id}</strong><br>
                        <small>Mesa ${p.mesa_numero || '-'}</small><br>
                        <span class="status-${p.status}">Status: ${p.status.toUpperCase()}</span><br>
                        <small>Data: ${new Date(p.created_at).toLocaleString('pt-BR')}</small>
                    </div>
                    <a href="${window.location.origin}/cozinha/public/pedidos/${p.id}" class="btn">Ver Detalhes</a>
                </div>
            `).join('');
        }

        setInterval(loadPedidos, 5000); // Atualiza a cada 5 segundos
        loadPedidos();
    </script>
</body>
</html>
