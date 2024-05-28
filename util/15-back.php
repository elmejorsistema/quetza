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
$o_config->control_structure_id2 = 15;
if(!checkCheckMenu($o_user->id, $o_config, $o_database)){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\"></head></html>";
  return;
  }

//echo var_dump($_POST); return;

// Se obtiene la variable de formato
/*
if(empty($_POST['formato']))
{
  echo "<script>alert(\"Error: variable de formato no recibida\")</script>";
  echo "<script>history.go(-1)</script>";
  return;
}
$formato = $_POST['formato'];
*/


// Se obtienen las variables de reprobadas
if(!array_key_exists('reprobada1', $_POST))
{
  echo "<script>alert(\"Error: variable r1 no recibida\")</script>";
  echo "<script>history.go(-1)</script>";
  return;
}
$reprobada1 = $_POST['reprobada1'];

if(!array_key_exists('reprobada2', $_POST))
{
  echo "<script>alert(\"Error: variable r1 no recibida\")</script>";
  echo "<script>history.go(-1)</script>";
  return;
}
$reprobada2 = $_POST['reprobada2'];



// Se obtiene la variable de indexación
if(empty($_POST['indexacion']))
{
  echo "<script>alert(\"Error: variable de indexacion no recibida\")</script>";
  echo "<script>history.go(-1)</script>";
  return;
}
$indexacion = $_POST['indexacion'];



// se obtiene la variable de universo
if(empty($_POST['universo']))
{
      echo "<script>alert(\"Error: variable universo no recibida\")</script>";
      echo "<script>history.go(-1)</script>";
      return;
}
$universo = $_POST['universo'];


// Se crea la consulta general dependiendo del valor del universo
switch($universo)
  {
  case "semestre":
    if(empty($_POST['o-semestre']))
      {
	echo "<script>alert(\"Error: variable semestre no recibida\")</script>";
	echo "<script>history.go(-1)</script>";
	return;
      }
    $semestre = $_POST['o-semestre'];
    $grupo = null;
    $q = "insert into concentrado select a.id, a.1_apellido, a.2_apellido, a.nombre, m.id, m.name, ahe.calif_5, m.secuencia_2, a.capacitacion_id from alumno_has_evaluacion as ahe join ciclo_has_materia as chm on chm.id = ahe.ciclo_has_materia_id join alumno as a on a.id = ahe.alumno_id join materia as m on chm.materia_id = m.id where chm.ciclo_id = '$o_config->ciclo' and  chm.ciclo_tipo = '$o_config->tipo' and m.semestre_id = $semestre  and ((chm.materia_id >= 1 and chm.materia_id <= 95) or (chm.materia_id >= 106  and chm.materia_id <= 113))";


    //$q = "insert into concentrado select a.id, a.1_apellido, a.2_apellido, a.nombre, m.id, m.name, ahe.calif_5, m.secuencia from alumno_has_evaluacion as ahe join ciclo_has_materia as chm on chm.id = ahe.ciclo_has_materia_id join alumno as a on a.id = ahe.alumno_id join materia as m on chm.materia_id = m.id where chm.ciclo_id = '$o_config->ciclo' and  chm.ciclo_tipo = '$o_config->tipo' and m.semestre_id = $semestre  and ((chm.materia_id >= 1 and chm.materia_id <= 95) or (chm.materia_id >= 106  and chm.materia_id <= 113))";
    $universo_e = "Universo: semestre $semestre";  
    //echo $q;
    break;
  case "grupo";
   if(empty($_POST['o-grupo']))
      {
	echo "<script>alert(\"Error: variable grupo no recibida\")</script>";
	echo "<script>history.go(-1)</script>";
	return;
      }
   $grupo =  $_POST['o-grupo'];
   $semestre = null;
   $q="insert into concentrado select a.id, a.1_apellido, a.2_apellido, a.nombre, m.id, m.name, ahe.calif_5, m.secuencia_2, a.capacitacion_id from alumno_has_evaluacion as ahe join ciclo_has_materia as chm on chm.id = ahe.ciclo_has_materia_id join alumno as a on a.id = ahe.alumno_id join materia as m on chm.materia_id = m.id where chm.ciclo_id = '$o_config->ciclo' and  chm.ciclo_tipo = '$o_config->tipo' and chm.grupo_id = $grupo and ((chm.materia_id >= 1 and chm.materia_id <= 95) or (chm.materia_id >= 106  and chm.materia_id <= 113))";

    //$q="insert into concentrado select a.id, a.1_apellido, a.2_apellido, a.nombre, m.id, m.name, ahe.calif_5, m.secuencia from alumno_has_evaluacion as ahe join ciclo_has_materia as chm on chm.id = ahe.ciclo_has_materia_id join alumno as a on a.id = ahe.alumno_id join materia as m on chm.materia_id = m.id where chm.ciclo_id = '$o_config->ciclo' and  chm.ciclo_tipo = '$o_config->tipo' and chm.grupo_id = $grupo and ((chm.materia_id >= 1 and chm.materia_id <= 95) or (chm.materia_id >= 106  and chm.materia_id <= 113))";
    //$q="insert into concentrado select a.id, a.1_apellido, a.2_apellido, a.nombre, m.id, m.name, ahe.calif_5, m.secuencia from alumno_has_evaluacion as ahe join ciclo_has_materia as chm on chm.id = ahe.ciclo_has_materia_id join alumno as a on a.id = ahe.alumno_id join materia as m on chm.materia_id = m.id where chm.ciclo_id = '$o_config->ciclo' and  chm.ciclo_tipo = '$o_config->tipo' and chm.grupo_id = $grupo or chm.grupo_id > 5000) and ((chm.materia_id >= 1 and chm.materia_id <= 95) or (chm.materia_id >= 106  and chm.materia_id <= 113))";

    $qq = "select name from grupo where id = $grupo";
    $o_database->query_fetch_field($qq);
    //$universo_e = "Universo: grupo $grupo";
    $universo_e = "Universo: grupo ".$o_database->query_field;
    //echo $q; return;
    break;
  default:
    echo "<script>alert(\"Error: variable universo no recibida correctamente\")</script>";
    echo "<script>history.go(-1)</script>";
    return;
  }

