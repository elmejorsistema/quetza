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

//$q = "insert into payroll values (null, $employee_id, $rate_hour, $hours, $total_hours, '$initial', '$final', $extra_1, $comment_extra_1, $extra_2, $comment_extra_2, $extra_3, $comment_extra_3)";



$q = "select e.name, p.*, p.total_hours + extra1 + extra2 + extra3, e.cuenta_nomina from employee as e join payroll as p on e.id = p.employee_id where p.initial_date >= '$initial' and p.final_date <= '$final'";

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
$salida .= "Nómina del $initial al $final,,,,,,,,,,\r\n";

$salida .= "Empleado,Cuenta Nómina,Pago por Hora, Horas Trabajadas, Pago por Horas, Extra 1, Comentario 1, Extra 2, Comentario 2, Extra 3, Comentario 3,Total\r\n";

foreach($result as $row)
  {
    $salida .= "\"$row[0]\",\"$row[15]\",$row[3],$row[4],$row[5],$row[8],\"$row[9]\",$row[10],\"$row[11]\",$row[12],\"$row[13]\",$row[14]\r\n";
    $total0 += $row[14];
  }

$salida .="Total,,,,,,,,,,,$total0\r\n";

$path    = "/tmp/";
$n_archivo = "GN-".$o_user->id.".csv";
$archivo = fopen("$path$n_archivo", "w");
fwrite($archivo,$salida);
fclose($archivo);



$file_size = filesize("$path$n_archivo");

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: $file_size");
header("Content-Disposition: attachment; filename=\"Nomina-$initial-a-$final.csv\";");
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