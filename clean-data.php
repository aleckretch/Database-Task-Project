<?php
	include("config.php");

	$clean = "DROP TABLE users";
	$result = pg_query($database, $clean);

	if (!$result) {
		die("Error in SQL query: " . pg_last_error());
	}

	$clean = "DROP TABLE tasks";
	$result = pg_query($database, $clean);

	if (!$result) {
		die("Error in SQL query: " . pg_last_error());
	}

	pg_close($dbh);
?>