<?php
$host = "localhost";
$user = "root";        // seu usuário do MySQL
$pass = "";            // sua senha do MySQL
$db   = "foodlog_db";  // nome do banco

$conn = new mysqli($host, $user, $pass, $db);

// Checa conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
