// Funções JavaScript básicas podem ser adicionadas aqui
document.addEventListener('DOMContentLoaded', function() {
    // Menu mobile (opcional)
    const menuToggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('nav ul');
    
    if (menuToggle && nav) {
        menuToggle.addEventListener('click', function() {
            nav.classList.toggle('active');
        });
    }
    
    // Mensagens flash (opcional)
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
    
    // Validação de formulários (opcional)
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'red';
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
            }
        });
    });
});
// Caixa de Ajuda
document.addEventListener('DOMContentLoaded', function() {
    const helpBox = document.querySelector('.help-box');
    const helpBoxClose = document.querySelector('.help-box-close');
    
    // Alternar visibilidade
    helpBox.addEventListener('click', function(e) {
        if (e.target.closest('.help-box-header')) {
            const content = this.querySelector('.help-box-content');
            content.style.display = content.style.display === 'none' ? 'block' : 'none';
        }
    });
    
    // Fechar a caixa
    helpBoxClose.addEventListener('click', function(e) {
        e.stopPropagation();
        helpBox.style.display = 'none';
    });
    
    // Formulário de ajuda
    const helpForm = document.querySelector('.help-form');
    if (helpForm) {
        helpForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = this.querySelector('textarea').value;
            // Aqui você pode adicionar AJAX para enviar a mensagem
            alert('Obrigado por sua mensagem! Entraremos em contato em breve.');
            this.querySelector('textarea').value = '';
        });
    }
});