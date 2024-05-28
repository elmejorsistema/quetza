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
<td class=\"modal-titulo\" colspan=\"4\">Nuevo Proveedor</td>
</tr>

<tr>
<td class=\"modal-label\">Nombre</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"nombre\" id=\"nombre\" type=\"text\" required=\"required\"   maxlength=\"255\" title=\"Nombre del Proveedor.\" data-message=\"Nombre del Proveedor.\"></td>
<td class=\"modal-label\">RFC</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"rfc\" id=\"rfc\" type=\"text\"  pattern=\"^[A-Za-z]{3,4}[\d]{6}[A-Za-z\d]{3}$\" maxlength=\"13\" title=\"RFC del Proveedor.\" data-message=\"Se neceista un formato de RFC válido .\"></td>
</tr>

<tr>
<td class=\"modal-label\">Calle</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"calle\" id=\"calle\" type=\"text\" maxlength=\"32\" title=\"La calle.\" data-message=\"La calle.\"></td>
<td class=\"modal-label\">No. Exterior</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"no_exterior\" id=\"no_exterior\" type=\"text\"  maxlength=\"32\" title=\"El número exterior.\" data-message=\"El número exterior.\"></td>
</tr>


<tr>
<td class=\"modal-label\">Colonia/Barrio</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"colonia\" id=\"colonia\" type=\"text\" maxlength=\"32\" title=\"La colonia o barrio.\" data-message=\"La colonia o barrio.\"></td>
<td class=\"modal-label\">No. Interior</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"no_interior\" id=\"no_interior\" type=\"text\"  maxlength=\"32\" title=\"El número interior.\" data-message=\"El número interior.\"></td>
</tr>


<tr>
<td class=\"modal-label\">Localidad/Pueblo</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"localidad\" id=\"localidad\" type=\"text\" maxlength=\"32\" title=\"La localidad o pueblo.\" data-message=\"La localidad o pueblo.\"></td>
<td class=\"modal-label\">Municipio/Delegación</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"municipio\" id=\"municipio\" type=\"text\"  maxlength=\"32\" title=\"El municipio o la delegación.\" data-message=\"El municipio o la delegación.\"></td>
</tr>


<tr>
<td class=\"modal-label\">Estado</td>
<td class=\"modal-input\">";
create_select("select id, nombre from estado order by id" , "estado_id", 17, null, $o_database, "a-select");
echo"
</td>
<td class=\"modal-label\">País</td>
<td class=\"modal-input\">";
create_select("select id, nombre from pais order by nombre" , "pais_id", 150, null, $o_database, "a-select");
echo "
</td>
</tr>


<tr>
<td class=\"modal-label\">Código Postal</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"codigo_postal\" id=\"codigo_postal\" type=\"text\" maxlength=\"24\" title=\"El código postal.\" data-message=\"El código postal.\"></td>
<td class=\"modal-label\">Teléfono</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"telefono\" id=\"telefono\" type=\"text\"  maxlength=\"64\" title=\"El teléfono.\" data-message=\"El teléfono.\"></td>
</tr>


<tr>
<td class=\"modal-label\">Correo Electrónico</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"email\" id=\"email\" type=\"email\" maxlength=\"128\" title=\"El correo electrónico.\" data-message=\"El correo electrónico.\"></td>
<td class=\"modal-label\">Página Web</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"pagina_web\" id=\"pagina_web\" type=\"url\"  maxlength=\"255\" title=\"La página web.\" data-message=\"La página web.\"></td>
</tr>



<tr>
<td class=\"modal-label-vacia\"></td>
<td class=\"modal-label\"><button type=\"submit\" onFocus=\"\" onBlur=\"\" class=\"modal-boton-aceptar\" formaction=\"util/NuevoProveedor.php\" formmethod=\"POST\" value=\"Aceptar\">Aceptar</button></td>
<td class=\"modal-label-vacia\"></td>
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

<tr>
<td class=\"modal-titulo\" colspan=\"4\">Busca Proveedor</td>
</tr>

