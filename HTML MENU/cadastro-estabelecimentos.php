<?php
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/PHP/conexao.php'; // arquivo de conexão com o banco

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recebendo dados do formulário
    $nome_organizacao = $_POST['nome_organizacao'];
    $nome_usuario = $_POST['nome_usuario'];
    $cnpj = $_POST['cnpj'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // criptografa a senha
    $tipo = $_POST['tipo']; // 'estabelecimento'

    // Inserindo estabelecimento
    $stmt = $conn->prepare("INSERT INTO estabelecimentos (nome_estabelecimento, cnpj) VALUES (?, ?)");
    $stmt->bind_param("ss", $nome_organizacao, $cnpj);
    
    if ($stmt->execute()) {
        $id_estabelecimento = $stmt->insert_id; // pega o id do estabelecimento recém-criado

        // Inserindo usuário vinculado ao estabelecimento
        $stmt2 = $conn->prepare("INSERT INTO usuarios (nome_usuario, email, senha, tipo_usuario, id_estabelecimento) VALUES (?, ?, ?, ?, ?)");
        $stmt2->bind_param("ssssi", $nome_usuario, $email, $senha, $tipo, $id_estabelecimento);
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
    <title>FoodLog - Cadastro Estabelecimento</title>
</head>
<body>
    <header>
        <div class="header-inner">
            <h1>FoodLog</h1>
            <nav>
                <ul>
                    <li><a href="home.html">Início</a></li>
                    <li><a href="sobre.html">Sobre</a></li>
                    <li><a href="login.html">Login</a></li>
                    <li><a href="escolha-cadastro.html">Cadastro</a></li>
                    <li><a href="contatos.html">Contato</a></li>
                    <li><a href="faq.html">FAQ</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <form action="" method="POST">
            <input type="hidden" name="tipo" value="estabelecimento">
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
                
                <h2 style="color: orange;">Informações Legais</h2>
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
                <button class="voltar" type="button" onclick="location.href='escolha-cadastro.html'"> Voltar </button>
                <button class="next-step" type="submit">Próximo Passo</button>
            </div>
        </form>
    </main>

    <footer>
        <strong>&copy; FoodLog 2025. Todos os direitos reservados. </strong>
    </footer>
</body>
</html>
