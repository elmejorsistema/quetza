<?php

/*
   Si no hay una toma activa muestra la forma para
   especifacr una toma.
*/
if(empty($o_config->alumno_id))
  {
    $m = 17;
    especifica_alumno($m);
    return;
  }


/*
 Necesita el folio
*/
if($o_config->folio == null)
     {
    echo "<script>alert(\"Error: variable nula\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
     }

$folio = $o_config->folio;

// Obtiene los datos de la alumna/o

// Datos generales del ciclo y se verifica que el pago sea del alumno activo


$q = "select a.nombre, a.1_apellido, a.2_apellido, a.grupo_id, a.estatus from alumno as a join alumno_has_ciclo_has_pago as ahchp on ahchp.alumno_id = a.id and ahchp.id = $folio where a.id = $o_config->alumno_id";

$o_database->query_fetch_row($q);
$result = $o_database->query_row;

if(!$result)
  {
    echo "<script>alert(\"Error: variables no coincidentes\")</script>";
    echo "<script>history.go(-1)</script>>";
    return;
     }   


// Datos del folio



$q = "select ciclo_has_pago_id, monto, descripcion from alumno_has_ciclo_has_pago where id = $folio";
$o_database->query_fetch_row($q);
$result_folio = $o_database->query_row;




echo "
<table class=\"nombre-pagos\">
<tr><td class=\"nombre-pagos\">$result[0] $result[1] $result[2] [Grupo $result[3]]</td></tr>
<tr><td class=\"nombre-pagos\"><span class=\"$result[4]\">$result[4]</span></td></tr>
</table><hr />";




echo"
<form action=\"index.php?cs1=16&cs2=17\" id=\"form_cancela\" method=\"POST\"></form>
<form action=\"util/32.php\" id=\"form_pago\" method=\"POST\">
<input type=\"hidden\" name=\"menu\" value=\"17\">
<input type=\"hidden\" name=\"folio\" value=\"$folio\">
<table class=\"nuevo-pagos\">
<tr><td class=\"nuevo-pagos\"><b>Edita Pago $folio:&nbsp;</b></td>
<td class=\"nuevo-pagos\">";


$q = "select chp.id, chp.monto, concat(concat(concat(concat(concat(p.descripcion, ' ['), chp.ciclo_id), ' ('), chp.ciclo_tipo), ')]') from ciclo_has_pago as chp join pago as p on p.id = chp.pago_id order by chp.ciclo_id desc, chp.ciclo_tipo desc, chp.fecha_limite, chp.pago_id";

//echo $q;return;

$a_names=array();
$a_names[]="id";
$a_names[]="monto";

create_select_json($q, $a_names, "chpago_id", "$result_folio[0]", "onchange='selected_pago();'", $o_database, "a-select");

echo "</td>
<td class=\"nuevo-pagos\">Monto:&nbsp;<input class=\"monto\" required type=\"number\" min=\"0.00\" step=\".01\" id=\"monto\" name=\"monto\" value=\"$result_folio[1]\"></td>
<td class=\"nuevo-pagos\">Comentario:&nbsp;<input name=\"comentario\" type=\"text\" size=\"32\" maxlength=\"64\" value=\"$result_folio[2]\"></td>
<td class=\"nuevo-pagos\"><button type=\"submit\" form=\"form_pago\" value=\"Pagar\">Modificar Pago</button><button class=\"cancelar\" form=\"form_cancela\" type=\"submit\" value=\"Cancelar\">Cancelar</button></td>";

echo"</tr>
</table></form>";


echo"
<script>
function selected_pago()
{
   var pago       = document.getElementById('chpago_id');
   var json_field = pago.options[pago.selectedIndex].value;
   var json       = JSON.parse(json_field);

   document.getElementById('monto').value = json['monto'];
}

function cambia_todos_pagos(m)
{
  document.getElementById('cambia_todos_ciclos').submit(); 
}

</script>
";


?>