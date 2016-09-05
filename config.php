<?php
   define('SQLSERVER', 'localhost:3036');
   define('SQLUSERNAME', 'root');
   define('SQLPASSWORD', 'rootpassword');
   define('SQLDATABASE', 'database');
   $database = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
   
   // Check connection
   if (!$conn) {
   	die("Connection failed: " . mysqli_connect_error());
   }
   echo "Connected successfully";

?>