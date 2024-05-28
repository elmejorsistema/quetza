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
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\"></head></html>";
  return;
}


// Check that this is a valid menu
//////////////////////////////////////////////

$o_config->control_structure_id1 = 25;
$o_config->control_structure_id2 = 27;
if(!checkCheckMenu($o_user->id, $o_config, $o_database)){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\"></head></html>";
  return;
  }

// //////////////////////////// //
// Diferentes tipos de reportes //
// //////////////////////////// //



if(empty($_GET['reporte_id']))
  {
    echo "<script>alert(\"Error: variable no recibida\")</script>";
    echo "<script>history.go(-1)</script>";
    return;
  }
else
  $reporte_id = $_GET['reporte_id'];


// Si no hay fecha toma la de hoy
if(empty($_GET["inicio_periodo"]) or empty($_GET["fin_periodo"]))
  {
      /*
        echo "<script>alert(\"Error: variable no recibida\")</script>";
        echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=./index.php?cs1=1&cs2=4\"></head></html>";
        return;
      */
      $inicio_periodo =  date("d\-m\-Y");
      $fin_periodo    =  date("d\-m\-Y");
  }
else
  {
      $inicio_periodo = $_GET["inicio_periodo"];
      $fin_periodo    = $_GET["fin_periodo"];
  }

//echo $inicio_periodo;return;

$d_inicio = new DateTime($inicio_periodo);
$d_fin    = new DateTime($fin_periodo);
$inicio_periodo = $d_inicio->format("Y-m-d");
$fin_periodo    = $d_fin->format("Y-m-d");



$interval = $d_inicio->diff($d_fin);
if ($interval->invert)
  {
    echo "<script>alert(\"Error: la fecha de inicio no puede ser posterior a la fecha de fin\")</script>";
    echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=25\"></head></html>";
    return;
  }








switch($reporte_id)
{
    //Reporte de Gastos de Hoy
case 1:
    reporte_gastos($inicio_periodo, $fin_periodo, $o_database, $o_config->ciclo, $o_config->tipo, $o_user->id);
    break;
case 2:
    reporte_ingresos_detalle($inicio_periodo, $fin_periodo, $o_database, $o_config->ciclo, $o_config->tipo, $o_user->id);
    break;
case 3:
    reporte_deudores($inicio_periodo, $fin_periodo, $o_database, $o_config->ciclo, $o_config->tipo, $o_user->id);
    break;
case 4:
    reporte_ingresos($inicio_periodo, $fin_periodo, $o_database, $o_config->ciclo, $o_config->tipo, $o_user->id);
    break;
default:
    echo "<script>alert(\"Error: variable de reporte no reconocida\")</script>";
    echo "<script>history.go(-1)</script>";
    return;
    break;
}
return;



function reporte_gastos($inicio_periodo, $fin_periodo, $o_database, $ciclo, $tipo, $user_id)
{

    $q= "select  g.id, g.user_id, g.fecha, concat(concat(concat(c.id, ' ('), c.tipo), ')'), g.descripcion, monto from gasto as g join ciclo as c on g.ciclo_id = c.id and g.ciclo_tipo = c.tipo where g.ciclo_id = '$ciclo'  and g.ciclo_tipo = '$tipo'  and g.fecha >= '$inicio_periodo' and g.fecha <= '$fin_periodo' order by g.fecha, g.id";
   

    $datos_archivo = "Folio,Usuario,Fecha,Semestre,Concepto,Monto\r\n";

    $filename = "Gastos-".$user_id.".csv"; 
    
    $o_database->query_rows($q);
    $result = $o_database->query_result;
    $total = 0;
    foreach($result as $row)
        $datos_archivo .= "$row[0],$row[1],$row[2],$row[3],\"$row[4]\",$row[5]\r\n";

    $path = "../csv/";
    file_put_contents("$path$filename", $datos_archivo);
    $file_size = filesize($path.$filename);
    unlink("$path$filename");

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Length: $file_size");
    header("Content-Disposition: attachment; filename=\"".$filename."\";");
    header("Content-type: text/comma-separated-values");
    echo $datos_archivo;
}


