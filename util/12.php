<?php
include "utility.php";
include "functions.php";

include "../clases.php";
include "../config/dbconfig.php";

require_once ('../dompdf/vendor/autoload.php');
use Dompdf\Dompdf;
use Dompdf\Options;

session_name($session_name);
session_start();

//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);


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

//var_dump($o_config);return;

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



// Check that this is a valid menu
//////////////////////////////////////////////

$o_config->control_structure_id1 = 10;
$o_config->control_structure_id2 = 11;
if(!checkCheckMenu($o_user->id, $o_config, $o_database)){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\"></head></html>";
  return;
  }



if(empty($_POST['chm']))
  {
    echo "<script>alert(\"Error: variable no recibida\")</script>";
    echo "<script>history.go(-1)</script>";
    return;
  }
else
  $chm = $_POST['chm'];

if(empty($_POST['lista']))
  {
    echo "<script>alert(\"Error: variable no recibida\")</script>";
    echo "<script>history.go(-1)</script>";
    return;
  }
else
  $lista = $_POST['lista'];

$q = "select s.id, chm.materia_id from ciclo_has_materia as chm join grupo as g on chm.grupo_id = g.id join semestre as s on s.id = g.semestre_id where chm.id = $chm";
$o_database->query_fetch_row($q);
$semestre   = $o_database->query_row[0];
$materia_id = $o_database->query_row[1];


//echo $grupo . " | " . $lista;
//echo $semestre;
//return;

/*
0 por nombre de alumno
1 por capacitación
2 por grupo
*/
$index = 0;
/*
switch($semestre)
  {
  case 1:
  case 2:
  case 3:
  case 4:

    // Estos son los talleres para primer año
    if(($materia_id > 95 and $materia_id < 106) or $materia_id == 114 or ($materia_id >=  120 and $materia_id <= 121) or ($materia_id >=  124 and $materia_id <= 133))
      {
	$q = "select a.1_apellido, a.2_apellido ,a.nombre, round(ahe.calif_1), round(ahe.calif_2), round(ahe.calif_3), round(ahe.calif_4),round(ahe.calif_5),faltas_1, faltas_2, faltas_3, m.name, m.semestre_id, a.id, chm.grupo_name, u.nombre, u.1_apellido, u.2_apellido, a.grupo_id, g.name from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and chm.id = $chm join materia as m on chm.materia_id = m.id join grupo as g on g.id = a.grupo_id join user as u on u.id = chm.user_id where  chm.ciclo_id = \"".$o_config->ciclo."\" and chm.ciclo_tipo = \"".$o_config->tipo."\" order by 19,1,2,3";
	//$q = "select a.1_apellido, a.2_apellido ,a.nombre, round(ahe.calif_1), round(ahe.calif_2), round(ahe.calif_3), round(ahe.calif_4),round(ahe.calif_5),faltas_1, faltas_2, faltas_3, m.name, m.semestre_id, a.id, chm.grupo_name, u.nombre, u.1_apellido, u.2_apellido, a.grupo_id, g.name from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and chm.id = $chm join materia as m on chm.materia_id = m.id join grupo as g on g.id = a.grupo_id join user as u on u.id = chm.user_id where  chm.ciclo_id = \"".$o_config->ciclo."\" and chm.ciclo_tipo = \"".$o_config->tipo."\" order by 19,1,2,3";
	$index= 2;
      }
    else
      $q = "select a.1_apellido, a.2_apellido ,a.nombre, round(ahe.calif_1), round(ahe.calif_2), round(ahe.calif_3), round(ahe.calif_4),round(ahe.calif_5),faltas_1, faltas_2, faltas_3, m.name, m.semestre_id, a.id, chm.grupo_name, u.nombre, u.1_apellido, u.2_apellido from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and chm.id = $chm join materia as m on chm.materia_id = m.id join user as u on u.id = chm.user_id where  chm.ciclo_id = \"".$o_config->ciclo."\" and chm.ciclo_tipo = \"".$o_config->tipo."\" order by 1,2,3";
    break;
    // 5 y 6 semestre
  default:
    // Estos son las capacitaciones para tercer año
    if(($materia_id > 58 and $materia_id < 65) or ($materia_id > 38 and $materia_id < 45) or ($materia_id > 109 and $materia_id < 114) or ($materia_id > 117 and $materia_id < 120) or ($materia_id > 135 and $materia_id < 144) or ($materia_id > 144 and $materia_id < 153))
      {
	$q = "select a.1_apellido, a.2_apellido ,a.nombre, round(ahe.calif_1), round(ahe.calif_2), round(ahe.calif_3), round(ahe.calif_4),round(ahe.calif_5),faltas_1, faltas_2, faltas_3, m.name, m.semestre_id, a.id, chm.grupo_name, u.nombre, u.1_apellido, u.2_apellido, a.grupo_id, g.name from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and chm.id = $chm join materia as m on chm.materia_id = m.id join grupo as g on g.id = a.grupo_id join user as u on u.id = chm.user_id where  chm.ciclo_id = \"".$o_config->ciclo."\" and chm.ciclo_tipo = \"".$o_config->tipo."\" order by 19,1,2,3";
	$index= 2;
	//echo $q; return;
      }
    else 
    // Todos los demás terceros se indexan por capacitación
      {
	$q = "select a.1_apellido, a.2_apellido ,a.nombre, round(ahe.calif_1), round(ahe.calif_2), round(ahe.calif_3), round(ahe.calif_4),round(ahe.calif_5),faltas_1, faltas_2, faltas_3, m.name, m.semestre_id, a.id, chm.grupo_name, u.nombre, u.1_apellido, u.2_apellido, a.capacitacion_id, c.name from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and chm.id = $chm join materia as m on chm.materia_id = m.id join user as u on u.id = chm.user_id join capacitacion as c on c.id = a.capacitacion_id where  chm.ciclo_id = \"".$o_config->ciclo."\" and chm.ciclo_tipo = \"".$o_config->tipo."\" order by 19,1,2,3";
	$index = 1;
      }
    break;
  }

*/



