<?php
require_once("db.php");
$locations = $conn->getLocationList();
 ?>
 <!DOCTYPE html>
 <html>
 <head>
   <title>Leaflet</title>
   <!-- include leaflet, javascript and leaflet css styling -->
   <script src="jquery-3.3.1.min.js"></script>
   <link rel="stylesheet" href="pageone.css"/>
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
    integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
    crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
  integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
  crossorigin="">
</script>
</head>
<body>
  <!-- <input type="text" name="search" id="search" /> <input type="button" id="searchBtn" value="Search" /> -->
<!-- create a div to store the map -->
<div id="map" style="width:1200px; height:700px"></div>
<script>
//create a varable which holds the map so we can manipulate it
//We set the view to specific coordinates (concordia)
let map = L.map('map').setView([45.49698739, -73.57880769], 18);
//Set the layer which acceses the map using the access token. Include copyright
L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    //Minzoom makes it so the user cannot zoom out of the map too much. the idea is to stay in Montreal
    //max zoom makes it so that you cannot zoom in too much
    minZoom: 12,
    maxZoom: 18,
    id: 'mapbox.streets',
    accessToken: 'pk.eyJ1IjoibW9vc2hlciIsImEiOiJjam80c3pvamIwM2d0M3FxbXFqcmtkaGowIn0.Xq0Sqda765iioS8nU7nQDg'
//add the functions to the map
}).addTo(map);
//
$(document).ready(function(){
  // $('#searchBtn').click(function() {
  //   $.ajax({
  //   type: "GET",
  //   url: "ajax.php?keyword="+$("#search").val()
  //   }).done(function( data )
  //   {
  //   var jsonData = JSON.parse(data);
  //   var jsonLength = jsonData.results.length;
  //   var html = "<ul>";
  //   for (var i = 0; i < jsonLength; i++) {
  //     var result = jsonData.results[i];
  //     html += '<li data-lat="' + result.latitude + '" data-lng="' + result.longitude + '" class="searchResultElement">' + result.name + '</li>';
  //   }
  //   html += '</ul>';
  //   $('#searchresult').html(html);
  //   $( 'li.searchResultElement' ).click( function() {
  //     var lat = $(this).attr( "data-lat" );
  //     var lng = $(this).attr( "data-lng" );
  //     map.panTo( [lat,lng] );
  //   });
  //   });
  // });
  addLocation()


});

function stringToGeoPoints(geo) {
  let linesPin = geo.split(",");

  let linesLat = new Array();
  let linesLng = new Array();

  for (i=0; i < linesPin.length; i++) {
    if(i %2) {
      linesLat.push(linesPin[i]);
    } else {
      linesLng.push(linesPin[i]);
    }
  }
  let latLngLine = new Array();

  for (i=0; i <linesLng.length;i++) {
    latLngLine.push(L.latLng(linesLat[i], linesLng[i]));
  }
  return latLngLine;
}
//array of colors from really cold to really hot
let colorTemp = [];
let color = '#';

  function changeLineColor() {
    //Create variables to change the lines colors
    for(let i = -30; i < 30; i++) {
      if (colorTemp[i] < -25) {
        $('#tempe').val(points.join(','));
        color += 'af70b5';
      }
    }

  }
function addLocation() {
  for (let i=0; i<locations.length; i++) {
    let polyline = L.polyline(stringToGeoPoints(locations[i]['geolocations']), {
      color:colorTemp}).addTo(map);
      console.log(color);

      polyline.bindPopup("<b>" + locations[i]['name']);
  }
}

let locations = JSON.parse('<?php echo json_encode($locations)?>');
</script>
</body>
</html>
