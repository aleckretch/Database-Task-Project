<?php
	include("config.php");
	session_start();

	// Let's process login information, if we have any
   if($_SERVER["REQUEST_METHOD"] == "POST") {

      $username = $_POST['username'];
      $password = $_POST['password'];

      $sql = "SELECT u.username, u.password, u.salt FROM users u WHERE u.username = '$username'";

		  $result = pg_query($database, $sql);

			if (!$result) {
				 die("Error in SQL query in login.php: " . pg_last_error());
		  }

			// iterate over result set
 		 // print each row
 		 /*while ($row = pg_fetch_array($result)) {
 				 echo "Title: " . $row[0] . "<br />";
 				 echo "Format: " . $row[1] . "<p />";
 		 }*/
	  // We should get only one results, if the username exists.
      $count = pg_num_rows($result);
	  if($count == 1) {
		$row = pg_fetch_row($result);
	    $salt = $row[2];
	    $saltedPassword = md5($password . $salt);
		
		if($saltedPassword == $row[1]) {
		   // Login was successfull!
		   $_SESSION['login_user'] = $username;
           header("location: index.php");
		}else {
		  $error = "Your Login Name or Password is invalid";
		}
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
		<link href="css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">

		<table class="table">
			<tr> <td>
			<center><h1>Login</h1></center>
			</td></tr>

			<tr>
			<td>
				<form action = "<?php echo basename($_SERVER['PHP_SELF']); ?>" method = "post">
					<div class="row">
			      <div class="form-group">
			        <label for="username">Username</label>
			        <input type="text" id="username" name="username" required maxlength="20" class="form-control" />
			      </div>

			      <div class="form-group">
			        <label for="password">Password</label><br/>
			        <input type="password" id="password" name="password" required class="form-control"></input>
			      </div>

			      <input type="submit" name="formSubmit" value="Submit" class="btn btn-success" />
				</form>
			</td> </tr>
			<tr><td align="center">Click <a href="register.php">here</a> to register.</td></tr>
			<?php
				// If we got a message, let's print it
				if ($_GET['message'] != "") {
					echo "<tr><td><center>";
					echo $_GET['message'];
					echo "</center></td></tr>";
				}
				// If we got an error, let's print it
				if ($error != "") {
					echo "<tr><td><center>";
					echo $error;
					echo "</center></td></tr>";
				}
			?>
		</table>
	</div>
	</body>
</html>
