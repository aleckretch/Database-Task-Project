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

  if(isset($_POST['assignTask']))
  {
    $task_id = $_POST['assignTask'];

    $sql = "SELECT * FROM tasks WHERE id = $task_id";

    $result = pg_query($database, $sql);

    if (!$result)
    {
      die("Tasks owned fetch error: " . pg_last_error());
    }

    $task = pg_fetch_array($result);

    $task_assigner = $task["assigner"];

    $sql = "UPDATE tasks SET assigner = '$task_assigner', status = 'approved', assigned = 'TRUE' WHERE id = $task_id";

    $result = pg_query($database, $sql);

    if (!$result)
    {
      die("Database update error: " . pg_last_error());
    }

    $assign_success = true;
  }

  if(isset($_POST['rejectTask']))
  {
    $task_id = $_POST['rejectTask'];

    $sql = "SELECT * FROM tasks WHERE id = $task_id";

    $result = pg_query($database, $sql);

    if (!$result)
    {
      die("Tasks owned fetch error: " . pg_last_error());
    }

    $task = pg_fetch_array($result);

    $task_assigner = $task["assigner"];

    $sql = "UPDATE tasks SET assigner = NULL, status = 'pending' WHERE id = $task_id";

    $result = pg_query($database, $sql);

    if (!$result)
    {
      die("Database update error: " . pg_last_error());
    }

    $reject_success = true;
  }

  if(isset($_POST['deleteTask']))
  {
    $task_id = $_POST['deleteTask'];

    $sql = "SELECT * FROM tasks WHERE id = $task_id";

    $result = pg_query($database, $sql);

    if (!$result)
    {
      die("Tasks owned fetch error: " . pg_last_error());
    }

    $task = pg_fetch_array($result);

    $task_assigner = $task["assigner"];

    $sql = "UPDATE tasks SET assigner = NULL, status = 'disapproved' WHERE id = $task_id";

    $result = pg_query($database, $sql);

    if (!$result)
    {
      die("Database update error: " . pg_last_error());
    }

    $delete_success = true;
  }

  $tasksql = "SELECT * FROM tasks WHERE assigner IS NOT NULL and status = 'pending'";
  $taskresult = pg_query($database, $tasksql);
  $taskcount = pg_num_rows($taskresult);

?>

<html>
<head> <title>Task management system</title> </head>
<link href="css/bootstrap.min.css" rel="stylesheet">

<body>
<div class="container">

  <div class="row">
    <ul class="nav navbar-nav">
         <li><a href="index.php">Home</a></li>
        <li><a href="adminAssign.php">Assign Tasks</a></li>
        <li><a href="browse.php">Browse</a></li>
        <li><a href="newTask.php">Create new Task</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="row">
    <h3>Tasks List</h3>
  </div>

  <?php 

    if($assign_success) {
        echo '<div class="alert fade in  alert-success">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  Assign successful!
            </div>';
    }

    if($reject_success) {
        echo '<div class="alert fade in  alert-success">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  Reject successful!
            </div>';
    }

    if($delete_success) {
        echo '<div class="alert fade in  alert-success">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  Delete successful!
            </div>';
    }
  
  ?>

  <table class="table table-borded table-hover">
    <thead>
      <th>ID</th>
      <th>Title</th>
      <th>Description</th>
      <th>Date</th>
      <th>Owner</th>
      <th>Requester</th>
      <th>Assign The Task</th>
      <th>Delete The Task</th>
    </thead>
    <tbody>
      <form action="adminAssign.php" method="POST">
      <?php
        if ($taskcount > 0)
        {
          while ($row = pg_fetch_array($taskresult)) {
            echo "<tr>
              <td>".$row[0]."</td>
              <td>".$row[1]."</td>
              <td>".$row[2]."</td>
              <td>".$row[3]."</td>
              <td>".$row[9]."</td>
              <td>".$row["assigner"]."</td>
              <td><button type='submit' name='assignTask' class='btn btn-success' value='".$row[0]."'>Approve</button> 
              <button type='submit' name='rejectTask' class='btn btn-warning' value='".$row[0]."'>Reject</button></td>
              <td><button type='submit' name='deleteTask' class='btn btn-danger' value='".$row[0]."'>Delete</button></td>
              </tr>";
          }
        }
      ?>
    </form>
    </tbody>
  </table>
</div>

</body>
</html>