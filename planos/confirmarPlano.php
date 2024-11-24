<?php
session_start();
require_once "../php/conexao.php";

if (!isset($_SESSION['username'])) {
    header("Location: login/loginForm.php"); 
    exit();
}

$planoEscolhido = $_POST['plano'] ?? '';
$precoPlano = $_POST['preco'] ?? '';
$cpf = $_POST['cpf'] ?? '';

if (!in_array($planoEscolhido, ['Basico', 'Intermediario', 'Premium']) || !$precoPlano) {
    echo "Plano ou preço inválido!";
    exit();
}

$pdo = conectaPDO();

$sql = "SELECT id_plano FROM plano WHERE cpf = :cpf";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':cpf', $cpf, PDO::PARAM_STR);
$stmt->execute();
$planoExistente = $stmt->fetchColumn();

if ($planoExistente) {
    $sqlUpdate = "UPDATE plano SET tipo = :plano, preco = :preco WHERE cpf = :cpf";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->bindValue(':plano', $planoEscolhido, PDO::PARAM_STR);
    $stmtUpdate->bindValue(':preco', $precoPlano, PDO::PARAM_STR);
    $stmtUpdate->bindValue(':cpf', $cpf, PDO::PARAM_STR);
    $stmtUpdate->execute();
    echo "<p>Plano $planoEscolhido atualizado com sucesso!</p>";
} else {
    $sqlInsert = "INSERT INTO plano (tipo, preco, cpf) VALUES (:plano, :preco, :cpf)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->bindValue(':plano', $planoEscolhido, PDO::PARAM_STR);
    $stmtInsert->bindValue(':preco', $precoPlano, PDO::PARAM_STR);
    $stmtInsert->bindValue(':cpf', $cpf, PDO::PARAM_STR);
    $stmtInsert->execute();
    echo "<p>Plano $planoEscolhido assinado com sucesso!</p>";
}

echo "<a href='planos.php'>Voltar para os planos</a>";
?>
