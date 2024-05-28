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
<input type=\"hidden\" name=\"employee_id\" id=\"employee_id\">
<input type=\"hidden\" name=\"rate_hour_h\" id=\"rate_hour_h\">
<input type=\"hidden\" name=\"total_hours_h\" id=\"total_hours_h\">

<tr>
<td class=\"modal-titulo\" colspan=\"4\">Nueva entrada de Nómina</td>
</tr>

<tr>
<td class=\"modal-label-ac-2\">Teclea Nombre</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"nombre\" id=\"nombre\" type=\"text\" required=\"required\" maxlength=\"48\" title=\"Nombre del Empleado.\" data-message=\"Nombre del Empleado.\"></td>

<td class=\"modal-label-2\">Pago por Hora</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" disabled name=\"rate_hour\" id=\"rate_hour\" type=\"number\" required=\"required\" maxlength=\"48\" title=\"Pago por hora.\" data-message=\"Pago por hora.\"></td>
</tr>

<tr>
<td class=\"modal-label-2\">Horas Trabajadas</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"hours\" id=\"hours\" type=\"number\" required=\"required\" min=\"0.1\" maxlength=\"48\" title=\"Horas trabajadas.\" data-message=\"Horas trabajadas.\"></td>

<td class=\"modal-label-2\">Pago Horas</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" disabled name=\"total_hours\" id=\"total_hours\" type=\"number\" required=\"required\" maxlength=\"48\" title=\"Total de horas.\" data-message=\"Total de horas.\"></td>
</tr>


<tr>
<td class=\"modal-label-2\">Fecha Inicial</td>
<td class=\"modal-input-2\"><input class=\"a-input-fecha\" type=\"date\" id=\"inicial\" name=\"inicial\" required=\"required\" title=\"Fecha inicial.\" data-message=\"Fecha inicial.\">
  </td>

<td class=\"modal-label-2\">Fecha Final</td>
<td class=\"modal-input-2\"><input class=\"a-input-fecha\" type=\"date\" id=\"final\" name=\"final\"   required=\"required\" title=\"Fecha final.\" data-message=\"Fecha final.\">
  </td>
</tr>

<tr>
<td class=\"modal-label-2\">Extra 1</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"extra_1\" id=\"extra_1\" type=\"number\" maxlength=\"48\" title=\"Pago extra 1.\" data-message=\"Pago extra 1.\"></td>

<td class=\"modal-label-2\">Comentario 1</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"comment_extra_1\" id=\"comment_extra_1\" type=\"text\" maxlength=\"48\" title=\"Comentario pago extra 1.\" data-message=\"Comentario pago extra 1.\"></td>
</tr>

<tr>
<td class=\"modal-label-2\">Extra 2</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"extra_2\" id=\"extra_2\" type=\"number\" maxlength=\"48\" title=\"Pago extra 2.\" data-message=\"Pago extra 2.\"></td>

<td class=\"modal-label-2\">Comentario 2</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"comment_extra_2\" id=\"comment_extra_2\" type=\"text\" maxlength=\"48\" title=\"Comentario pago extra 2.\" data-message=\"Comentario pago extra 2.\"></td>
</tr>

<tr>
<td class=\"modal-label-2\">Extra 3</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"extra_3\" id=\"extra_3\" type=\"number\" maxlength=\"48\" title=\"Pago extra 3.\" data-message=\"Pago extra 3.\"></td>

<td class=\"modal-label-2\">Comentario 3</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"comment_extra_3\" id=\"comment_extra_3\" type=\"text\" maxlength=\"48\" title=\"Comentario pago extra 3.\" data-message=\"Comentario pago extra 3.\"></td>
</tr>

<tr>
<td class=\"modal-label-2\" colspan=\"2\"><button type=\"submit\"   formaction=\"util/NuevaEntradaNomina.php\" formmethod=\"POST\" class=\"modal-boton-aceptar\">Aceptar</button></td>
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
<input type=\"hidden\" name=\"employee_id_b\" id=\"employee_id_b\">
<input type=\"hidden\" name=\"rate_hour_h_b\" id=\"rate_hour_h_b\">
<input type=\"hidden\" name=\"total_hours_h_b\" id=\"total_hours_h_b\">
<input type=\"hidden\" name=\"payroll_id_h_b\" id=\"payroll_id_h_b\">

<tr>
<td class=\"modal-titulo\" colspan=\"4\">Buscar entrada de Nómina</td>
</tr>

<tr>
<td class=\"modal-label-ac-2\">Teclea Nombre</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"nombre_b\" id=\"nombre_b\" type=\"text\" required=\"required\" maxlength=\"48\" title=\"Nombre del Empleado.\" data-message=\"Nombre del Empleado.\"></td>

<td class=\"modal-label-2\">Pago por Hora</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" disabled name=\"rate_hour_b\" id=\"rate_hour_b\" type=\"number\" required=\"required\" maxlength=\"48\" title=\"Pago por hora.\" data-message=\"Pago por hora.\"></td>
</tr>

