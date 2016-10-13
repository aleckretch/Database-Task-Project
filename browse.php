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

  $myself_claim = false;
  $claim_success = false;

  if($logined_in) {
    if(isset($_POST['claimTask'])) {
      $task_id = $_POST['claimTask'];

      $sql = "SELECT * FROM tasks WHERE id = $task_id";

      $result = pg_query($database, $sql);

      if (!$result) {
         die("Tasks owned fetch error: " . pg_last_error());
      }

      $task = pg_fetch_array($result);

      $task_owner = $task[9];

      if($username == $task_owner) {
        $myself_claim = true;
      } else {
        $sql = "UPDATE tasks SET assigner = '$username' WHERE id = $task_id";

        $result = pg_query($database, $sql);

        if (!$result) {
           die("Database update error: " . pg_last_error());
        }

        $claim_success = true;
      }
    }
  }

  $sql = "SELECT * FROM tasks WHERE assigner IS NULL AND status='pending' AND owner!='$username' ORDER BY task_date ASC";

  $tasks = pg_query($database, $sql);

  if (!$tasks) {
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
        <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="row">
    <h3>Tasks List</h3>
  </div>

  <?php 

    if($claim_success) {
        echo '<div class="alert fade in  alert-success">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  Claim successful!
            </div>';
    }else if($myself_claim) {
      echo '<div class="alert fade in  alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  This is your task!
            </div>';
    }
  
  ?>

  <table class="table table-borded table-hover">
    <thead>
      <th>ID</th>
      <th>Title</th>
      <th>Description</th>
      <th>Date</th>
      <th>Time</th>
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
                    <td>".$row[4].":".$row[5]." - ".$row[6].":".$row[7]."</td>
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