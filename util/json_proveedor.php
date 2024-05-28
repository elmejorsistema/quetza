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
header('Content-Type: application/json');


if(empty($_POST['proveedor']))
  {
    $data = array("result" => "Error: variable no recibida.");
    echo json_encode($data);
    return;
  }

$proveedor = trim($_POST['proveedor']);

if(empty($_POST['all_data']))
  $all_data = false;
else
  $all_data = true;


if(!$all_data)
  $q = "select id, name from supplier where name like \"%$proveedor%\"";
else
  $q = "select * from supplier where name like \"%$proveedor%\"";

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