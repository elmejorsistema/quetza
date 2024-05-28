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
   


             
if(empty($_POST['crearid']))
  {
    $id          = $_POST['id'];
    $external_id = $_POST['id'];
  }
else
  {
    $q = "select id from next_id";
    $o_database->query_fetch_field($q); 

    $id_tmp = $o_database->query_field;

    $id =generate_EAN13BarcodeFromSingle($id_tmp);
    $external_id = "default";

    $q = "update next_id set id = id + 1";
    $o_database->query_assign($q); 
  }

if(empty($_POST['supplier_id']))
  {
    $supplier_id = "default";
  }
else
  $supplier_id = $_POST['supplier_id'];


if(empty($_POST['ventapublico']))
  {
    $precio       = "default";
    $tax          = "default";
    $ventapublico = 0;
  }
else
  {
    $ventapublico = 1;
    $precio       = $_POST['precio'];
    
   if(empty($_POST['tax']))
      $tax          = "default";
    else
      $tax          = $_POST['tax'];

  }

$product_name = trim(str_replace("'", "\'",$_POST['product_name']));
$product_name = trim(str_replace("\"", "\\\"",$product_name));


$q = "insert into product values ($id, $external_id, $supplier_id, '$product_name', $precio, $tax, $ventapublico)";
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
  

echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=$o_config->control_structure_id1&cs2=$o_config->control_structure_id2&ovly=nuevo\"></head></html>";


return;


?>