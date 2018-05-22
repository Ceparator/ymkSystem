<?php
require_once("./conf.php");



//class for all users queries
class US{    
    
    public $status=0; // status prepodavatela
    public $pi=0; //prepodavatel id
    public $login="";
    
    //translit convertation
    function rus2translit($string) {
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '',    'ы' => 'y',   'ъ' => '',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    return strtr($string, $converter);
}


//url decode
function ud($a){
    return urldecode($a);
}



//url encode
function ue($a){
    return urlencode($a);
}



    //clear input from injection
    function ci($a){
        $t=str_replace(" ","",$a);//replace all slashes
        return $t;
    }

    //select something
    function ss($a,$b){
         $mysqli = new mysqli(HOST, USER, PD, BD);
         $mysqli->set_charset("utf8");
         
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }

        $res = $mysqli->query("SELECT ".ci($a)." FROM ".ci($b));
        $result=$res->fetch_all(MYSQLI_ASSOC);

        $mysqli->close();
        return $result;
    }
    
    

    //restore user password
    function rup($a){
        $mysqli = new mysqli(HOST, USER, PD, BD);
        $mysqli->set_charset("utf8");
        
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }

        $res = $mysqli->query("SELECT iu FROM users WHERE email='".$this->ci($a)."' LIMIT 1");

        if ($res->num_rows>0){
            $pd=rand(333,77777);//random number
            $row = $res->fetch_assoc();
            $res = $mysqli->query("UPDATE users SET pd='".md5($pd)."' WHERE iu='".$row["iu"]."'");
            $mysqli->close();
            //sending user new password
            mail($this->ci($a),"Новый пароль",$pd);
            //message for user
            $this->mes="Восстановление прошло успешно! Проверьте Ваш E-mail.";
            return true;
        } else {
            $mysqli->close();
            //message for user
            $this->mes="Вы не зарегистрированы у Нас! Пожалуйста, зарегистрируйтесь.";
            return false;
        }
    }

    //registering new user
    function rnu($a,$b,$c=1,$d=0){
        $mysqli = new mysqli(HOST, USER, PD, BD);
        $mysqli->set_charset("utf8");
        
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }

        $res = $mysqli->query("SELECT iu FROM users WHERE email='".$this->ci($a)."' LIMIT 1");

        if ($res->num_rows>0){
            die ("Вы уже зарегистрированны! Пожалуйста, воспользуйтесь возможностью Восстановления пароля." );
        } else {
        //register new user
        $res = $mysqli->query("INSERT INTO users( email, pd, status, pi) VALUES ('".$this->ci($a)."','".md5($this->ci($b))."',".$this->ci($c).",".$this->ci($d).")");
        }

        $mysqli->close();
    }


    //authentification
    function auth($a,$b){

        $mysqli = new mysqli(HOST, USER, PD, BD);
        $mysqli->set_charset("utf8");
        
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }

        $res = $mysqli->query("SELECT * FROM users WHERE email='".$this->ci($a)."' AND pd='".md5($this->ci($b))."' LIMIT 1");

        if ($res->num_rows>0){
            setcookie("login", $a);
            setcookie("password", $b);
            $row=$res->fetch_assoc();            
            $res->free();
            $mysqli->close();
            $this->status=$row["status"];
            $this->pi=$row["pi"];//index in table 'prepodi'
            $this->login=$row["email"];//email login
            return true;
        } else {
            setcookie("login", "", time() - 3600);
            setcookie("password", "", time() - 3600);
            $row=$res->fetch_assoc();
            $this->status=0;
            $res->free();
            $mysqli->close();
            return false;
        }
    }
    
//print select options
    function pso($id,$what,$table,$name,$cur=-1){
        $mysqli = new mysqli(HOST, USER, PD, BD);
        $mysqli->set_charset("utf8");
        
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }

        $res = $mysqli->query("SELECT ".$id.",".$what." FROM ".$table." ORDER BY ".$id."  ASC");

        if ($res->num_rows>0){
            echo "<div class='col-md-4 mb-3'>
                    <label for='".$what."'>".$name."</label>
                    <select class='form-control' name='".$what."' id='".$what."'>";
            printf ("<option value='%s'>%s</option>\n", "0", "Выбрать");
            while ($row = $res->fetch_assoc()) {
                echo "<option value='".$row[$id]."' ".(($row[$id]==$cur)?"selected":"")." >".$row[$what]."</option>\n";
            }
            echo "</select>
                </div>";
        } else {
            
            echo "<div class='col-md-4 mb-3'>
                    <label for='".$what."'>".$name."</label>
                    <select class='form-control' name='".$what."' id='".$what."'>";
            echo "<option>Нет значений</option>\n";
            echo "</select>
                </div>";
                
        }

        $mysqli->close();
    }
    
    
