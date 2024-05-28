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

$o_config->control_structure_id1 = 16;
$o_config->control_structure_id2 = 20;
if(!checkCheckMenu($o_user->id, $o_config, $o_database)){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\"></head></html>";
  return;
  }



//echo var_dump($_POST);
//manuelgaudencio36   

//var_dump($_GET);
//return;





if(empty($_GET['reporte_id']))
  {
    echo "<script>alert(\"Error: variable no recibida\")</script>";
    echo "<script>history.go(-1)</script>";
    return;
  }
else
  $reporte_id = $_GET['reporte_id'];





switch($reporte_id)
{
    
case 1:
    if(empty($_GET['pago_id']))
    {
        echo "<script>alert(\"Error: variable no recibida\")</script>";
        echo "<script>history.go(-1)</script>";
        return;
    }
    else
        $pago_id = $_GET['pago_id'];
    break;
    
case 2:
    break;

case 3:
    if(empty($_GET['recibo']))
    {
        echo "<script>alert(\"Error: no se marcaron pagos para nuevo recibo\")</script>";
        echo "<script>history.go(-1)</script>";
        return;
    }
    else
        $a_recibo = $_GET['recibo'];
    break;
    
default:
    echo "<script>alert(\"Error: opción no válida\")</script>";
    echo "<script>history.go(-1)</script>";
    return;
}











switch($reporte_id)
{
case 1:
    //imprime_pago($pago_id, $o_database);
    imprime_recibo($pago_id, $o_database, $o_config->alumno_id);
    break;
case 2:
    imprime_edocuenta($o_config->alumno_id,  $o_database, $o_config->ciclo, $o_config->tipo, $o_config->todos_los_ciclos);
    break;
case 3:
    imprime_nuevo_recibo($o_user->id,  $a_recibo, $o_database);
    break; 
default:
    echo "<script>alert(\"Error: variable de reporte no reconocida\")</script>";
    echo "<script>history.go(-1)</script>";
    return;
    break;
}
return;