<tr>
<td class=\"modal-label-ac\">Teclea Nombre</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"nombre_b\" id=\"nombre_b\" type=\"text\" required=\"required\"   maxlength=\"255\" title=\"Nombre del Proveedor.\" data-message=\"Nombre del Proveedor.\"></td>
<td class=\"modal-label\">RFC</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"rfc_b\" id=\"rfc_b\" type=\"text\"  pattern=\"^[A-Za-z]{3,4}[\d]{6}[A-Za-z\d]{3}$\" maxlength=\"13\" title=\"RFC del Proveedor.\" data-message=\"Se neceista un formato de RFC válido .\"></td>
</tr>

<tr>
<td class=\"modal-label\">Calle</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"calle_b\" id=\"calle_b\" type=\"text\" maxlength=\"32\" title=\"La calle.\" data-message=\"La calle.\"></td>
<td class=\"modal-label\">No. Exterior</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"no_exterior_b\" id=\"no_exterior_b\" type=\"text\"  maxlength=\"32\" title=\"El número exterior.\" data-message=\"El número exterior.\"></td>
</tr>


<tr>
<td class=\"modal-label\">Colonia/Barrio</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"colonia_b\" id=\"colonia_b\" type=\"text\" maxlength=\"32\" title=\"La colonia o barrio.\" data-message=\"La colonia o barrio.\"></td>
<td class=\"modal-label\">No. Interior</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"no_interior_b\" id=\"no_interior_b\" type=\"text\"  maxlength=\"32\" title=\"El número interior.\" data-message=\"El número interior.\"></td>
</tr>


<tr>
<td class=\"modal-label\">Localidad/Pueblo</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"localidad_b\" id=\"localidad_b\" type=\"text\" maxlength=\"32\" title=\"La localidad o pueblo.\" data-message=\"La localidad o pueblo.\"></td>
<td class=\"modal-label\">Municipio/Delegación</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"municipio_b\" id=\"municipio_b\" type=\"text\"  maxlength=\"32\" title=\"El municipio o la delegación.\" data-message=\"El municipio o la delegación.\"></td>
</tr>


<tr>
<td class=\"modal-label\">Estado</td>
<td class=\"modal-input\">";
create_select("select id, nombre from estado order by id" , "estado_id_b", 17, null, $o_database, "a-select");
echo"
</td>
<td class=\"modal-label\">País</td>
<td class=\"modal-input\">";
create_select("select id, nombre from pais order by nombre" , "pais_id_b", 150, null, $o_database, "a-select");
echo "
</td>
</tr>


<tr>
<td class=\"modal-label\">Código Postal</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"codigo_postal_b\" id=\"codigo_postal_b\" type=\"text\" maxlength=\"24\" title=\"El código postal.\" data-message=\"El código postal.\"></td>
<td class=\"modal-label\">Teléfono</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"telefono_b\" id=\"telefono_b\" type=\"text\"  maxlength=\"64\" title=\"El teléfono.\" data-message=\"El teléfono.\"></td>
</tr>


<tr>
<td class=\"modal-label\">Correo Electrónico</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"email_b\" id=\"email_b\" type=\"email\" maxlength=\"128\" title=\"El correo electrónico.\" data-message=\"El correo electrónico.\"></td>
<td class=\"modal-label\">Página Web</td>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"pagina_web_b\" id=\"pagina_web_b\" type=\"url\"  maxlength=\"255\" title=\"La página web.\" data-message=\"La página web.\"></td>
</tr>



