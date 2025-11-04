<?php
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/PHP/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Dados do formulário
    $nome_ong = $_POST['nome_ong'];
    $nome_usuario = $_POST['nome_usuario'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $data_nascimento = $_POST['data_nascimento'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo']; // 'estabelecimento'

    // Inserindo ONG
    $stmt = $conn->prepare("INSERT INTO ongs (nome_ong, cnpj) VALUES (?, ?)");
    $stmt->bind_param("ss", $nome_ong, $_POST['cnpj']);

    if ($stmt->execute()) {
        $id_ong = $stmt->insert_id;

        // Inserindo usuário vinculado à ONG
        $stmt2 = $conn->prepare("INSERT INTO usuarios (nome_usuario, cpf, email, data_nascimento, senha, tipo_usuario, id_ong) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("ssssssi", $nome_usuario, $cpf, $email, $data_nascimento, $senha, $tipo, $id_ong);
        $stmt2->execute();

        echo "<p style='color:green'>Cadastro realizado com sucesso!</p>";
    } else {
        echo "<p style='color:red'>Erro ao cadastrar: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/cadastros.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>FoodLog - Cadastro ONG</title>
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
        <form action="" method="POST">
            <input type="hidden" name="tipo" value="ong">
            <div class="container">
                <h2 style="color: orange;">Informações Pessoais</h2>
                <div class="input-box">
                    <input placeholder="Nome Completo" type="text" name="nome_usuario" required>
                    <i class="bx bxs-user"></i>
                </div>
                <div class="input-box">
                    <input placeholder="CPF" type="text" name="cpf" required>
                </div>
                <div class="input-box">
                    <input placeholder="E-mail" type="email" name="email" required>
                    <i class="material-icons">email</i>
                </div>
                <div class="input-box">
                    <label for="data_nascimento">Data de Nascimento</label>
                    <input type="date" name="data_nascimento" id="data_nascimento" required>
                </div>
                
                <h2 style="color: orange;">Organização</h2>
                <div class="input-box">
                    <input placeholder="Nome da Organização" type="text" name="nome_organizacao" required>
                    <i class="bx bxs-user"></i>
                </div>
                <div class="input-box">
                    <input placeholder="CNPJ" type="text" name="cnpj" required>
                </div>

                <div class="input-box">
                    <input placeholder="Senha" type="password" name="senha" required>
                    <i class="bx bxs-lock-alt"></i>
                </div>
                <button class="voltar" type="button" onclick="location.href='escolha_cadastro.html'"> Voltar </button>
                <button class="next-step" type="submit">Próximo Passo</button>
            </div>
        </form>
    </main>

    <footer>
        <strong>&copy; FoodLog 2025. Todos os direitos reservados. </strong>
    </footer>
</body>
</html>