function imprime_pago($pago_id, $o_database)
{

    // Datos para para la impresión
    $q = "select ahchp.id, chp.id, p.descripcion, ahchp.descripcion, ahchp.monto, ahchp.fecha, a.id, a.nombre, a.1_apellido, a.2_apellido, chp.ciclo_id, chp.ciclo_tipo from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id join alumno as a on ahchp.alumno_id = a.id where  ahchp.id = $pago_id" ;   
    
    $o_database->query_fetch_row($q);
    $result = $o_database->query_row;
    $unix_date=strtotime($result[5]);
    $human_date = date("d-",$unix_date).getSpanishMonth(date("n",$unix_date)).date("-Y",$unix_date);


    
    //echo $ciclo;
        //."\" and ciclo_tipo = \"".$o_config->tipo."\"";


$html = "<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
<style>

@page { 
  margin: 2mm; 
}

html {
  margin: 0;
}

body {
  font-family: font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 9pt;
  margin: 2mm;
}

hr {
border-width: .2px;
}
p.encabezado {
  line-height: 1.7em; 
  text-align: center;
  font-size: 9pt;
}

table {
  width: 100%;
  border-collapse: collapse;
}

td {
  font-family: font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 9pt;
  border-collapse: collapse;
  padding: 1mm;
  margin   0;
  text_align; left;
}
hr {width:50%;}
</style>
<body>
<p class=\"encabezado\">
<img class=\"logo-chico\" style=\"width:45mm;\" src=\"".$domain."Logo_Prefeco_Quetzalcoatl_4cm.jpg\"><br />
Escuela Preparatoria Federal por Cooperación<br />
<b>\"QUETZALCÓATL\"</b><br />
Clave: EMS-2/123 CCT. 17SBC2123R<br />Tepoztlán, Morelos<br />
Ciclo $result[10]  Semestre $result[11]
</p>
<hr />
<p class=\"encabezado\"><b>COMPROBANTE DE PAGO<br />$human_date</b></p>
<table>
<tr><td>Alumna/o:</td><td> $result[7] $result[8] $result[9] ($result[6])</td></tr>
<tr><td>Concepto:</td><td> $result[2]</td></tr>
<tr><td>Monto:</td><td>$".number_format($result[4],2,".",",")."</td></tr>
<tr><td>Pago ID:</td><td> $result[0]</td></tr>
</table>
</body>";


$nombre_archivo = "Pago-$result[0].pdf";
$largo = 300;
$pdf = new Dompdf();
$pdf->set_option("enable_html5_parser", TRUE);
$customPaper = array(0,0,226.77,$largo);
$pdf->set_paper($customPaper);
$pdf->load_html($html);
$pdf->render();
$pdf->stream($nombre_archivo);
/*
   Fin -- Imprimir Pago
*/

//echo $html;

    
}

    function imprime_recibo($recibo_id, $o_database, $alumno_id)
{


    // Datos de la alumna/o
    $q = "select nombre, 1_apellido, 2_apellido, curp from alumno where id = $alumno_id";
    $o_database->query_fetch_row($q);
    $a_alumno = $o_database->query_row;
    
    //echo $q;return;

 // Datos del recibo

    $q = "select fecha from recibo where id = $recibo_id";
    $o_database->query_fetch_field($q);
    $fecha_recibo = $o_database->query_field;

    $q = "select ahchp.id, substr(p.descripcion,1,18), ahchp.descripcion, ahchp.monto, ahchp.fecha, chp.ciclo_id, chp.ciclo_tipo, r.fecha from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id join recibo_has_pago as rhp on rhp.ahc_has_pago_id = ahchp.id join recibo as r on r.id = rhp.recibo_id where ahchp.alumno_id = $alumno_id and r.id = $recibo_id order by chp.fecha_limite, ahchp.id desc" ;   

    //echo $q;return;
    $o_database->query_rows($q);
    $result = $o_database->query_result;
    $largo=310+($o_database->query_num_rows*14);
    $unix_date = strtotime($fecha_recibo);
    $human_date = date("d-",$unix_date).getSpanishMonth(date("n",$unix_date)).date("-Y",$unix_date);



$html = "<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
<style>

@page { 
  margin: 2mm; 
}

html {
  margin: 0;
}

body {
  font-family: font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 9pt;
  margin: 2mm;
}

hr {
border-width: .2px;
}
p.encabezado {
  line-height: 1.7em; 
  text-align: center;
  font-size: 9pt;
}

table {
  width: 100%;
  border-collapse: collapse;
}

td {
  font-family: font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 9pt;
  border-collapse: collapse;
  padding: 1mm;
  margin   0;
  text-align; left;
}

td.derecha {
   text-align: right;
}

hr {width:50%;}
</style>
<body>
<p class=\"encabezado\">
Patronato Pro-Construcción de la Escuela Preparatoria de Tepoztlán Morelos A.C.<br />
RFC: PPE780202TE1<br />
Clave: EMS-2/123 CCT. 17SBC2123R<br />Tepoztlán, Morelos
</p>
<hr />
<p class=\"encabezado\"><b>COMPROBANTE DE PAGO No. $recibo_id<br />$a_alumno[0] $a_alumno[1] $a_alumno[2] ($alumno_id)<br />CURP: $a_alumno[3]<br />$human_date</b></p>
<table>";

//select ahchp.id, p.descripcion, ahchp.descripcion, ahchp.monto, ahchp.fecha, chp.ciclo_id, chp.ciclo_tipo, r.fecha from
$total=0;            
foreach($result as $row)
{
    $html .= "<tr><td>$row[1] $row[5] ($row[6])</td><td class=\"derecha\">".number_format($row[3], 2, ".", ",")."</td></tr>";
    $total += $row[3];
}
$total = number_format($total, 2, ".", ",");
$html .= "<tr><td><b>Total:</b></td><td class=\"derecha\"><b>$total</b></td></tr></table>";

$html .= "<hr><p class=\"encabezado\">Carretera Federal Cuernavaca-Tepoztlán Km. 15.5<br/>Tel. (739) 395-2090<br />quetza.edu.mx</p></body>";


$nombre_archivo = "Recibo-$recibo_id.pdf";

$pdf = new Dompdf();
$pdf->set_option("enable_html5_parser", TRUE);
$customPaper = array(0,0,226.77,$largo);
$pdf->set_paper($customPaper);
$pdf->load_html($html);
$pdf->render();
$pdf->stream($nombre_archivo);

/*
   Fin -- Imprimir Pago
*/

//echo $html;

    
}