//print list item
    function pli($id,$what,$table, $where=""){
        $mysqli = new mysqli(HOST, USER, PD, BD);
        $mysqli->set_charset("utf8");
        
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }

        $res = $mysqli->query("SELECT ".$id.",".$what." FROM ".$table.($where<>""? " WHERE ".$where : "")." ORDER BY ".$id."  ASC");

        if ($res->num_rows>0){
            echo "<div class='list-group mt-2'>\n";
            
            while ($row = $res->fetch_assoc()) {
                printf ("<a href='./info.php?%s=%s' class='list-group-item list-group-item-action'>%s</a>\n", $table, $row[$id], $row[$what]);
            }
            echo "</div>";
        } else {
            
            echo "<div class='mt-2'>";
            echo "Нет значений в списке\n";
            echo "</div>";
                
        }

        $mysqli->close();
    }



//print list of UMK for current prepodavatel
    function plu($id,$what,$table, $where=""){
        $mysqli = new mysqli(HOST, USER, PD, BD);
        $mysqli->set_charset("utf8");
        
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }

        $res = $mysqli->query("SELECT ".$id.",".$what." FROM ".$table.($where<>""? " WHERE ".$where : "")." ORDER BY ".$id."  ASC");

        if ($res->num_rows>0){
            echo "<div class='list-group mt-2'>\n";
            
            while ($row = $res->fetch_assoc()) {
                printf ("<a href='./umk.php?act=2&um=%s' class='list-group-item list-group-item-action'>%s</a>\n", $row[$id], $row[$what]);
            }
            echo "</div>";
        } else {
            
            echo "<div class='mt-2'>";
            echo "Нет значений в списке\n";
            echo "</div>";
                
        }

        $mysqli->close();
    }



//print opis item
    function poi($table,$id,$num){
        
        $mysqli = new mysqli(HOST, USER, PD, BD);
        $mysqli->set_charset("utf8");
        
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }
        
        //for discipline - insert link on export if exist
        $link="";
        if ($table=="discipline"){
            $res = $mysqli->query("SELECT ium FROM umk WHERE di=".$num."  LIMIT 1");
            if ($res->num_rows>0){
               while ($row = $res->fetch_assoc()) {
                $link="export.php?i=".$row["ium"];
               }
            }
        }
        //end for discipline
        
        $res = $mysqli->query("SELECT * FROM ".$table." WHERE ".$id."=".$num."  LIMIT 1");

        if ($res->num_rows>0){
            
           $row = $res->fetch_all();
             
            if ($this->status==3){
                echo "<form method='POST'>
                <h2>Редактор описания</h2>
                <input name='id' type='hidden' value='".$id."' />
                <input name='num' type='hidden' value='".$num."' />
                <input name='table' type='hidden' value='".$table."' />
                <div class='col-md-4 mb-3'>
                <label for='name'>Название категории</label>
                <input name='name' type='text' size='100' value='".$row[0][1]."' />
                </div>";
                switch ($table){
                    case "kafedra":
                        $this->pso("ifak","nf","fakultet","Факультет",$row[0][2]);
                        break;                    
                    case "discipline":
                        $this->pso("ik","nk","kafedra","Кафедра",$row[0][2]);
                        echo $this->pkc($row[0][4]);
                        break;
                    case "prepodi":
                        $this->pso("ik","nk","kafedra","Кафедра",$row[0][2]);
                        break;                    
                }
            echo "<textarea name='editor' id='editor' rows='10' cols='80'>
                ".$row[0][3]."
            </textarea>
            <script>
                
                CKEDITOR.replace( 'editor' );
            </script>
            <button class='btn btn-primary' type='submit' >Сохранить</button>
        </form>";
            } else {
               echo "<div class='jumbotron'>\n";                                       
                printf ("<h1>%s</h1>\n%s", $row[0][1], $row[0][3]); 
                
                if  ($link<>""){
                    if ($this->status>0){
                        echo "<p><a class='btn btn-success' href='".$link."'>Скачать УМК</a></p>";
                    } else {
                        echo "<p><a class='btn btn-success' onclick='alert(\"Скачивание доступно только после регистрации!\");'>Скачать УМК</a></p>";
                    }
                    
                }      
                   
                echo "</div>"; 
            }
        } else {
            
            echo "<div class='jumbotron'>";
            echo "Нет описания\n";
            echo "</div>";
                
        }
        
       
        
        $mysqli->close();
    }
    
    
