

let map, directionsService, directionsRenderer, sorceAutocomplet
function initMap() {
    //novo mapa
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 41.156485, lng: -8.610095 },
        zoom: 13,
    });


    google.maps.event.addListener(map, "click", function (event) {
        this.setOptions({ scrollwheel: true });
    })
    //marcar mapa
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);
    sorceAutocomplet = new google.maps.places.Autocomplete(document.getElementById('inicio'))
    var marker = new google.maps.Marker({
        position: { lat: 41.156485, lng: -8.610095 },
        map: map
    })
    //geolocation e addMark em mapa
    var infoWindow = new google.maps.InfoWindow({
        content:"<h4>Weasp</h4><p><i class='fa-solid fa-building' style='color: #627e93;'></i> web company office</p>"
    })
    marker.addListener('click', function(){
        infoWindow.open(map, marker);
    })
}
function addMark(coords) {
    var marker = new google.maps.Marker({
        position: coords,
        map: map
    });
}
function calcRoute() {
    var selectedMode = document.getElementById('mode').value;
    var inicio = document.getElementById('inicio').value
    var office = { lat: 41.156485, lng: -8.610095 };
    var request = {
        origin: inicio,
        destination: office,
        travelMode: google.maps.TravelMode[selectedMode],
    };
    directionsService.route(request, function (result, status) {
        if (status == "OK") {
            directionsRenderer.setDirections(result);
        }
    });
}
function locanow(){
    navigator.geolocation.getCurrentPosition(function (position,) {
        lat = position.coords.latitude,
            lng = position.coords.longitude
         var client = {lat:position.coords.latitude, lng: position.coords.longitude};
        addMark({ lat: lat, lng: lng })
        var office = { lat: 41.156485, lng: -8.610095 };
        var selectedMode = document.getElementById('mode').value;
    var request = {
        origin: client,
        destination: office,
        travelMode: google.maps.TravelMode[selectedMode],
    };
    directionsService.route(request, function (result, status) {
        if (status == "OK") {
            directionsRenderer.setDirections(result);
            console.log(result)
        }else{
            
        }
    });
})
}



//corrigido.
/*

                addMark({ lat: 41.156485, lng: -8.610095 })

            
*/
