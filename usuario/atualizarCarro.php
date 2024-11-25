<?php
session_start();
require_once "../php/conexao.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../login/loginForm.php");
    exit();
}

$pdo = conectaPDO();
if ($pdo) {
    $sql = "SELECT cpf FROM usuario WHERE nome = :nome";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':nome', $_SESSION['username'], PDO::PARAM_STR);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "Erro: Usuário não encontrado no banco de dados.";
        exit();
    }

    $cpf = htmlspecialchars($usuario['cpf']); 
} else {
    echo "Erro na conexão com o banco de dados.";
    exit();
}
    $id_carro = $_GET['id'];
    $sql = "SELECT * FROM carro WHERE id_carro=$id_carro";

    $resultado = $pdo->prepare($sql);
    $resultado->execute();
    $row = $resultado->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">   
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-Ua-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/atualizarCarro.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6-beta.24/jquery.inputmask.min.js"></script>
    <script src="js/atualizarCarro.js" defer></script>
    <title>Cadastre seu carro</title>
</head>
<body>
    <div class="topoPag">
        <p class="txtTopo"></p>
    </div>
    <div class="telaCadastro">
        <form name="form" id="form" class="card" action="../php/updateCarro.php" method="post">
            
            <div class="homePage">
                <a href="perfil.php" class="voltarHome"> Voltar</a>
                <button type="button" id="toggleTheme" class="toggle-button">
                    <span id="themeIcon">🌙</span>
                </button>
            </div>
            <div class="card-header">
                <h2 name="titulo" id="titulo">Cadastre seu carro</h2>
            </div>
            <div class="conteudo">
                <div class="modelo">
                    <label for="modelo">Modelo:</label>
                    <input class="inputs" type="text" name="modelo" id="modelo" placeholder="Digite o modelo do carro" oninput="modeloValidate()" value="<?php if(isset($row['modelo'])) {echo $row['modelo'];} ?>">
                    <span class="span-required">modelo deve ter no mínimo 15 caracteres.</span>
                </div>
                <div class="marca">
                    <label for="marca">Marca:</label>
                    <input class="inputs" autocomplete="on" type="text" name="marca" id="marca" placeholder="Digite a marca do carro" oninput="marcaValidate()"value="<?php if(isset($row['marca'])) {echo $row['marca'];} ?>">
                    <span class="span-required">Digite uma marca válida.</span>
                </div>
                <div class="motor">
                    <label for="motor">Motor:</label>
                    <select required class="motores" name="motor" id="motor" onchange="motorValidate()">
                        <option hidden value="0">Selecione o motor:</option>
                        <option value="1.0" <?php echo (isset($row['motor']) && $row['motor'] == '1.0') ? 'selected' : ''; ?>>Motor 1.0</option>
                        <option value="1.3" <?php echo (isset($row['motor']) && $row['motor'] == '1.3') ? 'selected' : ''; ?>>Motor 1.3</option>
                        <option value="1.4" <?php echo (isset($row['motor']) && $row['motor'] == '1.4') ? 'selected' : ''; ?>>Motor 1.4</option>
                        <option value="1.5" <?php echo (isset($row['motor']) && $row['motor'] == '1.5') ? 'selected' : ''; ?>>Motor 1.5</option>
                        <option value="1.6" <?php echo (isset($row['motor']) && $row['motor'] == '1.6') ? 'selected' : ''; ?>>Motor 1.6</option>
                        <option value="1.8" <?php echo (isset($row['motor']) && $row['motor'] == '1.8') ? 'selected' : ''; ?>>Motor 1.8</option>
                        <option value="2.0" <?php echo (isset($row['motor']) && $row['motor'] == '2.0') ? 'selected' : ''; ?>>Motor 2.0</option>
                    </select>

                    <span class="span-required">Por favor, selecione um motor.</span>
                </div>
                <div class="cpf">
                    <label for="cpf">CPF:</label>
                    <input class="inputs" type="text" name="cpf_display" id="cpf_display" value="<?php echo $cpf; ?>" disabled>
                    <input type="hidden" name="cpf" value="<?php echo $cpf; ?>">
                </div>
                <div class="idCarro">
                    <input type="hidden" name="id_carro" value="<?php echo $id_carro; ?>">    
                </div>

            </div>
            <div class="rodape">
                <input type="submit" name="enviar" id="enviar" class="enviar" value="Enviar">
            </div>
        </form>
    </div>
</body>
</html>
