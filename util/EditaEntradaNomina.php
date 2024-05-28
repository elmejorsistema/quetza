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

//echo var_dump($_POST); return;
   

if(empty($_POST['payroll_id_h_b']))
  {
    echo "<script>alert(\"Error: variable no recibida1\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $payroll_id = trim($_POST['payroll_id_h_b']);




if(empty($_POST['rate_hour_h_b']))
  {
    echo "<script>alert(\"Error: variable no recibida2\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $rate_hour = trim($_POST['rate_hour_h_b']);


if(empty($_POST['hours_b']))
  {
    echo "<script>alert(\"Error: variable no recibida3\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $hours = (trim($_POST['hours_b']));


if(empty($_POST['total_hours_h_b']))
  {
    echo "<script>alert(\"Error: variable no recibida4\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $total_hours = trim($_POST['total_hours_h_b']);

if($total_hours != $rate_hour * $hours)
  $total_hours = $rate_hour * $hours;



if(empty($_POST['inicial_b']))
  {
    echo "<script>alert(\"Error: variable no recibida5\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $initial = trim($_POST['inicial_b']);


if(empty($_POST['final_b']))
  {
    echo "<script>alert(\"Error: variable no recibida6\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $final = trim($_POST['final_b']);



if(empty($_POST['extra_1_b']))
  $extra_1 = "default";
else
  $extra_1 = trim($_POST['extra_1_b']);

if(empty($_POST['comment_extra_1_b']))
  $comment_extra_1 = "default";
else
  $comment_extra_1 = " \"".trim($_POST['comment_extra_1_b'])."\"";



if(empty($_POST['extra_2_b']))
  $extra_2 = "default";
else
  $extra_2 = trim($_POST['extra_2_b']);

if(empty($_POST['comment_extra_2_b']))
  $comment_extra_2 = "default";
else
  $comment_extra_2 = "\"".trim($_POST['comment_extra_2_b'])."\"";



if(empty($_POST['extra_3_b']))
  $extra_3 = "default";
else
  $extra_3 = trim($_POST['extra_3_b']);

if(empty($_POST['comment_extra_3_b']))
  $comment_extra_3 = "default";
else
  $comment_extra_3 = "\"".trim($_POST['comment_extra_3_b'])."\"";




//var_dump($_POST);RETURN;

//$q = "insert into payroll values (null, $employee_id, $rate_hour, $hours, $total_hours, '$initial', '$final', $extra_1, $comment_extra_1, $extra_2, $comment_extra_2, $extra_3, $comment_extra_3)";




$q = "update payroll set rate_hour = $rate_hour, hours = $hours, total_hours = $total_hours, initial_date = '$initial', final_date = '$final', extra1 = $extra_1, comment_extra1 = $comment_extra_1, extra2 = $extra_2, comment_extra2 = $comment_extra_2, extra3 = $extra_3, comment_extra3 = $comment_extra_3 where id = $payroll_id";



//echo $q;return;
$o_database->query_assign($q);

if($o_database->query_error)
  {
    $o_message = new message();
    echo "<script>alert(\"".$o_message->show_message(5,$o_database)."\")</script>";
    echo "<script>history.go(-1)</script>";
    return;
  }


if(!empty($_POST['cs1_b']))
  $o_config->control_structure_id1 = $_POST['cs1_b'];
if(!empty($_POST['cs2_b']))
  $o_config->control_structure_id2 = $_POST['cs2_b'];
  

echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=$o_config->control_structure_id1&cs2=$o_config->control_structure_id2&ovly=buscar\"></head></html>";


return;


?>