<?php
	include("config.php");
	$init = "SELECT * FROM book.book";
	
	$result = pg_query($database,$init);

	if (!$result) {
     die("Error in SQL query: " . pg_last_error());
 }       

 // iterate over result set
 // print each row
 while ($row = pg_fetch_array($result)) {
     echo "Title: " . $row[0] . "<br />";
     echo "Format: " . $row[1] . "<p />";
 }      

  // free memory
 pg_free_result($result);       

 // close connection
 pg_close($dbh);  
?>