//update opis item
    function uoi($arr){
        $mysqli = new mysqli(HOST, USER, PD, BD);
        $mysqli->set_charset("utf8");
        
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }
        
        $promtxt="";
        switch ($arr["table"]){
                    case "kafedra":
                        $promtxt.=", nk='".$arr["name"]."'";
                        if ($arr["nf"]<>0){
                            $promtxt.=", fak=".$arr["nf"];
                        }
                        break;                    
                    case "discipline":
                        $promtxt.=", nd='".$arr["name"]."', compcb='".json_encode($arr["compcb"])."'";
                        if ($arr["nk"]<>0){
                            $promtxt.=", ki=".$arr["nk"];
                        }
                        break;
                    case "prepodi":
                        $promtxt.=", np='".$arr["name"]."'";
                        if ($arr["nk"]<>0){
                            $promtxt.=", ki=".$arr["nk"];
                        }
                        break; 
                    case "kompetencii":
                        $promtxt.=", kodkomp='".$arr["name"]."'";                        
                        break;                   
                }
        
        $res = $mysqli->query("UPDATE ".$arr["table"]." SET opis='".$arr["editor"]."' ".$promtxt." WHERE ".$arr["id"]."=".$arr["num"]);
        
        $mysqli->close();
        
        return $arr["num"];
}


//create opis item
    function coi($arr){
        $mysqli = new mysqli(HOST, USER, PD, BD);
        $mysqli->set_charset("utf8");
        
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }
        
        $promitem="";
        $promval="";
        switch ($arr["newrow"]){
                    case "kafedra":
                        $promitem="nk, fak, opis";
                        $promval="'Отредактируйте название кафедры', 0,'Отредактируйте описание'";
                        break;                    
                    case "discipline":
                        $promitem="nd, ki, opis";
                        $promval="'Отредактируйте название дисциплины', 0,'Отредактируйте описание', '[]'";
                        break;
                    case "prepodi":
                        $promitem="np, ki, opis";
                        $promval="'Отредактируйте преподавателя', 0,'Отредактируйте описание'";
                        break;
                    case "fakultet":
                        $promitem="nf, opis";
                        $promval="'Отредактируйте название факультета', 'Отредактируйте описание'";
                        break;
                    case "kompetencii":
                        $promitem="kodkomp, opis";
                        $promval="'Отредактируйте код компетенции', 'Отредактируйте значение компетенции'";
                        break;                    
                }
        
        $res = $mysqli->query("INSERT INTO ".$arr["newrow"]." (".$promitem.") VALUES (".$promval.")");
        $i= $mysqli->insert_id;
        $mysqli->close();
        return $i;
}


