<?php

define("EMPRESA", "Prefeco Quetzalcóatl");
define("ENVIRONMENT", "DEVELOPMENT");

// inlcludes
include "clases.php";
include "util/functions.php";

// Database variables
include "config/dbconfig.php";

/*
************+++***********************
Created:   Manuel José Contreras Maya
Email:     manuel@agenteel.com
Date       2014
************+++***********************
**************************************
****** Do not share this code ********
       ^^^^^^^^^^^^^^^^^^^^^^
************+++***********************
************+++***********************
*/

//Define the control_structure
if(!empty($_POST['control_structure_id']))
  $control_structure_id=trim(addslashes($_POST['control_structure_id']));
else
  $control_structure_id=1;
///////////Search the control_structure_id in current_menu
$control_structure_id=6;


$o_databaseCredentials = new databaseCredentials($db_host,  $db_name,  $db_user,  $db_password);
$o_databaseCredentials2 = new databaseCredentials($db_host2,  $db_name2,  $db_user2,  $db_password2);

// database object
$o_database  = new database($o_databaseCredentials->db_host,  $o_databaseCredentials->db_name,
    $o_databaseCredentials->db_user,  $o_databaseCredentials->db_password);
$o_database2 = new database($o_databaseCredentials2->db_host, $o_databaseCredentials2->db_name,
    $o_databaseCredentials2->db_user, $o_databaseCredentials2->db_password);



// Let's go out, it was no possible to create a proper db connection 
if($o_database->status < 2)
  {
    echo "DB Error: ".$o_database->status;
    unset($o_database);
    return;
  }

// message object
$o_message = new message();

// Ckeck username
if(empty($_POST['user']))
  {
     echo "<script>alert(\"".$o_message->show_message(1,$o_database)."\")</script>";
     echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=index.html\"></head></html>";
     return;
  }
$username = trim(addslashes($_POST['user']));

// Ckeck password
if(empty($_POST['passwd']))
  {
    echo "<script>alert(\"".$o_message->show_message(2,$o_database)."\")</script>";
    echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=index.html\"></head></html>";
    return;
  }
$password = trim(addslashes($_POST['passwd']));

// user validation
$s_query  = "select id, nombre, 1_apellido, 2_apellido, curp, status from user where user = '$username' and password = sha1('$password')";
//echo $s_query; return;
if(!$o_database->query_fetch_row($s_query))
  {
    echo "<script>alert(\"".$o_message->show_message(3,$o_database)."\")</script>";
    echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=index.html\"></head></html>";
    return;
  }


// Let's get the user id and name
$user_id   = $o_database->query_row[0];
$humanname = $o_database->query_row[1]." ".$o_database->query_row[2]." ".$o_database->query_row[3]." ";
$user_curp = $o_database->query_row[4];
$user_tipo = $o_database->query_row[5];

// it seems that is a valid user
// let's let her in

session_name($session_name);
session_start();



$o_security = new security(session_id());

// update security field and last_visit field
$s_query    = "update user set security_id = $o_security->id where id = $user_id";
$o_database->query_assign($s_query);


// user object
$o_user = new user($user_id, $o_security->id, $humanname, $user_curp, $user_tipo);

//
$a_cs = first_cs_for_user($user_id, $o_database);

//echo var_dump($a_cs);return;

if(!$a_cs[0] or !$a_cs[1] )
{
    echo "<script>alert(\"".$o_message->show_message(4,$o_database)."\")</script>";
    echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=index.html\"></head></html>";
    return;
  }
// config objet

// Get el ciclo
$s_query  = "select id, tipo from ciclo where status = 'Activo'";
//echo $s_query; return;
if(!$o_database->query_fetch_row($s_query))
  {
    echo "<script>alert(\"".$o_message->show_message(6,$o_database)."\")</script>";
    echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=index.html\"></head></html>";
    return;
  }



$o_config = new config($a_cs[0], $a_cs[1], EMPRESA, $o_database->query_row[0], $o_database->query_row[1], ENVIRONMENT);



// VERY IMPORTANT //////////////////////////////
// disconnect the database since is not possible
// serialize/unserialize resources
$o_database->disconnect();

///////////////////////////////////////////////

// serialize the main objects to pass them
// as session variables
// the db is traveling disconnected and needs
// to be connected when unserialised at the arrival
// point  
$_SESSION['config']   = serialize($o_config);
$_SESSION['user']     = serialize($o_user);
$_SESSION['databaseCredentials'] = serialize($o_databaseCredentials);

$_SESSION['security'] = serialize($o_security);
$_SESSION['message']  = serialize($o_message);

// go to the application!
echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=index.php\"></head></html>";


?>
