<?php
session_start();
require_once "../php/conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $conn = conectaPDO();
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE login = :login AND senha = :senha");
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        $_SESSION['username'] = $user['nome'];
        header("Location: ../index.php"); 
        exit();
    } else {
        echo "Usuário ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro ao logar</title>
</head>
<body>
    <a href="loginForm.php">Voltar para a página de login.</a>
</body>
</html>