//print info about
    function pia($par,$val){
        $mysqli = new mysqli(HOST, USER, PD, BD);
        $mysqli->set_charset("utf8");
        
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }
        switch ($par){
            
            case "kafedra"://about kafedra
                
                $this->poi("kafedra","ik",$val);
            
                echo "<table class='table table-bordered'>\n<tr>\n<th><h2>Дисциплины</h2></th>\n<th><h2>Преподаватели</h2></th>\n</tr>\n<tr>";
                
                //disciplini
                echo "<td>\n";
                $this->pli("id","nd","discipline","ki=".$val);
                echo "</td>\n";
                
                //prepodavateli
                echo "<td>\n";
                $this->pli("ip","np","prepodi","ki=".$val);
                echo "</td>\n</tr>\n</table>\n";                          
                
                break;
                
            case "fakultet"://about fakultet
            
                $this->poi("fakultet","ifak",$val);
            
                echo "<table class='table table-bordered'>\n<tr>\n<th><h2>Кафедры</h2></th>\n</tr>\n<tr>";
                
                //kafedri
                echo "<td>\n";
                self::pli("ik","nk","kafedra","fak=".$val);
                echo "</td>\n</tr>\n</table>\n";
                
                break;
                
            case "discipline"://about disciplina
                $this->poi("discipline","id",$val);
                break;
                
            case "prepodi"://about prepodi
                $this->poi("prepodi","ip",$val);
                break;
            case "kompetencii"://about kompetencia
                $this->poi("kompetencii","ikomp",$val);
                break;
        }
        

        $mysqli->close();
    }


//print list admin
    function pla($id,$what,$table, $where=""){
        $mysqli = new mysqli(HOST, USER, PD, BD);
        $mysqli->set_charset("utf8");
        
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }

        $res = $mysqli->query("SELECT ".$id.",".$what." FROM ".$table.($where<>""? " WHERE ".$where : "")." ORDER BY ".$id."  ASC");


        if ($this->status==3){
        echo "<form method='POST' action='./info.php'>
        <input name='what' type='hidden' value='".$what."' />
        <input name='newrow' type='hidden' value='".$table."' />
        <button class='btn btn-primary' type='submit' >Добавить</button>
        </form></br>";
        }

        if ($res->num_rows>0){
            echo "<div class='list-group'>\n";
            
            while ($row = $res->fetch_assoc()) {
                if ($this->status==3){
                    printf ("<a href='./info.php?%s=%s' class='list-group-item list-group-item-action' title='Нажмите для редактирования'>%s</a><a class=\"btn btn-danger\" href='./del.php?n=%s&i=%s&t=%s' title='Удалить'>Удалить</a>\n", $table, $row[$id], $row[$what], $id, $row[$id], $table);
                } else {
                    printf ("<a href='./info.php?%s=%s' class='list-group-item list-group-item-action' >%s</a>\n", $table, $row[$id], $row[$what]);
                }
            }
            echo "</div>";
        } else {
            
            echo "<div class=''>";
            echo "Нет значений в списке\n";
            echo "</div>";
                
        }

        $mysqli->close();
    }
    

//delete list element
    function dle($table,$name,$id){
        $mysqli = new mysqli(HOST, USER, PD, BD);
        $mysqli->set_charset("utf8");
        
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
            exit();
        }

        $res = $mysqli->query("DELETE FROM ".$table." WHERE ".$name."=".$id);

         

        $mysqli->close();
    }
    
    
//insert UMK in DB
function iumk($arr){
   $mysqli = new mysqli(HOST, USER, PD, BD);
   $mysqli->set_charset("utf8");
        
   if ($mysqli->connect_errno) {
      echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
      exit();
   }
   
   $q="INSERT INTO umk( di, pi, uoi, formi, fakulteti, kafedrai, profili, napri, titul, celi, comp, konec, tech, ocen, ucheb, mat, sogl1, sogl2, npd, sem, ned, lek, prak, lab, kontr, kp, samost, interact, control)";
   $q.=" VALUES (".$arr["nd"].",".$arr["prepodi"].",".$arr["nnp"].",".$arr["nform"].",".$arr["nf"].",".$arr["nk"].",".$arr["npr"].",".$arr["nn"].",'".$this->ue($arr["titul"])."','".$this->ue($arr["celi"])."','".$this->ue($arr["comp"])."','".$arr["konec"]."','".$this->ue($arr["tech"])."','".$this->ue($arr["ocen"])."','".$this->ue($arr["ucheb"])."','".$this->ue($arr["mat"])."','".$this->ue($arr["sogl1"])."','".$this->ue($arr["sogl2"])."','".json_encode($arr["disc"],JSON_UNESCAPED_UNICODE)."','".json_encode($arr["sem"])."','".json_encode($arr["ned"])."','".json_encode($arr["lek"])."','".json_encode($arr["prak"])."','".json_encode($arr["lr"])."','".json_encode($arr["kr"])."','".json_encode($arr["kp"])."','".json_encode($arr["sr"])."','".json_encode($arr["im"])."','".json_encode($arr["kontr"],JSON_UNESCAPED_UNICODE)."')";
   
   $res = $mysqli->query($q);
  
   $mysqli->close(); 
}


