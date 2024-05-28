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

//echo var_dump($_POST);return;
   
$id          = $_POST['id_id_b'];

if(empty($_POST['supplier_id_b']))
  {
    $supplier_id = "default";
  }
else
  $supplier_id = $_POST['supplier_id_b'];


if(empty($_POST['ventapublico_b']))
  {
    $ventapublico = 0;
    $precio       = "default";
    $tax          = "default";
  }
else
  {
    $ventapublico = 1;
    $precio       = $_POST['precio_b'];

    if(empty($_POST['tax_b']))
      $tax          = "default";
    else
      $tax          = $_POST['tax_b'];
  }

//$product_name = $_POST['product_name_b'];
$product_name = trim(str_replace("'", "\'",$_POST['product_name_b']));
$product_name = trim(str_replace("\"", "\\\"",$product_name));


$q = "update product set name = '$product_name', supplier_id = $supplier_id, price = $precio, tax = $tax, para_venta = $ventapublico where id = $id";
//echo $q;return;
$o_database->query_assign($q);


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
  

echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=$o_config->control_structure_id1&cs2=$o_config->control_structure_id2&ovly=buscar\"></head></html>";


return;


?>