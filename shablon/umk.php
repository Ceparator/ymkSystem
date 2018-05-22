<?php

require_once("./func.php");

$us= new US;
if ($us->auth($_COOKIE["login"],$_COOKIE["password"])){ 
    
    $prizn=false; //neobhodimost vivoda informacii v formu
    $act=4;
    if (isset($_POST["um"])){
        if ($_POST["um"]==0){
            $act=1;
        } else {
            $act=3;
        }
    } elseif (isset($_GET["act"])) {
        $act=$_GET["act"];
    }
       
    
    switch ($act){
            case 0 : //delete
                $us->dumk($_GET["um"]);
                header("Location: index.php");
                break;
            case 1 : //insert
                $us->iumk($_POST);
                header("Location: ./info.php?nd=".$_POST["nd"]);
                break;
            case 2 : //select
                $form=$us->sumk($_GET["um"]);
                $prizn=true;
                break;
            case 3 : //update
                $us->uumk($_POST);
                $form=$us->sumk($_POST["um"]);
                $prizn=true;
                break; 
            case 4: ///new UMK
                break;   
                
        }
    
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Составление рабочей программы">
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
  
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
		<a class="navbar-brand" href="./index.php">Назад</a>
        <button class="btn btn-primary text-center" form="umk" type="submit"><?php echo ($prizn)?"Сохранить редактирование":"Сохранить";  ?></button>
<?php

if ($prizn){
    
?>
        <a class="btn btn-danger text-center ml-2" href="umk.php?act=0&um=<?php echo $form["ium"];  ?>">Удалить данный План</a>

<?php
}
?>

    </nav>

    <div class="container-fluid tab-content col-10 mx-auto mt-3">
    
<form id="umk" method="POST" action="">  


<input type="hidden"  name="um" value="<?php echo (prizn)? $form["ium"] :"0";  ?>" />
<input type="hidden"  name="prepodi" value="<?php echo $us->pi  ?>" />

  
    <div id="accordion">
  <div class="card">
    <div class="card-header" id="heading1">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
          1. Титульный лист
        </button>
      </h5>
    </div>

    <div id="collapse1" class="collapse show" aria-labelledby="heading1" data-parent="#accordion">
      <div class="card-body inline">
        
        
<?php

$us->pso("inp","nnp","ur_obr","Уровень высшего образования",$form["uoi"]);

$us->pso("iform","nform","formi","Форма обучения",$form["formi"]);

$us->pso("ifak","nf","fakultet","Факультет",$form["fakulteti"]);

$us->pso("ik","nk","kafedra","Кафедра",$form["kafedrai"]);

$us->pso("ipr","npr","profil","Профиль подготовки",$form["profili"]);

$us->pso("inap","nn","napravlenie","Направление подготовки",$form["napri"]);

$us->pso("id","nd","discipline","Дисциплина",$form["di"]);

$zagotovka='<p style="text-align:center;">Министерство образования и науки Российской Федерации<br/>Федеральное государственное бюджетное образовательное учреждение высшего профессионального образования<br/>«Владимирский государственный университет имени Александра Григорьевича и Николая Григорьевича Столетовых»<br/>(ВлГУ)</p><br><p style="text-align:right;">«УТВЕРЖДАЮ»<br/>Проректор по учебно-методической работе<br/>_________________ А.А. Панфилов<br/>{date}</p><br/><p style="text-align:center;">РАБОЧАЯ ПРОГРАММА ДИСЦИПЛИНЫ</p><p style="text-align:center;">{nd}</p><br/><p style="">Направление	подготовки: {np}</p><p style="">Профиль подготовки: {pp}</p><p style="">Уровень высшего образования: {uvo}</p><p style="">Форма обучения: {fo}</p><br><br><br><br><br><br><p style="text-align:center;">Владимир - {year}</p>';

?>
            <textarea name='titul' id='titul' rows='10' cols='80'>
             
             <?php echo ($prizn)? $us->ud($form["titul"]) : $zagotovka;  ?>
                
            </textarea>
            <script>
                
                CKEDITOR.replace( 'titul' );
            </script>
        
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="heading2">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
          2. Цели освоения дисциплины, Место дисциплины в структуре ОПОП ВО
        </button>
      </h5>
    </div>
    <div id="collapse2" class="collapse" aria-labelledby="heading2" data-parent="#accordion">
      <div class="card-body">
    
            <textarea name='celi' id='celi' rows='10' cols='80'>
             <?php echo ($prizn)? $us->ud($form["celi"]) : "";  ?>   
            </textarea>
            <script>
                
                CKEDITOR.replace( 'celi' );
            </script>
          
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="heading3">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
          3. Компетенции обучающегося, формируемые в результате освоения дисциплины
        </button>
      </h5>
    </div>
    <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordion">
      <div class="card-body">
      
      <textarea name='comp' id='comp' rows='10' cols='80'>
           <?php echo ($prizn)? $us->ud($form["comp"]) : "";  ?>     
            </textarea>
            <script>
                
                CKEDITOR.replace( 'comp' );
            </script>
         
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="heading4">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
          4. Структура и содержание дисциплины
        </button>
      </h5>
    </div>
    <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordion">
      <div class="card-body" id="struct">
      <input type="text"  name="konec" value="<?php echo ($prizn)? $form["konec"] : "";  ?>" placeholder="Введите вид итогового конроля (экзамен, зачёт...)" title="Вид итогового конроля" />
      <button class="btn btn-primary mb-1" id="add">Добавить раздел</button>
      
      
<?php

if ($prizn){
    
    $n=$us->psd($form);
    
} else {
    
?>
        <div class="form-inline mb-1" id="r1">            
            <input type="text" class="mr-1 mb-1" size="70" name="disc[]" placeholder="Раздел дисциплины"/>
            <input type="text" class="mr-1 mb-1" size="5" name="sem[]" placeholder="Семестр" title="Номер семестра"/>
            <input type="text" class="mr-1 mb-1" size="5" name="ned[]" placeholder="Неделя" title="Неделя семестра"/>
            <input type="text" class="mr-1 mb-1" size="5" name="lek[]" placeholder="Лекции" title="Количество часов лекций"/>
            <input type="text" class="mr-1 mb-1" size="5" name="prak[]" placeholder="Практика" title="Количество часов практических занятий"/>
            <input type="text" class="mr-1 mb-1" size="5" name="lr[]" placeholder="ЛР" title="Количество часов лабораторных работ"/>
            <input type="text" class="mr-1 mb-1" size="5" name="kr[]" placeholder="КР" title="Количество часов контрольных работ"/>
            <input type="text" class="mr-1 mb-1" size="5" name="kp[]" placeholder="КП/КР"/>
            <input type="text" class="mr-1 mb-1" size="5" name="sr[]" placeholder="СР"  title="Количество часов самостоятельных работ работ"/>
            <input type="text" class="mr-1 mb-1" size="5" name="im[]" placeholder="ИМ" title="Объём учебной работы с применением интерактивных методов"/>
            <input type="text" class="mr-1 mb-1" size="15" name="kontr[]" placeholder="Контроль" title="Формы текущего контроля успеваемости"/>
            <button class="btn btn-danger" onclick="del('r1')">Удалить раздел</button>
        </div>

<?php
}
?>       
      
        
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="heading5">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
          5. Образовательные технологии
        </button>
      </h5>
    </div>
    <div id="collapse5" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
      <div class="card-body">
      
      <textarea name='tech' id='tech' rows='10' cols='80'>
          <?php echo ($prizn)? $us->ud($form["tech"]) : "";  ?>      
            </textarea>
            <script>
                
                CKEDITOR.replace( 'tech' );
            </script>
        
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="heading6">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
          6. Оценочные средства для текущего контроля успеваемости, промежуточной аттестации по итогам освоения дисциплины и учебно-методическое обеспечение самостоятельной работы студентов
        </button>
      </h5>
    </div>
    <div id="collapse6" class="collapse" aria-labelledby="heading6" data-parent="#accordion">
      <div class="card-body">
      
      <textarea name='ocen' id='ocen' rows='10' cols='80'>
          <?php echo ($prizn)? $us->ud($form["ocen"]) : "";  ?>      
            </textarea>
            <script>
                
                CKEDITOR.replace( 'ocen' );
            </script>
        
      </div>
    </div>
  </div> 
  <div class="card">
    <div class="card-header" id="heading7">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
          7. Учебно-методическое и организационное обеспечение дисциплины
        </button>
      </h5>
    </div>
    <div id="collapse7" class="collapse" aria-labelledby="heading7" data-parent="#accordion">
      <div class="card-body">
      
      <textarea name='ucheb' id='ucheb' rows='10' cols='80'>
          <?php echo ($prizn)? $us->ud($form["ucheb"]) : "";  ?>      
            </textarea>
            <script>
                
                CKEDITOR.replace( 'ucheb' );
            </script>
        
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="heading8">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse8" aria-expanded="false" aria-controls="collapse8">
          8. Материально-техническое обеспечение дисциплины
        </button>
      </h5>
    </div>
    <div id="collapse8" class="collapse" aria-labelledby="heading8" data-parent="#accordion">
      <div class="card-body">
      
      <textarea name='mat' id='mat' rows='10' cols='80'>
          <?php echo ($prizn)? $us->ud($form["mat"]) : "";  ?>      
            </textarea>
            <script>
                
                CKEDITOR.replace( 'mat' );
            </script>
        
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="heading9">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse9" aria-expanded="false" aria-controls="collapse9">
          9. Лист согласований1
        </button>
      </h5>
    </div>
    <div id="collapse9" class="collapse" aria-labelledby="heading9" data-parent="#accordion">
      <div class="card-body">
      
      <textarea name='sogl1' id='sogl1' rows='10' cols='80'>
           <?php echo ($prizn)? $us->ud($form["sogl1"]) : "";  ?>     
            </textarea>
            <script>
                
                CKEDITOR.replace( 'sogl1' );
            </script>
        
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="heading10">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse10" aria-expanded="false" aria-controls="collapse10">
          10. Лист согласований 2
        </button>
      </h5>
    </div>
    <div id="collapse10" class="collapse" aria-labelledby="heading10" data-parent="#accordion">
      <div class="card-body">
      
      <textarea name='sogl2' id='sogl2' rows='10' cols='80'>
          <?php echo ($prizn)? $us->ud($form["sogl2"]) : "";  ?>      
            </textarea>
            <script>
                
                CKEDITOR.replace( 'sogl2' );
            </script>
        
      </div>
    </div>
  </div>
</div>
</form>
        
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
    
<?php

if ($prizn){
    
    echo "<script>
                
                i=".$n.";
            </script>";
    
} 
?>

  </body>
</html>

<?php    
} else {
   header("Location: index.php"); 
}
?>
