<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    die("Você precisa estar logado para acessar esta página.");
}

$id_usuario = $_SESSION['id_usuario'];
$mensagem = "";

// --- Processa o formulário ---
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

// --- Busca dados do usuário ---
$sql = "SELECT nome_usuario, cpf, data_nascimento FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - FoodLog</title>
    <link rel="stylesheet" href="/FoodLog/css/configuracoes.css">
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
    <section class="config">
        <h2>Configurações do Usuário</h2>
        <p>Atualize seus dados pessoais abaixo. A senha é opcional e só será alterada se preenchida.</p>

        <?php if ($mensagem): ?>
            <div class="message <?= strpos($mensagem, '✅') !== false ? 'success' : 'error' ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <div class="config-container">
            <div class="config-card">
                <form method="POST" action="">
                    <label for="nome_usuario">Nome completo</label>
                    <input type="text" id="nome_usuario" name="nome_usuario"
                        value="<?= htmlspecialchars($usuario['nome_usuario']) ?>" required>

                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" maxlength="14"
                        value="<?= htmlspecialchars($usuario['cpf']) ?>" required>

                    <label for="data_nascimento">Data de nascimento</label>
                    <input type="date" id="data_nascimento" name="data_nascimento"
                        value="<?= htmlspecialchars($usuario['data_nascimento']) ?>" required>

                    <label for="senha">Nova senha (deixe em branco se não quiser alterar)</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite nova senha">

                    <button type="submit">Salvar alterações</button>
                </form>
            </div>
        </div>
    </section>
</main>

<footer>
    <strong>&copy; FoodLog 2025. Todos os direitos reservados.</strong>
</footer>

</body>
</html>