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

  if(isset($_POST['taskSubmission'])) {

  }
 ?>

 <html>
<head> <title>Task management system</title> </head>
<link href="css/bootstrap.min.css" rel="stylesheet" />
<body>

<div class="row">
  <ul class="nav navbar-nav">
       <li><a href="index.php">Home</a></li>
      <li><a href="browse.php">Browse</a></li>
      <li><a href="newTask.php">Create new Task</a></li>
      <li><a href="#">My Tasks</a></li>
      <li><a href="logout.php">Logout</a></li>
  </ul>
</div>

<div class="container">
  <div class="row">
    <h3>Create Your new Task</h3>
  </div>

  <form method="POST" action="newTask.php" >
    <div class="row">
      <div class="form-group">
        <label for="taskTitle">Task Title</label>
        <input type="text" id="taskTitle" name="title" required maxlength="20" class="form-control" />
      </div>

      <div class="form-group">
        <label for="taskDesc">Task Description</label><br/>
        <textarea id="taskDesc" name="description" required class="form-control">Please fill in your task description</textarea>
      </div>

      <div class="form-group">
        <label for="taskDate">Task Date</label><br/>
        <input type="date" id="taskDate" name="date" required class="form-control" />
      </div>

      <input type="submit" name="taskSubmission" value="Submit" class="btn btn-success" />
    </div>
  </form>
</div>
</body>
</html>