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
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

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
https://{s}libcom.org/{z}/{x}/{y}files/images/library/black-square.jpg?{foo}
L.tileLayer('https://www.markpack.org.uk/files/2015/02/Black-square.png', {foo: 'bar'}).addTo(map);

$(document).ready(function(){
  addLocation()
});

function stringToGeoPoints(geo) {
  //Takees in both coordinates and splits them with a comma
  let linesPin = geo.split(",");
  //Separate latitude and longitude into two Separate arrays
  let linesLat = new Array();
  let linesLng = new Array();

//loop through the array, for every second iteration, you push the latitude and longitude
  for (i=0; i < linesPin.length; i++) {
    if(i %2) {
      linesLat.push(linesPin[i]);
    } else {
      linesLng.push(linesPin[i]);
    }
  }
  //create a new array to store the two coordinates
  let latLngLine = new Array();
//loop through the array and and push the coordinates
  for (i=0; i <linesLng.length;i++) {
    latLngLine.push(L.latLng(linesLat[i], linesLng[i]));

  }
  return latLngLine;
}
//function used to calculate Distance
//Harversine formula from MIT
function calcGeoDistance(latlon1,latlon2) {
     var R = 6371; //earth radius in KM


    var dLat = (latlon1.lat-latlon2.lat) * (Math.PI / 180);
    var dLon = (latlon1.lng-latlon2.lng) * (Math.PI / 180);
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(latlon1.lat * (Math.PI / 180)) * Math.cos(latlon2.lat * (Math.PI / 180)) *
            Math.sin(dLon/2) * Math.sin(dLon/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var p = R * c;
    return p;
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
//Function to change the stroke of the line
//used the distance formula to set the stroke weight
  function changeStroke(g) {
    return g > 0.1 ? "10" :
           g > .2 ? "3.5" :
           g > .4 ? "4" :
           g > 1 ? "4.5" :
           g > 1.50 ? "5" :
           g > 2.00 ? "5.5" :
           g > 2.50 ? "6" :
           g > 3.00 ? "7" :
           g > 3.50 ? "8" :
           g > 4.00 ? "9" :
           g > 4.50 ? "10" :
           g > 5.00 ? "11" :
                     "3" ;
  }
//Change if the user is in a light or dark Area
//the user inputs a 1 for light and 0 for dark
  function changeDash(c) {
    return c < 1 ? "8" :
           c > 1 ? "0" :
                        "0";
  }
  //return the functions above and attribute it to the database
  function styleDash(feature) {
    return {
      dashArray: changeDash(lOrD)
    };
  }

  function styleWeight(feature) {
    return {
      weight: changeStroke()
    };
  }

  function style(feature) {
    return {
      color: changeLineColor(tempe)
    };
  }

function addLocation() {
  //Adds the lines with its parameters
  for (let i=0; i<locations.length; i++) {

    let chosenColor = changeLineColor(locations[i]['tempe']);
    let lightOrDark = changeDash(locations[i]['lOrD']);
    let chosenWeight = changeStroke(locations[i]['geolocations']);
    let latLongArr = stringToGeoPoints(locations[i]['geolocations']);

    let d = calcGeoDistance(latLongArr[0],latLongArr[latLongArr.length-1]);

    let polyline = L.polyline(stringToGeoPoints(locations[i]['geolocations']),{color: chosenColor, weight: d*5, dashArray:lightOrDark}).addTo(map)
    .showMeasurements();

      polyline.bindPopup('<p>' + locations[i]['name'] + '</p>');
    }
}
//parse through the database table
let locations = JSON.parse('<?php echo json_encode($locations)?>');

//https://www.phpclasses.org/blog/post/284-Create-a-Google-Maps-alternative-with-PHP-and-MySQL-using-the-Leaflet-library.html
//Code from this website was implemented

</script>
</body>
</html>
