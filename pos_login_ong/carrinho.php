<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho - FoodLog</title>
    <link rel="stylesheet" href="/FoodLog/css/card.css">
</head>
<body>
<header>
    <div class="header-inner">
        <h1>FoodLog</h1>
        <nav>
            <ul>
                <li><a href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/FoodLog/pos_login_ong/carrinho.php">Carrinho</a></li>
                <li><a href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/FoodLog/pos_login_ong/produtos_disponiveis.php">Produtos Disponíveis</a></li>
                <li><a href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/FoodLog/pos_login_ong/inicio-ong.php">Início</a></li>
                <li><a href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/FoodLog/menu/index.php">Sair</a></li>
            </ul>
        </nav>
    </div>
</header>

<h2 style="text-align:center; margin-top:20px;">Itens no Carrinho</h2>

<form method="post" id="checkout-form">
    <div class="card-container">
        <?php
        // Aqui você pode pegar os produtos do carrinho do banco ou da sessão
        $cart = $_SESSION['cart'] ?? [];

        if(empty($cart)){
            echo '<p style="text-align:center;">Seu carrinho está vazio.</p>';
        } else {
            foreach($cart as $index => $product){
                echo '<div class="card">';
                echo '<img src="/FoodLog/img/'.$product['imagem'].'" alt="'.$product['nome'].'">';
                echo '<h3>'.$product['nome'].'</h3>';
                echo '<p>'.$product['descricao'].'</p>';
                echo '<p><strong>Validade:</strong> '.date('d/m/Y', strtotime($product['validade'])).'</p>';
                echo '<p><strong>Quantidade:</strong> '.$product['quantidade'].'</p>';
                echo '<input type="hidden" name="cart['.$index.'][id_produto]" value="'.$product['id'].'">';
                echo '<input type="hidden" name="cart['.$index.'][quantidade]" value="'.$product['quantidade'].'">';
                echo '<input type="hidden" name="cart['.$index.'][id_restaurante]" value="'.$product['id_restaurante'].'">';
                echo '</div>';
            }
            echo '<button type="submit">Finalizar pedido</button>';
        }
        ?>
    </div>
</form>
</body>
</html>
