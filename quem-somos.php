<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = 'Quem Somos - Biblioteca Online';
include 'includes/header.php';
?>

<main class="container">
    <section class="about-section">
        <h1>Quem Somos</h1>
        
        <div class="about-content">
            <div class="about-text">
                <h2>Nossa História</h2>
                <p>A Biblioteca Online nasceu em 2025 com o objetivo de democratizar o acesso à leitura e conectar amantes de livros em todo o mundo. Fundada por Alberto, Leidy e Esperança, nosso projeto começou pequeno mas já impactou milhares de leitores.</p>
                
                <h2>Nossa Missão</h2>
                <p>Promover o acesso gratuito ao conhecimento e fomentar a comunidade literária, proporcionando uma plataforma onde leitores podem compartilhar e descobrir novos livros.</p>
                
                <h2>Equipe</h2>
                <div class="team-grid">
                    <div class="team-member">
                        <img src="assets\images\USER\beto.jpg" alt="Alberto">
                        <h3>Alberto</h3>
                        <p>Desenvolvedor</p>
                        <p>Designer</p>
                    </div>
                    <div class="team-member">
                        <img src="assets\images\USER\leid.jpg" alt="Leidy">
                        <h3>Leidy</h3>
                        <p>Gerente de Conteúdo</p>
                    </div>
                    <div class="team-member">
                        <img src="assets\images\USER\esp.jpg" alt="Esperança">
                        <h3>Esperança</h3>
                        <p>Gerente de Conteúdo</p>
                    </div>
                </div>
            </div>
            
            <div class="about-image">
                <img src="assets\images\USER\3D-Developer.ico" alt="Equipe da Biblioteca Online">
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>