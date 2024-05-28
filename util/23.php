<?php


$unix_date=time();
$human_date = date("d-",$unix_date).getSpanishMonth(date("n",$unix_date)).date("-Y",$unix_date);


echo "
<table class=\"nombre-pagos\">
<tr><td class=\"nombre-pagos\">Gastos del $human_date</td></tr>
</table><hr />";

  echo"

<form action=\"util/24.php\" id=\"form_gasto\" method=\"POST\">
<table class=\"nuevo-pagos\">
<tr><td class=\"nuevo-pagos\"><b>Nuevo Gasto:&nbsp;</b></td>
<td class=\"nuevo-pagos\">Monto:&nbsp;<input autofocus class=\"monto\" required type=\"number\" min=\"0.00\" step=\".01\" id=\"monto\" name=\"monto\"></td>
<td class=\"nuevo-pagos\">Concepto:&nbsp;<input required name=\"descripcion\" type=\"text\" size=\"32\" maxlength=\"255\"></td>
<td class=\"nuevo-pagos\"><button type=\"submit\" form=\"form_gasto\" value=\"Pagar\">Registrar Gasto</button></td>

";

echo"</tr>
</table>";



// Datos generales de los gastos de este usuario en este ciclo de hoy

/*
+-------------+----------------------+------+-----+---------+----------------+
| Field       | Type                 | Null | Key | Default | Extra          |
+-------------+----------------------+------+-----+---------+----------------+
| id          | smallint(5) unsigned | NO   | PRI | NULL    | auto_increment |
| user_id     | tinyint(3) unsigned  | NO   | MUL | NULL    |                |
| ciclo_id    | char(9)              | NO   | MUL | NULL    |                |
| ciclo_tipo  | enum('A','B')        | NO   | MUL | NULL    |                |
| descripcion | varchar(255)         | NO   |     | NULL    |                |
| monto       | decimal(7,2)         | NO   |     | 0.00    |                |
| fecha       | date                 | YES  |     | NULL    |                |
+-------------+----------------------+------+-----+---------+----------------+
7 rows in set (0.01 sec)
*/


// Sólo muestra gastos el usuario actual
$q = "select id, fecha, descripcion, monto, user_id from gasto where user_id = ".$o_user->id." and ciclo_id = '".$o_config->ciclo."' and ciclo_tipo = '".$o_config->tipo."' and fecha = curdate()";

// Muestra gastos de todos los usuarios
$q = "select id, fecha, descripcion, monto, user_id from gasto where ciclo_id = '".$o_config->ciclo."' and ciclo_tipo = '".$o_config->tipo."' and fecha = curdate()";

//echo $q;return;










$o_database->query_rows($q);
$result = $o_database->query_result;

echo "<hr />

<table class=\"detalle-pagos\" id=\"gastos\">";
echo "<tr><td class=\"encabezado-detalle-pagos\">Folio</td><td class=\"encabezado-detalle-pagos\">Usuario</td><td class=\"encabezado-detalle-pagos\">Fecha</td><td class=\"encabezado-detalle-pagos\">Concepto</td><td class=\"encabezado-detalle-pagos\">Monto</td>";
foreach($result as $row)
    echo"<tr><td class=\"pago\">$row[0]</td><td class=\"pago\">$row[4]</td><td class=\"pago\">$row[1]</td><td class=\"pago\">$row[2]</td><td class=\"pago-monto\">$".number_format($row[3],2,".",",")."</td></tr>";

echo "<tr><td class=\"pago-estadocuenta\" colspan=\"5\"><a class=\"materia\" target=\"_self\" href=\"util/27.php?reporte_id=1\">Reporte de Gastos de Hoy</a></td>";


echo "</table>";


?>