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
<input type=\"hidden\" name=\"supplier_id\" id=\"supplier_id\">


<tr>
<td class=\"modal-titulo\">Nueva Compra</td>
</tr>

<tr>
<td class=\"modal-label-ac\">Teclea Nombre del Proveedor</td>
</tr>
<tr>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"nombre\" id=\"nombre\" type=\"text\" required=\"required\"   maxlength=\"255\" title=\"Nombre del Proveedor.\" data-message=\"Nombre del Proveedor.\"></td>
</tr>

<tr>
<td class=\"modal-label\">Identificador</td>
</tr>
<tr>
<td class=\"modal-input\">
  <input class=\"a-input\" name=\"identifier\" id=\"identifier\" type=\"text\"  maxlength=\"16\" title=\"Identificador.\" data-message=\"Identificador.\"></td>
</tr>
<tr>
<td class=\"modal-label\"><button type=\"submit\" onFocus=\"\" onBlur=\"\" class=\"modal-boton-aceptar\" formaction=\"util/NuevaCompra.php\" formmethod=\"POST\" value=\"Aceptar\">Aceptar</button></td>
</tr>
<tr>
<td class=\"modal-label\"><button type=\"reset\"  onclick=\"limpiaOverlay()\" class=\"modal-boton\">Limpiar</button></td>
</tr>
</table>
</form>

</td>
<td class=\"a-menu-contenido\"><div class=\"a-opcion\" rel=\"#buscr\">
<table class=\"a-ventas\">
";
$q = "select p.id, p.identifier, s.name from purchase as p join supplier as s on s.id = p.supplier_id where status_purchase_id = 1";
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
      $etiqueta = "Compra $row[0] ($row[2])";
    else
      $etiqueta = "$row[1] ($row[2])";

    //echo "<td class=\"a-ventas\"><button rel=\"div.modal:eq($div_actual)\" class=\"a-ventas\" id=\"$row[0]\"\">$etiqueta</button></td>";
    echo "<td class=\"a-ventas\"><button rel=\"div.modal:eq($eq)\" class=\"a-compras\" id=\"$row[0]\">$etiqueta</button></td>";


    
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

