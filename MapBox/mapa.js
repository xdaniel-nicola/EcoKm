// IMPORTANTE: Substitua pelo seu token do Mapbox
const MAPBOX_TOKEN = 'pk.eyJ1IjoieGRhbmllbCIsImEiOiJjbWJ6ODc5bGwxeGUwMmtvYXNnMWRmYnB5In0.REhR9CKwqKO6D0YsP8_FNw';

// Variáveis globais para o mapa
let map = null;
let originCoords = null;
let destinationCoords = null;
let routeData = null;

// Aguardar o DOM carregar completamente
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se o Mapbox GL JS foi carregado
    if (typeof mapboxgl === 'undefined') {
        console.error('Mapbox GL JS não foi carregado');
        return;
    }
    
    // Configurar token e inicializar mapa
    mapboxgl.accessToken = MAPBOX_TOKEN;
    initializeMapbox();
});

function initializeMapbox() {
    // Inicializar o mapa
    map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v12',
        center: [-43.2096, -22.9035], // Rio de Janeiro como centro inicial
        zoom: 10
    });

    // Aguardar o mapa carregar
    map.on('load', function() {
        console.log('Mapa carregado com sucesso!');
        
        // Configurar autocomplete após o mapa estar pronto
        setupMapboxAutocomplete('origin');
        setupMapboxAutocomplete('destination');
        
        // Adicionar controles de navegação
        map.addControl(new mapboxgl.NavigationControl());
        map.addControl(new mapboxgl.FullscreenControl());
    });
}

// Função para configurar autocomplete do Mapbox
function setupMapboxAutocomplete(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    let timeout;
    let suggestionsDiv = document.getElementById(inputId + 'Suggestions');
    
    // Se não existe div de sugestões, criar uma
    if (!suggestionsDiv) {
        suggestionsDiv = document.createElement('div');
        suggestionsDiv.id = inputId + 'Suggestions';
        suggestionsDiv.className = 'mapbox-suggestions-dropdown';
        suggestionsDiv.style.cssText = `
            position: absolute;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            width: 100%;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        `;
        input.parentNode.style.position = 'relative';
        input.parentNode.appendChild(suggestionsDiv);
    }

    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value.trim();
        
        if (query.length < 3) {
            suggestionsDiv.style.display = 'none';
            return;
        }

        timeout = setTimeout(async () => {
            try {
                const response = await fetch(
                    `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(query)}.json?access_token=${MAPBOX_TOKEN}&country=BR&limit=5`
                );
                const data = await response.json();
                
                displayMapboxSuggestions(data.features, suggestionsDiv, input, inputId);
            } catch (error) {
                console.error('Erro na busca do Mapbox:', error);
            }
        }, 300);
    });

    // Fechar sugestões ao clicar fora
    document.addEventListener('click', function(e) {
        if (!e.target.closest(input.parentNode)) {
            suggestionsDiv.style.display = 'none';
        }
    });
}

// Função para exibir sugestões do Mapbox
function displayMapboxSuggestions(features, suggestionsDiv, input, inputType) {
    if (!features || features.length === 0) {
        suggestionsDiv.style.display = 'none';
        return;
    }

    suggestionsDiv.innerHTML = '';
    features.forEach(feature => {
        const div = document.createElement('div');
        div.className = 'mapbox-suggestion-item';
        div.style.cssText = `
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        `;
        div.textContent = feature.place_name;
        
        div.addEventListener('mouseover', () => {
            div.style.backgroundColor = '#f0f0f0';
        });
        
        div.addEventListener('mouseout', () => {
            div.style.backgroundColor = 'transparent';
        });
        
        div.addEventListener('click', () => {
            input.value = feature.place_name;
            suggestionsDiv.style.display = 'none';
            
            // Armazenar coordenadas
            if (inputType === 'origin') {
                originCoords = feature.center;
            } else {
                destinationCoords = feature.center;
            }
        });
        suggestionsDiv.appendChild(div);
    });
    
    suggestionsDiv.style.display = 'block';
}

