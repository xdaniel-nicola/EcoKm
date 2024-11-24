<?php
include "conexao.php";

if(isset($_GET['id'])) {
    $cpf = $_GET['id'];
}

$pdo = conectaPDO();

if($pdo) {
    try {
        $sql = "DELETE FROM usuario WHERE cpf = :cpf";
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':cpf', $cpf);

        if($stmt->execute()) {
            header("Location: ../usuario/pesquisa.php");
            exit;
        } else {
            echo "<p>Erro ao exckuir o usuário.<p>";
        }
    }
    catch (PDOException $e) {
        echo "<p>Erro: " . $e->getMessage() . "</p>";
        }
}
?>