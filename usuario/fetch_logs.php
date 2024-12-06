<?php
require "../php/conexao.php";
$pdo = conectaPDO();

// Buscar dados da tabela de log
try {
    $sql = "SELECT * FROM logUsers ORDER BY idLog DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retornar os dados em JSON
    header('Content-Type: application/json');
    echo json_encode($logs);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

// function registraLog($pdo, $usuario, $acao) {
//    try {
//     $sql = "INSERT INTO logUsers (usuario, acao) VALUES (:usuario, :acao)";
//     $stmt = $pdo->prepare($sql);
//     $stmt->bindParam(':usuario', $usuario);
//     $stmt->bindParam(':acao', $acao);
//     $stmt->execute();
//    } catch (PDOException $e) {
//     error_log("Erro ao registrar log: " . $e->getMessage());
//    }
// }
?>
