# Painel Administrativo - Setup

## 🚀 Como Usar

### 1. Iniciar o XAMPP

Abra o XAMPP Control Panel e inicie:
- **Apache Web Server**
- **MySQL Database**

### 2. Setup do Usuário Admin

Acesse no navegador:
```
http://localhost/api/public/setup.html
```

Clique em "Criar Usuário Admin" para criar as tabelas e o usuário padrão.

### 3. Login no Painel Admin

Acesse:
```
http://localhost/api/public/admin/login
```

**Credenciais padrão:**
- **E-mail:** admin@admin.com
- **Senha:** admin123

> ⚠️ **IMPORTANTE:** Altere a senha após fazer o primeiro login!

## 📋 Funcionalidades do Painel

### Dashboard
- Resumo de vendas e pedidos dos últimos 10 dias
- Estatísticas gerais

### Painel de Vendas
- Gráfico de vendas dos últimos 10 dias
- Estatísticas de receita e pedidos
- Filtros por período

### Painel de Consumo
- Tabela de produtos consumidos
- Quantidade e receita por produto
- Filtro por categoria
- Período configurável

### Gerenciamento de Usuários
- Criar novos usuários
- Editar usuários existentes
- Bloquear/desbloquear usuários
- Atribuir funções (Admin, Gerente, Operador)

## 🔗 URLs do Sistema

- **Cliente:** http://localhost/cliente/public/
- **Cozinha:** http://localhost/cozinha/public/
- **Admin:** http://localhost/api/public/admin/login

## 📱 API Endpoints

### Autenticação
- `POST /api/login` - Login
- `POST /api/verificar-token` - Verificar token

### Usuários
- `GET /api/usuarios` - Listar usuários
- `POST /api/usuarios` - Criar usuário
- `GET /api/usuarios/:id` - Obter usuário
- `PUT /api/usuarios/:id` - Atualizar usuário
- `DELETE /api/usuarios/:id` - Deletar usuário

### Relatórios
- `GET /api/relatorios/vendas` - Dados de vendas
- `GET /api/relatorios/consumo` - Dados de consumo

## 🛠️ Tecnologias

- **Backend:** CodeIgniter 4 + PHP
- **Frontend:** HTML5 + CSS3 + JavaScript
- **Banco de Dados:** MySQL
- **Gráficos:** Chart.js

## 📝 Notas

- Todos os tokens e senhas são criptografados com bcrypt
- O sistema armazena tokens de sessão para autenticação
- Os dados são sincronizados em tempo real com a API
