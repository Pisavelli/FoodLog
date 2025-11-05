<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php'; // ajuste o caminho da sua conexão

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../html_menu/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Busca os produtos do usuário logado
$result = mysqli_query($conn, "SELECT * FROM produto WHERE id_usuario = '$id_usuario' ORDER BY cadastro DESC");

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/card.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
    <title>Meus Produtos - FoodLog</title>
</head>

<body>
    <header>
        <div class="header-inner">
            <h1>FoodLog</h1>
            <nav>
                <ul>
                    <li><a href="notificacao.html">Notificações</a></li>
                    <li><a href="meus_produtos.php">Meus Produtos</a></li>
                    <li><a href="inicio_restaurante.php">Cadastrar Produtos</a></li>
                    <li><a href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/FoodLog/menu/index.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="produtos">
        <h2>Meus Produtos</h2>
    </div>

    <div class="card-container">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($produto = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                    <img src="../IMAGENS/<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
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

</body>
</html>
