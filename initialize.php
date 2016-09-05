<?php
	include("config.php")
	$init = "CREATE TABLE users (username VARHCAR(255) PRIMARY KEY, password VARCHAR(255))"
	
	$result = mysqli_query($database,$init);

?>