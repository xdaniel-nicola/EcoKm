<?php
function registraLog($pdo, $usuario, $acao) {
   try {
    $sql = "INSERT INTO logUsers (usuario, acao) VALUES (:usuario, :acao)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':acao', $acao);
    $stmt->execute();
   } catch (PDOException $e) {
    error_log("Erro ao registrar log: " . $e->getMessage());
   }
}
?>