<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Painel Administrativo</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .3);
            padding: 40px;
            text-align: center;
        }
        .logo {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 20px;
            color: #667eea;
        }
        h1 {
            margin: 0 0 30px;
            font-size: 1.8rem;
            color: #333;
        }
        .field {
            margin-bottom: 20px;
            text-align: left;
        }
        .field label {
            display: block;
            font-weight: 700;
            margin-bottom: 8px;
            color: #555;
        }
        .field input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .field input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            font-size: 1rem;
            transition: transform 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
        }
        .alert {
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 20px;
            background: #ffe9dd;
            color: #7a3a16;
            display: none;
        }
        .alert.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">PED</div>
        <h1>Painel Administrativo</h1>
        <div id="alert" class="alert"></div>
        <form id="loginForm">
            <div class="field">
                <label for="email">E-mail</label>
                <input id="email" type="email" placeholder="seu@email.com" required>
            </div>
            <div class="field">
                <label for="password">Senha</label>
                <input id="password" type="password" placeholder="Sua senha" required>
            </div>
            <button class="btn-login" type="submit">Entrar</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const alert = document.getElementById('alert');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            alert.classList.remove('show');

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('http://localhost/api/public/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.messages?.error || result.message || 'Erro ao fazer login');
                }

                localStorage.setItem('admin_token', result.token);
                localStorage.setItem('admin_user', JSON.stringify(result.user));
                window.location.href = 'http://localhost/api/public/admin/dashboard';
            } catch (error) {
                alert.textContent = error.message;
                alert.classList.add('show');
            }
        });
    </script>
</body>
</html>
