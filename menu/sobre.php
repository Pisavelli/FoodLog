<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/FoodLog/css/sobre.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
    <title>Sobre - FoodLog</title>
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

    <main style="padding:50px 20px; max-width:1000px; margin:0 auto; display:flex; flex-direction:column; gap:50px;">

        <section class="story">
            <h2>Como tudo começou</h2>
            <div style="display:flex; flex-wrap:wrap; gap:20px; align-items:center;">
                <p style="flex:2; min-width:300px; text-align:justify;">
                    A FoodLog nasceu em 2025 a partir da visão de um grupo de jovens empreendedores comprometidos com a
                    solidariedade e a redução do desperdício de alimentos.
                    <strong>Matheus Alievi, Pierre Savelli, Robency Michel, João Pedro e João Batista:</strong> Unidos
                    pelo desejo de transformar a maneira como alimentos eram distribuídos, eles criaram uma plataforma
                    que conecta restaurantes e ONGs, garantindo que comida boa chegue às pessoas que realmente precisam.
                </p>
            </div>
        </section>

        <section class="mission">
            <h2>O problema que resolvemos</h2>
            <div style="display:flex; flex-wrap:wrap; gap:20px; align-items:center;">
                <p style="flex:2; min-width:300px; text-align:justify;">
                    Todos os dias, toneladas de alimentos são desperdiçadas em restaurantes, padarias e cafés, enquanto
                    muitas famílias enfrentam fome e insegurança alimentar. A FoodLog surgiu para criar uma solução
                    simples e eficiente: redistribuir alimentos excedentes de forma organizada e confiável,
                    transformando desperdício em oportunidade.
                </p>
            </div>
        </section>

        <section class="values">
            <h2>Missão e Valores</h2>
            <div style="display:flex; flex-direction:column; gap:20px;">
                <p style="text-align:justify;">
                    A missão da FoodLog é clara: reduzir o desperdício de alimentos e promover a solidariedade,
                    conectando quem tem com quem precisa. Restaurantes podem cadastrar suas doações e ONGs solicitar
                    alimentos de forma rápida, segura e transparente.
                </p>
                <p style="text-align:justify;">
                    Além da missão social, a plataforma promove educação e conscientização sobre desperdício de
                    alimentos, incentivando hábitos mais responsáveis e sustentáveis. Cada ação reforça nosso
                    compromisso com ética, transparência e impacto positivo na comunidade.
                </p>
            </div>
        </section>

        <section class="impact">
            <h2>Nosso impacto</h2>
            <div style="display:flex; flex-wrap:wrap; gap:20px; align-items:center;">
                <p style="flex:2; min-width:300px; text-align:justify;">
                    Desde sua fundação, a FoodLog já impactou centenas de famílias e instituições, criando uma rede de
                    solidariedade ativa e eficaz. Cada doação realizada por meio da plataforma demonstra que pequenas
                    ações podem gerar grandes mudanças, promovendo dignidade e esperança para quem mais precisa.
                </p>
            </div>
        </section>

    </main>

    <footer>
        <strong>&copy; FoodLog 2025. Todos os direitos reservados. </strong>
    </footer>
</body>

</html>