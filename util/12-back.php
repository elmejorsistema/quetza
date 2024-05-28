<?php

// Esto es para arir alguno de los overlay
//////////////////////////////////////////

  /*
if(!empty($_GET['ovly']))
  $ovly = $_GET['ovly'];
else
  $ovly = null;


switch($ovly)
  {
  case "nuevo":
    $cargar_nuevo   = "load : true";
    $cargar_buscar  = "load : false";
    $cargar_generar = "load : false";
    break;
  case "buscar":
    $cargar_buscar  = "load : true";
    $cargar_nuevo   = "load : false";
    $cargar_generar = "load : false";
    break;
  case "generar":
    $cargar_buscar  = "load : false";
    $cargar_nuevo   = "load : false";
    $cargar_generar = "load : true";
    break;
  default:
    $cargar_buscar  = "load : false";
    $cargar_nuevo   = "load : false";
    $cargar_generar = "load : false";
    break;
    }

  */

// Qué lugar ocupa este menú en la pantalla
///////////////////////////////////////////
$a_lugar = get_lugar_menu($o_config->control_structure_id1, $o_config->control_structure_id2, $o_user->id, $o_database);

$i = 1;
echo "
<table class=\"a-contenido\">";
while($i <= $a_lugar[0])
{
  if($i == $a_lugar[1])
    {
    // Estos son los menús activos
    //////////////////////////////
echo"
<tr>
<td class=\"a-menu-contenido-2\"><div class=\"a-opcion\" rel=\"#ventas\">Ventas</div></td>
<td class=\"a-menu-contenido-2\"><div class=\"a-opcion\" rel=\"#compras\">Compras</div></td>
<td class=\"a-menu-contenido-2\"><div class=\"a-opcion\" rel=\"\">&nbsp;</div></td>
</tr>";
    }
else
  {
    // Estos son los menús vacíos
    /////////////////////////////
echo"
<tr>
<td class=\"a-menu-contenido-vacio\" colspan=\"2\"><div class=\"a-opcion-vacio\">&nbsp;</div></td>
</tr>";
  }

  $i++;
}

// Se define el contenido HTML y Javascript
// Es esta parte que hay que ir modificando
// según los requerimientos.
///////////////////////////////////////////
echo "
</table>
";

echo"
<div class=\"modal\" id=\"ventas\">
<form id=\"myform_v\">
<table class=\"modal-captura\">


<tr>
<td class=\"modal-titulo\" colspan=\"4\">Generar Reporte de Ventas</td>
</tr>


<tr>
<td class=\"modal-label-2\">Fecha Inicial</td>
<td class=\"modal-input-2\"><input class=\"a-input-fecha\" type=\"date\" id=\"inicial_v\" name=\"inicial_v\" required=\"required\" title=\"Fecha inicial.\" data-message=\"Fecha inicial.\">
  </td>

<td class=\"modal-label-2\">Fecha Final</td>
<td class=\"modal-input-2\"><input class=\"a-input-fecha\" type=\"date\" id=\"final_v\" name=\"final_v\" required=\"required\" title=\"Fecha final.\" data-message=\"Fecha final.\">
  </td>
</tr>
</table>

<table class=\"modal-captura\">
<tr>
<td class=\"modal-label\"><button type=\"reset\"  onclick=\"limpiaOverlay()\" class=\"modal-boton\">Limpiar</button></td>
<td class=\"modal-label\"><button id=\"genera\" type=\"submit\" formaction=\"util/GeneraVentas.php\" formmethod=\"POST\" class=\"modal-boton-aceptar\">Generar</button></td>
<td class=\"modal-label\"><button type=\"button\" onclick=\"cierraOverlay()\" class=\"modal-boton-cerrar-ol\">Cancelar</button></td>
</tr>
</table>
</form>
</div>





<div class=\"modal\" id=\"compras\">
<form id=\"myform_c\">
<table class=\"modal-captura\">


