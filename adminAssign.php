<?php
  //Let's connect to the databse and set everything up
  include("config.php");
  session_start();

  if(!isset($_SESSION['login_user'])){
      header("location:login.php");
   } 

  $username = $_SESSION['login_user'];
  //Let's fetch all the users
  $sql = "SELECT username FROM users WHERE username = '$username' AND type = 'admin'";
  $result = pg_query($database, $sql);
  $count = pg_num_rows($result);
  if ($count != 1)
  {
    header("location:login.php");
  }

  //$tasksql = "SELECT * FROM tasks WHERE assigner IS NOT NULL and status = 'pending'";

  //$tasks = pg_query($database, $tasksql);

  //if (!$tasks) {
  //   die("Tasks owned fetch error: " . pg_last_error());
  //}

?>