switch($semestre)
  {
  case 1:
  case 2:
  case 3:
  case 4:

    // Estos son los talleres para primer año
    if(($materia_id > 95 and $materia_id < 106) or $materia_id == 114 or ($materia_id >=  120 and $materia_id <= 121) or ($materia_id >=  124 and $materia_id <= 133) or ($materia_id >=  172 and $materia_id <= 177) or ($materia_id >= 187 and $materia_id <= 190))
      {
  $q = "select a.1_apellido, a.2_apellido ,a.nombre, ahe.calif_1, ahe.calif_2, ahe.calif_3, ahe.calif_4, ahe.calif_5, faltas_1, faltas_2, faltas_3, m.name, m.semestre_id, a.id, chm.grupo_name, u.nombre, u.1_apellido, u.2_apellido, a.grupo_id, g.name from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and chm.id = $chm join materia as m on chm.materia_id = m.id join grupo as g on g.id = a.grupo_id join user as u on u.id = chm.user_id where  chm.ciclo_id = \"".$o_config->ciclo."\" and chm.ciclo_tipo = \"".$o_config->tipo."\" order by 19,1,2,3";
  //$q = "select a.1_apellido, a.2_apellido ,a.nombre, round(ahe.calif_1), round(ahe.calif_2), round(ahe.calif_3), round(ahe.calif_4),round(ahe.calif_5),faltas_1, faltas_2, faltas_3, m.name, m.semestre_id, a.id, chm.grupo_name, u.nombre, u.1_apellido, u.2_apellido, a.grupo_id, g.name from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and chm.id = $chm join materia as m on chm.materia_id = m.id join grupo as g on g.id = a.grupo_id join user as u on u.id = chm.user_id where  chm.ciclo_id = \"".$o_config->ciclo."\" and chm.ciclo_tipo = \"".$o_config->tipo."\" order by 19,1,2,3";
  $index= 2;
      }
    else
      $q = "select a.1_apellido, a.2_apellido ,a.nombre, ahe.calif_1, ahe.calif_2, ahe.calif_3, ahe.calif_4, ahe.calif_5,faltas_1, faltas_2, faltas_3, m.name, m.semestre_id, a.id, chm.grupo_name, u.nombre, u.1_apellido, u.2_apellido from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and chm.id = $chm join materia as m on chm.materia_id = m.id join user as u on u.id = chm.user_id where  chm.ciclo_id = \"".$o_config->ciclo."\" and chm.ciclo_tipo = \"".$o_config->tipo."\" order by 1,2,3";
    break;
    // 5 y 6 semestre
  default:
    // Estos son las capacitaciones para tercer año
    if(($materia_id > 58 and $materia_id < 65) or ($materia_id > 38 and $materia_id < 45) or ($materia_id > 109 and $materia_id < 114) or ($materia_id > 117 and $materia_id < 120) or ($materia_id > 135 and $materia_id < 144) or ($materia_id > 144 and $materia_id < 153) or ($materia_id > 155 and $materia_id < 172))
      {
  $q = "select a.1_apellido, a.2_apellido ,a.nombre, ahe.calif_1, ahe.calif_2, ahe.calif_3, ahe.calif_4, ahe.calif_5, faltas_1, faltas_2, faltas_3, m.name, m.semestre_id, a.id, chm.grupo_name, u.nombre, u.1_apellido, u.2_apellido, a.grupo_id, g.name from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and chm.id = $chm join materia as m on chm.materia_id = m.id join grupo as g on g.id = a.grupo_id join user as u on u.id = chm.user_id where  chm.ciclo_id = \"".$o_config->ciclo."\" and chm.ciclo_tipo = \"".$o_config->tipo."\" order by 19,1,2,3";
  $index= 2;
  //echo $q; return;
      }
    else 
    // Todos los demás terceros se indexan por capacitación
      {
  $q = "select a.1_apellido, a.2_apellido ,a.nombre, ahe.calif_1, ahe.calif_2, ahe.calif_3, ahe.calif_4, ahe.calif_5, faltas_1, faltas_2, faltas_3, m.name, m.semestre_id, a.id, chm.grupo_name, u.nombre, u.1_apellido, u.2_apellido, a.capacitacion_id, c.name from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and chm.id = $chm join materia as m on chm.materia_id = m.id join user as u on u.id = chm.user_id join capacitacion as c on c.id = a.capacitacion_id where  chm.ciclo_id = \"".$o_config->ciclo."\" and chm.ciclo_tipo = \"".$o_config->tipo."\" order by 19,1,2,3";
  $index = 1;
      }
    break;
  }





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
  font-size: 7pt;
  margin: 10mm 15mm 10mm 15mm;
}

