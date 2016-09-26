<?php
   include('config.php');
   session_start();

   $user_check = $_SESSION['login_user'];

   $sql = "SELECT username FROM users WHERE username = '$user_check'";
   $result = pg_query($database, $sql);

   if (!$result) {
      die("Error in SQL query in session.php: " . pg_last_error());
   }

   $count = pg_num_rows($result);

   if($count != 1) {
     // User is not logged in!
     header("location:login.php");
   }
?>
