<?php
	include("config.php");

	$clean = "DROP TABLE IF EXISTS tasks";
	$result = pg_query($database, $clean);

	if (!$result) {
		die("Error in SQL query: " . pg_last_error());
	}

	$clean = "DROP TABLE IF EXISTS users";
	$result = pg_query($database, $clean);

	if (!$result) {
		die("Error in SQL query: " . pg_last_error());
	}

	pg_close($dbh);
	echo "Tables deleted successful. Please run the initialize.php to create data"
?>