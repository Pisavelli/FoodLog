<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';

// --- 1. Verifica se o usuário está logado ---
if (!isset($_SESSION['id_usuario'])) {
    header("Location: /FoodLog/menu/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// --- 2. Busca segura dos produtos do usuário ---
$sql = "SELECT * FROM produtos WHERE id_usuario = ? ORDER BY cadastro DESC"; 
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Erro na preparação da query: ' . $conn->error);
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result(); 

$produtos = [];
while($produto = $result->fetch_assoc()) {
    $produtos[] = $produto;
}

$stmt->close();
$conn->close();

// Mensagem de status, se vier via GET
$mensagem = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodLog - Meus Produtos</title>
    <link rel="stylesheet" href="/FoodLog/css/card.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card {
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
            position: relative;
        }
        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }
        .card-buttons {
            margin-top: 10px;
        }
        .edit-btn, .delete-btn {
            text-decoration: none;
            padding: 5px 10px;
            margin-right: 5px;
            border-radius: 4px;
            color: #fff;
        }
        .edit-btn { background-color: #4CAF50; }
        .delete-btn { background-color: #f44336; }
        .message {
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
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

<div class="container">
    <h2>Meus Produtos</h2>

    <?php if($mensagem): ?>
        <div class="message <?= strpos($mensagem,'sucesso') !== false ? 'success' : 'error' ?>">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>

    <div class="card-container">
        <?php if(count($produtos) > 0): ?>
            <?php foreach($produtos as $produto): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                    <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                    <p><?= htmlspecialchars($produto['descricao']) ?></p>
                    <p>Validade: <?= (!empty($produto['validade']) && $produto['validade'] != '0000-00-00') ? htmlspecialchars($produto['validade']) : 'Não informada' ?></p>
                    <p>Quantidade: <?= htmlspecialchars($produto['quantidade']) ?> <?= htmlspecialchars($produto['unidade']) ?></p>
                    <div class="card-buttons">
                        <a href="/FoodLog/pos_login_estabelecimento/editar_produto.php?id=<?= $produto['id'] ?>" class="edit-btn">Editar</a>
                        <a href="/FoodLog/pos_login_estabelecimento/apagar_produto.php?id=<?= $produto['id'] ?>" class="delete-btn" onclick="return confirm('Tem certeza que deseja apagar este produto?')">Apagar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum produto cadastrado ainda.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
