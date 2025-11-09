<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/FoodLog/css/index.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
    <title>FoodLog Início</title>
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
        <section class="welcome">
            <h2>Bem-vindo ao FoodLog!</h2>
            <p>O FoodLog conecta estebelecimentos e ONGs para distribuir alimentos de forma eficiente, ajudando quem
                mais precisa.</p>
            <a href="escolha_cadastro.php" class="btn-cta">Cadastre-se</a>
        </section>

        <section class="how-it-works">

            <a href="#" class="card">
                <i class='bx bxs-home'></i>
                <h3>Estabelecimentos</h3>
                <p>Cadastre os alimentos que deseja doar e ajude quem precisa.</p>
            </a>

            <a href="#" class="card">
                <i class='bx bxs-heart'></i>
                <h3>ONGs</h3>
                <p>Solicite os alimentos que sua instituição precisa de forma simples.</p>
            </a>
            <a href="#" class="card">
                <i class='bx bxs-truck'></i>
                <h3>Entrega</h3>
                <p>Não nos responsabilizamos pelas entregas, tais como atrasos ou extravios.</p>
            </a>
        </section>
    </main>

    <footer>
        <strong>&copy; FoodLog 2025. Todos os direitos reservados. </strong>
    </footer>

</body>

</html>