<?php

require_once("./func.php");
$us=new US;
if ($us->auth($_POST["email"],$_POST["password"])){
    header("Location: ./index.php");
    } else {

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Авторизируйтесь пожалуйста">
    <meta name="author" content="">
    <!--<link rel="icon" href="./favicon.ico">-->

    <title>Авторизация</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
	  <link href="./css/signin.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">

		<form class="form-signin" action="./login.php" method="POST">
        <h2 class="form-signin-heading text-center">Пожалуйста авторизируйтесь</h2>
        <div class="alert alert-danger"
<?php
if (isset($_POST["email"])) {
    echo "style='display:block;'";
} else {
    echo "style='display:none;'";
}       
?>        
        
         role="alert">
          Логин или пароль введены неверно!
        </div>
        <label for="inputEmail" class="sr-only">Email</label>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email" required autofocus>
        <label for="inputPassword" class="sr-only">Пароль</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Пароль" required>
        
        <button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
        
        
      </form>
       
        
        
        <div class="text-center">
        <a href="./index.php"><strong>Вернуться на сайт</strong></a>
        <br />
        <a href="./registration.php">Зарегистрироваться</a>
        <br />
        <a href="./repassword.php">Восстановление пароля</a>
        </div>       
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	  <script src="./js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
<?php
}
?>