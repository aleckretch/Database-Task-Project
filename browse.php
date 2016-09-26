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

  if($logined_in) {
    if(isset($_POST['claimTask'])) {
      $task_id = $_POST['claimTask'];

      $sql = "SELECT * FROM tasks WHERE id = $task_id";

      $result = pg_query($database, $sql);

      if (!$result) {
         die("Tasks owned fetch error: " . pg_last_error());
      }

      $task = pg_fetch_array($result);

      
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
<link href="css/bootstrap.min.css" rel="stylesheet">

<body>
<div class="container">

  <div class="row">
    <ul class="nav navbar-nav">
         <li><a href="index.php">Home</a></li>
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
      <th>Claim The Task</th>
    </thead>
    <tbody>
      <form action="browse.php" method="POST">
      <?php
        while ($row = pg_fetch_array($tasks)) {
             echo "<tr>
                    <td>".$row[0]."</td>
                    <td>".$row[1]."</td>
                    <td>".$row[2]."</td>
                    <td>".$row[3]."</td>
                    <td>".$row[9]."</td>
                    <td><button type='submit' name='claimTask' class='btn btn-success' value='".$row[0]."'>Claim</button></td>
             </tr>";
         }

      ?>
    </form>
    </tbody>
  </table>
</div>

</body>
</html>