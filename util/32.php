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
$o_config->control_structure_id2 = 32;


if(!checkCheckMenu($o_user->id, $o_config, $o_database)){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\"></head></html>";
  return;
  }


if(empty($_POST["menu"]))
  {
    echo "<script>alert(\"Error: variable no recibida\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
$m = $_POST["menu"];


if(empty($_POST["folio"]))
  {
    echo "<script>alert(\"Error: variable no recibida\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
$folio = $_POST["folio"];


$a_pago = json_decode($_POST['chpago_id']);

if(empty($a_pago->id))
  {
    echo "<script>alert(\"Error: variable no recibida\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
$chpago_id = $a_pago->id;


if(empty($_POST["monto"]))
{
    $monto = 0;
}
else
{
    $monto = $_POST["monto"];
}


if(empty($_POST["comentario"]))
{
    $comentario = "NULL";
}
else
{
    $comentario = "'".$_POST["comentario"]."'";
}




$q = "update alumno_has_ciclo_has_pago set ciclo_has_pago_id = $chpago_id, monto = $monto, descripcion = $comentario where id = $folio";
//echo $q;return;

$o_database->query_assign($q);
$o_config->folio = null;




///////////////////////////////////////////////

// serialize the main objects to pass them
// as session variables
// the db is traveling disconnected and needs
// to be connected when unserialised at the arrival
// point  
$_SESSION['config']   = serialize($o_config);
$_SESSION['user']     = serialize($o_user);
$_SESSION['databaseCredentials'] = serialize($o_databaseCredentials);

$_SESSION['security'] = serialize($o_security);
//$_SESSION['message']  = serialize($o_message);

// Regresa a la página desde donde se hizo la llamada
echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=16&cs2=$m\"></head></html>";

?>