<?php
	include("config.php");

	// Let's process registration information, if we have any
   if($_SERVER["REQUEST_METHOD"] == "POST") {

      $username = $_POST['username'];
      $password = $_POST['password'];

      // Let's see if the username is available
      $sql = "SELECT username FROM users WHERE username = '$username'";

		  $result = pg_query($database, $sql);

			if (!$result) {
				 die("Error in SQL query: " . pg_last_error());
		  }

		 $count = pg_num_rows($result);

      // If we get results, the username is taken!

      if($count != 0) {
				 // The username is already taken.
         $error = "Username is already taken!";
      }else {
         // Let's create the user!
         $sql = "INSERT INTO users VALUES ('$username', '$password', 'normal')";

   		   $result = pg_query($database, $sql);

   			 if (!$result) {
   			 	 die("Error in SQL query: " . pg_last_error());
   		   }

         $error = "User created!";
      }
   }

	 // free memory
	 pg_free_result($result);

?>


<html>
	<head>
		<title>TaskRabbit Task management system - Login</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
		<table align="center" class="table">
			<tr> <td>
			<h1> <u>Registration</u></h1>
			</td></tr>

			<tr>
			<td>
				<form action = "" method = "post">
					<div class="row">
			      <div class="form-group">
			        <label for="username">Username</label>
			        <input type="text" id="username" name="username" required maxlength="20" class="form-control" />
			      </div>

			      <div class="form-group">
			        <label for="password">Password</label><br/>
			        <input type="password" id="password" name="password" required class="form-control"></input>
			      </div>

			      <input type="submit" name="formSubmit" value="Register" class="btn btn-success" />
				</form>
			</td> </tr>
			<tr><td align="center">Click <a href="login.php">here</a> to return to login screen.</td></tr>
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
