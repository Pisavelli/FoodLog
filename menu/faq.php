<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/FoodLog/css/faq.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
    <title>FAQ - FoodLog</title>
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
                </ul>
            </nav>
        </div>
    </header>

    <main class="faq-container">
        <h2>🥗 FAQ – Sistema de Doação de Alimentos</h2>

        <section class="faq-item">
            <h3>❓ O que é o sistema?</h3>
            <p>Nosso sistema é uma plataforma que conecta <strong>estabelecimentos com excedentes de alimentos</strong>
                (como restaurantes, padarias, mercados e hotéis) a <strong>ONGs e instituições sociais</strong> que
                necessitam de doações para atender pessoas em situação de vulnerabilidade.</p>
        </section>

        <section class="faq-item">
            <h3>🏢 Quem pode doar?</h3>
            <p>Qualquer estabelecimento que produza, manipule ou comercialize alimentos e tenha sobras em boas condições
                para consumo pode se cadastrar e realizar doações.</p>
        </section>

        <section class="faq-item">
            <h3>👐 Quem pode receber as doações?</h3>
            <p>ONGs, instituições beneficentes, abrigos, cozinhas solidárias e outros projetos sociais que trabalham com
                alimentação e possuam CNPJ válido.</p>
        </section>

        <section class="faq-item">
            <h3>💡 Como funciona o processo de doação?</h3>
            <ol>
                <li>O estabelecimento cadastra os alimentos disponíveis para doação.</li>
                <li>As ONGs visualizam as doações disponíveis e demonstram interesse.</li>
                <li>As partes entram em contato para combinar <strong>data, horário e forma de retirada</strong>.</li>
            </ol>
            <p class="alert">⚠️ <strong>Importante:</strong> A plataforma <strong>não realiza nem se responsabiliza pelo
                    transporte ou entrega dos alimentos.</strong>
                A logística deve ser acordada diretamente entre o doador e a ONG.</p>
        </section>

        <section class="faq-item">
            <h3>🧾 Há algum custo para usar a plataforma?</h3>
            <p>Não. O uso da plataforma é <strong>gratuito</strong> tanto para doadores quanto para as instituições
                beneficiadas.</p>
        </section>

        <section class="faq-item">
            <h3>🥦 Que tipo de alimentos podem ser doados?</h3>
            <p>Alimentos <strong>em boas condições de consumo</strong>, respeitando as normas de segurança alimentar.
                Isso pode incluir produtos próximos da validade, excedentes de produção, refeições prontas não servidas,
                frutas, verduras, entre outros.</p>
        </section>

        <section class="faq-item">
            <h3>📋 Como garantir que os alimentos doados são seguros?</h3>
            <p>Os doadores se comprometem a seguir as normas da <strong>ANVISA</strong> e boas práticas de manipulação e
                armazenamento. As ONGs também são orientadas a verificar as condições dos alimentos no momento da
                retirada.</p>
        </section>

        <section class="faq-item">
            <h3>🚚 Quem é responsável pela retirada das doações?</h3>
            <p>A responsabilidade pela <strong>retirada e transporte dos alimentos</strong> é da <strong>ONG ou do
                    estabelecimento</strong>, conforme o acordo entre as partes. A plataforma atua apenas como
                <strong>intermediadora da conexão</strong> entre doador e receptor.
            </p>
        </section>

        <section class="faq-item">
            <h3>🕓 Em quanto tempo uma ONG pode receber uma doação?</h3>
            <p>O tempo pode variar conforme a disponibilidade das partes. A comunicação é feita diretamente entre o
                doador e a ONG após o interesse ser manifestado no sistema.</p>
        </section>

        <section class="faq-item">
            <h3>📞 Como entrar em contato com suporte?</h3>
            <p>Em caso de dúvidas técnicas sobre o uso da plataforma, entre em contato pelo nosso canal de suporte:
                <strong><a href="contatos.php">Contato</a>.</strong>
            </p>
        </section>

        <section class="faq-item">
            <h3>⚖️ O sistema é responsável por problemas nas doações?</h3>
            <p>Não. O sistema atua apenas como <strong>facilitador da conexão</strong> entre doadores e instituições.
                Não
                nos responsabilizamos por:</p>
            <ul>
                <li>Transporte ou entrega das doações;</li>
                <li>Condições dos alimentos após a retirada;</li>
                <li>Acordos firmados entre as partes fora da plataforma.</li>
            </ul>
        </section>
    </main>

    <footer>
        <strong>&copy; FoodLog 2025. Todos os direitos reservados.</strong>
    </footer>
</body>

</html>