<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}

$backup_dir = __DIR__ . '/../backups/';
if (!file_exists($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}

$backup_file = $backup_dir . 'backup_' . date('Y-m-d_H-i-s') . '.sql';

// Comando para gerar backup (MySQL)
$command = "mysqldump --user=" . DB_USER . " --password=" . DB_PASS . " --host=" . DB_HOST . " " . DB_NAME . " > " . $backup_file;

system($command, $output);

if ($output === 0) {
    $mensagem = "Backup criado com sucesso: " . basename($backup_file);
    $tipo = 'success';
} else {
    $mensagem = "Erro ao criar backup";
    $tipo = 'danger';
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup - Biblioteca Online</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <main class="container">
        <h1>Backup do Sistema</h1>
        
        <div class="alert alert-<?php echo $tipo; ?>">
            <?php echo $mensagem; ?>
        </div>
        
        <div class="backup-actions">
            <a href="dashboard.php" class="btn btn-primary">Voltar ao Painel</a>
            <?php if ($output === 0): ?>
                <a href="../backups/<?php echo basename($backup_file); ?>" download class="btn btn-secondary">Download Backup</a>
            <?php endif; ?>
        </div>
    </main>
    
    <script src="../assets/js/script.js"></script>
</body>
</html>