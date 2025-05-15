<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$livro = getLivroById($_GET['id']);

if (!$livro) {
    header("Location: index.php");
    exit();
}

// Registrar visualização
registrarVisualizacao($livro['id']);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($livro['titulo']); ?> - Biblioteca Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .livro-container {
            max-width: 1000px;
            margin: 2rem auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .livro-header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            align-items: center;
        }

        .livro-capa {
            width: 200px;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }

        .livro-capa:hover {
            transform: scale(1.05);
        }

        .livro-info {
            flex: 1;
            min-width: 300px;
        }

        .livro-titulo {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            color: white;
        }

        .livro-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin: 1.5rem 0;
            color: #ecf0f1;
        }

        .livro-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .livro-meta-item i {
            font-size: 1.2rem;
        }

        .livro-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .livro-conteudo {
            padding: 2rem;
        }

        .livro-descricao {
            line-height: 1.8;
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 2rem;
        }

        .livro-embed {
            width: 100%;
            height: 70vh;
            border: 1px solid #eee;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .livro-footer {
            padding: 1.5rem 2rem;
            background: #f9f9f9;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .livro-header {
                flex-direction: column;
                text-align: center;
            }
            
            .livro-actions {
                justify-content: center;
            }
            
            .livro-meta {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <div class="livro-container">
            <div class="livro-header">
                <img src="<?php echo $livro['capa'] ? 'uploads/' . htmlspecialchars($livro['capa']) : 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'; ?>" 
                     alt="<?php echo htmlspecialchars($livro['titulo']); ?>" 
                     class="livro-capa">
                
                <div class="livro-info">
                    <h1 class="livro-titulo"><?php echo htmlspecialchars($livro['titulo']); ?></h1>
                    
                    <p class="livro-autor">por <?php echo htmlspecialchars($livro['autor_nome']); ?></p>
                    
                    <div class="livro-meta">
                        <span class="livro-meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <?php echo date('d/m/Y', strtotime($livro['data_publicacao'])); ?>
                        </span>
                        <span class="livro-meta-item">
                            <i class="fas fa-eye"></i>
                            <?php echo number_format($livro['visualizacoes'] ?? 0, 0, ',', '.'); ?> visualizações
                        </span>
                        <span class="livro-meta-item">
                            <i class="fas fa-book-open"></i>
                            <?php echo formatarTamanhoLivro($livro['tamanho_arquivo'] ?? 0); ?>
                        </span>
                    </div>
                    
                    <div class="livro-actions">
                        <a href="download.php?id=<?php echo $livro['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-download"></i> Baixar
                        </a>
                        <button class="btn btn-secondary" id="favoritar-btn">
                            <i class="far fa-heart"></i> Favoritar
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="livro-conteudo">
                <?php if (!empty($livro['descricao'])): ?>
                    <div class="livro-descricao">
                        <h3>Sobre o Livro</h3>
                        <p><?php echo nl2br(htmlspecialchars($livro['descricao'])); ?></p>
                    </div>
                <?php endif; ?>
                
                <h3>Conteúdo</h3>
                <iframe src="uploads/<?php echo htmlspecialchars($livro['caminho_arquivo']); ?>" 
                        class="livro-embed"></iframe>
            </div>
            
            <div class="livro-footer">
                <div>
                    <small>Publicado por: <?php echo htmlspecialchars($livro['autor_nome']); ?></small>
                </div>
                <div>
                    <small>ID do Livro: <?php echo $livro['id']; ?></small>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/script.js"></script>
    <script>
        // Favoritar livro
        document.getElementById('favoritar-btn').addEventListener('click', function() {
            if (!<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>) {
                alert('Por favor, faça login para favoritar livros.');
                window.location.href = 'login.php';
                return;
            }
            
            fetch('api/favoritar.php?id=<?php echo $livro['id']; ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const icon = this.querySelector('i');
                        if (icon.classList.contains('far')) {
                            icon.classList.replace('far', 'fas');
                            this.classList.add('btn-favoritado');
                        } else {
                            icon.classList.replace('fas', 'far');
                            this.classList.remove('btn-favoritado');
                        }
                    }
                });
        });
    </script>
</body>
</html>