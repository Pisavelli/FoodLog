<?php
// login.php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/PHP/conexao.php'; // ajuste o caminho para onde está o seu conexao.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Evita SQL Injection
    $email = mysqli_real_escape_string($conn, $email);
    $senha = mysqli_real_escape_string($conn, $senha);

    // Consulta usuário
    $sql = "SELECT * FROM usuario WHERE email = '$email' AND senha = '$senha' LIMIT 1";
    $resultado = mysqli_query($conn, $sql);

    if (mysqli_num_rows($resultado) === 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        $_SESSION['id_usuario'] = $usuario['id'];
        $_SESSION['nome_usuario'] = $usuario['nome'];
        $_SESSION['tipo'] = $usuario['tipo']; // 'ong' ou 'estabelecimento'

        // Redireciona para área logada
        header('Location: dashboard.php');
        exit;
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
    <link rel="stylesheet" href="../CSS/stylelogin.css">
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
                <li><a href="escolha-cadastro.html">Cadastro</a></li>
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
                    <input type="checkbox">
                    Lembrar minha senha
                </label>
            </div>
            <button class="login" type="submit"> Login </button>

            <div class="register-link">
                <p>Não tem uma conta? <a href="cadastro.html">Cadastre-se</a></p>
            </div>
        </div>
    </form>
</main>

<footer>
    <strong>&copy; FoodLog 2025. Todos os direitos reservados. </strong>
</footer>
</body>
</html>
