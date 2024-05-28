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
<input type=\"hidden\" name=\"supplier_id\" id=\"supplier_id\">

<tr>
<td class=\"modal-titulo\" colspan=\"3\">Nuevo Producto</td>
</tr>

<tr>
<td class=\"modal-label\">ID</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"id\" id=\"id\" type=\"number\" pattern=\"[0-9]{13}\" required=\"required\" min=\"1\" size=\"13\" maxlength=\"13\" title=\"ID de 13 dígitos.\" data-message=\"Código de barras de 13 dígitos.\"></td>
<td class=\"modal-input\"><input class=\"a-input\" type=\"checkbox\" name=\"crearid\" id=\"crearid\" title=\"¿Crear un ID propio?\">&nbsp;Crear ID propio</td>
</tr>

<tr>
<td class=\"modal-label-ac\">Proveedor</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"supplier\" id=\"supplier\" type=\"text\" attern=\"[0-9]\" size=\"16\" maxlength=\"16\" title=\"Nombre del proveedor.\" data-message=\"Nombre del proveedor.\"></td>
<td class=\"modal-input-vacia\"></td>
</tr>

<tr>
<td class=\"modal-label\">Nombre del Producto</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"product_name\" id=\"product_name\" type=\"text\" pattern=\".{4,}\" required=\"required\" size=\"24\" maxlength=\"255\" title=\"Nombre del producto.\" data-message=\"Nombre del producto, 4 letras mínimo.\"></td>
<td class=\"modal-input-vacia\"></td>
</tr>

<tr>
<td class=\"modal-label\">Precio (con Impuesto)</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"precio\" id=\"precio\" type=\"number\" attern=\"[0-9]{0,6}\.[0-9]{0,2}\" required=\"required\" min=\"0.01\" size=\"9\" maxlength=\"9\" title=\"Precio del producto.\" data-message=\"Precio del producto.\"></td>
<td class=\"modal-input\"><input class=\"a-input\" type=\"checkbox\" checked name=\"ventapublico\" id=\"ventapublico\" title=\"¿Para venta al público?\">&nbsp;Para venta al público</td>
</tr>


<tr>
<td class=\"modal-label\">Impuesto (%)</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"tax\" id=\"tax\" type=\"number\" attern=\"[0-9]{0,6}\.[0-9]{0,2}\" min=\"0.00\" size=\"9\" maxlength=\"5\" title=\"Impuesto del producto en porcentaje.\" data-message=\"Impuesto del producto en porcentaje.\"></td>
<td class=\"modal-input-vacia\"></td>
</tr>



<tr>
<td class=\"modal-label-vacia\"></td>
<td class=\"modal-label\"><button type=\"submit\" onFocus=\"\" onBlur=\"\" class=\"modal-boton-aceptar\" formaction=\"util/NuevoProducto.php\" formmethod=\"POST\" value=\"Aceptar\">Aceptar</button></td>
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
<input type=\"hidden\" name=\"supplier_id_b\" id=\"supplier_id_b\">
<input type=\"hidden\" name=\"id_id_b\" id=\"id_id_b\">

<tr>
<td class=\"modal-titulo\" colspan=\"3\">Buscar Producto</td>
</tr>

<tr>
<td class=\"modal-label-ac\">Teclea el ID o nombre del producto</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"id_b\" id=\"id_b\" title=\"Nombre o ID.\" required=\"required\" data-message=\"Nombre o ID.\"></td>
<td class=\"modal-label\"><button id=\"barcode\" disabled type=\"button\" onclick=\"generaBC()\" class=\"modal-boton-cb\">Código de Barras</button></td>
</tr>

<tr>
<td class=\"modal-label\">Proveedor</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"supplier_b\" id=\"supplier_b\" type=\"text\" attern=\"[0-9]\" size=\"16\" maxlength=\"16\" title=\"Nombre del proveedor.\" data-message=\"Nombre del proveedor.\"></td>
<td class=\"modal-input-vacia\"></td>
</tr>

