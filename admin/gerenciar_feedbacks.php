<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isAdmin()) {
    header("Location: ../index.php");
    exit;
}

// Aprovar/rejeitar feedbacks
if (isset($_GET['acao'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $acao = $_GET['acao'];
    
    if ($id && in_array($acao, ['aprovar', 'rejeitar'])) {
        $aprovado = $acao === 'aprovar' ? 1 : 0;
        $stmt = $conn->prepare("UPDATE feedbacks SET aprovado = ? WHERE id = ?");
        $stmt->execute([$aprovado, $id]);
    }
}

$feedbacks = $conn->query("
    SELECT f.*, COALESCE(u.nome, f.nome) AS autor 
    FROM feedbacks f
    LEFT JOIN usuarios u ON f.usuario_id = u.id
    ORDER BY f.aprovado ASC, f.data_publicacao DESC
")->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Gerenciar Feedbacks';
include '../includes/header.php';
?>

<main class="container">
    <h1>Gerenciar Feedbacks</h1>
    
    <table class="tabela-admin">
        <thead>
            <tr>
                <th>ID</th>
                <th>Autor</th>
                <th>Mensagem</th>
                <th>Data</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($feedbacks as $feedback): ?>
                <tr class="<?= $feedback['aprovado'] ? 'aprovado' : 'pendente' ?>">
                    <td><?= $feedback['id'] ?></td>
                    <td><?= htmlspecialchars($feedback['autor'] ?: 'Anônimo') ?></td>
                    <td><?= nl2br(htmlspecialchars(substr($feedback['mensagem'], 0, 100))) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($feedback['data_publicacao'])) ?></td>
                    <td><?= $feedback['aprovado'] ? 'Aprovado' : 'Pendente' ?></td>
                    <td class="acoes">
                        <?php if (!$feedback['aprovado']): ?>
                            <a href="?acao=aprovar&id=<?= $feedback['id'] ?>" class="btn btn-pequeno btn-sucesso">Aprovar</a>
                        <?php endif; ?>
                        <a href="?acao=rejeitar&id=<?= $feedback['id'] ?>" class="btn btn-pequeno btn-perigo">Rejeitar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include '../includes/footer.php'; ?>