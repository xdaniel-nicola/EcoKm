<?php
include_once "../php/conexao.php";
include_once "../usuario/utils.php";

session_start();
$pdo = conectaPDO();

if (isset($_SESSION['login'])) {
    $usuario = $_SESSION['login'];
    registraLog($pdo, $usuario, "Saiu do sistema");
}

session_destroy();

header("Location: ../index.php");
exit();
?>