$q = "select p.*, s.name, s.id from purchase as p join supplier as s on s.id = p.supplier_id where status_purchase_id = 1";
$o_database->query_rows($q);
$result = $o_database->query_result;
while($row = mysql_fetch_row($result))
  {
    /*
+--------------------+-----------------------+------+-----+---------+----------------+
| Field              | Type                  | Null | Key | Default | Extra          |
+--------------------+-----------------------+------+-----+---------+----------------+
| id                 | smallint(5) unsigned  | NO   | PRI | NULL    | auto_increment |
| user_id            | tinyint(3) unsigned   | NO   | MUL | NULL    |                |
| supplier_id        | smallint(5) unsigned  | NO   | MUL | NULL    |                |
| status_purchase_id | tinyint(3) unsigned   | NO   | MUL | 1       |                |
| total_s_tax        | decimal(8,2) unsigned | NO   |     | 0.00    |                |
| total_c_tax        | decimal(8,2) unsigned | NO   |     | 0.00    |                |
| tax                | decimal(8,2) unsigned | NO   |     | 0.00    |                |
| date               | date                  | NO   |     | NULL    |                |
| identifier         | varchar(16)           | YES  |     | NULL    |                |
| comment            | varchar(255)          | YES  |     | NULL    |                |
+--------------------+-----------------------+------+-----+---------+----------------+
10 rows in set (0.00 sec)

   */

$total_s_tax =  number_format($row[4], 2, '.', ',');
$total_c_tax =  number_format($row[5], 2, '.', ',');
$tax         =  number_format($row[6], 2, '.', ',');

echo "

<div class=\"modal\" id=\"$row[0]\">

<table class=\"modal-captura\">

<input type=\"hidden\" name=\"cs1\" value=\"$o_config->control_structure_id1\">
<input type=\"hidden\" name=\"cs2\" value=\"$o_config->control_structure_id2\">
<input type=\"hidden\" name=\"purchase_id\" id=\"purchase_id\" value=\"$row[0]\">
<input type=\"hidden\" name=\"supplier_id\" id=\"supplier_id\" value=\"$row[11]\">

<tr>
<td class=\"modal-titulo\" id=\"titulo$row[0]\" name=\"titulo\" colspan=\"6\">$row[10] - $row[8] - Compra $row[0]</td>
</tr>



<tr>
<td class=\"modal-label\">Cant.</td>
<td class=\"modal-label\">Producto</td>
<td class=\"modal-label\">Costo</td>
<td class=\"modal-label\">Subtotal</td>
<td class=\"modal-label\">Imp.</td>
<td class=\"modal-label\">Total</td>
</tr>
";
/*
+-------------+-----------------------+------+-----+---------+----------------+
| Field       | Type                  | Null | Key | Default | Extra          |
+-------------+-----------------------+------+-----+---------+----------------+
| id          | int(10) unsigned      | NO   | PRI | NULL    | auto_increment |
| purchase_id | smallint(5) unsigned  | NO   | MUL | NULL    |                |
| product_id  | bigint(20) unsigned   | NO   | MUL | NULL    |                |
| quantity    | int(10) unsigned      | NO   |     | NULL    |                |
| cost        | decimal(8,2) unsigned | NO   |     | NULL    |                |
| tax         | decimal(4,2) unsigned | NO   |     | 0.00    |                |
+-------------+-----------------------+------+-----+---------+----------------+
6 rows in set (0.00 sec)
*/

$q = "
select 
 php.id,
 php.quantity,
 substr(p.name,1,30),
 round(php.cost/(1+(php.tax/100)),2),
 round(php.quantity*(php.cost/(1+(php.tax/100))),2), 
 round(php.quantity*(php.cost/(1+(php.tax/100))*php.tax/100),2),
 product_id
 from purchase_has_product as php join product as p on php.product_id = p.id where php.purchase_id = $row[0]";

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
<input value=\"$row1[3]\" class=\"a-input-flex\" name=\"cost\" id=\"cost$row1[0]\" type=\"number\" disabled>
</td>

<td class=\"modal-input-flex\">
<input value=\"$row1[4]\" class=\"a-input-flex\" name=\"s_total$row1[0]\" id=\"s_total$row1[0]\" type=\"number\" disabled>
</td>

<td class=\"modal-input-flex\">
<input value=\"$row1[5]\" class=\"a-input-flex\" name=\"tax\" id=\"tax$row1[0]\" type=\"number\" disabled>
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

<td class=\"modal-label-ac\">Producto</td>

<td class=\"modal-input\">
  <input class=\"a-input\" name=\"id_b$row[0]\" id=\"id_b$row[0]\" title=\"Nombre o ID.\" required=\"required\" data-message=\"Nombre o ID.\"></td>


<td class=\"modal-label\">Cantidad</td>
<td class=\"modal-input-flex\">
  <input disabled class=\"a-input-flex\" name=\"new_quantity$row[0]\" id=\"new_quantity$row[0]\" type=\"number\" required=\"required\"  title=\"Cantidad de Producto.\" data-message=\"Cantidad de Producto.\"></td>

<td class=\"modal-label\">Costo</td>
<td class=\"modal-input-flex\">
  <input disabled class=\"a-input-flex\" name=\"new_cost$row[0]\" id=\"new_cost$row[0]\" type=\"number\" required=\"required\"  title=\"Costo Neto del Producto.\" data-message=\"Costo Neto del Producto.\"></td>

<td class=\"modal-label\">Impuesto (%)</td>
<td class=\"modal-input-flex\">
  <input disabled class=\"a-input-flex\" name=\"new_tax$row[0]\" id=\"new_tax$row[0]\" type=\"number\"  title=\"Impuesto en Porcentaje.\" data-message=\"Impuesto en Porcentaje.\"></td>
</tr>



</form>
</table>
";

echo"
<table class=\"modal-captura\">

<tr><td class=\"modal-separador\" colspan=\"6\"></td></tr>

<tr>
<td class=\"modal-label\" colspan=\"3\" >Comentario</td>
<td class=\"modal-input-flex\" colspan=\"3\">
  <input class=\"a-input-flex\" name=\"comment$row[0]\" id=\"comment$row[0]\" type=\"text\"  maxlength=\"255
\" title=\"Comentario\" data-message=\"Comentario.\"></td>
</tr>

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


<tr><td class=\"modal-separador\" colspan=\"6\"></td></tr>
<tr>

<td colspan=\"2\" class=\"modal-label\"><button type=\"submit\" onclick=\"cierraCompra('$row[0]', '$row[10]')\" class=\"modal-boton-cerrar\" value=\"Cerrar Compra\">Cerrar Compra</button></td>

<td colspan=\"2\" class=\"modal-label\"><button type=\"button\" onclick=\"cierraOverlay()\" class=\"modal-boton\">Cerrar Ventana</button></td>

<td colspan=\"2\" class=\"modal-label\"><button type=\"button\" onclick=\"cancelaCompra('$row[0]', '$row[10]')\" class=\"modal-boton-cancelar\" value=\"Cancelar Compra\">Cancelar Compra</button></td>



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
$('[id^=new_cost]').tooltip();
$('[id^=new_tax]').tooltip();


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
     //onLoad: function() {
        //$(\"#nombre_b\").focus(); 
        //var t = $(\"div.modal\").eq(i).attr(\"id\");
        //var x = $(\"#payment_type_id\"+t).val();
        //checkCard(t);
        //$(\"#titulo\"+t).html(t);
        //alert(t);
     //},
     //onBeforeClose: function() {
     //   $(\"#myform_b\").data(\"validator\").reset();
     //},
     mask: {
       color: '#ffffff',
       loadSpeed: 200,
       opacity: 0.6 }
     });
  });


