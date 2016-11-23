<?php
session_start(); 

if(array_key_exists('userLogged', $_SESSION)){
  header("Location: enter-price.php");
}

$errorMessage = '';
if ( !empty($_POST)) {
  require 'database.php'; 
  $conn = Database::connect();

  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password' AND groupid = 9";
  $results = mysqli_query($conn, $sql);
  
  if ($results->num_rows > 0) {
    $_SESSION['userLogged'] = $results->fetch_array();
    header('Location: enter-price.php');
  } else {
    $errorMessage = "Username or password is not correct!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Pip Rebate Report</title>

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/signin.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">

      <form class="form-signin" method="POST">
        <?php if($errorMessage != '') { ?>
        <p class="bg-danger" style="padding: 10px;"><?php echo $errorMessage ?></p>
        <?php } ?>
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="inputEmail" class="sr-only">Username</label>
        <input type="text" id="inputEmail" name="username" class="form-control" placeholder="Username" required="" autofocus="">
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required="">
        <!-- <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div> -->
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

    </div> <!-- /container -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>