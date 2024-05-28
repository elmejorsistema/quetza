<?php


//nix_date=time();
//uman_date = date("d-",$unix_date).getSpanishMonth(date("n",$unix_date)).date("-Y",$unix_date);


$d_hoy = new DateTime();
$hoy = $d_hoy->format("d-m-Y");



// Ingreso Detalle
echo "
<hr /><table class=\"nombre-pagos\">
<tr><td class=\"nombre-pagos\">Reporte de Ingresos Detalle</td></tr>
</table><hr />

<form action=\"util/27.php\" id=\"form_ingreso_detalle\" method=\"GET\">
<input type=\"hidden\" name=\"reporte_id\" value=\"2\">
<table class=\"nuevo-pagos\">
<tr>
<td class=\"nuevo-pagos\">Fecha Inicial:&nbsp;<input type=\"text\" id=\"datepicker1\" name=\"inicio_periodo\"></td>
<td class=\"nuevo-pagos\">Fecha Final:&nbsp;<input type=\"text\"   id=\"datepicker2\" name=\"fin_periodo\"></td>
<td class=\"nuevo-pagos\"><button type=\"submit\" form=\"form_ingreso_detalle\">Enviar</button></td>
</tr>
</table>
</form>";


// Ingreso
echo "
<br /><br />   
<hr /><hr /><table class=\"nombre-pagos\">
<tr><td class=\"nombre-pagos\">Reporte de Ingresos</td></tr>
</table><hr />

<form action=\"util/27.php\" id=\"form_ingreso\" method=\"GET\">
<input type=\"hidden\" name=\"reporte_id\" value=\"4\">
<table class=\"nuevo-pagos\">
<tr>
<td class=\"nuevo-pagos\">Fecha Inicial:&nbsp;<input type=\"text\" id=\"datepicker7\" name=\"inicio_periodo\"></td>
<td class=\"nuevo-pagos\">Fecha Final:&nbsp;<input type=\"text\"   id=\"datepicker8\" name=\"fin_periodo\"></td>
<td class=\"nuevo-pagos\"><button type=\"submit\" form=\"form_ingreso\">Enviar</button></td>
</tr>
</table>
</form>";





// Gastos
echo "
<br /><br />
<hr /><hr /><table class=\"nombre-pagos\">
<tr><td class=\"nombre-pagos\">Reporte de Gastos</td></tr>
</table><hr />

<form action=\"util/27.php\" id=\"form_gasto\" method=\"GET\">
<input type=\"hidden\" name=\"reporte_id\" value=\"1\">
<table class=\"nuevo-pagos\">
<tr>
<td class=\"nuevo-pagos\">Fecha Inicial:&nbsp;<input type=\"text\" id=\"datepicker3\" name=\"inicio_periodo\"></td>
<td class=\"nuevo-pagos\">Fecha Final:&nbsp;<input type=\"text\"   id=\"datepicker4\" name=\"fin_periodo\"></td>
<td class=\"nuevo-pagos\"><button type=\"submit\" form=\"form_gasto\">Enviar</button></td>
</tr>
</table>
</form>";


// Deudores
echo "
<br /><br />
<hr /><hr /><table class=\"nombre-pagos\">
<tr><td class=\"nombre-pagos\">Reporte de Deudores</td></tr>
</table><hr />

<form action=\"util/27.php\" id=\"form_deudor\" method=\"GET\">
<input type=\"hidden\" name=\"reporte_id\" value=\"3\">
<table class=\"nuevo-pagos\">
<tr>
<td class=\"nuevo-pagos\">Fecha Inicial:&nbsp;<input type=\"text\" id=\"datepicker5\" name=\"inicio_periodo\"></td>
<td class=\"nuevo-pagos\">Fecha Final:&nbsp;<input type=\"text\"   id=\"datepicker6\" name=\"fin_periodo\"></td>
<td class=\"nuevo-pagos\"><button type=\"submit\" form=\"form_deudor\">Enviar</button></td>
</tr>
</table>
</form>";

echo "<hr /><table class=\"nuevo-pagos\">
<tr>";

$q = "select p.descripcion, chp.fecha_limite from ciclo_has_pago as chp join pago as p on chp.pago_id = p.id where chp.ciclo_id = '$o_config->ciclo' and chp.ciclo_tipo = '$o_config->tipo' order by chp.fecha_limite, chp.id";
$o_database->query_rows($q);
$result = $o_database->query_result;
while($row = mysql_fetch_row($result))
    echo "<td class=\"nuevo-pagos-c\">$row[0]<br />[ $row[1] ]<td>";
echo "</tr></table><hr />";







// Scripts
echo "
<script>
  $( function() {

    $( \"#datepicker1\" ).datepicker();
    $( \"#datepicker1\" ).datepicker( \"option\", \"dateFormat\", \"dd-mm-yy\");
    $( \"#datepicker1\" ).datepicker( \"setDate\", \"$hoy\" );
    $( \"#datepicker2\" ).datepicker();
    $( \"#datepicker2\" ).datepicker( \"option\", \"dateFormat\", \"dd-mm-yy\");
    $( \"#datepicker2\" ).datepicker( \"setDate\", \"$hoy\" );

    $( \"#datepicker3\" ).datepicker();
    $( \"#datepicker3\" ).datepicker( \"option\", \"dateFormat\", \"dd-mm-yy\");
    $( \"#datepicker3\" ).datepicker( \"setDate\", \"$hoy\" );
    $( \"#datepicker4\" ).datepicker();
    $( \"#datepicker4\" ).datepicker( \"option\", \"dateFormat\", \"dd-mm-yy\");
    $( \"#datepicker4\" ).datepicker( \"setDate\", \"$hoy\" );

    $( \"#datepicker5\" ).datepicker();
    $( \"#datepicker5\" ).datepicker( \"option\", \"dateFormat\", \"dd-mm-yy\");
    $( \"#datepicker5\" ).datepicker( \"setDate\", \"$hoy\" );
    $( \"#datepicker6\" ).datepicker();
    $( \"#datepicker6\" ).datepicker( \"option\", \"dateFormat\", \"dd-mm-yy\");
    $( \"#datepicker6\" ).datepicker( \"setDate\", \"$hoy\" );

    $( \"#datepicker7\" ).datepicker();
    $( \"#datepicker7\" ).datepicker( \"option\", \"dateFormat\", \"dd-mm-yy\");
    $( \"#datepicker7\" ).datepicker( \"setDate\", \"$hoy\" );
    $( \"#datepicker8\" ).datepicker();
    $( \"#datepicker8\" ).datepicker( \"option\", \"dateFormat\", \"dd-mm-yy\");
    $( \"#datepicker8\" ).datepicker( \"setDate\", \"$hoy\" );



  } );
  </script>



";

?>
