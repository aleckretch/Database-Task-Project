<?php
	include("config.php");

  // Let's initialize the tables used, and greate a test user.
	$init = "CREATE TABLE users (username VARCHAR(254) PRIMARY KEY, password VARCHAR(254))";
	$result = pg_query($database, $init);

	if (!$result) {
     die("Error in SQL query: " . pg_last_error());
  }

	$init = "INSERT INTO users VALUES ('Mark', '1234')";
	$result = pg_query($database, $init);

	if (!$result) {
     die("Error in SQL query: " . pg_last_error());
  }

 // close connection
 pg_close($dbh);
?>
