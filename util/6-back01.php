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

$o_config->control_structure_id1 = 1;
$o_config->control_structure_id2 = 6;
if(!checkCheckMenu($o_user->id, $o_config, $o_database)){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\"></head></html>";
  return;
  }

//var_dump($o_config);return;



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
	    if($valor >= -1 and $valor <= 10)
	      {
		$q= "update alumno_has_evaluacion set calif_".$a_valores_campos[2]." = ".$valor." where id = ".$a_valores_campos[1];
		$o_database->query_assign($q);
		//echo $q . "<br />";
	      }
	  }
	elseif($a_valores_campos[0] == "ahef")
	  {
	    if($valor >= 1)
	      {
		$q= "update alumno_has_evaluacion set faltas_".$a_valores_campos[2]." = ".$valor." where id = ".$a_valores_campos[1];
		$o_database->query_assign($q);
		//echo $q . "<br />";
	      }
	    else
	      {
		$q= "update alumno_has_evaluacion set faltas_".$a_valores_campos[2]." = null where id = ".$a_valores_campos[1];
		$o_database->query_assign($q);
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




//$q= "update alumno_has_evaluacion set calif_5 = if(if(calif_1, calif_1, 0) + if(calif_2, calif_2, 0) + if(calif_3, calif_3, 0) + if(calif_4, calif_4, 0) >= 24, round((if(calif_1, calif_1, 0) + if(calif_2, calif_2, 0) + if(calif_3, calif_3, 0) + if(calif_4, calif_4, 0)) / 4), floor((if(calif_1, calif_1, 0) + if(calif_2, calif_2, 0) + if(calif_3, calif_3, 0) + if(calif_4, calif_4, 0)) / 4)) where ciclo_has_materia_id = $chm_id";

/*
$q= "
update alumno_has_evaluacion set calif_5 =
if(
   if(calif_1, calif_1, 0) + if(calif_2, calif_2, 0) + if(calif_3, calif_3, 0) + if(calif_4, calif_4, 0) >= 24, 
    round((if(calif_1, calif_1, 0) + if(calif_2, calif_2, 0) + if(calif_3, calif_3, 0) + if(calif_4, calif_4, 0)) / 4), 
    floor((if(calif_1, calif_1, 0) + if(calif_2, calif_2, 0) + if(calif_3, calif_3, 0) + if(calif_4, calif_4, 0)) / 4)) 
where ciclo_has_materia_id = $chm_id";
*/

/*
$q= "
update alumno_has_evaluacion set calif_5 =
if(calif_1=-1 or calif_2=-1 or calif_3=-1 or calif_4=-1, -1, 
if(
   if(calif_1, calif_1, 0) + if(calif_2, calif_2, 0) + if(calif_3, calif_3, 0) + if(calif_4, calif_4, 0) >= 24, 
    round((if(calif_1, calif_1, 0) + if(calif_2, calif_2, 0) + if(calif_3, calif_3, 0) + if(calif_4, calif_4, 0)) / 4), 
    floor((if(calif_1, calif_1, 0) + if(calif_2, calif_2, 0) + if(calif_3, calif_3, 0) + if(calif_4, calif_4, 0)) / 4)))
where ciclo_has_materia_id = $chm_id";
*/


/* Esto era cuando no se calculaban decimales */
/*
$q= "
update alumno_has_evaluacion set calif_5 =
if(
   if(calif_1 >-1, calif_1, 0) + if(calif_2 >-1, calif_2, 0) + if(calif_3 >-1, calif_3, 0) + if(calif_4 >-1, calif_4, 0) >= 24, 
    round((if(calif_1 >-1, calif_1, 0) + if(calif_2 >-1, calif_2, 0) + if(calif_3 >-1, calif_3, 0) + if(calif_4 >-1, calif_4, 0)) / 4), 
    floor((if(calif_1 >-1, calif_1, 0) + if(calif_2 >-1, calif_2, 0) + if(calif_3 >-1, calif_3, 0) + if(calif_4 >-1, calif_4, 0)) / 4))
where ciclo_has_materia_id = $chm_id";
*/


/* Califiaciones con un decimal */
/*
$q= "
update alumno_has_evaluacion set calif_5 = round(
(if(calif_1 >= 0, calif_1, 0) + if(calif_2 >= 0, calif_2, 0) + if(calif_3 >= 0, calif_3, 0) + if(calif_4 >= 0, calif_4, 0)) / 4, 1)
where ciclo_has_materia_id = $chm_id";
*/

/* Cálculo según Oficio No. DGAIR/DAC/009/2019 */

$q= "
update alumno_has_evaluacion set calif_5 =
if(
cast( substr( substring_index( 
  cast( (if(calif_1 >= 0, calif_1, 0) + if(calif_2 >= 0, calif_2, 0) + if(calif_3 >= 0, calif_3, 0) + if(calif_4 >= 0, calif_4, 0)) / 4 as char), '.', -1), 2, 1) as unsigned) 
    >= 5,
    round((if(calif_1 >= 0, calif_1, 0) + if(calif_2 >= 0, calif_2, 0) + if(calif_3 >= 0, calif_3, 0) + if(calif_4 >= 0, calif_4, 0)) / 4,1),
    truncate((if(calif_1 >= 0, calif_1, 0) + if(calif_2 >= 0, calif_2, 0) + if(calif_3 >= 0, calif_3, 0) + if(calif_4 >= 0, calif_4, 0)) / 4,1)
)
where ciclo_has_materia_id = $chm_id";


$q= "
update alumno_has_evaluacion set calif_5 = round((if(calif_1 >= 0, calif_1, 0) + if(calif_2 >= 0, calif_2, 0) + if(calif_3 >= 0, calif_3, 0) + if(calif_4 >= 0, calif_4, 0)) / 4,1)
where ciclo_has_materia_id = $chm_id";







/* Esto fue para probar el comando */

/*


select  substring_index ( cast( (if(calif_1 >= 0, calif_1, 0) + if(calif_2 >= 0, calif_2, 0) + if(calif_3 >= 0, calif_3, 0) + if(calif_4 >= 0, calif_4, 0)) / 4) as char), '.', 2) fro alumno_has_avaluacion; 


select (if(calif_1 >= 0, calif_1, 0) + if(calif_2 >= 0, calif_2, 0) + if(calif_3 >= 0, calif_3, 0) + if(calif_4 >= 0, calif_4, 0)) / 4 from alumno_has_evaluacion where id = 51813; 



select  substring_index( cast( (if(calif_1 >= 0, calif_1, 0) + if(calif_2 >= 0, calif_2, 0) + if(calif_3 >= 0, calif_3, 0) + if(calif_4 >= 0, calif_4, 0)) / 4 as char), '.', -1) from alumno_has_evaluacion where id = 51813; 



select  substr( substring_index( 
  cast( (if(calif_1 >= 0, calif_1, 0) + if(calif_2 >= 0, calif_2, 0) + if(calif_3 >= 0, calif_3, 0) + if(calif_4 >= 0, calif_4, 0)) / 4 as char), '.', -1), 2, 1) from alumno_has_evaluacion where id = 51813; 

select  cast( substr( substring_index( 
  cast( (if(calif_1 >= 0, calif_1, 0) + if(calif_2 >= 0, calif_2, 0) + if(calif_3 >= 0, calif_3, 0) + if(calif_4 >= 0, calif_4, 0)) / 4 as char), '.', -1), 2, 1) as unsigned) from alumno_has_evaluacion where id = 51813; 




select (if(calif_1 >= 0, calif_1, 0) + if(calif_2 >= 0, calif_2, 0) + if(calif_3 >= 0, calif_3, 0) + if(calif_4 >= 0, calif_4, 0)) / 4 from alumno_has_evaluacion where id = 51813; 




select

if(
cast( substr( substring_index( 
  cast( (if(calif_1 >= 0, calif_1, 0) + if(calif_2 >= 0, calif_2, 0) + if(calif_3 >= 0, calif_3, 0) + if(calif_4 >= 0, calif_4, 0)) / 4 as char), '.', -1), 2, 1) as unsigned) 
    >=5,
    round((if(calif_1 >= 0, calif_1, 0) + if(calif_2 >= 0, calif_2, 0) + if(calif_3 >= 0, calif_3, 0) + if(calif_4 >= 0, calif_4, 0)) / 4,1),
    truncate((if(calif_1 >= 0, calif_1, 0) + if(calif_2 >= 0, calif_2, 0) + if(calif_3 >= 0, calif_3, 0) + if(calif_4 >= 0, calif_4, 0)) / 4,1)
)

from alumno_has_evaluacion where id = 51813; 


*/


$o_database->query_assign($q);

//echo $q;return;

//echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=$o_config->control_structure_id1&cs2=$o_config->control_structure_id2\"></head></html>";
echo   "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=".$_POST['cs1']."&cs2=".$_POST['cs2']."&id=$chm_id\"></head></html>";



return;?>