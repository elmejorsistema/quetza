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

//var_dump($o_config);return;

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

//echo var_dump($_POST);
//manuelgaudencio36   

//var_dump($_POST);
//return;

if(empty($_POST))
  {
    echo "<script>alert(\"Error: variable no recibida\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }


$o_config->control_structure_id2 = 9;


$q = "update ciclo set evaluacion1 = 0 ,evaluacion2 = 0 ,evaluacion3 = 0 ,evaluacion4 = 0 where id = \"".$o_config->ciclo."\" and tipo = \"".$o_config->tipo."\"";
$o_database->query_assign($q);


foreach ($_POST as $key=>$valor)
  {
    $q= "update ciclo set ".$key." = 1 where id = \"".$o_config->ciclo."\" and tipo = \"".$o_config->tipo."\"";
    $o_database->query_assign($q);
  }
      
echo   "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=".$_POST['cs1']."&cs2=".$_POST['cs2']."\"></head></html>";

//echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=$o_config->control_structure_id1&cs2=$o_config->control_structure_id2\"></head></html>";

return;

 

?>