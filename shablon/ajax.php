<?php

require_once("./func.php");


//return buying data
if (isset($_GET["payment"])){
    $mysqli = new mysqli(HOST, USER, PD, BD);
    $mysqli->set_charset("utf8");
    if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }    
    
    //get amount money on account
    $login=US::ci($_GET["l"]);
    $psw=US::ci($_GET["p"]);
    
    $res = $mysqli->query("SELECT bal, iu FROM users WHERE email='".$login."' AND pd='".md5($psw)."' LIMIT 1");
    while ($row = $res->fetch_assoc()) {
        $bal=$row["bal"];
        $iu=$row["iu"];
    }
    //get array of filtered columns
    $res = $mysqli->query("SELECT ic FROM columns WHERE filter=1");
    while ($row = $res->fetch_assoc()) {
        $f_c[]=$row["ic"];
    }
    //total sum of buying product
    $total=0;
    //get one of tables
    foreach ($_GET["s3"] as $val){
        //get array of columns in that table
        $res = $mysqli->query("SELECT fields,title_translit,cenaone, opt, opt_cenaone, title FROM tables WHERE it=".$val);
        while ($row = $res->fetch_assoc()) {
            $col=ltrim($row["fields"],"[\"");
            $col=rtrim($col,"]\"");
            $acol=explode("\",\"",$col);
            $namoftable=$row["title_translit"];
            $price=$row["cenaone"];
            $opt=$row["opt"];
            $price_opt=$row["opt_cenaone"];
            $tit=$row["title"];
        }
        $f_acol=array_intersect($acol,$f_c);
        //get filtered column in that tables and make condition for query
        $cond="";
        foreach ($f_acol as $val1) {
            if ($_GET[$val."_".$val1]!="0"){
                $cond.=" `".$val1."`=\"".$_GET[$val."_".$val1]."\" AND";
            }
        }
        $cond=rtrim($cond,"DNA ");
        
        //make query and get number of position
        $q="SELECT * FROM ".$namoftable.(($cond!="")?" WHERE".$cond:"");
        $res = $mysqli->query($q);
        //calc price for that table
        $np=$res->num_rows;
        if (($np<$opt) or ($opt==0)){
            $sum=$price*$np;
        } else {
            $sum=$price_opt*$np;
        } 
        //buy info in table if enough money
        if ($bal>$sum) {
            //upload info from table to xls
            $link="./buyed/".time().".xlsx";
            $fileEx=new Excel_mysql($mysqli, $link);
            $fileEx->mysql_to_excel($namoftable, "Экспорт", false,false,false,false,false,(($cond!="")?$cond:""));
            
            //change bal
            $bal=$bal-$sum;
            $total=$total+$sum;
            //insert info about new order in db
            $res = $mysqli->query("INSERT INTO orders( ui, nameoftab, pos, price, link ) VALUES (".$iu.", \"".$tit."\", ".$np.", ".$sum.", \"".$link."\")");
       
        }
        
    }
    
    //after all tables update info about ballance of account
   $res = $mysqli->query("UPDATE users SET bal=".$bal." WHERE iu=".$iu); 
    
   
   //$res->free();
   $mysqli->close(); 
    
}


//return order price and order config
if (isset($_GET["order_form"])){
    $mysqli = new mysqli(HOST, USER, PD, BD);
    $mysqli->set_charset("utf8");
    if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }    
    
    //get array of filtered columns
    $res = $mysqli->query("SELECT ic FROM columns WHERE filter=1");
    while ($row = $res->fetch_assoc()) {
        $f_c[]=$row["ic"];
    }
    //total sum
    $total=0;
    //get one of tables
    foreach ($_GET["s3"] as $val){
        //get array of columns in that table
        $res = $mysqli->query("SELECT fields,title_translit,cenaone, opt, opt_cenaone FROM tables WHERE it=".$val);
        while ($row = $res->fetch_assoc()) {
            $col=ltrim($row["fields"],"[\"");
            $col=rtrim($col,"]\"");
            $acol=explode("\",\"",$col);
            $namoftable=$row["title_translit"];
            $price=$row["cenaone"];
            $opt=$row["opt"];
            $price_opt=$row["opt_cenaone"];
        }
        $f_acol=array_intersect($acol,$f_c);
        //get filtered column in that tables and make condition for query
        $cond="";
        foreach ($f_acol as $val1) {
            if ($_GET[$val."_".$val1]!="0"){
                $cond.=" `".$val1."`=\"".$_GET[$val."_".$val1]."\" AND";
            }
        }
        $cond=rtrim($cond,"DNA ");
        
        //make query and get number of position
        $q="SELECT * FROM ".$namoftable.(($cond!="")?" WHERE".$cond:"");
        $res = $mysqli->query($q);
        //calc price for that table
        if (($res->num_rows<$opt) or ($opt==0)){
            $total=$total+$price*$res->num_rows;
        } else {
            $total=$total+$price_opt*$res->num_rows;
        }
        
    }
   echo $total;
   $res->free();
   $mysqli->close(); 
    
}


