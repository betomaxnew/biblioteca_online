<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = 'Feedback - Biblioteca Online';
include 'includes/header.php';

// Processar envio de feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);
    $nome = isLoggedIn() ? null : filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    
    if (!empty($mensagem)) {
        $stmt = $conn->prepare("INSERT INTO feedbacks (usuario_id, nome, mensagem) VALUES (?, ?, ?)");
        $stmt->execute([
            isLoggedIn() ? $_SESSION['user_id'] : null,
            $nome,
            $mensagem
        ]);
        
        $feedback_msg = "Obrigado pelo seu feedback!";
    }
}

// Obter feedbacks aprovados
$feedbacks = $conn->query("
    SELECT f.*, COALESCE(u.nome, f.nome) AS autor 
    FROM feedbacks f
    LEFT JOIN usuarios u ON f.usuario_id = u.id
    WHERE f.aprovado = TRUE
    ORDER BY f.data_publicacao DESC
    LIMIT 50
")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container">
    <section class="feedback-section">
        <h1>Deixe seu Feedback</h1>
        
        <?php if (isset($feedback_msg)): ?>
            <div class="alert alert-success"><?= $feedback_msg ?></div>
        <?php endif; ?>
        
        <div class="feedback-container">
            <div class="feedback-form">
                <form method="POST">
                    <?php if (!isLoggedIn()): ?>
                        <div class="form-group">
                            <label for="nome">Seu Nome (opcional):</label>
                            <input type="text" id="nome" name="nome">
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="mensagem">Seu Feedback:</label>
                        <textarea id="mensagem" name="mensagem" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Enviar Feedback</button>
                </form>
            </div>
            
            <div class="feedback-list">
                <h2>Últimos Feedbacks</h2>
                
                <?php if (count($feedbacks) > 0): ?>
                    <?php foreach ($feedbacks as $feedback): ?>
                        <div class="feedback-item">
                            <div class="feedback-header">
                                <span class="feedback-author"><?= htmlspecialchars($feedback['autor'] ?: 'Anônimo') ?></span>
                                <span class="feedback-date"><?= date('d/m/Y H:i', strtotime($feedback['data_publicacao'])) ?></span>
                            </div>
                            <div class="feedback-content">
                                <?= nl2br(htmlspecialchars($feedback['mensagem'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Ainda não há feedbacks publicados. Seja o primeiro!</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>