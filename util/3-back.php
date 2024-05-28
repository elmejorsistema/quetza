<?php

// Esto es para arir alguno de los overlay
//////////////////////////////////////////


echo "
<table class=\"a-contenido\">";

echo"
<tr>
<td class=\"a-menu-contenido\">


<form id=\"myform\">
<table class=\"modal-captura-venta\">

<input type=\"hidden\" name=\"cs1\" value=\"$o_config->control_structure_id1\">
<input type=\"hidden\" name=\"cs2\" value=\"$o_config->control_structure_id2\">


<tr>
<td class=\"modal-titulo\">Corte de Caja</td>
</tr>

<tr>
<td class=\"modal-label\">Fecha</td>
</tr>
<tr>
<td class=\"modal-input\">
<input class=\"a-input-fecha\" type=\"date\" id=\"mydate\" required=\"required\" title=\"Fecha del corte de caja\" data-message=\"Fecha del corte de caja\">
</td>
</tr>
<tr>
<td class=\"modal-label\"><button type=\"submit\" id=\"corte\" onclick=\"\" class=\"modal-boton-aceptar\" value=\"Aceptar\">Aceptar</button></td>
</tr>
</table>
</form>

</td>
</tr>
</table>

<script>

$(\"#yform\").validator({
    singleError: false,
    messageClass: 'error-time',
    offset: [348, 708.5],
    position: \"bottom center\"
});

$(\"#mydate\").tooltip();


$.tools.dateinput.localize(\"es\", {
  months: 'Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Septiembre,Octubre,Noviembre,Diciembre',
  shortMonths:  'Ene,Feb,Mar,Abr,May,Jun,Jul,Ago,Sep,Oct,Nov,Dic',
  days:         'Domingo,Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
  shortDays:    'Dom,Lun,Mar,Mié,Jue,Vie,Sáb'
 });

$(\":date\").dateinput(
{
 format: 'yyyy-mm-dd',
 lang: 'es'
});

$(\"#corte\").bind('click', function(e) {
  var fecha = $(\"#mydate\").val();

  $.ajax({
    url: \"./util/ImprimeCorte.php\",
    data: \"fecha=\"+fecha,
    type: 'POST',
    success: function (resp) {
        //alert(resp);
    },
    error: function(e) {
        //alert('Error: '+e);
    }  
});

  //alert(fecha);
});




</script>



";
?>