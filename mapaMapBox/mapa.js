        // Substitua pelo seu token do Mapbox
        mapboxgl.accessToken = 'pk.eyJ1IjoieGRhbmllbCIsImEiOiJjbTMzcXZjZ2oxcnN6Mm1wczJudDl5emJ3In0.b3w2oKSjzT0vH7LEQ6oY_Q';
        let map;

        document.getElementById('tripForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const origem = document.getElementById('origem').value;
            const destino = document.getElementById('destino').value;
            
            if (!origem || !destino) {
                alert('Por favor, preencha origem e destino');
                return;
            }
            
            // Mostrar loading
            document.getElementById('loading').style.display = 'block';
            document.getElementById('results').style.display = 'none';
            
            try {
                // Fazer requisição para o PHP
                const response = await fetch('processar_rota.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        origem: origem,
                        destino: destino
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    mostrarResultados(data.rota, data.postos);
                } else {
                    alert('Erro ao calcular rota: ' + data.error);
                }
            } catch (error) {
                alert('Erro na requisição: ' + error.message);
            } finally {
                document.getElementById('loading').style.display = 'none';
            }
        });

        function mostrarResultados(rota, postos) {
            // Preencher informações
            document.getElementById('origemInfo').textContent = rota.origem.place_name;
            document.getElementById('destinoInfo').textContent = rota.destino.place_name;
            document.getElementById('distanciaInfo').textContent = rota.distancia_km + ' km';
            document.getElementById('tempoInfo').textContent = rota.tempo_formatado;
            
            // Mostrar postos
            const postosContainer = document.getElementById('postosInfo');
            postosContainer.innerHTML = '';
            
            if (postos && postos.length > 0) {
                postos.forEach(posto => {
                    const postoDiv = document.createElement('div');
                    postoDiv.className = 'posto-item';
                    postoDiv.innerHTML = `
                        <strong>${posto.nome}</strong><br>
                        ${posto.endereco}<br>
                        <small>Distância da origem: ${posto.distancia_origem} km</small>
                    `;
                    postosContainer.appendChild(postoDiv);
                });
            } else {
                postosContainer.innerHTML = '<p>Nenhum posto encontrado na rota.</p>';
            }
            
            // Criar mapa
            criarMapa(rota);
            
            // Mostrar resultados
            document.getElementById('results').style.display = 'block';
        }

        function criarMapa(rota) {
            const mapContainer = document.getElementById('mapContainer');
            mapContainer.innerHTML = '<div id="map" style="width: 100%; height: 100%;"></div>';
            
            map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [rota.origem.longitude, rota.origem.latitude],
                zoom: 6
            });

            map.on('load', function() {
                // Adicionar rota
                map.addSource('route', {
                    'type': 'geojson',
                    'data': {
                        'type': 'Feature',
                        'properties': {},
                        'geometry': rota.geometria
                    }
                });

                map.addLayer({
                    'id': 'route',
                    'type': 'line',
                    'source': 'route',
                    'layout': {
                        'line-join': 'round',
                        'line-cap': 'round'
                    },
                    'paint': {
                        'line-color': '#007bff',
                        'line-width': 5
                    }
                });

                // Adicionar marcadores
                new mapboxgl.Marker({ color: 'green' })
                    .setLngLat([rota.origem.longitude, rota.origem.latitude])
                    .setPopup(new mapboxgl.Popup().setHTML('<h3>Origem</h3><p>' + rota.origem.place_name + '</p>'))
                    .addTo(map);

                new mapboxgl.Marker({ color: 'red' })
                    .setLngLat([rota.destino.longitude, rota.destino.latitude])
                    .setPopup(new mapboxgl.Popup().setHTML('<h3>Destino</h3><p>' + rota.destino.place_name + '</p>'))
                    .addTo(map);

                // Ajustar zoom para mostrar toda a rota
                const bounds = new mapboxgl.LngLatBounds();
                bounds.extend([rota.origem.longitude, rota.origem.latitude]);
                bounds.extend([rota.destino.longitude, rota.destino.latitude]);
                map.fitBounds(bounds, { padding: 50 });
            });
        }