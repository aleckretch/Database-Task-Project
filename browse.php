<?php
  //Let's connect to the databse and set everything up
  include("config.php");
  session_start();

  $logined_in = true;
  $username = "";

  if(!isset($_SESSION['login_user'])){
      $logined_in  = false;
  } else {
    $username = $_SESSION['login_user'];
  } 

  if($logined_in) {
    //Let's fetch all the users
    $sql = "SELECT username, password FROM users WHERE username = '$username'";
    $result = pg_query($database, $sql);

    if (!$result) {
       die("Error in SQL query: " . pg_last_error());
    }

    $count = pg_num_rows($result);

    if($count != 1) {
      $logined_in = false;
      $username = "";
    }
  }

  $sql = "SELECT * FROM tasks WHERE assigner IS NULL";

  $tasks = pg_query($database, $sql);

  if (!$result) {
     die("Tasks owned fetch error: " . pg_last_error());
  }
 ?>

<html>
<head> <title>Task management system</title> </head>
<style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}

li {
    display: inline;
}
</style>
<body>

<ul>
    <li><a href="index.php">Home&nbsp</a></li>
    <li><a href="#">Browse&nbsp</a></li>
    <li><a href="newTask.php">Create new Task&nbsp</a></li>
    <li><a href="#">My Tasks&nbsp</a></li>
</ul>

<h3>Tasks List</h3>

<table>
  <thead>
    <th>ID</th>
    <th>Title</th>
    <th>Description</th>
    <th>Date</th>
    <th>Owner</th>
    <th>View Detail</th>
  </thead>
  <tbody>
    <?php
      while ($row = pg_fetch_array($tasks)) {
           echo "<tr>
                <td>".$row[0]."</td>
                <td>".$row[1]."</td>
                <td>".$row[2]."</td>
                <td>".$row[3]."</td>
                <td>".$row[9]."</td>
                <td><a href='detail.php?task=".$row[0]."'>Detail</a></td>
           </tr>";
       }

    ?>
  </tbody>
</table>

</body>
</html>