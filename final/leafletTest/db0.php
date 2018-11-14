<?php

class connectToDB {
  private $conn;
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
}
// public function addLocation($location, $geo, $keywords) {
//   $statement = $this->conn->prepare("INSERT INTO locations(name, geolocations, keywords) VALUES(?, ?, ?)");
//   $statement->bind_param( 'sss', $location, $geo, $keywords);
//   $statement->execute();
//   $statement->close();
// }
//
// public function getLocationList() {
//   $arr = array();
//   $statement = $this->conn->prepare("SELECT id, name, geolocations, keywords from locations order by name ASC");
//   $statement->bind_result($id, $name, $geolocations,$keywords);
//   $statement->execute();
//   while ($statement->fetch()) { {
//     $arr[] = ["id" => $id, "name" => $name, "geolocations" =>$geolocations, "keywords => $keywords"];
//   }
//     $statement->close();
//   }
//   return $arr;
// }
// public function getSearchResults($keyword)
// {
// $statement = $this->conn->prepare( "SELECT name, geolocations FROM `locations` where keywords REGEXP ? or name REGEXP ?");
// $statement->bind_param( 'ss', $keyword, $keyword);
// $statement->execute();
// $statement->bind_result( $name, $geolocations);
// while ($statement->fetch()) {
// $temp = explode(",",$geolocations);
// $arr[] = '{"name":"' . $name. '","latitude":"' . $temp[1]. '","longitude":"' . $temp[0]. '"}';
// }
// $statement->close();
//
// $jsonData .= implode(",", $arr);
// $jsonData .= ']}';
// return $jsonData;
//   }
// }
//
//$conn = new connectToDB($config['db']['server'], $config['db']['user'],$config['db']['pass'],$config['db']['dbname']);
  $conn = new connectToDB();
  ?>
