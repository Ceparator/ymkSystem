<?php

require_once("./func.php");

$us= new US;
if ($us->auth($_COOKIE["login"],$_COOKIE["password"])){ 
    
    $c=0; //trigger of creating or updating
    
    if (isset($_POST["editor"]) or isset($_POST["newrow"]) and ($us->status==3)){
        if (isset($_POST["editor"])) {  //update opis item from admin
            $c=$us->uoi($_POST);
            $par=$_POST["table"]; 
        } else {                        //create opis item from admin
            $c=$us->coi($_POST);
            $par=$_POST["newrow"];
        }
        
    }
    
    
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Детальная информация">
    <meta name="author" content="">
    

    <title>Детальная инфомация</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/dashboard.css" rel="stylesheet">
    
<?php

if ($us->status>1){
    echo "<script src='../ckeditor/ckeditor.js'></script>";
}

?>
    
  </head>

  <body>
  <div id="load">
  <img src="./files/img/load.gif"/>
  </div>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
		<a class="navbar-brand" href="./index.php">Назад</a>
      
    </nav>

    <div class="container-fluid tab-content col-10 mx-auto mt-3">
    
<?php

if ($c==0){
    foreach ($_GET as $key=>$val){
        $us->pia($key,$val);
    };
} else {
    
    $us->pia($par,$c);
}

?>      
       
        
       </div>
       
       


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	  <script src="./js/jquery-3.2.1.min.js" ></script>

	  <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="./js/ie10-viewport-bug-workaround.js"></script>
    <script src="./js/custom.js"></script>

  </body>
</html>

