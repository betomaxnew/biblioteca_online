<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);
    
    if (!empty($mensagem)) {
        $stmt = $conn->prepare("INSERT INTO chat_mensagens (usuario_id, mensagem) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $mensagem]);
    }
}

// Obter últimas mensagens
$mensagens = $conn->query("
    SELECT cm.*, u.nome 
    FROM chat_mensagens cm
    JOIN usuarios u ON cm.usuario_id = u.id
    ORDER BY cm.data_envio DESC
    LIMIT 50
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bate-papo - Biblioteca Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .chat-container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .chat-header {
            background: #3498db;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        
        .chat-messages {
            height: 400px;
            overflow-y: auto;
            padding: 1rem;
            background: #f9f9f9;
        }
        
        .message {
            margin-bottom: 1rem;
            padding: 0.8rem;
            background: white;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #666;
        }
        
        .message-user {
            font-weight: bold;
            color: #3498db;
        }
        
        .chat-input {
            padding: 1rem;
            background: #eee;
            display: flex;
            gap: 10px;
        }
        
        .chat-input textarea {
            flex: 1;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: none;
            height: 60px;
        }
        
        .chat-login-prompt {
            padding: 1rem;
            text-align: center;
            background: #fff8e1;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <div class="chat-container">
            <div class="chat-header">
                <h2>Bate-papo dos Leitores</h2>
            </div>
            
            <div class="chat-messages">
                <?php foreach (array_reverse($mensagens) as $msg): ?>
                    <div class="message">
                        <div class="message-header">
                            <span class="message-user"><?= htmlspecialchars($msg['nome']) ?></span>
                            <span><?= date('d/m/Y H:i', strtotime($msg['data_envio'])) ?></span>
                        </div>
                        <p><?= nl2br(htmlspecialchars($msg['mensagem'])) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (isLoggedIn()): ?>
                <form method="POST" class="chat-input">
                    <textarea name="mensagem" placeholder="Digite sua mensagem..." required></textarea>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            <?php else: ?>
                <div class="chat-login-prompt">
                    <p>Para participar do bate-papo, <a href="login.php">faça login</a> ou <a href="registro.php">cadastre-se</a>.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/chat.js"></script>
</body>
</html>