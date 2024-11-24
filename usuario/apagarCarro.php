<?php 
require "../php/conexao.php";

if (!empty($_GET['id'])) {
    $id_carro = $_GET['id']; 

    $pdo = conectaPDO();

    if ($pdo) {
        $sqlDelete = "DELETE FROM carro WHERE id_carro = :id_carro";
        $stmtDelete = $pdo->prepare($sqlDelete);

        $stmtDelete->bindValue(':id_carro', $id_carro, PDO::PARAM_INT);

        if ($stmtDelete->execute()) {
            header('Location: perfil.php?msg=Carro excluído com sucesso');
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
