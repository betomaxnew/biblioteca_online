<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}

// Lógica para listar e gerenciar usuários
$usuarios = $conn->query("SELECT * FROM usuarios ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários - Biblioteca Online</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <main class="container">
        <h1>Gerenciar Usuários</h1>
        
        <table class="tabela-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Data Registro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td><?php echo $usuario['tipo']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($usuario['data_registro'])); ?></td>
                        <td class="acoes">
                            <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-small">Editar</a>
                            <?php if ($usuario['id'] != $_SESSION['user_id']): ?>
                                <a href="excluir_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-small btn-danger">Excluir</a>
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