function reporte_ingresos_detalle($inicio_periodo, $fin_periodo, $o_database, $ciclo, $tipo, $user_id)
{
    
    // $q= "select ahchp.id, ahchp.user_id, ahchp.fecha, concat(concat(concat(chp.ciclo_id, ' ('), chp.ciclo_tipo), ')'),  concat(concat(concat(concat(concat(concat(concat(a.nombre, ' '), a.1_apellido), ' '), a.2_apellido), ' ['), a.id), ']'), p.descripcion, ahchp.descripcion, ahchp.monto from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id join alumno as a on a.id = ahchp.alumno_id where chp.ciclo_id = '$ciclo' and chp.ciclo_tipo = '$tipo' and ahchp.fecha >= '$inicio_periodo' and ahchp.fecha <= '$fin_periodo' order by ahchp.fecha, ahchp.id";

    // Sin ciclo
    // $q= "select ahchp.id, ahchp.user_id, ahchp.fecha, concat(concat(concat(chp.ciclo_id, ' ('), chp.ciclo_tipo), ')'),  concat(concat(concat(concat(concat(concat(concat(a.nombre, ' '), a.1_apellido), ' '), a.2_apellido), ' ['), a.id), ']'), p.descripcion, ahchp.descripcion, ahchp.monto from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id join alumno as a on a.id = ahchp.alumno_id where ahchp.fecha >= '$inicio_periodo' and ahchp.fecha <= '$fin_periodo' order by ahchp.fecha, ahchp.id";


    // Sin ciclo con recibo
    //$q= "select  rhp.recibo_id, ahchp.user_id, r.fecha, concat(concat(concat(chp.ciclo_id, ' ('), chp.ciclo_tipo), ')'),  concat(concat(concat(concat(concat(concat(concat(a.nombre, ' '), a.1_apellido), ' '), a.2_apellido), ' ['), a.id), ']'), p.descripcion, ahchp.descripcion, ahchp.monto, a.grupo_id from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id join alumno as a on a.id = ahchp.alumno_id join recibo_has_pago as rhp on rhp.ahc_has_pago_id = ahchp.id join recibo as r on rhp.recibo_id = r.id where r.fecha >= '$inicio_periodo' and r.fecha <= '$fin_periodo' order by rhp.recibo_id, chp.fecha_limite, ahchp.id";


    //$q= "select  rhp.recibo_id, ahchp.user_id, r.fecha, concat(concat(concat(chp.ciclo_id, ' ('), chp.ciclo_tipo), ')'),  concat(concat(concat(concat(concat(concat(concat(a.1_apellido, ' '), a.2_apellido), ' '), a.nombre), ' ['), a.id), ']'), p.descripcion, ahchp.descripcion, ahchp.monto, a.grupo_id from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id join alumno as a on a.id = ahchp.alumno_id join recibo_has_pago as rhp on rhp.ahc_has_pago_id = ahchp.id join recibo as r on rhp.recibo_id = r.id where r.fecha >= '$inicio_periodo' and r.fecha <= '$fin_periodo' order by rhp.recibo_id, chp.fecha_limite, ahchp.id";
    

// Esto funciona pero no muestra los recibos no usados

// $q= "select  rhp.recibo_id, ahchp.user_id, r.fecha, concat(concat(concat(chp.ciclo_id, ' ('), chp.ciclo_tipo), ')'),  concat(concat(concat(concat(concat(concat(concat(a.1_apellido, ' '), a.2_apellido), ' '), a.nombre), ' ['), a.id), ']'), upper(p.descripcion), ahchp.descripcion, ahchp.monto, a.grupo_id from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id join alumno as a on a.id = ahchp.alumno_id join recibo_has_pago as rhp on rhp.ahc_has_pago_id = ahchp.id join recibo as r on rhp.recibo_id = r.id where r.fecha >= '$inicio_periodo' and r.fecha <= '$fin_periodo' order by rhp.recibo_id, chp.fecha_limite, ahchp.id";
    

// Esto muestra los recibos no usados

$q= "select r.id, ahchp.user_id, r.fecha, concat(concat(concat(chp.ciclo_id, ' ('), chp.ciclo_tipo), ')'), concat(concat(concat(concat(concat(concat(concat(a.1_apellido, ' '), a.2_apellido), ' '), a.nombre), ' ['), a.id), ']'), upper(p.descripcion), ahchp.descripcion, ahchp.monto, a.grupo_id from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id join alumno as a on a.id = ahchp.alumno_id join recibo_has_pago as rhp on rhp.ahc_has_pago_id = ahchp.id right join recibo as r on rhp.recibo_id = r.id where r.fecha >= '$inicio_periodo' and r.fecha <= '$fin_periodo' order by r.id, chp.fecha_limite, ahchp.id";



    
    //echo $q; return;


    $datos_archivo = "Folio,Usuario,Fecha,Semestre,Alumna/o,Grupo,Pago,Comentario,Monto\r\n";

    $filename = "Ingresos-Detalle-".$user_id.".csv"; 
    
    $o_database->query_rows($q);
    $result = $o_database->query_result;
    $total = 0;
    foreach($result as $row)
        $datos_archivo .= "$row[0],$row[1],$row[2],$row[3],\"$row[4]\",$row[8],\"$row[5]\",\"$row[6]\",$row[7]\r\n";

    $path = "../csv/";
    file_put_contents("$path$filename", $datos_archivo);
    $file_size = filesize($path.$filename);
    unlink("$path$filename");

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Length: $file_size");
    header("Content-Disposition: attachment; filename=\"".$filename."\";");
    header("Content-type: text/comma-separated-values");
    echo $datos_archivo;
}


