<?php
ini_set('memory_limit', '512M'); 
require_once 'includes/config.php';
require_once 'includes/functions.php';

$livros = getLivrosRecentes();
$categorias = $conn->query("SELECT * FROM categorias LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .hero-banner {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('assets/images/library-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 100px 20px;
            margin-bottom: 40px;
            border-radius: 8px;
        }
        
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .hero-content h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(39, 212, 255, 0.5);
        }
        
        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(65, 185, 255, 0.5);
        }
        
        .categorias-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 25px;
            margin: 40px 0;
        }
        
        .categoria-card {
            position: relative;
            height: 200px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .categoria-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .categoria-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .categoria-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            padding: 20px;
            color: white;
        }
        
        .categoria-overlay h3 {
            margin: 0;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <section class="hero-banner">
            <div class="hero-content">
                <h1>Bem-vindo à Biblioteca Online</h1>
                <p>Leia e compartilhe livros gratuitamente</p>
                <?php if (!isLoggedIn()): ?>
                    <div class="actions">
                        <a href="registro.php" class="btn btn-primary btn-large">Registrar-se</a>
                        <a href="login.php" class="btn btn-secondary btn-large">Login</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        
    
    
</section>
        <section class="categorias-destaque">
    <h2>Explore Nossas Categorias</h2>
    <div class="categorias-grid">
        <!-- Ficção -->
        <div class="categoria-item">
            <a href="categorias.php?categoria_id=1" class="categoria-link">
                <img src="https://i.pinimg.com/736x/9f/16/42/9f1642596eae03f514b2560e7f692d44.jpg" 
                     alt="Ficção" class="categoria-img">
            </a>
            <h3 class="categoria-titulo">Ficção</h3>
        </div>
        
        <!-- Não Ficção -->
        <div class="categoria-item">
            <a href="categorias.php?categoria_id=2" class="categoria-link">
                <img src="https://i.pinimg.com/736x/ad/01/77/ad017714a2accff2f4b93f959575c611.jpg" 
                     alt="Não Ficção" class="categoria-img">
            </a>
            <h3 class="categoria-titulo">Não Ficção</h3>
        </div>
        
        <!-- Tecnologia -->
        <div class="categoria-item">
            <a href="categorias.php?categoria_id=3" class="categoria-link">
                <img src="https://i.pinimg.com/736x/e0/9f/bc/e09fbc090ef355e32bbb31a38e431517.jpg" 
                     alt="Tecnologia" class="categoria-img">
            </a>
            <h3 class="categoria-titulo">Tecnologia</h3>
        </div>
        
        <!-- Autoajuda -->
        <div class="categoria-item">
            <a href="categorias.php?categoria_id=4" class="categoria-link">
                <img src="https://i.pinimg.com/736x/4f/b7/e1/4fb7e1f1d96775c823315cab8cfb76aa.jpg" 
                     alt="Autoajuda" class="categoria-img">
            </a>
            <h3 class="categoria-titulo">Autoajuda</h3>
        </div>
        
        <!-- História -->
        <div class="categoria-item">
            <a href="categorias.php?categoria_id=5" class="categoria-link">
                <img src="https://i.pinimg.com/736x/3a/3f/51/3a3f51d3fda170a01d66dda2b56f4813.jpg" 
                     alt="História" class="categoria-img">
            </a>
            <h3 class="categoria-titulo">História</h3>
        </div>
        
        <!-- Romance -->
        <div class="categoria-item">
            <a href="categorias.php?categoria_id=6" class="categoria-link">
                <img src="https://i.pinimg.com/736x/12/29/f0/1229f055d2900f517000b63fa37b57e7.jpg" 
                     alt="Romance" class="categoria-img">
            </a>
            <h3 class="categoria-titulo">Romance</h3>
        </div>
        
        <!-- Aventura -->
        <div class="categoria-item">
            <a href="categorias.php?categoria_id=7" class="categoria-link">
                <img src="https://i.pinimg.com/736x/bf/ce/77/bfce77b70cee97c52afe5a32a00be122.jpg" 
                     alt="Aventura" class="categoria-img">
            </a>
            <h3 class="categoria-titulo">Aventura</h3>
        </div>
    </div>
</section>
<section class="destaque-fisico">
    <div class="container">
        <h2><i class="fas fa-crown"></i> Livros em Destaque</h2>
        
        <div class="livro-container">
            <!-- IMAGEM COM TAMANHO FIXO -->
            <div class="livro-capa-container">
                <img src="assets\images\capas\destaque3.jpg" 
                     alt="Capa do Livro" 
                     class="livro-capa-fisica"
                     style="width:150px; height:225px; object-fit:cover;"> <!-- TAMANHO FIXO AQUI -->
                <span class="badge-fisico"></span>
            </div>
            
            <div class="livro-info">
                <h3>MULHER DOS SEUS SONHOS</h3>
                <p class="autor">Por: ALBERTO BASILIO</p>
                
                <div class="detalhes">
                    <p><i class="fas fa-star"></i> 4.8 (Avaliações)</p>
                    <p><i class="fas fa-book-open"></i> 122 páginas</p>
                    <p><i class="fas fa-tag"></i> Gênero Literário</p>
                </div>
                
                
            </div>
        </div>
    </div>
</section>
        
        <section class="livros-recentes">
            <h2>Livros Recentes</h2>
            <div class="livros-grid">
                <?php foreach ($livros as $livro): ?>
                    <div class="livro-card">
                        <img src="<?php echo $livro['capa'] ? 'uploads/' . $livro['capa'] : 'assets/images/default-cover.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($livro['titulo']); ?>">
                        <h3><?php echo htmlspecialchars($livro['titulo']); ?></h3>
                        <p>por <?php echo htmlspecialchars($livro['autor_nome']); ?></p>
                        <a href="livro.php?id=<?php echo $livro['id']; ?>" class="btn btn-small">Ler Livro</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="livros-destaque">
    <h2>Livros em Destaque</h2>
    <div class="livros-grid">
        <?php
        // Livros em destaque (exemplo)
        $destaques = [
            [
                'titulo' => 'A Arte da Programação',
                'autor' => 'Carlos Silva',
                'capa' => 'destaque1.jpg',
                'link' => 'livro.php?id=1'
            ],
            [
                'titulo' => 'O Segredo das Estrelas',
                'autor' => 'Ana Oliveira',
                'capa' => 'destaque2.jpg',
                'link' => 'livro.php?id=2'
            ],
            [
                'titulo' => 'Viagem ao Centro do Conhecimento',
                'autor' => 'Miguel Santos',
                'capa' => 'destaque3.jpg',
                'link' => 'livro.php?id=3'
            ],
            [
                'titulo' => 'Histórias da Minha Terra',
                'autor' => 'Beatriz Costa',
                'capa' => 'destaque4.jpg',
                'link' => 'livro.php?id=4'
            ]
        ];
        
        foreach ($destaques as $livro): ?>
            <div class="livro-card">
                <a href="<?php echo $livro['link']; ?>">
                    <img src="assets/images/capas/<?php echo $livro['capa']; ?>" 
                         alt="<?php echo htmlspecialchars($livro['titulo']); ?>">
                    <h3><?php echo htmlspecialchars($livro['titulo']); ?></h3>
                    <p>por <?php echo htmlspecialchars($livro['autor']); ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<section class="testimonials-section">
    <div class="testimonials-header">
        <h2>Vozes dos Nossos Leitores</h2>
        <p>Descubra o que estão dizendo sobre essa experiência literária</p>
    </div>
    
    <div class="testimonials-carousel">
        <div class="testimonial-card">
            <div class="quote-icon">“</div>
            <div class="testimonial-content">
                <p class="testimonial-text">Esta biblioteca mudou minha forma de ler! A variedade de livros é incrível e a comunidade é acolhedora.</p>
                <div class="testimonial-author">
                    <img src="assets\images\USER\OIP.jpeg" alt="Ana Carolina" class="author-avatar">
                    <div class="author-info">
                        <h4>Caua de Carolina</h4>
                        <div class="author-rating">
                            ★ ★ ★ ★ ★
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="testimonial-card">
            <div class="quote-icon">“</div>
            <div class="testimonial-content">
                <p class="testimonial-text">Finalmente encontrei um lugar onde posso compartilhar meus livros favoritos e descobrir novas pérolas literárias.</p>
                <div class="testimonial-author">
                    <img src="assets\images\USER\OIP (1).jpeg" alt="Carlos Eduardo" class="author-avatar">
                    <div class="author-info">
                        <h4>Machel Arsenio</h4>
                        <div class="author-rating">
                            ★ ★ ★ ★ ☆
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="testimonial-card">
            <div class="quote-icon">“</div>
            <div class="testimonial-content">
                <p class="testimonial-text">Como estudante, essa biblioteca foi uma salvação! Acesso gratuito a tantos títulos acadêmicos e literários.</p>
                <div class="testimonial-author">
                    <img src="assets\images\USER\OSK.jpeg" alt="Mariana Santos" class="author-avatar">
                    <div class="author-info">
                        <h4>Mario Melembe</h4>
                        <div class="author-rating">
                            ★ ★ ★ ★ ★
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="testimonials-cta">
        <p>Quer compartilhar sua experiência?</p>
        <a href="#share-testimonial" class="btn btn-outline">Deixe seu depoimento</a>
    </div>
</section>
<section class="estatisticas">
    <div class="container">
        <h2>Estatisticas da Biblioteca</h2>
        <div class="stats-grid">
            <!-- Livros Cadastrados -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number" data-count="5000">9</span>
                    <span class="stat-label">Livros Disponíveis</span>
                </div>
            </div>
            
            <!-- Usuários Registrados -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number" data-count="12000">7</span>
                    <span class="stat-label">Leitores Ativos</span>
                </div>
            </div>
            
            <!-- Downloads -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-download"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number" data-count="85000">6</span>
                    <span class="stat-label">Downloads</span>
                </div>
            </div>
            
            <!-- Categorias -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number" data-count="35">10</span>
                    <span class="stat-label">Categorias</span>
                </div>
            </div>
        </div>
    </div>
</section>

    </main>
    <?php include 'includes/footer.php'; ?>
    <div class="help-box">
    <div class="help-box-header">
        <span>Precisa de ajuda?</span>
        <button class="help-box-close">&times;</button>
    </div>
    <div class="help-box-content">
        <p>Estamos aqui para te ajudar! Envie sua dúvida.</p>
        <form class="help-form">
            <textarea placeholder="Digite sua mensagem..." required></textarea>
            <button type="submit" class="btn btn-small">Enviar</button>
        </form>
    </div>
</div>
    <script src="assets/js/script.js"></script>

</body>
</html>