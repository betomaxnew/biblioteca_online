<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['user_id'];
$cartao = getCartaoAtivo($usuario_id);

if ($cartao && $cartao['publicacoes_restantes'] > 0) {
    header("Location: perfil.php");
    exit();
}

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Verificar se já existe uma requisição pendente
        $stmt = $conn->prepare("SELECT id FROM requisicoes_cartao WHERE usuario_id = ? AND status = 'pendente'");
        $stmt->execute([$usuario_id]);
        
        if ($stmt->rowCount() > 0) {
            $erro = "Você já tem uma requisição de cartão pendente.";
        } else {
            // Criar nova requisição
            $stmt = $conn->prepare("INSERT INTO requisicoes_cartao (usuario_id) VALUES (?)");
            $stmt->execute([$usuario_id]);
            
            $mensagem = "Sua requisição de novo cartão foi enviada com sucesso! O administrador irá analisar sua solicitação.";
        }
    } catch(PDOException $e) {
        $erro = "Erro ao enviar requisição: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisitar Novo Cartão - Biblioteca Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <h1>Requisitar Novo Cartão</h1>
        
        <?php if ($mensagem): ?>
            <div class="alert alert-success"><?php echo $mensagem; ?></div>
            <a href="perfil.php" class="btn btn-primary">Voltar ao Perfil</a>
        <?php else: ?>
            <?php if ($erro): ?>
                <div class="alert alert-danger"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <div class="cartao-info">
                <p>Você está solicitando um novo cartão de publicação. Cada cartão permite publicar até 5 livros.</p>
                <p>Após o administrador aprovar sua solicitação, um novo cartão será gerado para você automaticamente.</p>
                
                <form method="POST" action="requisitar_cartao.php">
                    <button type="submit" class="btn btn-primary">Confirmar Solicitação</button>
                    <a href="perfil.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        <?php endif; ?>
    </main>
    
    <script src="assets/js/script.js"></script>
</body>
</html>