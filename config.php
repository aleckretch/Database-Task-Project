<?php
  $SQLSERVER = "localhost";
  $SQLPORT = 5432;
  $SQLUSERNAME = "postgres";
  $SQLPASSWORD = "sunzhihao";
  $SQLDATABASE = "postgres";

   $database = pg_connect("host=127.0.0.1 port=5432 dbname=postgres user=postgres password=sunzhihao");

   if(!$database){
         echo "Error : Unable to open database\n";
      } else {
         echo "Opened database successfully\n";
      }


?>
