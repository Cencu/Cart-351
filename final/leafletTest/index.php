<?php
require_once("db.php");
$locations = $conn->getLocationList();
 ?>
 <!DOCTYPE html>
 <html>
 <head>
   <title>Leaflet</title>
   <!-- include leaflet, javascript and leaflet css styling -->
   <h1 id= "statwalks"> Statistical Walks</h1>
    <p  id="preface">A project which includes my location, demonstrated by lines.<br> Each line contains certain data
    such as the distance, temperature and if the user is in a light or dark area. <br>
    The lines are styled based on these factors. <br>
    Additionally, users may implement their own data to create combined data.<br>
    This page will only show the results, there is another page to post the data.</p>

   <script src="jquery-3.3.1.min.js"></script>
   <link rel="stylesheet" href="pageone.css"/>
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
    integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
    crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
  integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
  crossorigin="">

</script>
<script src="leaflet-measure-path.js"></script>


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
//let colorTemp = colorHash.length;
//let colorChosen = '#69fc67';
  function changeLineColor(d) {
    return d > 25 ? '#fc6900':
           d > 20 ? '#fcb400':
           d > 15 ? '#fcef00':
           d > 10 ? '#e1f968':
           d > 5 ? '#09fc05':
           d > 0 ? '#69fc67':
           d > -5 ? '#ffffff':
           d > -10 ? '#35d2dd':
           d > -15 ? '#5d73d8':
           d > -20 ? '#2c02fc':
           d > -25 ? '#f4b7eb':
                     '#69fc67';

  }

  function showMeasurements(g) {
    return g > 0 ? "1" :
           g > 50 ? "2" :
           g > 100 ? "3" :
           g > 150 ? "4" :
           g > 200 ? "5" :
           g > 250 ? "6" :
           g > 300 ? "7" :
           g > 350 ? "8" :
           g > 400 ? "9" :
           g > 450 ? "10" :
           g > 500 ? "11" :
                     "1" ;
  }

  function styleWeight(featureW) {
    return {
      weight: changeLineWeight()
    };
  }


  function style(feature) {
    return {
      color: changeLineColor(tempe)

    };
  }

function addLocation() {
  for (let i=0; i<locations.length; i++) {
    let chosenColor = changeLineColor(locations[i]['tempe']);
    let polyline = L.polyline(stringToGeoPoints(locations[i]['geolocations']),{color: chosenColor}).addTo(map)
    .showMeasurements();
  //   var imageUrl = '  https://images.fineartamerica.com/images/artworkimages/mediumlarge/1/montreal-map-jazzberry-blue.jpg',
  //     imageBounds = [[40.712216, -74.22655], [40.773941, -74.12544]];
  // L.imageOverlay(imageUrl, imageBounds).addTo(map);

    //  console.log(locations[i]);
      polyline.bindPopup("<b>" + locations[i]['name']);
    }


}

let locations = JSON.parse('<?php echo json_encode($locations)?>');
</script>
</body>
</html>
