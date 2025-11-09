<?php
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Dados do formulário
    $nome_estabelecimento = $_POST['nome_estabelecimento'];
    $nome_usuario = $_POST['nome_usuario'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $data_nascimento = $_POST['data_nascimento'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo']; // 'estabelecimento'

    // Inserindo estabelecimento
    $stmt = $conn->prepare("INSERT INTO estabelecimentos (nome_estabelecimento, cnpj) VALUES (?, ?)");
    $stmt->bind_param("ss", $nome_estabelecimento, $_POST['cnpj']);

    if ($stmt->execute()) {
        $id_estabelecimento = $stmt->insert_id;

        // Inserindo usuário vinculado ao estabelecimento
        $stmt2 = $conn->prepare("INSERT INTO usuarios (nome_usuario, cpf, email, data_nascimento, senha, tipo_usuario, id_estabelecimento) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("ssssssi", $nome_usuario, $cpf, $email, $data_nascimento, $senha, $tipo, $id_estabelecimento);
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
    <link rel="stylesheet" href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/FoodLog/css/cadastros.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>FoodLog - Cadastro Estabelecimento</title>

<!-- jQuery Mask Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(document).ready(function(){
    $('#cpf').mask('000.000.000-00');       // Máscara para CPF
    $('#cnpj').mask('00.000.000/0000-00');  // Máscara para CNPJ
});
</script>

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
                <div class="input-box">
                    <label for="data_nascimento">Data de Nascimento</label>
                    <input type="date" name="data_nascimento" id="data_nascimento" required>
                </div>

                
                <h2 style="color: orange;">Estabelecimento</h2>
                <div class="input-box">
                    <input placeholder="Nome do Estabelecimento" type="text" name="nome_estabelecimento" required>
                    <i class="bx bxs-user"></i>
                </div>
                <div class="input-box">
                    <input placeholder="CNPJ" type="text" name="cnpj" required>
                </div>

                <div class="input-box">
                    <input placeholder="Senha" type="password" name="senha" required>
                    <i class="bx bxs-lock-alt"></i>
                </div>
                <button class="voltar" type="button" onclick="location.href='escolha_cadastro.php'"> Voltar </button>
                <button class="next-step" type="submit">Próximo Passo</button>
            </div>
        </form>
    </main>

    <footer>
        <strong>&copy; FoodLog 2025. Todos os direitos reservados. </strong>
    </footer>
</body>
</html>