//update UMK in DB
function uumk($arr){
   $mysqli = new mysqli(HOST, USER, PD, BD);
   $mysqli->set_charset("utf8");
        
   if ($mysqli->connect_errno) {
      echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
      exit();
   }
   
   $q="UPDATE umk SET di=".$arr["nd"].",pi=".$arr["prepodi"].",uoi=".$arr["nnp"].",formi=".$arr["nform"].",fakulteti=".$arr["nf"].",kafedrai=".$arr["nk"].",profili=".$arr["npr"].",napri=".$arr["nn"].",titul='".$this->ue($arr["titul"])."',celi='".$this->ue($arr["celi"])."',comp='".$this->ue($arr["comp"])."', konec='".$arr["konec"]."', tech='".$this->ue($arr["tech"])."',ocen='".$this->ue($arr["ocen"])."',ucheb='".$this->ue($arr["ucheb"])."',mat='".$this->ue($arr["mat"])."',sogl1='".$this->ue($arr["sogl1"])."',sogl2='".$this->ue($arr["sogl2"]);
   $q.="',npd='".json_encode($arr["disc"],JSON_UNESCAPED_UNICODE)."',sem='".json_encode($arr["sem"])."',ned='".json_encode($arr["ned"])."',lek='".json_encode($arr["lek"])."',prak='".json_encode($arr["prak"])."',lab='".json_encode($arr["lr"])."',kontr='".json_encode($arr["kr"])."',kp='".json_encode($arr["kp"])."',samost='".json_encode($arr["sr"])."',interact='".json_encode($arr["im"])."',control='".json_encode($arr["kontr"],JSON_UNESCAPED_UNICODE)."' WHERE ium=".$arr["um"];
                                                          
   
   $res = $mysqli->query($q);
  
   $mysqli->close(); 
}


//select UMK in DB
function sumk($ind){
   $mysqli = new mysqli(HOST, USER, PD, BD);
   $mysqli->set_charset("utf8");
        
   if ($mysqli->connect_errno) {
      echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
      exit();
   }
   
   
   $q="SELECT * FROM umk WHERE ium=".$ind;
                                                          
   
   $res = $mysqli->query($q);
   
   while ($row = $res->fetch_assoc()) {
    $ret=$row;
   }
   
   $mysqli->close();
  
  return $ret;
    
}


//delete UMK in DB
function dumk($ind){
   $mysqli = new mysqli(HOST, USER, PD, BD);
   $mysqli->set_charset("utf8");
        
   if ($mysqli->connect_errno) {
      echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
      exit();
   }
   
   
   $q="DELETE FROM umk WHERE ium=".$ind;
                                                          
   
   $res = $mysqli->query($q);
   
   $mysqli->close();
   
  return $res;
    
}


