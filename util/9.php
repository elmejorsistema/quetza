<?php

// Abre los parciales 


$ev1 = null;
$ev2 = null;
$ev3 = null;
$ev4 = null;

$q = "select evaluacion1,evaluacion2,evaluacion3,evaluacion4,fecha_boletas,nombre_puesto,titulo_academico,user_id from ciclo where ciclo.id = \"".$o_config->ciclo."\" and tipo = \"".$o_config->tipo."\""; 
$o_database->query_fetch_row($q);


if($o_database->query_row[0])
  $ev1 = "checked";
if($o_database->query_row[1])
  $ev2 = "checked";
if($o_database->query_row[2])
  $ev3 = "checked";
if($o_database->query_row[3])
  $ev4 = "checked";

$fecha_boletas = $o_database->query_row[4];
$dt_fecha_boletas = new DateTime($fecha_boletas);
$fecha_boletas = $dt_fecha_boletas->format("d-m-Y");
$nombre_puesto = $o_database->query_row[5];
$titulo_academico = $o_database->query_row[6];
$user_id = $o_database->query_row[7];

$q = "select nombre,1_apellido,2_apellido from user where id = ".$user_id;
$o_database->query_fetch_row($q);
$nombre = $o_database->query_row[0]." ".$o_database->query_row[1]." ".$o_database->query_row[2];




echo "
<form id=\"formaparcial\">
<input type=\"hidden\" name=\"cs1\" value=\"$o_config->control_structure_id1\">
<input type=\"hidden\" name=\"cs2\" value=\"$o_config->control_structure_id2\">
<br /><br />
<table>

<tr>
<td colspan=\"4\" class=\"a-menu\">Entrada de Calificaciones</td>
</tr>

<tr>
<td class=\"titulo-alumno\">Parcial 1</td>
<td class=\"titulo-alumno\">Parcial 2</td>
<td class=\"titulo-alumno\">Parcial 3</td>
<td class=\"titulo-alumno\">Semestral</td>
</tr>





<tr>
<td class=\"titulo-alumno\"><input class=\"checkbox1\" type=\"checkbox\" name=\"evaluacion1\" $ev1></td>
<td class=\"titulo-alumno\"><input class=\"checkbox1\" type=\"checkbox\" name=\"evaluacion2\" $ev2></td>
<td class=\"titulo-alumno\"><input class=\"checkbox1\" type=\"checkbox\" name=\"evaluacion3\" $ev3></td>
<td class=\"titulo-alumno\"><input class=\"checkbox1\" type=\"checkbox\" name=\"evaluacion4\" $ev4></td>
</tr>
<tr>
</table>
<br /><br />
<table>


<tr>
<td colspan=\"4\" class=\"a-menu\">Datos Boletas</td>
</tr>

<tr>
<td class=\"titulo-alumno\">Fecha en Boletas</td>
<td class=\"titulo-alumno\">Nombre Puesto</td>
<td class=\"titulo-alumno\">Título Académico</td>
<td class=\"titulo-alumno\">Usuario</td>
</tr>

<tr>
<td class=\"titulo-alumno\"><input name=\"fechaboletas\" type=\"text\" id=\"datepicker1\"></td>
<td class=\"titulo-alumno\"><input name=\"nombrepuesto\" type=\"text\" size=\"32\" maxlength=\"32\" value=\"$nombre_puesto\">
<td class=\"titulo-alumno\"><input name=\"tituloacademico\" type=\"text\" size=\"32\" maxlength=\"16\" value=\"$titulo_academico\">
<td class=\"titulo-alumno\">[$nombre]<input name=\"user\" type=\"number\" size=\"32\" value=\"$user_id\">
</tr>
<tr>

</table>
<br /><br />
<table>

<td  class=\"titulo-alumno\" colspan=\"4\"><button type=\"submit\" onFocus=\"\" onBlur=\"\" class=\"alumno-salvado\" formaction=\"util/13.php\" formmethod=\"POST\">Enviar</button></td>
</tr>
</table>
</form>";


echo "
<script>
  $( function() {
    $( \"#datepicker1\" ).datepicker();
    $( \"#datepicker1\" ).datepicker( \"option\", \"dateFormat\", \"dd-mm-yy\");
    $( \"#datepicker1\" ).datepicker( \"setDate\", \"$fecha_boletas\" );
    })
 </script>
";


return;
?>