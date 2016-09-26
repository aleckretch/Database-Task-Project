<?php
	include("config.php");

  // Let's initialize the tables used, and greate a test user.
	$init = "CREATE TABLE IF NOT EXISTS users (username VARCHAR(254) PRIMARY KEY, password VARCHAR(254), type VARCHAR(6) CHECK(type='normal' OR type='admin'))";
	$result = pg_query($database, $init);

	if (!$result) {
     die("Error in SQL query: " . pg_last_error());
  	}

  	$init = "CREATE TABLE IF NOT EXISTS tasks (id integer SERIAL PRIMARY KEY, title VARCHAR(40) NOT NULL, 
  		description VARCHAR(254) NOT NULL default '', 
  		task_date date NOT NULL default CURRENT_DATE, 
  		start_hour integer NOT NULL CHECK (start_hour >= 0 AND start_hour < 24), start_min integer NOT NULL CHECK (start_min >= 0 AND start_min < 60), 
  		end_hour integer NOT NULL CHECK (end_hour >= 0 AND end_hour < 24), end_min integer NOT NULL CHECK (end_min >= 0 AND end_min < 60), 
  		assigner VARCHAR(254) REFERENCES users, owner VARCHAR(254) REFERENCES users NOT NULL )";
	
	$result = pg_query($database, $init);

	if (!$result) {
     die("Error in SQL query: " . pg_last_error());
  	}

	$sql = "INSERT INTO users VALUES ('Mark', '1234', 'normal'), ('Lisa', 'abcd', 'normal')";
	$result = pg_query($database, $sql);

	if (!$result) {
      die("Error in SQL query: " . pg_last_error());
 	}

    echo 'init successful';
  }

 // close connection
 pg_close($dbh);
?>
