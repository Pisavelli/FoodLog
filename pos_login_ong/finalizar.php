<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['finalizar_pedido'])) {
    $carrinho = $_SESSION['carrinho'] ?? [];

    if (!empty($carrinho)) {
        $id_ong = $_SESSION['id_usuario']; // ONG logada

        // Buscar os produtos do carrinho
        $ids = implode(',', array_keys($carrinho));
        $result = $conn->query("SELECT * FROM produtos WHERE id IN ($ids)");
        $produtos = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        // Criar pedido (um único pedido para todos os itens)
        // Aqui id_usuario é o dono do produto, mas como temos vários, podemos deixar NULL ou pegar do primeiro
        $id_usuario = !empty($produtos) ? $produtos[0]['id_usuario'] : 0;

        $query_pedido = "INSERT INTO pedidos (id_usuario, id_ong, data_pedido)
                         VALUES ('$id_usuario', '$id_ong', NOW())";
        if ($conn->query($query_pedido)) {
            $id_pedido = $conn->insert_id;

            // Inserir itens
            foreach ($carrinho as $id_produto => $quantidade) {
                $conn->query("INSERT INTO pedido_item (id_pedido, id_produto, quantidade)
                              VALUES ('$id_pedido', '$id_produto', '$quantidade')");
            }
        }
    }

    // Limpa carrinho
    $_SESSION['carrinho'] = [];
}

// Agora, em vez de redirecionar, vamos listar os pedidos da ONG logada
$id_ong = $_SESSION['id_usuario'];
$query_pedidos = "
    SELECT p.id, p.data_pedido, u.nome_usuario, pr.nome AS nome_produto, pi.quantidade
    FROM pedidos p
    INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
    INNER JOIN pedido_item pi ON p.id = pi.id_pedido
    INNER JOIN produtos pr ON pi.id_produto = pr.id
    WHERE p.id_ong = '$id_ong'
    ORDER BY p.data_pedido DESC
";
$result = $conn->query($query_pedidos);
$pedidos = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedidos da ONG</title>
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

<h2>Pedidos realizados pela ONG</h2>
<?php if (empty($pedidos)): ?>
    <p>Nenhum pedido realizado ainda.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID Pedido</th>
            <th>Data</th>
            <th>Estabelecimento</th>
            <th>Produto</th>
            <th>Quantidade</th>
        </tr>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?= $pedido['id'] ?></td>
                <td><?= $pedido['data_pedido'] ?></td>
                <td><?= htmlspecialchars($pedido['nome_usuario']) ?></td>
                <td><?= htmlspecialchars($pedido['nome_produto']) ?></td>
                <td><?= $pedido['quantidade'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
</body>
</html>
