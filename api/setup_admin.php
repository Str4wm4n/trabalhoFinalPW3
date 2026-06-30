<?php
// Script para criar um usuário admin padrão

try {
    // Criar conexão com o banco
    $db = mysqli_connect('localhost', 'root', '', 'pedidos');
    
    if (!$db) {
        echo "Erro ao conectar: " . mysqli_connect_error();
        exit;
    }

    // Verificar se a tabela usuarios existe
    $result = mysqli_query($db, "SHOW TABLES LIKE 'usuarios'");
    
    if (mysqli_num_rows($result) == 0) {
        echo "Criando tabela usuarios...\n";
        $sql = "CREATE TABLE usuarios (
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
        
        if (!mysqli_query($db, $sql)) {
            echo "Erro ao criar tabela: " . mysqli_error($db);
            exit;
        }
        echo "Tabela usuarios criada com sucesso!\n";
    }

    // Verificar se o usuário admin já existe
    $result = mysqli_query($db, "SELECT * FROM usuarios WHERE email = 'admin@admin.com'");
    
    if (mysqli_num_rows($result) == 0) {
        echo "Criando usuário admin padrão...\n";
        $senha_hash = password_hash('admin123', PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (nome, email, senha, role, ativo) VALUES ('Administrador', 'admin@admin.com', '$senha_hash', 'super_admin', 1)";
        
        if (!mysqli_query($db, $sql)) {
            echo "Erro ao criar usuário: " . mysqli_error($db);
            exit;
        }
        echo "Usuário admin criado com sucesso!\n";
        echo "Email: admin@admin.com\n";
        echo "Senha: admin123\n";
    } else {
        echo "Usuário admin já existe!\n";
    }

    mysqli_close($db);
    echo "\nSetup concluído com sucesso!\n";
    
} catch (\Exception $e) {
    echo "Erro: " . $e->getMessage();
}
