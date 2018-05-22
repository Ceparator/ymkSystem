<?php

require_once("./func.php");

$us= new US;
if ($us->auth($_COOKIE["login"],$_COOKIE["password"])){ 
    
    //delete from admin panel
    if (isset($_GET["t"]) and ($us->status==3)){
        $us->dle($_GET["t"],$_GET["n"],$_GET["i"]);
    }
    
} 

header("Location: index.php");

?>