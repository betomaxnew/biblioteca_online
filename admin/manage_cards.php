<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['action']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$action = $_GET['action'];
$requisicao_id = $_GET['id'];

// Verificar se a requisição existe
$stmt = $conn->prepare("SELECT * FROM requisicoes_cartao WHERE id = ?");
$stmt->execute([$requisicao_id]);
$requisicao = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$requisicao) {
    header("Location: dashboard.php");
    exit();
}

if ($requisicao['status'] !== 'pendente') {
    header("Location: dashboard.php");
    exit();
}

// Processar ação
if ($action === 'approve') {
    try {
        $conn->beginTransaction();
        
        // Atualizar status da requisição
        $stmt = $conn->prepare("UPDATE requisicoes_cartao SET status = 'aprovado' WHERE id = ?");
        $stmt->execute([$requisicao_id]);
        
        // Desativar cartões antigos do usuário
        $stmt = $conn->prepare("UPDATE cartoes SET ativo = FALSE WHERE usuario_id = ?");
        $stmt->execute([$requisicao['usuario_id']]);
        
        // Criar novo cartão
        $codigo_cartao = gerarCodigoCartao();
        $stmt = $conn->prepare("INSERT INTO cartoes (usuario_id, codigo) VALUES (?, ?)");
        $stmt->execute([$requisicao['usuario_id'], $codigo_cartao]);
        
        $conn->commit();
        
        $_SESSION['mensagem'] = "Cartão aprovado com sucesso!";
    } catch(PDOException $e) {
        $conn->rollBack();
        $_SESSION['erro'] = "Erro ao aprovar cartão: " . $e->getMessage();
    }
} elseif ($action === 'reject') {
    try {
        $stmt = $conn->prepare("UPDATE requisicoes_cartao SET status = 'recusado' WHERE id = ?");
        $stmt->execute([$requisicao_id]);
        
        $_SESSION['mensagem'] = "Requisição recusada com sucesso!";
    } catch(PDOException $e) {
        $_SESSION['erro'] = "Erro ao recusar requisição: " . $e->getMessage();
    }
}

header("Location: dashboard.php");
exit();
?>