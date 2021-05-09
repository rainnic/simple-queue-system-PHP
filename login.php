<?php
    session_start();
    echo isset($_SESSION['login']);
    if(isset($_SESSION['login'])) {
      header('Location: controller.php'); die();
    }
?>
<!DOCTYPE html>
<html>
   <head>
     <meta http-equiv='content-type' content='text/html;charset=utf-8' />
     <title>Login</title>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   </head>
<body>
  <div class="container">
    <h3 class="text-center">Login</h3>
    <?php
      if(isset($_POST['submit'])){
        $password = $_POST['password'];
        if($password === 'password'){
          $_SESSION['login'] = true; header('Location: controller.php'); die();
        } {
          echo "<div class='alert alert-danger'>Password sbagliata.</div>";
        }
        
      }
    ?>
    <form action="" method="post">
      <div class="form-group">
        <label for="pwd">Password:</label>
        <input type="password" class="form-control" id="pwd" name="password" required>
      </div>
      <button type="submit" name="submit" class="btn btn-default">Login</button>
    </form>
  </div>
</body>
</html>