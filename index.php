<?php
//echo "Sitio en Mantenimiento";exit;
/*
**************************************
Created:   Manuel José Contreras Maya
Email:     manuel@agenteel.com
Date       2014
**************************************
**************************************
****** Do not share this code ********
       ^^^^^^^^^^^^^^^^^^^^^^
**************************************
**************************************
*/


define("UTIL", "./util/");

include UTIL."utility.php";
include UTIL."functions.php";

include "clases.php";
include "config/dbconfig.php";

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
 $o_message  = unserialize($_SESSION['message']);
}


/**
* Mantenimiento
*
**/

/*
if($o_user->id !== '66'){
  //echo $o_user->id;return;
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=logout.php\" ></head></html>";
  return;
}
*/




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


if(!empty($_GET['cs1']))
  {
    $cs1 = $_GET['cs1'];
    $o_config->control_structure_id1 = $cs1;

    if(!empty($_GET['cs2']))
      {
	$cs2 = $_GET['cs2'];
	$o_config->control_structure_id2 = $cs2;
      }
    else
      {
	$cs2 = first_cs2_for_user($o_user->id, $o_database, $cs1);
	$o_config->control_structure_id2 = $cs2;
      }
  }
else
  {
    $cs1 = null;
    $cs2 = null;
  }


// A partir de este punto ambos cs<n> deben ser válidos.

// Check that this is a valid menu
//////////////////////////////////////////////
if(!checkCheckMenu($o_user->id, $o_config, $o_database)){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=index.html\"></head></html>";
  return;
}



//Creates HTML header
head_HTML($o_config, $o_user, $o_database);

//Creates header
head($o_config, $o_user, $o_database);

//Creates header
menu($o_config, $o_user, $o_database);

//Creates the content
content($o_config, $o_user, $o_database, $o_message);

//Creates footer
foot();

return;
?>
