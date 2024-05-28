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

//echo var_dump($_GET);return;
   

if(empty($_GET['sale_id']))
{
 echo "<script>alert(\"Error: variable no recibida\")</script>";
 echo "<script>history.go(-1)</script>>";
 return;
}
else
  $sale_id    =  $_GET['sale_id'];

if(empty($_GET['product_id']))
{
 echo "<script>alert(\"Error: variable no recibida\")</script>";
 echo "<script>history.go(-1)</script>>";
 return;
}
else
  $product_id =  $_GET['product_id'];

if(empty($_GET['quantity']))
{
  /*echo "<script>alert(\"Error: variable no recibida\")</script>";
 echo "<script>history.go(-1)</script>>";
 return;*/
  $quantity   = 0;
}
else
  $quantity   =  $_GET['quantity'];


//echo var_dump($_GET);return;
//$sale_id    =  $_GET['sale_id'];
//$product_id =  $_GET['product_id'];
//$quantity   =  $_GET['quantity'];

// Primero verificamos si ese producto ya existe en la venta
$q = "select id from sale_has_product where sale_id = $sale_id and product_id = $product_id";
$o_database->query_fetch_field($q);
$shp_id = $o_database->query_field;
$recalcula = false;
// Sí existe la venta
if($shp_id)
  {
    $recalcula = true;
    $q = "select quantity from sale_has_product where id = $shp_id";
    $o_database->query_fetch_field($q);
    $current_quantity = $o_database->query_field;

    //echo "$current_quantity || $quantity";return;
    // ¿La nueva cantidad es válida?
    if($current_quantity + $quantity < 1)
      {
	$q = "delete from sale_has_product where id = $shp_id";
	$o_database->query_assign($q);
      }
    else
      {
	$q = "update sale_has_product set quantity = quantity + $quantity where id = $shp_id";
	$o_database->query_assign($q);
      }
  }
else
  {
     // ¿La nueva cantidad es válida?
    if($quantity > 0)
      {
	$recalcula = true;
	$q = "insert into sale_has_product values (null, $sale_id, $product_id, $quantity, (select price from product where id = $product_id), (select ifnull(tax,0) from product where id = $product_id))";
	//	echo $q;
	$o_database->query_assign($q);
	if($o_database->query_error)
	  {
	    echo "Error: $q";
	    return;
	  }
      }
  }

if($recalcula)
  {
    $q = "update sale set total_s_tax = (select round(sum(quantity*((price/(1+(tax/100))))),2) from sale_has_product where sale_id = $sale_id) where id = $sale_id";
    $o_database->query_assign($q);

    $q = "update sale set tax  = (select round(sum(quantity*((price/(1+(tax/100)))*tax/100)),2) from sale_has_product where sale_id = $sale_id) where id = $sale_id";
    $o_database->query_assign($q);

    $q = "update sale set total_c_tax = total_s_tax + tax  where id = $sale_id";
    $o_database->query_assign($q);
  }


if(!empty($_GET['cs1']))
  $cs1 = $_GET['cs1'];
else
  $cs1 = null;
if(!empty($_GET['cs2']))
  $cs2 = $_GET['cs2'];
else
  $cs2 = null;

echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=$cs1&cs2=$cs2&ovly=$sale_id\"></head></html>";


return;


?>