<?php
session_start();

// Proteção de página
if(!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
?>

<h1>Bem-vindo, <?php echo $_SESSION['nome_usuario']; ?></h1>

<?php if($_SESSION['tipo'] === 'ong'): ?>
    <h2>Menu ONG</h2>
    <ul>
        <li><a href="cadastrar_doacoes.php">Cadastrar Doações</a></li>
        <li><a href="relatorio_doacoes.php">Relatório de Doações</a></li>
    </ul>
<?php elseif($_SESSION['tipo'] === 'estabelecimento'): ?>
    <h2>Menu Estabelecimento</h2>
    <ul>
        <li><a href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/FoodLog/menu/listar_ongs.php">Listar ONGs</a></li>
        <li><a href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/FoodLog/menu/cadastrar_produtos.php">Cadastrar Produtos</a></li>
    </ul>
<?php endif; ?>

<a href="logout.php">Sair</a>