table {
  border-collapse: collapse;
  width: 186mm;
  padding: 0mm;
  margin: 0mm;
}

td {
  border-collapse: collapse;
  border: .1mm solid black;
  padding: .5mm 1mm .5mm 1mm;
  margin:  0;
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
  border: 0mm solid black;
  width: 47mm;
}

td.encabezado-arriba-centro
{
  border: 0mm solid black;
  text-align: center;
  font-size: 9pt;
}

td.encabezado-arriba-izq
{
  border: 0mm solid black;
  text-align: left;
  font-size: 10pt;
}

td.encabezado-arriba-der
{
  border: 0mm;
  text-align: right;
  font-size: 10pt;
}

td.encabezado-arriba-cen
{
  border: 0mm;
  text-align: center;
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

p.firma {
    border-bottom: .1mm solid black;
    border-top: 0mm;
    border-right: 0mm;
    border-left: 0mm;
    margin: 0mm;
    margin-left: 56mm;
    margin-top: 1mm;
    width:   75mm;
    height: 10mm; 
    text-align: center; 
}

</style>
</head>
<body>";

$alumno_id = null;
$inicio    = true;
$contador  = 1;
$capacitacion = null;
foreach($result as $row)
  {
   

    $row[3] = ($row[3] == 10) ? $row[3] = number_format($row[3]) : $row[3];
    $row[4] = ($row[4] == 10) ? $row[4] = number_format($row[4]) : $row[4];
    $row[5] = ($row[5] == 10) ? $row[5] = number_format($row[5]) : $row[5];
    $row[6] = ($row[6] == 10) ? $row[6] = number_format($row[6]) : $row[6];
    $row[7] = ($row[7] == 10) ? $row[7] = number_format($row[7]) : $row[7];    

    if($inicio) //and $lista == 1)
      {
	/*
	if($semestre  > 4)
	  $capacitacion = $row[18];
	*/


	switch($index)
	  {
	  case 0:
	    break;
	  case 1:
	  case 2:
	    $capacitacion = $row[18];
	    break;
	  }
	



        $nombre_grupo = $row[14];
        $profesor = $row[15]. " " .$row[16]. " " . $row[17];
	$inicio = false;
	$html .= encabezado($row[12], $row[14], $o_user, $o_config, $row[11], $o_config->domain);
	$html .= "

<table>
";

	if($lista == 1 or $lista == 2)
	  {
	  $html .= "


<tr>
<td class=\"materia\"><b>&nbsp;</b></td>
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
	  /*
	  if($semestre > 4)
	    $html .= "<tr><td class=\"numero\" colspan=\"9\">$row[19]</td></tr>";
	  */


	  switch($index)
	    {
	    case 0:
	      break;
	    case 1:
	    case 2:
	      $html .= "<tr><td class=\"numero\" colspan=\"9\">$row[19]</td></tr>";
	      break;
	    }




	  }

	else
	  {
	  $html .=  "

<tr>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
</tr>
<tr>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
</tr>
";

	  /*
	  if($semestre > 4)
	    $html .= "<tr><td class=\"numero\" colspan=\"19\">$row[19]</td></tr>";
	  */

	  switch($index)
	    {
	    case 0:
	      break;
	    case 1:
	    case 2:
	      $html .= "<tr><td class=\"numero\" colspan=\"19\">$row[19]</td></tr>";
	      break;
	    }
	  }
      }
     

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



    if($lista == 1)
      $row[7] = null;

    if($lista == 1 or $lista == 2)
      {
	/*
	if($semestre > 4)
	  if($capacitacion != $row[18])
	    {
	      $capacitacion = $row[18];
	      $html .= "<tr><td class=\"numero\" colspan=\"9\">$row[19]</td></tr>";
	    }
	*/


	switch($index)
	    {
	    case 0:
	      break;
	    case 1:
	    case 2:
	      if($capacitacion != $row[18])
		{
		  $capacitacion = $row[18];
		  $html .= "<tr><td class=\"numero\" colspan=\"9\">$row[19]</td></tr>";
		}
	      break;
	    }

    $html .=  "

<tr>
<td class=\"materia\">$row[0] $row[1] $row[2]</td>
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
    else
      {
	/*
	if($semestre > 4)
	  if($capacitacion != $row[18])
	    {
	      $capacitacion = $row[18];
	      $html .= "<tr><td class=\"numero\" colspan=\"19\">$row[19]</td></tr>";
	    }
	*/

	switch($index)
	    {
	    case 0:
	      break;
	    case 1:
	    case 2:
	      if($capacitacion != $row[18])
		{
		  $capacitacion = $row[18];
		  $html .= "<tr><td class=\"numero\" colspan=\"19\">$row[19]</td></tr>";
		}
	      break;
	    }




    $html .=  "

<tr>
<td class=\"materia\">$row[0] $row[1] $row[2]</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
<td class=\"numero\">&nbsp;</td>
</tr>
";
      }

  }

$html .= "</table>
";

if($lista == 1 or $lista == 2)
$html .=  "

<p class=\"firma\">&nbsp;</p>
<table>
<tr>
<td class=\"encabezado-arriba-cen\" >Profesor/a: ".$profesor."</td>
</tr>
</table>
";

$html .=  "

</body>
</html>";
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
//$options->set('debugKeepTemp', true); // Keep temporary files for debugging
$options->set('isRemoteEnabled', true);
$options->set("enable_html5_parser", true);
$pdf = new Dompdf($options);

//$pdf->set_option("enable_html5_parser", TRUE);

$pdf->setPaper("Letter", "portrait");

$pdf->loadHtml($html);

//echo $html;return;

$pdf->render();

switch($lista)
  {
  case 1:
    $nombre_archivo = $nombre_grupo. "-Calificaciones.pdf";
    break;
  case 2:
    $nombre_archivo = $nombre_grupo. "-Calificaciones-Final.pdf";
    break;
  case 3:
    $nombre_archivo = $nombre_grupo. "-Control.pdf";
    break;
  default:
    $nombre_archivo = $nombre_grupo.".pdf";
  }

$pdf->stream($nombre_archivo);

//echo $html;


function encabezado($semestre, $nombre_grupo, $o_user, $o_config, $materia, $domain)
{
 
return "
<table>
<tr>
<td class=\"encabezado-arriba-img\">
<img class=\"logo\" src=\"".$domain."SEP_3cm.jpg\">
</td>
<td class=\"encabezado-arriba-centro\">
Subsecretaría de Educación Media Superior<br />
Dirección General de Bachillerato<br />
Escuela Preparatoria Federal por Cooperación<br />
<b>\"QUETZALCÓATL\"</b><br />
<img class=\"logo-chico\" src=\"".$domain."Logo_Prefeco_Quetzalcoatl_4cm.jpg\"><br />
Clave: EMS-2/123 CCT. 17SBC2123R Tepoztlán, Morelos
</td>
<td class=\"encabezado-arriba-img\">
<img class=\"logo\" src=\"".$domain."DGB_A.png\">
</td>
</tr>
</table>

<table>
<tr>
<td class=\"encabezado-arriba-izq\">Ciclo: ".$o_config->ciclo." (".$o_config->tipo.")</td>
<td class=\"encabezado-arriba-der\">Grupo: $nombre_grupo</td>
</tr>

<tr>
<td class=\"encabezado-arriba-izq\">Materia: $materia</td>
<td class=\"encabezado-arriba-der\">Semestre: ".$semestre."° </td>
</tr>
</table>";

}



return;


?>