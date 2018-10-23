<?php

// You will have at least 3 different inputs (2 from the canvas and 1 from the button).
//Button: Spawns shapes of various size,
//Shapes will spin in an oval
//Canvas: one shape will cause the words to stretch and compress
//Canvas: one shape will remove letters from the words


//check if there has been something posted to the server to be processed
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $sampleDataAsIfInAFile = array("smarties","twix","snickers","maltesers","flake","wunderbar","mars");
    $sampleDataAsIfInAFile2 = array("oranges","apples","peppers","carrots","grapes","grapefruits","kumquats");
// need to process -> we could save this data ...
 $xPos = $_POST['xpos'];
 $yPos = $_POST['ypos'];
 $action = $_POST['action'];
 //do some silly processing:
 $newPos = $xPos+$yPos;
 //lets choose a word from our "data file" based on the sum of the x and y pos...
 //there are 2 possible actions choose the word depending on action...
 if($action =="theButton"){
 $dataToSend =  $sampleDataAsIfInAFile[$newPos%count($sampleDataAsIfInAFile)];
}
else{
  $dataToSend =$sampleDataAsIfInAFile2[$newPos%count($sampleDataAsIfInAFile2)];
}

    //package the data and echo back...
    $myPackagedData=new stdClass();
    $myPackagedData->word = $dataToSend;
     // Now we want to JSON encode these values to send them to $.ajax success.
    $myJSONObj = json_encode($myPackagedData);
    echo $myJSONObj;
    exit;
}//POST
?>

<!DOCTYPE html>
<html>
<head>
<title>USING JQUERY AND AJAX AND CANVAS </title>
<!-- get JQUERY -->
  <script src = "libs/jquery-3.3.1.min.js"></script>
<style>
body{
  margin:0;
  padding:0;
}
canvas{
  background:black;
  margin:0;
  padding:0;
}
#b{
  background:purple;
  color:white;
  margin:5px;
  text-align: center;
  padding: 5px;
  width:10%;
}
</style>
</head>
<body>
<div id = "b"><p>CLICK BUTTON</p></div>

<canvas id="myCanvas" width=500 height=500></canvas>
<!-- here we put our JQUERY -->
<script>
$(document).ready (function(){

  //declare some global vars ...
  let x = 250;
  let y = 250;

  let theWord = "";
  let theWord2 = "";
  //start ani
  goAni();
  // when we click on the canvas somewhere and the collision detection returns true ...
  let xSize = Math.random()*50;
  let ySize = Math.random()*50;



  $('#myCanvas').on("mousedown",function(event){
  //  console.log("mouseover on canvas");
    let truth = checkCollision(event);
    if(truth ===true){
      //our function for sending data
      sendData("theCanvas");


    }
  });


  // if we click on the button other stuff happens ...
    $( "#b" ).click(function( event ) {
      //stop submit the form, we will post it manually. PREVENT THE DEFAULT behaviour ...
       event.preventDefault();
       console.log("button clicked");
       sendData("theButton");


     });

     function sendData(typeOfClick){
       let data = new FormData();
       data.append('action', typeOfClick);
       data.append('xpos', x);
       data.append('ypos', y);

       $.ajax({
             type: "POST",
             enctype: 'multipart/form-data',
             url: "PHPEx.php",
             data: data,
             processData: false,//prevents from converting into a query string
             contentType: false,
             cache: false,
             timeout: 600000,
             success: function (response) {
             //reponse is a STRING (not a JavaScript object -> so we need to convert)
             console.log(response);
             //use the JSON .parse function to convert the JSON string into a Javascript object
             let parsedJSON = JSON.parse(response);
             console.log(parsedJSON);
             if(typeOfClick ==="theButton"){
             theWord = parsedJSON.word;

           }
           else {
              theWord2 = parsedJSON.word;
           }

         },
         error:function(){
           console.log("error occured");
         }
       });
     } //end sendData


    function goAni(){
      let canvas = document.getElementById('myCanvas');
      let canvasContext = canvas.getContext('2d');


      requestAnimationFrame(runAni);

     function runAni(){
     //need to reset the background :)
     // clear the canvas ...
     canvasContext.clearRect(0, 0, canvas.width, canvas.height);
     canvasContext.fillStyle = "#33B2FF";
     canvasContext.fillRect(x,y,xSize,ySize);
     canvasContext.fillStyle = "#FFFFFF";
     canvasContext.fillRect(x,y,1,1);
    // x+=1;

     canvasContext.font = "40px Arial";
     canvasContext.fillStyle = "#B533FF";
     canvasContext.fillText(theWord,canvas.width/2 - (theWord.length/2*20),canvas.height/2);

     canvasContext.fillStyle = "#FF9033";
     canvasContext.fillText(theWord2,canvas.width/2 - (theWord2.length/2*20),canvas.height/4);
    // spin();
    // returnBorders();
     requestAnimationFrame(runAni);
   }

   function spin(event) {
     if (x >15 && x <20) {
       xSize++;
       ySize++;
     }
     if (x > 110 && x <115) {
       xSize--;
       ySize--;
     }
   }

   function returnBorders (event){

     if (x <= 11 ) {
       x +=1;
     }
     if (x > 120 ) {
       x -= 2;
     }

   }

  }
  function checkCollision(event){
    let domRect = document.getElementById("myCanvas").getBoundingClientRect();
     if(x>event.clientX-20 && x<event.clientX+20 && y >(event.clientY-domRect.top)-20 && y<((event.clientY-domRect.top)+20))
    {
      return true;

    }
    return false;
  }
}); //document ready
</script>
</body>
</html>
