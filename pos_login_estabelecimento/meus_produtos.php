<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php'; 

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: /FoodLog/menu/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// --- Busca Segura dos Produtos (Prepared Statement) ---
// Tabela: `produtos` (ajuste se for 'produto')
$sql = "SELECT * FROM produtos WHERE id_usuario = ? ORDER BY data_cadastro DESC"; 
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Erro na preparação da query: ' . $conn->error);
}

// Vincula o ID do usuário (parâmetro 'i' = integer)
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result(); 

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/FoodLog/css/card.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
    <title>Meus Produtos - FoodLog</title>
</head>
<body>
    <header>
        <div class="header-inner">
            <h1>FoodLog</h1>
            <nav>
                <ul>
                    <li><a href="notificacao.php">Notificações</a></li>
                    <li><a href="meus_produtos.php">Meus produtos</a></li>
                    <li><a href="dashboard_estabelecimento.php">Cadastrar produtos</a></li>
                    <li><a href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/FoodLog/menu/index.php">Sair</a></li>
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
    <script src="../JS/card.js"></script>
</html>