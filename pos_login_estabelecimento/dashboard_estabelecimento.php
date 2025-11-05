<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/FoodLog/php/conexao.php';

// Se já não está logado, verifica cookie
if(!isset($_SESSION['id_usuario']) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];

    $stmt = $conn->prepare("SELECT id_usuario, expiracao FROM tokens_login WHERE token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        if(strtotime($row['expiracao']) > time()) {
            // Cookie válido, cria sessão
            $stmt2 = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = ? LIMIT 1");
            $stmt2->bind_param("i", $row['id_usuario']);
            $stmt2->execute();
            $userRes = $stmt2->get_result()->fetch_assoc();

            $_SESSION['id_usuario'] = $userRes['id_usuario'];
            $_SESSION['nome_usuario'] = $userRes['nome_usuario'];
            $_SESSION['tipo'] = $userRes['tipo_usuario'];
        } else {
            // Token expirado
            setcookie("remember_me", "", time()-3600, "/");
        }
    } else {
        // Token inválido
        setcookie("remember_me", "", time()-3600, "/");
    }
}

// Se ainda não está logado
if(!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
?>
