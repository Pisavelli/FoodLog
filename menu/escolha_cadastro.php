<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/FoodLog/css/escolha-cadastro.css">
    <title>FoodLog - Cadastro</title>

</head>

<body>

    <header>
        <div class="header-inner">
            <h1>FoodLog</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Início</a></li>
                    <li><a href="sobre.php">Sobre</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="escolha_cadastro.php">Cadastro</a></li>
                    <li><a href="contatos.php">Contato</a></li>
                    <li><a href="faq.php">FAQ</a></li>
                    <li>
                    <a href="<?php 
                        if(isset($_SESSION['tipo'])) {
                        echo $_SESSION['tipo'] === 'ong' 
                            ? 'pos_login_ong/dashboard_ong.php' 
                            : 'pos_login_estabelecimento/dashboard_estabelecimento.php';
                        } else {
                        echo 'login.php'; // fallback se não estiver logado
                        }
                    ?>">Dashboard</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Tipo de Cadastro:</h2>
            <a class="opcao" href="cadastro_ongs.php">Cadastrar ONG</a>
            <a class="opcao" href="cadastro_estabelecimentos.php">Cadastrar ESTABELECIMENTO</a>
        </div>
    </main>

    <footer>
        <strong>&copy; FoodLog 2025. Todos os direitos reservados. </strong>
    </footer>

</body>

</html>