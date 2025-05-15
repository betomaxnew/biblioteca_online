<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}

// Obter cartões com informações do usuário
$cartoes = $conn->query("SELECT c.*, u.nome as usuario_nome FROM cartoes c JOIN usuarios u ON c.usuario_id = u.id ORDER BY c.data_emissao DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Cartões - Biblioteca Online</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <main class="container">
        <h1>Gerenciar Cartões de Publicação</h1>
        
        <table class="tabela-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Usuário</th>
                    <th>Publicações Restantes</th>
                    <th>Data Emissão</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartoes as $cartao): ?>
                    <tr>
                        <td><?php echo $cartao['id']; ?></td>
                        <td><?php echo htmlspecialchars($cartao['codigo']); ?></td>
                        <td><?php echo htmlspecialchars($cartao['usuario_nome']); ?></td>
                        <td><?php echo $cartao['publicacoes_restantes']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($cartao['data_emissao'])); ?></td>
                        <td><?php echo $cartao['ativo'] ? 'Ativo' : 'Inativo'; ?></td>
                        <td class="acoes">
                            <?php if ($cartao['ativo']): ?>
                                <a href="desativar_cartao.php?id=<?php echo $cartao['id']; ?>" class="btn btn-small btn-warning">Desativar</a>
                            <?php else: ?>
                                <a href="ativar_cartao.php?id=<?php echo $cartao['id']; ?>" class="btn btn-small btn-success">Ativar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    
    <script src="../assets/js/script.js"></script>
</body>
</html>