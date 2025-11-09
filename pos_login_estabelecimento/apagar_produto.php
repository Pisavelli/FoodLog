<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /FoodLog/menu/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Produto inválido.");
}

$id_produto = intval($_GET['id']);

// --- Busca a imagem do produto antes de apagar ---
$stmt = $conn->prepare("SELECT imagem FROM produtos WHERE id = ? AND id_usuario = ?");
$stmt->bind_param("ii", $id_produto, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Produto não encontrado.");
}

$produto = $result->fetch_assoc();
$imagem = $produto['imagem'];
$stmt->close();

// --- Deleta o produto ---
$stmt = $conn->prepare("DELETE FROM produtos WHERE id = ? AND id_usuario = ?");
$stmt->bind_param("ii", $id_produto, $id_usuario);

if ($stmt->execute()) {
    // Deleta o arquivo de imagem, se existir
    if (!empty($imagem) && file_exists($_SERVER['DOCUMENT_ROOT'] . $imagem)) {
        unlink($_SERVER['DOCUMENT_ROOT'] . $imagem);
    }
    $stmt->close();
    header("Location: /FoodLog/pos_login_estabelecimento/meus_produtos.php?msg=Produto+apagado+com+sucesso");
    exit;
} else {
    die("Erro ao apagar o produto.");
}
?>
