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

  $sql = "SELECT username FROM users WHERE username = '$username' AND type = 'admin'";
  $result = pg_query($database, $sql);
  $count = pg_num_rows($result);
  $isAdmin = false;
  if ($count == 1)
  {
    $isAdmin = true;
  }

  $error_message = null;
  $success = false;

  if(isset($_POST['taskSubmission'])) {
    if(isset($_POST['title']) && isset($_POST['description']) && isset($_POST['date']) && isset($_POST['starttime']) && isset($_POST['endtime'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $date = $_POST['date'];
        $starttime = $_POST['starttime'];
        $endtime = $_POST['endtime'];

        $start_arr = explode(":" , $starttime);
        $end_arr = explode(":" , $endtime);

        $start_hour = intval($start_arr[0]);
        $start_min = intval($start_arr[1]);
        $end_hour = intval($end_arr[0]);
        $end_min = intval($end_arr[1]);

        $sql = "INSERT INTO tasks (title , description , task_date , start_hour , start_min, end_hour, end_min, owner) VALUES('$title' , '$description' , '$date', $start_hour, $start_min , $end_hour , $end_min , '$username')";

        // echo $sql;

        $result = pg_query($database, $sql);

        if (!$result) {
            die("Error in SQL query: " . pg_last_error());
        }

        $success = true;
    } else {
      $error_message = "Info missed";
    }
    
  }
 ?>

 <html>
<head> <title>Task management system</title> </head>
<link href="css/bootstrap.min.css" rel="stylesheet" />
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
    <h3>Create Your new Task</h3>
  </div>

  <?php 
    if($success) {
      echo '<div class="alert fade in  alert-success">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  Task created!
            </div>';
    } else if($error_message != null) {
      echo '<div class="alert fade in  alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  '.$error_message.'
            </div>';
    }
  
  ?>

  <form method="POST" action="newTask.php" >
    <div class="row">
      <div class="form-group">
        <label for="taskTitle">Task Title</label>
        <input type="text" id="taskTitle" name="title" required maxlength="20" class="form-control" />
      </div>

      <div class="form-group">
        <label for="taskDesc">Task Description</label><br/>
        <textarea id="taskDesc" name="description" required class="form-control" placeholder="Please describe your task here"></textarea>
      </div>

      <div class="form-group">
        <label for="taskDate">Task Date</label><br/>
        <input type="date" id="taskDate" name="date" required class="form-control" />
      </div>

      <div class="form-group">
        <label for="taskStartTime">Task Start Time</label><br/>
        <input type="time" id="taskStartTime" name="starttime" required class="form-control" />
      </div>

      <div class="form-group">
        <label for="taskEndTime">Task End Time</label><br/>
        <input type="time" id="taskEndTime" name="endtime" required class="form-control" />
      </div>

      <input type="submit" name="taskSubmission" value="Submit" class="btn btn-success" />
    </div>
  </form>
</div>
</body>
</html>