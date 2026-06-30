<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Detalhes do Pedido</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7fb; margin: 0; padding: 20px; }
        .container { max-width: 600px; background: white; padding: 20px; border-radius: 8px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { border-bottom: 2px solid #1d3c72; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { text-align: left; padding: 10px; border-bottom: 1px solid #ddd; }
        .actions { display: flex; gap: 10px; margin-top: 20px; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; color: white; font-weight: bold; flex: 1; text-align: center; text-decoration: none; }
        .btn-finish { background: green; }
        .btn-cancel { background: red; }
        .btn-back { background: #555; }
    </style>
</head>
<body>
    <div class="container" id="detalhesContent">
        <p>Carregando detalhes...</p>
    </div>

    <script>
        const pedidoId = "<?= $id ?>";
        const apiUrl = `http://localhost/api/public/api/pedidos/${pedidoId}`;

        async function loadDetalhes() {
            const response = await fetch(apiUrl);
            const pedido = await response.json();
            const content = document.getElementById('detalhesContent');

            let itensHtml = pedido.itens.map(item => `
                <tr>
                    <td>${item.produto_nome}</td>
                    <td>${item.quantidade}</td>
                </tr>
            `).join('');

            content.innerHTML = `
                <h2>Pedido #${pedido.id}</h2>
                <p><strong>Mesa:</strong> ${pedido.mesa_numero || '-'}</p>
                <p><strong>Status Atual:</strong> ${pedido.status.toUpperCase()}</p>
                <table>
                    <thead>
                        <tr><th>Produto</th><th>Qtd</th></tr>
                    </thead>
                    <tbody>${itensHtml}</tbody>
                </table>
                <div class="actions">
                    <button class="btn btn-finish" onclick="atualizarStatus('finalizado')">Finalizar</button>
                    <button class="btn btn-cancel" onclick="atualizarStatus('cancelado')">Cancelar</button>
                    <a href="<?= site_url('pedidos') ?>" class="btn btn-back">Voltar</a>
                </div>
            `;
        }

        async function atualizarStatus(novoStatus) {
            if (!confirm(`Deseja realmente alterar o status para ${novoStatus}?`)) return;

            const response = await fetch(apiUrl, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: novoStatus })
            });

            if (response.ok) {
                alert('Status atualizado com sucesso!');
                window.location.href = 'http://localhost/cozinha/public/pedidos';
            } else {
                alert('Erro ao atualizar status.');
            }
        }

        loadDetalhes();
    </script>
</body>
</html>
