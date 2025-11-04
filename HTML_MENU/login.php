<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = $_POST['senha'];

    // Consulta usuário
    $sql = "SELECT * FROM usuarios WHERE email = '$email' LIMIT 1";
    $resultado = mysqli_query($conn, $sql);

    if (mysqli_num_rows($resultado) === 1) {
        $usuario = mysqli_fetch_assoc($resultado);

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
            $_SESSION['tipo'] = $usuario['tipo_usuario']; // 'ong' ou 'estabelecimento'

            // Redireciona dependendo do tipo
            if ($_SESSION['tipo'] === 'ong') {
                header('Location: dashboard_ong.php');
            } else if ($_SESSION['tipo'] === 'estabelecimento') {
                header('Location: dashboard_estabelecimento.php');
            }
            exit;
        }


            // Se marcou "lembrar minha senha"
            if(isset($_POST['lembrar'])) {
                $token = bin2hex(random_bytes(32));
                $expiracao = date('Y-m-d H:i:s', strtotime('+30 days'));

                $stmt = $conn->prepare("INSERT INTO tokens_login (id_usuario, token, expiracao) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $usuario['id_usuario'], $token, $expiracao);
                $stmt->execute();

                setcookie("remember_me", $token, time() + (30*24*60*60), "/", "", false, true);
            }

            header('Location: dashboard.php');
            exit;
        } else {
            $erro = "Email ou senha incorretos!";
        }
    } else {
        $erro = "Email ou senha incorretos!";
    }
}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/stylelogin.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
    <title>FoodLog Login</title>
</head>
<body>
<header>
    <div class="header-inner">
        <h1>FoodLog</h1>
        <nav>
            <ul>
                <li><a href="home.html">Início</a></li>
                <li><a href="sobre.html">Sobre</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="escolha_cadastro.html">Cadastro</a></li>
                <li><a href="contatos.html">Contato</a></li>
                <li><a href="faq.html">FAQ</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <form method="POST" action="">
        <div class="container">
            <h2>Login FoodLog</h2>

            <?php if (!empty($erro)) { echo "<p style='color:red;'>$erro</p>"; } ?>

            <div class="input-box">
                <input placeholder="Email" type="email" name="email" required>
                <i class="bx bxs-user"></i>
            </div>
            <div class="input-box">
                <input placeholder="Senha" type="password" name="senha" required>
                <i class="bx bxs-lock-alt"></i>
            </div>

            <div class="remember-password">
                <label>
                    <input type="checkbox" name="lembrar">
                    Lembrar minha senha
                </label>
            </div>

            <button class="login" type="submit"> Login </button>

            <div class="register-link">
                <p><a href="../php/esqueci_senha.php">Esqueci minha senha</a></p>
                <p>Não tem uma conta? <a href="escolha_cadastro.html">Cadastre-se</a></p>
            </div>
        </div>
    </form>
</main>

<footer>
    <strong>&copy; FoodLog 2025. Todos os direitos reservados. </strong>
</footer>
</body>
</html>
