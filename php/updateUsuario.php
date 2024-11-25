<?php
session_start();
require_once "conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $celular1 = $_POST['celular1'];
    $celular2 = $_POST['celular2'];
    $cep = $_POST['cep'];
    $endereco = $_POST['endereco'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $login = $_POST['login'];
    $senha = $_POST['senha'];
    $sexo = $_POST['sexo'];

    // Verifica se os campos obrigatórios estão preenchidos
    if (empty($cpf) || empty($email) || empty($celular1) || empty($cep) || empty($endereco) || empty($cidade) || empty($bairro) || empty($login) || empty($senha) || empty($sexo)) {
        echo "Todos os campos obrigatórios devem ser preenchidos.";
        exit();
    }

    $pdo = conectaPDO();

    if ($pdo) {
        try {
            // Verifica se o CPF existe no banco
            $sqlCheck = "SELECT cpf FROM usuario WHERE cpf = :cpf";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->bindValue(':cpf', $cpf, PDO::PARAM_STR);
            $stmtCheck->execute();

            if ($stmtCheck->rowCount() === 0) {
                echo "CPF não encontrado no banco de dados.";
                exit();
            }

            // Atualiza os dados
            $sql = "UPDATE usuario SET 
                    email = :email, 
                    celular1 = :celular1, 
                    celular2 = :celular2,
                    cep = :cep, 
                    endereco = :endereco, 
                    cidade = :cidade, 
                    bairro = :bairro, 
                    login = :login, 
                    senha = :senha, 
                    sexo = :sexo
                    WHERE cpf = :cpf";
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':cpf', $cpf, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':celular1', $celular1, PDO::PARAM_STR);
            $stmt->bindValue(':celular2', $celular2, PDO::PARAM_STR);
            $stmt->bindValue(':cep', $cep, PDO::PARAM_STR);
            $stmt->bindValue(':endereco', $endereco, PDO::PARAM_STR);
            $stmt->bindValue(':cidade', $cidade, PDO::PARAM_STR);
            $stmt->bindValue(':bairro', $bairro, PDO::PARAM_STR);
            $stmt->bindValue(':login', $login, PDO::PARAM_STR);

            $hashedPassword = password_hash($senha, PASSWORD_DEFAULT);
            $stmt->bindValue(':senha', $hashedPassword, PDO::PARAM_STR);

            $stmt->bindValue(':sexo', $sexo, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "Dados atualizados com sucesso!";
                header("Location: ../usuario/perfil.php");
                exit();
            } else {
                echo "Erro ao atualizar os dados: ";
                print_r($stmt->errorInfo());
            }
        } catch (PDOException $e) {
            echo "Erro ao executar a query: " . $e->getMessage();
        }
    } else {
        echo "Erro na conexão com o banco de dados.";
    }
} else {
    echo "Método de requisição inválido.";
}
