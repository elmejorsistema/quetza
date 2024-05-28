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


$o_config->cs1 = 1;
$o_config->cs1 = 3;
// Check that this is a valid menu
//////////////////////////////////////////////
if(!checkCheckMenu($o_user->id, $o_config, $o_database)){
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

foreach ($_POST as $key=>$valor)
  {
    $a_valores_campos = preg_split ("/-/", $key);
    //var_dump($a_valores_campos)."<br />";// return;

    if(is_numeric($valor))
      {
	if($a_valores_campos[0] == "ahe")
	  {
	    if($valor >= 0 and $valor <= 10)
	      {
		$q= "update alumno_has_evaluacion set calif_".$a_valores_campos[2]." = ".$valor." where id = ".$a_valores_campos[1];
		$o_database->query_assign($q);
		//echo $q . "<br />";
	      }
	  }
	elseif($a_valores_campos[0] == "ahef")
	  {
	    if($valor >= 0)
	      {
		$q= "update alumno_has_evaluacion set faltas_".$a_valores_campos[2]." = ".$valor." where id = ".$a_valores_campos[1];
		$o_database->query_assign($q);
		//echo $q . "<br />";
	      }
	  }
	elseif($a_valores_campos[0] == "chm_id")
	  {
	    $chm_id =  $valor;
	  }
      }
  }
      

////////////////////////////////////////
// Calcula las calificaciones finales //
////////////////////////////////////////



// update alumno_has_evaluacion set calif_5 = if( calif_1 + calif_2 + calif_3 + calif_4 >= 24, round((calif_1 + calif_2 + calif_3 + calif_4) / 4), floor((calif_1 + calif_2 + calif_3 + calif_4) / 4));


//update alumno_has_evaluacion set calif_5 = if(if(calif_1, calif_1, 0) + if(calif_2, calif_2, 0) + if(calif_3, calif_3, 0) + if(calif_4, calif_4, 0) >= 24, round((if(calif_1, calif_1, 0) + if(calif_2, calif_2, 0) + if(calif_3, calif_3, 0) + if(calif_4, calif_4, 0)) / 4), floor((if(calif_1, calif_1, 0) + if(calif_2, calif_2, 0) + if(calif_3, calif_3, 0) + if(calif_4, calif_4, 0)) / 4));




$q= "update alumno_has_evaluacion set calif_5 = if(if(calif_1, calif_1, 0) + if(calif_2, calif_2, 0) + if(calif_3, calif_3, 0) + if(calif_4, calif_4, 0) >= 24, round((if(calif_1, calif_1, 0) + if(calif_2, calif_2, 0) + if(calif_3, calif_3, 0) + if(calif_4, calif_4, 0)) / 4), floor((if(calif_1, calif_1, 0) + if(calif_2, calif_2, 0) + if(calif_3, calif_3, 0) + if(calif_4, calif_4, 0)) / 4)) where ciclo_has_materia_id = $chm_id";


$o_database->query_assign($q);

//echo $q;

//echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=$o_config->control_structure_id1&cs2=$o_config->control_structure_id2\"></head></html>";
echo   "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=".$_POST['cs1']."&cs2=".$_POST['cs2']."&id=$chm_id\"></head></html>";



return;?>