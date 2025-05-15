<?php
// Verificação de sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexão simplificada
if (!isset($conn)) {
    require_once __DIR__ . '/config.php';
}

// Definir base path dinamicamente
$is_admin = strpos($_SERVER['REQUEST_URI'], '/admin/') !== false;
$base_path = $is_admin ? '../' : '';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Biblioteca Online'; ?></title>
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<header>
    <div class="container">
        <a href="<?php echo $base_path; ?>index.php" class="logo">Biblioteca Online</a>
        <nav>
            <ul>
                <li><a href="<?php echo $base_path; ?>index.php">Início</a></li>
                <li><a href="<?php echo $base_path; ?>categorias.php">Categorias</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo $base_path; ?>perfil.php">Perfil</a></li>
                    <?php if ($_SESSION['user_type'] === 'admin'): ?>
                        <li><a href="<?php echo $is_admin ? 'dashboard.php' : 'admin/dashboard.php'; ?>">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo $is_admin ? 'logout.php' : $base_path.'logout.php'; ?>">Sair</a></li>
                <?php else: ?>
                    <li><a href="<?php echo $base_path; ?>login.php">Login</a></li>
                    <li><a href="<?php echo $base_path; ?>registro.php">Registrar</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>