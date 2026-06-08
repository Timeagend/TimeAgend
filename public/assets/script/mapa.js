// 📍 Coordenadas fixas do seu local
var local = [-16.690241108302864, -49.25239841959069];

// Criar mapa já centralizado
var map = L.map('map02').setView(local, 17);

// Camada do mapa (OpenStreetMap)
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19
}).addTo(map);

// 🔴 Marcador fixo (principal)
L.marker(local).addTo(map)
  .bindPopup('<b>📌TimeAgend</b><br>Barbearia Premium')
  .openPopup();

// Mostrar coordenadas fixas no topo
document.getElementById("coords02").innerHTML =
"📍 Local fixo: " + local[0] + " | " + local[1];

// 🟡 Marcador temporário ao clicar (opcional)
var markerTemp;

map.on('click', function(e){

  var lat = e.latlng.lat;
  var lon = e.latlng.lng;

  document.getElementById("coords02").innerHTML =
  "📌 Clique: " + lat + " | " + lon;

  if(markerTemp){
    map.removeLayer(markerTemp);
  }

  markerTemp = L.marker([lat,lon]).addTo(map)
  .bindPopup("Lat: "+lat+"<br>Lng: "+lon)
  .openPopup();

});