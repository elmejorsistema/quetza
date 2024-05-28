<?php
include "utility.php";
include "functions.php";

include "../clases.php";
include "../config/dbconfig.php";

session_name($session_name);
session_start();


// the main object arriving
////////////////////////////////////////////////
if(empty($_SESSION['security']) || empty($_SESSION['config']) || empty($_SESSION['user']) || empty($_SESSION['database'])){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=logout.php\" ></head></html>";
  return;
}else{
 $o_config   = unserialize($_SESSION['config']);
 $o_user     = unserialize($_SESSION['user']);
 $o_security = unserialize($_SESSION['security']);
 $o_database = unserialize($_SESSION['database']);
}


// VERY IMPORTANT //////////////////////////////
// connect the database since is not possible
// serialize/unserialize resources
$o_database->initialconnect(); 
///////////////////////////////////////////////



// Check that this is a valid session
//////////////////////////////////////////////
if(!$o_security->checkSession($o_user->id, $o_user->security_id,session_id(),$o_database)){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=index.html\"></head></html>";
  return;
}

//echo var_dump($_POST); return;

if(empty($_POST['inicial_g']))
  {
    echo "<script>alert(\"Error: variable no recibida5\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $initial = trim($_POST['inicial_g']);


if(empty($_POST['final_g']))
  {
    echo "<script>alert(\"Error: variable no recibida6\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $final = trim($_POST['final_g']);


//var_dump($_POST);RETURN;

$q = "select comment, date, total_s_tax,round(total_s_tax*(tax/100),2),total_c_tax from expense where date >= '$initial' and date <= '$final'";


//echo $q;return;
$o_database->query_rows($q);

if($o_database->query_error)
  {
    $o_message = new message();
    echo "<script>alert(\"".$o_message->show_message(5,$o_database)."\")</script>";
    echo "<script>history.go(-1)</script>";
    return;
  }

$result = $o_database->query_result;
$total0 = 0;
$salida = null;
$salida .= "Reporte de Gastos Fijos del $initial al $final,,,,\r\n";

$salida .= "Concepto,Fecha,Total sin Impuestos,Impuesto,Total\r\n";

while($row = mysql_fetch_row($result))
  {
    $salida .= "\"$row[0]\",\"$row[1]\",$row[2],$row[3],$row[4]\r\n";
    $total0 += $row[4];
  }

$salida .="Total,,,,$total0\r\n";

$path    = "/tmp/";
$n_archivo = "GG-".$o_user->id.".csv";
$archivo = fopen("$path$n_archivo", "w");
fwrite($archivo,$salida);
fclose($archivo);



$file_size = filesize("$path$n_archivo");

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: $file_size");
header("Content-Disposition: attachment; filename=\"Gastos-Fijos-$initial-a-$final.csv\";");
header("Content-type: text/comma-separated-values");

readFile("$path$n_archivo");

/*


if(!empty($_POST['cs1_b']))
  $o_config->control_structure_id1 = $_POST['cs1_b'];
if(!empty($_POST['cs2_b']))
  $o_config->control_structure_id2 = $_POST['cs2_b'];
  

echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=$o_config->control_structure_id1&cs2=$o_config->control_structure_id2&ovly=generar\"></head></html>";
*/

return;


?>