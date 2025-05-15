<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['user_id'];
$livros = getLivrosByUsuario($usuario_id);
$cartao = getCartaoAtivo($usuario_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publicar_livro'])) {
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $descricao = trim($_POST['descricao']);
    
    if (empty($titulo) || empty($autor)) {
        $erro = "Título e autor são obrigatórios!";
    } elseif ($cartao['publicacoes_restantes'] <= 0) {
        $erro = "Você não tem publicações restantes neste cartão. Por favor, solicite um novo cartão.";
    } else {
        // Processar upload do arquivo
        $arquivo_nome = $_FILES['arquivo']['name'];
        $arquivo_tmp = $_FILES['arquivo']['tmp_name'];
        $arquivo_ext = strtolower(pathinfo($arquivo_nome, PATHINFO_EXTENSION));
        
        // Processar upload da capa (opcional)
        $capa_nome = null;
        if (!empty($_FILES['capa']['name'])) {
            $capa_tmp = $_FILES['capa']['tmp_name'];
            $capa_ext = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
            $capa_nome = uniqid() . '.' . $capa_ext;
            
            if (!move_uploaded_file($capa_tmp, 'uploads/' . $capa_nome)) {
                $erro = "Erro ao fazer upload da capa.";
            }
        }
        
        if (empty($erro)) {
            $novo_nome_arquivo = uniqid() . '.' . $arquivo_ext;
            
            if (move_uploaded_file($arquivo_tmp, 'uploads/' . $novo_nome_arquivo)) {
                try {
                    $stmt = $conn->prepare("INSERT INTO livros (titulo, autor, descricao, caminho_arquivo, usuario_id, cartao_id, capa) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$titulo, $autor, $descricao, $novo_nome_arquivo, $usuario_id, $cartao['id'], $capa_nome]);
                    
                    // Decrementar publicações restantes
                    decrementarPublicacaoCartao($cartao['id']);
                    
                    header("Location: perfil.php");
                    exit();
                } catch(PDOException $e) {
                    $erro = "Erro ao publicar livro: " . $e->getMessage();
                }
            } else {
                $erro = "Erro ao fazer upload do arquivo.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Biblioteca Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <h1>Meu Perfil</h1>
        
        <div class="perfil-info">
            <h2>Informações do Usuário</h2>
            <p>Nome: <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
            <p>Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
            
            <h2>Meu Cartão</h2>
            <?php if ($cartao): ?>
                <p>Código do Cartão: <?php echo htmlspecialchars($cartao['codigo']); ?></p>
                <p>Publicações Restantes: <?php echo $cartao['publicacoes_restantes']; ?></p>
                
                <?php if ($cartao['publicacoes_restantes'] <= 0): ?>
                    <a href="requisitar_cartao.php" class="btn btn-primary">Solicitar Novo Cartão</a>
                <?php endif; ?>
            <?php else: ?>
                <p>Você não tem um cartão ativo.</p>
                <a href="requisitar_cartao.php" class="btn btn-primary">Solicitar Cartão</a>
            <?php endif; ?>
        </div>
        
        <div class="publicar-livro">
            <h2>Publicar Novo Livro</h2>
            
            <?php if (isset($erro)): ?>
                <div class="alert alert-danger"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <?php if ($cartao && $cartao['publicacoes_restantes'] > 0): ?>
                <form method="POST" action="perfil.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="autor">Autor:</label>
                        <input type="text" id="autor" name="autor" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao">Descrição (opcional):</label>
                        <textarea id="descricao" name="descricao" rows="4"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="arquivo">Arquivo do Livro (PDF):</label>
                        <input type="file" id="arquivo" name="arquivo" accept=".pdf" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="capa">Capa do Livro (opcional):</label>
                        <input type="file" id="capa" name="capa" accept="image/*">
                    </div>
                    
                    <button type="submit" name="publicar_livro" class="btn btn-primary">Publicar Livro</button>
                </form>
            <?php else: ?>
                <p>Você não tem publicações disponíveis no seu cartão atual.</p>
            <?php endif; ?>
        </div>
        
        <div class="meus-livros">
            <h2>Meus Livros Publicados</h2>
            
            <?php if (count($livros) > 0): ?>
                <div class="livros-grid">
                    <?php foreach ($livros as $livro): ?>
                        <div class="livro-card">
                            <img src="<?php echo $livro['capa'] ? 'uploads/' . $livro['capa'] : 'assets/images/default-cover.jpg'; ?>" alt="<?php echo htmlspecialchars($livro['titulo']); ?>">
                            <h3><?php echo htmlspecialchars($livro['titulo']); ?></h3>
                            <p>por <?php echo htmlspecialchars($livro['autor']); ?></p>
                            <a href="livro.php?id=<?php echo $livro['id']; ?>" class="btn btn-small">Ver Livro</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Você ainda não publicou nenhum livro.</p>
            <?php endif; ?>
        </div>
    </main>
    
    <script src="assets/js/script.js"></script>
</body>
</html>