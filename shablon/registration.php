<?php

require_once("./func.php");

$us=new US;

//registering new user
if (isset($_POST["g-recaptcha-response"])){
    
  $privatekey = "6LeL0TMUAAAAAGSeQZfvNhraAKBT2sPvW_uiMrYb";
  $url = 'https://www.google.com/recaptcha/api/siteverify';
$params = array(
    'secret' => $privatekey, 
    'response' => $_POST["g-recaptcha-response"], 
);
$fromgoog = file_get_contents($url, false, stream_context_create(array(
    'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($params)
    )
)));

$resp=json_decode($fromgoog,true);

  if (!$resp["success"]) {
    //  CAPTCHA was entered incorrectly
    die ("Вы не прошли проверочное задание - система считает Вас роботом. Пожалуйста, вернитесь на предидущую страницу и выполните задание снова." .
         "(Произошла ошибка: " . $resp["error-codes"] . ")");
  } else {
    //  successful verification
    if ($_POST["password"]==$_POST["passwordr"]){
        $us->rnu($_POST["email"],$_POST["password"]);
        if($us->auth($_POST["email"],$_POST["password"])) {
            header("Location:./index.php");
        } else {
           die ("Произошла ошибка! Пожалуйста, перерегистрируйтесь снова!" ); 
        }
    } else {
        die ("Пароль и его подтверждение не совпадают - Пожалуйста, заполните форму снова" );
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content="Регистрация нового пользователя"/>
    <meta name="author" content="">
    <!--<link rel="icon" href="./favicon.ico">-->

    <title>Регистрация</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet"/>

    <!-- Custom styles for this template -->
	  <link href="./css/signin.css" rel="stylesheet"/>
      
      <script src='https://www.google.com/recaptcha/api.js'></script>
  </head>

  <body>

    <div class="container">

		<form class="form-signin" method="POST">
        <h2 class="form-signin-heading text-center">Пожалуйста заполните поля для регистрации</h2>
        <label for="inputEmail" class="sr-only">Email</label>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email" required autofocus>
        <label for="inputPassword" class="sr-only">Пароль</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Пароль" required>
        <label for="inputPasswordR" class="sr-only">Пароль (повторите)</label>
        <input type="password" name="passwordr" id="inputPasswordR" class="form-control" placeholder="Повторите пароль" required>        
        <div class="g-recaptcha" data-sitekey="6LeL0TMUAAAAAAGmW6f5qBpSg4gZ_3oEjQ8Tp15w"></div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
        
        
      </form>

        <br />
        
        
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	  <script src="./js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
