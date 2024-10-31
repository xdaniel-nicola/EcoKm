<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="telaLogin">
        <form class="card" method="post" action="loginHandler.php">
            <div class="homePage">
                <a href="..\index.html" class="voltarHome">Início</a>
            </div>
            <div class="card-header">
                <h2 name="titulo" id="titulo">Vamos começar</h2>
            </div>
            <div class="modoTema">
                <button id="toggleTheme" class="themeBtn" type="button">
                    <span id="themeIcon" class="moon">🌙</span>
                </button>
            </div>
            <div class="conteudo">
                <div class="usuario">
                    <label for="usuario">Email</label>
                    <input class="inputs" type="text" name="email" id="email" placeholder="Digite seu email">
                </div>
                <div class="senha">
                    <label for="senha">Senha</label>
                    <input class="inputs" type="password" name="senha" id="senha" placeholder="Digite sua senha">
                </div>
                <div class="rodape">
                    <input type="submit" name="enviar" id="enviar" value="Entrar">
                    <a href="#" class="recuperarSenha">Esqueceu a sua senha?</a>
                    <h5>Não tem conta? <a href="../cadastro/cadastro.html" class="loginEmpresa">Cadastre-se gratuitamente.</a></h5>
                </div>
            </div>
        </form>
    </div>
    <script src="login.js"></script>
</body>
</html>
