<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta segura
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
            $_SESSION['tipo'] = $usuario['tipo_usuario']; // 'ong' ou 'estabelecimento'

            // Lembrar senha
            if(isset($_POST['lembrar'])) {
                $token = bin2hex(random_bytes(32));
                $expiracao = date('Y-m-d H:i:s', strtotime('+30 days'));

                $stmtToken = $conn->prepare("INSERT INTO tokens_login (id_usuario, token, expiracao) VALUES (?, ?, ?)");
                $stmtToken->bind_param("iss", $usuario['id_usuario'], $token, $expiracao);
                $stmtToken->execute();

                setcookie("remember_me", $token, time() + (30*24*60*60), "/", "", false, true);
            }

            // Redireciona dependendo do tipo
            if ($_SESSION['tipo'] === 'ong') {
                header('Location: /FoodLog/pos_login_ong/dashboard_ong.php');
            } else {
                header('Location: /FoodLog/pos_login_estabelecimento/dashboard_estabelecimento.php');
            }
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
    <link rel="stylesheet" href="/FoodLog/css/login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
    <title>FoodLog Login</title>
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
                        ? '/FoodLog/pos_login_ong/dashboard_ong.php' 
                        : '/FoodLog/pos_login_estabelecimento/dashboard_estabelecimento.php';
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
                    <input type="checkbox" name="lembrar"> Lembrar minha senha
                </label>
            </div>

            <button class="login" type="submit"> Login </button>

            <div class="register-link">
                <p>Não tem uma conta? <a href="escolha_cadastro.php">Cadastre-se</a></p>
            </div>
        </div>
    </form>
</main>

<footer>
    <strong>&copy; FoodLog 2025. Todos os direitos reservados.</strong>
</footer>
</body>
</html>
