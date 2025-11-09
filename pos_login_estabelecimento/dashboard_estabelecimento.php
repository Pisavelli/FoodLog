<?php
session_start();
// O caminho de inclusão é mantido como absoluto para segurança
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';

// ... (Seu código original de verificação de cookie e login) ...
if(!isset($_SESSION['id_usuario']) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    $stmt = $conn->prepare("SELECT id_usuario, expiracao FROM tokens_login WHERE token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        if(strtotime($row['expiracao']) > time()) {
            $stmt2 = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = ? LIMIT 1");
            $stmt2->bind_param("i", $row['id_usuario']);
            $stmt2->execute();
            $userRes = $stmt2->get_result()->fetch_assoc();
            $_SESSION['id_usuario'] = $userRes['id_usuario'];
            $_SESSION['nome_usuario'] = $userRes['nome_usuario'];
            $_SESSION['tipo'] = $userRes['tipo_usuario'];
        } else {
            setcookie("remember_me", "", time()-3600, "/");
        }
    } else {
        setcookie("remember_me", "", time()-3600, "/");
    }
}

// Redirecionamento se não estiver logado
if(!isset($_SESSION['id_usuario'])) {
    // ATENÇÃO: Se o erro "Unexpected Token '<'" persistir, o problema pode estar aqui. 
    // O PHP está redirecionando o AJAX para uma página HTML (login.php)
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/card.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
    <title>Restaurante - FoodLog</title>
</head>
<body>
    <header>
        <div class="header-inner">
            <h1>FoodLog - Estabelecimento</h1>
            <nav>
                <ul>
                    <li><a href="/FoodLog/pos_login_estabelecimento/dashboard_estabelecimento.php">Início</a></li>
                    <li><a href="/FoodLog/pos_login_estabelecimento/notificacao.php">Notificações</a></li>
                    <li><a href="/FoodLog/pos_login_estabelecimento/meus_produtos.php">Meus Produtos</a></li>
                    <li><a href="/FoodLog/pos_login_estabelecimento/cadastrar_produto.php">Cadastrar Produtos</a></li>
                    <li><a href="/FoodLog/menu/index.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <script>
        document.getElementById('product-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = document.getElementById('product-form');
            const formData = new FormData(form);

            // 1. Envia os dados para o script PHP
            // Verifique o caminho. '../php/salvar_produto.php' pressupõe que dashboard_estabelecimento.php
            // está um nível acima de 'php'.
            fetch('../FoodLog/PHP/salvar_produtos.php', { 
                method: 'POST',
                body: formData
            })
            .then(response => {
                // Se o servidor retornar HTML (404, 500, ou redirecionamento), isso vai falhar.
                if (!response.ok) {
                    throw new Error('Erro HTTP: ' + response.status);
                }
                return response.json(); 
            })
            .then(data => {
                if (data.success) {
                    alert('Produto cadastrado com sucesso!');
                    window.location.href = 'meus_produtos.php'; 
                } else {
                    alert('Erro ao cadastrar produto: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro de rede ou no servidor:', error);
                // Esta mensagem é exibida se o JSON for inválido (SyntaxError) ou a requisição falhar (404/500)
                alert('Ocorreu um erro ao tentar se conectar ou processar a resposta do servidor. Verifique o console (F12) para detalhes.');
            });
        });
        
        // Trecho da notificação
        const pedidoFinalizado = localStorage.getItem('pedidoFinalizado');
        if (pedidoFinalizado === 'true') {
            alert('Novo pedido recebido!');
            localStorage.removeItem('pedidoFinalizado');
        }
    </script>
</body>
</html>