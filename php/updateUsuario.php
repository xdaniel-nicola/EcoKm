<?php
session_start();
require_once "conexao.php";
require_once "../usuario/utils.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $celular1 = $_POST['celular1'];
    $celular2 = $_POST['celular2'];
    $nomeMae = $_POST['nomeMae'];
    $cep = $_POST['cep'];
    $endereco = $_POST['endereco'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $login = $_POST['login'];
    $senha = $_POST['senha'];
    $sexo = $_POST['sexo'];

    // Verifica se os campos obrigatórios estão preenchidos
    if (empty($cpf) || empty($nome) || empty($email) || empty($celular1) || empty($celular2) || empty($nomeMae) || empty($cep) || empty($endereco) || empty($cidade) || empty($bairro) || empty($login) || empty($senha) || empty($sexo)) {
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
                    nome = :nome,
                    email = :email, 
                    celular1 = :celular1, 
                    celular2 = :celular2,
                    nomeMae = :nomeMae,
                    cep = :cep, 
                    endereco = :endereco, 
                    cidade = :cidade, 
                    bairro = :bairro, 
                    login = :login, 
                    senha = :senha, 
                    sexo = :sexo
                    WHERE cpf = :cpf";
            $stmt = $pdo->prepare($sql);

            function limparMascara($valor) {
                return trim(preg_replace('/[^0-9]/', '', $valor));
            }
    
            function prepararTexto($valor) {
                return strtoupper(trim($valor));
            }

            $nome = trim($_POST['nome']);
            $email = trim($_POST['email']);
            $cpf = trim($_POST['cpf']);
            $celular1 = trim($_POST['celular1']);
            $celular2 = trim($_POST['celular2']);
            $nomeMae = trim($_POST['nomeMae']);
            $cep = trim($_POST['cep']);
            $endereco = trim($_POST['endereco']);
            $login = trim($_POST['login']);
            $cidade = trim($_POST['cidade']);
            $senha = $_POST['senha'];
            $bairro = trim($_POST['bairro']);
            $sexo = trim($_POST['sexo']);

            $stmt->bindvalue(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindValue(':cpf', $cpf, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':celular1', $celular1, PDO::PARAM_STR);
            $stmt->bindValue(':celular2', $celular2, PDO::PARAM_STR);
            $stmt->bindValue(':nomeMae', $nomeMae, PDO::PARAM_STR);
            $stmt->bindValue(':cep', $cep, PDO::PARAM_STR);
            $stmt->bindValue(':endereco', $endereco, PDO::PARAM_STR);
            $stmt->bindValue(':cidade', $cidade, PDO::PARAM_STR);
            $stmt->bindValue(':bairro', $bairro, PDO::PARAM_STR);
            $stmt->bindValue(':login', $login, PDO::PARAM_STR);

            $hashedPassword = password_hash($senha, PASSWORD_DEFAULT);
            $stmt->bindValue(':senha', $hashedPassword, PDO::PARAM_STR);

            $stmt->bindValue(':sexo', $sexo, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "<h1>Dados atualizados com sucesso!</h1>";
                registraLog($pdo, $login, "Alterou dados do perfil.");
                header("Refresh: 2; url=../usuario/perfil.php"); 
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