<tr>
<td class=\"modal-label\">Nombre del Producto</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"product_name_b\" id=\"product_name_b\" type=\"text\" pattern=\".{4,}\" required=\"required\" size=\"24\" maxlength=\"255\" title=\"Nombre del producto.\" data-message=\"Nombre del producto, 4 letras mínimo.\"></td>
<td class=\"modal-input-vacia\"></td>
</tr>

<tr>
<td class=\"modal-label\">Precio (con Impuesto)</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"precio_b\" id=\"precio_b\" type=\"number\" attern=\"[0-9]{0,6}\.[0-9]{0,2}\" required=\"required\" min=\"0.01\" size=\"9\" maxlength=\"9\" title=\"Precio del producto.\" data-message=\"Precio del producto.\"></td>
<td class=\"modal-input\"><input class=\"a-input\" type=\"checkbox\" checked name=\"ventapublico_b\" id=\"ventapublico_b\" title=\"¿Para venta al público?\">&nbsp;Para venta al público</td>
</tr>

<tr>
<td class=\"modal-label\">Impuesto (%)</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"tax_b\" id=\"tax_b\" type=\"number\" attern=\"[0-9]{0,6}\.[0-9]{0,2}\" min=\"0.00\" size=\"9\" maxlength=\"5\" title=\"Impuesto del producto en porcentaje.\" data-message=\"Impuesto del producto en porcentaje.\"></td>
<td class=\"modal-input-vacia\"></td>
</tr>

<tr>
<td class=\"modal-label\"><button type=\"reset\"  onclick=\"limpiaOverlay()\" class=\"modal-boton\">Limpiar</button></td>
<td class=\"modal-label\"><button disabled id=\"edita_b\" type=\"submit\" onFocus=\"\" onBlur=\"\" class=\"modal-boton-aceptar\" formaction=\"util/EditaProducto.php\" formmethod=\"POST\" valeu=\"Aceptar\">Guardar</button></td>
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


