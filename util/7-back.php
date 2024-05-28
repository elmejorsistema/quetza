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
    $cargar_nuevo  = "load : true";
    $cargar_buscar = "load : false";
    break;
  case "buscar":
    $cargar_buscar = "load : true";
    $cargar_nuevo  = "load : false";
    break;
  default:
    $cargar_buscar = "load : false";
    $cargar_nuevo  = "load : false";
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
<td class=\"a-menu-contenido\"><div class=\"a-opcion\" rel=\"#nuevo\">Nuevo</div></td>
<td class=\"a-menu-contenido\"><div class=\"a-opcion\" rel=\"#buscar\">Buscar</div></td>
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
<td class=\"modal-titulo\" colspan=\"2\">Nuevo Empleado</td>
</tr>

<tr>
<td class=\"modal-label\">Nombre</td>
<td class=\"modal-input-1\">
  <input class=\"a-input\" name=\"nombre\" id=\"nombre\" type=\"text\" required=\"required\" maxlength=\"48\" title=\"Nombre del Empleado.\" data-message=\"Nombre del Empleado.\"></td>
</tr>


<tr>
<td class=\"modal-label\">Pago por Hora</td>
<td class=\"modal-input-1\">
  <input class=\"a-input\" name=\"rate_hour\" id=\"rate_hour\" type=\"number\" maxlength=\"48\" title=\"Pago por hora.\" data-message=\"Pago por hora.\"></td>
</tr>


<tr>
<td class=\"modal-label\">Cuenta Nómina</td>
<td class=\"modal-input-1\">
  <input class=\"a-input\" name=\"cuenta_nomina\" id=\"cuenta_nomina\" type=\"text\" maxlength=\"48\" title=\"Cuenta de nómina.\" data-message=\"Cuenta de nómina.\"></td>
</tr>

<tr>
<td class=\"modal-label\"><button type=\"submit\" onFocus=\"\" onBlur=\"\" class=\"modal-boton-aceptar\" formaction=\"util/NuevoEmpleado.php\" formmethod=\"POST\" value=\"Aceptar\">Aceptar</button></td>
<td class=\"modal-label\"><button type=\"button\" onclick=\"cierraOverlay()\" class=\"modal-boton-cerrar-ol\">Cancelar</button></td>

</tr>
</table>
</form>
</div>




<div class=\"modal\" id=\"buscar\">
<form id=\"myform_b\">
<table class=\"modal-captura\">

<input type=\"hidden\" name=\"cs1\" value=\"$o_config->control_structure_id1\">
<input type=\"hidden\" name=\"cs2\" value=\"$o_config->control_structure_id2\">
<input type=\"hidden\" name=\"employee_id_b\" id=\"employee_id_b\">

<tr>
<td class=\"modal-titulo\" colspan=\"3\">Buscar Empleado</td>
</tr>

<tr>
<td class=\"modal-label-ac\">Teclea Nombre</td>
<td class=\"modal-input\" colspan=\"2\">
  <input class=\"a-input-1\" name=\"nombre_b\" id=\"nombre_b\" type=\"text\" required=\"required\" maxlength=\"48\" title=\"Nombre del Empleado.\" data-message=\"Nombre del Empleado.\"></td>
</tr>


<tr>
<td class=\"modal-label\">Pago por Hora</td>
<td class=\"modal-input\" colspan=\"2\"\">
  <input class=\"a-input-1\" name=\"rate_hour_b\" id=\"rate_hour_b\" type=\"number\" maxlength=\"48\" title=\"Pago por hora.\" data-message=\"Pago por hora.\"></td>
</tr>


<tr>
<td class=\"modal-label\">Cuenta Nómina</td>
<td class=\"modal-input-1\" colspan=\"2\"\">
  <input class=\"a-input\" name=\"cuenta_nomina_b\" id=\"cuenta_nomina_b\" type=\"text\" maxlength=\"48\" title=\"Cuenta de nómina.\" data-message=\"Cuenta de nómina.\"></td>
</tr>

<tr>
<td class=\"modal-label\"><button type=\"reset\"  onclick=\"limpiaOverlay()\" class=\"modal-boton\">Limpiar</button></td>
<td class=\"modal-label\"><button type=\"submit\"  id=\"edita_b\" onFocus=\"\" onBlur=\"\" class=\"modal-boton-aceptar\" formaction=\"util/EditaEmpleado.php\" formmethod=\"POST\" value=\"Aceptar\" disabled>Guardar</button></td>
<td class=\"modal-label\"><button type=\"button\" onclick=\"cierraOverlay()\" class=\"modal-boton-cerrar-ol\">Cancelar</button></td>

</tr>
</table>
</form>
</div>

<script>

$(document).ready(function() {

$(\"#myform\").validator({
    singleError: false,
    position: \"bottom center\"
});


$(\"#myform_b\").validator({
    singleError: false,
    position: \"bottom center\"
});


$(\"#nombre\").tooltip();
$(\"#rate_hour\").tooltip();
$(\"#cuenta_nomina\").tooltip();

$(\"#nombre_b\").tooltip();
$(\"#rate_hour_b\").tooltip();
$(\"#cuenta_nomina_b\").tooltip();





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

 });


$(\"#nombre_b\").autocomplete({
         source:  function (a,b)
                 {
                    id = $(\"#nombre_b\").val();
                    $.post(\"util/json_empleado.php\",
                           {\"empleado\" : id},
                           function(data){
                               if(data)
                                  {
                                    var suggestions = []; 
                                    $.each(data, function(i, val)
                                     {                           
                                       //var newitem = val.value +\" \"+ val.label;
                                       //suggestions.push(newitem);  
                                        suggestions.push(val);  
                                       //alert(val.id);
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
                   $(\"#employee_id_b\").val(supplier.item.id)

 $(\"#nombre_b\").val(supplier.item.label);
 $(\"#rate_hour_b\").val(supplier.item.rate_hour);
 $(\"#cuenta_nomina_b\").val(supplier.item.cuenta_nomina);
 $(\"#edita_b\").prop('disabled', false);
                  },
        
        open:   function(event, ui) {
            $(\".ui-autocomplete\").css(\"z-index\", 1000000);
           }

})





function cierraOverlay()
{

 $('.error').hide();
 $('.tooltip').hide();

 $(\"#nombre\").val(\"\");
 $(\"#rate_hour\").val(\"\");
 $(\"#cuenta_nomina\").val(\"\");

 $(\"#nombre_b\").val(\"\");
 $(\"#rate_hour_b\").val(\"\");
 $(\"#cuenta_nomina_b\").val(\"\");


 $(\"#myform\").data(\"validator\").reset();
 $(\"#myform_b\").data(\"validator\").reset();


 $(\"div[rel]\").each(function() {
    $(this).overlay().close();
  });
}


function limpiaOverlay()
{
 $(\"#edita_b\").prop('disabled', true);
 $(\"#myform_b\").data(\"validator\").reset();
}

</script>
";
?>