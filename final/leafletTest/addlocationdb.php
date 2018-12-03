<?php
//where the data gets posted
require_once("db.php");
$locations = strip_tags( $_POST['locations'] );
$geo = strip_tags( $_POST['geo'] );
$tTravel = strip_tags($_POST['ttravel']);
$tempe = strip_tags($_POST['tempe']);
$lOrD = strip_tags($_POST['lOrD']);
$keywords = strip_tags( $_POST['keywords'] );

 $conn->addLocation( $locations, $geo, $tTravel, $tempe, $lOrD, $keywords);
  // $tTravel, $tempe, $lOrD,

?>
<!DOCTYPE html>
<html>
 <head>
  <title>Location added</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
 </head>
 <body>
  <h1>Location has been added</h1>
 </body>
</html>
