<?php
session_start();
require_once "conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf']; // Identificador do usuário
    // $nome = $_POST['nome'];
    $email = $_POST['email'];
    $celular1 = $_POST['celular1'];
    $celular2 = $_POST['celular2'];
    // $nomeMae = $_POST['nomeMae'];
    // $dt_nasc = $_POST['dt_nasc'];
    $cep = $_POST['cep'];
    $endereco = $_POST['endereco'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $login = $_POST['login'];
    $senha = $_POST['senha'];
    $sexo = $_POST['sexo'];
    var_dump($_POST);

    // $date = DateTime::createFromFormat('d/m/Y', $dt_nasc);
    // if ($date) {
    //     $dt_nasc_formatada = $date->format('Y-m-d');
    // } else {
    //     echo "Erro na formatação da data de nascimento.";
    //     exit(); // Encerra o script caso a data não seja válida
    // }

    $pdo = conectaPDO();

    if ($pdo) {
        try {
            // Query de atualização
            $sql = "UPDATE usuario SET 
            -- nome = :nome, 
            email = :email, 
            celular1 = :celular1, 
            celular2 = :celular2,
            -- dt_nasc = :dt_nasc,
            -- nomeMae = :nomeMae,
            cep = :cep, 
            endereco = :endereco, 
            cidade = :cidade, 
            bairro = :bairro, 
            login = :login, 
            senha = :senha, 
            sexo = :sexo
            WHERE cpf = :cpf";
            $stmt = $pdo->prepare($sql);

            // Bind dos valores
            $stmt->bindValue(':cpf', $cpf, PDO::PARAM_STR);
            // $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':celular1', $celular1, PDO::PARAM_STR);
            $stmt->bindValue(':celular2', $celular2, PDO::PARAM_STR);
            // $stmt->bindValue(':nomeMae', $nomeMae, PDO::PARAM_STR);
            // $stmt->bindValue(':dt_nasc', $dt_nasc_formatada, PDO::PARAM_STR); // Data no formato SQL
            $stmt->bindValue(':cep', $cep, PDO::PARAM_STR);
            $stmt->bindValue(':endereco', $endereco, PDO::PARAM_STR);
            $stmt->bindValue(':cidade', $cidade, PDO::PARAM_STR);
            $stmt->bindValue(':bairro', $bairro, PDO::PARAM_STR);
            $stmt->bindValue(':login', $login, PDO::PARAM_STR);
            $stmt->bindValue(':senha', $senha, PDO::PARAM_STR);
            $stmt->bindValue(':sexo', $sexo, PDO::PARAM_STR);



            if ($stmt->execute()) {
                echo "Dados atualizados com sucesso!";
                // header("Location: ../usuario/perfil.php");
                exit();
            } else {
                echo "Erro ao atualizar os dados: ";
                print_r($stmt->errorInfo()); // Adiciona informações detalhadas sobre o erro
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
