<?php

// Esto es para arir alguno de los overlay
//////////////////////////////////////////
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
<td class=\"a-menu-contenido-2\"><div class=\"a-opcion\" rel=\"#nuevo\">Nuevo</div></td>
<td class=\"a-menu-contenido-2\"><div class=\"a-opcion\" rel=\"#buscar\">Buscar</div></td>
<td class=\"a-menu-contenido-2\"><div class=\"a-opcion\" rel=\"#generar\">Generar</div></td>
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
<div class=\"modal\" id=\"nuevo\">
<form id=\"myform\">
<table class=\"modal-captura\">

<input type=\"hidden\" name=\"cs1\" value=\"$o_config->control_structure_id1\">
<input type=\"hidden\" name=\"cs2\" value=\"$o_config->control_structure_id2\">


<tr>
<td class=\"modal-titulo\" colspan=\"4\">Nueva entrada de Gasto Fijo</td>
</tr>

<tr>
<td class=\"modal-label-2\">Concepto</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"comment\" id=\"comment\" type=\"text\" required=\"required\" maxlength\"48\" title=\"Motivo del pago.\" data-message=\"Motivo del pago.\"></td>
<td class=\"modal-label-2\">Fecha</td>
<td class=\"modal-input-2\"><input class=\"a-input-fecha\" type=\"date\" id=\"fecha\" name=\"fecha\" required=\"required\" title=\"Fecha.\" data-message=\"Fecha.\">
  </td>
</tr>


<tr>
<td class=\"modal-label-2\">Costo</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"total_c_tax\" id=\"total_c_tax\" type=\"number\" required=\"required\" min=\"0.1\" title=\"Costo Neto.\" data-message=\"Costo Neto.\"></td>

<td class=\"modal-label-2\">Impuesto (%)</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\"  name=\"tax\" id=\"tax\" type=\"number\" min=\"0.0\" title=\"Impuesto en porcentaje.\" data-message=\"Impuesto en porcentaje.\"></td>
</tr>




<tr>
<td class=\"modal-label-2\" colspan=\"2\"><button type=\"submit\"   formaction=\"util/NuevoGasto.php\" formmethod=\"POST\" class=\"modal-boton-aceptar\">Aceptar</button></td>
<td class=\"modal-label-2\" colspan=\"2\"><button type=\"button\" onclick=\"cierraOverlay()\" class=\"modal-boton-cerrar-ol\">Cancelar</button></td>
</tr>
</table>
</form>
</div>

<div class=\"modal\" id=\"buscar\">
<form id=\"myform_b\">
<table class=\"modal-captura\">

<input type=\"hidden\" name=\"cs1_b\" value=\"$o_config->control_structure_id1\">
<input type=\"hidden\" name=\"cs2_b\" value=\"$o_config->control_structure_id2\">
<input type=\"hidden\" name=\"id_b\"  id=\"id_b\">


<tr>
<td class=\"modal-titulo\" colspan=\"4\">Buscar entrada de Gasto Fijo</td>
</tr>


<tr>
<td class=\"modal-label-ac-2\">Concepto</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"comment_b\" id=\"comment_b\" type=\"text\" required=\"required\" maxlength\"48\" title=\"Motivo del pago.\" data-message=\"Motivo del pago.\"></td>
<td class=\"modal-label-2\">Fecha</td>
<td class=\"modal-input-2\"><input disabled class=\"a-input-fecha\" type=\"date\" id=\"fecha_b\" name=\"fecha_b\" required=\"required\" title=\"Fecha.\" data-message=\"Fecha.\">
  </td>
</tr>


<tr>
<td class=\"modal-label-2\">Costo</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" disabled name=\"total_c_tax_b\" id=\"total_c_tax_b\" type=\"number\" required=\"required\" min=\"0.1\" title=\"Costo Neto.\" data-message=\"Costo Neto.\"></td>

<td class=\"modal-label-2\">Impuesto (%)</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" disabled  name=\"tax_b\" id=\"tax_b\" type=\"number\" min=\"0.0\" title=\"Impuesto en porcentaje.\" data-message=\"Impuesto en porcentaje.\"></td>
</tr>

<tr>
<td class=\"modal-label\"><button type=\"reset\"  onclick=\"limpiaOverlay()\" class=\"modal-boton\">Limpiar</button></td>
<td class=\"modal-label\"><button id=\"edita_b\" disabled type=\"submit\" formaction=\"util/EditaGasto.php\" formmethod=\"POST\" class=\"modal-boton-editar\">Guardar</button></td>
<td class=\"modal-label\"><button type=\"button\" onclick=\"cierraOverlay()\" class=\"modal-boton-cerrar-ol\">Cancelar</button></td>
<td class=\"modal-label\"><button id=\"borra\" type=\"button\" disabled onclick=\"cancelaGasto()\" class=\"modal-boton-cancelar\">Borrar</button></td>
</tr>
</table>
</form>
</div>

<div class=\"modal\" id=\"generar\">
<form id=\"myform_g\">
<table class=\"modal-captura\">


<tr>
<td class=\"modal-titulo\" colspan=\"4\">Generar Reporte de Gastos Fijos</td>
</tr>


<tr>
<td class=\"modal-label-2\">Fecha Inicial</td>
<td class=\"modal-input-2\"><input class=\"a-input-fecha\" type=\"date\" id=\"inicial_g\" name=\"inicial_g\" required=\"required\" title=\"Fecha inicial.\" data-message=\"Fecha inicial.\">
  </td>

<td class=\"modal-label-2\">Fecha Final</td>
<td class=\"modal-input-2\"><input class=\"a-input-fecha\" type=\"date\" id=\"final_g\" name=\"final_g\"   required=\"required\" title=\"Fecha final.\" data-message=\"Fecha final.\">
  </td>
