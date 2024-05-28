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

// Obtiene los datos de la alumna/o

// Datos generales del ciclo


$q = "select a.nombre, a.1_apellido, a.2_apellido, a.grupo_id, a.estatus from alumno as a where id = $o_config->alumno_id";

$o_database->query_fetch_row($q);
$result = $o_database->query_row;

echo "
<table class=\"nombre-pagos\">
<tr><td class=\"nombre-pagos\">$result[0] $result[1] $result[2] [Grupo $result[3]]</td></tr>
<tr><td class=\"nombre-pagos\"><span class=\"$result[4]\">$result[4]</span></td></tr>
</table><hr />";

/*
if($result[4] != "Activo")
{
    echo"    
<table class=\"mensje-pagos\">
<tr><td class=\"estatus-pagos\">Tiene estatus $result[4]</td></tr>
</table>";
    return;
}
*/



// Para visualizar o no $todos_los_ciclos

echo "
<form action=\"util/28.php\" id=\"cambia_todos_ciclos\" method=\"POST\">
<input type=\"hidden\" name=\"menu\" value=\"17\">
<table>
<tr><td class=\"nuevo-pagos\"><b>Mostrar todos los ciclos:&nbsp;</b></td><td class=\"nuevo-pagos\"><input onchange=\"cambia_todos_pagos();\" type=\"checkbox\"";

if($o_config->todos_los_ciclos)
    echo " checked";

echo "></tr></table></form>";

echo"

<form action=\"util/18.php\" id=\"form_pago\" method=\"POST\">
<table class=\"nuevo-pagos\">
<tr><td class=\"nuevo-pagos\"><b>Nuevo Pago:&nbsp;</b></td>
<td class=\"nuevo-pagos\">";


//$q = "select chp.id, chp.monto, p.descripcion from ciclo_has_pago as chp join pago as p on p.id = chp.pago_id where chp.ciclo_id = '$o_config->ciclo' and  chp.ciclo_tipo = '$o_config->tipo' order by chp.fecha_limite";

/*
if($o_config->todos_los_ciclos) // Todos los ciclos
    $q = "select chp.id, chp.monto, concat(concat(concat(concat(concat(p.descripcion, ' ['), chp.ciclo_id), ' ('), chp.ciclo_tipo), ')]') from ciclo_has_pago as chp join pago as p on p.id = chp.pago_id order by chp.fecha_limite desc";
else
    $q = "select chp.id, chp.monto, concat(concat(concat(concat(concat(p.descripcion, ' ['), chp.ciclo_id), ' ('), chp.ciclo_tipo), ')]') from ciclo_has_pago as chp join pago as p on p.id = chp.pago_id where chp.ciclo_id = '$o_config->ciclo' and  chp.ciclo_tipo = '$o_config->tipo' order by chp.fecha_limite";
*/

if($o_config->todos_los_ciclos) // Todos los ciclos
    $q = "select chp.id, chp.monto, concat(concat(concat(concat(concat(p.descripcion, ' ['), chp.ciclo_id), ' ('), chp.ciclo_tipo), ')]'), chp.ciclo_id, chp.ciclo_tipo from ciclo_has_pago as chp join pago as p on p.id = chp.pago_id order by chp.ciclo_id desc, chp.ciclo_tipo desc, chp.fecha_limite, chp.pago_id";
else
    $q = "select chp.id, chp.monto, concat(concat(concat(concat(concat(p.descripcion, ' ['), chp.ciclo_id), ' ('), chp.ciclo_tipo), ')]'), chp.ciclo_id, chp.ciclo_tipo from ciclo_has_pago as chp join pago as p on p.id = chp.pago_id where chp.ciclo_id = '$o_config->ciclo' and  chp.ciclo_tipo = '$o_config->tipo' order by chp.ciclo_id desc, chp.ciclo_tipo desc, chp.fecha_limite, chp.pago_id";


//ciclo_id desc, ciclo_tipo desc, fecha_limite asc, pago_id


//echo $q;

$a_names=array();
$a_names[]="id";
$a_names[]="monto";

create_select_json($q, $a_names, "chpago_id", "", "onchange='selected_pago();'", $o_database, "a-select");

