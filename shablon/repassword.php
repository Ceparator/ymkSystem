<?php

require_once("./func.php");
$us=new US;
$status="hidden";
if (isset($_POST["email"])){
    if ($us->rup($_POST["email"])) {
        $status="alert-success";
    } else {
        $status="alert-danger";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Восстановление пароля">
    <meta name="author" content="">
    <!--<link rel="icon" href="./favicon.ico">-->

    <title>Восстановление пароля</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
	  <link href="./css/signin.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">
        <div class="alert <?php echo $status; ?>"><?php echo $us->mes; ?></div>
		<form class="form-signin" method="POST">
        <h2 class="form-signin-heading text-center">Пожалуйста введите E-mail, который Вы указывали при регистрации</h2>
        <label for="inputEmail" class="sr-only">Email</label>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="E-mail" required autofocus/>
                
        <button class="btn btn-lg btn-primary btn-block" type="submit">Восстановить пароль</button>
        
        
      </form>
             
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	  <script src="./js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
