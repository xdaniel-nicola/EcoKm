<?php
// Teste simples para verificar se tudo está funcionando
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== TESTE MAPBOX API ===\n\n";

// Verificar se a classe existe
if (!file_exists('MapboxAPI.php')) {
    die("Arquivo MapboxAPI.php não encontrado!\n");
}

require_once 'MapboxAPI.php';

// Teste 1: Verificar se a classe pode ser instanciada
try {
    $mapbox = new MapboxAPI('test_token');
    echo "✓ Classe MapboxAPI criada com sucesso\n";
} catch (Exception $e) {
    die("✗ Erro ao criar classe: " . $e->getMessage() . "\n");
}

// Teste 2: Verificar se CURL está disponível
if (!function_exists('curl_init')) {
    die("✗ CURL não está disponível no PHP\n");
}
echo "✓ CURL está disponível\n";

// Teste 3: Verificar se consegue fazer uma requisição simples
try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://httpbin.org/json');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && $response) {
        echo "✓ Requisições HTTP funcionando\n";
    } else {
        echo "✗ Problema com requisições HTTP. Código: $httpCode\n";
    }
} catch (Exception $e) {
    echo "✗ Erro na requisição de teste: " . $e->getMessage() . "\n";
}

// Teste 4: Verificar variáveis de ambiente
echo "\n=== INFORMAÇÕES DO SERVIDOR ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "User Agent: " . $_SERVER['HTTP_USER_AGENT'] ?? 'N/A' . "\n";
echo "Request Method: " . $_SERVER['REQUEST_METHOD'] ?? 'N/A' . "\n";

// Teste 5: Testar JSON
$testData = ['test' => 'data', 'number' => 123];
$json = json_encode($testData);
echo "JSON encode test: " . ($json ? "✓ OK" : "✗ ERRO") . "\n";

$decoded = json_decode($json, true);
echo "JSON decode test: " . ($decoded && $decoded['test'] === 'data' ? "✓ OK" : "✗ ERRO") . "\n";

$response = curl_exec($ch);

if ($response === false) {
    echo "✗ Erro com CURL: " . curl_error($ch) . "\n";
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "\n=== TESTE CONCLUÍDO ===\n";
echo "Se todos os testes passaram, o problema pode ser:\n";
echo "1. Token do Mapbox não configurado\n";
echo "2. Problema de CORS\n";
echo "3. Arquivo MapboxAPI.php com erro de sintaxe\n";
echo "\nPróximo passo: Configure seu token e teste novamente.\n";
?>