<?php
session_start();
require_once "conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf']; 
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];
    $motor = $_POST['motor'];

    if (empty($modelo) || empty($marca) || empty($motor)) {
        echo "Por favor, preencha todos os campos.";
        exit();
    }

    $pdo = conectaPDO();

    if ($pdo) {
        try {
            $sql = "UPDATE carro SET modelo = :modelo, marca = :marca, motor = :motor WHERE cpf = :cpf";
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':modelo', $modelo, PDO::PARAM_STR);
            $stmt->bindValue(':marca', $marca, PDO::PARAM_STR);
            $stmt->bindValue(':motor', $motor, PDO::PARAM_STR);
            $stmt->bindValue(':cpf', $cpf, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "Dados atualizados com sucesso!";
                header("Location: ../usuario/perfil.php"); 
                exit();
            } else {
                echo "Erro ao atualizar os dados.";
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    } else {
        echo "Erro na conexão com o banco de dados.";
    }
} else {
    echo "Método de requisição inválido.";
}
