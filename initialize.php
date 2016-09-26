<?php
	include("config.php");

  // Let's initialize the tables used, and greate a test user.
	$init = "CREATE TABLE IF NOT EXISTS users (username VARCHAR(254) PRIMARY KEY, password VARCHAR(254), salt CHAR(32), type VARCHAR(6) CHECK(type='normal' OR type='admin'))";
	$result = pg_query($database, $init);

	if (!$result) {
     die("Error in SQL query in initialize.php: " . pg_last_error());
  	}

  	$init = "CREATE TABLE IF NOT EXISTS tasks (id SERIAL PRIMARY KEY, title VARCHAR(40) NOT NULL, 
  		description VARCHAR(254) NOT NULL default '', 
  		task_date date NOT NULL default CURRENT_DATE, 
  		start_hour integer NOT NULL CHECK (start_hour >= 0 AND start_hour < 24), start_min integer NOT NULL CHECK (start_min >= 0 AND start_min < 60), 
  		end_hour integer NOT NULL CHECK (end_hour >= 0 AND end_hour < 24), end_min integer NOT NULL CHECK (end_min >= 0 AND end_min < 60), 
  		assigner VARCHAR(254) REFERENCES users, owner VARCHAR(254) REFERENCES users NOT NULL )";
	
	$result = pg_query($database, $init);

	if (!$result) {
     die("Error in SQL query in initialize.php: " . pg_last_error());
  	}

	$sql = "INSERT INTO users VALUES ('Mark', 'e3ce2f895ecde88c9a0ca3cfbab42644', '2f4c37ee681b2811440145f620f7b449', 'normal'), ('Lisa', '1f25376ecddf5bb9ee77573de2886e42', '4cf6fdafe5ab4f5401f682d147bfee80', 'normal'), ('admin', 'b6a7c7264267ceb82adb9e2c84d49edb', '72c2bb70b9062f123e21e34eac743f88', 'admin')";//Mark Password = 1234, Lisa = abcd, admin = password
	$result = pg_query($database, $sql);

	if (!$result) {
      die("Error in SQL query in initialize.php: " . pg_last_error());
 	}

 	$sql = "INSERT INTO tasks (title , description , task_date , start_hour , start_min, end_hour, end_min, owner) VALUES('Second Task' , 'Second task to show.' , '2016-10-04', 8, 30 , 10 , 30 , 'Mark')
,('Third Task' , 'Third task to show.' , '2016-10-05', 9, 30 , 11 , 30 , 'Mark')
";
	$result = pg_query($database, $sql);

	if (!$result) {
      die("Error in SQL query in initialize.php: " . pg_last_error());
 	}

    echo 'init successful';


 // close connection
 pg_close($dbh);
?>
