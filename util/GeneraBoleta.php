<?php
include "utility.php";
include "functions.php";

include "../clases.php";
include "../config/dbconfig.php";

require_once ('../dompdf/dompdf_config.inc.php');

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

if(empty($_GET['final']))
  {
    $final = 0;
  }
else
  $final = 1;




$q = "select a.1_apellido, a.2_apellido ,a.nombre, round(ahe.calif_1), round(ahe.calif_2), round(ahe.calif_3), round(ahe.calif_4),round(ahe.calif_5),faltas_1, faltas_2, faltas_3, m.name, m.semestre_id, a.id, chm.grupo_name from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id join materia as m on chm.materia_id = m.id where  ciclo_id = \"".$o_config->ciclo."\" and ciclo_tipo = \"".$o_config->tipo."\" order by 1,2,3,11";

$o_database->query_rows($q);
$result = $o_database->query_result;

//echo $q ; ;

//select grupo_id from  ciclo_has_materia where  ciclo_id = \"".$o_config->ciclo."\" and ciclo_tipo = \"".$o_config->tipo."\"";

$html = "<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
<style>

html {
  margin: 0;
}

body {
  font-family: font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10pt;
  margin: 15mm 15mm 15mm 15mm;
}

table {
  border-collapse: collapse;
  width: 186mm;
}

td {
  border-collapse: collapse;
  border: .1mm solid black;
  padding: 1mm;
  margin   0;
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
  padding 0;
  width: 45mm;
}

img.logo-chico
{
  margin: 1mm,0mm,1mm,0mm;
  padding 0;
  width: 25mm;
}

hr {
  page-break-after: always;
  border:  0;
  margin:  0;
  padding: 0;
}


</style>
</head>
<body>";

$alumno_id = null;
$inicio    = true;
$contador  = 1;
while($row = mysql_fetch_row($result))
  {
    if($alumno_id != $row[13])
      {
	if(!$inicio)
	  {
	   $html .= "
</table>";

	   if($contador == 2)
	     $html .= "<br /><br /><br /><br />"; 
	  }
	else
	  {
	    $inicio = false;
	  }
	$alumno_id = $row[13];
	$html .= encabezado($row[12], $row[14]);
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
	    $html .= "<hr />";
	    $contador = 1;
	  }
	else
	  {
	    $contador++;
	  }
      }

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

$html .= "</table></body></html>";


$pdf = new DOMPDF();

$pdf->set_paper("Letter", "portrait");

$pdf->load_html($html);

$pdf->render();
 
if($final)
  $nombre_archivo = $grupo."-final.pdf";
else
  $nombre_archivo = $grupo.".pdf";

$pdf->stream($nombre_archivo);

//echo $html;


function encabezado($semestre, $nombre_grupo)
{
 
return "

<table>
<tr>
<td class=\"encabezado-arriba-img\">
<img class=\"logo\" src=\"../img/SEP_3cm.jpg\">
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
<td class=\"encabezado-arriba-centro\" colspan=\"2\">
\"2016, Año del Bicentenario de la Declaración de la Independencia Nacional\"<br /><br />
INFORME DE APROVECHAMIENTO
</td>
</tr>
<tr>
<td class=\"encabezado-arriba-izq\">Semestre: ".$semestre."° </td>
<td class=\"encabezado-arriba-der\">Grupo: $nombre_grupo</td>
</tr>
</table>

";

}



return;


?>