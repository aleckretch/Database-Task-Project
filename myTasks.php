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

  $sql = "SELECT username FROM users WHERE username = '$username' AND type = 'admin'";
  $result = pg_query($database, $sql);
  $count = pg_num_rows($result);
  $isAdmin = false;
  if ($count == 1)
  {
    $isAdmin = true;
  }

  $sql = "SELECT * FROM tasks t WHERE owner = '$username' OR assigner = '$username'";

  $tasks = pg_query($database, $sql);

  if (!$result) {
     die("Tasks owned fetch error: " . pg_last_error());
  }
 ?>

<html>
<head> <title>Task management system</title> </head>
<link href="css/bootstrap.min.css" rel="stylesheet">

<body>
<div class="container">

  <div class="row">
    <ul class="nav navbar-nav">
         <li><a href="index.php">Home</a></li>
         <?php
          if($isAdmin) {
            echo '<li><a href="adminAssign.php">Assign Tasks</a></li>';
          }
        ?>
        <li><a href="browse.php">Browse</a></li>
        <li><a href="newTask.php">Create new Task</a></li>
        <li><a href="myTasks.php">My Tasks</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="row">
    <h3>Tasks List</h3>
  </div>

  <table class="table table-borded table-hover">
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
</div>

</body>
</html>