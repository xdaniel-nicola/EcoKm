<?php
session_start();
require_once "../php/conexao.php";

$pdo = conectaPDO();

if (!isset($_SESSION['pergunta']) || !isset($_SESSION['resposta'])) {
    header("Location: loginForm.php"); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(empty($_POST['resposta'])) {
        echo "Por favor, preencha a resposta.";
        exit;
    }

    if(!isset($_SESSION['tentativas'])) {
        $_SESSION['tentativas'] = 0;
    }

    if ($_POST['resposta'] == $_SESSION['resposta']) {
        $_SESSION['logado'] = true;
        session_start();
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['tentativas']++;
    }

    if ($_SESSION['tentativas'] >= 3) {
        echo "3 tentativas sem sucesso! Favor realizar login novamente.";
        session_destroy(); 
        header("Refresh: 2; url=loginForm.php"); 
        exit();
    } else {
        echo "Resposta incorreta. Tentativa " . $_SESSION['tentativas'] . " de 3.<style> body.light-mode {color:black;}</style>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de 2FA</title>
    <link rel="stylesheet" href="2fa.css">
    <script src="login.js" defer></script>
</head>
<body>
    <div class="telaLogin">
        <div class="card" method="post">
            <div class="homePage">
                
            </div>
            <div class="theme-toggle">
                <input type="checkbox" id="toggle-switch" class="toggle-switch">
                <label for="toggle-switch" class="toggle-label">
                <span class="toggle-slider"></span>
                </label>
            </div>
            <div class="card-header">
                <h2 name="titulo" id="titulo">Verificação de 2 fatores</h2>
            </div>
            
            <div class="conteudo">
                <div class="usuario">
                    <label for="login">Login</label>
                    <input disabled class="inputs" type="text" name="login" id="login"  value="<?php echo isset($_SESSION['login']) ? htmlspecialchars($_SESSION['login']) : ''; ?>">
                </div>
                <p>Pergunta de segurança: Qual o seu(ua):  <?php echo ucfirst(str_replace('_', ' ', $_SESSION['pergunta'])) ?>?</p>
                <form class="aut2f" action="verificar_2fa.php" method="POST">
                    <div class="resposta">
                    <label for="resposta">Resposta:</label>
                    <input class="inputs" type="text" name="resposta" placeholder="Digite a resposta" required>
                    </div>
                    <div>
                    <input class="verif" type="submit" id="enviar" value="Verificar">
                    </div>
                </form>
            </div>
        </div>
</body>
</html>