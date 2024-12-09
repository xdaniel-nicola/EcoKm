<?php
session_start();
require_once "../php/conexao.php";
require_once "../usuario/utils.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $pdo = conectaPDO();
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE login = :login");
    $stmt->bindParam(':login', $login);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        if ($login === 'admin' and $senha === $user['senha']) {
            $_SESSION['username'] = $user['nome'];
            registraLog($pdo, $user['nome'], "Logou no sistema");
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
            $_SESSION['login'] = $login;
            $_SESSION['cpf'] = $user['cpf'];

            // Redirecionando para a página de 2FA
            registraLog($pdo, $login, "Logou e foi para o 2FA");
            header("Location: verificar_2fa.php");
            exit();
        } else {
            echo "<div style='border: 1px solid #000000; margin: 15vw 0 0 37vw; width: 400px; border-radius:10px;'>";
                echo "<p style='text-align:center; font-size:20px; font-weight:bold; color: #b30000;';>Usuário ou senha inválidos. Tente novamente.</p>";
                echo "<p style='text-align:center; font-size:20px; font-weight:bold; margin-top:10px;';>Redirecionamento automático em 2 segundos..</p>";
                header("Refresh: 2, url=loginForm.php");
            echo "</div>";
        }
    } else {
        echo "<div style='border: 1px solid #000000; margin: 15vw 0 0 37vw; width: 400px; border-radius:10px;'>";
            echo "<p style='text-align:center; font-size:20px; font-weight:bold; color: #b30000;';>Usuário ou senha inválidos. Tente novamente.</p>";
            echo "<p style='text-align:center; font-size:20px; font-weight:bold; margin-top:10px;';>Redirecionamento automático em 2 segundos..</p>";
            header("Refresh: 2, url=loginForm.php");
        echo "</div>";
    }
}
?>