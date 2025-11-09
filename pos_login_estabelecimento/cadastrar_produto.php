<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';

// Verifica login
if(!isset($_SESSION['id_usuario'])){
    header("Location: /FoodLog/menu/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$mensagem = "";

// Processa formulário
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome = trim($_POST['product_name']);
    $descricao = trim($_POST['product_description']);
    $validade = $_POST['product_validity']; // tipo YYYY-MM-DD
    $quantidade = $_POST['product_quantity'];
    $unidade = $_POST['product_unit'];

    // Valida campos
    if(empty($nome) || empty($descricao) || empty($validade) || empty($quantidade) || empty($unidade)){
        $mensagem = "❌ Todos os campos são obrigatórios!";
    } elseif(!isset($_FILES['product_image']) || $_FILES['product_image']['error'] !== 0){
        $mensagem = "❌ Erro no envio da imagem!";
    } else {
        // Valida quantidade
        if(!is_numeric($quantidade) || $quantidade <= 0){
            $mensagem = "❌ Quantidade inválida!";
        } else {
            // Upload da imagem
            $upload_dir = $_SERVER['DOCUMENT_ROOT'].'/FoodLog/uploads/';
            if(!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            $ext = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
            $nome_arquivo = uniqid() . '.' . $ext;
            $caminho_absoluto = $upload_dir . $nome_arquivo;
            $caminho_relativo = '/FoodLog/uploads/' . $nome_arquivo; // para exibir na página

            if(move_uploaded_file($_FILES['product_image']['tmp_name'], $caminho_absoluto)){
                $sql = "INSERT INTO produtos (id_usuario, nome, descricao, validade, quantidade, unidade, imagem) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if($stmt){
                    $stmt->bind_param("issssss", $id_usuario, $nome, $descricao, $validade, $quantidade, $unidade, $caminho_relativo);
                    if($stmt->execute()){
                        $mensagem = "✅ Produto cadastrado com sucesso!";
                        // Limpa campos após cadastro
                        $nome = $descricao = $validade = $quantidade = $unidade = "";
                    } else {
                        $mensagem = "❌ Erro ao salvar produto no banco.";
                    }
                    $stmt->close();
                } else {
                    $mensagem = "❌ Erro na preparação da query: ".$conn->error;
                }
            } else {
                $mensagem = "❌ Não foi possível mover a imagem para a pasta uploads.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodLog - Cadastrar Produto</title>
    <link rel="stylesheet" href="/FoodLog/css/card.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
    <style>
        .message { padding: 10px; margin: 15px 0; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        form label { display: block; margin-top: 10px; }
        form input, form textarea, form select, form button { width: 100%; margin-top: 5px; padding: 8px; }
        form button { margin-top: 15px; background-color: #4CAF50; color: #fff; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <header>
        <div class="header-inner">
            <h1>Dashboard - Estabelecimento</h1>
            <nav>
                <ul>
                    <li><a href="/FoodLog/pos_login_estabelecimento/notificacao.php">Notificações</a></li>
                    <li><a href="/FoodLog/pos_login_estabelecimento/meus_produtos.php">Meus Produtos</a></li>
                    <li><a href="/FoodLog/pos_login_estabelecimento/cadastrar_produto.php">Cadastrar Produtos</a></li>
                    <li><a href="/FoodLog/pos_login_estabelecimento/dashboard_estabelecimento.php">Atualizar Cadastro</a></li>
                    <li><a href="/FoodLog/menu/index.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <h2>Cadastrar Produto</h2>

        <?php if($mensagem): ?>
            <div class="message <?= strpos($mensagem,'✅') !== false ? 'success' : 'error' ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form id="product-form" method="POST" enctype="multipart/form-data">
            <label>Imagem do Produto</label>
            <input type="file" name="product_image" accept="image/*" />
            <?php if(!empty($produto['imagem'])): ?>
                <br>
                <span>Imagem atual:</span><br>
                <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem do produto" width="100">
            <?php endif; ?>


            <label>Nome do Produto</label>
            <input type="text" id="product-name" name="product_name" placeholder="Nome do produto" value="<?= isset($nome) ? htmlspecialchars($nome) : '' ?>" required />

            <label>Descrição do Produto</label>
            <textarea id="product-description" name="product_description" placeholder="Descrição do produto" required><?= isset($descricao) ? htmlspecialchars($descricao) : '' ?></textarea>

            <label>Validade</label>
            <input type="date" id="product-validity" name="product_validity" value="<?= isset($validade) ? htmlspecialchars($validade) : '' ?>" required />

            <label>Quantidade em Estoque</label>
            <input type="number" id="product-quantity" name="product_quantity" min="1" value="<?= isset($quantidade) ? htmlspecialchars($quantidade) : '' ?>" required />

            <label>Unidade</label>
            <select name="product_unit" required>
                <option value="">Selecione</option>
                <option value="unidade" <?= (isset($unidade) && $unidade === 'unidade') ? 'selected' : '' ?>>Unidade</option>
                <option value="kg" <?= (isset($unidade) && $unidade === 'kg') ? 'selected' : '' ?>>Kg</option>
                <option value="litro" <?= (isset($unidade) && $unidade === 'litro') ? 'selected' : '' ?>>Litro</option>
                <option value="pacote" <?= (isset($unidade) && $unidade === 'pacote') ? 'selected' : '' ?>>Pacote</option>
            </select>

            <button type="submit">Cadastrar</button>
        </form>
    </main>
</body>
</html>
