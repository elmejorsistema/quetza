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
   

if(empty($_GET['sale_id']))
{
 echo "<script>alert(\"Error: variable no recibida\")</script>";
 echo "<script>history.go(-1)</script>>";
 return;
}
else
  $sale_id = $_GET['sale_id'];


if(empty($_GET['payment_type_id']))
{
 echo "<script>alert(\"Error: variable no recibida\")</script>";
 echo "<script>history.go(-1)</script>>";
 return;
}
else
  $payment_type_id = $_GET['payment_type_id'];

if(empty($_GET['card_id']))
{
 echo "<script>alert(\"Error: variable no recibida\")</script>";
 echo "<script>history.go(-1)</script>>";
 return;
}
else
  $card_id = $_GET['card_id'];

if(empty($_GET['comment']))
{
 $comment = "comment = default";
}
else
  {
    $comment =  trim(str_replace("'", "\'",$_GET['comment']));
    $comment =  trim(str_replace("\"", "\\\"",$comment));
    $comment = "comment = \"$comment\""; 
  }





$q = "update sale set status_sale_id = 2, payment_type_id = $payment_type_id, card_id = $card_id, $comment, date = now() where id = $sale_id";
$o_database->query_assign($q);



if($o_database->query_error)
  {
    $o_message = new message();
    echo "<script>alert(\"".$o_message->show_message(5,$o_database)."\")</script>";
    echo "<script>history.go(-1)</script>";
    return;
  }


//$fecha = date("j/", mktime()).substr(getSpanishMonth(date("n", mktime())),0,3).date("/Y H:i", mktime());
//$fecha = date("j/", mktime()).substr(getSpanishMonth(date("n", mktime())),0,3).date("/Y H:i", mktime());
 
imirimeTicket($sale_id, $o_database, $o_user);

if(!empty($_GET['cs1']))
  $o_config->control_structure_id1 = $_GET['cs1'];
if(!empty($_GET['cs2']))
  $o_config->control_structure_id2 = $_GET['cs2'];
  

echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=$o_config->control_structure_id1&cs2=$o_config->control_structure_id2\"></head></html>";


return;


?>