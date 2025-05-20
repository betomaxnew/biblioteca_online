<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    
    if (empty($email) || empty($senha)) {
        $erro = "Email e senha são obrigatórios!";
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_name'] = $usuario['nome'];
                $_SESSION['user_email'] = $usuario['email'];
                $_SESSION['user_type'] = $usuario['tipo'];
                
                $cartao = getCartaoAtivo($usuario['id']);
                if ($cartao) {
                    $_SESSION['cartao_codigo'] = $cartao['codigo'];
                }
                
                header("Location: index.php");
                exit();
            } else {
                $erro = "Credenciais inválidas!";
            }
        } catch(PDOException $e) {
            $erro = "Erro ao fazer login: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Biblioteca Online</title>
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
        <h1 class="auth-title">Bem-Vindo de volta</h1>
        <p class="auth-subtitle">Acesse sua conta agora mesmo</p>
        
        <?php if ($erro): ?>
            <div class="error"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php" class="auth-form">
            <div class="input-group">
                <label>EMAIL</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="input-group">
                <label>SENHA</label>
                <input type="password" name="senha" required>
            </div>
            
            <button type="submit" class="auth-button">ENTRAR</button>
        </form>
        
        <div class="auth-footer">
            <p>Não tem uma conta? <a href="registro.php">Crie sua conta</a></p>
        </div>
    </div>
</body>
</html>