<tr>
<td class=\"modal-label-2\">Horas Trabajadas</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"hours_b\" id=\"hours_b\" type=\"number\" required=\"required\" min=\"0.1\" maxlength=\"48\" title=\"Horas trabajadas.\" data-message=\"Horas trabajadas.\"></td>

<td class=\"modal-label-2\">Pago Horas</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" disabled name=\"total_hours_b\" id=\"total_hours_b\" type=\"number\" required=\"required\" maxlength=\"48\" title=\"Total de horas.\" data-message=\"Total de horas.\"></td>
</tr>


<tr>
<td class=\"modal-label-2\">Fecha Inicial</td>
<td class=\"modal-input-2\"><input class=\"a-input-fecha\" type=\"date\" id=\"inicial_b\" name=\"inicial_b\" required=\"required\" title=\"Fecha inicial.\" data-message=\"Fecha inicial.\">
  </td>

<td class=\"modal-label-2\">Fecha Final</td>
<td class=\"modal-input-2\"><input class=\"a-input-fecha\" type=\"date\" id=\"final_b\" name=\"final_b\"   required=\"required\" title=\"Fecha final.\" data-message=\"Fecha final.\">
  </td>
</tr>

<tr>
<td class=\"modal-label-2\">Extra 1</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"extra_1_b\" id=\"extra_1_b\" type=\"number\" maxlength=\"48\" title=\"Pago extra 1.\" data-message=\"Pago extra 1.\"></td>

<td class=\"modal-label-2\">Comentario 1</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"comment_extra_1_b\" id=\"comment_extra_1_b\" type=\"text\" maxlength=\"48\" title=\"Comentario pago extra 1.\" data-message=\"Comentario pago extra 1.\"></td>
</tr>

<tr>
<td class=\"modal-label-2\">Extra 2</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"extra_2_b\" id=\"extra_2_b\" type=\"number\" maxlength=\"48\" title=\"Pago extra 2.\" data-message=\"Pago extra 2.\"></td>

<td class=\"modal-label-2\">Comentario 2</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"comment_extra_2_b\" id=\"comment_extra_2_b\" type=\"text\" maxlength=\"48\" title=\"Comentario pago extra 2.\" data-message=\"Comentario pago extra 2.\"></td>
</tr>

<tr>
<td class=\"modal-label-2\">Extra 3</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"extra_3_b\" id=\"extra_3_b\" type=\"number\" maxlength=\"48\" title=\"Pago extra 3.\" data-message=\"Pago extra 3.\"></td>

<td class=\"modal-label-2\">Comentario 3</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"comment_extra_3_b\" id=\"comment_extra_3_b\" type=\"text\" maxlength=\"48\" title=\"Comentario pago extra 3.\" data-message=\"Comentario pago extra 3.\"></td>
</tr>
</table>

<table class=\"modal-captura\">
<tr>
<td class=\"modal-label\"><button type=\"reset\"  onclick=\"limpiaOverlay()\" class=\"modal-boton\">Limpiar</button></td>
<td class=\"modal-label\"><button id=\"edita_b\" disabled type=\"submit\" formaction=\"util/EditaEntradaNomina.php\" formmethod=\"POST\" class=\"modal-boton-editar\">Guardar</button></td>
<td class=\"modal-label\"><button type=\"button\" onclick=\"cierraOverlay()\" class=\"modal-boton-cerrar-ol\">Cancelar</button></td>
<td class=\"modal-label\"><button id=\"borra\" type=\"button\" disabled onclick=\"cancelaEntradaNomina()\" class=\"modal-boton-cancelar\">Borrar</button></td>
</tr>
</table>
</form>
</div>


<div class=\"modal\" id=\"generar\">
<form id=\"myform_g\">
<table class=\"modal-captura\">


<tr>
<td class=\"modal-titulo\" colspan=\"4\">Generar Nómina</td>
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
<td class=\"modal-label\"><button id=\"genera\" type=\"submit\" formaction=\"util/GeneraNomina.php\" formmethod=\"POST\" class=\"modal-boton-aceptar\">Generar</button></td>
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


$('[id^=nombre]').tooltip();
$('[id^=rate_hour]').tooltip();
$('[id^=hours]').tooltip();
$('[id^=total_hours]').tooltip();
$('[id^=extra_1]').tooltip();
$('[id^=comment_extra_1]').tooltip();
$('[id^=extra_2]').tooltip();
$('[id^=comment_extra_2]').tooltip();
$('[id^=extra_3]').tooltip();
$('[id^=comment_extra_3]').tooltip();













