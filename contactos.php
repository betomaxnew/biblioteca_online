<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = 'Contactos - Biblioteca Online';
include 'includes/header.php';

// Processar formulário de contato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);
    
    // Aqui você pode adicionar o código para enviar o email
    $sucesso = true; // Simulação de envio bem-sucedido
}
?>

<main class="container">
    <section class="contact-section">
        <h1>Contacte-nos</h1>
        
        <div class="contact-container">
            <div class="contact-info">
                <h2>Informações de Contacto</h2>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> Av. da Leitura, 123 - Cidade dos Livros</li>
                    <li><i class="fas fa-phone"></i> +351 123 456 789</li>
                    <li><i class="fas fa-envelope"></i> contacto@bibliotecaonline.com</li>
                </ul>
                
                <div class="social-media">
                    <h3>Siga-nos</h3>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div class="contact-form">
                <?php if (isset($sucesso) && $sucesso): ?>
                    <div class="alert alert-success">
                        Obrigado pela sua mensagem! Entraremos em contacto brevemente.
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="mensagem">Mensagem:</label>
                        <textarea id="mensagem" name="mensagem" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>