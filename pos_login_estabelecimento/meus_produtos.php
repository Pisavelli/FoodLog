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

// Vincula o ID do usuário (parâmetro 'i' = integer)
$stmt->bind_param("i", $id_usuario);
$stmt->execute();

// Obtém o resultado da query
$result = $stmt->get_result(); 

// --- 3. Armazena produtos em um array ---
$produtos = [];
while($produto = $result->fetch_assoc()) {
    $produtos[] = $produto;
}

// --- 4. Fecha statement e conexão ---
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/FoodLog/css/card.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
    <title>FoodLog - Meus Produtos</title>
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

    <div class="produtos">
        <h2>Meus Produtos</h2>
    </div>

    <div class="card-container">
        <?php if($result->num_rows > 0): ?>
            <?php while($produto = $result->fetch_assoc()): ?>
                <div class="card">
                    <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                    <h3><?php echo $produto['nome']; ?></h3>
                    <p><?php echo $produto['descricao']; ?></p>
                    <p>Validade: <?php echo date('d/m/Y', strtotime($produto['validade'])); ?></p>
                    <p>Quantidade: <?php echo $produto['quantidade']; ?></p>
                    </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nenhum produto cadastrado ainda.</p>
        <?php endif; ?>
    </div>

    <?php 
    $stmt->close();
    $conn->close();
    ?>
</body>
    <script src="/FoodLog/js/card.js"></script>
</html>