<?php
session_start();
require_once "../php/conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $conn = conectaPDO();
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE login = :login");
    $stmt->bindParam(':login', $login);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        if ($login === 'admin') {
            $_SESSION['username'] = $user['nome'];
            header("Location: ../index.php"); 
            exit();
        }

        if (password_verify($senha, $user['senha'])) {
            $_SESSION['username'] = $user['nome'];

            $perguntas = [
                'Nome Materno' => $user['nomeMae'],
                'CEP' => $user['cep'],
                'Endereço' => $user['endereco'],
                'Bairro' => $user['bairro'],
                'Cidade' => $user['cidade']
            ];

            $pergunta_aleatoria = array_rand($perguntas);

            $_SESSION['pergunta'] = $pergunta_aleatoria;
            $_SESSION['resposta'] = $perguntas[$pergunta_aleatoria];

            // Redirecionando para a página de 2FA
            header("Location: verificar_2fa.php");
            exit();
        } else {
            echo "Usuário ou senha inválidos.";
        }
    } else {
        echo "Usuário ou senha inválidos.";
    }
}
?>