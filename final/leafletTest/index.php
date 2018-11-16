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
//-30 -25 PURPLE, -25 -20 LIGHT PURPLE, -20 -15 BLUE, -15 -10 MID BLUE, -10 -5 LIGHT BLUE, -5 0 (WHITE).
//0 5 LIGHT GREEN, 5 10 GREEN, 10 15 LIGHT YELLOW, 15 20 YELLOW, 20 25 ORANGE, 25 30 DARK ORANGE
let colorHash = ['#f43fd9','#f4b7eb', '#2c02fc', '#5d73d8','#35d2dd','#ffffff','#69fc67', '#09fc05','#e1f968','#fcef00','#fcb400','#fc6900'];
let colorTemp = colorHash.length;
let colorChosen = '#69fc67';
  function changeLineColor() {
    //Create variables to change the lines colors
    // for(let i = 0; i < colorTemp; i++) {
      if ($('#tempe') >= 25) {
        colorChosen = colorHash[11];
      } if ($('#tempe') <= 24 && $('#tempe') >=20) {
        colorChosen =  colorHash[10];
      }if ($('#tempe') <= 19 && $('#tempe') >=15) {
        colorChosen =  colorHash[9];
      }if ($('#tempe') <= 14 && $('#tempe') >=10) {
          colorChosen =colorHash[8];
      }if ($('#tempe') <= 9 && $('#tempe') >=5) {
          colorChosen =colorHash[7];
      }if ($('#tempe') <= 4 && $('#tempe') >=0) {
        colorChosen =  colorHash[6];
      }if ($('#tempe') <= 0 && $('#tempe') >=-5) {
        colorChosen =  colorHash[5];
      }if ($('#tempe') <= -6 && $('#tempe') >=-10) {
        colorChosen =  colorHash[4];
      }if ($('#tempe') <= -11 && $('#tempe') >=-15) {
        colorChosen =  colorHash[3];
      }if ($('#tempe') <= -16 && $('#tempe') >=-20) {
        colorChosen =  colorHash[2];
      }if ($('#tempe') <= -21 && $('#tempe') >=-25) {
        colorChosen =  colorHash[1];
      }if ($('#tempe') <= -26) {
        colorChosen =  colorHash[0];
      }
//    }

  }
function addLocation() {
  for (let i=0; i<locations.length; i++) {
    changeLineColor();
      console.log(colorChosen);
    let polyline = L.polyline(stringToGeoPoints(locations[i]['geolocations']), {
      color:colorChosen}).addTo(map);

      polyline.bindPopup("<b>" + locations[i]['name']);
    }


}

let locations = JSON.parse('<?php echo json_encode($locations)?>');
</script>
</body>
</html>
