<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php'; // ajuste o caminho da sua conexão

// Verifica se usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../html_menu/login.php");
    exit;
}

// Processa o envio do formulário
$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['product_name'];
    $descricao = $_POST['product_description'];
    $validade = $_POST['product_validity'];
    $quantidade = $_POST['product_quantity'];

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
        $imgNome = time() . "_" . $_FILES['product_image']['name'];
        $imgTmp = $_FILES['product_image']['tmp_name'];
        $destino = '../IMAGENS/' . $imgNome;
        if (move_uploaded_file($imgTmp, $destino)) {
            $id_usuario = $_SESSION['id_usuario'];
            $sql = "INSERT INTO produto (id_usuario, nome, descricao, validade, quantidade, imagem)
                    VALUES ('$id_usuario', '$nome', '$descricao', '$validade', '$quantidade', '$imgNome')";
            if (mysqli_query($conn, $sql)) {
                $mensagem = "Produto cadastrado com sucesso!";
            } else {
                $mensagem = "Erro ao cadastrar produto: " . mysqli_error($conn);
            }
        } else {
            $mensagem = "Erro no upload da imagem.";
        }
    } else {
        $mensagem = "Selecione uma imagem válida.";
    }
}

// Busca os produtos cadastrados
$id_usuario = $_SESSION['id_usuario'];
$result = mysqli_query($conn, "SELECT * FROM produto WHERE id_usuario = '$id_usuario' ORDER BY cadastro DESC");

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Produtos - FoodLog</title>
    <link rel="stylesheet" href="../css/card.css">
</head>
<body>
    <header>
        <h1>FoodLog - Meus Produtos</h1>
        <nav>
            <a href="inicio_restaurante.php">Cadastrar Produto</a>
            <a href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/FoodLog/menu/index.php">Sair</a>
        </nav>
    </header>

    <main>
        <?php if($mensagem) echo "<p style='color:green;'>$mensagem</p>"; ?>

        <form enctype="multipart/form-data" method="POST">
            <h2>Cadastrar Produto</h2>
            <input type="file" name="product_image" accept="image/*" required />
            <input type="text" name="product_name" placeholder="Nome do produto" required />
            <textarea name="product_description" placeholder="Descrição do produto" required></textarea>
            <input type="date" name="product_validity" required />
            <input type="text" name="product_quantity" placeholder="Quantidade em Kg, litros ou unidade" required />
            <button type="submit">Cadastrar</button>
        </form>

        <h2>Produtos Cadastrados</h2>
        <div class="card-container">
            <?php while($produto = mysqli_fetch_assoc($result)) { ?>
                <div class="card">
                    <img src="../IMAGENS/<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                    <h3><?php echo $produto['nome']; ?></h3>
                    <p><?php echo $produto['descricao']; ?></p>
                    <p>Validade: <?php echo date('d/m/Y', strtotime($produto['validade'])); ?></p>
                    <p>Quantidade: <?php echo $produto['quantidade']; ?></p>
                </div>
            <?php } ?>
        </div>
    </main>
</body>
</html>
