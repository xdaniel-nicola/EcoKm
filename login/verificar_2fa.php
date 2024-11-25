<?php
session_start();

// Verifica se há uma pergunta de 2FA na sessão
if (!isset($_SESSION['pergunta']) || !isset($_SESSION['resposta'])) {
    header("Location: loginForm.php"); // Caso a pergunta de 2FA não esteja definida, redireciona para o login
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(empty($_POST['resposta'])) {
        echo "Por favor, preencha a resposta.";
        exit;
    }

    // Inicia o contador de tentativas caso não exista
    if(!isset($_SESSION['tentativas'])) {
        $_SESSION['tentativas'] = 0; // Inicia a contagem de tentativas
    }

    // Verifica se a resposta está correta
    if ($_POST['resposta'] == $_SESSION['resposta']) {
        // Resposta correta, o usuário pode acessar a página principal
        $_SESSION['logado'] = true;
        header("Location: ../index.php");
        exit();
    } else {
        // Resposta incorreta, incrementa o contador de tentativas
        $_SESSION['tentativas']++;
    }

    // Se o número de tentativas for 3 ou mais, destrói a sessão e redireciona para o login
    if ($_SESSION['tentativas'] >= 3) {
        echo "3 tentativas sem sucesso! Favor realizar login novamente.";
        session_destroy(); // Destroi a sessão
        header("Refresh: 2; url=loginForm.php"); // Redireciona para o login após 3 tentativas falhas
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
                <a href="..\index.php" class="voltarHome">Início</a>
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
                    <label for="usuario">Login</label>
                    <input class="inputs" type="text" name="login" id="login" autocomplete="off" value="<?php $_SESSION['username'];?>">
                </div>
                <p>Pergunta de segurança: Qual é o seu <?= ucfirst(str_replace('_', ' ', $_SESSION['pergunta'])) ?>?</p>
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