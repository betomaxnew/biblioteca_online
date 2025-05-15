<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'biblioteca_online');

try {
    $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

function gerarCodigoCartao() {
    return 'CART-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
}
// Definir a URL base do projeto
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/biblioteca_online/');
define('ADMIN_URL', BASE_URL . 'admin/');
?>