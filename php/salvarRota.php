<?php
require_once "conexao.php";
require_once "../usuario/utils.php";
session_start();
$pdo = conectaPDO();

$tipoMotor = isset($_POST['tipoMotor']) ? $_POST['tipoMotor'] : null;
$tipoCombustivel = isset($_POST['tipoCombustivel']) ? $_POST['tipoCombustivel'] : null;
$distancia = isset($_POST['distancia']) ? $_POST['distancia'] : null; 

if (!$tipoMotor) {
    die("Erro: Tipo de motor não foi enviado ou é inválido.");
}

if (!$tipoCombustivel) {
    die("Erro: Tipo de combustível não foi enviado ou é inválido.");
}

$eficienciaMotores = [
    "15.3" => "1.0",
    "14.2" => 1.3,
    "13.6" => 1.4,
    "12.6" => 1.5,
    "11.6" => 1.6,
    "10.7" => 1.8,
    "9.8" => 2.0
];

$tiposCombustivel = [
    "10" => "Gasolina",
    "8" => "Etanol",
    "15" => "GNV"
];

$distancia = floatval($distancia);

if (isset($eficienciaMotores[(string)$tipoMotor])) {
    $_POST['tipoMotor'] = $eficienciaMotores[(string)$tipoMotor];
} else {
    die("Erro: Tipo de motor inválido.");
}

if (array_key_exists($tipoCombustivel, $tiposCombustivel)) {
    $_POST['tipoCombustivel'] = $tiposCombustivel[$tipoCombustivel];
} else {
    die("Erro: Tipo de combustível inválido.");
}

// echo "Modelo correspondente de motor: " . $_POST['tipoMotor'] . "<br>";
// echo "Tipo de combustível correspondente: " . $_POST['tipoCombustivel'] . "<br>";
// echo "Distância: " . $_POST['distancia'] . " km<br>";

// echo "<pre>";
// print_r($_POST);
// echo "</pre>";

try {
    $tipoMotor = $_POST['tipoMotor'];
    $tipoCombustivel = $_POST['tipoCombustivel'];
    $distancia = $_POST['distancia'];
    $precoCombustivel = $_POST['precoCombustivel'];
    $enderecoPartida = $_POST['partida'];
    $enderecoChegada = $_POST['chegada'];
    $tempoEstimado = $_POST['tempoEstimado'];
    $consumo = $_POST['consumo'];
    $custo = $_POST['custo'];
    
    $cpf = $_SESSION['cpf'];
    $login = $_SESSION['login'];

    $sql = "INSERT INTO viagem (tipoMotor, tipoCombustivel, distancia, precoCombustivel, partida, chegada, tempoEstimado, consumo, custo, cpf) 
            VALUES (:tipoMotor, :tipoCombustivel, :distancia, :precoCombustivel, :enderecoPartida, :enderecoChegada, :tempoEstimado, :consumo, :custo,  :cpf)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':tipoMotor', $tipoMotor);
    $stmt->bindParam(':tipoCombustivel', $tipoCombustivel);
    $stmt->bindParam(':distancia', $distancia);
    $stmt->bindParam(':precoCombustivel', $precoCombustivel);
    $stmt->bindParam(':enderecoPartida', $enderecoPartida);
    $stmt->bindParam(':enderecoChegada', $enderecoChegada);
    $stmt->bindParam(':tempoEstimado', $tempoEstimado);
    $stmt->bindParam(':consumo', $consumo);
    $stmt->bindParam(':custo', $custo);
    $stmt->bindParam(':cpf', $cpf);

    if ($stmt->execute()) {
        echo "sucesso";
        registraLog($pdo, $_SESSION['login'], "Salvou rota no perfil.");
        // header('Location: ../index.php#calculadora'); 
        
    } else {
        echo "Erro ao salvar a rota.";
        echo "erro";
    }
} catch (PDOException $e) {
    echo "Erro no banco de dados: " . $e->getMessage();
}
