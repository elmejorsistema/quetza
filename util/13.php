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

//var_dump($o_config);return;

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



// Check that this is a valid menu
//////////////////////////////////////////////

$o_config->control_structure_id1 = 8;
$o_config->control_structure_id2 = 13;
if(!checkCheckMenu($o_user->id, $o_config, $o_database)){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\"></head></html>";
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

//var_dump($_POST); exit();

foreach ($_POST as $key=>$valor)
  {
    if(substr($key,0,10) == 'evaluacion')
    {
      $q= "update ciclo set ".$key." = 1 where id = \"".$o_config->ciclo."\" and tipo = \"".$o_config->tipo."\"";
      $o_database->query_assign($q);
    }
  }

if(isset($_POST['fechaboletas'])){

  $dt_fecha_boletas = new DateTime($_POST['fechaboletas']);
  $fecha_boletas = $dt_fecha_boletas->format("Y-m-d");

  $q = "update ciclo set fecha_boletas = \"$fecha_boletas\" where id = \"".$o_config->ciclo."\" and tipo = \"".$o_config->tipo."\"";
  $o_database->query_assign($q);

}


if(isset($_POST['nombrepuesto'])){
  $nombre_puesto=$_POST['nombrepuesto'];

  $q = "update ciclo set nombre_puesto = \"$nombre_puesto\" where id = \"".$o_config->ciclo."\" and tipo = \"".$o_config->tipo."\"";
  $o_database->query_assign($q);
}


if(isset($_POST['tituloacademico'])){
  $titulo_academico=$_POST['tituloacademico'];

  $q = "update ciclo set titulo_academico = \"$titulo_academico\" where id = \"".$o_config->ciclo."\" and tipo = \"".$o_config->tipo."\"";
  $o_database->query_assign($q);
}

if(isset($_POST['user'])){
  $user_id=$_POST['user'];

  $q = "update ciclo set user_id= $user_id where id = \"".$o_config->ciclo."\" and tipo = \"".$o_config->tipo."\"";
  $o_database->query_assign($q);
}
      
echo   "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=".$_POST['cs1']."&cs2=".$_POST['cs2']."\"></head></html>";

//echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=$o_config->control_structure_id1&cs2=$o_config->control_structure_id2\"></head></html>";

return;

 

?>