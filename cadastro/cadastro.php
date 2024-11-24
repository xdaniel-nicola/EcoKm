<?php
require_once "../php/conexao.php";
    $pdo = conectaPDO();
?>
<!DOCTYPE html>
<html lang="pt-br">   
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-Ua-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cadastro.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6-beta.24/jquery.inputmask.min.js"></script>
    <script src="script.js" defer></script>
    <title>Cadastre sua conta</title>
</head>
<body>
    <div class="topoPag">
        <p class="txtTopo"></p>
    </div>
    <div class="telaCadastro">
        <form name="form" id="form" class="card" action="../php/insert.php" method="post">
            
            <div class="homePage">
                <a href="..\index.php" class="voltarHome"> Voltar</a>
                <button type="button" id="toggleTheme" class="toggle-button">
                    <span id="themeIcon">🌙</span>
                </button>
            </div>
            <div class="card-header">
                <h2 name="titulo" id="titulo">Vamos começar</h2>
                <p name="telaLogin" id="telaLogin">Já possui uma conta? <a class="voltarLogin" href="..\login\loginForm.php">Login</a></p>
            </div>
            <div class="conteudo">
                <div class="nome">
                    <label for="nome">Nome:</label>
                    <input class="inputs" type="text" name="nome" id="nome" placeholder="Digite seu nome" oninput="nameValidate()">
                    <span class="span-required">Nome deve ter no mínimo 15 caracteres.</span>
                </div>
                <div class="email">
                    <label for="email">E-mail:</label>
                    <input class="inputs" autocomplete="on" type="text" name="email" id="email" placeholder="Digite seu e-mail" oninput="emailValidate()">
                    <span class="span-required">Digite um e-mail válido.</span>
                </div>
                <div class="cpf">
                    <label for="cpf">CPF:</label>
                    <input class="inputs" maxlength="14" type="text" name="cpf" id="cpf" placeholder="000.000.000-00" oninput="cpfValidate()">
                    <span class="span-required">Digite um CPF válido.</span>
                </div>
                <div class="celular1">
                    <label for="celular1">Celular 1:</label>
                    <input class="inputs" maxlength="18" type="text" name="celular1" id="celular1" placeholder="+55 (00)00000-0000" oninput="celular1Validate()">
                    <span class="span-required">Digite um celular válido.</span>
                </div>
                <div class="dt_nasc">
                    <label for="dt_nasc">Data de nascimento:</label>
                    <input class="inputs" maxlength="10" type="text" name="dt_nasc" id="dt_nasc" placeholder="dd/mm/aaaa" oninput="dateValidate()">
                    <span class="span-required" name="dt_nasc_erro" id="dt_nasc_erro">Digite uma data válida.</span>
                </div>
                <div class="celular2">
                    <label for="celular2">Celular 2:</label>
                    <input class="inputs" maxlength="18" type="text" name="celular2" id="celular2" placeholder="+55 (00)00000-0000" oninput="celular2Validate()">
                    <span class="span-required">Digite um celular válido.</span>
                </div>
                <div class="cep">
                    <label for="cep">CEP:</label>
                    <input class="inputs" maxlength="9" type="text" name="cep" id="cep" placeholder="00000-000" oninput="cepValidate()" onchange="validarCamposPreenchidosPelaApi()">
                    <span class="span-required">Digite um CEP válido.</span>
                </div>
                <div class="nomeMae">
                    <label for="nomeMae">Nome materno:</label>
                    <input class="inputs" type="text" name="nomeMae" id="nomeMae" placeholder="Digite o nome da sua mãe" oninput="nomeMaeValidate()">
                    <span class="span-required">Nome Materno deve ter no mínimo 15 caracteres.</span>
                </div>
                <div class="endereco">
                    <label for="endereco">Endereço:</label>
                    <input class="inputs" type="text" name="endereco" id="endereco" placeholder="Rua, Estrada, Avenida e Nº" oninput="enderecoValidate()" onchange="enderecoValidate()">
                    <span class="span-required">O endereço deve ter no mínimo 3 caracteres.</span>
                </div>
                <div class="login">
                    <label for="login">Login:</label>
                    <input class="inputs" type="text" name="login" id="login" placeholder="Digite seu login" oninput="loginValidate()">
                    <span class="span-required">Login deve ter exatamente 6 caracteres.</span>
                </div>
                <div>
                    <label for="cidade">Cidade:</label>
                    <input class="inputs" type="text" id="cidade" name="cidade" placeholder="Cidade" oninput="cidadeValidate()" onchange="cidadeValidate()">
                    <span class="span-required">A cidade deve ter no mínimo 2 caracteres.</span>
                </div>
                <div class="senha">
                    <label for="senha">Senha:</label>
                    <input class="inputs" type="password" name="senha" id="senha" placeholder="Digite sua senha" oninput="mainPasswordValidate()">
                    <span class="span-required">A senha deve ter no mínimo 8 caracteres.</span>
                </div>
                <div class="bairro">
                    <label for="bairro">Bairro: </label>
                    <input class="inputs" name="bairro" id="bairro" type="text" placeholder="Bairro" oninput="bairroValidate()" onchange="bairroValidate()">
                    <span class="span-required">O bairro deve ter no mínimo 3 caracteres.</span>
                </div> 
                <div class="confirmaSenha">
                    <label for="confirmaSenha">Confirme a senha:</label>
                    <input class="inputs" type="password" name="confirmaSenha" id="confirmaSenha" placeholder="Confirme sua senha" oninput="comparePassword()">
                    <span class="span-required">Senhas devem ser compatíveis.</span>
                </div>
                <div class="sexo">
                    <label for="sexo">Sexo:</label>
                    <select required class="sexos" name="sexo" id="sexo" onchange="sexoValidate()">
                        <option hidden value="0" name="sexo" id="sexo">Selecione seu sexo:</option>
                        <option value="Masculino" name="sexo" id="sexo" value="1" >Masculino</option>
                        <option value="Feminino" name="sexo" id="sexo" value="2">Feminino</option>
                        <option value="Outro" name="sexo" id="sexo" value="3">Outro</option>
                    </select>
                    <span class="span-required">Por favor, selecione um sexo.</span>
                </div>
                <input class="limpar" type="reset" name="apagar" id="apagar" value="Apagar">
            </div>
            <div class="rodape">
                <input type="submit" name="enviar" id="enviar" class="enviar" value="Enviar">
            </div>
        </form>
        <footer><div class="footer"><p>.</p></div></footer>
    </div>
</body>
</html>