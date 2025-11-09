<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    die("Você precisa estar logado para acessar esta página.");
}

$id_usuario = $_SESSION['id_usuario'];
$mensagem = "";

// Busca dados antigos
$sql = "SELECT nome_usuario, cpf, data_nascimento, senha FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario_antigo = $result->fetch_assoc();
$stmt->close();

// Atualização dos dados
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome_usuario"] ?? '');
    $cpf = preg_replace('/\D/', '', $_POST["cpf"] ?? '');
    $data_nascimento = $_POST["data_nascimento"] ?? '';
    $senha = $_POST["senha"] ?? '';

    if (strlen($nome) < 2) {
        $mensagem = "❌ Nome inválido.";
    } elseif (!preg_match('/^\d{11}$/', $cpf)) {
        $mensagem = "❌ CPF inválido.";
    } elseif (strtotime($data_nascimento) > time()) {
        $mensagem = "❌ Data de nascimento não pode ser futura.";
    } else {
        if (!empty($senha)) {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios 
                    SET nome_usuario=?, cpf=?, data_nascimento=?, senha=? 
                    WHERE id_usuario=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $nome, $cpf, $data_nascimento, $senha_hash, $id_usuario);
        } else {
            $sql = "UPDATE usuarios 
                    SET nome_usuario=?, cpf=?, data_nascimento=? 
                    WHERE id_usuario=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nome, $cpf, $data_nascimento, $id_usuario);
        }

        if ($stmt->execute()) {
            $mensagem = "✅ Dados atualizados com sucesso!";
        } else {
            $mensagem = "❌ Erro ao atualizar: " . $conn->error;
        }
        $stmt->close();
    }
}

// Recarrega os dados
$sql = "SELECT nome_usuario, cpf, data_nascimento FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();
$conn->close();
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