</tr>
</table>

<table class=\"modal-captura\">
<tr>
<td class=\"modal-label\"><button type=\"reset\"  onclick=\"limpiaOverlay()\" class=\"modal-boton\">Limpiar</button></td>
<td class=\"modal-label\"><button id=\"genera\" type=\"submit\" formaction=\"util/GeneraGastos.php\" formmethod=\"POST\" class=\"modal-boton-aceptar\">Generar</button></td>
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


$('[id^=total_c_tax]').tooltip();
$('[id^=tax]').tooltip();
$('[id^=fecha]').tooltip();
$('[id^=comment]').tooltip();


$(\"div[rel=#nuevo]\").overlay({
  // disable this for modal dialog-type of overlays
     closeOnClick: false,
     closeOnEsc:   false,
     top:   110,
     left:  215,
     fixed: false,
     api:   true,
     onLoad: function() {
        $(\"#comment\").focus(); 
     },
     onBeforeClose: function() {
        $(\"#myform\").data(\"validator\").reset();
     },
     mask: {
       color: '#ffffff',
       loadSpeed: 200,
       opacity: 0.6 },
     $cargar_nuevo
     });


$(\"div[rel=#buscar]\").overlay({
  // disable this for modal dialog-type of overlays
     closeOnClick: false,
     closeOnEsc:   false,
     top:   110,
     left:  215,
     fixed: false,
     api:   true,
     onLoad: function() {
        $(\"#comment_b\").focus(); 
     },
     onBeforeClose: function() {
        $(\"#myform_b\").data(\"validator\").reset();
     },
     mask: {
       color: '#ffffff',
       loadSpeed: 200,
       opacity: 0.6 },
     $cargar_buscar
     });


$(\"div[rel=#generar]\").overlay({
  // disable this for modal dialog-type of overlays
     closeOnClick: false,
     closeOnEsc:   false,
     top:   110,
     left:  215,
     fixed: false,
     api:   true,
     onLoad: function() {
        $(\"#inicial_g\").focus(); 
     },
     onBeforeClose: function() {
        $(\"#myform_g\").data(\"validator\").reset();
     },
     mask: {
       color: '#ffffff',
       loadSpeed: 200,
       opacity: 0.6 },
     $cargar_generar
     });
 });


$(\"#comment_b\").autocomplete({
         source:  function (a,b)
                 {
                    id = $(\"#comment_b\").val();
                    $.post(\"util/json_gasto.php\",
                           {\"gasto\" : id},
                           function(data){
                               if(data)
                                  {
                                    var suggestions = []; 
                                    $.each(data, function(i, val)
                                     {                           
                                       suggestions.push(val);  
                                       return b(suggestions);
                                     })
                                   }
                                },
                           \"json\");
                   return b(\"\")
                 },

         minLength: 2,
 
         select: function(event, gasto)
                  {
                   $(\"#total_c_tax_b\").prop('disabled', false);
                   $(\"#tax_b\").prop('disabled', false);
                   $(\"#fecha_b\").prop('disabled', false);
                   $(\"#comment_b\").prop('disabled', true);
                   $(\"#borra\").prop('disabled', false);
                   $(\"#edita_b\").prop('disabled', false);
                   $(\"#total_c_tax_b\").val(gasto.item.total_c_tax);
                   $(\"#tax_b\").val(gasto.item.tax);
                   $(\"#fecha_b\").val(gasto.item.date);
                   $(\"#id_b\").val(gasto.item.id);
                  },
        
        open:   function(event, ui) {
            $(\".ui-autocomplete\").css(\"z-index\", 1000000);
           }

})




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


$(\":date:eq(2)\").data(\"dateinput\").change(function() {
	// we use it's value for the seconds input min option
	$(\":date:eq(3)\").data(\"dateinput\").setMin(this.getValue(), true);
});



function limpiaOverlay()
{
 $(\"#total_c_tax_b\").prop('disabled', true);
 $(\"#tax_b\").prop('disabled', true);
 $(\"#fecha_b\").prop('disabled', true);
 $(\"#comment_b\").prop('disabled', false);
 $(\"#borra\").prop('disabled', true);
 $(\"#edita_b\").prop('disabled', true);
 $(\"#id_b\").val(\"\");
 $(\"#total_c_tax_b\").val(\"\");
 $(\"#tax_b\").val(\"\");
 $(\"#comment_b\").val(\"\");
 $(\"#fecha_b\").val(\"\");
}


function cierraOverlay()
{
 //alert('hola');
 $(\"#total_c_tax_b\").prop('disabled', true);
 $(\"#tax_b\").prop('disabled', true);
 $(\"#fecha_b\").prop('disabled', true);
 $(\"#comment_b\").prop('disabled', false);
 $(\"#borra\").prop('disabled', true);
 $(\"#edita_b\").prop('disabled', true);
 $(\"#id_b\").val(\"\");
 $(\"#total_c_tax_b\").val(\"\");
 $(\"#tax_b\").val(\"\");
 $(\"#comment_b\").val(\"\");
 $(\"#fecha_b\").val(\"\");

$(\"div[rel]\").each(function() {
    $(this).overlay().close();
  });
}



function cancelaGasto()
{
 var id = $(\"#id_b\").val();
 var r=confirm(\"¿Borrar gasto #\" + id +\"?\");
 if (r==true)
  {
    window.open('./util/BorraGasto.php?id_b=' + id + '&cs1_b=' + $o_config->control_structure_id1 + '&cs2_b=' + $o_config->control_structure_id2,'_self');
  }
else
  {
    return;
  }
}






</script>
";
?>