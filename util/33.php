<?php
include "utility.php";
include "functions.php";

include "../clases.php";
include "../config/dbconfig.php";

require_once ('../dompdf/dompdf_config.inc.php');

session_name($session_name);
session_start();

//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);


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

//var_dump($o_config);return;

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



// Check that this is a valid menu
//////////////////////////////////////////////

$o_config->control_structure_id1 = 4;
$o_config->control_structure_id2 = 33;
if(!checkCheckMenu($o_user->id, $o_config, $o_database)){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\"></head></html>";
  return;
  }



//echo var_dump($_POST);
//manuelgaudencio36   

//var_dump($_POST);
//return;

if(empty($_GET['grupo']))
  {
    echo "<script>alert(\"Error: variable no recibida\")</script>";
    echo "<script>history.go(-1)</script>";
    return;
  }
else
  $grupo = $_GET['grupo'];

if($grupo > 500)
  $tamagno_letra = '8pt';
else
  $tamagno_letra = '8pt';


if(empty($_GET['final']))
  {
    $final = 0;
  }
else
  $final = 1;




$q = "select day(fecha_boletas),month(fecha_boletas),year(fecha_boletas),nombre_puesto,titulo_academico,user_id from ciclo where ciclo.id = \"".$o_config->ciclo."\" and tipo = \"".$o_config->tipo."\""; 
$o_database->query_fetch_row($q);



$fecha_boletas = $o_database->query_row[0]." de ". getSpanishMonth($o_database->query_row[1])." de ".$o_database->query_row[2];
$nombre_puesto = $o_database->query_row[3];
$titulo_academico = $o_database->query_row[4];
$user_id = $o_database->query_row[5];

$q = "select nombre,1_apellido,2_apellido from user where id = ".$user_id;
$o_database->query_fetch_row($q);
$nombre = $titulo_academico." ".$o_database->query_row[0]." ".$o_database->query_row[1]." ".$o_database->query_row[2];

//echo "<html><body>espere...</body></html>";

/*
$q = "select a.1_apellido, a.2_apellido ,a.nombre, round(ahe.calif_1), round(ahe.calif_2), round(ahe.calif_3), round(ahe.calif_4),round(ahe.calif_5),faltas_1, faltas_2, faltas_3, m.name, m.semestre_id, a.id, chm.grupo_name from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and chm.grupo_id = $grupo join materia as m on chm.materia_id = m.id where  ciclo_id = \"".$o_config->ciclo."\" and ciclo_tipo = \"".$o_config->tipo."\" order by 1,2,3,12";
echo $q; return;
*/


$q = "select chm.id, chm.grupo_id, m.name, u.nombre, u.1_apellido, u.2_apellido from ciclo_has_materia as chm join materia as m on chm.materia_id = m.id join ciclo as c on chm.ciclo_id = c.id and chm.ciclo_tipo = c.tipo join user as u on chm.user_id = u.id where c.id = \"".$o_config->ciclo."\" and c.tipo = \"".$o_config->tipo."\"";



//echo $q;
$o_database->query_rows($q);
$result = $o_database->query_result;


switch($o_config->tipo)
  {
  case "A":
    $taller        = 1001;
    $capacitacion0 = 5005;
    $capacitacion1 = 5015;
    
    break;
  default:
    $taller        = 2002;
    $capacitacion0 = 6006;
    $capacitacion1 = 6016;
  }


//$q = "select a.1_apellido, a.2_apellido ,a.nombre, round(ahe.calif_1), round(ahe.calif_2), round(ahe.calif_3), round(ahe.calif_4),round(ahe.calif_5),faltas_1, faltas_2, faltas_3, m.name, m.semestre_id, a.id, chm.grupo_name, m.secuencia from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and a.grupo_id = $grupo and (chm.grupo_id = $grupo or chm.grupo_id = $taller or chm.grupo_id = $capacitacion)  join materia as m on chm.materia_id = m.id where  ciclo_id = \"".$o_config->ciclo."\" and ciclo_tipo = \"".$o_config->tipo."\" order by 1,2,3,16";

// con el nombre de la metaria máximo a 46 caracteres
//$q = "select a.1_apellido, a.2_apellido ,a.nombre, round(ahe.calif_1), round(ahe.calif_2), round(ahe.calif_3), round(ahe.calif_4),round(ahe.calif_5),faltas_1, faltas_2, faltas_3, substr(m.name,1,46), m.semestre_id, a.id, chm.grupo_name, m.secuencia_2 from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and a.grupo_id = $grupo and (chm.grupo_id = $grupo or chm.grupo_id = $taller or chm.grupo_id = $capacitacion)  join materia as m on chm.materia_id = m.id where  ciclo_id = \"".$o_config->ciclo."\" and ciclo_tipo = \"".$o_config->tipo."\" order by 1,2,3,16,12";


