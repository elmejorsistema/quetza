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

//echo var_dump($_POST);
if(empty($_POST['total_c_tax']))
  {
    echo "<script>alert(\"Error: variable no recibida1\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $total_c_tax = trim($_POST['total_c_tax']);

if(empty($_POST['tax']))
  {
    $tax = 0;
  }
else
  $tax = trim($_POST['tax']);

if(empty($_POST['comment']))
  {
    echo "<script>alert(\"Error: variable no recibida3\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $comment = trim($_POST['comment']);

if(empty($_POST['fecha']))
  {
    echo "<script>alert(\"Error: variable no recibida4\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $fecha = trim($_POST['fecha']);
/*
+-------------+-----------------------+------+-----+---------+----------------+
| Field       | Type                  | Null | Key | Default | Extra          |
+-------------+-----------------------+------+-----+---------+----------------+
| id          | smallint(5) unsigned  | NO   | PRI | NULL    | auto_increment |
| total_s_tax | decimal(8,2) unsigned | NO   |     | 0.00    |                |
| total_c_tax | decimal(8,2) unsigned | NO   |     | 0.00    |                |
| tax         | decimal(4,2) unsigned | NO   |     | 0.00    |                |
| comment     | varchar(255)          | NO   |     | NULL    |                |
| date        | date                  | NO   |     | NULL    |                |
+-------------+-----------------------+------+-----+---------+----------------+
6 rows in set (0.00 sec)
*/
$total_s_tax = round($total_c_tax/(1+($tax/100)), 2);

$q = "insert into expense values (null, $total_s_tax, $total_c_tax, $tax, '$comment', '$fecha')";
//echo $q;return;
$o_database->query_assign($q);
$nueva_compra_id = mysql_insert_id();
if($o_database->query_error)
  {
    $o_message = new message();
    echo "<script>alert(\"".$o_message->show_message(5,$o_database)."\")</script>";
    echo "<script>history.go(-1)</script>";
    return;
  }


if(!empty($_POST['cs1']))
  $o_config->control_structure_id1 = $_POST['cs1'];
if(!empty($_POST['cs2']))
  $o_config->control_structure_id2 = $_POST['cs2'];
  

echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=$o_config->control_structure_id1&cs2=$o_config->control_structure_id2&ovly=nuevo\"></head></html>";


return;


?>