//return title of coutrys by type
if (isset($_GET["t"]) and is_int(intval($_GET["t"]))) {
    $mysqli = new mysqli(HOST, USER, PD, BD);
    $mysqli->set_charset("utf8");
    if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }
    $res = $mysqli->query("SELECT c.title, t.country FROM tables as t, countrys as c WHERE t.type=".intval($_GET["t"])." AND t.country=c.ic Group by title");
    $s="<option >Страна</option>\n";
    while ($row = $res->fetch_assoc()) {
        $s.="<option value=\"".$row["country"]."\">".$row["title"]."</option>\n";
    }
    $res->free();
    $mysqli->close();
    echo $s;

} else {
    echo $_GET["t"];
}


//return titles of db by type and country
if (isset($_GET["tb"]) and is_int(intval($_GET["tb"])) and is_int(intval($_GET["c"]))) {
    $mysqli = new mysqli(HOST, USER, PD, BD);
    $mysqli->set_charset("utf8");
    if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }
    $res = $mysqli->query("SELECT title, it FROM tables  WHERE type=".intval($_GET["tb"])." AND country=".intval($_GET["c"])." Group by title Order by title ASC");
    $s="";
    if ($res->num_rows>0){
    while ($row = $res->fetch_assoc()) {
        $s.="<option value=\"".$row["it"]."\">".$row["title"]."</option>\n";
    }
    } else {
        $s.="<option value=\"1\">Для данного набора типа базы и страны информации нет!</option>\n";
    }
    $res->free();
    $mysqli->close();
    echo $s;

} else {
    echo $_GET["tb"];
}


//return row in specified table
if (isset($_GET["nt"]) and is_int(intval($_GET["nt"]))){
    
    //make sql condition for specific table
    $arr=$_GET["condition"];
    $condition="";
    foreach ($arr as $field=>$value){
        if ($value!='0'){
            $condition.="`".$field."`='".$value."' AND ";
        }
    }
    $condition=rtrim($condition," DNA");
	
	//connect to bd
	 $mysqli = new mysqli(HOST, USER, PD, BD);
    $mysqli->set_charset("utf8");
    if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }
	
	//find all index of secure columns
    $q="SELECT ic FROM columns WHERE secure";
    $res = $mysqli->query($q);
    $secure=array();
    while ($row = $res->fetch_assoc()) {
        $secure[]=$row["ic"];
    }
	
	//find translit_title and all columns in table by its num
	 $res = $mysqli->query("SELECT title_translit, fields FROM tables WHERE it=".($_GET["nt"]));
     $rtemp=$res->fetch_all(MYSQLI_ASSOC);
	
	//create  array of fields
    $result=json_decode($rtemp[0]["fields"]);
	$nresult=count($result);
        $q="SELECT ic FROM columns WHERE ic=".$result[0];
        for ($j=1;$j<$nresult;$j++){
            $q.=" OR ic=".$result[$j];
        }
        $res = $mysqli->query($q);
        $head="";
        while ($row = $res->fetch_assoc()) {            
            $head.="`".$row["ic"]."`, ";
        }
        $head=rtrim($head," ,");
       
        //print body of table
        $q="SELECT ".$head." FROM ".$mysqli->real_escape_string($rtemp[0]["title_translit"]).(($condition=="")?"":" WHERE ".$condition)." LIMIT 100";
        echo $q;
        $res = $mysqli->query($q);
			
	$s="";//string for table rows
	while ($row = $res->fetch_assoc()) {
            $s.="<tr>\n";
            foreach ($row as $k=>$val) {
                if (in_array($k,$secure)){
                   $s.="<td>*****</td>\n";
                } else {
                  $s.="<td>".$val."</td>\n";
                }
            }
            $s.="</tr>\n";
        }
						   
	echo $s;
						   
	$res->free();
    $mysqli->close();
	
	
}


