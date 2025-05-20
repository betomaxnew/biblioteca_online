<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    http_response_code(403);
    exit;
}

$mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);

if (!empty($mensagem)) {
    $stmt = $conn->prepare("INSERT INTO chat_mensagens (usuario_id, mensagem) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $mensagem]);
    echo json_encode(['success' => true]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Mensagem vazia']);
}