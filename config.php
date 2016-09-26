<?php
  $SQLSERVER = "localhost";
  $SQLPORT = 5432;
  $SQLUSERNAME = "postgres";
  $SQLPASSWORD = "sunzhihao";
  $SQLDATABASE = "test";

   $database = pg_connect("host=$SQLSERVER port=$SQLPORT dbname=$SQLDATABASE user=$SQLUSERNAME password=$SQLPASSWORD");

   if(!$database){
         echo "Error : Unable to open database<br>". pg_last_error();
   }

?>
