<?php 
require "../php/conexao.php";
require "utils.php";
session_start();
$login = $_SESSION['login'];

if (!empty($_GET['id'])) {
    $id_viagem = $_GET['id']; 

    $pdo = conectaPDO();

    if ($pdo) {
        $sqlDelete = "DELETE FROM viagem WHERE id_viagem = :id_viagem";
        $stmtDelete = $pdo->prepare($sqlDelete);

        $stmtDelete->bindValue(':id_viagem', $id_viagem, PDO::PARAM_INT);

        if ($stmtDelete->execute()) {
            registraLog($pdo, $_SESSION['login'], "Apagou viagem salva");
            header('Location: perfil.php#viagens');
            exit();
        } else {
            echo '<p>Erro ao excluir o carro.</p>';
        }
    } else {
        echo '<p>Erro na conexão com o banco de dados.</p>';
    }
} else {
    echo '<p>ID do carro não fornecido.</p>';
}
?>
