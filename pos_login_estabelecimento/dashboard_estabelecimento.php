<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';

// Proteção de página
if (!isset($_SESSION['id_usuario'])) {
    header("Location: /FoodLog/menu/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$mensagem = "";

// --- 1. Busca os dados atuais do usuário e do estabelecimento ---
$stmt = $conn->prepare("
    SELECT u.*, e.nome_estabelecimento, e.cnpj 
    FROM usuarios u 
    LEFT JOIN estabelecimentos e ON u.id_estabelecimento = e.id_estabelecimento 
    WHERE u.id_usuario = ?
");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Usuário não encontrado.");
}

$usuario = $result->fetch_assoc();
$stmt->close();

// --- 2. Processa atualização ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome_estabelecimento = trim($_POST['nome_estabelecimento']);
    $nome_usuario = trim($_POST['nome_usuario']);
    $cpf = trim($_POST['cpf']);
    $email = trim($_POST['email']);
    $data_nascimento = $_POST['data_nascimento'];
    $senha = !empty($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : $usuario['senha'];

    // Atualiza Estabelecimento
    $stmt = $conn->prepare("UPDATE estabelecimentos SET nome_estabelecimento = ?, cnpj = ? WHERE id_estabelecimento = ?");
    $stmt->bind_param("ssi", $nome_estabelecimento, $_POST['cnpj'], $usuario['id_estabelecimento']);
    $stmt->execute();
    $stmt->close();

    // Atualiza usuário
    $stmt2 = $conn->prepare("UPDATE usuarios SET nome_usuario = ?, cpf = ?, email = ?, data_nascimento = ?, senha = ? WHERE id_usuario = ?");
    $stmt2->bind_param("sssssi", $nome_usuario, $cpf, $email, $data_nascimento, $senha, $id_usuario);

    if ($stmt2->execute()) {
        $mensagem = "✅ Cadastro atualizado com sucesso!";

        // Atualiza dados locais
        $usuario['nome_estabelecimento'] = $nome_estabelecimento;
        $usuario['nome_usuario'] = $nome_usuario;
        $usuario['cpf'] = $cpf;
        $usuario['email'] = $email;
        $usuario['data_nascimento'] = $data_nascimento;
        $usuario['senha'] = $senha;
        $usuario['cnpj'] = $_POST['cnpj'];

        // Atualiza SESSION para refletir mudanças
        $_SESSION['nome_usuario'] = $nome_usuario;
    } else {
        $mensagem = "❌ Erro ao atualizar cadastro: " . $stmt2->error;
    }

    $stmt2->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../CSS/card.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
<title>Atualizar Cadastro - Estabelecimento</title>

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
        <br>
        <nav>
            <ul>
                <li><a href="/FoodLog/pos_login_estabelecimento/notificacao.php">Notificações</a></li>
                <li><a href="/FoodLog/pos_login_estabelecimento/meus_produtos.php">Meus Produtos</a></li>
                <li><a href="/FoodLog/pos_login_estabelecimento/cadastrar_produto.php">Cadastrar Produtos</a></li>
                <li><a href="/FoodLog/pos_login_estabelecimento/dashboard_estabelecimento.php">Atualizar Cadastro</a></li>
                <li><a href="/FoodLog/menu/index.php">Sair</a></li>
            </ul>
        </nav>
    </div>
</header>

<h2>Atualizar Cadastro</h2>

<?php if($mensagem): ?>
<p style="color: <?= strpos($mensagem,'✅') !== false ? 'green' : 'red' ?>"><?= htmlspecialchars($mensagem) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Nome do Estabelecimento</label>
    <input type="text" name="nome_estabelecimento" value="<?= htmlspecialchars($usuario['nome_estabelecimento']) ?>" required />

    <label>CNPJ</label>
    <input type="text" name="cnpj" id="cnpj" value="<?= htmlspecialchars($usuario['cnpj']) ?>" required />

    <label>Nome do Usuário</label>
    <input type="text" name="nome_usuario" value="<?= htmlspecialchars($usuario['nome_usuario']) ?>" required />

    <label>CPF</label>
    <input type="text" name="cpf" id="cpf" value="<?= htmlspecialchars($usuario['cpf']) ?>" required />

    <label>E-mail</label>
    <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required />

    <label>Data de Nascimento</label>
    <input type="date" name="data_nascimento" value="<?= htmlspecialchars($usuario['data_nascimento']) ?>" required />

    <label>Senha (deixe em branco para não alterar)</label>
    <input type="password" name="senha" placeholder="Nova senha" />

    <p>Tipo de usuário: <strong><?= htmlspecialchars($usuario['tipo_usuario']) ?></strong></p>

    <button type="submit">Atualizar Cadastro</button>
</form>
</body>
</html>