$(\"#nombre\").autocomplete({
         source:  function (a,b)
                 {
                    id = $(\"#nombre\").val();
                    $.post(\"util/json_proveedor.php\",
                           { \"proveedor\" : id, \"all_data\" : \"\" },
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
                   $(\"#supplier_id\").val(supplier.item.id)
                   $(\"#nombre\").val(supplier.item.label);
                   $(\"#nombre\").prop('disabled', true);
                  },
        
        open:   function(event, ui) {
            $(\".ui-autocomplete\").css(\"z-index\", 1000000);
           }

})





$('[id^=id_b]').each(function(i, el) {
 el = $(el);
 el.autocomplete({
         source:  function (a,b)
                 {
                   //jQuery('#mydiv > select[name=myselect] > option:selected')
                   //  $('div:hidden #some-field').val();
                    var a = $(el).closest(\"div\").attr(\"id\");
                    
                    p_id = $(el).val();
                    s_id = $(\"div[id=\"+a+\"] input[id=supplier_id]\").val();
                    //s_id = $(\"div[id=1] supplier_id\").val();
                    $.post(\"util/json_producto_comprar.php\",
                           { \"p_id\" : p_id, \"s_id\" : s_id },
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
                    //alert(a);
                    $(\"#product_id\"+a).val(product.item.id);
                    $(\"#new_quantity\"+a).prop('disabled', false);
                    $(\"#new_quantity\"+a).focus();
                    $(\"#new_cost\"+a).prop('disabled', false);
                    $(\"#new_tax\"+a).prop('disabled', false);
                  },

        
        open:   function(event, ui) {
            $(\".ui-autocomplete\").css(\"z-index\", 1000000);
           }
});

});

var ov_paracargar = $(\"#$ovly\").data(\"overlay\");
ov_paracargar.load();

});


function cierraOverlay()
{
 $(\"button[rel]\").each(function() {
    var purchase_id = $(this).attr(\"id\");
    $(\"#id_b\" + purchase_id).val(\"\");
    $(\"#product_id\" + purchase_id).val(\"\");
    $(\"#new_quantity\" + purchase_id).val(\"\");
    $(\"#new_cost\" + purchase_id).val(\"\");
    $(\"#new_tax\" + purchase_id).val(\"\");
    $(\"#product_id\" + purchase_id).prop('disabled', false);
    //alert(purchase_id);
    $(this).overlay().close();
  });
/*
 $(\"div[rel]\").each(function() {
    $(this).overlay().close();
  });
*/

}



$('[id^=new_quantity],[id^=new_cost],[id^=new_tax]').bind('keypress', function(e) {

   var purchase_id = $(this).closest(\"div\").attr(\"id\");
   var product_id  = $(\"#product_id\" + purchase_id).val();
   var quantity    = $(\"#new_quantity\" + purchase_id).val()
   var cost        = $(\"#new_cost\" + purchase_id).val()
   var tax         = $(\"#new_tax\" + purchase_id).val()


  var code = e.keyCode || e.which;
  if(code == 13) {

   if(empty(quantity) || parseInt(quantity) <= 0)
     {
       alert(\"Valor de la cantidad inválido\");
       retutn;
     }

   if(empty(cost) || parseInt(cost) <= 0)
     {
       alert(\"Valor del costo inválido\");
       retutn;
     }

  if(parseInt(tax) <= 0)
     {
       alert(\"Valor del costo inválido\");
       retutn;
     }

   window.open('./util/AnadeProductoCompra.php?purchase_id=' +  purchase_id + '&product_id=' + product_id + '&quantity=' + quantity + '&cost=' + cost + '&tax=' + tax + '&cs1=' + $o_config->control_structure_id1 + '&cs2=' + $o_config->control_structure_id2,'_self');
 }
/*
 if(code == 38) {
 quantity = parseInt(quantity) + parseInt(1);
 $(\"#new_quantity\" + purchase_id).val(quantity);
}

 if(code == 40) {
 quantity = parseInt(quantity) - parseInt(1);
 $(\"#new_quantity\" + purchase_id).val(quantity)
}
*/

});


$('[id^=quantity]').each(function(i) {
  $(this).bind('keypress', function(e) {
    $(this).attr(\"class\", \"a-input-flex-editing\");
    var shp_id   = $(this).attr(\"name\");

   //alert(shp_id);
   //return;

   var purchase_id = $(this).closest(\"div\").attr(\"id\");
   var cost        = $(\"div[id=\"+purchase_id+\"] input[id=cost]\").val();
   var tax         = $(\"div[id=\"+purchase_id+\"] input[id=tax]\").val();
   var product_id  = $(this).attr(\"name\");
   var quantity    = $(this).val()

 var code = e.keyCode || e.which;
 if(code == 13) {
   //alert(quantity);
   window.open('./util/CambiaProductoCompra.php?purchase_id=' +  purchase_id + '&product_id=' + product_id + '&quantity=' + quantity + '&cost=' + cost + '&tax=' + tax +'&cs1=' + $o_config->control_structure_id1 + '&cs2=' + $o_config->control_structure_id2,'_self');
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
    $(this).attr(\"class\", \"a-input-flex-editing\");
    var shp_id   = $(this).attr(\"name\");

   //alert(shp_id);
   //return;

   var purchase_id = $(this).closest(\"div\").attr(\"id\");
   var cost        = $(\"div[id=\"+purchase_id+\"] input[id=cost]\").val();
   var tax         = $(\"div[id=\"+purchase_id+\"] input[id=tax]\").val();
   var product_id  = $(this).attr(\"name\");
   var quantity    = $(this).val()

   window.open('./util/CambiaProductoCompra.php?purchase_id=' +  purchase_id + '&product_id=' + product_id + '&quantity=' + quantity + '&cost=' + cost + '&tax=' + tax +'&cs1=' + $o_config->control_structure_id1 + '&cs2=' + $o_config->control_structure_id2,'_self');
    
    })
});





function cancelaCompra(purchase_id, identifier)
{
 var r=confirm(\"¿Cancelar [\"+identifier+\"] la Venta \"+purchase_id+\"?\");
 if (r==true)
  {
    window.open('./util/CancelaCompra.php?purchase_id=' +  purchase_id + '&cs1=' + $o_config->control_structure_id1 + '&cs2=' + $o_config->control_structure_id2,'_self');
  }
else
  {
  retutn;
  } 
}


function cierraCompra(purchase_id, identifier)
{

 var payment_type_id = $(\"#payment_type_id\"+purchase_id).val();
 var comment = $(\"#comment\"+purchase_id).val();
 
 var r=confirm(\"¿Cerrar [\"+identifier+\"] la Compra \"+purchase_id+\"?\");
 if (r==true)
  {
    window.open('./util/CierraCompra.php?purchase_id=' +  purchase_id + '&comment=' + comment + '&cs1=' + $o_config->control_structure_id1 + '&cs2=' + $o_config->control_structure_id2,'_self');
  }
else
  {
  retutn;
  } 
}



function limpiaOverlay()
{
 $(\"#myform\").data(\"validator\").reset();
 $(\"#supplier_id\").val()
 $(\"#nombre\").val();
 $(\"#identifier\").val();
 $(\"#nombre\").prop('disabled', false);
}




function empty (mixed_var) {
  var undef, key, i, len;
  var emptyValues = [undef, null, false, 0, \"\", \"0\"];

  for (i = 0, len = emptyValues.length; i < len; i++) {
    if (mixed_var === emptyValues[i]) {
      return true;
    }
  }

  return false;
}


</script>
";


 ?>