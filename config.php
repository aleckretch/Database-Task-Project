<?php
  $SQLSERVER = "localhost";
  $SQLPORT = 5432;
  $SQLUSERNAME = "postgres";
  $SQLPASSWORD = "sunzhihao";
  $SQLDATABASE = "test";

   $database = pg_connect("host=127.0.0.1 port=5432 dbname=test user=postgres password=sunzhihao");

   if(!$database){
         echo "Error : Unable to open database<br>";
   }


?>
