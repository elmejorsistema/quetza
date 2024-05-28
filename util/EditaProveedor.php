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

if(empty($_POST['supplier_id_b']))
 {
    echo "<script>alert(\"Error: variable no recibidam\")</script>";
    echo "<script>history.go(-1)</script>";
    return;
  }
else
  $supplier_id = trim($_POST['supplier_id_b']);

if(empty($_POST['nombre_b']))
  {
    echo "<script>alert(\"Error: variable no recibidaf\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $nombre = "'".trim($_POST['nombre_b'])."'";

if(empty($_POST['estado_id_b']))
  {
    echo "<script>alert(\"Error: variable no recibidas\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $estado_id = trim($_POST['estado_id_b']);

if(empty($_POST['pais_id_b']))
  {
    echo "<script>alert(\"Error: variable no recibidaz\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }
else
  $pais_id = trim($_POST['pais_id_b']);


if(empty($_POST['rfc_b']))
  $rfc = "default";
else
  $rfc = "'".strtoupper(trim($_POST['rfc_b']))."'";

if(empty($_POST['calle_b']))
  $calle = "default";
else
  $calle = "'".trim($_POST['calle_b'])."'";

if(empty($_POST['no_exterior_b']))
  $no_exterior = "default";
else
  $no_exterior = "'".trim($_POST['no_exterior_b'])."'";

if(empty($_POST['colonia_b']))
  $colonia = "default";
else
  $colonia = "'".trim($_POST['colonia_b'])."'";

if(empty($_POST['no_interior_b']))
  $no_interior = "default";
else
  $no_interior = "'".trim($_POST['no_interior_b'])."'";

if(empty($_POST['localidad_b']))
  $localidad = "default";
else
  $localidad = "'".trim($_POST['localidad_b'])."'";

if(empty($_POST['municipio_b']))
  $municipio = "default";
else
  $municipio = "'".trim($_POST['municipio_b'])."'";

if(empty($_POST['codigo_postal_b']))
  $codigo_postal = "default";
else
  $codigo_postal = "'".trim($_POST['codigo_postal_b'])."'";

if(empty($_POST['telefono_b']))
  $telefono = "default";
else
  $telefono = "'".trim($_POST['telefono_b'])."'";

if(empty($_POST['email_b']))
  $email = "default";
else
  $email = "'".trim($_POST['email_b'])."'";

if(empty($_POST['pagina_web_b']))
  $pagina_web = "default";
else
  $pagina_web = "'".trim($_POST['pagina_web_b'])."'";


/*
+-------------+----------------------+------+-----+---------+----------------+
| Field       | Type                 | Null | Key | Default | Extra          |
+-------------+----------------------+------+-----+---------+----------------+
| id          | smallint(5) unsigned | NO   | PRI | NULL    | auto_increment |
| name        | varchar(255)         | NO   |     | NULL    |                |
| rfc         | varchar(13)          | YES  |     | NULL    |                |
| address1    | varchar(255)         | YES  |     | NULL    |                |
| address2    | varchar(255)         | YES  |     | NULL    |                |
| address3    | varchar(255)         | YES  |     | NULL    |                |
| address4    | varchar(32)          | YES  |     | NULL    |                |
| address5    | varchar(32)          | YES  |     | NULL    |                |
| address6    | varchar(96)          | YES  |     | NULL    |                |
| estado_id   | tinyint(3) unsigned  | NO   | MUL | NULL    |                |
| pais_id     | tinyint(3) unsigned  | NO   | MUL | NULL    |                |
| postal_code | varchar(24)          | YES  |     | NULL    |                |
| telephone   | varchar(64)          | YES  |     | NULL    |                |
| email       | varchar(128)         | YES  |     | NULL    |                |
| webpage     | varchar(255)         | YES  |     | NULL    |                |
+-------------+----------------------+------+-----+---------+----------------+
*/


$q = "update supplier set
name = $nombre,
rfc = $rfc,
address1 = $calle,
address2 = $no_exterior,
address3 = $no_interior,
address4 = $colonia,
address5 = $localidad,
address6 = $municipio,
estado_id = $estado_id,
pais_id = $pais_id,
postal_code = $codigo_postal,
telephone = $telefono,
email = $email,
webpage = $pagina_web
where id = $supplier_id";

//echo $q;return;
$o_database->query_assign($q);

if($o_database->query_error)
  {
    $o_message = new message();
    echo "<script>alert(\"".$o_message->show_message(5,$o_database)."\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
  }


if(!empty($_POST['cs1']))
  $o_config->control_structure_id1 = $_POST['cs1'];
if(!empty($_POST['cs2']))
  $o_config->control_structure_id2 = $_POST['cs2'];
  

echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=$o_config->control_structure_id1&cs2=$o_config->control_structure_id2&ovly=buscar\"></head></html>";


return;


?>