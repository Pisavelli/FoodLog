<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';

// --- Verifica login ---
if (!isset($_SESSION['id_usuario'])) {
    header("Location: /FoodLog/menu/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// --- Pega ID do produto ---
if (!isset($_GET['id']) || empty($_GET['id'])) die("Produto inválido.");
$id_produto = intval($_GET['id']);

// --- Busca dados do produto ---
$stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ? AND id_usuario = ?");
$stmt->bind_param("ii", $id_produto, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) die("Produto não encontrado.");
$produto = $result->fetch_assoc();
$stmt->close();

// --- Mensagem de status ---
$mensagem = "";

// --- Processa formulário ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['product_name']);
    $descricao = trim($_POST['product_description']);
    $validade = $_POST['product_validity'];
    $quantidade = intval($_POST['product_quantity']);
    $unidade = $_POST['product_unit'];

    // --- Inicializa a imagem atual ---
    $imagem_atual = $produto['imagem'];

    // --- Upload de nova imagem (opcional) ---
    if (!empty($_FILES['product_image']['name']) && $_FILES['product_image']['error'] === 0) {
        $ext = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
        $nome_arquivo = uniqid() . '.' . $ext;
        $destino = $_SERVER['DOCUMENT_ROOT'] . "/FoodLog/uploads/" . $nome_arquivo;

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $destino)) {
            $imagem_atual = "/FoodLog/uploads/" . $nome_arquivo;
        } else {
            $mensagem = "❌ Falha ao enviar a imagem!";
        }
    }

    // --- Atualiza banco ---
    $stmt = $conn->prepare("UPDATE produtos SET nome=?, descricao=?, validade=?, quantidade=?, unidade=?, imagem=? WHERE id=? AND id_usuario=?");
    $stmt->bind_param("sssisiii", $nome, $descricao, $validade, $quantidade, $unidade, $imagem_atual, $id_produto, $id_usuario);

    if ($stmt->execute()) {
        $mensagem = "✅ Produto atualizado com sucesso!";
        // Atualiza localmente para mostrar no formulário
        $produto['nome'] = $nome;
        $produto['descricao'] = $descricao;
        $produto['validade'] = $validade;
        $produto['quantidade'] = $quantidade;
        $produto['unidade'] = $unidade;
        $produto['imagem'] = $imagem_atual;
    } else {
        $mensagem = "❌ Erro ao atualizar o produto!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Produto</title>
<link rel="stylesheet" href="/FoodLog/css/card.css">
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

<div class="container">
<h2>Editar Produto</h2>

<?php if($mensagem): ?>
    <div class="message <?= strpos($mensagem,'✅') !== false ? 'success' : 'error' ?>">
        <?= htmlspecialchars($mensagem) ?>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Imagem do Produto</label>
    <input type="file" name="product_image" accept="image/*" />
    <?php if(!empty($imagem_atual)): ?>
        <br>
        <span>Imagem atual:</span><br>
        <img src="<?= htmlspecialchars($imagem_atual) ?>" alt="Imagem do produto" width="150">
    <?php endif; ?>

    <label>Nome do Produto</label>
    <input type="text" name="product_name" value="<?= htmlspecialchars($produto['nome']) ?>" required />

    <label>Descrição do Produto</label>
    <textarea name="product_description" required><?= htmlspecialchars($produto['descricao']) ?></textarea>

    <label>Validade</label>
    <input type="date" name="product_validity" value="<?= htmlspecialchars($produto['validade']) ?>" required />

    <label>Quantidade</label>
    <input type="number" name="product_quantity" value="<?= htmlspecialchars($produto['quantidade']) ?>" required />

    <label>Unidade</label>
    <select name="product_unit" required>
        <option value="unidade" <?= $produto['unidade']=='unidade' ? 'selected' : '' ?>>Unidade</option>
        <option value="kg" <?= $produto['unidade']=='kg' ? 'selected' : '' ?>>Kg</option>
        <option value="litro" <?= $produto['unidade']=='litro' ? 'selected' : '' ?>>Litro</option>
        <option value="pacote" <?= $produto['unidade']=='pacote' ? 'selected' : '' ?>>Pacote</option>
    </select>

    <button type="submit">Atualizar Produto</button>
</form>
</div>
</body>
</html>
