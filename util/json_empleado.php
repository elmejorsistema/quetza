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
header('Content-Type: application/json');


if(empty($_POST['empleado']))
  {
    $data = array("result" => "Error: variable no recibida.");
    echo json_encode($data);
    return;
  }

$empleado = trim($_POST['empleado']);


$q = "select * from employee where name like \"%$empleado%\"";

$o_database->query_rows($q);
$result = $o_database->query_result;
$data = array();
while($row = mysql_fetch_row($result))
    $data[] = array("value" => $row[1], "label" => $row[1], "id" => $row[0], "rate_hour" =>  $row[2],  "cuenta_nomina" =>  $row[3]);
//mail("manuel@agenteel.com","Data" , json_encode($data));
echo json_encode($data);
return;

?>