$(\"div[rel=#nuevo]\").overlay({
  // disable this for modal dialog-type of overlays
     closeOnClick: false,
     closeOnEsc:   false,
     top:   110,
     left:  215,
     fixed: false,
     api:   true,
     onLoad: function() {
        $(\"#nombre\").focus(); 
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
        $(\"#nombre_b\").focus(); 
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


$(\":date:eq(4)\").data(\"dateinput\").change(function() {
	// we use it's value for the seconds input min option
	$(\":date:eq(5)\").data(\"dateinput\").setMin(this.getValue(), true);
});





$(\"#nombre\").autocomplete({
         source:  function (a,b)
                 {
                    id = $(\"#nombre\").val();
                    $.post(\"util/json_empleado.php\",
                           {\"empleado\" : id},
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
 
         select: function(event, employee)
                  {
                   $(\"#employee_id\").val(employee.item.id)
                   $(\"#nombre\").val(employee.item.label);
                   $(\"#nombre\").prop('disabled', true);
                   $(\"#nombre\").data('tooltip').hide();
                   $(\"#rate_hour\").val(employee.item.rate_hour);
                   $(\"#rate_hour_h\").val(employee.item.rate_hour);
                  },
        
        open:   function(event, ui) {
            $(\".ui-autocomplete\").css(\"z-index\", 1000000);
           }

})




$(\"#nombre_b\").autocomplete({
         source:  function (a,b)
                 {
                    id = $(\"#nombre_b\").val();
                    $.post(\"util/json_empleado_nomina.php\",
                           {\"empleado\" : id},
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
 
         select: function(event, employee)
                  {
                   $(\"#payroll_id_h_b\").val(employee.item.p_id);
                   $(\"#employee_id_b\").val(employee.item.e_id)
                   $(\"#nombre_b\").val(employee.item.name);
                   $(\"#nombre_b\").prop('disabled', true);
                   $(\"#nombre_b\").data('tooltip').hide();
                   $(\"#rate_hour_b\").val(employee.item.rate_hour);
                   $(\"#rate_hour_h_b\").val(employee.item.rate_hour);
                   $(\"#hours_b\").val(employee.item.hours);
                   $(\"#total_hours_b\").val(employee.item.total_hours);
                   $(\"#total_hours_h_b\").val(employee.item.total_hours);
                   $(\"#inicial_b\").val(employee.item.initial);
                   $(\":date:eq(3)\").data(\"dateinput\").setMin(employee.item.initial, true);
                   $(\"#final_b\").val(employee.item.final);
                   $(\"#extra_1_b\").val(employee.item.extra_1);
                   $(\"#comment_extra_1_b\").val(employee.item.comment_extra_1);
                   $(\"#extra_2_b\").val(employee.item.extra_2);
                   $(\"#comment_extra_2_b\").val(employee.item.comment_extra_2);
                   $(\"#extra_3_b\").val(employee.item.extra_3);
                   $(\"#comment_extra_3_b\").val(employee.item.comment_extra_3);
                   $(\"#edita_b\").prop('disabled', false);
                   $(\"#borra\").prop('disabled', false);
                  },
        
        open:   function(event, ui) {
            $(\".ui-autocomplete\").css(\"z-index\", 1000000);
           }

})






function cierraOverlay()
{
 $('[id^=myform]').data(\"validator\").reset();
 $('[id^=myform]').trigger('reset');
 $('[id^=nombre]').prop('disabled', false);
 $('#edita_b').prop('disabled', true);

/*
 $(\"#myform\").data(\"validator\").reset();
 $(\"#myform\").trigger('reset');
 $(\"#nombre\").prop('disabled', false);
*/

 $(\"div[rel]\").each(function() {
    $(this).overlay().close();
  });
}


function limpiaOverlay()
{
 $(\"#payroll_id_h_b\").val('');
 $(\"#employee_id_b\").val('')
 $(\"#myform_b\").data(\"validator\").reset();
 $(\"#nombre_b\").prop('disabled', false);
 $(\"#edita_b\").prop('disabled', true);
 $(\"#borra\").prop('disabled', true);
}

$('[id^=hours]').bind('focusin', function(e) {
 $(this).attr(\"class\", \"a-input-2-editing\");
})


$(\"#hours\").bind('blur', function(e) {
 var rate_hour    = $(\"#rate_hour\").val();
 var hours        = $(\"#hours\").val();
 var pago         = parseFloat(hours) * parseFloat(rate_hour);
 pago             = parseFloat(pago).toFixed(2);
 $(\"#total_hours\").val(pago);
 $(\"#total_hours_h\").val(pago);
 $(this).attr(\"class\", \"a-input-2\");
})


$(\"#hours_b\").bind('blur', function(e) {
 var rate_hour    = $(\"#rate_hour_b\").val();
 var hours        = $(\"#hours_b\").val();
 var pago         = parseFloat(hours) * parseFloat(rate_hour);
 pago             = parseFloat(pago).toFixed(2);
 $(\"#total_hours_b\").val(pago);
 $(\"#total_hours_h_b\").val(pago);
 $(this).attr(\"class\", \"a-input-2\");
})


function cancelaEntradaNomina()
{
 var payroll_id = $(\"#payroll_id_h_b\").val();
 var r=confirm(\"¿Borrar entrada de nómina #\" + payroll_id +\"?\");
 if (r==true)
  {
    window.open('./util/BorraEntradaNomina.php?payroll_id_h_b=' +  payroll_id + '&cs1_b=' + $o_config->control_structure_id1 + '&cs2_b=' + $o_config->control_structure_id2,'_self');
  }
else
  {
    return;
  } 
}



</script>
";
?>