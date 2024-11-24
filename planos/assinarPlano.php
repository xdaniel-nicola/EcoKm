<?php
session_start();
require_once "../php/conexao.php";

if (!isset($_SESSION['username'])) {
    header("Location: login/loginForm.php"); 
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
    <script src="script.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Assinar Plano: <?php echo htmlspecialchars($planoEscolhido); ?></h1>
        <p>Preço: R$ <?php echo number_format($precoPlano, 2, ',', '.'); ?>/mês</p>
        
        <form action="confirmarPlano.php" method="POST">
            <div class="form-group">
                <label for="numeroCartao">Número do Cartão</label>
                <input type="text" class="form-control" id="numeroCartao" name="numeroCartao" required>
            </div>
            <div class="form-group">
                <label for="vencimento">Data de Vencimento</label>
                <input type="text" class="form-control" id="vencimento" name="vencimento" required>
            </div>
            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" class="form-control" id="cvv" name="cvv" required>
            </div>
            <input type="hidden" name="plano" value="<?php echo htmlspecialchars($planoEscolhido); ?>">
            <input type="hidden" name="cpf" value="<?php echo htmlspecialchars($cpf); ?>">
            <input type="hidden" name="preco" value="<?php echo htmlspecialchars($precoPlano); ?>">

            <button type="submit" class="btn btn-primary">Assinar</button>
        </form>
    </div>
</body>
</html>
