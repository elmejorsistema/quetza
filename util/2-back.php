<?php

// Esto es para arir alguno de los overlay
//////////////////////////////////////////
if(!empty($_GET['ovly']))
  $ovly = $_GET['ovly'];
else
  $ovly = null;

/*
switch($ovly)
  {
  case "nuevo":
    $cargar_nuevo   = "load : true";
    $cargar_buscar  = "load : false";
  default:
    $cargar_nuevo   = "load : false";
    $cargar_buscar  = "load : true";
    break;
  }
*/


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
<td class=\"modal-titulo\">Nueva Venta</td>
</tr>

<tr>
<td class=\"modal-label\">Mesa/Identificador</td>
</tr>
<tr>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"identifier\" id=\"identifier\" type=\"text\"  maxlength=\"16\" title=\"Mesa o identificador.\" data-message=\"Mesa o identificador.\"></td>
</tr>
<tr>
<td class=\"modal-label\"><button type=\"submit\" onFocus=\"\" onBlur=\"\" class=\"modal-boton-aceptar\" formaction=\"util/NuevaVenta.php\" formmethod=\"POST\" value=\"Aceptar\">Aceptar</button></td>
</tr>
</table>
</form>

</td>
<td class=\"a-menu-contenido\"><div class=\"a-opcion\" rel=\"#buscr\">
<table class=\"a-ventas\">
";
$q = "select id, identifier from sale where status_sale_id = 1";
$o_database->query_rows($q);
$result = $o_database->query_result;
$columnas = 3;
$col_actual = 1;
$eq = 0;
while($row = mysql_fetch_row($result))
  {
    if($col_actual > $columnas)
      $col_actual = 1;

    if($col_actual == 1)
      echo "<tr>";

    $div_actual = $col_actual - 1;

    if(empty($row[1]))
      $etiqueta = "Venta $row[0]";
    else
      $etiqueta = $row[1];

    //echo "<td class=\"a-ventas\"><button rel=\"div.modal:eq($div_actual)\" class=\"a-ventas\" id=\"$row[0]\"\">$etiqueta</button></td>";
    echo "<td class=\"a-ventas\"><button rel=\"div.modal:eq($eq)\" class=\"a-ventas\" id=\"$row[0]\"\">$etiqueta</button></td>";


    
    if($col_actual == $columnas)
	echo "</tr>";
	
    $col_actual++;
    $eq++;
  }
$cierra_tr = false;
while($col_actual <= $columnas)
  {
    $cierra_tr = true;
    echo "<td class=\"a-ventas\">&nbsp;</td>";
    $col_actual++;
  }
if($cierra_tr)
  echo "</tr>";

echo "</table>";


echo "</td>
</tr>
</table>";

