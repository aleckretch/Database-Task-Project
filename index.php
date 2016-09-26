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
     die("Error in SQL query in index.php: " . pg_last_error());
  }

  $count = pg_num_rows($result);

  if($count != 1) {
    header("location:login.php");
  }

  $sql = "SELECT * FROM tasks WHERE owner = '$username'";

  $tasks_owned = pg_query($database, $sql);

  if (!$result) {
     die("Tasks owned fetch error in index.php: " . pg_last_error());
  }

  $sql = "SELECT * FROM tasks WHERE assigner = '$username'";

  $tasks_assigned = pg_query($database, $sql);

  if (!$result) {
     die("Tasks assigned fetch error in index.php: " . pg_last_error());
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

  <h1>Welcome to Task management system, <?php echo "$username"; ?>!</h1>
  
  <div class="row">
    <h3>Tasks Your issued</h3>
  </div>


  <table class="table table-borded table-hover">
    <thead>
      <th>ID</th>
      <th>Title</th>
      <th>Description</th>
      <th>Date</th>
      <th>Time</th>
      <th>Assign To</th>
    </thead>
    <tbody>
      <?php
        while ($row = pg_fetch_array($tasks_owned)) {

          $assign_to = $row[8];

          if(!$assign_to) {
            $assign_to = "Not assigned";
          }

             echo "<tr>
                  <td>".$row[0]."</td>
                  <td>".$row[1]."</td>
                  <td>".$row[2]."</td>
                  <td>".$row[3]."</td>
                  <td>".$row[4].":".$row[5]." - ".$row[6].":".$row[7]."</td>
                  <td>$assign_to</td>
             </tr>";
         }

      ?>
    </tbody>
  </table>

  <div class="row">
    <h3>Tasks Your Claimed</h3>
  </div>

  <table class="table table-borded table-hover">
    <thead>
      <th>ID</th>
      <th>Title</th>
      <th>Description</th>
      <th>Date</th>
      <th>Time</th>
    </thead>
    <tbody>
      <?php
        while ($row = pg_fetch_array($tasks_assigned)) {
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
</div>

</body>
</html>
