<?php
	include("config.php");
	session_start();

	// Let's process login information, if we have any
   if($_SERVER["REQUEST_METHOD"] == "POST") {

      $username = $_POST['username'];
      $password = $_POST['password'];

      $sql = "SELECT username FROM users WHERE username = '$username' and password = '$password'";

		  $result = pg_query($database, $sql);

			if (!$result) {
				 die("Error in SQL query: " . pg_last_error());
		  }

			// iterate over result set
 		 // print each row
 		 /*while ($row = pg_fetch_array($result)) {
 				 echo "Title: " . $row[0] . "<br />";
 				 echo "Format: " . $row[1] . "<p />";
 		 }*/

		 $count = pg_num_rows($result);

      // We should get only one results, if the login was successfull

      if($count == 1) {
				 echo "MOI";
				 $ver = phpversion();
				 echo "$ver";

				 $_SESSION['login_user'] = $username;
				 echo "Logged in!<br>";
         header("location: index.php");
      }else {
         $error = "Your Login Name or Password is invalid";
      }
   }

	 // free memory
	 pg_free_result($result);

?>

<html>
	<head>
		<title>TaskRabbit Task management system - Login</title>
	</head>
	<body>
		<table align="center" border="1px">
			<tr> <td>
			<h1> <u>Login</u></h1>
			</td></tr>

			<tr>
			<td style="background-color:#eeeeee;">
				<form action = "" method = "post">
				Username: <input type="text" name="username"><br>
				Password: <input type="password" name="password"><br>
				<center><input type="submit" name="formSubmit" value="Submit" ></center>
				</form>
			</td> </tr>
			<?php
				// If we got an error, let's print in
				if ($error != "") {
					echo "<tr><td>";
					echo $error;
					echo "</td></tr>";
				}
			?>
		</table>

	</body>
</html>
