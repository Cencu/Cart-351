<?php

class connectToDB {
  private $conn;
//   public function __construct(){
//
//     $config = include 'config.php';
//     $this->conn = new connectToDB($config['db']['server'], $config['db']['user'],$config['db']['pass'],$config['db']['dbname']);
//   }
// function __destruct() {
//   $this->conn.close();
// }
public function __construct(){

  $config = include 'config.php';
  $this->conn = mysqli_connect($config['db']['server'], $config['db']['user'],$config['db']['pass'],$config['db']['dbname']);
  //echo($config['db']['dbname']);
  // Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL:"  . mysqli_connect_error();
}
}
function __destruct() {
mysqli_close($this->conn);
}
public function addLocation($locations, $geo, $tTravel, $tempe, $lOrD, $keywords) {
  $statement = $this->conn->prepare("INSERT INTO location(name, geolocations, tTravel, tempe, lOrD, keywords) VALUES(?, ?, ?, ?, ?, ?)");
  $statement->bind_param( 'ssssss', $locations, $geo, $tTravel, $tempe, $lOrD, $keywords);
  $statement->execute();
  $statement->close();
}
// tTravel, temp, lOrD,
//$tTravel, $tempe, $lOrD,

public function getLocationList() {
  $arr = array();
  $statement = $this->conn->prepare("SELECT id, name, geolocations, tTravel, tempe, lOrD, keywords from location order by name ASC");
  //tTravel, tempe, lOrD,
  $statement->execute();
  $statement->bind_result($id, $name, $geolocations, $tTravel, $tempe, $lOrD, $keywords);
  //$tTravel, $tempe, $lOrD,
  while ($statement->fetch()) {
    $arr[] = ["id" => $id, "name" => $name, "geolocations" =>$geolocations, "tTravel" => $tTravel, "tempe" =>$tempe, "lOrD" =>$lOrD, "keywords => $keywords"];
    //"tTravel" => $tTravel, "tempe" =>$tempe, "lOrD" =>$lOrD,
  }
    $statement->close();

  return $arr;
}
public function getSearchResults($keyword)
{
$statement = $this->conn->prepare( "SELECT name, geolocations FROM `locations` where keywords REGEXP ? or name REGEXP ?");
$statement->bind_param( 'ss', $keyword, $keyword);
$statement->execute();
$statement->bind_result( $name, $geolocations);
//$tTravel, $tempe, $lOrD,
while ($statement->fetch()) {
$temp = explode(",",$geolocations);
$arr[] = '{"name":"' . $name. '","latitude":"' . $temp[1]. '","longitude":"' . $temp[0]. '"}';
}
$statement->close();

$jsonData .= implode(",", $arr);
$jsonData .= ']}';
return $jsonData;
  }
}





  $conn = new connectToDB();
  ?>
