<?php
session_start();
require_once "../php/conexao.php"; // Caminho ajustado para conexoes.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['email'];  // Mudança no nome do campo
    $senha = $_POST['senha'];

    $conn = conectaPDO();
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE login = :login AND senha = :senha"); // Altere para a tabela `usuario`
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        $_SESSION['username'] = $user['nome'];  // Usar a coluna `nome`
        header("Location: ../indexLogin.php"); // Redireciona para a nova página
        exit();
    } else {
        echo "Usuário ou senha inválidos.";
    }
}
?>