// La indexación para terceros que toma en cuenta la capcitación
//echo " $universo $grupo $semestre"; 
//if($grupo >= 501 )
if(($grupo >= 501 and $universo == "grupo") or ($semestre >= 5 and $universo == "semestre"))
  {
     switch($indexacion)
      {
      case "alfabetica":
	$indexacion_e =  "Indexación: capacitación, alfabética";
	$order = "order by 7, 2, 1, 5, 4";
	break;
      case "promedio":
	$indexacion_e =  "Indexación: capacitación, promedio";
	$order = "order by 7, 6 desc, 1, 5, 4 ";
	break;
      default:
	$indexacion_e =  "Indexación: capacitación, alfabética";
	$order = "order by 7, 2, 1, 5, 4";
	break;
      }
  }
// La indexación para primero y segundo años que no toma en cuenta la capcitación
else
  {
     switch($indexacion)
      {
      case "alfabetica":
	$indexacion_e =  "Indexación: alfabética";
	$order = "order by 2, 1, 5, 4";
	break;
      case "promedio":
	$indexacion_e =  "Indexación: promedio";
	$order = "order by 6 desc , 1, 5, 4 ";
	break;
      default:
	$indexacion_e =  "Indexación: alfabética";
	$order = "order by 2, 1, 5, 4";
	break;
      }
  }
  



/*
+----------------+----------------------+------+-----+---------+-------+
| Field          | Type                 | Null | Key | Default | Extra |
+----------------+----------------------+------+-----+---------+-------+
| alumno_id      | bigint(20) unsigned  | NO   | PRI | NULL    |       |
| 1_apellido     | varchar(32)          | NO   |     | NULL    |       |
| 2_apellido     | varchar(32)          | NO   |     | NULL    |       |
| nombre         | varchar(32)          | NO   |     | NULL    |       |
| materia_id     | smallint(5) unsigned | NO   | PRI | NULL    |       |
| materia_nombre | varchar(64)          | NO   |     | NULL    |       |
| calif          | decimal(4,2)         | YES  |     | NULL    |       |
+----------------+----------------------+------+-----+---------+-------+
7 rows in set (0.00 sec)
*/

// Se borra y llena la tabla concentrado 
$qq = "delete from concentrado";
//echo $q; return;
$o_database->query_assign($qq);

