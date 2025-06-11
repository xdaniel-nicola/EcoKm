<?php
// Habilitar relatório de erros para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Log para debug
error_log("Iniciando processar_rota.php");

// Incluir a classe MapboxAPI
require_once 'MapboxAPI.php';

try {
    // Verificar se é POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método não permitido. Use POST.');
    }
    
    // Pegar dados do POST
    $inputRaw = file_get_contents('php://input');
    error_log("Dados recebidos: " . $inputRaw);
    
    if (empty($inputRaw)) {
        throw new Exception('Nenhum dado recebido');
    }
    
    $input = json_decode($inputRaw, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSON inválido: ' . json_last_error_msg());
    }
    
    error_log("Dados decodificados: " . print_r($input, true));
    
    if (!isset($input['origem']) || !isset($input['destino'])) {
        throw new Exception('Origem e destino são obrigatórios');
    }
    
    $origem = $input['origem'];
    $destino = $input['destino'];
    
    // IMPORTANTE: Substitua pelo seu token do Mapbox
    $accessToken = 'pk.eyJ1IjoieGRhbmllbCIsImEiOiJjbTMzcXZjZ2oxcnN6Mm1wczJudDl5emJ3In0.b3w2oKSjzT0vH7LEQ6oY_Q';
    
    if ($accessToken === 'pk.eyJ1IjoieGRhbmllbCIsImEiOiJjbTMzcXZjZ2oxcnN6Mm1wczJudDl5emJ3In0.b3w2oKSjzT0vH7LEQ6oY_Q') {
        throw new Exception('Configure seu token do Mapbox primeiro! Acesse: https://account.mapbox.com/access-tokens/');
    }
    
    error_log("Criando instância MapboxAPI");
    
    // Criar instância da API
    $mapbox = new MapboxAPI($accessToken);
    
    error_log("Calculando rota de: {$origem} para: {$destino}");
    
    // Calcular rota
    $rota = $mapbox->getDirections($origem, $destino);
    
    if (!$rota) {
        throw new Exception('Não foi possível calcular a rota entre os pontos especificados. Verifique se os endereços estão corretos.');
    }
    
    error_log("Rota calculada com sucesso. Distância: " . $rota['distancia_km'] . "km");
    
    // Buscar postos na rota (opcional, pode comentar se estiver dando problema)
    $postos = [];
    try {
        error_log("Buscando postos na rota...");
        $postos = $mapbox->getPostosNaRota($rota);
        error_log("Encontrados " . count($postos) . " postos");
    } catch (Exception $e) {
        error_log("Erro ao buscar postos: " . $e->getMessage());
        // Continua sem os postos
    }
    
    // Retornar resultado
    $response = [
        'success' => true,
        'rota' => $rota,
        'postos' => $postos,
        'resumo' => [
            'origem' => $rota['origem']['place_name'],
            'destino' => $rota['destino']['place_name'],
            'distancia_km' => $rota['distancia_km'],
            'tempo_estimado' => $rota['tempo_formatado'],
            'total_postos' => count($postos)
        ]
    ];
    
    error_log("Enviando resposta de sucesso");
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    error_log("Erro: " . $e->getMessage());
    http_response_code(400);
    
    $errorResponse = [
        'success' => false,
        'error' => $e->getMessage(),
        'debug_info' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ];
    
    echo json_encode($errorResponse, JSON_UNESCAPED_UNICODE);
}
?>