// Las calificaciones conun decimal
//$q = "select a.1_apellido, a.2_apellido ,a.nombre, round(ahe.calif_1,1), round(ahe.calif_2,1), round(ahe.calif_3,1), round(ahe.calif_4,1),round(ahe.calif_5,1),faltas_1, faltas_2, faltas_3, substr(m.name,1,46), m.semestre_id, a.id, chm.grupo_name, m.secuencia_2 from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and a.grupo_id = $grupo and (chm.grupo_id = $grupo or chm.grupo_id = $taller or chm.grupo_id = $capacitacion)  join materia as m on chm.materia_id = m.id where  ciclo_id = \"".$o_config->ciclo."\" and ciclo_tipo = \"".$o_config->tipo."\" order by 1,2,3,16,12";


$q = "select a.1_apellido, a.2_apellido ,a.nombre, round(ahe.calif_1,1), round(ahe.calif_2,1), round(ahe.calif_3,1), round(ahe.calif_4,1),round(ahe.calif_5,1),faltas_1, faltas_2, faltas_3, substr(m.name,1,46), m.semestre_id, a.id, chm.grupo_name, m.secuencia_2 from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and a.grupo_id = $grupo and (chm.grupo_id = $grupo or chm.grupo_id = $taller or chm.grupo_id = $capacitacion0 or chm.grupo_id = $capacitacion1)  join materia as m on chm.materia_id = m.id where  ciclo_id = \"".$o_config->ciclo."\" and ciclo_tipo = \"".$o_config->tipo."\" order by 1,2,3,16,12";






$o_database->query_rows($q);
$result = $o_database->query_result;

//echo $q ; return;

//select grupo_id from  ciclo_has_materia where  ciclo_id = \"".$o_config->ciclo."\" and ciclo_tipo = \"".$o_config->tipo."\"";

$html = "<!DOCTYPE html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
<style>

html {
  margin: 0;
}

body {
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: $tamagno_letra;
  margin: 5mm 15mm 0mm 15mm;
}

table {
  border-collapse: collapse;
  width: 186mm;
}

td {
  border-collapse: collapse;
  border: 0.1mm solid black;
  padding: 1mm;
  margin:   0;
}

td.materia
{
  text-align: left;
}

td.numero
{
  text-align: center;
}

td.encabezado
{
  text-align: center;
  width: 10mm;
}

td.encabezado-arriba-img
{
  border: 0;
  width: 47mm;
}

td.encabezado-arriba-centro
{
  border: 0;
  text-align: center;
  font-size: 9pt;
}

td.encabezado-arriba-izq
{
  border: 0;
  text-align: left;
  font-size: 10pt;
}

td.encabezado-arriba-der
{
  border: 0;
  text-align: right;
  font-size: 10pt;
}

img.logo
{
  margin: 0;
  padding: 0;
  width: 45mm;
}

img.logo-chico
{
  margin: 1mm 0mm 1mm 0mm;
  padding: 0;
  width: 25mm;
}

img.firma
{
  margin: 0mm 0mm 0mm 0mm;
  padding: 0;
  width: 35mm;
  position: absolute;
  left: 280px;
}

.la 
{
  margin: 0mm 0mm 0mm 0mm;
  padding: 0;
  position: relative;
}

hr {
  page-break-after: always;
  border:  0;
  margin:  0;
  padding: 0;
}

.page_break { page-break-after: always; }


</style>
</head>
<body>";

