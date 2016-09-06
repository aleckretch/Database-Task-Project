<?php
   define('SQLSERVER', 'localhost:5432');
   define('SQLUSERNAME', 'postgres');
   define('SQLPASSWORD', 'sunzhihao');
   define('SQLDATABASE', 'test');

   $database = pg_connect("host=localhost port=5432 dbname=test user=postgres password=sunzhihao")
    or die('Could not connect: ' . pg_last_error());

    
?>