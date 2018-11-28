<!DOCTYPE html>
<html lang="en">
 <head>
   <meta charset="utf-8">
   <title>Leaflet</title>
   <script src="leaflet.hotline.js"></script>
   <link rel="stylesheet" href="pageone.css">
   <script src="jquery-3.3.1.min.js"></script>

   <script src="https://d3js.org/d3.v5.min.js"></script>

   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
    integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
    crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
  integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
  crossorigin="">
</script>
<script src="http://maps.google.com/maps/api/js?key=AIzaSyBbCxEsdcXzR884-ABo1cp58ZfNDnhX8nE"></script>
<link rel="stylesheet" href="https://ppete2.github.io/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.css" />
<script src="https://ppete2.github.io/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.js"></script>
 </head>
 <body>
   <div id="map" style="width:1200px; height:700px"></div><br/>

    <!-- <h1 id="titles2">Add a new Location</h1> -->
    <!-- Two buttons, one to draw the street and the other to clear the map -->
    <input id="drawloc" type="button" onclick="drawLocation();" value="Draw a location"/>
    <input id="drawloc" type="button" onclick="resetLocation();" value = "Clear location"/><br/>

    <p id="titles2">To add a location, point and click on the map. To remove the location, point and click again</p>
<!-- this form collects points from the map -->
      <form  action="addlocationdb.php" method="post">
        <!-- <h1 id="titles">Location Specifics</h1> -->
        <tr>
          <p id="ttextD" >Distance:</p>
          <td><textarea id="tboxtextD" name="tTravel"></textarea></td>
        </tr>
        <tr>
          <p id="ttextT">Temperature</p>
          <td ><textarea id="tboxtextT" name="tempe"></textarea></td>
        </tr>
        <tr >
          <p id="ttextL">Light or Dark Area</p>
          <td ><textarea id="tboxtextL" name="lOrD"></textarea></td>
        </tr>



        <h1 id="titles">Add a new location</h1>
        <table id="write" cellpadding = "5" cellspacing="0" border="0">
          <tbody>
            <tr align="left" valign="top">
              <td align="left" valign="top">Geographic Locations</td>
              <td align="left" valign="top"><input type="text" name="location"/></td>
            </tr>
            <tr align="left" valign="top">
              <td align="left" valign="top">Geographic Locations</td>
              <td align="left" valign="top">
                <textarea id="geo" name="geo"></textarea>
                <br/><input type="button" onclick="getGeoPoints();" value="Collect points"/>
              </td>
            </tr>
            <tr align="left" valign="top">
              <td align="left" valign="top">Keywords</td>
              <td align="left" valign="top"><textarea name="keywords"></textarea></td>
            </tr>
            <tr align="left" valign="top">
              <td align="left" valign="top"></td>
              <td align="left" valign="top"><input type="submit" value="Save"></td>
          </tbody>
        </table>
      </form>

   <script>
   var map = L.map('map').setView([45.49698739, -73.57880769], 18);
   map.locate({setView: true, maxZoom: 16});
   //polyline will hold the lines we draw on the map
   var polyLine;
   //This array will hold all the draggable locations, every location has a latitide and
   //a longitude, it may also have several points, so we need to hold all this data.
   var draggableLocMarkers = new Array();
   //let hotlineLayer = L.hotline(points, options).addTo(map);

  // let lineColor = {"type" };
   L.tileLayer('https://api.tiles.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibW9vc2hlciIsImEiOiJjam80c3pvamIwM2d0M3FxbXFqcmtkaGowIn0.Xq0Sqda765iioS8nU7nQDg', {
       attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
       minZoom: 12,
       maxZoom: 18,
      // id: 'mapbox.streets',
    //   accessToken: 'pk.eyJ1IjoibW9vc2hlciIsImEiOiJjam80c3pvamIwM2d0M3FxbXFqcmtkaGowIn0.Xq0Sqda765iioS8nU7nQDg'
   }).addTo(map);
   //This function clears all the points we have put on the map
   //it checks if there were any polylines put on the map and removes them
   function resetLocation() {
     if (polyLine != null) {
       map.removeLayer(polyLine);
     }
     //loop through draggableLocMarkers and check every elemnt which is a marker
     //We also remove it from the map and reset draggableLocMarkers by assigning a new array
     for (i=0; i< draggableLocMarkers.length; i++) {
       map.removeLayer(draggableLocMarkers[i]);
     }
     draggableLocMarkers = new Array();
   }

   function addMarkersLocPoint (latLng) {
     let locMarker = L.marker([latLng.lat, latLng.lng], {draggable:true, zIndexOffset:900}).addTo(map);
     locMarker.arrayId = draggableLocMarkers.length;
     locMarker.on('click',function(){
       map.removeLayer(draggableLocMarkers[this.arrayId]);
       draggableLocMarkers[this.arrayId]="";
     });
     draggableLocMarkers.push(locMarker);
   }

   //We draw a line through the points of a location we want to create
   function drawLocation() {
     //we remove overlapping polylines with this function
     if(polyLine != null) {
       map.removeLayer(polyLine);
     }
     //define an array to hold the latitude and longitude of every point on the map
   let latLngLoc = new Array();
   //loop through the points in the array, if the element is not empty then push the latitude
   //and longitude values to latLngLoc array as a latLng pair, which is a type of data in Leaflet
   //draggableLocMarkers uses elements as markers, so we take the Geographical points from them
   //we use getLatLng method to get it
   for(i=0;i<draggableLocMarkers.length;i++) {
     if(draggableLocMarkers[i]!="") {
       latLngLoc.push(L.latLng(draggableLocMarkers[i].getLatLng().lat,draggableLocMarkers[i].getLatLng().lng));
     }
   }

   //If we have more than one point on the map then we can draw a line
   if (latLngLoc.length>1){
    // changeLineColor();
     //create a red polyline from an array of latLng points
     polyLine=L.polyline(latLngLoc,{color:style}).addTo(map);
   }


   //if the line is succesfully created then we zoom to the lines location
   if(polyLine != null){
     //zoom the map to the polyLine
     map.fitBounds(polyLine.getBounds());
   }
}

//this function gets the points from the map and assigns them in the text area box
//we first create an array called points, we loop through the draggableLocMarkers array
//and for each element that is not empty, we extract from it the latitude and longitude
//we pair them by putting a comma between them
 function getGeoPoints() {
   let points = new Array();
   for (let i = 0; i < draggableLocMarkers.length; i++){
     if(draggableLocMarkers[i] != "") {
       points[i] = draggableLocMarkers[i].getLatLng().lng + "," + draggableLocMarkers[i].getLatLng().lat;

     }
   }
   $('#geo').val(points.join(','));
 }

 $(document).ready(function() {
   map.on('click',function(e){
     addMarkersLocPoint(e.latlng);
   });
 });


   </script>
 </body>
</html>
