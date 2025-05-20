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
    // Chat Flutuante - Funcionalidade Básica
document.addEventListener('DOMContentLoaded', function() {
    const chatFlutuante = document.getElementById('chatFlutuante');
    const chatCabecalho = document.getElementById('chatCabecalho');
    const chatCorpo = document.getElementById('chatCorpo');
    
    // Alternar visibilidade do chat
    chatCabecalho.addEventListener('click', function() {
        chatFlutuante.classList.toggle('aberto');
        
        // Se estiver aberto, carrega as mensagens
        if (chatFlutuante.classList.contains('aberto') && document.getElementById('chatEnviarBtn')) {
            carregarMensagens();
        }
    });
    
    // Função para carregar mensagens (apenas para usuários logados)
    function carregarMensagens() {
        if (!document.getElementById('chatEnviarBtn')) return;
        
        fetch('api/get_chat.php')
            .then(response => {
                if (!response.ok) throw new Error('Erro ao carregar mensagens');
                return response.json();
            })
            .then(mensagens => {
                const chatMensagens = document.getElementById('chatMensagens');
                chatMensagens.innerHTML = '';
                
                mensagens.forEach(msg => {
                    const hora = new Date(msg.data_envio).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    chatMensagens.innerHTML += `
                        <div class="chat-mensagem">
                            <span class="chat-mensagem-usuario">${msg.nome}:</span>
                            <span class="chat-mensagem-texto">${msg.mensagem}</span>
                            <div class="chat-mensagem-hora">${hora}</div>
                        </div>
                    `;
                });
                chatMensagens.scrollTop = chatMensagens.scrollHeight;
            })
            .catch(error => {
                console.error('Erro:', error);
                document.getElementById('chatMensagens').innerHTML = 
                    '<div class="chat-erro">Erro ao carregar mensagens</div>';
            });
    }
    
    // Enviar mensagem (apenas para usuários logados)
    if (document.getElementById('chatEnviarBtn')) {
        const mensagemInput = document.getElementById('chatMensagemInput');
        const enviarBtn = document.getElementById('chatEnviarBtn');
        
        enviarBtn.addEventListener('click', enviarMensagem);
        
        mensagemInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                enviarMensagem();
            }
        });
        
        function enviarMensagem() {
            const mensagem = mensagemInput.value.trim();
            if (!mensagem) return;
            
            fetch('api/send_chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `mensagem=${encodeURIComponent(mensagem)}`
            })
            .then(response => {
                if (!response.ok) throw new Error('Erro ao enviar');
                mensagemInput.value = '';
                return carregarMensagens();
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao enviar mensagem');
            });
        }
        
        // Atualizar mensagens a cada 5 segundos quando aberto
        let chatInterval;
        chatFlutuante.addEventListener('click', function() {
            if (chatFlutuante.classList.contains('aberto')) {
                chatInterval = setInterval(carregarMensagens, 5000);
            } else {
                clearInterval(chatInterval);
            }
        });
    }
});
// Carrossel de depoimentos
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('.testimonials-carousel');
    if (carousel) {
        let isDown = false;
        let startX;
        let scrollLeft;

        carousel.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - carousel.offsetLeft;
            scrollLeft = carousel.scrollLeft;
            carousel.style.cursor = 'grabbing';
        });

        carousel.addEventListener('mouseleave', () => {
            isDown = false;
            carousel.style.cursor = 'grab';
        });

        carousel.addEventListener('mouseup', () => {
            isDown = false;
            carousel.style.cursor = 'grab';
        });

        carousel.addEventListener('mousemove', (e) => {
            if(!isDown) return;
            e.preventDefault();
            const x = e.pageX - carousel.offsetLeft;
            const walk = (x - startX) * 2;
            carousel.scrollLeft = scrollLeft - walk;
        });

        // Toque para mobile
        carousel.addEventListener('touchstart', (e) => {
            isDown = true;
            startX = e.touches[0].pageX - carousel.offsetLeft;
            scrollLeft = carousel.scrollLeft;
        });

        carousel.addEventListener('touchend', () => {
            isDown = false;
        });

        carousel.addEventListener('touchmove', (e) => {
            if(!isDown) return;
            const x = e.touches[0].pageX - carousel.offsetLeft;
            const walk = (x - startX) * 2;
            carousel.scrollLeft = scrollLeft - walk;
        });
    }
});
// Esta versão não precisa de JavaScript para funcionar
// Mas caso queira mais controle, pode usar:
document.addEventListener('DOMContentLoaded', function() {
    const carrossel = document.querySelector('.carrossel');
    const inner = document.querySelector('.carrossel-inner');
    const items = document.querySelectorAll('.carrossel-item');
    let current = 0;
    
    function nextSlide() {
        current = (current + 1) % items.length;
        inner.style.transform = `translateX(-${current * 100 / items.length}%)`;
    }
    
    // Alternativa ao CSS animation
    setInterval(nextSlide, 5000);
    
    // Pausa no hover
    carrossel.addEventListener('mouseenter', () => {
        inner.style.animationPlayState = 'paused';
    });
    
    carrossel.addEventListener('mouseleave', () => {
        inner.style.animationPlayState = 'running';
    });
});
// Animação para contar os números
    document.addEventListener('DOMContentLoaded', function() {
        const statNumbers = document.querySelectorAll('.stat-number');
        
        statNumbers.forEach(stat => {
            const target = parseInt(stat.getAttribute('data-count'));
            const duration = 2000; // 2 segundos
            const step = target / (duration / 16);
            let current = 0;
            
            const updateNumber = () => {
                current += step;
                if (current < target) {
                    stat.textContent = Math.floor(current);
                    requestAnimationFrame(updateNumber);
                } else {
                    stat.textContent = target.toLocaleString();
                }
            };
            
            // Inicia a animação quando o elemento estiver visível
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    updateNumber();
                    observer.unobserve(stat);
                }
            });
            
            observer.observe(stat);
        });
    });
    
});