<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

$mensagens = $conn->query("
    SELECT cm.*, u.nome 
    FROM chat_mensagens cm
    JOIN usuarios u ON cm.usuario_id = u.id
    ORDER BY cm.data_envio DESC
    LIMIT 50
")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(array_reverse($mensagens));