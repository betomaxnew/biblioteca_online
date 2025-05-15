<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Obter categorias do banco de dados
$categorias = $conn->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);

// Obter livros por categoria (se categoria selecionada)
$livros = [];
if (isset($_GET['categoria_id']) && is_numeric($_GET['categoria_id'])) {
    $categoria_id = $_GET['categoria_id'];
    $stmt = $conn->prepare("SELECT l.*, u.nome as autor_nome FROM livros l 
                           JOIN usuarios u ON l.usuario_id = u.id 
                           JOIN livro_categoria lc ON l.id = lc.livro_id
                           WHERE lc.categoria_id = ?
                           ORDER BY l.data_publicacao DESC");
    $stmt->execute([$categoria_id]);
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - Biblioteca Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <h1>Explorar por Categorias</h1>
        
        <div class="categorias-lista">
            <?php foreach ($categorias as $categoria): ?>
                <a href="categorias.php?categoria_id=<?php echo $categoria['id']; ?>" 
                   class="categoria-item <?php echo isset($_GET['categoria_id']) && $_GET['categoria_id'] == $categoria['id'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($categoria['nome']); ?>
                </a>
            <?php endforeach; ?>
        </div>
        
        <?php if (!empty($livros)): ?>
            <h2>Livros na categoria</h2>
            <div class="livros-grid">
                <?php foreach ($livros as $livro): ?>
                    <div class="livro-card">
                    <img src="assets/images/categorias/<?php echo $categoria['imagem']; ?>" 
     alt="<?php echo htmlspecialchars($categoria['nome']); ?>"
     class="categoria-img">
                        <h3><?php echo htmlspecialchars($livro['titulo']); ?></h3>
                        <p>por <?php echo htmlspecialchars($livro['autor_nome']); ?></p>
                        <a href="livro.php?id=<?php echo $livro['id']; ?>" class="btn btn-small">Ler Livro</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (isset($_GET['categoria_id'])): ?>
            <p>Nenhum livro encontrado nesta categoria.</p>
        <?php else: ?>
            <p>Selecione uma categoria para ver os livros disponíveis.</p>
        <?php endif; ?>
    </main>
    
    <script src="assets/js/script.js"></script>
</body>
</html>