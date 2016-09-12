<?php
  //Let's connect to the databse and set everything up
  include("config.php");
 ?>

<html>
<head> <title>Task management system</title> </head>
<body>

<table align="center" border="1px">
<tr> <td>
<h1> <u>Login</u></h1>
</td></tr>
<?php
  //Let's fetch all the users
  $sql = "SELECT username, password FROM users";
  $result = pg_query($database, $sql);

  if (!$result) {
     die("Error in SQL query: " . pg_last_error());
  }
  echo "moi";
  // iterate over result set
 // print each row
 while ($row = pg_fetch_array($result)) {
     echo "<tr><td> Username: $row[0] </td>";
     echo "<td> Password: $row[1] </td></tr>";
 }

 ?>
</table>

</body>
</html>
