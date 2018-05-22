<?php

require_once("./func.php");

// include autoloader
require_once ("dompdf/autoload.inc.php");
use Dompdf\Dompdf;


$us= new US;
if ($us->auth($_COOKIE["login"],$_COOKIE["password"])){ 

//make html UMK
$html=$us->mhu($_GET["i"]);    


// instantiate and use the dompdf class
$dompdf = new Dompdf();
//$dompdf->set_option('defaultFont', 'DejaVu Sans');

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream("УМК.pdf");
//echo($html);    

}

?> 