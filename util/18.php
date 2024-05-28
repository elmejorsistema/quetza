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
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\"></head></html>";
  return;
}



// Check that this is a valid menu
//////////////////////////////////////////////

$o_config->control_structure_id1 = 16;
$o_config->control_structure_id2 = 18;
if(!checkCheckMenu($o_user->id, $o_config, $o_database)){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\"></head></html>";
  return;
  }


//echo var_dump($_POST);
//return;



if(empty($_POST['chpago_id']))
  {
      echo "<script>alert(\"Error: variable no recibida\")</script>";
      echo "<script>history.go(-1)</script>>";
      return;
  }

if(empty($_POST['monto']))
  {
      echo "<script>alert(\"Error: variable no recibida\")</script>";
      echo "<script>history.go(-1)</script>>";
      return;
  }
$monto = $_POST['monto'];




if(empty($o_config->alumno_id))
  {
      echo "<script>alert(\"Error: variable* no recibida\")</script>";
      echo "<script>history.go(-1)</script>>";
      return;
  }


if(empty($_POST['comentario']))
    $comentario = 'null';
else
    $comentario = "'".$_POST['comentario']."'";



$a_pago = json_decode($_POST['chpago_id']);
//echo var_dump($a_pago); return;
/*
describe alumno_has_ciclo_has_pago;
+----------------------+----------------------+------+-----+---------+----------------+
| Field                | Type                 | Null | Key | Default | Extra          |
+----------------------+----------------------+------+-----+---------+----------------+
| id                   | int(10) unsigned     | NO   | PRI | NULL    | auto_increment |
| ciclo_has_pago_id    | int(10) unsigned     | NO   | MUL | NULL    |                |
| alumno_id            | bigint(20) unsigned  | NO   | MUL | NULL    |                |
| ciclo_has_materia_id | smallint(5) unsigned | YES  | MUL | NULL    |                |
| descripcion          | varchar(64)          | YES  |     | NULL    |                |
| monto                | decimal(7,2)         | NO   |     | 0.00    |                |
| fecha                | date                 | YES  |     | NULL    |                |
| pagado               | tinyint(3) unsigned  | NO   |     | 0       |                |
+----------------------+----------------------+------+-----+---------+----------------+
8 rows in set (0.00 sec)
*/

$q = "insert into alumno_has_ciclo_has_pago values (null, ".$a_pago->id.", $o_config->alumno_id, null, $comentario, ".$monto.", now(), ".$o_user->id." )";
$o_database->query_assign($q);

if($o_database->query_error)
{
    echo "<script>alert(\"Hubo un error al registrar el pago\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
}

echo   "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=16&cs2=17\"></head></html>";

return;

?>