<?php
require_once "../php/conexao.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$loggedInClass = isset($_SESSION['username']) ? 'logged-in' : '';

if (!isset($_SESSION['username'])) {
    header("Location: ../login/loginForm.php"); 
}

$planoEscolhido = $_GET['plano'] ?? ''; 
$precoPlano = $_GET['preco'] ?? '';

if (!in_array($planoEscolhido, ['Basico', 'Intermediario', 'Premium']) || !$precoPlano) {
    echo "Plano ou preço inválido!";
    exit();
}

$pdo = conectaPDO();
$sqlCpf = "SELECT cpf FROM usuario WHERE nome = :nome";
$stmt = $pdo->prepare($sqlCpf);
$stmt->bindValue(':nome', $_SESSION['username'], PDO::PARAM_STR);
$stmt->execute();
$cpf = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assinar Plano</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="assinarPlano.css">
    <script src="assinarPlano.js" defer></script>
</head>
<body>
<body class="<?php echo $loggedInClass; ?>">
    <header>
        <div class="navbar">
            <div class="logo"><img src="../img/ecokmlogo3.png" width="135"></div>
            <ul class="links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="planos.php">Planos</a></li>
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
                    <li><a href="../login/logout.php" class="action-btn">Logout</a></li>
                </ul>
                </div>
            <?php else: ?>
                <a href="../login/loginForm.php" class="action-btn">Login</a>
            <?php endif; ?>
            <div class="toggle-btn">
                <i class="fa-solid fa-bars"></i>
            </div>
            </div>
        </div>
    </header>
    <div class="container">
        <h1>Assinar Plano: <?php echo htmlspecialchars($planoEscolhido); ?></h1>
        <p>Preço: R$ <?php echo number_format($precoPlano, 2, ',', '.'); ?>/mês</p>
        
        <form action="confirmarPlano.php" id="form" method="POST">
            <div class="form-group">
                <label for="numeroCartao">Número do Cartão</label>
                <input type="text" class="form-control" id="numeroCartao" name="numeroCartao" oninput="numCartaoValidate()">
                <span class="span-required">Número inválido.</span>
            </div>
            <div class="form-group">
                <label for="vencimento">Data de Vencimento</label>
                <input type="text" class="form-control" id="vencimento" name="vencimento" oninput="validadeValidate()">
                <span class="span-required">Digite uma data válida.</span>
            </div>
            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" class="form-control" id="cvv" name="cvv" oninput="CVVValidate()">
                <span class="span-required">CVV deve ter 3 dígitos.</span>
            </div>
            <input type="hidden" name="plano" value="<?php echo htmlspecialchars($planoEscolhido); ?>">
            <input type="hidden" name="cpf" value="<?php echo htmlspecialchars($cpf); ?>">
            <input type="hidden" name="preco" value="<?php echo htmlspecialchars($precoPlano); ?>">

            <button type="submit" class="alterBtn">Assinar</button>
        </form>
    </div>
</body>
</html>