$alumno_id = null;
$inicio    = true;
$contador  = 1;
$c = 0 ;
while($row = mysql_fetch_row($result))
  {
   

    $row[3] = ($row[3] == 10) ? $row[3] = number_format($row[3]) : $row[3];
    $row[4] = ($row[4] == 10) ? $row[4] = number_format($row[4]) : $row[4];
    $row[5] = ($row[5] == 10) ? $row[5] = number_format($row[5]) : $row[5];
    $row[6] = ($row[6] == 10) ? $row[6] = number_format($row[6]) : $row[6];
    $row[7] = ($row[7] == 10) ? $row[7] = number_format($row[7]) : $row[7];




    if($alumno_id != $row[13])
      {
	if(!$inicio)
	  {
	   $html .= "


<tr>
<td class=\"encabezado-arriba-centro\" colspan=\"9\"></td>
</tr>

<tr>
<td class=\"encabezado-arriba-centro\" colspan=\"9\">

<div class=\"la\">
<img class=\"firma\" src=\"../img/firma.png\">
</div>

</td>
</tr>

<tr>
<td class=\"encabezado-arriba-centro\" colspan=\"9\"><br /><br /><br /><br /><br />$nombre<br />$nombre_puesto</td>
</tr>

</table>";

	   //if($contador == 2)
	     //$html .= "<br /><br />"; 
	  }
	else
	  {
	    $inicio = false;
	  }
	$alumno_id = $row[13];
  $html .= "<div class=\"page_break\"></div>";
	$html .= encabezado($row[12], $row[14], $fecha_boletas, $o_config->ciclo, $o_config->tipo);
	$html .= "

<table>
<tr>
<td class=\"materia\"><b>$row[0] $row[1] $row[2]</b></td>
<td class=\"encabezado\">1<sup>a</sup> E</td>
<td class=\"encabezado\">F</td>
<td class=\"encabezado\">2<sup>a</sup> E</td>
<td class=\"encabezado\">F</td>
<td class=\"encabezado\">3<sup>a</sup> E</td>
<td class=\"encabezado\">F</td>
<td class=\"encabezado\">Sem.</td>
<td class=\"encabezado\">Final</td>
</tr>

";
	if($contador == 2)
	  {
	    //$html .= "<hr />";
	    $contador = 1;
	  }
	else
	  {
	    $contador++;
	  }
      }

/*    if($row[3] == -1)
      $row[3] = "NP";
    if($row[4] == -1)
      $row[4] = "NP";
    if($row[5] == -1)
      $row[5] = "NP";
    if($row[6] == -1)
      $row[6] = "NP";*/


    if($row[3] < 0)
      $row[3] = "NP";
    if($row[4] < 0)
      $row[4] = "NP";
    if($row[5] < 0)
      $row[5] = "NP";
    if($row[6] < 0)
      $row[6] = "NP";





    /*if($row[7] == -1)
      $row[7] = "NP";*/


    if(!$final)
      $row[7] = null;

    $html .=  "

<tr>
<td class=\"materia\">$row[11]</td>
<td class=\"numero\">$row[3]</td>
<td class=\"numero\">$row[8]</td>
<td class=\"numero\">$row[4]</td>
<td class=\"numero\">$row[9]</td>
<td class=\"numero\">$row[5]</td>
<td class=\"numero\">$row[10]</td>
<td class=\"numero\">$row[6]</td>
<td class=\"numero\">$row[7]</td>
</tr>






";
  }



$html .= "


<tr>
<td class=\"encabezado-arriba-centro\" colspan=\"9\"></td>
</tr>

<tr>
<td class=\"encabezado-arriba-centro\" colspan=\"9\">

<div class=\"la\">
<img class=\"firma\" src=\"../img/firma.png\">
</div>

</td>
</tr>

<tr>
<td class=\"encabezado-arriba-centro\" colspan=\"9\"><br /><br /><br /><br /><br />$nombre<br />$nombre_puesto</td>
</tr>


</table></body></html>";


$pdf = new DOMPDF();

$pdf->set_option("enable_html5_parser", TRUE);

$pdf->set_paper("Letter", "portrait");

//echo $html; return;

$pdf->load_html($html);

$pdf->render();
 
if($final)
  $nombre_archivo = $grupo."-final.pdf";
else
  $nombre_archivo = $grupo.".pdf";


$pdf->stream($nombre_archivo);

//echo $html;


function encabezado($semestre, $nombre_grupo, $fecha, $ciclo, $tipo)
{
 
return "
<br /><br /><br /><br />
<table>
<tr>
<td class=\"encabezado-arriba-img\">
<img class=\"logo\" src=\"../img/SEP_3cm_ch.jpg\">
</td>
<td class=\"encabezado-arriba-centro\">
Subsecretaría de Educación Media Superior<br />
Dirección General de Bachillerato<br />
Escuela Preparatoria Federal por Cooperación<br />
<b>\"QUETZALCÓATL\"</b><br />
<img class=\"logo-chico\" src=\"../img/Logo_Prefeco_Quetzalcoatl_4cm.jpg\"><br />
Clave: EMS-2/123 CCT. 17SBC2123R Tepoztlán, Morelos
</td>
<td class=\"encabezado-arriba-img\">
<img class=\"logo\" src=\"../img/DGB_A.png\">
</td>
</tr>
</table>

<table>
<tr>
<td class=\"encabezado-arriba-centro\">
INFORME DE APROVECHAMIENTO
</td>
<tr>
<td class=\"encabezado-arriba-centro\">Ciclo Escolar $ciclo ($tipo)</td>
</tr>
<tr>
<td class=\"encabezado-arriba-centro\">$fecha</td>
</tr>
</table>
<table>
<tr>
<td class=\"encabezado-arriba-izq\">Semestre: ".$semestre."° </td>
<td class=\"encabezado-arriba-der\">Grupo: $nombre_grupo</td>
</tr>
</table>

";

}

/*
<table>
<tr>
<td class=\"encabezado-arriba-centro\" colspan=\"2\">
\"2017, Año del Centenario de la Promulgación de la Constitución Política de los Estados Unidos Mexicanos\"<br /><br />
INFORME DE APROVECHAMIENTO
</td>
</tr>
<tr>
<td class=\"encabezado-arriba-izq\">Semestre: ".$semestre."° </td>
<td class=\"encabezado-arriba-der\">Grupo: $nombre_grupo</td>
</tr>
</table>
*/

return;


?>