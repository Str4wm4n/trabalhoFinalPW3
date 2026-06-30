<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar pedido</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #111111;
            background: #ffffff;
            min-height: 100vh;
        }
        .container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
        }
        .box {
            text-align: center;
            max-width: 560px;
            width: 100%;
        }
        .eyebrow {
            margin: 0 0 12px;
            font-size: 0.85rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: #777777;
            font-weight: 700;
        }
        h1 {
            margin: 0 0 18px;
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 800;
            letter-spacing: -0.04em;
            color: #111111;
        }
        .lead {
            margin: 0 auto 12px;
            font-size: 1.08rem;
            line-height: 1.7;
            color: #444444;
            max-width: 460px;
        }
        .support {
            margin: 0 auto 28px;
            font-size: 0.98rem;
            line-height: 1.6;
            color: #666666;
            max-width: 440px;
        }
        .mesa-select {
            margin: 0 auto 22px;
            max-width: 320px;
            text-align: left;
        }
        .mesa-select label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            font-weight: 700;
            color: #333333;
        }
        .mesa-select select {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #d8d8d8;
            background: #ffffff;
            font-size: 1rem;
        }
        .mesa-help {
            margin-top: 8px;
            font-size: 0.85rem;
            color: #666666;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 30px;
            background: #111111;
            color: #ffffff;
            font-weight: 700;
            border-radius: 999px;
            text-decoration: none;
            transition: transform .2s ease, box-shadow .2s ease;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.14);
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.18);
        }
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        }
        .steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 14px;
            margin: 0 0 28px;
            text-align: left;
        }
        .step {
            border: 1px solid #e9e9e9;
            border-radius: 16px;
            padding: 16px;
            background: #fafafa;
        }
        .step strong {
            display: block;
            margin-bottom: 6px;
            font-size: 0.95rem;
            color: #111111;
        }
        .step span {
            font-size: 0.92rem;
            line-height: 1.5;
            color: #666666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="box">
            <div class="eyebrow">pedido simples e rápido</div>
            <h1>Seu pedido começa aqui</h1>
            <p class="lead">Escolha um produto, monte o carrinho e finalize em poucos cliques.</p>

            <div class="mesa-select">
                <label for="mesaNumero">Identificação do mesa</label>
                <select id="mesaNumero" required>
                    <option value="">Selecione a mesa</option>
                    <option value="1">Mesa 1</option>
                    <option value="2">Mesa 2</option>
                    <option value="3">Mesa 3</option>
                    <option value="4">Mesa 4</option>
                    <option value="5">Mesa 5</option>
                    <option value="6">Mesa 6</option>
                    <option value="7">Mesa 7</option>
                    <option value="8">Mesa 8</option>
                    <option value="9">Mesa 9</option>
                </select>
                <div class="mesa-help">Essa mesa será vinculada ao pedido para acompanhamento no admin.</div>
            </div>
            <button class="btn" id="startOrderBtn" type="button" disabled>Iniciar pedido</button>
        </div>
    </div>

    <script>
        const mesaKey = 'pedidoMesa';
        const mesaSelect = document.getElementById('mesaNumero');
        const startOrderBtn = document.getElementById('startOrderBtn');

        const mesaSalva = localStorage.getItem(mesaKey) || '';
        if (mesaSalva) {
            mesaSelect.value = mesaSalva;
            startOrderBtn.disabled = false;
        }

        mesaSelect.addEventListener('change', () => {
            startOrderBtn.disabled = !mesaSelect.value;
        });

        startOrderBtn.addEventListener('click', () => {
            const mesa = Number(mesaSelect.value);
            if (!mesa || mesa < 1 || mesa > 9) {
                alert('Selecione uma mesa válida de 1 a 9.');
                return;
            }

            localStorage.setItem(mesaKey, String(mesa));
            window.location.href = '<?= site_url('produtos') ?>';
        });
    </script>
</body>
</html>
