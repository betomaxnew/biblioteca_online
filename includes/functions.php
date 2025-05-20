<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

function criarCartaoUsuario($usuario_id) {
    global $conn;
    
    $codigo = gerarCodigoCartao();
    $stmt = $conn->prepare("INSERT INTO cartoes (usuario_id, codigo) VALUES (?, ?)");
    $stmt->execute([$usuario_id, $codigo]);
    
    return $codigo;
}

function getCartaoAtivo($usuario_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM cartoes WHERE usuario_id = ? AND ativo = TRUE ORDER BY id DESC LIMIT 1");
    $stmt->execute([$usuario_id]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function decrementarPublicacaoCartao($cartao_id) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE cartoes SET publicacoes_restantes = publicacoes_restantes - 1 WHERE id = ?");
    $stmt->execute([$cartao_id]);
}

function getLivrosRecentes($limit = 10) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT l.*, u.nome as autor_nome FROM livros l JOIN usuarios u ON l.usuario_id = u.id ORDER BY data_publicacao DESC LIMIT :limit");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLivroById($id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT l.*, u.nome as autor_nome FROM livros l JOIN usuarios u ON l.usuario_id = u.id WHERE l.id = ?");
    $stmt->execute([$id]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getLivrosByUsuario($usuario_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM livros WHERE usuario_id = ? ORDER BY data_publicacao DESC");
    $stmt->execute([$usuario_id]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function registrarVisualizacao($livro_id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE livros SET visualizacoes = visualizacoes + 1 WHERE id = ?");
    $stmt->execute([$livro_id]);
}

function formatarTamanhoLivro($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}
function getImagemCategoria($categoria_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT imagem FROM categorias WHERE id = ?");
    $stmt->execute([$categoria_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && file_exists("assets/images/categorias/" . $result['imagem'])) {
        return "assets/images/categorias/" . $result['imagem'];
    }
    return "assets/images/categorias/default-category.jpg";
}




?>