<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}

// Obter requisições pendentes
$stmt = $conn->query("SELECT r.*, u.nome as usuario_nome FROM requisicoes_cartao r JOIN usuarios u ON r.usuario_id = u.id WHERE r.status = 'pendente' ORDER BY r.data_requisicao ASC");
$requisicoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obter estatísticas
$total_usuarios = $conn->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$total_livros = $conn->query("SELECT COUNT(*) FROM livros")->fetchColumn();
$total_cartoes = $conn->query("SELECT COUNT(*) FROM cartoes")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Biblioteca Online</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <main class="container">
        <h1>Painel Administrativo</h1>
        
        <div class="admin-stats">
            <div class="stat-card">
                <h3>Usuários</h3>
                <p><?php echo $total_usuarios; ?></p>
                <a href="gerenciar_usuarios.php" class="btn btn-small">Gerenciar</a>
            </div>
            
            <div class="stat-card">
                <h3>Livros</h3>
                <p><?php echo $total_livros; ?></p>
                <a href="gerenciar_livros.php" class="btn btn-small">Gerenciar</a>
            </div>
            
            <div class="stat-card">
                <h3>Cartões</h3>
                <p><?php echo $total_cartoes; ?></p>
                <a href="gerenciar_cartoes.php" class="btn btn-small">Gerenciar</a>
            </div>
            
            <div class="stat-card">
                <h3>Requisições Pendentes</h3>
                <p><?php echo count($requisicoes); ?></p>
                <a href="manage_cards.php" class="btn btn-small">Ver Todas</a>
            </div>
        </div>
        
        <section class="requisicoes-pendentes">
            <h2>Requisições de Cartão Pendentes</h2>
            
            <?php if (count($requisicoes) > 0): ?>
                <table class="tabela-admin">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requisicoes as $requisicao): ?>
                            <tr>
                                <td><?php echo $requisicao['id']; ?></td>
                                <td><?php echo htmlspecialchars($requisicao['usuario_nome']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($requisicao['data_requisicao'])); ?></td>
                                <td class="acoes">
                                    <a href="manage_cards.php?action=approve&id=<?php echo $requisicao['id']; ?>" class="btn btn-small btn-success">Aprovar</a>
                                    <a href="manage_cards.php?action=reject&id=<?php echo $requisicao['id']; ?>" class="btn btn-small btn-danger">Recusar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info">Não há requisições pendentes no momento.</div>
            <?php endif; ?>
        </section>
        
        <section class="quick-actions">
            <h2>Ações Rápidas</h2>
            <div class="actions-grid">
                <a href="adicionar_categoria.php" class="btn btn-primary">Adicionar Categoria</a>
                <a href="../registro.php?admin=1" class="btn btn-primary">Criar Novo Usuário</a>
                <a href="backup.php" class="btn btn-secondary">Fazer Backup</a>
            </div>
        </section>
    </main>
    
    <script src="../assets/js/script.js"></script>
</body>
</html>