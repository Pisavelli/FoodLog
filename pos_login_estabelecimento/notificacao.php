<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';  // ajuste o caminho da sua conexão

// Checar login do restaurante
if(!isset($_SESSION['id_usuario'])){
    header("Location: ../html_menu/login.php");
    exit;
}

$id_restaurante = $_SESSION['id_usuario'];

// Buscar todos os pedidos para este restaurante
$pedidos_sql = "SELECT p.id AS pedido_id, p.data_pedido, u.nome_completo AS ong_nome
                FROM pedido p
                JOIN usuario u ON u.id = p.id_ong
                WHERE p.id_usuario = '$id_restaurante'
                ORDER BY p.data_pedido DESC";

$pedidos_result = mysqli_query($conn, $pedidos_sql);

$pedidos = [];
while($pedido = mysqli_fetch_assoc($pedidos_result)){
    $pedido_id = $pedido['pedido_id'];

    // Buscar itens do pedido
    $itens_sql = "SELECT prod.nome, prod.descricao, prod.validade, prod.quantidade AS estoque, prod.imagem, pi.quantidade AS quantidade_pedida
                  FROM pedido_item pi
                  JOIN produto prod ON prod.id = pi.id_produto
                  WHERE pi.id_pedido = '$pedido_id'";

    $itens_result = mysqli_query($conn, $itens_sql);
    $itens = [];
    while($item = mysqli_fetch_assoc($itens_result)){
        $itens[] = $item;
    }

    $pedidos[] = [
        'id' => $pedido_id,
        'data_pedido' => $pedido['data_pedido'],
        'ong_nome' => $pedido['ong_nome'],
        'itens' => $itens
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Notificações - Restaurante</title>
    <link rel="stylesheet" href="../css/card.css">
</head>
<body>
<header>
    <div class="header-inner">
        <h1>FoodLog - Restaurante</h1>
        <nav>
            <ul>
                <li><a href="inicio_restaurante.php">Início</a></li>
                <li><a href="meus_produtos.php">Meus Produtos</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="notificacao">
    <h2>📦 Pedidos Recebidos</h2>
    <p>Confira os itens solicitados pelas ONGs abaixo:</p>
</div>

<div class="card-container">
<?php if(empty($pedidos)): ?>
    <p style="text-align:center;">Nenhum pedido recebido até agora.</p>
<?php else: ?>
    <?php foreach($pedidos as $pedido): ?>
        <h3 style="text-align:center; margin-top:30px;">Pedido #<?= $pedido['id'] ?> - <?= $pedido['ong_nome'] ?></h3>
        <?php foreach($pedido['itens'] as $item): ?>
            <div class="card">
                <?php if($item['imagem']): ?>
                    <img src="../IMAGENS/<?= $item['imagem'] ?>" alt="<?= $item['nome'] ?>" />
                <?php endif; ?>
                <h3><?= $item['nome'] ?></h3>
                <p><?= $item['descricao'] ?></p>
                <p><strong>Validade:</strong> <?= date('d/m/Y', strtotime($item['validade'])) ?></p>
                <p><strong>Quantidade no pedido:</strong> <?= $item['quantidade_pedida'] ?></p>
                <p><strong>Estoque disponível:</strong> <?= $item['estoque'] ?></p>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endif; ?>
</div>

</body>
</html>
