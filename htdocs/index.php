<!doctype html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <title>Stardust Server</title>
  </head>

<body>
  <form id="lgform" method="post">
  <h2>Admin Login</h2>
  <div class="mb-3">
    <label for="userNmae" class="form-label">Username</label>
    <input type="username" class="form-control" id="username" name="username" required>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" id="password" name="password" required>
  </div>
  <div class="d-grid gap-2">
  <button type="submit" type="reset" class="btn btn-primary">Submit</button>
  </div>
  <br><br>
  <?php
      require "cred/var.php";
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $pass = $_POST['password'];
        if(Admin_UsrName == $username && Admin_Pass == $pass) {
          echo'<h4 style="color: green;">Login Success !</h4>';
          $LogIn = true;
          session_start();
          $_SESSION['loggedin'] = true;
          header("location: panel.php");
        } else {
          echo'<h4 style="color: red;">Login Error !</h4>';
          $Login = false;
        }
      }   
    ?>
</form>
  <script src="js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>