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
<input type=\"hidden\" name=\"supplier_id_h\" id=\"supplier_id_h\">

<tr>
<td class=\"modal-titulo\" colspan=\"4\">Nueva Compra</td>
</tr>

<tr>
<td class=\"modal-label-ac-2\">Nombre Proveedor</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"nombre_s\" id=\"nombre_s\" type=\"text\" required=\"required\" maxlength=\"48\" title=\"Nombre del Proveedor.\" data-message=\"Nombre del Proveedor.\"></td>

<td class=\"modal-label-2\">Fecha</td>
<td class=\"modal-input-2\"><input class=\"a-input-fecha\" type=\"date\" id=\"fecha\" name=\"fecha\" required=\"required\" title=\"Fecha.\" data-message=\"Fecha.\">
  </td>
</tr>
<tr><td class=\"modal-separador\" colspan=\"4\"></td></tr>
</table>

<table class=\"modal-captura\">
<tr>
<td class=\"modal-label-ac-2\">Producto</td>
<td class=\"modal-input-2\">
  <input class=\"a-input-2\" name=\"nombre_p\" id=\"nombre_p\" type=\"text\" required=\"required\" disabled maxlength=\"48\" title=\"Nombre o ID del producto.\" data-message=\"Nombre o ID del producto.\"></td>

<td class=\"modal-label-2\">Descripcion</td>

<td class=\"modal-input-2\"><input disabled class=\"a-input-flex\" name=\"descripcion\" id=\"descripcion\" type=\"text\" disabled   title=\"Descripción del Producto.\" data-message=\"Descripción del Producto.\"></td>
</tr>
</table>





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




$(\"#nombre_s\").autocomplete({
         source:  function (a,b)
                 {
                    id = $(\"#nombre_s\").val();
                    $.post(\"util/json_proveedor.php\",
                           {\"proveedor\" : id},
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
 
         select: function(event, supplier)
                  {
                   $(\"#nombre_s\").val(supplier.item.label);
                   $(\"#nombre_s\").prop('disabled', true);
                   $(\"#nombre_s\").data('tooltip').hide();
                   $(\"#nombre_p\").prop('disabled', false);
                   $(\"#supplier_id_h\").val(supplier.item.id);
                  },
        
        open:   function(event, ui) {
            $(\".ui-autocomplete\").css(\"z-index\", 1000000);
           }

})





$(\"#nombre_p\").autocomplete({
         source:  function (a,b)
                 {
                    s_id = $(\"#nombre_s\").val();
                    p_id = $(\"#nombre_p\").val();
                    $.post(\"util/json_producto_editar.php\",
                           {\"s_id\" : s_id, \"p_id\" : p_id},
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
 
         select: function(event, supplier)
                  {
                   $(\"#nombre_s\").val(supplier.item.label);
                   $(\"#nombre_s\").prop('disabled', true);
                   $(\"#nombre_s\").data('tooltip').hide();
                   $(\"#nombre_p\").prop('disabled', false);
                   $(\"#supplier_id_h\").val(supplier.item.id);
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
  retutn;
  } 
}



</script>
";
?>