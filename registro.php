<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Todos os campos são obrigatórios!";
    } elseif ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem!";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres!";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $erro = "Este email já está registrado!";
            } else {
                $hash_senha = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
                $stmt->execute([$nome, $email, $hash_senha]);
                
                $usuario_id = $conn->lastInsertId();
                $codigo_cartao = criarCartaoUsuario($usuario_id);
                
                $_SESSION['user_id'] = $usuario_id;
                $_SESSION['user_name'] = $nome;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_type'] = 'usuario';
                $_SESSION['cartao_codigo'] = $codigo_cartao;
                
                header("Location: index.php");
                exit();
            }
        } catch(PDOException $e) {
            $erro = "Erro ao registrar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Biblioteca Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --text-color: #2b2d42;
            --light-color: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        
        .auth-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            text-align: center;
        }
        
        .auth-title {
            color: var(--text-color);
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .auth-subtitle {
            color: #6c757d;
            font-size: 16px;
            margin-bottom: 30px;
        }
        
        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .input-group {
            text-align: left;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-color);
        }
        
        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        
        .auth-button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            margin-top: 10px;
        }
        
        .auth-footer {
            margin-top: 25px;
            color: #6c757d;
        }
        
        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .error {
            color: #f72585;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1 class="auth-title">Crie sua conta</h1>
        <p class="auth-subtitle">Cadastre seus dados</p>
        
        <?php if ($erro): ?>
            <div class="error"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="registro.php" class="auth-form">
            <div class="input-group">
                <label>NOME</label>
                <input type="text" name="nome" required>
            </div>
            
            <div class="input-group">
                <label>EMAIL</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="input-group">
                <label>SENHA</label>
                <input type="password" name="senha" required>
            </div>
            
            <div class="input-group">
                <label>CONFIRMAR SENHA</label>
                <input type="password" name="confirmar_senha" required>
            </div>
            
            <button type="submit" class="auth-button">CADASTRAR</button>
        </form>
        
        <div class="auth-footer">
            <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
        </div>
    </div>
</body>
</html>