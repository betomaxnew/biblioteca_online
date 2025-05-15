<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    
    if (empty($nome)) {
        $erro = "O nome da categoria é obrigatório!";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO categorias (nome, descricao) VALUES (?, ?)");
            $stmt->execute([$nome, $descricao]);
            
            $sucesso = "Categoria adicionada com sucesso!";
        } catch(PDOException $e) {
            $erro = "Erro ao adicionar categoria: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Categoria - Biblioteca Online</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <main class="container">
        <h1>Adicionar Nova Categoria</h1>
        
        <?php if ($erro): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <?php if ($sucesso): ?>
            <div class="alert alert-success"><?php echo $sucesso; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form-categoria">
            <div class="form-group">
                <label for="nome">Nome da Categoria:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" rows="4"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Adicionar Categoria</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </main>
    
    <script src="../assets/js/script.js"></script>
</body>
</html>