<tr>
<td class=\"modal-label\"><button type=\"reset\"  onclick=\"limpiaOverlay()\" class=\"modal-boton\">Limpiar</button></td>
<td class=\"modal-label\"><button type=\"submit\" id=\"edita_b\" onFocus=\"\" onBlur=\"\" class=\"modal-boton-aceptar\" formaction=\"util/EditaProveedor.php\" formmethod=\"POST\" value=\"Aceptar\" disabled>Guardar</button></td>
<td class=\"modal-label-vacia\"></td>
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
$(\"#rfc\").tooltip();
$(\"#calle\").tooltip();
$(\"#no_exterior\").tooltip();
$(\"#colonia\").tooltip();
$(\"#no_interior\").tooltip();
$(\"#localidad\").tooltip();
$(\"#municipio\").tooltip();
$(\"#codigo_postal\").tooltip();
$(\"#telefono\").tooltip();
$(\"#email\").tooltip();
$(\"#pagina_web\").tooltip();



$(\"#nombre_b\").tooltip();
$(\"#rfc_b\").tooltip();
$(\"#calle_b\").tooltip();
$(\"#no_exterior_b\").tooltip();
$(\"#colonia_b\").tooltip();
$(\"#no_interior_b\").tooltip();
$(\"#localidad_b\").tooltip();
$(\"#municipio_b\").tooltip();
$(\"#codigo_postal_b\").tooltip();
$(\"#telefono_b\").tooltip();
$(\"#email_b\").tooltip();
$(\"#pagina_web_b\").tooltip();



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
                    $.post(\"util/json_proveedor.php\",
                           { \"proveedor\" : id, \"all_data\" : \"on\" },
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
                   $(\"#supplier_id_b\").val(supplier.item.id)

 $(\"#nombre_b\").val(supplier.item.label);
 $(\"#rfc_b\").val(supplier.item.rfc);
 $(\"#calle_b\").val(supplier.item.calle);
 $(\"#no_exterior_b\").val(supplier.item.no_exterior);
 $(\"#colonia_b\").val(supplier.item.colonia);
 $(\"#no_interior_b\").val(supplier.item.no_interior);
 $(\"#localidad_b\").val(supplier.item.localidad); 
 $(\"#municipio_b\").val(supplier.item.municipio);
 $(\"#codigo_postal_b\").val(supplier.item.codigo_postal);
 $(\"#telefono_b\").val(supplier.item.telefono);
 $(\"#email_b\").val(supplier.item.email);
 $(\"#pagina_web_b\").val(supplier.item.pagina_web);
 $(\"#estado_id_b\").val(supplier.item.estado_id);
 $(\"#pais_id_b\").val(supplier.item.pais_id);
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
 $(\"#rfc\").val(\"\");
 $(\"#calle\").val(\"\");
 $(\"#no_exterior\").val(\"\");
 $(\"#colonia\").val(\"\");
 $(\"#no_interior\").val(\"\");
 $(\"#localidad\").val(\"\"); 
 $(\"#municipio\").val(\"\");
 $(\"#codigo_postal\").val(\"\");
 $(\"#telefono\").val(\"\");
 $(\"#email\").val(\"\");
 $(\"#pagina_web\").val(\"\");
 $(\"#estado_id\").val(17);
 $(\"#pais_id\").val(150);

 $(\"#nombre_b\").val(\"\");
 $(\"#rfc_b\").val(\"\");
 $(\"#calle_b\").val(\"\");
 $(\"#no_exterior_b\").val(\"\");
 $(\"#colonia_b\").val(\"\");
 $(\"#no_interior_b\").val(\"\");
 $(\"#localidad_b\").val(\"\"); 
 $(\"#municipio_b\").val(\"\");
 $(\"#codigo_postal_b\").val(\"\");
 $(\"#telefono_b\").val(\"\");
 $(\"#email_b\").val(\"\");
 $(\"#pagina_web_b\").val(\"\");
 $(\"#estado_id_b\").val(17);
 $(\"#pais_id_b\").val(150);


 $(\"#edita_b\").prop('disabled', true);
 $(\"#myform_b\").data(\"validator\").reset();
 $(\"#myform\").data(\"validator\").reset();


 $(\"div[rel]\").each(function() {
    $(this).overlay().close();
  });
}


function limpiaOverlay()
{
 $(\"#edita_b\").prop('disabled', true);
 $(\"#myform_b\").data(\"validator\").reset();
 $(\"#estado_id_b\").val(17);
 $(\"#pais_id_b\").val(150);
}

</script>
";
?>