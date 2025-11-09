<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';
?>

<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Configurações - FoodLog</title>
<link rel="stylesheet" href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/FoodLog/css/configuracoes.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
</head>

<body>
    <header>
        <div class="header-inner">
            <h1> FoodLog</h1>
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
        <section class="contact">
            <h2>Entre em Contato</h2>
            <p style="text-align:center; max-width:800px; margin:0 auto;">
                Estamos prontos para ajudar! Caso tenha dúvidas, sugestões ou queira se conectar com a FoodLog,
                utilize um dos canais abaixo. Retornaremos o mais breve possível!
            </p>

            <div class="contact-container">
                <div class="contact-card">
                    <h3>E-mail</h3>
                    <a href="mailto:joao.costa8@pucpr.edu.br">joao.costa8@pucpr.edu.br</a>
                    <br>
                    <a href="mailto:matheus.alievi@pucpr.edu.br">matheus.alievi@pucpr.edu.br</a>
                    <br>
                    <a href="mailto:pierre.cardoso@pucpr.edu.br">pierre.cardoso@pucpr.edu.br</a>
                    <br>
                    <a href="mailto:robency.michel@pucpr.edu.br">robency.michel@pucpr.edu.br</a>
                </div>
                <br>
                <div class="contact-card">
                    <h3>Links Externos</h3>
                    <p>
                        <a href="https://github.com/Pisavelli/FoodLog" target="_blank">Portfólio GitHub</a><br>
                    </p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <strong>&copy; FoodLog 2025. Todos os direitos reservados. </strong>
    </footer>
</body>

</html>