//print structure div in 4  section UMK
function psd($arr){
    $npd=json_decode($arr["npd"]);
    $sem=json_decode($arr["sem"]);
    $ned=json_decode($arr["ned"]);
    $lek=json_decode($arr["lek"]);
    $prak=json_decode($arr["prak"]);
    $lab=json_decode($arr["lab"]);
    $kontr=json_decode($arr["kontr"]);
    $kp=json_decode($arr["kp"]);
    $samost=json_decode($arr["samost"]);
    $interact=json_decode($arr["interact"]);
    $control=json_decode($arr["control"]);
   $n=count($npd);
   
   //perebor strukturi
   for ($k=0;$k<$n;$k++){
     echo "<div class=\"form-inline mb-1\" id=\"r".$k."\">            
            <input type=\"text\" class=\"mr-1 mb-1\" size=\"70\" name=\"disc[]\" placeholder=\"Раздел дисциплины\" value=\"".$npd[$k]."\"/>
            <input type=\"text\" class=\"mr-1 mb-1\" size=\"5\" name=\"sem[]\" placeholder=\"Семестр\" title=\"Номер семестра\" value=\"".$sem[$k]."\"/>
            <input type=\"text\" class=\"mr-1 mb-1\" size=\"5\" name=\"ned[]\" placeholder=\"Неделя\" title=\"Неделя семестра\" value=\"".$ned[$k]."\"/>
            <input type=\"text\" class=\"mr-1 mb-1\" size=\"5\" name=\"lek[]\" placeholder=\"Лекции\" title=\"Количество часов лекций\" value=\"".$lek[$k]."\"/>
            <input type=\"text\" class=\"mr-1 mb-1\" size=\"5\" name=\"prak[]\" placeholder=\"Практика\" title=\"Количество часов практических занятий\" value=\"".$prak[$k]."\"/>
            <input type=\"text\" class=\"mr-1 mb-1\" size=\"5\" name=\"lr[]\" placeholder=\"ЛР\" title=\"Количество часов лабораторных работ\" value=\"".$lab[$k]."\"/>
            <input type=\"text\" class=\"mr-1 mb-1\" size=\"5\" name=\"kr[]\" placeholder=\"КР\" title=\"Количество часов контрольных работ\" value=\"".$kontr[$k]."\"/>
            <input type=\"text\" class=\"mr-1 mb-1\" size=\"5\" name=\"kp[]\" placeholder=\"КП/КР\" value=\"".$kp[$k]."\"/>
            <input type=\"text\" class=\"mr-1 mb-1\" size=\"5\" name=\"sr[]\" placeholder=\"СР\"  title=\"Количество часов самостоятельных работ работ\" value=\"".$samost[$k]."\"/>
            <input type=\"text\" class=\"mr-1 mb-1\" size=\"5\" name=\"im[]\" placeholder=\"ИМ\" title=\"Объём учебной работы с применением интерактивных методов\" value=\"".$interact[$k]."\"/>
            <input type=\"text\" class=\"mr-1 mb-1\" size=\"15\" name=\"kontr[]\" placeholder=\"Контроль\" title=\"Формы текущего контроля успеваемости\" value=\"".$control[$k]."\"/>
            <button class=\"btn btn-danger\" onclick=\"del('r".$k."')\">Удалить раздел</button>
        </div>";
   }
   
   return $n; 
}