// Função principal para calcular rota (NOME IGUAL AO CHAMADO NO HTML)
async function calculateRoute() {
    // Verificar se o mapa foi inicializado
    if (!map) {
        alert('Mapa ainda não foi carregado. Aguarde e tente novamente.');
        return;
    }

    const originInput = document.getElementById('origin').value.trim();
    const destinationInput = document.getElementById('destination').value.trim();
    
    if (!originInput || !destinationInput) {
        alert('Por favor, preencha os endereços de partida e chegada.');
        return;
    }

    // Desabilitar botão durante o cálculo
    const button = document.querySelector('.calculateBtn');
    if (button) {
        const originalText = button.textContent;
        button.textContent = 'Calculando...';
        button.disabled = true;
        
        try {
            // Geocodificar endereços se não temos coordenadas
            if (!originCoords) {
                originCoords = await geocodeAddress(originInput);
            }
            if (!destinationCoords) {
                destinationCoords = await geocodeAddress(destinationInput);
            }

            // Calcular rota
            await getDirections(originCoords, destinationCoords);
            
            // Buscar postos de gasolina
            await findGasStations();
            
            // Preencher automaticamente o campo de distância
            if (routeData) {
                const distanceKm = (routeData.distance / 1000).toFixed(1);
                const distanceInput = document.getElementById('distancia');
                if (distanceInput) {
                    distanceInput.value = distanceKm;
                }
            }
            
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao calcular rota. Verifique os endereços e tente novamente.');
        } finally {
            // Reabilitar botão
            button.textContent = originalText;
            button.disabled = false;
        }
    }
}

// Função para geocodificar endereço (NOME SIMPLIFICADO)
async function geocodeAddress(address) {
    const response = await fetch(
        `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(address)}.json?access_token=${MAPBOX_TOKEN}&country=BR&limit=1`
    );
    const data = await response.json();
    
    if (!data.features || data.features.length === 0) {
        throw new Error(`Endereço não encontrado: ${address}`);
    }
    
    return data.features[0].center;
}

// Função para obter direções (NOME SIMPLIFICADO)
async function getDirections(start, end) {
    const response = await fetch(
        `https://api.mapbox.com/directions/v5/mapbox/driving/${start[0]},${start[1]};${end[0]},${end[1]}?geometries=geojson&access_token=${MAPBOX_TOKEN}`
    );
    
    const data = await response.json();
    
    if (!data.routes || data.routes.length === 0) {
        throw new Error('Nenhuma rota encontrada');
    }

    routeData = data.routes[0];
    displayRoute(routeData);
    
    // Mostrar informações da rota
    displayRouteInfo(routeData);
}

// Função para exibir informações da rota
function displayRouteInfo(route) {
    const distance = (route.distance / 1000).toFixed(1);
    const duration = Math.round(route.duration / 60);
    
    // Criar ou atualizar div de resultado
    let resultDiv = document.getElementById('result');
    if (!resultDiv) {
        resultDiv = document.createElement('div');
        resultDiv.id = 'result';
        resultDiv.style.cssText = `
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            font-family: Arial, sans-serif;
        `;
        
        // Inserir após o mapa
        const mapContainer = document.getElementById('map');
        if (mapContainer && mapContainer.parentNode) {
            mapContainer.parentNode.insertBefore(resultDiv, mapContainer.nextSibling);
        }
    }
    
    resultDiv.innerHTML = `
        <h3 hidden style="margin: 0 0 10px 0; color: #495057;">📍 Informações da Rota</h3>
        <p hidden style="margin: 5px 0;"><strong>Distância:</strong> ${distance} km</p>
        <p style="margin: 5px 0;"><strong>Tempo estimado:</strong> ${duration} minutos</p>
        <p hidden style="margin: 5px 0; color: #28a745;"><strong>Status:</strong> Rota calculada com sucesso!</p>
    `;
    const durationInput = document.getElementById('duration');
    if (durationInput) {
        durationInput.value = duration; // Preencher automaticamente o campo de duração
    }
}

// Função para exibir rota no mapa (NOME SIMPLIFICADO)
function displayRoute(route) {
    // Verificar se o mapa existe e está carregado
    if (!map || !map.getSource) {
        console.error('Mapa não está disponível');
        return;
    }

    // Limpar camadas anteriores
    if (map.getSource('route')) {
        map.removeLayer('route');
        map.removeSource('route');
    }
    
    // Remover marcadores anteriores
    document.querySelectorAll('.mapboxgl-marker').forEach(marker => marker.remove());

    // Adicionar rota
    map.addSource('route', {
        type: 'geojson',
        data: {
            type: 'Feature',
            properties: {},
            geometry: route.geometry
        }
    });

    map.addLayer({
        id: 'route',
        type: 'line',
        source: 'route',
        layout: {
            'line-join': 'round',
            'line-cap': 'round'
        },
        paint: {
            'line-color': '#667eea',
            'line-width': 6
        }
    });

    // Adicionar marcadores
    new mapboxgl.Marker({ color: '#48bb78' })
        .setLngLat(originCoords)
        .setPopup(new mapboxgl.Popup().setText('Partida'))
        .addTo(map);

    new mapboxgl.Marker({ color: '#f56565' })
        .setLngLat(destinationCoords)
        .setPopup(new mapboxgl.Popup().setText('Chegada'))
        .addTo(map);

    // Ajustar visualização para mostrar toda a rota
    const bounds = new mapboxgl.LngLatBounds();
    route.geometry.coordinates.forEach(coord => bounds.extend(coord));
    map.fitBounds(bounds, { padding: 50 });
}