$q = "select * from sale where status_sale_id = 1";
$o_database->query_rows($q);
$result = $o_database->query_result;
while($row = mysql_fetch_row($result))
  {
    /*
+-----------------+-----------------------+------+-----+---------+----------------+
| Field           | Type                  | Null | Key | Default | Extra          |
+-----------------+-----------------------+------+-----+---------+----------------+
| id              | smallint(5) unsigned  | NO   | PRI | NULL    | auto_increment |
| user_id         | tinyint(3) unsigned   | NO   |     | NULL    |                |
| client_id       | int(10) unsigned      | YES  | MUL | NULL    |                |
| status_sale_id  | tinyint(3) unsigned   | NO   | MUL | 1       |                |
| payment_type_id | tinyint(3) unsigned   | NO   | MUL | 1       |                |
| card_id         | tinyint(3) unsigned   | YES  | MUL | NULL    |                |
| total_s_tax     | decimal(8,2) unsigned | NO   |     | 0.00    |                |
| total_c_tax     | decimal(8,2) unsigned | NO   |     | 0.00    |                |
| tax             | decimal(3,3) unsigned | NO   |     | 0.000   |                |
| date            | datetime              | YES  |     | NULL    |                |
| identifier      | varchar(16)           | YES  |     | NULL    |                |
| comment         | varchar(16)           | YES  |     | NULL    |                |
+-----------------+-----------------------+------+-----+---------+----------------+
12 rows in set (0.00 sec)
   */

$total_s_tax =  number_format($row[6], 2, '.', ',');
$total_c_tax =  number_format($row[7], 2, '.', ',');
$tax         =  number_format($row[8], 2, '.', ',');

echo "

<div class=\"modal\" id=\"$row[0]\">

<table class=\"modal-captura\">

<input type=\"hidden\" name=\"cs1\" value=\"$o_config->control_structure_id1\">
<input type=\"hidden\" name=\"cs2\" value=\"$o_config->control_structure_id2\">
<input type=\"hidden\" name=\"sale_id\" id=\"sale_id\" value=\"$row[0]\">

<tr>
<td class=\"modal-titulo\" id=\"titulo$row[0]\" name=\"titulo\" colspan=\"6\">$row[10] - Venta $row[0]</td>
</tr>



<tr>
<td class=\"modal-label\">Cant.</td>
<td class=\"modal-label\">Producto</td>
<td class=\"modal-label\">Precio</td>
<td class=\"modal-label\">Subtotal</td>
<td class=\"modal-label\">Imp.</td>
<td class=\"modal-label\">Total</td>
</tr>
";


//$q = "select shp.id, shp.quantity, substr(p.name,1,30),shp.price, round(shp.quantity*shp.price,2), round(shp.quantity*((shp.tax/100)*(shp.price)),2) from sale_has_product as shp join product as p on shp.product_id = p.id  where shp.sale_id = $row[0]";

$q = "
select 
 shp.id,
 shp.quantity,
 substr(p.name,1,30),
 round(shp.price/(1+(shp.tax/100)),2),
 round(shp.quantity*(shp.price/(1+(shp.tax/100))),2), 
 round(shp.quantity*(shp.price/(1+(shp.tax/100))*shp.tax/100),2),
 product_id
from sale_has_product as shp join product as p on shp.product_id = p.id where shp.sale_id = $row[0]";

//update sale set total_s_tax = (select round(sum(quantity*((price/(1+(tax/100))))),2)           from sale_has_product where sale_id = 1) where id = 1;
//update sale set tax         = (select round(sum(quantity*((price/(1+(tax/100)))*tax/100)),2)


$o_database->query_rows($q);
$result1 = $o_database->query_result;

while($row1 = mysql_fetch_row($result1))
  {

    $total = round($row1[4]+$row1[5],2);

echo "
<tr>
<td class=\"modal-input-flex\">
<input value=\"$row1[1]\" class=\"a-input-flex-editable\" name=\"$row1[6]\" id=\"quantity$row1[0]\" type=\"number\" title=\"Cantidad de Producto.\" data-message=\"Cantidad de Producto.\" >
</td>

<td class=\"modal-input-flex\">
<input value=\"$row1[2]\" class=\"a-input-flex\" name=\"p_name$row1[0]\" id=\"name$row1[0]\" type=\"text\" disabled>
</td>

<td class=\"modal-input-flex\">
<input value=\"$row1[3]\" class=\"a-input-flex\" name=\"precio$row1[0]\" id=\"precio$row1[0]\" type=\"number\" disabled>
</td>

<td class=\"modal-input-flex\">
<input value=\"$row1[4]\" class=\"a-input-flex\" name=\"s_total$row1[0]\" id=\"s_total$row1[0]\" type=\"number\" disabled>
</td>

<td class=\"modal-input-flex\">
<input value=\"$row1[5]\" class=\"a-input-flex\" name=\"tax$row1[0]\" id=\"tax$row1[0]\" type=\"number\" disabled>
</td>

<td class=\"modal-input-flex\">
<input value=\"$total\" class=\"a-input-flex\" name=\"total$row1[0]\" id=\"total$row1[0]\" type=\"number\" disabled>
</td>
</tr>

";


    /*
+------------+-----------------------+------+-----+---------+----------------+
| Field      | Type                  | Null | Key | Default | Extra          |
+------------+-----------------------+------+-----+---------+----------------+
| id         | int(10) unsigned      | NO   | PRI | NULL    | auto_increment |
| sale_id    | smallint(5) unsigned  | NO   | MUL | NULL    |                |
| product_id | bigint(20) unsigned   | NO   | MUL | NULL    |                |
| quantity   | int(10) unsigned      | NO   |     | NULL    |                |
| price      | decimal(8,2) unsigned | NO   |     | NULL    |                |
| tax        | decimal(4,2) unsigned | NO   |     | NULL    |                |
+------------+-----------------------+------+-----+---------+----------------+
6 rows in set (0.01 sec)
    */




  }

echo "

<tr><td class=\"modal-separador\" colspan=\"6\"></td></tr>
</table>
";
echo"
<table class=\"modal-captura\">
<form id=\"myform$row[0]\">
<input type=\"hidden\" id=\"product_id$row[0]\" name=\"product_id$row[0]\">
<tr>

<td class=\"modal-label-ac\">Teclea el ID o nombre del producto</td>

<td class=\"modal-input\">
  <input class=\"a-input\" name=\"id_b$row[0]\" id=\"id_b$row[0]\" title=\"Nombre o ID.\" required=\"required\" data-message=\"Nombre o ID.\"></td>


<td class=\"modal-label\">Cantidad</td>

<td class=\"modal-input-flex\">
  <input disabled class=\"a-input-flex\" name=\"new_quantity$row[0]\" id=\"new_quantity$row[0]\" type=\"number\" required=\"required\"  title=\"Cantidad de Producto.\" data-message=\"Cantidad de Producto.\"></td>
</tr>
</form>
</table>
";

echo"
<table class=\"modal-captura\">

<tr><td class=\"modal-separador\" colspan=\"6\"></td></tr>




<tr>
<td class=\"modal-label\">Subtotal</td>
<td class=\"modal-input-flex\">
  <input class=\"a-input-flex\" name=\"total_s_tax$row[0]\" id=\"total_s_tax$row[0]\" type=\"number\" value=\"$total_s_tax\" disabled></td>
<td class=\"modal-label\">Impuestos</td>
<td class=\"modal-input-flex\">
  <input class=\"a-input-flex\" name=\"tax$row[0]\" id=\"tax$row[0]\" type=\"number\" value=\"$tax\" disabled></td>
<td class=\"modal-label\">Total</td>
<td class=\"modal-input-flex\">
  <input class=\"a-input-flex\" name=\"total_c_tax$row[0]\" id=\"total_c_tax$row[0]\" type=\"number\" value=\"$total_c_tax\" disabled></td>
</tr>


<tr>
<td class=\"modal-label\">Pago</td>
<td class=\"modal-input-flex\">";
create_select("select id, name from payment_type order by id", "payment_type_id$row[0]", $row[4], "onChange=\"checkCard('$row[0]')\"", $o_database, "a-select");

echo"
<td class=\"modal-label\">Tarjeta</td>
<td class=\"modal-input-flex\">";
create_select("select id, name from card order by id", "card_id$row[0]", $row[5], null, $o_database, "a-select");
echo"
<td class=\"modal-label\">Comentario</td>
<td class=\"modal-input-flex\">
  <input class=\"a-input-flex\" name=\"comment$row[0]\" id=\"comment$row[0]\" type=\"text\"  maxlength=\"16\" title=\"Comentario/No. de tarjeta.\" data-message=\"Comentario/No. de tarjeta.\"></td>
</tr>

<tr><td class=\"modal-separador\" colspan=\"6\"></td></tr>
<tr>

<td colspan=\"2\" class=\"modal-label\"><button type=\"submit\" onclick=\"cierraVenta('$row[0]', '$row[10]')\" class=\"modal-boton-cerrar\" value=\"Cerrar Venta\">Cerrar Venta</button></td>

<td colspan=\"2\" class=\"modal-label\"><button type=\"button\" onclick=\"cierraOverlay()\" class=\"modal-boton\">Cerrar Ventana</button></td>

<td colspan=\"2\" class=\"modal-label\"><button type=\"button\" onclick=\"cancelaVenta('$row[0]', '$row[10]')\" class=\"modal-boton-cancelar\" value=\"Cancelar Venta\">Cancelar Venta</button></td>



</tr>
";


echo"
</table>
</div>
";
  }

