<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$livro = getLivroById($_GET['id']);

if (!$livro) {
    header("Location: index.php");
    exit();
}

$filepath = 'uploads/' . $livro['caminho_arquivo'];

if (file_exists($filepath)) {
    // Registrar download
    $stmt = $conn->prepare("UPDATE livros SET downloads = downloads + 1 WHERE id = ?");
    $stmt->execute([$livro['id']]);
    
    // Forçar download
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="'.basename($livro['titulo']).'.pdf"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));
    readfile($filepath);
    exit;
} else {
    header("Location: livro.php?id=".$livro['id']."&erro=arquivo_nao_encontrado");
}