//make html UMK
function mhu($i){
   $mysqli = new mysqli(HOST, USER, PD, BD);
   $mysqli->set_charset("utf8");
        
   if ($mysqli->connect_errno) {
      echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
      exit();
   }
   
   
   $q="SELECT * FROM umk, ur_obr, formi, fakultet, kafedra, profil, napravlenie, discipline WHERE ium=".$i." AND inp=uoi AND iform=formi AND ifak=fakulteti AND ik=kafedrai AND ipr=profili AND inap=napri AND id=di";
                                                          
   $html="<style>
   @font-face {
    font-family: TNR;
    src: url('./fonts/7454.ttf');
}
div.umk{
    font-family: TNR; 
    font-size:12pt;
}
   </style>\n<div class=\"umk\">";
   $res = $mysqli->query($q);
   
   if ($res->num_rows>0){        
        //parse result row    
        while ($row = $res->fetch_assoc()) {
            
            //work with part 1            
            $t=$this->ud($row["titul"]);
            $search=array("{date}","{nd}","{np}","{pp}","{uvo}","{fo}","{year}");
            $replace=array(date("«d» m Y",strtotime($row["dt"])),$row["nd"],$row["nn"],$row["npr"],$row["nnp"],$row["nform"],date("Y",strtotime($row["dt"])));
            $html.=str_replace($search,$replace,$t);            
            //end work with part1
            
            //work with part 2            
            $html.=$this->ud($row["celi"]);            
            //end work with part2
            
            //work with part 3
            $html.=$this->ud($row["comp"]);
            $html.=$this->pkl($row["compcb"]);
            //end work with part3
            
            //work with part 4
            $npd=json_decode($row["npd"]);
            $sem=json_decode($row["sem"]);
            $ned=json_decode($row["ned"]);
            $lek=json_decode($row["lek"]);
            $prak=json_decode($row["prak"]);
            $lab=json_decode($row["lab"]);
            $kontr=json_decode($row["kontr"]);
            $kp=json_decode($row["kp"]);
            $samost=json_decode($row["samost"]);
            $interact=json_decode($row["interact"]);
            $control=json_decode($row["control"]);
            $n=count($npd);
            
            $t="
            <style>
            th, td {border: 1px solid black;text-overflow:clip;vertical-align:bottom;font-family:TNR; font-size:12pt;font-stretch:normal;font-style:normal;font-variant: normal;font-weight:normal;} 
            
            .v {
                white-space:nowrap;
                width:10px;
            height:12px;
            text-align:left;
                padding:0px;
                margin:0px;
            text-overflow:clip;
                -webkit-transform: rotate(-90deg); 
  -moz-transform: rotate(-90deg);
  -ms-transform: rotate(-90deg);
  -o-transform: rotate(-90deg);
  transform: rotate(-90deg);}</style>
            <table style=\"border-collapse: collapse;width:100%;table-layout:fixed;\">\n<thead><tr>
            <th rowspan=\"2\" style=\"width:5%;\">№</th>
            <th rowspan=\"2\" style=\"width:40%;\">Раздел дисциплины</th>
            <th rowspan=\"2\" style=\"width:5%;\"><p class=\"v\">Семестр</p></th>
            <th rowspan=\"2\" style=\"width:5%;\"><p class=\"v\">Неделя</p></th>
            <th colspan=\"6\" style=\"text-align:center; vertical-align:middle;\">Виды учебной работы и трудоёмкость</th>
            <th rowspan=\"2\" style=\"width:5%;\"><p class=\"v\">Интерактивных методов</p></th>
            <th rowspan=\"2\" style=\"width:10%;\"><p class=\"v\">Формы текущего контроля</p></th>
            </tr>\n
            <tr>
             <th style=\"width:5%;height:180px;\" ><div class=\"v\">Лекции</div></th><th  style=\"width:5%;\"><p class=\"v\">Практика</p></th><th style=\"width:5%;\"><p class=\"v\">Лабораторные</p></th><th style=\"width:5%;\"><p class=\"v\">Контрольные</p></th><th style=\"width:5%;\"><p class=\"v\">КП/КР</p></th><th style=\"width:5%;\"><p class=\"v\">Самостоятельные</p></th>
            </tr></thead>\n";
            $sl=0;
            $sp=0;
            $slab=0;
            $sk=0;
            $skp=0;
            $ss=0;
            $si=0;
            //form row of table
            for ($k=0;$k<$n;$k++){
                $t.="<tr>\n";
                $t.="<td>".($k+1)."</td><td>".$npd[$k]."</td><td>".$sem[$k]."</td><td>".$ned[$k]."</td><td>".$lek[$k]."</td><td>".$prak[$k]."</td><td>".$lab[$k]."</td><td>".$kontr[$k]."</td><td>".$kp[$k]."</td><td>".$samost[$k]."</td><td>".$interact[$k]."</td><td>".$control[$k]."</td>\n";
                $t.="</tr>\n";
                //calc sum hours
                $sl=$sl+$lek[$k];
                $sp=$sp+$prak[$k];
                $slab=$slab+$lab[$k];
                $sk=$sk+$kontr[$k];
                $skp=$skp+$kp[$k];
                $ss=$ss+$samost[$k];
                $si=$si+$interact[$k];
            }
            //final calc
            $t.="<tr>\n";
            $t.="<td></td><td>Итого:</td><td></td><td></td><td>".$sl."</td><td>".$sp."</td><td>".$slab."</td><td>".$sk."</td><td>".$skp."</td><td>".$ss."</td><td>".$si."</td><td>".$row["konec"]."</td>\n";
            $t.="</tr>\n</table>\n";
            $html.=$t;
            //end work with part4
            
            //work with part 5
            $html.=$this->ud($row["tech"]);
            //end work with part5
            
            //work with part 6
            $html.=$this->ud($row["ocen"]);
            //end work with part6
            
            //work with part 7
            $html.=$this->ud($row["ucheb"]);
            //end work with part7
            
            //work with part 8
            $html.=$this->ud($row["mat"]);
            //end work with part8
            
            //work with part 9
            $html.=$this->ud($row["sogl1"]);
            //end work with part9
            
            //work with part 10
            $html.=$this->ud($row["sogl2"]);
            //end work with part10
            
            $html.="</div>";   
        }
           
        } else {
            
            $html.="<h1>";
            $html.="Данных об этом плане нет  в базе. <br/>Обратитесь к администратору!\n";
            $html.="</h1>";
                
        }
   
   
   return $html;
  
    
}

