<?php
  $SQLSERVER = "localhost";
  $SQLPORT = 5433;
  $SQLUSERNAME = "postgres";
  $SQLPASSWORD = "sunzhihao";
  $SQLDATABASE = "postgres";

   $database = pg_connect("host=$SQLSERVER port=$SQLPORT dbname=$SQLDATABASE user=$SQLUSERNAME password=$SQLPASSWORD");


   if(!$database){
	     $SQLPORT = 5432;
		 $database = pg_connect("host=$SQLSERVER port=$SQLPORT dbname=$SQLDATABASE user=$SQLUSERNAME password=$SQLPASSWORD");
		 if(!$database){
			echo "Error : Unable to open database in config.php<br>". pg_last_error();
		 }
   }

?>