function imprime_edocuenta($alumno_id, $o_database, $ciclo, $tipo, $todos_los_ciclos)
{

    // Datos de la alumna/o
    $q = "select nombre, 1_apellido, 2_apellido from alumno where id = $alumno_id";
    //echo $q;return;
    $o_database->query_fetch_row($q);
    $a_alumno = $o_database->query_row;
    // Datos para para la impresión
    if($todos_los_ciclos)
        $q = "select ahchp.id, p.descripcion, ahchp.descripcion, ahchp.monto, ahchp.fecha, chp.ciclo_id, chp.ciclo_tipo from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id where ahchp.alumno_id = $alumno_id order by chp.fecha_limite desc" ;   
    else
        $q = "select ahchp.id, p.descripcion, ahchp.descripcion, ahchp.monto, ahchp.fecha, chp.ciclo_id, chp.ciclo_tipo from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id where ahchp.alumno_id = $alumno_id and chp.ciclo_id = '$ciclo' and chp.ciclo_tipo = '$tipo' order by chp.fecha_limite desc";

    //echo $q;return;
    $o_database->query_rows($q);
    $result = $o_database->query_result;
    $largo=270+($o_database->query_num_rows*18);
    $unix_date=time();
    $human_date = date("d-",$unix_date).getSpanishMonth(date("n",$unix_date)).date("-Y",$unix_date);


    
    //echo $ciclo;
        //."\" and ciclo_tipo = \"".$o_config->tipo."\"";


$html = "<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
<style>

@page { 
  margin: 2mm; 
}

html {
  margin: 0;
}

body {
  font-family: font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 9pt;
  margin: 2mm;
}

hr {
border-width: .2px;
}
p.encabezado {
  line-height: 1.7em; 
  text-align: center;
  font-size: 9pt;
}

table {
  width: 100%;
  border-collapse: collapse;
}

td {
  font-family: font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 8pt;
  border-collapse: collapse;
  padding: 1mm;
  margin   0;
  text-align: left;
}

span.chica {font-size: 6pt;}

td.d {
  font-family: font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 8pt;
  border-collapse: collapse;
  padding: 1mm;
  margin   0;
  text-align: right;
}
hr {width:50%;}
</style>
<body>
<p class=\"encabezado\">
Escuela Preparatoria Federal por Cooperación<br />
<b>\"QUETZALCÓATL\"</b><br />
Clave: EMS-2/123 CCT. 17SBC2123R<br />Tepoztlán, Morelos<br />";

if(!$todos_los_ciclos)
    $html .= "Ciclo $ciclo  Semestre $tipo";

$html .= "</p>
<hr />
<p class=\"encabezado\"><b>Estado de Cuenta<br />$a_alumno[0] $a_alumno[1] $a_alumno[2] ($alumno_id)<br />Fecha de impresión: $human_date</b></p><table>";
foreach($result as $row)
{
    if(!$todos_los_ciclos)
        $html.= "<tr><td>$row[4]</td><td>$row[1]</td><td class=\"d\">$".number_format($row[3],2,".",",")."</td></tr>";
    else
        $html.= "<tr><td>$row[4]</td><td>$row[1]<br /><span class=\"chica\">[$row[5] ($row[6])]</span></td><td class=\"d\">$".number_format($row[3],2,".",",")."</td></tr>"; 
}


 $html.= "</table><hr><p class=\"encabezado\">Carretera Federal Cuernavaca-Tepoztlán Km. 15.5<br/>Tel. (739) 395-2090<br />quetza.edu.mx</p></body>";


$nombre_archivo = "EstadoCuenta-$alumno_id.pdf";
//$largo = 300;
$pdf = new Dompdf();
$pdf->set_option("enable_html5_parser", TRUE);
$customPaper = array(0,0,226.77,$largo);
$pdf->set_paper($customPaper);
$pdf->load_html($html);
$pdf->render();
$pdf->stream($nombre_archivo);

/*
   Fin -- Imprimir Pago
*/

//echo $html;

    
}

