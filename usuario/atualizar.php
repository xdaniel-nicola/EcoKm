<?php
require "../php/conexao.php";

if (!empty($_GET['nome'])) {
    $nome = $_GET['nome'];

    $pdo = conectaPDO();

    if ($pdo) {
        $sqlSelect = "SELECT * FROM usuario WHERE nome = :nome";
        $stmt = $pdo->prepare($sqlSelect);

        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);

        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
        }
    }
}
if (isset($usuario['dt_nasc'])) {
    $data = $usuario['dt_nasc'];
    $date = DateTime::createFromFormat('Y-m-d', $data);
    $data_formatada = $date->format('dmY'); 
}
if ($usuario) {
    if (isset($usuario['celular1']) && substr($usuario['celular1'], 0, 2) === '55') {
        $usuario['celular1'] = substr($usuario['celular1'], 2); 
    }

    if (isset($usuario['celular2']) && substr($usuario['celular2'], 0, 2) === '55') {
        $usuario['celular2'] = substr($usuario['celular2'], 2); 
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">   
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-Ua-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/atualizar.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6-beta.24/jquery.inputmask.min.js"></script>
    <script src="js/atualizar.js" defer></script>
    <title>Atualizar dados</title>
</head>
<body>
    <div class="topoPag">
        <p class="txtTopo"></p>
    </div>
    <div class="telaAtualizar">
        <form name="form" id="form" class="card" action="../php/updateUsuario.php" method="post">
            
            <div class="homePage">
                <a href="perfil.php" class="voltarHome"> Voltar</a>
                <button type="button" id="toggleTheme" class="toggle-button">
                    <span id="themeIcon">🌙</span>
                </button>
            </div>
            <div class="card-header">
                <h2 name="titulo" id="titulo">Atualizar dados</h2>
            </div>
            <div class="conteudo">
                <div class="nome">
                    <label for="nome">Nome:</label>
                    <input disabled class="inputs" type="text" name="nome" id="nome" placeholder="Digite seu nome" oninput="nameValidate()" value="<?php echo isset($usuario['nome']) ? htmlspecialchars($usuario['nome']) : ''; ?>">
                    <span class="span-required">Nome deve ter no mínimo 15 caracteres.</span>
                </div>
                <div class="email">
                    <label for="email">E-mail:</label>
                    <input class="inputs" autocomplete="on" type="text" name="email" id="email" placeholder="Digite seu e-mail" oninput="emailValidate()" value="<?php echo isset($usuario['email']) ? htmlspecialchars($usuario['email']) : ''; ?>">
                    <span class="span-required">Digite um e-mail válido.</span>
                </div>
                <div class="cpf">
                    <label for="cpf">CPF:</label>
                    <input readonly class="inputs" maxlength="14" type="text" name="cpf" id="cpf" placeholder="000.000.000-00" oninput="cpfValidate()" value="<?php echo isset($usuario['cpf']) ? htmlspecialchars($usuario['cpf']) : ''; ?>">
                    <span class="span-required">Digite um CPF válido.</span>
                </div>
                <div class="celular1">
                    <label for="celular1">Celular 1:</label>
                    <input class="inputs" maxlength="18" type="text" name="celular1" id="celular1" placeholder="+55 (00)00000-0000" oninput="celular1Validate()" value="<?php echo isset($usuario['celular1']) ? htmlspecialchars($usuario['celular1']) : ''; ?>">
                    <span class="span-required">Digite um celular válido.</span>
                </div>
                <div class="dt_nasc">
                    <label for="dt_nasc">Data de nascimento:</label>
                    <input disabled class="inputs" maxlength="10" type="text" name="dt_nasc" id="dt_nasc" placeholder="dd/mm/aaaa" oninput="dateValidate()" value="<?php echo $data_formatada; ?>">
                    <span class="span-required" name="dt_nasc_erro" id="dt_nasc_erro">Digite uma data válida.</span>
                </div>
                <div class="celular2">
                    <label for="celular2">Celular 2:</label>
                    <input class="inputs" maxlength="18" type="text" name="celular2" id="celular2" placeholder="+55 (00)00000-0000" oninput="celular2Validate()" value="<?php echo isset($usuario['celular2']) ? htmlspecialchars($usuario['celular2']) : ''; ?>">
                    <span class="span-required">Digite um celular válido.</span>
                </div>
                <div class="cep">
                    <label for="cep">CEP:</label>
                    <input class="inputs" maxlength="9" type="text" name="cep" id="cep" placeholder="00000-000" oninput="cepValidate()" onchange="validarCamposPreenchidosPelaApi()" value="<?php echo isset($usuario['cep']) ? htmlspecialchars($usuario['cep']) : ''; ?>">
                    <span class="span-required">Digite um CEP válido.</span>
                </div>
                <div class="nomeMae">
                    <label for="nomeMae">Nome materno:</label>
                    <input class="inputs" type="text" name="nomeMae" id="nomeMae" placeholder="Digite o nome da sua mãe" oninput="nomeMaeValidate()" value="<?php echo isset($usuario['nomeMae']) ? htmlspecialchars($usuario['nomeMae']) : ''; ?>">
                    <span class="span-required">Nome Materno deve ter no mínimo 15 caracteres.</span>
                </div>
                <div class="endereco">
                    <label for="endereco">Endereço:</label>
                    <input class="inputs" type="text" name="endereco" id="endereco" placeholder="Rua, Estrada, Avenida e Nº" oninput="enderecoValidate()" onchange="enderecoValidate()" value="<?php echo isset($usuario['endereco']) ? htmlspecialchars($usuario['endereco']) : ''; ?>">
                    <span class="span-required">O endereço deve ter no mínimo 3 caracteres.</span>
                </div>
                <div class="login">
                    <label for="login">Login:</label>
                    <input class="inputs" type="text" name="login" id="login" placeholder="Digite seu login" oninput="loginValidate()" value="<?php echo isset($usuario['login']) ? htmlspecialchars($usuario['login']) : ''; ?>">
                    <span class="span-required">Login deve ter exatamente 6 caracteres.</span>
                </div>
                <div>
                    <label for="cidade">Cidade:</label>
                    <input class="inputs" type="text" id="cidade" name="cidade" placeholder="Cidade" oninput="cidadeValidate()" onchange="cidadeValidate()" value="<?php echo isset($usuario['cidade']) ? htmlspecialchars($usuario['cidade']) : ''; ?>">
                    <span class="span-required">A cidade deve ter no mínimo 2 caracteres.</span>
                </div>
                <div class="senha">
                    <label for="senha">Senha:</label>
                    <input class="inputs" type="password" name="senha" id="senha" placeholder="Digite sua senha" oninput="mainPasswordValidate()" value="<?php echo isset($usuario['senha']) ? htmlspecialchars($usuario['senha']) : ''; ?>">
                    <span class="span-required">A senha deve ter no mínimo 8 caracteres.</span>
                </div>
                <div class="bairro">
                    <label for="bairro">Bairro: </label>
                    <input class="inputs" name="bairro" id="bairro" type="text" placeholder="Bairro" oninput="bairroValidate()" onchange="bairroValidate()" value="<?php echo isset($usuario['bairro']) ? htmlspecialchars($usuario['bairro']) : ''; ?>">
                    <span class="span-required">O bairro deve ter no mínimo 3 caracteres.</span>
                </div> 
                <div class="confirmaSenha">
                    <label for="confirmaSenha">Confirme a senha:</label>
                    <input class="inputs" type="password" name="confirmaSenha" id="confirmaSenha" placeholder="Confirme sua senha" oninput="comparePassword()" value="<?php echo isset($usuario['confirmaSenha']) ? htmlspecialchars($usuario['confirmaSenha']) : ''; ?>">
                    <span class="span-required">Senhas devem ser compatíveis.</span>
                </div>
                <div class="sexo">
                    <label for="sexo">Sexo:</label>
                    <select required class="sexos" name="sexo" id="sexo" onchange="sexoValidate()">
                        <option hidden value="0">Selecione seu sexo:</option>
                        <option value="Masculino" <?php echo (isset($usuario['sexo']) && trim($usuario['sexo']) == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                        <option value="Feminino" <?php echo (isset($usuario['sexo']) && trim($usuario['sexo']) == 'Feminino') ? 'selected' : ''; ?>>Feminino</option>
                        <option value="Outro" <?php echo (isset($usuario['sexo']) && trim($usuario['sexo']) == 'Outro') ? 'selected' : ''; ?>>Outro</option>
                        </select>
                    <span class="span-required">Por favor, selecione um sexo.</span>
                </div>
            </div>
            <div class="rodape">
                <input type="submit" name="enviar" id="enviar" class="enviar" value="Enviar">
            </div>
        </form>
        <footer><div class="footer"><p>.</p></div></footer>
    </div>
</body>
</html>