$(\"#id\").tooltip();
$(\"#crearid\").tooltip();
$(\"#supplier\").tooltip();
$(\"#product_name\").tooltip();
$(\"#precio\").tooltip();
$(\"#tax\").tooltip();
$(\"#ventapublico\").tooltip();

$(\"#id_b\").tooltip();
$(\"#crearid_b\").tooltip();
$(\"#supplier_b\").tooltip();
$(\"#product_name_b\").tooltip();
$(\"#precio_b\").tooltip();
$(\"#tax_b\").tooltip();
$(\"#ventapublico_b\").tooltip();



$(\"div[rel=#nuevo]\").overlay({
  // disable this for modal dialog-type of overlays
     closeOnClick: false,
     closeOnEsc:   false,
     top:   110,
     left:  215,
     fixed: false,
     api:   true,
     onLoad: function() {
        $('#id').focus(); 
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
        $('#id_b').focus(); 
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


$('#supplier').autocomplete({
         source:  function (a,b)
                 {
                    id = $(\"#supplier\").val();
                    $.post(\"util/json_proveedor.php\",
                           { \"proveedor\" : id },
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
                   $(\"#supplier_id\").val(supplier.item.id)
                  },
        
        open:   function(event, ui) {
            $(\".ui-autocomplete\").css(\"z-index\", 1000000);
           }

})



$('#supplier_b').autocomplete({
         source:  function (a,b)
                 {
                    id = $(\"#supplier_b\").val();
                    $.post(\"util/json_proveedor.php\",
                           { \"proveedor\" : id },
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
                       //alert(supplier.item.id);
                       $(\"#supplier_id_b\").val(supplier.item.id);
                  },
        
        open:   function(event, ui) {
            $(\".ui-autocomplete\").css(\"z-index\", 1000000);
           }

})




$('#id_b').autocomplete({
         source:  function (a,b)
                 {
                    id = $(\"#id_b\").val();
                    $.post(\"util/json_producto_editar.php\",
                           { \"id\" : id },
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


         select: function(event, product)
                  {
                   event.preventDefault();
                   $(\"#id_b\").prop('disabled', true);
                   $(\"#id_b\").tooltip().hide();
                   $(\"#precio_b\").val(product.item.price);
                   $(\"#tax_b\").val(product.item.tax);
                   $(\"#id_b\").val(product.item.id);
                   $(\"#id_id_b\").val(product.item.id);
                   $(\"#supplier_b\").val(product.item.supplier);
                   $(\"#supplier_id_b\").val(product.item.supplier_id);
                   $(\"#product_name_b\").val(product.item.value)
                   $(\"#edita_b\").prop('disabled', false);
                   $(\"#barcode\").prop('disabled', false);
  
                   if(product.item.supplier_id)
                     $(\"#supplier_b\").prop('disabled', true);

                   if(product.item.paraventa == 0)
                     {
                       $(\"#ventapublico_b\").prop('checked', false);
                       $(\"#precio_b\").prop('disabled', true);
                       $(\"#tax_b\").prop('disabled', true);
                     }
                   else
                     {
                       $(\"#ventapublico_b\").prop('checked', true);
                       $(\"#precio_b\").prop('disabled', false);
                       $(\"#tax_b\").prop('disabled', false);
                     }
                  },
        
        open:   function(event, ui) {
            $(\".ui-autocomplete\").css(\"z-index\", 1000000);
           }

})


$('input[name=crearid]').change(function(){

    if($(this).is(':checked'))
    {
        document.getElementById('id').disabled = true
    }
    else
    {
        document.getElementById('id').disabled = false;
    }
    
});


$('input[name=ventapublico]').change(function(){

    if($(this).is(':checked'))
    {
        document.getElementById('precio').disabled = false;
        document.getElementById('tax').disabled = false;
    }
    else
    {
        document.getElementById('precio').disabled = true;
        document.getElementById('tax').disabled = true;
    }
    
});


$('input[name=ventapublico_b]').change(function(){

    if($(this).is(':checked'))
    {
        document.getElementById('precio_b').disabled = false;
        document.getElementById('tax_b').disabled = false;
    }
    else
    {
        document.getElementById('precio_b').disabled = true;
        document.getElementById('tax_b').disabled = true;
    }
    
});




$('input[name=id]').blur(function(){

   var id = $(\"#id\").val();
 
  if(id)
   {
    $.post(\"util/json_producto.php\",
        { \"id\" : id },
        function(data){
            if(!data.result)
              {
                alert(data.message);
                document.getElementById('id').focus();
              }
                      }, 
        \"json\");
    }
   });

/*
$(\".cancel\").click(function() {
    validator.resetForm();
});
*/

function cierraOverlay()
{

 $('.error').hide();
 $('.tooltip').hide();

 $(\"#id_b\").val(\"\");
 $(\"#id_b\").prop('disabled', false);
 $(\"#supplier_b\").val(\"\");
 $(\"#product_name_b\").val(\"\")
 $(\"#precio_b\").val(\"\")
 $(\"#tax_b\").val(\"\")
 $(\"#precio_b\").prop('disabled', false);
 $(\"#ventapublico_b\").prop('checked', true);
 $(\"#edita_b\").prop('disabled', true);
 $(\"#barcode\").prop('disabled', true);
 $(\"#supplier_b\").prop('disabled', true);
 $(\"div[rel]\").each(function() {
    $(this).overlay().close();
  });
}


function limpiaOverlay()
{
 $(\"#myform_b\").data(\"validator\").reset();
 $(\"#id_b\").prop('disabled', false);
 $(\"#edita_b\").prop('disabled', true);
 $(\"#barcode\").prop('disabled', true);
 $(\"#supplier_b\").prop('disabled', false);
 
}

function generaBC()
{
   var bc = $(\"#id_b\").val();
   window.open(\"./util/get_bc.php?barcode=\"+bc, 'barcode', 'width=400,height=400,scrollbars=yes');
}

</script>
";
 ?>