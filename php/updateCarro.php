<?php
session_start();
require_once "conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_carro = $_POST['id_carro'] ?? null;
    $modelo = $_POST['modelo'] ?? null;
    $marca = $_POST['marca'] ?? null;
    $motor = $_POST['motor'] ?? null;

    // Verifica se os campos foram preenchidos
    if (empty($modelo) || empty($marca) || empty($motor)) {
        echo "Erro: Todos os campos devem ser preenchidos.";
        exit();
    }

    $pdo = conectaPDO();

    if ($pdo) {
        try {
            // Debug para verificar os dados recebidos
            var_dump($id_carro, $modelo, $marca, $motor);

            $sql = "UPDATE carro 
                    SET modelo = :modelo, marca = :marca, motor = :motor 
                    WHERE id_carro = :id_carro";
            $stmt = $pdo->prepare($sql);

            // Vincula os valores ao comando SQL
            $stmt->bindValue(':id_carro', $id_carro, PDO::PARAM_INT);
            $stmt->bindValue(':modelo', $modelo, PDO::PARAM_STR);
            $stmt->bindValue(':marca', $marca, PDO::PARAM_STR);
            $stmt->bindValue(':motor', $motor, PDO::PARAM_STR);

            // Executa a consulta
            if ($stmt->execute()) {
                echo "Dados atualizados com sucesso!";
                header("Location: ../usuario/perfil.php");
                exit();
            } else {
                echo "Erro: Falha ao atualizar os dados.";
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    } else {
        echo "Erro: Não foi possível conectar ao banco de dados.";
    }
} else {
    echo "Erro: Método de requisição inválido.";
}