echo "<script>

$('[id^=myform]').validator({
    singleError: false,
    position: \"bottom center\"
});


$('[id^=new_quantity]').tooltip();
//$('[id^=quantity]').tooltip();



//$(function() {

$(document).ready(function() {
$(\"button[rel]\").each(function(i) {

  $(this).overlay({
  // disable this for modal dialog-type of overlays
     closeOnClick: false,
     closeOnEsc:   false,
     top:   110,
     left:  215,
     fixed: false,
     api:   true,
     onLoad: function() {
        //$(\"#nombre_b\").focus(); 
        var t = $(\"div.modal\").eq(i).attr(\"id\");
        var x = $(\"#payment_type_id\"+t).val();
        checkCard(t);
        //$(\"#titulo\"+t).html(t);
        //alert(t);
     },
     //onBeforeClose: function() {
     //   $(\"#myform_b\").data(\"validator\").reset();
     //},
     mask: {
       color: '#ffffff',
       loadSpeed: 200,
       opacity: 0.6 }
     });
  });


$('[id^=id_b]').each(function(i, el) {
 el = $(el);
 el.autocomplete({
//$('#id_b').autocomplete({
         source:  function (a,b)
                 {
                    //id = $(\"#id_b1\").val();
                    id = $(el).val();
                    //id = this.value;
                    //id = 'cu';
                    //alert(id);
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
                    var a = $(el).closest(\"div\").attr(\"id\");
                    //alert(product.item.id);
                    $(\"#new_quantity\"+a).prop('disabled', false); 
                    $(\"#new_quantity\"+a).val(\"1\"); 
                    $(\"#new_quantity\"+a).focus();
                    $(\"#product_id\"+a).val(product.item.id); 
                  },
/*
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
                  },*/
        
        open:   function(event, ui) {
            $(\".ui-autocomplete\").css(\"z-index\", 1000000);
           }
});


});

var ov_paracargar = $(\"#$ovly\").data(\"overlay\");
ov_paracargar.load();

});


function checkCard(id)
{
 var x = $(\"#payment_type_id\"+id).val();

 if(x == 2)
   $(\"#card_id\"+id).prop('disabled', false);
 else
   $(\"#card_id\"+id).prop('disabled', true);
}

function cierraOverlay()
{
 $(\"button[rel]\").each(function() {
    $(this).overlay().close();
  });
}

// shp_id, product_id, quantity

$('[id^=new_quantity]').bind('keypress', function(e) {

 var sale_id    = $(this).closest(\"div\").attr(\"id\");
 var product_id = $(\"#product_id\" + sale_id).val();
 var quantity   = $(\"#new_quantity\" + sale_id).val()

 var code = e.keyCode || e.which;
 if(code == 13) {
   //alert(quantity);
   window.open('./util/AnadeProductoVenta.php?sale_id=' +  sale_id + '&product_id=' + product_id + '&quantity=' + quantity + '&cs1=' + $o_config->control_structure_id1 + '&cs2=' + $o_config->control_structure_id2,'_self');
 }

 if(code == 38) {
 quantity = parseInt(quantity) + parseInt(1);
 $(\"#new_quantity\" + sale_id).val(quantity);
}

 if(code == 40) {
 quantity = parseInt(quantity) - parseInt(1);
 $(\"#new_quantity\" + sale_id).val(quantity)
}


});


$('[id^=quantity]').each(function(i) {
  $(this).bind('keypress', function(e) {
    $(this).attr(\"class\", \"a-input-flex-editing\");
    var shp_id   = $(this).attr(\"name\");

   //alert(shp_id);
   //return;

   var sale_id    = $(this).closest(\"div\").attr(\"id\");
   var product_id = $(this).attr(\"name\");
   var quantity   = $(this).val()

 var code = e.keyCode || e.which;
 if(code == 13) {
   //alert(quantity);
   window.open('./util/CambiaProductoVenta.php?sale_id=' +  sale_id + '&product_id=' + product_id + '&quantity=' + quantity + '&cs1=' + $o_config->control_structure_id1 + '&cs2=' + $o_config->control_structure_id2,'_self');
 }

 if(code == 38) {
 quantity = parseInt(quantity) + parseInt(1);
 $(this).val(quantity);
}

 if(code == 40) {
 quantity = parseInt(quantity) - parseInt(1);
 $(this).val(quantity)
 }


    
    })
});



$('[id^=quantity]').each(function(i) {
  $(this).bind('blur', function(e) {
   $(this).attr(\"class\", \"a-input-flex-editable\");
   var shp_id   = $(this).attr(\"name\");
   var sale_id    = $(this).closest(\"div\").attr(\"id\");
   var product_id = $(this).attr(\"name\");
   var quantity   = $(this).val()

   window.open('./util/CambiaProductoVenta.php?sale_id=' +  sale_id + '&product_id=' + product_id + '&quantity=' + quantity + '&cs1=' + $o_config->control_structure_id1 + '&cs2=' + $o_config->control_structure_id2,'_self');
   
    })
});

function cancelaVenta(sale_id, identifier)
{
 var r=confirm(\"¿Cancelar [\"+identifier+\"] la Venta \"+sale_id+\"?\");
 if (r==true)
  {
    window.open('./util/CancelaVenta.php?sale_id=' +  sale_id + '&cs1=' + $o_config->control_structure_id1 + '&cs2=' + $o_config->control_structure_id2,'_self');
  }
else
  {
  retutn;
  } 
}


function cierraVenta(sale_id, identifier)
{

 var payment_type_id = $(\"#payment_type_id\"+sale_id).val();
 var card_id = $(\"#card_id\"+sale_id).val();
 var comment = $(\"#comment\"+sale_id).val();
 
 var r=confirm(\"¿Cerrar [\"+identifier+\"] la Venta \"+sale_id+\"?\");
 if (r==true)
  {
    window.open('./util/CierraVenta.php?sale_id=' +  sale_id + '&payment_type_id=' + payment_type_id + '&card_id=' + card_id + '&comment=' + comment + '&cs1=' + $o_config->control_structure_id1 + '&cs2=' + $o_config->control_structure_id2,'_self');
  }
else
  {
  retutn;
  } 
}




</script>
";


 ?>