// Função para encontrar postos de gasolina (NOME SIMPLIFICADO)
async function findGasStations() {
    if (!routeData || !map) return;

    const coordinates = routeData.geometry.coordinates;
    const samplePoints = [];
    
    // Pegar alguns pontos ao longo da rota
    for (let i = 0; i < coordinates.length; i += Math.floor(coordinates.length / 5)) {
        samplePoints.push(coordinates[i]);
    }

    const gasStations = [];
    
    for (const point of samplePoints) {
        try {
            const response = await fetch(
                `https://api.mapbox.com/geocoding/v5/mapbox.places/gas%20station.json?proximity=${point[0]},${point[1]}&access_token=${MAPBOX_TOKEN}&country=BR&limit=3`
            );
            const data = await response.json();
            
            if (data.features) {
                gasStations.push(...data.features);
            }
        } catch (error) {
            console.error('Erro ao buscar postos:', error);
        }
    }

    // Remover duplicatas
    const uniqueStations = [];
    gasStations.forEach(station => {
        const isDuplicate = uniqueStations.some(existing => {
            const distance = calculateDistance(
                station.center[0], station.center[1],
                existing.center[0], existing.center[1]
            );
            return distance < 0.5;
        });
        
        if (!isDuplicate) {
            uniqueStations.push(station);
        }
    });

    displayGasStations(uniqueStations.slice(0, 10));
}

// Função para exibir postos de gasolina (NOME SIMPLIFICADO)
function displayGasStations(stations) {
    if (!map) return;

    stations.forEach(station => {
        new mapboxgl.Marker({ 
            color: '#ffd700',
            scale: 0.8
        })
        .setLngLat(station.center)
        .setPopup(new mapboxgl.Popup().setHTML(`
            <div style="font-size: 14px;">
                <strong>⛽ Posto de Gasolina</strong><br>
                ${station.place_name}
            </div>
        `))
        .addTo(map);
    });
}

// Função utilitária para calcular distância entre dois pontos (NOME SIMPLIFICADO)
function calculateDistance(lon1, lat1, lon2, lat2) {
    const R = 6371; // Raio da Terra em km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// Função para obter dados da rota atual (para usar nas suas funções existentes)
function getRouteData() {
    if (!routeData) return null;
    
    return {
        distance: routeData.distance / 1000, // em km
        duration: routeData.duration / 60, // em minutos
        geometry: routeData.geometry,
        originCoords: originCoords,
        destinationCoords: destinationCoords
    };
}

// ===============================================
// MANTER AS FUNÇÕES EXISTENTES DO PHP INTACTAS
// ===============================================
// As funções abaixo são mantidas para compatibilidade com as funcionalidades específicas do PHP
// Como: calcularCombustivel(), salvaRota(), etc.

// Função para geocodificar endereço (MANTIDA PARA COMPATIBILIDADE)
async function geocodeMapboxAddress(address) {
    return await geocodeAddress(address);
}

// Função para obter direções (MANTIDA PARA COMPATIBILIDADE)
async function getMapboxDirections(start, end) {
    return await getDirections(start, end);
}

// Função para exibir rota no mapa (MANTIDA PARA COMPATIBILIDADE)
function displayMapboxRoute(route) {
    return displayRoute(route);
}

// Função para encontrar postos de gasolina (MANTIDA PARA COMPATIBILIDADE)
async function findMapboxGasStations() {
    return await findGasStations();
}

// Função para exibir postos de gasolina (MANTIDA PARA COMPATIBILIDADE)
function displayMapboxGasStations(stations) {
    return displayGasStations(stations);
}

// Função utilitária para calcular distância (MANTIDA PARA COMPATIBILIDADE)
function calculateMapboxDistance(lon1, lat1, lon2, lat2) {
    return calculateDistance(lon1, lat1, lon2, lat2);
}

// Função para obter dados da rota (MANTIDA PARA COMPATIBILIDADE)
function getMapboxRouteData() {
    return getRouteData();
}