// Se llena la tabla concentrado
$o_database->query_assign($q);
// Caso especial de las capacitaciones de 3er año de los grupos 5005 y 6006 
if($universo == "grupo" and $grupo >= 501)
  {
    $q = "insert into concentrado  (select cc.alumno_id, cc.1_apellido, cc.2_apellido, cc.nombre, chm.materia_id, m.name, ahe.calif_5, m.secuencia_2, cc.capacitacion_id from concentrado as cc join alumno_has_evaluacion as ahe on cc.alumno_id = ahe.alumno_id join ciclo_has_materia as chm on chm.id = ahe.ciclo_has_materia_id join materia as m on m.id = chm.materia_id where   chm.ciclo_id = '$o_config->ciclo' and  chm.ciclo_tipo = '$o_config->tipo' and chm.grupo_id  >= 5005 group by cc.alumno_id,chm.id)";
    //echo $q; return;
  $o_database->query_assign($q);
  }


/*
+-------------------+----------------------+------+-----+---------+-------+
| Field             | Type                 | Null | Key | Default | Extra |
+-------------------+----------------------+------+-----+---------+-------+
| alumno_id         | bigint(20) unsigned  | NO   | PRI | NULL    |       |
| 1_apellido        | varchar(32)          | NO   |     | NULL    |       |
| 2_apellido        | varchar(32)          | NO   |     | NULL    |       |
| nombre            | varchar(32)          | NO   |     | NULL    |       |
| materia_id        | smallint(5) unsigned | NO   | PRI | NULL    |       |
| materia_nombre    | varchar(64)          | NO   |     | NULL    |       |
| calif             | decimal(4,2)         | YES  |     | NULL    |       |
| materia_secuencia | tinyint(3) unsigned  | NO   |     | NULL    |       |
+-------------------+----------------------+------+-----+---------+-------+
8 rows in set (0.05 sec)
*/

// se borra y llena la tabla concentrado_has_resumen
$qq = "delete from concentrado_has_resumen";
$o_database->query_assign($qq);
//$q = "insert into concentrado_has_resumen select alumno_id, count(calif),0 from concentrado where calif < 6 group by alumno_id"; 
$q = "insert into concentrado_has_resumen select alumno_id, 0, round(avg(calif),3) from concentrado group by alumno_id"; 
$o_database->query_assign($q);

// Se actualizan los promedios en la tabla concentrado_has_resumen
//$q = "update concentrado_has_resumen chr inner join (select  round(avg(calif),3) cali, alumno_id  from concentrado group by alumno_id) as c on chr.alumno_id = c.alumno_id set chr.promedio = c.cali";
$q = "update concentrado_has_resumen chr inner join (select  count(calif) reprobadas, alumno_id from concentrado where calif < 6 group by alumno_id) as c on chr.alumno_id = c.alumno_id set chr.reprobadas = c.reprobadas";
$o_database->query_assign($q);

// Se hace una matriz de materias presentes indexadas por secuencia
$q = "select m.id, m.name from concentrado as c join materia as m on m.id = c.materia_id group by m.id order by m.secuencia_2, m.id";
$o_database->query_rows($q);
$result = $o_database->query_result;
$a_materias = array();
while($row = mysql_fetch_row($result))
  {
    $a_materias[] = array($row[0], $row[1], 0, 0, 0);
  }


//echo var_dump($a_materias);return;

//echo "<br /><br />".var_dump($_POST);
//return;



// Se genera el código html
$html = "<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
<style>

@page {
  margin: 0;
}

html {
  margin: 0;
}

body {
  font-family: font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 7pt;
  margin: 10mm 10mm 10mm 10mm;
}

table {
  border-collapse: collapse;
  padding 0mm;
  margin 0mm;
  margin-left:auto; 
  margin-right:auto;
  width: 100%;
}

table.nuevapagina {
page-break-before: always;
}

table.arriba {
/*page-break-before: always;*/
width=100%;

}

tr.pagebreak
{
page-break-after: always;
}


td {
  border-collapse: collapse;
  border: .1mm solid black;
  padding: .5mm 1mm .5mm 1mm;
  margin   0;
  font-size: 8pt;
}

td.e-materia
{
  padding-right:0px; 
  padding-left: 0px; 
  font-size: 7pt;
  font-weight: bold;
  text-align: center;
  vertical-align: middle;
/*width: 20px;
  height: 40px;

  -webkit-transform:rotate(270deg);
   -moz-transform:rotate(270deg);
   -ms-transform:rotate(270deg);
   -o-transform:rotate(270deg);
   transform:rotate(270deg);
   /*transform-origin: 50% 50%; */
   padding-top:    14px;
   padding-bottom: 14px;
*/
}

