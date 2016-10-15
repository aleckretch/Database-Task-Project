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

  $today = date("Y-m-d");
  $sql = "SELECT * FROM tasks WHERE assigner IS NULL AND status='pending' AND owner!='$username' AND task_date > '$today' ORDER BY task_date ASC";
  $tasks = pg_query($database, $sql);

  if (!$tasks) {
     die("Tasks owned fetch error: " . pg_last_error());
  }

  if(isset($_POST['searchSubmit'])) {
    $query = "SELECT * FROM tasks WHERE assigner IS NULL AND status='pending' AND owner!='$username'";
    if(isset($_POST['title']))
    {
      $title = $_POST['title'];
      if ($title)
      {
        $query = "$query AND title LIKE '%$title%'";
      }
    }
    if(isset($_POST['owner']))
    {
      $owner = $_POST['owner'];
      if ($owner)
      {
        $query = "$query AND owner LIKE '%$owner%'";
      }
    }
    if(isset($_POST['beforeDate']))
    {
      $beforeDate = $_POST['beforeDate'];
      if ($beforeDate)
      {
        $query = "$query AND task_date < '$beforeDate'";
      }
    }
    if(isset($_POST['afterDate']))
    {
      $afterDate = $_POST['afterDate'];
      if ($afterDate)
      {
        $query = "$query AND task_date > '$afterDate'";
      }
    }
    if(!isset($_POST['expired']))
    {
      $query = "$query AND task_date > '$today'";
    }
    $orderBy = $_POST['orderBy'];
    $orderByString = "";
    if ($orderBy == 0)
    {
      $orderByString = "DESC";
    }
    else
    {
      $orderByString = "ASC";
    }
    $query = "$query ORDER BY task_date $orderByString";

    $filter_success = true;

    $tasks = pg_query($database, $query);

    if (!$tasks) {
       die("Tasks owned fetch error: " . pg_last_error());
    }
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
		jQuery('#task_table').DataTable();
	});
</script>
</head>
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
    <h3>Filter Tasks</h3>
  </div>

  <form method="POST" action="browse.php" >
    <div class="row">
    <div class="form-group">
      <label for="title">Title contains</label>
      <input type="text" id="title" name="title" class="form-control" />
    </div>

    <div class="form-group">
      <label for="owner">Owner contains</label><br/>
      <input type="text" id="owner" name="owner" class="form-control"></input>
    </div>

    <div class="form-group">
      <label for="beforeDate">Before date</label><br/>
      <input type="date" id="beforeDate" name="beforeDate" class="form-control" />
    </div>

    <div class="form-group">
      <label for="afterDate">After date</label><br/>
      <input type="date" id="afterDate" name="afterDate" class="form-control" />
    </div>

    <div class="form-group">
      <label for="expired">Include expired tasks?</label><br/>
      <input type="checkbox" id="expired" name="expired" class="form-control" />
    </div>

    <div class="form-group form-group-sm">
      <label for="expired">Order by date</label><br/>
      Ascending<input type="radio" id="ascending" name="orderBy" value="1" class="form-control" />
      Descending<input type="radio" id="descending" name="orderBy" value="0" class="form-control" />
    </div>

    <input type="submit" name="searchSubmit" value="Search!" class="btn btn-success" />
  </form>

  <div class="row">
    <h3>Open Tasks List</h3>
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

    if($filter_success) {
        echo '<div class="alert fade in  alert-success">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  Filter successful!
            </div>';
    }
  
  ?>

  <table id="task_table" class="table table-borded table-hover">
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