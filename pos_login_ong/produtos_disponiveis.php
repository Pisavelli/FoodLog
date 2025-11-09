<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /FoodLog/menu/login.php");
    exit;
}

if (isset($_POST['add_to_cart'])) {
    $id_produto = intval($_POST['id_produto']);
    $quantidade = intval($_POST['quantidade']);

    if ($quantidade > 0) {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        $_SESSION['carrinho'][$id_produto] = 
            ($_SESSION['carrinho'][$id_produto] ?? 0) + $quantidade;

        $mensagem = "Produto adicionado ao carrinho!";
    }
}

$query = "SELECT p.*, u.nome_usuario, e.nome_estabelecimento
          FROM produtos p
          INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
          INNER JOIN estabelecimentos e ON u.id_estabelecimento = e.id_estabelecimento
          ORDER BY p.cadastro DESC";
$result = $conn->query($query);
$produtos = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Produtos Disponíveis</title>
    <link rel="stylesheet" href="../css/card.css">
</head>
<body>
<header>
    <div class="header-inner">
        <h1>FoodLog</h1>
        <nav>
            <ul>
                <li><a href="produtos_disponiveis.php">Produtos Disponíveis</a></li>
                <li><a href="carrinho.php">Carrinho</a></li>
                <li><a href="dashboard_ong.php">Atualizar Cadastro</a></li>
                <li><a href="/FoodLog/menu/index.php">Sair</a></li>
            </ul>
        </nav>
    </div>
</header>

<h2>Produtos Disponíveis</h2>
<?php if (!empty($mensagem)) echo "<p>$mensagem</p>"; ?>

<div class="card-container">
    <?php foreach ($produtos as $produto): ?>
        <div class="card">
            <h3><?= htmlspecialchars($produto['nome']) ?></h3>
            <p><?= htmlspecialchars($produto['descricao']) ?></p>
            <p><strong>Quantidade disponível:</strong> <?= $produto['quantidade'] ?></p>
            <p><strong>Validade:</strong> <?= $produto['validade'] ?></p>
            <p><strong>Estabelecimento:</strong> <?= $produto['nome_estabelecimento'] ?></p>
            <form method="POST">
                <input type="hidden" name="id_produto" value="<?= $produto['id'] ?>">
                <input type="number" name="quantidade" value="1" min="1" max="<?= $produto['quantidade'] ?>">
                <button type="submit" name="add_to_cart">Adicionar ao carrinho</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