td.e-nombre
{
  /*font-size: 12pt;*/
  font-weight: bold;
  text-align: center;
  vertical-align: middle;
}

td.nombre
{
  text-align: left;
}

td.numero
{
  text-align: center;
}
td.numero-reprobado
{
  text-align: center;
  background-color: #EC7E7E;
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

td.encabezado-arriba-centro1
{
  border: 0mm solid black;
  text-align: center;
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

// Se crea el comando tomado en cuenta la indexación y las reprobadas 
/*
+-------------------+----------------------+------+-----+---------+-------+
| Field             | Type                 | Null | Key | Default | Extra |
+-------------------+----------------------+------+-----+---------+-------+
| alumno_id         | bigint(20) unsigned  | NO   | PRI | NULL    |       |
| 1_apellido        | varchar(32)          | NO   |     | NULL    |       |
| 2_apellido        | varchar(32)          | NO   |     | NULL    |       |
| nombre            | varchar(32)          | NO   |     | NULL    |       |
| materia_id        | smallint(5) unsigned | NO   | PRI | NULL    |       |
| materia_nombre    | varchar(64)          | NO   |     | NULL    |       |
| calif             | decimal(4,2)         | YES  |     | NULL    |       |
| materia_secuencia | tinyint(3) unsigned  | NO   |     | NULL    |       |
+-------------------+----------------------+------+-----+---------+-------+
8 rows in set (0.00 sec)

+------------+-----------------------+------+-----+---------+-------+
| Field      | Type                  | Null | Key | Default | Extra |
+------------+-----------------------+------+-----+---------+-------+
| alumno_id  | bigint(20) unsigned   | NO   | PRI | NULL    |       |
| reprobadas | tinyint(3) unsigned   | NO   |     | NULL    |       |
| promedio   | decimal(5,3) unsigned | NO   |     | NULL    |       |
+------------+-----------------------+------+-----+---------+-------+
3 rows in set (0.00 sec)
*/

$q = "select c.alumno_id, concat(concat(concat(concat(c.1_apellido, \" \"), c.2_apellido), \" \"), c.nombre), round(c.calif), c.materia_id, c.materia_secuencia, chr.promedio, capacitacion_id from concentrado as c join concentrado_has_resumen as chr on c.alumno_id = chr.alumno_id where chr.reprobadas >= $reprobada1 and chr.reprobadas <= $reprobada2  $order"; 
$o_database->query_rows($q);
$result = $o_database->query_result;
//echo $q; //return;

$alumno_id            = null;
$inicio               = true;
$nombre               = true;
$total_materias       = count($a_materias);
$contador_materia     = 0;
$contador_registros   = 0;
$pagina               = 1;
$num_registros        = registros_por_pagina($total_materias, $pagina);
$cerro_tabla          = true;
// El ciclo con los datos
//$html .= "<table>";
/*
while($row = mysql_fetch_row($result))
  {
    echo "$row[1]<br />";
    }

return;
*/
/*
echo "<table>";
foreach($a_materias as $value)
  echo "<tr><td>".$value[0]."</td></tr>";
echo "</table>";
return;
*/
while($row = mysql_fetch_row($result))
  {
    $cerro_tabla = false;
    // El id del alumno
    $alumno_id = $row[0];

    // Encabezado
    if($inicio) //and $lista == 1)
      {
	//echo var_dump($a_materias);
        
	$inicio = false;
	//$html .= encabezado($row[12], $row[14], $o_user, $o_config, $row[11]);
        if($pagina == 1)
	  $html .= encabezado();
        $html .= encabezado_tabla($a_materias, $universo_e, $indexacion_e, $reprobada1, $reprobada2, $o_config, $pagina);
      }

    // Datos de id y nombre 
    if($nombre)
      {
	$html .= "<tr><td class=\"numero\">$row[0]</td><td class=\"nombre\">$row[1]</td>";
	$nombre = false;
      }

    // Datos de la materia
    if($row[2] < 6)
      $class = "numero-reprobado";
    else
      $class = "numero";

    //echo "$a_materia[$contador_materia][0] == $row[3]"; exit();
    if($a_materias[$contador_materia][0] == $row[3])
      {
	$html .= "<td class=\"$class\">$row[2]</td>";
	// Calcula el promedio de la materia 
	$a_materias[$contador_materia][2] += $row[2];
	$a_materias[$contador_materia][3]++;
      }
     else
      // Es necesario encontrar la materia
      {
	for($contador_materia; $a_materias[$contador_materia][0] != $row[3]; $contador_materia++)
	     $html .= "<td class=\"numero\"></td>";
        $html .= "<td class=\"$class\">$row[2]</td>";
	$a_materias[$contador_materia][2] += $row[2];
	$a_materias[$contador_materia][3]++;
      }
    
    // Aumentar los contadores
    $contador_materia++;
    if($contador_materia  >= $total_materias)
      {
	// Datos del promedio
	if($row[5] < 6)
	  $class = "numero-reprobado";
	else
	  $class = "numero";

        $contador_materia = 0;
	$html .= "<td class=\"$class\">$row[5]</td></tr>";
	$nombre = true;
	$contador_registros++;
	if($contador_registros > $num_registros)
	  {
            $contador_registros = 0;
	    $pagina++;
            $num_registros = registros_por_pagina($total_materias, $pagina);
	    $inicio = true;
            $html .= "</tbody></table>";
            $cerro_tabla = true;
	  }
      }
  }

// Pone los promedios de las materias por universo
$html .= "<tr><td class=\"numero\" colspan=\"2\"><b>Promedios Generales</b></td>";
$contador = 0;
$promedio_g = 0;
foreach($a_materias as $key => $value)
  {
    $promedio = round($value[2]/$value[3],3);
    $promedio_g += $promedio;
    $contador++;
    // Datos del promedio
    if($promedio < 6)
      $class = "numero-reprobado";
    else
      $class = "numero";

    $html.= "<td class=\"$class\">$promedio</td>";
  }


if($contador > 0)
  $promedio = round($promedio_g/$contador,3);
else
  $promedio = 0;

$html.= "<td class=\"$class\">$promedio</td></tr>";


if(!$cerro_tabla)
  $html .= "</tbody></table>";

$html .= "</body></html>";


//echo $html; return;
 

$pdf = new DOMPDF();

$pdf->set_paper("a4", "landscape");

$pdf->load_html($html);

$pdf->render();

$nombre_archivo = "concentrado.pdf";

$pdf->stream($nombre_archivo);

//echo $html;


function encabezado_tabla($a_materias, $universo_e, $indexacion_e, $reprobada1, $reprobada2, $o_config, $pagina)
{

  if($pagina > 1)
    $class = "class = \"nuevapagina\"";
  else
    $class = null;

  //echo var_dump($a_materias);
  $html = "
<table $class>
<tr>
<td class=\"encabezado-arriba-izq\">Ciclo: ".$o_config->ciclo." (".$o_config->tipo.")</td>
<td class=\"encabezado-arriba-centro1\">$universo_e</td>
<td class=\"encabezado-arriba-centro1\">Reprobadas: <i>r</i> &gt;= $reprobada1 y <i>r</i> &lt;= $reprobada2</td>
<td class=\"encabezado-arriba-centro1\">$indexacion_e</td>
<td class=\"encabezado-arriba-der\">Página: $pagina</td>
</tr>
</table>


<table><tbody><tr><td class=\"e-nombre\">ID</td><td class=\"e-nombre\">NOMBRE</td>";

  foreach($a_materias as $value)
    {
      //$nombre = str_replace ( " " , "<br />", $value[1]);
      $a_nombre = explode(" ", $value[1]);
      $nombre = null;
      foreach($a_nombre as $value1)
	$nombre .= mb_substr($value1, 0, 4)."<br/>";

      //$nombre = substr($value[1], 0, 5);
      $html .= "<td class=\"e-materia\">$nombre</td>";
      //$html .= "<td lass=\"e-materia\"></td>";
    }

  $html .= "<td class=\"e-materia\">PROM</td></tr>";

  return $html;
}


//function encabezado($universo_e, $indexacion_e, $reprobada1, $reprobada2, $o_config, $pagina)
function encabezado()
{ 
return "
<table class=\"arriba\">
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
<tr>
<td class=\"encabezado-arriba-centro\" colspan=\"3\"><b>Concentrado de Calificaciones</b></td>
</tr>
</table>";

}

function registros_por_pagina($total_materias, $pagina)
{
  if($pagina == 1)
    {
      if($total_materias < 16)
	return 25;
      elseif($total_materias < 22)
	return 22;
      else
	return 14;
    }
  else
    {
      if($total_materias < 16)
	return 32;
      elseif($total_materias < 22)
	return 30;
      else
	return 20;
    }
}

return;


?>