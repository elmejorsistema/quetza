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



// Check that this is a valid menu
//////////////////////////////////////////////

$o_config->control_structure_id1 = 10;
$o_config->control_structure_id2 = 11;
if(!checkCheckMenu($o_user->id, $o_config, $o_database)){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\"></head></html>";
  return;
  }



//echo var_dump($_POST);
//manuelgaudencio36   

//var_dump($_POST);
//return;

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

$q = "select s.id from ciclo_has_materia as chm join grupo as g on chm.grupo_id = g.id join semestre as s on s.id = g.semestre_id where chm.id = $chm";
$o_database->query_fetch_field($q);
$semestre = $o_database->query_field;


//echo $grupo . " | " . $lista;
//echo $semestre;
//return;

switch($semestre)
  {
  case 1:
  case 2:
  case 3:
  case 4:
    $q = "select a.1_apellido, a.2_apellido ,a.nombre, round(ahe.calif_1), round(ahe.calif_2), round(ahe.calif_3), round(ahe.calif_4),round(ahe.calif_5),faltas_1, faltas_2, faltas_3, m.name, m.semestre_id, a.id, chm.grupo_name, u.nombre, u.1_apellido, u.2_apellido from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and chm.id = $chm join materia as m on chm.materia_id = m.id join user as u on u.id = chm.user_id where  ciclo_id = \"".$o_config->ciclo."\" and ciclo_tipo = \"".$o_config->tipo."\" order by 1,2,3";
    break;
    // 5 y 6 semestre
  default:
    $q = "select a.1_apellido, a.2_apellido ,a.nombre, round(ahe.calif_1), round(ahe.calif_2), round(ahe.calif_3), round(ahe.calif_4),round(ahe.calif_5),faltas_1, faltas_2, faltas_3, m.name, m.semestre_id, a.id, chm.grupo_name, u.nombre, u.1_apellido, u.2_apellido, a.capacitacion_id, c.name from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id join ciclo_has_materia as chm on ahe.ciclo_has_materia_id = chm.id and chm.id = $chm join materia as m on chm.materia_id = m.id join user as u on u.id = chm.user_id join capacitacion as c on c.id = a.capacitacion_id where  ciclo_id = \"".$o_config->ciclo."\" and ciclo_tipo = \"".$o_config->tipo."\" order by 19,1,2,3";
    break;
  }


$o_database->query_rows($q);
$result = $o_database->query_result;

//echo $q ; return;

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
  font-size: 7pt;
  margin: 10mm 15mm 10mm 15mm;
}

table {
  border-collapse: collapse;
  width: 186mm;
  padding 0mm;
  margin 0mm;
}

td {
  border-collapse: collapse;
  border: .1mm solid black;
  padding: .5mm 1mm .5mm 1mm;
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
  padding 0;
  width: 45mm;
}

img.logo-chico
{
  margin: 1mm,0mm,1mm,0mm;
  padding 0;
  width: 25mm;
}

p.firma {
    border-bottom: .1mm solid black;
    border-top 0mm;
    border-right 0mm;
    border-left 0mm;
    margin 0mm;
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
while($row = mysql_fetch_row($result))
  {
   

    if($inicio) //and $lista == 1)
      {
	$capacitacion = $row[18];

        $nombre_grupo = $row[14];
        $profesor = $row[15]. " " .$row[16]. " " . $row[17];
	$inicio = false;
	$html .= encabezado($row[12], $row[14], $o_user, $o_config, $row[11]);
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
	  if($semestre > 4)
	    $html .= "<tr><td class=\"numero\" colspan=\"9\">$row[19]</td></tr>";
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
	  if($semestre > 4)
	    $html .= "<tr><td class=\"numero\" colspan=\"19\">$row[19]</td></tr>";
	  }
      }
     

    if($lista == 1)
      $row[7] = null;

    if($lista == 1 or $lista == 2)
      {

	if($semestre > 4)
	  if($capacitacion != $row[18])
	    {
	      $capacitacion = $row[18];
	      $html .= "<tr><td class=\"numero\" colspan=\"9\">$row[19]</td></tr>";
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

	if($semestre > 4)
	  if($capacitacion != $row[18])
	    {
	      $capacitacion = $row[18];
	      $html .= "<tr><td class=\"numero\" colspan=\"19\">$row[19]</td></tr>";
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


$pdf = new DOMPDF();

$pdf->set_paper("Letter", "portrait");

$pdf->load_html($html);

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


function encabezado($semestre, $nombre_grupo, $o_user, $o_config, $materia)
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
</td>
<td class=\"encabezado-arriba-img\">
<img class=\"logo\" src=\"../img/DGB_A.png\">
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