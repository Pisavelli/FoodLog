<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/card.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
    <title>ONG - FoodLog</title>

</head>

<body>
    <header>
        <div class="header-inner">
            <h1>FoodLog</h1>
            <nav>
                <ul>
                    <li><a href="/FoodLog/pos_login_ong/produtos_disponiveis.php">Produtos Disponíveis</a></li>
                    <li><a href="/FoodLog/pos_login_ong/carrinho.php">Carrinho</a></li>
                    <li><a href="/FoodLog/pos_login_ong/dashboard_ong.php">Atualizar Cadastro</a></li>
                    <li><a href="/FoodLog/menu/index.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <h2>Produtos Disponíveis</h2>

    <div class="card-container" id="product-list"></div>


    <script src="../JAVASCRIPT/card.js"></script>

</body>