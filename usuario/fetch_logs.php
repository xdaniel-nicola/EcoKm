<?php
require "../php/conexao.php";
header('Content-Type: application/json');
$pdo = conectaPDO();
try {

    $busca = isset($_POST['busca']) ? trim($_POST['busca']) : '';
// Buscar dados da tabela de log
if ($busca !== '') {
    $sql ="SELECT * FROM logUsers WHERE usuario LIKE :busca ORDER BY idLog DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':busca', '%' . $busca . '%');
} else {
    $sql = "SELECT * FROM logUsers ORDER BY idLog DESC";
    $stmt = $pdo->prepare($sql);
}
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retornar os dados em JSON
    // header('Content-Type: application/json');
    echo json_encode($logs);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

?>
