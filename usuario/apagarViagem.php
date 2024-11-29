<?php 
require "../php/conexao.php";

if (!empty($_GET['id'])) {
    $id_viagem = $_GET['id']; 

    $pdo = conectaPDO();

    if ($pdo) {
        $sqlDelete = "DELETE FROM viagem WHERE id_viagem = :id_viagem";
        $stmtDelete = $pdo->prepare($sqlDelete);

        $stmtDelete->bindValue(':id_viagem', $id_viagem, PDO::PARAM_INT);

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