function reporte_ingresos($inicio_periodo, $fin_periodo, $o_database, $ciclo, $tipo, $user_id)
{
    
    // $q= "select ahchp.id, ahchp.user_id, ahchp.fecha, concat(concat(concat(chp.ciclo_id, ' ('), chp.ciclo_tipo), ')'),  concat(concat(concat(concat(concat(concat(concat(a.nombre, ' '), a.1_apellido), ' '), a.2_apellido), ' ['), a.id), ']'), p.descripcion, ahchp.descripcion, ahchp.monto from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id join alumno as a on a.id = ahchp.alumno_id where chp.ciclo_id = '$ciclo' and chp.ciclo_tipo = '$tipo' and ahchp.fecha >= '$inicio_periodo' and ahchp.fecha <= '$fin_periodo' order by ahchp.fecha, ahchp.id";

    // Sin ciclo
    // $q= "select ahchp.id, ahchp.user_id, ahchp.fecha, concat(concat(concat(chp.ciclo_id, ' ('), chp.ciclo_tipo), ')'),  concat(concat(concat(concat(concat(concat(concat(a.nombre, ' '), a.1_apellido), ' '), a.2_apellido), ' ['), a.id), ']'), p.descripcion, ahchp.descripcion, ahchp.monto from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id join alumno as a on a.id = ahchp.alumno_id where ahchp.fecha >= '$inicio_periodo' and ahchp.fecha <= '$fin_periodo' order by ahchp.fecha, ahchp.id";


   // Sin ciclo con recibo
    // $q= "select  rhp.recibo_id, ahchp.user_id, r.fecha, concat(concat(concat(chp.ciclo_id, ' ('), chp.ciclo_tipo), ')'),  concat(concat(concat(concat(concat(concat(concat(a.nombre, ' '), a.1_apellido), ' '), a.2_apellido), ' ['), a.id), ']'), p.descripcion, ahchp.descripcion, ahchp.monto from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id join alumno as a on a.id = ahchp.alumno_id join recibo_has_pago as rhp on rhp.ahc_has_pago_id = ahchp.id join recibo as r on rhp.recibo_id = r.id where ahchp.fecha >= '$inicio_periodo' and ahchp.fecha <= '$fin_periodo' order by rhp.recibo_id, chp.fecha_limite, ahchp.id";
    
    //$q= "select  r.id, r.user_id, r.fecha, concat(concat(concat(concat(concat(concat(concat(a.nombre, ' '), a.1_apellido), ' '), a.2_apellido), ' ['), a.id), ']'), sum(ahchp.monto), a.grupo_id from recibo as r join recibo_has_pago as rhp on rhp.recibo_id = r.id join alumno_has_ciclo_has_pago as ahchp on ahchp.id = rhp.ahc_has_pago_id join alumno as a on a.id = ahchp.alumno_id where r.fecha >= '$inicio_periodo' and r.fecha <= '$fin_periodo' group by rhp.recibo_id order by r.id";

    // Esto funciona pero no muestra los recibos no usados
    //$q= "select  r.id, r.user_id, r.fecha, concat(concat(concat(concat(concat(concat(concat(a.1_apellido, ' '), a.2_apellido), ' '), a.nombre), ' ['), a.id), ']'), sum(ahchp.monto), a.grupo_id from recibo as r join recibo_has_pago as rhp on rhp.recibo_id = r.id join alumno_has_ciclo_has_pago as ahchp on ahchp.id = rhp.ahc_has_pago_id join alumno as a on a.id = ahchp.alumno_id where r.fecha >= '$inicio_periodo' and r.fecha <= '$fin_periodo' group by rhp.recibo_id order by r.id";

// Esto muestra los recibos no usados
$q= "select  r.id, r.user_id, r.fecha, concat(concat(concat(concat(concat(concat(concat(a.1_apellido, ' '), a.2_apellido), ' '), a.nombre), ' ['), a.id), ']'), sum(ahchp.monto), a.grupo_id from recibo as r left join recibo_has_pago as rhp on rhp.recibo_id = r.id left join alumno_has_ciclo_has_pago as ahchp on ahchp.id = rhp.ahc_has_pago_id left join alumno as a on a.id = ahchp.alumno_id where r.fecha >= '$inicio_periodo' and r.fecha <= '$fin_periodo' group by rhp.recibo_id order by r.id";





    


    

    //echo $q; return;


    $datos_archivo = "Folio,Usuario,Fecha,Alumna/o,Grupo,Monto\r\n";

    $filename = "Ingresos-".$user_id.".csv"; 
    
    $o_database->query_rows($q);
    $result = $o_database->query_result;
    $total = 0;
    foreach($result as $row)
        $datos_archivo .= "$row[0],$row[1],$row[2],$row[3],$row[5],\"$row[4]\"\r\n";

    $path = "../csv/";
    file_put_contents("$path$filename", $datos_archivo);
    $file_size = filesize($path.$filename);
    unlink("$path$filename");

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Length: $file_size");
    header("Content-Disposition: attachment; filename=\"".$filename."\";");
    header("Content-type: text/comma-separated-values");
    echo $datos_archivo;
}



