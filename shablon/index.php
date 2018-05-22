<?php

require_once("./func.php");

$us= new US;
if ($us->auth($_COOKIE["login"],$_COOKIE["password"])){    
    
}

//search UMK
    $search="";
    if (isset($_POST["nnp"])){
        $search=$us->psr($_POST);
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Учебно-методический комплекс">
    <meta name="author" content="">
    

    <title>Учебно-методический комплекс</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/dashboard.css" rel="stylesheet">
  </head>

  <body>
  <div id="load">
  <img src="./files/img/load.gif"/>
  </div>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
		<a class="navbar-brand" href="./index.php">УМК</a>
      <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#smallNav" aria-controls="smallNav" aria-expanded="false" aria-label="Меню">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="smallNav">
        <ul class="navbar-nav mr-auto nav nav-pills" role="tablist">
          <li class="nav-item ">
            <a class="nav-link active" href="#summary" data-toggle="pill"  role="tab" >Общая информация</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#fak" data-toggle="pill" role="tab">Факультеты</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#kaf" data-toggle="pill" role="tab">Кафедры</a>
          </li>
<?php
if ($us->status>1){
?>    
          <li class="nav-item">
            <a class="nav-link" href="#prepod" data-toggle="pill" role="tab">Преподавателю</a>
          </li>
<?php   
}
?>

<?php
if ($us->status>1){
?>    
          <li class="nav-item">
            <a class="nav-link" href="#adm" data-toggle="pill" role="tab">Администратору</a>
          </li>    
<?php   
}
?>
          
          
 
			
        </ul>

      </div>
      
      <label style="float: right;color: #fff;font-size: larger;font-weight: bold;">
<?php
if ($us->login<>"") {
    echo $us->login;
} 
?>      
      </label>
				<a class="nav-link" href="./login.php" style="float: right;">
<?php
if ($us->status==0) {
    echo "Войти";
} else {
    echo "Выйти";
}


?>                
                </a>
        
      
    </nav>

    <div class="container-fluid tab-content col-10 mx-auto mt-3">
    
    
	<div class="row tab-pane  active" role="tabpanel" id="summary" >
    
	  <form class="form-inline" id="filtr" method="POST">
        

<?php

$us->pso("inp","nnp","ur_obr","Уровень высшего образования");

$us->pso("iform","nform","formi","Форма обучения");

$us->pso("ifak","nf","fakultet","Факультет");

$us->pso("ik","nk","kafedra","Кафедра");

$us->pso("ipr","npr","profil","Профиль подготовки");

$us->pso("inap","nn","napravlenie","Направление подготовки");

$us->pso("ig","ng","goda","Год");

?>
    <div class='col-md-4 mb-3'>
      <button class="btn btn-primary" type="submit" id="fs">Найти</button> 
    </div>    
      </form>
<?php
if ($search<>""){
?>
      <div>
        <h2>Результаты поиска</h2>
<?php echo $search; ?>
      </div>
<?php
}
?>
    </div>
        
        
      <div class="row tab-pane col-8 col-offset-2" role="tabpanel" id="fak">
          <h1>Список факультетов</h1>
          
          
<?php

$us->pli("ifak","nf","fakultet");


?>          

		</div>
        
        
        
		<div class=" row tab-pane  col-8 col-offset-2" id="kaf" role="tabpanel">
			<h1>Список кафедр</h1>
<?php

$us->pli("ik","nk","kafedra");

?>

		</div>
        
<?php
if ($us->status>1){
?>        
		<div class=" row tab-pane  col-8 col-offset-2" id="prepod" role="tabpanel">
			<h1>Преподавателю</h1>
			<a href="./umk.php" class="btn btn-primary">Создать рабочую программу</a>
            
<?php

$us->plu("ium","nd","umk, discipline","pi=".$us->pi." AND di=id");
            
?>           
            
		</div>
<?php   
}
?>
        
<?php
if ($us->status>1){
?>         
        <div class=" row tab-pane  col-8 col-offset-2" id="adm" role="tabpanel">
			<h1>Администратору</h1>
			
<div id="accordion">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Преподаватели
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body">
        
<?php

$us->pla("ip","np","prepodi");

?>
        
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Факультеты
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
      <div class="card-body">
        
        
<?php

$us->pla("ifak","nf","fakultet");

?>
          
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Кафедры
        </button>
      </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
      <div class="card-body">
        
<?php

$us->pla("ik","nk","kafedra");

?>
          
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingFour">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
          Дисциплины
        </button>
      </h5>
    </div>
    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
      <div class="card-body">
      
<?php

$us->pla("id","nd","discipline");

?>
        
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingFive">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
          Компетенции
        </button>
      </h5>
    </div>
    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
      <div class="card-body">
      
<?php

$us->pla("ikomp","kodkomp","kompetencii");

?>
        
      </div>
    </div>
  </div>
   <div class="card">
    <div class="card-header" id="headingSix">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
          Пользователи
        </button>
      </h5>
    </div>
    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
      <div class="card-body">
      
<?php

$us->pla("iu","email","users");

?>
        
      </div>
    </div>
  </div>
</div>
            
		</div>
<?php   
}
?>        
        
       </div>
       
       
<!-- The Modal -->
<div class="modal fade" id="order_price">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Цена Вашего заказа:</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <p style="text-align: center;color: green;font-size: large;"><b><span id="o_p"></span></b>$</p> 
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" id="pay_btn" class="btn btn-success" >Оплатить</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
      </div>

    </div>
  </div>
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

