let map;
let directionsService;
let directionsRenderer;
let autocompleteStart;
let autocompleteEnd;
let placesService;

document.addEventListener('DOMContentLoaded', function () {
});

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: -23.55052, lng: -46.633308 }, 
    zoom: 12,
  });

  getUserLocation();

  directionsService = new google.maps.DirectionsService();
  directionsRenderer = new google.maps.DirectionsRenderer();
  directionsRenderer.setMap(map);

  placesService = new google.maps.places.PlacesService(map);

  const startInput = document.getElementById("start");
  const endInput = document.getElementById("end");
  autocompleteStart = new google.maps.places.Autocomplete(startInput);
  autocompleteEnd = new google.maps.places.Autocomplete(endInput);

  document.getElementById("calculateBtn").addEventListener("click", calculateRoute);
  document.getElementById("searchPlacesBtn").addEventListener("click", searchNearbyPlaces);
}

function calculateRoute() {
  const start = document.getElementById("start").value;
  const end = document.getElementById("end").value;

  if (!start || !end) {
    alert("Por favor, preencha os campos de partida e destino.");
    return;
  }

  const request = {
    origin: start,
    destination: end,
    travelMode: google.maps.TravelMode.DRIVING,
  };

  directionsService.route(request, (result, status) => {
    if (status === google.maps.DirectionsStatus.OK) {
      directionsRenderer.setDirections(result);

      const route = result.routes[0].legs[0];
      
      let distance = route.distance.text; 
      let duration = route.duration.text; 

      let distanceValue = parseFloat(distance); 
      if (isNaN(distanceValue)) {
        distanceValue = 0; 
      }

      
      const distanceInput = document.getElementById('distancia');
      distanceInput.value = distanceValue.toFixed(2);  
      document.getElementById("result").innerHTML = `
        <p>Tempo estimado: <strong>${duration}</strong></p>
      `;

      searchNearbyPlaces(route);
    } else {
      alert("Erro ao calcular a rota. Tente novamente.");
    }
  });
}

function searchNearbyPlaces(route) {
  const placeType = document.getElementById("placeType").value;
  
  if (!route || !route.steps || route.steps.length === 0) {
    alert("Rota não encontrada ou sem etapas válidas.");
    return;
  }

  route.steps.forEach((step) => {
    const location = step.end_location;

    const placesService = new google.maps.places.PlacesService(map);
    const request = {
      location: location,
      radius: 1000, // Raio de 5km
      type: [placeType],
    };

    placesService.nearbySearch(request, (results, status) => {
      if (status === google.maps.places.PlacesServiceStatus.OK) {
        if (results && results.length > 0) {
          displayPlaces(results);
        } else {
          console.log("Nenhum local encontrado nesta etapa.");
        }
      } else {
        console.error("Erro ao buscar locais próximos:", status);
      }
    });
  });
}

function displayPlaces(places) {
  clearMarkers();

  places.forEach((place) => {
    const marker = new google.maps.Marker({
      position: place.geometry.location,
      map: map,
      title: place.name,
    });

    const infoWindow = new google.maps.InfoWindow({
      content: `
        <div>
          <h3>${place.name}</h3>
          <p>${place.vicinity}</p>
        </div>
      `,
    });

    marker.addListener("click", () => {
      infoWindow.open(map, marker);
    });
  });

  const bounds = new google.maps.LatLngBounds();
  places.forEach((place) => bounds.extend(place.geometry.location));
  map.fitBounds(bounds);
}

function clearMarkers() {
}

function getUserLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function (position) {
        const userLocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };

        map.setCenter(userLocation);
        map.setZoom(14);  

      },
      function () {
        alert("Erro ao obter a localização. Permita o acesso à sua localização.");
      }
    );
  } else {
    alert("Geolocalização não é suportada neste navegador.");
  }
}