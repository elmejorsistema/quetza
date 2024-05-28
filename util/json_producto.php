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


if(empty($_POST['id']))
  {
    $data = array("result" => false, "message" => "Error: variable no recibida");
    echo json_encode($data);
    return;
  }

$id = trim($_POST['id']);


if(!validate_EAN13Barcode($id))
 {
   $data = array("result" => false, "message" => "Error: el número no cumple con los estándares EAN13.");
   echo json_encode($data);
   return;
  }


$q = "select id from product where id = $id";
$o_database->query_fetch_field($q); 

if($o_database->query_field)
  $data = array("result" => false, "message" => "Error: el producto ya existe.");
else
  $data = array("result" => true,  "message" => null);

echo json_encode($data);
return;
?>