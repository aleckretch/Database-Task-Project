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
    <li><a href="browse.php">Browse&nbsp</a></li>
    <li><a href="#">Create new Task&nbsp</a></li>
    <li><a href="#">My Tasks&nbsp</a></li>
</ul>

<h3>Create Your new Task</h3>

<form method="POST" action="newTask.php" >
  <label for="taskTitle">Task Title</label>
  <input type="text" id="taskTitle" name="title" required maxlength="20" /><br/>

  <label for="taskDesc">Task Description</label><br/>
  <textarea id="taskDesc" name="description" required>Please fill in your task description</textarea><br/>

  <label for="taskDate">Task Date</label><br/>
  <input type="date" id="taskDate" name="date" required /><br/>

  <input type="submit" name="taskSubmission" value="Submit" />
</form>
</body>
</html>