function imprime_nuevo_recibo($user_id, $a_recibo, $o_database)
{

    $q = "insert into recibo values ( null, $user_id, now() )";
    $o_database->query_assign($q);
    $insert_id = mysql_insert_id(); 
    //return;

    $q = "insert into recibo_has_pago values ";
    $num_pagos = count($a_recibo);
    
    foreach($a_recibo as $value)
    {
        $q .= "($insert_id,$value)";
        $num_pagos --;
        if($num_pagos > 0)
            $q .= ",";
    }
    echo $q;
    $o_database->query_assign($q);


    echo   "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../index.php?cs1=16&cs2=17\"></head></html>";

    
    return;
    
    // Datos para para la impresión
    $q = "select ahchp.id, chp.id, p.descripcion, ahchp.descripcion, ahchp.monto, ahchp.fecha, a.id, a.nombre, a.1_apellido, a.2_apellido, chp.ciclo_id, chp.ciclo_tipo from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id join alumno as a on ahchp.alumno_id = a.id where  ahchp.id = $pago_id" ;   
    
    $o_database->query_fetch_row($q);
    $result = $o_database->query_row;
    $unix_date=strtotime($result[5]);
    $human_date = date("d-",$unix_date).getSpanishMonth(date("n",$unix_date)).date("-Y",$unix_date);


    
    //echo $ciclo;
        //."\" and ciclo_tipo = \"".$o_config->tipo."\"";


$html = "<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
<style>

@page { 
  margin: 2mm; 
}

html {
  margin: 0;
}

body {
  font-family: font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 9pt;
  margin: 2mm;
}

hr {
border-width: .2px;
}
p.encabezado {
  line-height: 1.7em; 
  text-align: center;
  font-size: 9pt;
}

table {
  width: 100%;
  border-collapse: collapse;
}

td {
  font-family: font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 9pt;
  border-collapse: collapse;
  padding: 1mm;
  margin   0;
  text_align; left;
}
hr {width:50%;}
</style>
<body>
<p class=\"encabezado\">
<img class=\"logo-chico\" style=\"width:45mm;\" src=\"".$domain."Logo_Prefeco_Quetzalcoatl_4cm.jpg\"><br />
Escuela Preparatoria Federal por Cooperación<br />
<b>\"QUETZALCÓATL\"</b><br />
Clave: EMS-2/123 CCT. 17SBC2123R<br />Tepoztlán, Morelos<br />
Ciclo $result[10]  Semestre $result[11]
</p>
<hr />
<p class=\"encabezado\"><b>COMPROBANTE DE PAGO<br />$human_date</b></p>
<table>
<tr><td>Alumna/o:</td><td> $result[7] $result[8] $result[9] ($result[6])</td></tr>
<tr><td>Concepto:</td><td> $result[2]</td></tr>
<tr><td>Monto:</td><td>$".number_format($result[4],2,".",",")."</td></tr>
<tr><td>Pago ID:</td><td> $result[0]</td></tr>
</table>
</body>";


$nombre_archivo = "Pago-$result[0].pdf";
$largo = 300;
$pdf = new Dompdf();
$pdf->set_option("enable_html5_parser", TRUE);
$customPaper = array(0,0,226.77,$largo);
$pdf->set_paper($customPaper);
$pdf->load_html($html);
$pdf->render();
$pdf->stream($nombre_archivo);
/*
   Fin -- Imprimir Pago
*/

//echo $html;

    
}




///////////////////////////////////////////////////////////////////////////

$html = "<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
<style>

html {
  margin: 0;
}

body {
  font-family: font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: $tamagno_letra pt;
  margin: 5mm 15mm 0mm 15mm;
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
foreach($result as $row)
  {
    if($alumno_id != $row[13])
      {
	if(!$inicio)
	  {
	   $html .= "
</table>";

	   if($contador == 2)
	     $html .= "<br /><br />"; 
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

    if($row[3] == -1)
      $row[3] = "NP";
    if($row[4] == -1)
      $row[4] = "NP";
    if($row[5] == -1)
      $row[5] = "NP";
    if($row[6] == -1)
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

$html .= "</table></body></html>";


$pdf = new Dompdf();
$pdf->set_option("enable_html5_parser", TRUE);
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

";

}



return;


?>