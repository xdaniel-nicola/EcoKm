// Configuração do token de acesso do Mapbox
mapboxgl.accessToken = 'pk.eyJ1IjoieGRhbmllbCIsImEiOiJjbTMzcXZjZ2oxcnN6Mm1wczJudDl5emJ3In0.b3w2oKSjzT0vH7LEQ6oY_Q';

// Seleciona os elementos do DOM
const startInput = document.getElementById('start-input');
const endInput = document.getElementById('end-input');
const startSuggestionsContainer = document.getElementById('start-suggestions');
const endSuggestionsContainer = document.getElementById('end-suggestions');
const routeButton = document.getElementById('route-button');

// Inicializa o mapa
const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [-43.556856,-22.905194], 
    zoom: 12 
});

// Adiciona eventos de input para os campos de entrada
startInput.addEventListener('input', () => {
    handleInput(startInput.value, startSuggestionsContainer);
});

endInput.addEventListener('input', () => {
    handleInput(endInput.value, endSuggestionsContainer);
});

// Função para desaparecer e aparecer o erro
const erroElement = document.getElementById('erro');
function setError(erro) {
    erro.style.display = 'block';
}

function removeError(erro) {
    erro.style.display = 'none';
}

// Função para lidar com entradas de texto e buscar sugestões
function handleInput(query, suggestionsContainer) {
    if (query.length > 2) { // Apenas busca se houver mais de 2 caracteres
        fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(query)}.json?access_token=${mapboxgl.accessToken}`)
            .then(response => response.json())
            .then(data => showSuggestions(data.features, suggestionsContainer));
    } else {
        suggestionsContainer.innerHTML = ''; // Limpa as sugestões
        suggestionsContainer.style.display = 'none'; // Esconde o container
    }
}

// Função para exibir sugestões de endereços
function showSuggestions(suggestions, suggestionsContainer) {
    suggestionsContainer.innerHTML = ''; // Limpa sugestões anteriores

    if (suggestions.length === 0) {
        suggestionsContainer.style.display = 'none'; // Esconde se não houver sugestões
        return;
    }

    suggestions.forEach(suggestion => {
        const div = document.createElement('div');
        div.className = 'suggestion';
        div.textContent = suggestion.place_name;

        // Evento de clique na sugestão
        div.addEventListener('click', () => {
            if (suggestionsContainer === startSuggestionsContainer) {
                startInput.value = suggestion.place_name; // Preenche o campo de partida
            } else {
                endInput.value = suggestion.place_name; // Preenche o campo de destino
            }
            suggestionsContainer.innerHTML = ''; // Limpa as sugestões
            suggestionsContainer.style.display = 'none'; // Esconde o container
        });

        suggestionsContainer.appendChild(div); // Adiciona a sugestão ao container
    });

    suggestionsContainer.style.display = 'block'; // Mostra o container de sugestões
}

// Evento para traçar a rota
routeButton.addEventListener('click', () => {
    const start = startInput.value;
    const end = endInput.value;

    if (start && end) {
        removeError(erroElement);
        // Geocodificação para o ponto de partida
        fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(start)}.json?access_token=${mapboxgl.accessToken}`)
            .then(response => {
                if (!response.ok) throw new Error("Erro ao geocodificar o ponto de partida");
                return response.json();
            })
            .then(data => {
                const startCoords = data.features[0].geometry.coordinates;

                // Geocodificação para o destino
                return fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(end)}.json?access_token=${mapboxgl.accessToken}`)
                    .then(response => {
                        if (!response.ok) throw new Error("Erro ao geocodificar o destino");
                        return response.json();
                    })
                    .then(data => {
                        const endCoords = data.features[0].geometry.coordinates;

                        // Traçar a rota
                        return fetch(`https://api.mapbox.com/directions/v5/mapbox/driving/${startCoords[0]},${startCoords[1]};${endCoords[0]},${endCoords[1]}?geometries=geojson&access_token=${mapboxgl.accessToken}`);
                    });
            })
            .then(response => {
                if (!response.ok) throw new Error("Erro ao obter direções");
                return response.json();
            })
            .then(data => {
                if (!data.routes || data.routes.length === 0) throw new Error("Nenhuma rota encontrada");

                const route = data.routes[0].geometry.coordinates;
                const geojson = {
                    type: 'FeatureCollection',
                    features: [{
                        type: 'Feature',
                        geometry: {
                            type: 'LineString',
                            coordinates: route
                        }
                    }]
                };

                // Adiciona a rota ao mapa
                if (map.getSource('route')) {
                    map.getSource('route').setData(geojson); // Atualiza a rota se já existir
                } else {
                    map.addSource('route', {
                        type: 'geojson',
                        data: geojson // Adiciona nova rota ao mapa
                    });

                    // Adiciona uma linha ao mapa
                    map.addLayer({
                        id: 'route',
                        type: 'line',
                        source: 'route',
                        layout: {
                            'line-join': 'round',
                            'line-cap': 'round'
                        },
                        paint: {
                            'line-color': '#888', // Cor da linha
                            'line-width': 8 // Largura da linha
                        }
                    });
                }
                
                // Centraliza o mapa na rota
                const bounds = new mapboxgl.LngLatBounds();
                route.forEach(coord => bounds.extend(coord)); // Expande os limites para incluir todos os pontos da rota
                map.fitBounds(bounds, { padding: 20 }); // Ajusta o mapa para se ajustar aos limites
                
                // Exibir informações de distância e duração
                const distance = data.routes[0].distance / 1000; // em km
                const duration = data.routes[0].duration / 60; // em minutos
                const distanciaInput = document.getElementById('distancia');
                distanciaInput.value = distance.toFixed(2); // Preenche o campo com a distância em km
                document.getElementById('result').innerHTML = `Duração: ${duration.toFixed(2)} min`;
            })
            .catch(error => {
                console.error("Erro:", error);
                // document.getElementById('erro').innerHTML = `Ocorreu um erro ao traçar a rota. Verifique os endereços e tente novamente.`;
                setError(erro);
            });
    } else {
        // document.getElementById('erro').innerHTML = `Por favor, preencha ambos os endereços.`;
        setError(erro);
    }
removeError(erro);    
});

// Fechar sugestões quando clicar fora
document.addEventListener('click', (e) => {
    if (!startInput.contains(e.target) && !startSuggestionsContainer.contains(e.target)) {
        startSuggestionsContainer.innerHTML = '';
        startSuggestionsContainer.style.display = 'none';
    }
    if (!endInput.contains(e.target) && !endSuggestionsContainer.contains(e.target)) {
        endSuggestionsContainer.innerHTML = '';
        endSuggestionsContainer.style.display = 'none';
    }});
    // dist.addEventListener('focusout', async () => {

    //     const response = await fetch (`https://viacep.com.br/ws/${cep.value}/json/`);
        
    //     if(!response.ok) {
    //         throw await response.json();
    //     }
    //      const responseDist = await response.json();
    //      distancia.value = responseDist.$distance;
    //      distancia.value = responseDist.${distance.toFixed(2)};
    //      cidade.value = responseDist.localidade;
    //     addCommaToEndereco(endereco);
    //     })

  