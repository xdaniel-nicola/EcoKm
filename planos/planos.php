<?php
    session_start();
    $loggedInClass = isset($_SESSION['username']) ? 'logged-in' : '';
require_once "../php/conexao.php";

if (isset($_SESSION['username'])) {
    $pdo = conectaPDO();

    $sqlCpf = "SELECT cpf FROM usuario WHERE nome = :nome";
    $stmt = $pdo->prepare($sqlCpf);
    $stmt->bindValue(':nome', $_SESSION['username'], PDO::PARAM_STR);
    $stmt->execute();
    $cpf = $stmt->fetchColumn();

    if ($cpf) {
        $sqlPlano = "SELECT tipo FROM plano WHERE cpf = :cpf";
        $stmt = $pdo->prepare($sqlPlano);
        $stmt->bindValue(':cpf', $cpf, PDO::PARAM_STR);
        $stmt->execute();
        $planoAtual = $stmt->fetchColumn();
    } else {
        $planoAtual = null; 
    }}

$loggedInClass = isset($_SESSION['username']) ? 'logged-in' : '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planos EcoKm</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="plano.css">
    <script src="script.js" defer></script>
</head>
<body class="<?php echo $loggedInClass; ?>">
    <header>
        <div class="navbar">
            <div class="logo"><img src="../img/ecokmlogo3.png" width="135"></div>
            <ul class="links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="/planos/planos.php">Planos</a></li>
            </ul>
            <!-- Botão de alternância de tema -->
            <div class="theme-toggle">
            <input type="checkbox" id="toggle-switch" class="toggle-switch">
            <label for="toggle-switch" class="toggle-label">
            <span class="toggle-slider"></span>
            </label>
            </div>
            <?php if (isset($_SESSION['username'])): ?>
                <div class="username-container">
                <ul class="links">
                    <li><a class="usuario" href="../usuario/perfil.php">Bem vindo, <?php echo htmlspecialchars($_SESSION['username']);?></a></li>
                    <li><a href="login/logout.php" class="action-btn">Logout</a></li>
                </ul>
                </div>
            <?php else: ?>
                <a href="login/loginForm.php" class="action-btn">Login</a>
            <?php endif; ?>
            <div class="toggle-btn">
                <i class="fa-solid fa-bars"></i>
            </div>
            </div>
        </div>
    </header>

    <div class="cabecalho">
        <h1>Planos de Serviço</h1>
    </div>

    <div class="conteudo-principal">
    <div class="container-planos">
        <div class="plano_destaque">
            <h2>Plano Básico</h2>
            <p>Perfeito para indivíduos.</p>
            <ul>
                <li>Funcionalidade A</li>
                <li>Funcionalidade B</li>
                <li>Suporte básico</li>
            </ul>
            <p><strong>R$ 49,90/mês</strong></p>
            <button class="botao" 
            <?php echo ($planoAtual === 'Basico') ? 'disabled' : ''; ?>>
                <a href="assinarPlano.php?plano=Basico&preco=49.90"> 
                    <?php echo ($planoAtual === 'Basico') ? 'Ativo' : 'Escolher Plano'; ?>
                </a>
            </button>
        </div>

        <div class="plano_destaque">
            <h2>Plano Intermediário</h2>
            <p>Ideal para pequenos negócios.</p>
            <ul>
                <li>Funcionalidade A</li>
                <li>Funcionalidade B</li>
                <li>Suporte premium</li>
            </ul>
            <p><strong>R$ 79,90/mês</strong></p>
            <button class="botao destaque-botao" 
            <?php echo ($planoAtual === 'Intermediario') ? 'disabled' : ''; ?>>
                <a href="assinarPlano.php?plano=Intermediario&preco=79.90"> 
                    <?php echo ($planoAtual === 'Intermediario') ? 'Ativo' : 'Escolher Plano'; ?>
                </a>
            </button>
        </div>

        <div class="plano_destaque">
            <h2>Plano Premium</h2>
            <p>Para empresas e grandes equipes.</p>
            <ul>
                <li>Funcionalidade A</li>
                <li>Funcionalidade B</li>
                <li>Suporte completo 24/7</li>
            </ul>
            <p><strong>R$ 119,90/mês</strong></p>
            <button class="botao" 
            <?php echo ($planoAtual === 'Premium') ? 'disabled' : ''; ?>>
                <a href="assinarPlano.php?plano=Premium&preco=119.90"> 
                    <?php echo ($planoAtual === 'Premium') ? 'Ativo' : 'Escolher Plano'; ?>
                </a>
            </button>
        </div>
    </div>
</div>
    <button onclick="voltarAoTopo()" id="btnTopo"><img class="setaTopo" src="../img/seta-topo-removebg-preview.png" alt="setaTopo"></button>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
