
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="logo">Biblioteca Online</div>
            
            <div class="dev-info">
                <p>Desenvolvido com ❤️ por:</p>
                <p><strong>Alberto</strong>, <strong>Leidy</strong> e <strong>Esperança</strong></p>
                <p>© <?php echo date('Y'); ?> Todos os direitos reservados</p>
            </div>
            
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-github"></i></a>
            </div>
        </div>
    </div>
</footer>
<div class="chat-flutuante" id="chatFlutuante">
    <div class="chat-cabecalho" id="chatCabecalho">
        <span>Bate-papo</span>
        <i class="fas fa-comments"></i>
    </div>
    
    <div class="chat-corpo" id="chatCorpo">
        <div class="chat-mensagens" id="chatMensagens">
            <?php if (isLoggedIn()): ?>
                <div class="chat-carregando">Carregando mensagens...</div>
            <?php else: ?>
                <div class="chat-login-required">
                    <p>Faça <a href="login.php">login</a> para participar</p>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (isLoggedIn()): ?>
            <div class="chat-input">
                <textarea id="chatMensagemInput" placeholder="Digite sua mensagem..."></textarea>
                <button id="chatEnviarBtn" class="btn btn-pequeno btn-azul">Enviar</button>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>