//print kompetencii checkbox
function pkc($str){
   $mysqli = new mysqli(HOST, USER, PD, BD);
   $mysqli->set_charset("utf8");
        
   if ($mysqli->connect_errno) {
      echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
      exit();
   }
   
   
   $q="SELECT * FROM kompetencii";
                                                          
   
   $res = $mysqli->query($q);
   //parse result row 
   $ar=json_decode($str);
   $html="<div><h2>Выберите необходимые компетенции</h2>";   
   while ($row = $res->fetch_assoc()) {
    $html.= "<div class=\"form-check form-check-inline ml-2\" style=\"border:1px solid blue;border-radius:5px;\">
  <input class=\"form-check-input\" style=\" position: relative;margin: 0 0 0 3px;\"  type=\"checkbox\" name=\"compcb[]\" id=\"inlineCheckbox".$row["ikomp"]."\" value=\"".$row["ikomp"]."\" ".(in_array($row["ikomp"], $ar)?"checked":"").">
  <label class=\"form-check-label pr-1 pl-1\"  for=\"inlineCheckbox".$row["ikomp"]."\">".$row["kodkomp"]."</label>
</div>\n";
   }
   $html.="</div>";
   
   $mysqli->close();
   
  return $html;
    
}


//print kompetencii list
function pkl($str){
   $mysqli = new mysqli(HOST, USER, PD, BD);
   $mysqli->set_charset("utf8");
        
   if ($mysqli->connect_errno) {
      echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
      exit();
   }
   
   
   $q="SELECT * FROM kompetencii";
                                                          
   
   $res = $mysqli->query($q);
   //parse result row 
   $ar=json_decode($str);
   $html="<div style=\"width:100%\"><p>ПРИОБРЕТАЕМЫЕ КОМПЕТЕНЦИИ:</p>";   
   while ($row = $res->fetch_assoc()) {
    if(in_array($row["ikomp"], $ar)){
       $html.= "<p>".$row["opis"]."( ".$row["kodkomp"]." )</p>\n"; 
    }        
   }
   $html.="</div>";
   
   $mysqli->close();
   
  return $html;
    
}


//print search result from main page
function psr($arr){
   $mysqli = new mysqli(HOST, USER, PD, BD);
   $mysqli->set_charset("utf8");
        
   if ($mysqli->connect_errno) {
      echo "Не удалось подключиться к Базе Данных: " . $mysqli->connect_error;
      exit();
   }
   
   if ($arr["nnp"]<>0){
    $where.="uoi=".$arr["nnp"]." AND ";
   }
   
   if ($arr["nform"]<>0){
    $where.="formi=".$arr["nform"]." AND ";
   }
   
   if ($arr["nf"]<>0){
    $where.="fakulteti=".$arr["nf"]." AND ";
   }
   
   if ($arr["nk"]<>0){
    $where.="kafedrai=".$arr["nk"]." AND ";
   }
   
   if ($arr["npr"]<>0){
    $where.="profili=".$arr["npr"]." AND ";
   }
   
   if ($arr["nn"]<>0){
    $where.="napri=".$arr["nn"]." AND ";
   }
   
   $q="SELECT id, nd FROM umk,discipline WHERE ".$where."id=di";
                                                          
   
   $res = $mysqli->query($q);
   
   
   $html="";
   if ($res->num_rows>0){
            $html.="<div class='list-group'>\n";
            
            while ($row = $res->fetch_assoc()) {
                
                    $html.="<a href='./info.php?discipline=".$row["id"]."' class='list-group-item list-group-item-action' >".$row["nd"]."</a>\n";
               
            }
            $html.="</div>";
        } else {
            
            $html.="<div class=''>";
            $html.="Нет дисциплин с УМК, которые соответствуют критериям поиска!\n";
            $html.="</div>";
                
        }
   
   $mysqli->close();
   
  return $html;
    
}


}



?>