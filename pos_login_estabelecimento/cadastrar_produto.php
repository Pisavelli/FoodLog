<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';  

// Checar login do usuário
if(!isset($_SESSION['id_usuario'])){
    header("Location: /FoodLog/menu/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$mensagem = "";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['product_name'];
    $descricao = $_POST['product_description'];
    $validade = $_POST['product_validity'];
    $quantidade = $_POST['product_quantity'];

    // Tratar upload de imagem
    if(isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK){
        $uploadDir = $_SERVER['DOCUMENT_ROOT'].'/FoodLog/uploads/';
        $filename = uniqid() . '_' . basename($_FILES['product_image']['name']);
        $targetFile = $uploadDir . $filename;

        if(move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFile)){
            // Inserir no banco
            $sql = "INSERT INTO produtos (id_usuario, nome, descricao, validade, quantidade, imagem) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssis", $id_usuario, $nome, $descricao, $validade, $quantidade, $filename);

            if($stmt->execute()){
                $mensagem = "✅ Produto cadastrado com sucesso!";
            } else {
                $mensagem = "❌ Erro ao cadastrar produto: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $mensagem = "❌ Erro ao enviar a imagem.";
        }
    } else {
        $mensagem = "❌ Nenhuma imagem selecionada ou erro no upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/FoodLog/css/card.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
    <title>Restaurante - FoodLog</title>
</head>
<body>
    <header>
        <div class="header-inner">
            <h1>FoodLog - Estabelecimento</h1>
            <nav>
                <ul>
                    <li><a href="/FoodLog/pos_login_estabelecimento/dashboard_estabelecimento.php">Início</a></li>
                    <li><a href="/FoodLog/pos_login_estabelecimento/notificacao.php">Notificações</a></li>
                    <li><a href="/FoodLog/pos_login_estabelecimento/meus_produtos.php">Meus Produtos</a></li>
                    <li><a href="/FoodLog/pos_login_estabelecimento/cadastrar_produto.php">Cadastrar Produtos</a></li>
                    <li><a href="/FoodLog/menu/index.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>
<div class="container">
    <h2>Cadastrar Produto</h2>

    <?php if($mensagem): ?>
        <div class="message <?= strpos($mensagem,'✅') !== false ? 'success' : 'error' ?>">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>

    <form id="product-form" method="POST" enctype="multipart/form-data">
        <label>Imagem do Produto</label>
        <input type="file" id="product-image" name="product_image" accept="image/*" required />

        <label>Nome do Produto</label>
        <input type="text" id="product-name" name="product_name" placeholder="Nome do produto" required />

        <label>Descrição do Produto</label>
        <textarea id="product-description" name="product_description" placeholder="Descrição do produto" required></textarea>

        <label>Validade</label>
        <input type="date" id="product-validity" name="product_validity" required />

        <label>Quantidade</label>
        <input type="text" id="product-quantity" name="product_quantity" placeholder="Quantidade em Kg, litros, ou unidade" required />

        <button type="submit">Cadastrar</button>
    </form>
</div>
</body>
</html>
