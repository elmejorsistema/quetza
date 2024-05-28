<?php
include "utility.php";
include "functions.php";

include "../clases.php";
include "../config/dbconfig.php";

session_name($session_name);
session_start();
/*
$data[] = array("value" => "hola", "label" => "hola", "id" => "hola"); 
header('Content-Type: application/json');
echo json_encode($data);
return;
*/
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
//$id = trim($_GET['id']);



$data=array();
/*
+-------------+-----------------------+------+-----+---------+----------------+
| Field       | Type                  | Null | Key | Default | Extra          |
+-------------+-----------------------+------+-----+---------+----------------+
| id          | bigint(20) unsigned   | NO   | PRI | NULL    | auto_increment |
| external_id | bigint(20) unsigned   | YES  |     | NULL    |                |
| supplier_id | smallint(5) unsigned  | YES  | MUL | NULL    |                |
| name        | varchar(255)          | NO   |     | NULL    |                |
| price       | decimal(8,2) unsigned | YES  |     | NULL    |                |
| para_venta  | tinyint(3) unsigned   | NO   |     | 1       |                |
+-------------+-----------------------+------+-----+---------+----------------+

+-------------+-----------------------+------+-----+---------+----------------+
| Field       | Type                  | Null | Key | Default | Extra          |
+-------------+-----------------------+------+-----+---------+----------------+
| id          | bigint(20) unsigned   | NO   | PRI | NULL    | auto_increment |
| external_id | bigint(20) unsigned   | YES  |     | NULL    |                |
| supplier_id | smallint(5) unsigned  | YES  | MUL | NULL    |                |
| name        | varchar(255)          | NO   |     | NULL    |                |
| price       | decimal(8,2) unsigned | YES  |     | NULL    |                |
| tax         | decimal(4,2) unsigned | YES  |     | NULL    |                |
| para_venta  | tinyint(3) unsigned   | NO   |     | 1       |                |
+-------------+-----------------------+------+-----+---------+----------------+
7 rows in set (0.00 sec)


6 rows in set (0.01 sec)
*/
if( is_numeric($id))
 $q = "select p.*, s.name, s.id from product as p left join supplier as s on p.supplier_id = s.id  where p.id = $id ";
else
 $q = "select p.*, s.name, s.id from product as p left join supplier as s on p.supplier_id = s.id  where p.name like \"%$id%\"";
//echo $q;
$o_database->query_rows($q);
$result = $o_database->query_result;
foreach($result as $row)
{
  $data[] = array("value" => $row[3], "label" => $row[3], "id" => $row[0], "supplier"=> $row[7],"supplier_id"=> $row[8], "price"=> $row[4], "tax"=> $row[5], "paraventa"=> $row[6]);
  //$data[] = array("id" => $row[0], "supplier"=> $row[6], "name"=> $row[3], "price"=> $row[4], "paraventa"=> $row[5]);  
  
}

echo json_encode($data);
return;
?>