echo "</td>
<td class=\"nuevo-pagos\">Monto:&nbsp;<input class=\"monto\" required type=\"number\" min=\"0.00\" step=\".01\" id=\"monto\" name=\"monto\"></td>
<td class=\"nuevo-pagos\">Comentario:&nbsp;<input name=\"comentario\" type=\"text\" size=\"32\" maxlength=\"64\"></td>
<td class=\"nuevo-pagos\"><button type=\"submit\" form=\"form_pago\" value=\"Pagar\">Pagar</button></td>

";

echo"</tr>
</table></form>";

echo "
<form action=\"util/20.php\" id=\"nuevo_recibo\" method=\"GET\">
<input type=\"hidden\" name=\"reporte_id\" value=\"3\">";

if($o_config->todos_los_ciclos) // Todos los ciclos
{
    $q = "select ahchp.id, chp.id, p.descripcion, ahchp.descripcion, ahchp.monto, ahchp.fecha, ahchp.user_id, chp.ciclo_id, chp.ciclo_tipo from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id where ahchp.alumno_id =".$o_config->alumno_id." order by  ahchp.fecha desc" ;

    
    $o_database->query_rows($q);
    $result = $o_database->query_result;

    echo "<hr />
<table class=\"detalle-pagos\" id=\"pagos\">";
echo "<tr><td class=\"encabezado-detalle-pagos\">Folio</td><td class=\"encabezado-detalle-pagos\">Usuario</td><td class=\"encabezado-detalle-pagos\">Ciclo</td><td class=\"encabezado-detalle-pagos\">Semestre</td><td class=\"encabezado-detalle-pagos\">Fecha</td><td class=\"encabezado-detalle-pagos\">Concepto</td><td class=\"encabezado-detalle-pagos\">Monto</td><td class=\"encabezado-detalle-pagos\">Comentario</td><td class=\"encabezado-detalle-pagos\">Recibo</td><td class=\"encabezado-detalle-pagos\">Acciones</td></tr>";

$pagos_sin_recibo = false;
while($row = mysql_fetch_row($result))
{

    $q1 = "select recibo_id from recibo_has_pago where ahc_has_pago_id = $row[0]";
    $o_database->query_fetch_field($q1);
    $result1 = $o_database->query_field;
    if($result1)
    {
        $imprime = "<td class=\"pago-checkbox\"><a class=\"materia\" target=\"_self\" href=\"util/20.php?pago_id=".$result1."&reporte_id=1\">Imprimir [$result1]</a></td>";
    }
    else
    {
        $pagos_sin_recibo = true;
        $imprime = "<td class=\"pago-checkbox\"><input type=\"checkbox\" name=\"recibo[]\" value=\"$row[0]\"></td>";
    }

    $acciones = ($row[6] == $o_user->id) ? "<td class=\"pago-checkbox\"><a class=\"materia\" target=\"_self\" href=\"util/30.php?folio=".$row[0]."&menu=31\"><img src=\"img/Edita.png\"></a><a onclick=\"return confirm('¿Cancelar folio $row[0]?')\" class=\"materia\" target=\"_self\" href=\"util/29.php?folio=".$row[0]."&menu=17\"><img src=\"img/Cancela.png\"></a></td>" : "<td class=\"pago\"></td>";
            
    
     
    //echo"<tr><td class=\"pago\">$row[0]</td><td class=\"pago\">$row[6]</td><td class=\"pago\">$row[7]</td><td class=\"pago\">$row[8]</td><td class=\"pago\">$row[5]</td><td class=\"pago\">$row[2]</td><td class=\"pago-monto\">$".number_format($row[4],2,".",",")."</td><td class=\"pago\">$row[3]</td><td class=\"pago\"><a class=\"materia\" target=\"_self\" href=\"util/20.php?pago_id=".$row[0]."&reporte_id=1\">Imprimir</a></td></tr>";


    echo"<tr><td class=\"pago\">$row[0]</td><td class=\"pago\">$row[6]</td><td class=\"pago\">$row[7]</td><td class=\"pago\">$row[8]</td><td class=\"pago\">$row[5]</td><td class=\"pago\">$row[2]</td><td class=\"pago-monto\">$".number_format($row[4],2,".",",")."</td><td class=\"pago\">$row[3]</td>$imprime$acciones</tr>";

}
if($pagos_sin_recibo)
    echo "<tr><td colspan=\"8\" class=\"pago\"></td><td class=\"pago-checkbox\"><button type=\"submit\" form=\"nuevo_recibo\" value=\"Agrupar en Nuevo Recibo\">Agrupar en Nuevo Recibo</button></td><td class=\"pago\"></td></tr>";

echo "<tr><td class=\"pago-estadocuenta\" colspan=\"10\"><a class=\"materia\" target=\"_self\" href=\"util/20.php?pago_id=".$row[0]."&reporte_id=2\">Estado de Cuenta</a></td></tr>";


echo "</table></form>";
    
}
///////////////////////////////////
else // Solamente el ciclo actual
//////////////////////////////////    
{

    $q = "select ahchp.id, chp.id, p.descripcion, ahchp.descripcion, ahchp.monto, ahchp.fecha, ahchp.user_id from alumno_has_ciclo_has_pago as ahchp join ciclo_has_pago as chp on ahchp.ciclo_has_pago_id = chp.id join pago as p on chp.pago_id = p.id where ahchp.alumno_id =".$o_config->alumno_id." and chp.ciclo_id ='".$o_config->ciclo."' and chp.ciclo_tipo='".$o_config->tipo."' order by ahchp.fecha desc" ;

    
    $o_database->query_rows($q);
    $result = $o_database->query_result;

    echo "<hr />

<table class=\"detalle-pagos\" id=\"pagos\">";
echo "<tr><td class=\"encabezado-detalle-pagos\">Folio</td><td class=\"encabezado-detalle-pagos\">Usuario</td><td class=\"encabezado-detalle-pagos\">Fecha</td><td class=\"encabezado-detalle-pagos\">Concepto</td><td class=\"encabezado-detalle-pagos\">Monto</td><td class=\"encabezado-detalle-pagos\">Comentario</td><td class=\"encabezado-detalle-pagos\">Recibo</td><td class=\"encabezado-detalle-pagos\">Acciones</td></tr>";

$pagos_sin_recibo = false;
while($row = mysql_fetch_row($result))
{

    $q1 = "select recibo_id from recibo_has_pago where ahc_has_pago_id = $row[0]";
    $o_database->query_fetch_field($q1);
    $result1 = $o_database->query_field;
    if($result1)
    {
        $imprime = "<td class=\"pago-checkbox\"><a class=\"materia\" target=\"_self\" href=\"util/20.php?pago_id=".$result1."&reporte_id=1\">Imprimir [$result1]</a></td>";
    }
    else
    {
        $pagos_sin_recibo = true;
        $imprime = "<td class=\"pago-checkbox\"><input type=\"checkbox\" name=\"recibo[]\" value=\"$row[0]\"></td>";
    }


    $acciones = ($row[6] == $o_user->id) ? "<td class=\"pago-checkbox\"><a class=\"materia\" target=\"_self\" href=\"util/30.php?folio=".$row[0]."&menu=31\"><img src=\"img/Edita.png\"></a><a onclick=\"return confirm('¿Cancelar folio $row[0]?')\" class=\"materia\" target=\"_self\" href=\"util/29.php?folio=".$row[0]."&menu=17\"><img src=\"img/Cancela.png\"></a></td>" : "<td class=\"pago\"></td>";
            
    
        
    // echo"<tr><td class=\"pago\">$row[0]</td><td class=\"pago\">$row[6]</td><td class=\"pago\">$row[5]</td><td class=\"pago\">$row[2]</td><td class=\"pago-monto\">$".number_format($row[4],2,".",",")."</td><td class=\"pago\">$row[3]</td><td class=\"pago\"><a class=\"materia\" target=\"_self\" href=\"util/20.php?pago_id=".$row[0]."&reporte_id=1\">Imprimir</a></td></tr>";

echo"<tr><td class=\"pago\">$row[0]</td><td class=\"pago\">$row[6]</td><td class=\"pago\">$row[5]</td><td class=\"pago\">$row[2]</td><td class=\"pago-monto\">$".number_format($row[4],2,".",",")."</td><td class=\"pago\">$row[3]</td>$imprime$acciones</tr>";

}
if($pagos_sin_recibo)
    echo "<tr><td colspan=\"6\" class=\"pago\"></td><td class=\"pago-checkbox\"><button type=\"submit\" form=\"nuevo_recibo\" value=\"Agrupar en Nuevo Recibo\">Agrupar en Nuevo Recibo</button></td><td class=\"pago\"></td></tr>";


echo "<tr><td class=\"pago-estadocuenta\" colspan=\"8\"><a class=\"materia\" target=\"_self\" href=\"util/20.php?pago_id=".$row[0]."&reporte_id=2\">Estado de Cuenta</a></td></tr>";


echo "</table></form>";
}


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