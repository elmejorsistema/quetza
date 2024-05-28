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
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\"></head></html>";
  return;
}


// Check that this is a valid menu
//////////////////////////////////////////////

$o_config->control_structure_id1 = 16;
$o_config->control_structure_id2 = 19;
if(!checkCheckMenu($o_user->id, $o_config, $o_database)){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\"></head></html>";
  return;
  }


// Lo campos requeridos
if(empty($_POST["alumno_id"]))
  {
    echo "<script>alert(\"Error: variable no recibida\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }


$o_config->alumno_id = $_POST["alumno_id"];


if(empty($_POST["menu"]))
  {
    echo "<script>alert(\"Error: variable no recibida\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
$m = $_POST["menu"];


///////////////////////////////////////////////

// serialize the main objects to pass them
// as session variables
// the db is traveling disconnected and needs
// to be connected when unserialised at the arrival
// point  
$_SESSION['config']   = serialize($o_config);
$_SESSION['user']     = serialize($o_user);
$_SESSION['database'] = serialize($o_database);
$_SESSION['security'] = serialize($o_security);
//$_SESSION['message']  = serialize($o_message);

// Regresa a la página desde donde se hizo la llamada
echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=16&cs2=$m\"></head></html>";

?>