<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="telaLogin">
        <form class="card" method="post" action="loginHandlerNP.php">
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
                <h2 name="titulo" id="titulo">Recuperar senha</h2>
            </div>
            
            <div class="conteudo">
                <div class="usuario">
                    <label for="usuario">Login</label>
                    <input class="inputs" type="text" name="login" id="login" autocomplete="off" placeholder="Digite seu login">
                </div>
                <div class="email">
                    <label for="email">Email</label>
                    <input class="inputs" type="text" name="email" id="email" autocomplete="off" placeholder="Digite seu email">
                </div>
                <div class="rodape">
                    <input type="submit" name="enviar" id="enviar" value="Entrar">
                    <h5>Não tem conta? <a href="../cadastro/cadastro.php" class="loginEmpresa">Cadastre-se gratuitamente.</a></h5>
                </div>
            </div>
        </form>
    </div>
    <script src="login.js"></script>
</body>
</html>