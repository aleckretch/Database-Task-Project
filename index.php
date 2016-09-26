<?php
  //Let's connect to the databse and set everything up
  include("config.php");
  session_start();

  if(!isset($_SESSION['login_user'])){
      header("location:login.php");
   }

  $username = $_SESSION['login_user'];
  //Let's fetch all the users
  $sql = "SELECT username, password FROM users WHERE username = '$username'";
  $result = pg_query($database, $sql);

  if (!$result) {
     die("Error in SQL query: " . pg_last_error());
  }

  $count = pg_num_rows($result);

  if($count != 1) {
    header("location:login.php");
  }

  $sql = "SELECT * FROM tasks WHERE owner = '$username'";

  $tasks_owned = pg_query($database, $sql);

  if (!$result) {
     die("Tasks owned fetch error: " . pg_last_error());
  }

  $sql = "SELECT * FROM tasks WHERE assigner = '$username'";

  $tasks_assigned = pg_query($database, $sql);

  if (!$result) {
     die("Tasks assigned fetch error: " . pg_last_error());
  }
 ?>

<html>
<head> <title>Task management system</title> </head>
<body>

<h1>Welcome to Task management system, <?php echo "$username"; ?>!</h1>
<h3>Tasks Your issued</h3>

<table>
  <thead>
    <th>ID</th>
    <th>Title</th>
    <th>Description</th>
    <th>Date</th>
    <th>Time</th>
  </thead>
  <tbody>
    <?php
      while ($row = pg_fetch_array($tasks_owned)) {
           echo "<tr>
                <td>".$row[0]."</td>
                <td>".$row[1]."</td>
                <td>".$row[2]."</td>
                <td>".$row[3]."</td>
           </tr>";
       }

    ?>
  </tbody>
</table>

<a href="logout.php">Logout</a>

</body>
</html>
