<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - FoodLog</title>
    <link rel="stylesheet" href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/FoodLog/css/contatos.css">
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
                    <p>joao.costa8@pucpr.edu.br</p>
                    <p>matheus.alievi@pucpr.edu.br</p>
                    <p>pierre.cardoso@pucpr.edu.br</p>
                    <p>robency.michel@pucpr.edu.br</p>
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