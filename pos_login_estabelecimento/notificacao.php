<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';  

// Checar login do restaurante
if(!isset($_SESSION['id_usuario'])){
    header("Location: /FoodLog/menu/login.php");
    exit;
}

$id_restaurante = $_SESSION['id_usuario'];

// --- Buscar todos os pedidos deste restaurante ---
$pedidos_sql = "
    SELECT p.id AS pedido_id, p.data_pedido, u.nome_usuario AS ong_nome
    FROM pedidos p
    JOIN usuarios u ON u.id_usuario = p.id_ong
    WHERE p.id_usuario = ?
    ORDER BY p.data_pedido DESC
";

$stmt = $conn->prepare($pedidos_sql);
$stmt->bind_param("i", $id_restaurante);
$stmt->execute();
$pedidos_result = $stmt->get_result();

$pedidos = [];

while($pedido = $pedidos_result->fetch_assoc()){
    $pedido_id = $pedido['pedido_id'];

    // --- Buscar itens do pedido ---
    $itens_sql = "
        SELECT pr.nome, pr.descricao, pr.validade, pr.quantidade AS estoque, pr.imagem, pi.quantidade AS quantidade_pedida
        FROM pedido_item pi
        JOIN produtos pr ON pr.id = pi.id_produto
        WHERE pi.id_pedido = ?
    ";

    $itens_stmt = $conn->prepare($itens_sql);
    $itens_stmt->bind_param("i", $pedido_id);
    $itens_stmt->execute();
    $itens_result = $itens_stmt->get_result();

    $itens = [];
    while($item = $itens_result->fetch_assoc()){
        $itens[] = $item;
    }

    $pedidos[] = [
        'id' => $pedido_id,
        'data_pedido' => $pedido['data_pedido'],
        'ong_nome' => $pedido['ong_nome'],
        'itens' => $itens
    ];

    $itens_stmt->close();
}

$stmt->close();
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>FoodLog - Notificações</title>
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
