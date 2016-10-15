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

  $sql = "SELECT username FROM users WHERE username = '$username' AND type = 'admin'";
  $result = pg_query($database, $sql);
  $count = pg_num_rows($result);
  $isAdmin = false;
  if ($count == 1)
  {
    $isAdmin = true;
  }

  if(isset($_POST['completedTask']))
  {
    $task_id = $_POST['completedTask'];

    $sql = "SELECT * FROM tasks WHERE id = $task_id";

    $result = pg_query($database, $sql);

    if (!$result)
    {
      die("Tasks owned fetch error: " . pg_last_error());
    }

    $task = pg_fetch_array($result);

    $sql = "UPDATE tasks SET status = 'completed' WHERE id = $task_id";

    $result = pg_query($database, $sql);

    if (!$result)
    {
      die("Database update error: " . pg_last_error());
    }

    $completed_success = true;
  }

  if(isset($_POST['unclaimTask']))
  {
    $task_id = $_POST['unclaimTask'];

    $sql = "SELECT * FROM tasks WHERE id = $task_id";

    $result = pg_query($database, $sql);

    if (!$result)
    {
      die("Tasks owned fetch error: " . pg_last_error());
    }

    $task = pg_fetch_array($result);

    $sql = "UPDATE tasks SET assigner = NULL, status = 'pending', assigned = 'FALSE' WHERE id = $task_id";

    $result = pg_query($database, $sql);

    if (!$result)
    {
      die("Database update error: " . pg_last_error());
    }

    $unclaim_success = true;
  }

  $sql = "SELECT * FROM tasks WHERE owner = '$username' ORDER BY task_date ASC";

  $tasks_owned = pg_query($database, $sql);

  if (!$result) {
     die("Tasks owned fetch error in index.php: " . pg_last_error());
  }

  $sql = "SELECT * FROM tasks WHERE assigner = '$username' AND status = 'approved' OR assigner = '$username' AND status = 'completed' ORDER BY task_date ASC";

  $tasks_assigned = pg_query($database, $sql);

  if (!$result) {
     die("Tasks assigned fetch error in index.php: " . pg_last_error());
  }

  $sql = "SELECT * FROM tasks WHERE assigner = '$username' AND status = 'pending' ORDER BY task_date ASC";

  $tasks_claimed = pg_query($database, $sql);

  if (!$result) {
     die("Tasks claimed fetch error in index.php: " . pg_last_error());
  }
 ?>

<html>
<head> <title>Task management system</title> 
<link href="css/bootstrap.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs-3.3.6/dt-1.10.12/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdn.datatables.net/v/bs-3.3.6/dt-1.10.12/datatables.min.js"></script>

<script>
	jQuery.noConflict();
	jQuery(document).ready(function() {
		jQuery('#task_owned_table').DataTable();
		jQuery('#task_assigned_table').DataTable();
		jQuery('#task_claimed_table').DataTable();
	});
</script>


</head>

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

  <h1>Welcome to Task management system, <?php echo "$username"; ?>!</h1>

  <div class="row">
    <h3>Tasks Owned</h3>
  </div>

  <table id="task_owned_table" class="table table-borded table-hover">
    <thead>
      <th>ID</th>
      <th>Title</th>
      <th>Description</th>
      <th>Date</th>
      <th>Time</th>
      <th>Status</th>
    </thead>
    <tbody>
      <?php
        while ($row = pg_fetch_array($tasks_owned)) {
             echo "<tr>
                  <td>".$row[0]."</td>
                  <td>".$row[1]."</td>
                  <td>".$row[2]."</td>
                  <td>".$row[3]."</td>
                  <td>".$row[4].":".$row[5]." - ".$row[6].":".$row[7]."</td>";
            if ($row['status'] == "disapproved")
            {
              echo "<td>Admin Rejected</td>";
            }
            else if ($row['status'] == "pending" && !$row["assigner"])
            {
              echo "<td>Pending Claim</td>";
            }
            else if ($row['status'] == "pending" && $row["assigner"])
            {
              echo "<td>Pending Admin Approval</td>";
            }
            else if ($row['status'] == "approved")
            {
              echo "<td>Assigned to ".$row["assigner"]."</td>";
            }
            else if ($row['status'] == "completed")
            {
              echo "<td>Completed by ".$row["assigner"]."</td>";
            }
            echo "</tr>";
         }
      ?>
    </tbody>
  </table>

  <div class="row">
    <h3>Tasks Assigned</h3>
  </div>

  <?php 

    if($completed_success) {
        echo '<div class="alert fade in  alert-success">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  Marked as completed successful!
            </div>';
    }

    if($unclaim_success) {
        echo '<div class="alert fade in  alert-success">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  Unclaim successful!
            </div>';
    }
  
  ?>

  <table id="task_assigned_table" class="table table-borded table-hover">
    <thead>
      <th>ID</th>
      <th>Title</th>
      <th>Description</th>
      <th>Date</th>
      <th>Time</th>
      <th>Owner</th>
      <th>Status</th>
    </thead>
    <tbody>
      <form action="index.php" method="POST">
      <?php
        while ($row = pg_fetch_array($tasks_assigned)) {
			$startMin = $row[5]; 
			if($startMin==0){
				$startMin = "00";
			}
			$endMin = $row[7];
			if($endMin == 0){
				$endMin = "00";
			}
             echo "<tr>
                  <td>".$row[0]."</td>
                  <td>".$row[1]."</td>
                  <td>".$row[2]."</td>
                  <td>".$row[3]."</td>
                  <td>".$row[4].":".$startMin." - ".$row[6].":".$endMin."</td>
                  <td>".$row[9]."</td>";
              if ($row['status'] == "completed")
              {
                echo "<td>Completed</td>";
              }
              else
              {
                echo "<td><button type='submit' name='completedTask' class='btn btn-success' value='".$row[0]."'>Completed</button> 
              <button type='submit' name='unclaimTask' class='btn btn-danger' value='".$row[0]."'>Unclaim</button></td>";
              }
             echo "</tr>";
         }
      ?>
    </tbody>
  </table>

  <div class="row">
    <h3>Tasks Claimed</h3>
  </div>

  <table id="task_claimed_table" class="table table-borded table-hover">
    <thead>
      <th>ID</th>
      <th>Title</th>
      <th>Description</th>
      <th>Date</th>
      <th>Time</th>
      <th>Owner</th>
    </thead>
    <tbody>
      <?php
        while ($row = pg_fetch_array($tasks_claimed)) {
			$startMin = $row[5]; 
			if($startMin==0){
				$startMin = "00";
			}
			$endMin = $row[7];
			if($endMin == 0){
				$endMin = "00";
			}
             echo "<tr>
                  <td>".$row[0]."</td>
                  <td>".$row[1]."</td>
                  <td>".$row[2]."</td>
                  <td>".$row[3]."</td>
                  <td>".$row[4].":".$startMin." - ".$row[6].":".$endMin."</td>
                  <td>".$row[9]."</td>
             </tr>";
         }
      ?>
    </tbody>
  </table>
</div>

</body>
</html>
