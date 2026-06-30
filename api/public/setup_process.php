<?php
header('Content-Type: application/json');

$action = $_POST['action'] ?? null;

if ($action === 'create_admin') {
    try {
        $db = mysqli_connect('localhost', 'root', '', 'pedidos');
        
        if (!$db) {
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao conectar ao banco de dados: ' . mysqli_connect_error()
            ]);
            exit;
        }

        // Criar tabela usuarios se não existir
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
        
        if (!mysqli_query($db, $sql)) {
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao criar tabela: ' . mysqli_error($db)
            ]);
            exit;
        }

        // Verificar se o usuário admin já existe
        $result = mysqli_query($db, "SELECT * FROM usuarios WHERE email = 'admin@admin.com'");
        
        if (mysqli_num_rows($result) > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Usuário admin já existe!'
            ]);
            mysqli_close($db);
            exit;
        }

        // Criar usuário admin
        $senha_hash = password_hash('admin123', PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (nome, email, senha, role, ativo) VALUES ('Administrador', 'admin@admin.com', '$senha_hash', 'admin', 1)";
        
        if (!mysqli_query($db, $sql)) {
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao criar usuário: ' . mysqli_error($db)
            ]);
            exit;
        }

        mysqli_close($db);
        echo json_encode([
            'success' => true,
            'message' => 'Usuário admin criado com sucesso!'
        ]);

    } catch (\Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erro: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Ação não especificada'
    ]);
}
