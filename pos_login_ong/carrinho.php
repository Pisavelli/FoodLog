<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';

$carrinho = $_SESSION['carrinho'] ?? [];

$produtos = [];
if (!empty($carrinho)) {
    $ids = implode(',', array_keys($carrinho));
    $result = $conn->query("SELECT * FROM produtos WHERE id IN ($ids)");
    $produtos = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
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

<h2>Seu Carrinho</h2>
<?php if (empty($carrinho)): ?>
    <p>Seu carrinho está vazio.</p>
    <a href="produtos_disponiveis.php">Voltar aos produtos</a>
<?php else: ?>
    <ul>
        <?php foreach ($produtos as $produto): ?>
            <li>
                <?= htmlspecialchars($produto['nome']) ?> - 
                Quantidade escolhida: <?= $carrinho[$produto['id']] ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <form method="POST" action="finalizar.php">
        <button type="submit" name="finalizar_pedido">Finalizar Pedido</button>
    </form>
<?php endif; ?>
</body>
</html>
