<?php
// Script para criar tabela e usuário admin

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'pedidos';

// Conectar ao banco
$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

echo "✓ Conectado ao banco de dados\n\n";

// Criar tabela usuarios
$sql = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    role VARCHAR(50),
    ativo TINYINT DEFAULT 1,
    api_token VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
)";

if ($mysqli->query($sql) === TRUE) {
    echo "✓ Tabela 'usuarios' criada/verificada\n";
} else {
    die('Erro ao criar tabela: ' . $mysqli->error);
}

// Verificar se admin já existe
$result = $mysqli->query("SELECT id FROM usuarios WHERE email = 'admin@admin.com'");

if ($result->num_rows > 0) {
    echo "✓ Usuário admin já existe\n";
} else {
    // Criar usuário admin
    $nome = 'Administrador';
    $email = 'admin@admin.com';
    $senha = password_hash('admin123', PASSWORD_BCRYPT);
    $role = 'super_admin';
    
    $sql = "INSERT INTO usuarios (nome, email, senha, role, ativo) 
            VALUES ('$nome', '$email', '$senha', '$role', 1)";
    
    if ($mysqli->query($sql) === TRUE) {
        echo "✓ Usuário admin criado com sucesso!\n";
        echo "  E-mail: admin@admin.com\n";
        echo "  Senha: admin123\n";
    } else {
        die('Erro ao criar usuário: ' . $mysqli->error);
    }
}

$mysqli->close();
echo "\n✓ Setup concluído com sucesso!\n";
echo "  Você pode fazer login agora!\n";
?>
