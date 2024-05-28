<?php

include "utility.php";
include "functions.php";

include "../clases.php";
include "../config/dbconfig.php";

//mail("manuel@agenteel.com", "hola", "autocomplete");
//return;

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
header('Content-Type: application/json');


if(empty($_POST['alumno']))
  {
    $data = array("result" => "Error: variable no recibida.");
    echo json_encode($data);
    return;
  }

$alumno = trim($_POST['alumno']);


// Se separan las palabras de la consulta
$a_alumno = explode(" " , $alumno);

$es_numerico = false;


if(count($a_alumno) == 1 and is_numeric($a_alumno[0]))
  {
      //$q = "select id, concat(concat(concat(concat(nombre, ' '), 1_apellido), ' '), 2_apellido) from alumno where id = $a_alumno[0] and estatus = 'Activo'";
      $q = "select id, concat(concat(concat(concat(nombre, ' '), 1_apellido), ' '), 2_apellido) from alumno where id = $a_alumno[0]";

      
    $es_numerico = true;
  }

if(!$es_numerico)
  {
// Para evitar consultas muy tardadas se ignoran consultas
// con palabras de menos de 3 letras 
foreach($a_alumno as $value)
  {
    if(strlen($value) < 4)
      {
	$data = array();
	echo json_encode($data);
	return;
      }
  }


    // Se construye el comando de consulta
    
    
//$q = "select id, concat(concat(concat(concat(nombre, ' '), 1_apellido), ' '), 2_apellido) from alumno  where estatus = 'Activo' and (";

$q = "select id, concat(concat(concat(concat(nombre, ' '), 1_apellido), ' '), 2_apellido) from alumno  where (";



    $contador = 0;
    foreach($a_alumno as $value)
      {
	if($contador > 0)
      $q .= " and ";
	
	$contador ++;
	$q .= "(nombre like \"%$value%\" or 1_apellido like \"%$value%\" or 2_apellido like \"%$value%\")";
      }
    $q .= ")";
    
  }// Termina si 



$o_database->query_rows($q);
$result = $o_database->query_result;
$data = array();
foreach($result as $row)
  {
    //$data[] = array("value" => $row[1], "label" => $row[1], "id" => $row[0]);
      //$value = $row[1]." [".$row[2]."] [".$row[3]."]";
    $data[] = array("id" => $row[0],  "label" => $row[1], "value" => $row[1]);
  }
//mail("manuel@agenteel.com","Data" , json_encode($data));
echo json_encode($data);
return;

$data[] = array("value" => $row[1], "label" => $row[1], "id" => $row[0]);




    

if(empty($_POST['all_data']))
  $all_data = false;
else
  $all_data = true;


if(!$all_data)
  $q = "select id, concat(concat(concat(concat(nombre, ' '), 1_apellido), ' '), 2_apellido)  from alumno  where nombre like \"%$alumno%\" or 1_apellido like \"%$alumno%\" or 2_apellido like \"%$alumno%\"";


$o_database->query_rows($q);
$result = $o_database->query_result;
$data = array();
foreach($result as $row)
  {
    //$data = array("result" => "Datos recibidos: $proveedor");
    if(!$all_data)
      $data[] = array("value" => $row[1], "label" => $row[1], "id" => $row[0]);
    else
      $data[] = array(
"value"         => $row[1],
"label"         => $row[1],
"id"            => $row[0],
"rfc"           => $row[2],
"calle"         => $row[3],
"no_exterior"   => $row[4],
"no_interior"   => $row[5],
"colonia"       => $row[6],
"localidad"     => $row[7],
"municipio"     => $row[8],
"estado_id"     => $row[9],
"pais_id"       => $row[10],
"codigo_postal" => $row[11],
"telefono"      => $row[12],
"email"         => $row[13],
"pagina_web"    => $row[14]);
    }
//mail("manuel@agenteel.com","Data" , json_encode($data));
echo json_encode($data);
return;

?>