function reporte_deudores($inicio_periodo, $fin_periodo, $o_database, $ciclo, $tipo, $user_id)
{
    
    //$q="select a.id, a.grupo_id, concat(concat(concat(concat(a.1_apellido, ' '), a.2_apellido), ' '), a.nombre), p.descripcion, chp.monto, chp.fecha_limite from ciclo_has_pago as chp join alumno as a left join alumno_has_ciclo_has_pago as ahchp on a.id = ahchp.alumno_id and ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id where chp.ciclo_id ='$ciclo' and chp.ciclo_tipo = '$tipo' and chp.fecha_limite >= '$inicio_periodo' and chp.fecha_limite <= '$fin_periodo' and ahchp.ciclo_has_pago_id is null and a.estatus = 'Activo' order by a.grupo_id, a.1_apellido, a.2_apellido, a.nombre";


    $q="select a.id, a.grupo_id, concat(concat(concat(concat(a.1_apellido, ' '), a.2_apellido), ' '), a.nombre), upper(p.descripcion), chp.monto, chp.fecha_limite from ciclo_has_pago as chp join alumno as a left join alumno_has_ciclo_has_pago as ahchp on a.id = ahchp.alumno_id and ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id where chp.ciclo_id ='$ciclo' and chp.ciclo_tipo = '$tipo' and chp.fecha_limite >= '$inicio_periodo' and chp.fecha_limite <= '$fin_periodo' and ahchp.ciclo_has_pago_id is null and a.estatus = 'Activo' order by a.grupo_id, a.1_apellido, a.2_apellido, a.nombre";




//echo $q; return;

    

    $datos_archivo = "Alumno ID,Grupo,Alumna/o,Pago,Monto, Fecha Límite\r\n";

    $filename = "Deudores-".$user_id.".csv"; 
    
    $o_database->query_rows($q);
    $result = $o_database->query_result;
    $total = 0;
    foreach($result as $row)
        $datos_archivo .= "$row[0],$row[1],\"$row[2]\",\"$row[3]\",$row[4],$row[5]\r\n";

    $path = "../csv/";
    file_put_contents("$path$filename", $datos_archivo);
    $file_size = filesize($path.$filename);
    unlink("$path$filename");

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Length: $file_size");
    header("Content-Disposition: attachment; filename=\"".$filename."\";");
    header("Content-type: text/comma-separated-values");
    echo $datos_archivo;
}




?>