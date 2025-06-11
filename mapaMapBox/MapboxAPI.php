<?php

class MapboxAPI {
    private $accessToken;
    private $baseUrl = 'https://api.mapbox.com';
    
    public function __construct($accessToken) {
        $this->accessToken = $accessToken;
    }
    
    /**
     * Geocoding: Converte endereço em coordenadas
     */
    public function geocode($address) {
        $encodedAddress = urlencode($address);
        $url = "{$this->baseUrl}/geocoding/v5/mapbox.places/{$encodedAddress}.json?access_token={$this->accessToken}&limit=1";
        
        $response = $this->makeRequest($url);
        
        if ($response && isset($response['features']) && count($response['features']) > 0) {
            $feature = $response['features'][0];
            return [
                'longitude' => $feature['center'][0],
                'latitude' => $feature['center'][1],
                'place_name' => $feature['place_name']
            ];
        }
        
        return null;
    }
    
    /**
     * Directions: Calcula rota entre dois pontos
     */
    public function getDirections($origem, $destino, $profile = 'driving') {
        // Se origem e destino são endereços, geocodificar primeiro
        if (!is_array($origem)) {
            $origem = $this->geocode($origem);
        }
        if (!is_array($destino)) {
            $destino = $this->geocode($destino);
        }
        
        if (!$origem || !$destino) {
            return null;
        }
        
        $coordinates = "{$origem['longitude']},{$origem['latitude']};{$destino['longitude']},{$destino['latitude']}";
        $url = "{$this->baseUrl}/directions/v5/mapbox/{$profile}/{$coordinates}?geometries=geojson&access_token={$this->accessToken}";
        
        $response = $this->makeRequest($url);
        
        if ($response && isset($response['routes']) && count($response['routes']) > 0) {
            $route = $response['routes'][0];
            
            return [
                'distancia_km' => round($route['distance'] / 1000, 2),
                'tempo_segundos' => $route['duration'],
                'tempo_minutos' => round($route['duration'] / 60, 0),
                'tempo_formatado' => $this->formatTime($route['duration']),
                'geometria' => $route['geometry'],
                'origem' => $origem,
                'destino' => $destino,
                'rota_completa' => $route
            ];
        }
        
        return null;
    }
    
    /**
     * Busca postos de gasolina ao longo da rota
     */
    public function getPostosNaRota($rota, $raio = 5000) {
        if (!isset($rota['geometria']['coordinates'])) {
            return [];
        }
        
        $coordinates = $rota['geometria']['coordinates'];
        $postos = [];
        
        // Pega alguns pontos ao longo da rota para buscar postos
        $pontos = $this->sampleRoutePoints($coordinates, 5);
        
        foreach ($pontos as $ponto) {
            $longitude = $ponto[0];
            $latitude = $ponto[1];
            
            $url = "{$this->baseUrl}/geocoding/v5/mapbox.places/gas%20station.json?proximity={$longitude},{$latitude}&access_token={$this->accessToken}&limit=3";
            
            $response = $this->makeRequest($url);
            
            if ($response && isset($response['features'])) {
                foreach ($response['features'] as $feature) {
                    $posto = [
                        'nome' => $feature['text'],
                        'endereco' => $feature['place_name'],
                        'longitude' => $feature['center'][0],
                        'latitude' => $feature['center'][1],
                        'distancia_origem' => $this->calculateDistance(
                            $rota['origem']['latitude'], 
                            $rota['origem']['longitude'],
                            $feature['center'][1], 
                            $feature['center'][0]
                        )
                    ];
                    
                    // Evita duplicatas
                    $key = $posto['longitude'] . ',' . $posto['latitude'];
                    if (!isset($postos[$key])) {
                        $postos[$key] = $posto;
                    }
                }
            }
            
            // Pequena pausa para não sobrecarregar a API
            usleep(100000); // 0.1 segundo
        }
        
        // Ordena por distância da origem
        usort($postos, function($a, $b) {
            return $a['distancia_origem'] <=> $b['distancia_origem'];
        });
        
        return array_values($postos);
    }
    
    /**
     * Faz requisição HTTP
     */
    private function makeRequest($url) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, 'MapboxPHP/1.0');
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        
        if ($httpCode === 200 && $response) {
            return json_decode($response, true);
        }
        
        return null;
    }
    
    /**
     * Formata tempo em formato legível
     */
    private function formatTime($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}min";
        } else {
            return "{$minutes}min";
        }
    }
    
    /**
     * Calcula distância entre dois pontos (fórmula de Haversine)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return round($earthRadius * $c, 2);
    }
    
    /**
     * Pega pontos amostrais ao longo da rota
     */
    private function sampleRoutePoints($coordinates, $numPoints) {
        $total = count($coordinates);
        if ($total <= $numPoints) {
            return $coordinates;
        }
        
        $step = floor($total / $numPoints);
        $points = [];
        
        for ($i = 0; $i < $total; $i += $step) {
            $points[] = $coordinates[$i];
        }
        
        return $points;
    }
}

?>