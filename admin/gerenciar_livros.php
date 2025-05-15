<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}

// Paginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 10;
$inicio = ($pagina > 1) ? ($pagina * $por_pagina) - $por_pagina : 0;

// Obter total de livros
$total = $conn->query("SELECT COUNT(*) FROM livros")->fetchColumn();
$paginas = ceil($total / $por_pagina);

// Obter livros com paginação
$stmt = $conn->prepare("SELECT l.*, u.nome as autor_nome FROM livros l JOIN usuarios u ON l.usuario_id = u.id ORDER BY l.data_publicacao DESC LIMIT :inicio, :por_pagina");
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':por_pagina', $por_pagina, PDO::PARAM_INT);
$stmt->execute();
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Livros - Biblioteca Online</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <main class="container">
        <h1>Gerenciar Livros</h1>
        
        <div class="admin-actions">
            <a href="adicionar_livro.php" class="btn btn-primary">Adicionar Livro</a>
        </div>
        
        <table class="tabela-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Capa</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Publicado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livros as $livro): ?>
                    <tr>
                        <td><?php echo $livro['id']; ?></td>
                        <td><img src="../uploads/<?php echo $livro['capa'] ?? 'default-cover.jpg'; ?>" alt="Capa" class="mini-capa"></td>
                        <td><?php echo htmlspecialchars($livro['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($livro['autor_nome']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($livro['data_publicacao'])); ?></td>
                        <td class="acoes">
                            <a href="editar_livro.php?id=<?php echo $livro['id']; ?>" class="btn btn-small">Editar</a>
                            <a href="excluir_livro.php?id=<?php echo $livro['id']; ?>" class="btn btn-small btn-danger">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if ($paginas > 1): ?>
            <div class="paginacao">
                <?php for ($i = 1; $i <= $paginas; $i++): ?>
                    <a href="?pagina=<?php echo $i; ?>" class="<?php echo $pagina == $i ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </main>
    
    <script src="../assets/js/script.js"></script>
</body>
</html>