<?php
include "utility.php";
include "functions.php";

include "../clases.php";
include "../config/dbconfig.php";

session_name($session_name);
session_start();


// the main object arriving
////////////////////////////////////////////////
if(empty($_SESSION['security']) || empty($_SESSION['config']) || empty($_SESSION['user']) || empty($_SESSION['databaseCredentials'])){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\" ></head></html>";
  return;
}else{
 $o_config   = unserialize($_SESSION['config']);
 $o_user     = unserialize($_SESSION['user']);
 $o_security = unserialize($_SESSION['security']);
 $o_databaseCredentials  = unserialize($_SESSION['databaseCredentials']);
    
}


// VERY IMPORTANT //////////////////////////////
// connect the database since is not possible
// serialize/unserialize resources
$o_database  = new database($o_databaseCredentials->db_host,  $o_databaseCredentials->db_name,
    $o_databaseCredentials->db_user,  $o_databaseCredentials->db_password);

///////////////////////////////////////////////



// Check that this is a valid session
//////////////////////////////////////////////
if(!$o_security->checkSession($o_user->id, $o_user->security_id,session_id(),$o_database)){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=index.html\"></head></html>";
  return;
}

//echo var_dump($_POST); return;

if(empty($_POST['inicial_c']))
  {
    echo "<script>alert(\"Error: variable no recibida5\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $initial = trim($_POST['inicial_c']);


if(empty($_POST['final_c']))
  {
    echo "<script>alert(\"Error: variable no recibida6\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $final = trim($_POST['final_c']);


//var_dump($_POST);RETURN;

//$q = "select s.id, s.identifier, date, u.name, ss.name, pt.name, cr.name, total_s_tax, round(total_s_tax*(tax/100),2),total_c_tax, s.comment from sale as s join status_sale as ss on ss.id = s.status_sale_id join payment_type as pt on pt.id = s.payment_type_id left join card as cr on cr.id = s.card_id join user as u on u.id = s.user_id where date >= '$initial' and date <= '$final' order by s.date , s.id";


$q = "select p.id, p.identifier, date, u.name, sp.name, total_s_tax, round(total_s_tax*(tax/100),2),total_c_tax, p.comment from purchase as p join status_purchase as sp on sp.id = p.status_purchase_id join user as u on u.id = p.user_id where date >= '$initial' and date <= '$final' order by p.date , p.id";



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
$salida .= "Reporte de Compras del $initial al $final,,,,,,,,\r\n";

$salida .= "ID,Identificador,Fecha,Comprador,Estatus,Total sin Impuestos,Impuestos,Total,Comentario\r\n";

foreach($result as $row)
  {
    $salida .= "$row[0],\"$row[1]\",$row[2],\"$row[3]\",\"$row[4]\",$row[5],$row[6],$row[7],\"$row[8]\"\r\n";
    $total0 += $row[7];
  }

$salida .="Total,,,,,,,$total0\r\n";

$path    = "/tmp/";
$n_archivo = "GC-".$o_user->id.".csv";
$archivo = fopen("$path$n_archivo", "w");
fwrite($archivo,$salida);
fclose($archivo);



$file_size = filesize("$path$n_archivo");

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: $file_size");
header("Content-Disposition: attachment; filename=\"Compras-$initial-a-$final.csv\";");
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