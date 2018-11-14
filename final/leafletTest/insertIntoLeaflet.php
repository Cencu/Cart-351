<?php

class MyDB extends SQLite3
   {
      function __construct()
      {
         $this->open('../db/leafletTest.db');
      }
   }

try
{
   $db = new MyDB();
   $countRowsStatement= "SELECT COUNT (*) FROM locationLine";
   $countRows = $db->query($countRowsStatement);
   if (!$countRows) die("Cannot execute query.");
   $countResult = $countRows->fetchArray(SQLITE3_NUM);
echo $countResult[0]."<br \>";
if($countResult[0] == 0)
{
 echo " we have an empty database:: <br \>";
}
else
{
    echo "We already have " .$countResult[0] . " rows in the leaflet Table";
    $queryArray = array(
         "INSERT INTO locationLine (artist, title, creationDate, geoLoc, descript, image) VALUES ('Martha', 'Elephants','1998-03-04','Montreal','Description for the arts','images/alternative-paris-tours.jpg')",
    	   "INSERT INTO locationLine (artist, title, creationDate, geoLoc, descript, image) VALUES ('Sarah', 'Hippos','2002-06-12','Montreal','Description for the arts','images/Artists-Lane-2.jpg')",
    	   "INSERT INTO locationLine (artist, title, creationDate, geoLoc, descript, image) VALUES ('Harold', 'Untitled', '2012-10-21','New York','Description for the arts','images/gorod-stena-grafiti-4927.jpg')",
     	   "INSERT INTO locationLine (artist, title, creationDate, geoLoc, descript, image) VALUES ('Stephen', 'Scotland','1999-07-18','Edinborough','Description for the arts','images/graffiti-artist-scotland.jpg')",
     	   "INSERT INTO locationLine (artist, title, creationDate, geoLoc, descript, image) VALUES ('Martha', 'Tigers','2017-08-21','Paris','Description for the arts','images/maxresdefault.jpg')",
    	   "INSERT INTO locationLine (artist, title, creationDate, geoLoc, descript, image) VALUES ('Sarah', 'WIndow','2005-06-13','Toronto','Description for the arts','images/windows.jpg')",
    	   "INSERT INTO locationLine (artist, title, creationDate, geoLoc, descript, image) VALUES ('Sarah', 'Untitled', '2003-03-19','Halifax','Description for the arts','images/work-50.jpg')",
     	   "INSERT INTO locationLine (artist, title, creationDate, geoLoc, descript, image) VALUES ('Stephen', 'Zoo','2000-05-06','London','Description for the arts','images/multi.jpg')"
     	  );
        // go through each entry in the array and execute the INSERT query statement....
        for($i =0; $i< count($queryArray); $i++)
        {
    	     $ok1 = $db->exec($queryArray[$i]);
    	      if (!$ok1)
    	      {
    		         die($db->lastErrorMsg());
    	       }
    }
    // if we reach this point then all the data has been inserted successfully.
    echo "INSERTION OF ".count($queryArray)." entries into artCollection Table successful";
}
}
catch(Exception $e)
{
   die($e);
}
?>