//return serched tables
if (isset($_GET["tbs"]) and is_int(intval($_GET["tbs"])) and is_int(intval($_GET["cs"]))) {
    $mysqli = new mysqli(HOST, USER, PD, BD);
    $mysqli->set_charset("utf8");
    if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }
    //find all index of secure columns
    $q="SELECT ic FROM columns WHERE secure";
    $res = $mysqli->query($q);
    $secure=array();
    while ($row = $res->fetch_assoc()) {
        $secure[]=$row["ic"];
    }

    //iterrate all tables in GET["b"]
    $s="";
    $get=explode(",",$_GET["b"]);
    $nb=count($get);
    for ($i=0;$i<$nb;$i++){
        if ($nb==1){
            $it=$get[0];
        } else {
            $it=$get[$i];
        }
        $s.="<br/><table id=\"table".$it."\" class=\"table-bordered table-hover table-responsive\">\n<thead>\n<tr>\n";
        //search title in head of table
        $res = $mysqli->query("SELECT title_translit, fields FROM tables WHERE it=".$it);
        $rtemp=$res->fetch_all(MYSQLI_ASSOC);
        //create  array of fields
        $result=json_decode($rtemp[0]["fields"]);
        $nresult=count($result);
        $q="SELECT title, ic, filter FROM columns WHERE ic=".$result[0];
        for ($j=1;$j<$nresult;$j++){
            $q.=" OR ic=".$result[$j];
        }
        $res = $mysqli->query($q);
        $head="";
        while ($row = $res->fetch_assoc()) {
            //check what print title or select
            if ($row["filter"]){//if filtered field - print SELECT with all variants
                $q1="SELECT `".$row["ic"]."` FROM ".$mysqli->real_escape_string($rtemp[0]["title_translit"])." GROUP BY `".$row["ic"]."`";
                $res1 = $mysqli->query($q1);
                $s1="<select class=\"hs\" name=\"".$it."_".$row["ic"]."\" >\n<option value=\"0\">".$row["title"]."?</option>\n";
                while ($row1 = $res1->fetch_assoc()) {
                   $s1.="<option value=\"".$row1[$row["ic"]]."\">".$row1[$row["ic"]]."</option>\n";
                }
                $s1.="</select>\n";
                //print SELECT in head cell of table
                $s.="<th>".$s1."</th>\n";
            } else {//if non filtered field - print simple title of field
                $s.="<th>".$row["title"]."</th>\n";
            }
            $head.="`".$row["ic"]."`, ";
        }
        $head=rtrim($head," ,");
        $s.="</tr>\n</thead>\n";
        //print body of table
        $s.="<tbody>\n";
        $q="SELECT ".$head." FROM ".$mysqli->real_escape_string($rtemp[0]["title_translit"])." LIMIT 100";
        $res = $mysqli->query($q);
        while ($row = $res->fetch_assoc()) {
            $s.="<tr>\n";
            foreach ($row as $k=>$val) {
                if (in_array($k,$secure)){
                   $s.="<td>*****</td>\n";
                } else {
                  $s.="<td>".$val."</td>\n";
                }
            }
            $s.="</tr>\n";
        }
        $s.="</tbody>\n</table>\n";

    }

    $res->free();
    $mysqli->close();
    echo $s;

} else {
    echo $_GET["tbs"];
}

//admin panel tab columns check/uncheck
if (isset($_GET["name_check"])) {
     $mysqli = new mysqli(HOST, USER, PD, BD);
    $mysqli->set_charset("utf8");
    if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
    }

    $prizn=substr($_GET["name_check"],0,1);
    $numcol=substr($_GET["name_check"],1);

    $q="UPDATE columns SET ".(($prizn=="f")?"filter":"secure")."=".$_GET["val_check"]." WHERE ic=".$numcol;
    $res = $mysqli->query($q);

    $mysqli->close();
    echo "ok";
}

//admin panel tab columns check/uncheck
if (isset($_GET["name_inp"])) {
     $mysqli = new mysqli(HOST, USER, PD, BD);
    $mysqli->set_charset("utf8");
    if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
    }

    $tarr=explode("_",$_GET["name_inp"]);
    $table=intval($tarr[0]);
    $numcol=intval($tarr[1]);
    
    switch ($numcol) {
        case 1 :
            $q="UPDATE tables SET cenaone=".$_GET["val_inp"]." WHERE it=".$table;
            break;
        case 2 :
            $q="UPDATE tables SET opt=".$_GET["val_inp"]." WHERE it=".$table;
            break;
        case 3 :
            $q="UPDATE tables SET opt_cenaone=".$_GET["val_inp"]." WHERE it=".$table;
            break;
    }
echo $q;
    
    $res = $mysqli->query($q);

    $mysqli->close();
    echo "Изменение выполнено!";
}

?>