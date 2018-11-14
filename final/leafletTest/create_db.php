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
   $theQuery = 'CREATE TABLE locationLine (pieceID INTEGER PRIMARY KEY NOT NULL, location TEXT, tTime TEXT, latitude TEXT, longitude TEXT, temperature, light TEXT, dark TEXT)';
 $ok = $db ->exec($theQuery);
	// make sure the query executed
	if (!$ok)
	die($db->lastErrorMsg());
	// if everything executed error less we will arrive at this statement
	echo "Table locationLine created successfully<br \>";
}
catch(Exception $e)
{
   die($e);
}
?>
