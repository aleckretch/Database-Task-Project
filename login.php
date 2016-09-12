<?php
	include("config.php")
	session_start()

	// Let's process login information, if we have any
   if($_SERVER["REQUEST_METHOD"] == "POST") {

      $username = mysqli_real_escape_string($db,$_POST['username']);
      $password = mysqli_real_escape_string($db,$_POST['password']);

      $sql = "SELECT username FROM users WHERE username = '$username' and passcode = '$password'";
      $result = mysqli_query($database,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $active = $row['active'];

      $count = mysqli_num_rows($result);

      // We should get only one results, if the login was successfull

      if($count == 1) {
         session_register("username");
         $_SESSION['login_user'] = $username;

         header("location: index.php");
         $error = ""
      }else {
         $error = "Your Login Name or Password is invalid";
      }
   }
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
				<form action = "" method = "post">>
				Username: <input type="text" name="username"><br>
				Password: <input type="text" name="password"><br>
				<center><input type="submit" name="formSubmit" value="Submit" ></center>
				</form>
			</td> </tr>
			<?php
				// If we got an error, let's print in
				if ($error == "") {
					echo "<tr><td>";
					echo $error;
					echo "</td></tr>";
				}
			?>
		</table>

	</body>
</html>