<tr>
<td class=\"modal-titulo\" colspan=\"4\">Generar Reporte de Compras</td>
</tr>


<tr>
<td class=\"modal-label-2\">Fecha Inicial</td>
<td class=\"modal-input-2\"><input class=\"a-input-fecha\" type=\"date\" id=\"inicial_c\" name=\"inicial_c\" required=\"required\" title=\"Fecha inicial.\" data-message=\"Fecha inicial.\">
  </td>

<td class=\"modal-label-2\">Fecha Final</td>
<td class=\"modal-input-2\"><input class=\"a-input-fecha\" type=\"date\" id=\"final_c\" name=\"final_c\" required=\"required\" title=\"Fecha final.\" data-message=\"Fecha final.\">
  </td>
</tr>
</table>

<table class=\"modal-captura\">
<tr>
<td class=\"modal-label\"><button type=\"reset\"  onclick=\"limpiaOverlay()\" class=\"modal-boton\">Limpiar</button></td>
<td class=\"modal-label\"><button id=\"genera\" type=\"submit\" formaction=\"util/GeneraCompras.php\" formmethod=\"POST\" class=\"modal-boton-aceptar\">Generar</button></td>
<td class=\"modal-label\"><button type=\"button\" onclick=\"cierraOverlay()\" class=\"modal-boton-cerrar-ol\">Cancelar</button></td>
</tr>
</table>
</form>
</div>
<script>




$(document).ready(function() {



$('[id^=myform]').validator({
    singleError: false,
    position: \"bottom center\"
});

$('[id^=fecha]').tooltip();


$(\"div[rel=#ventas]\").overlay({
  // disable this for modal dialog-type of overlays
     closeOnClick: false,
     closeOnEsc:   false,
     top:   110,
     left:  215,
     fixed: false,
     api:   true,
     onLoad: function() {
       // $(\"#nombre\").focus(); 
     },
     onBeforeClose: function() {
        $(\"#myform_v\").data(\"validator\").reset();
     },
     mask: {
       color: '#ffffff',
       loadSpeed: 200,
       opacity: 0.6 },
     });


$(\"div[rel=#compras]\").overlay({
  // disable this for modal dialog-type of overlays
     closeOnClick: false,
     closeOnEsc:   false,
     top:   110,
     left:  215,
     fixed: false,
     api:   true,
     onLoad: function() {
        //$(\"#nombre_b\").focus(); 
     },
     onBeforeClose: function() {
        $(\"#myform_c\").data(\"validator\").reset();
     },
     mask: {
       color: '#ffffff',
       loadSpeed: 200,
       opacity: 0.6 },
     });




 });

$.tools.dateinput.localize(\"es\", {
  months: 'Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Septiembre,Octubre,Noviembre,Diciembre',
  shortMonths:  'Ene,Feb,Mar,Abr,May,Jun,Jul,Ago,Sep,Oct,Nov,Dic',
  days:         'Domingo,Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
  shortDays:    'Dom,Lun,Mar,Mié,Jue,Vie,Sáb'
 });

$(\":date\").dateinput(
{
 format: 'yyyy-mm-dd',
 lang: 'es',
 trigger: true
});

$(\":date:eq(0)\").data(\"dateinput\").change(function() {
	// we use it's value for the seconds input min option
	$(\":date:eq(1)\").data(\"dateinput\").setMin(this.getValue(), true);
});

$(\":date:eq(2)\").data(\"dateinput\").change(function() {
	// we use it's value for the seconds input min option
	$(\":date:eq(3)\").data(\"dateinput\").setMin(this.getValue(), true);
});





function cierraOverlay()
{
 $(\"[id^=inicial]\").val('');
 $(\"[id^=final]\").val('');
 
 $(\"div[rel]\").each(function() {
    $(this).overlay().close();
  });
}


function limpiaOverlay()
{
 $(\"[id^=inicial]\").val('');
 $(\"[id^=final]\").val('');
}

</script>
";
?>