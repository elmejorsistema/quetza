<?php

// Busca el primer cs de alto nivel para el usuario
// (menú principal)
// Busca el primer cs de segundo nivel para el usuario
// (submenú)
function first_cs_for_user($user_id, $o_database)
{
  $q = "select cs.id from control_structure as cs join user_has_control_structure as uhcs on cs.id = uhcs.control_structure_id where uhcs.user_id = $user_id and cs.control_structure_id is null order by cs.sequence asc limit 1";
  $o_database->query_fetch_field($q);
  $cs1 = $o_database->query_field;
  
  $q = "select cs.id from control_structure as cs join user_has_control_structure as uhcs on cs.id = uhcs.control_structure_id where uhcs.user_id = $user_id and cs.control_structure_id = $cs1 order by cs.sequence asc limit 1";
  $o_database->query_fetch_field($q);
  $cs2 = $o_database->query_field;

  return array($cs1, $cs2);
}



// Busca el primer cs de segundo nivel para el usuario
// dado un cs de alto nivel
function first_cs2_for_user($user_id, $o_database, $cs1)
{
  $q = "select cs.id from control_structure as cs join user_has_control_structure as uhcs on cs.id = uhcs.control_structure_id where uhcs.user_id = $user_id and cs.control_structure_id = $cs1 order by cs.sequence asc limit 1";
  $o_database->query_fetch_field($q);
  $cs2 = $o_database->query_field;

  return $cs2;
}

// Verifica que el usuario tiene acceso a ambos menús
function checkCheckMenu($user_id, $o_config, $o_database)
{
  $q = "select cs.id from control_structure as cs join user_has_control_structure as uhcs on cs.id = uhcs.control_structure_id where uhcs.user_id = $user_id and cs.control_structure_id is null and  $o_config->control_structure_id1 = cs.id";
  $o_database->query_fetch_field($q);
  $cs1 = $o_database->query_field;
  if(!$cs1)
    return false;

  $q = "select cs.id from control_structure as cs join user_has_control_structure as uhcs on cs.id = uhcs.control_structure_id where uhcs.user_id = $user_id and cs.control_structure_id = $cs1 and  $o_config->control_structure_id2 = cs.id";
  $o_database->query_fetch_field($q);
  $cs2 = $o_database->query_field;
  if(!$cs2)
    return false;

  return true;
}


function get_lugar_menu($cs1, $cs2, $o_user, $o_database)
{
$renglones = 0;
$q = "select cs.id from user_has_control_structure as uhcs join control_structure as cs on cs.id = uhcs.control_structure_id where cs.control_structure_id =$cs1 and uhcs.user_id = $o_user order by cs.sequence";
$o_database->query_rows($q);
$result = $o_database->query_result;
foreach($result as $row)
  {
    $renglones ++;
    if($row[0] == $cs2)
      $lugar = $renglones;
  }

return array($renglones, $lugar);

}

function validate_EAN13Barcode($barcode)
{
  // check to see if barcode is 13 digits long
  if(!preg_match("/^[0-9]{13}$/",$barcode)) {
    return false;
  }

  $digits = (string) $barcode;


  // 1. Add the values of the digits in the even-numbered positions: 2, 4, 6, etc.
  $even_sum = $digits[1] + $digits[3] + $digits[5] + $digits[7] + $digits[9] + $digits[11];
  // 2. Multiply this result by 3.
  $even_sum_three = $even_sum * 3;
  // 3. Add the values of the digits in the odd-numbered positions: 1, 3, 5, etc.
  $odd_sum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];
  // 4. Sum the results of steps 2 and 3.
  $total_sum = $even_sum_three + $odd_sum;
  // 5. The check character is the smallest number which, when added to the result in step 4, produces a multiple of 10.
  $next_ten = (ceil($total_sum/10))*10;
  $check_digit = $next_ten - $total_sum;

  // if the check digit and the last digit of the barcode are OK return true;
  if($check_digit == $digits[12]) {
    return true;
  }


return false;
}

function generate_EAN13BarcodeFromSingle($barcode)
{

 
  // check to see if barcode are digits
  if(!preg_match("/^[0-9]+$/",$barcode)) {
    return false;
  }
  
 
  $s_barcode = (string) $barcode;

  $len = strlen($s_barcode);

  if($len > 12)
    return false;
  else
    {
      if($len == 12)
	if($s_barcode[0] != "1")
	  return false;
    }

  $i = 12-$len; 
  $uno = true;
  $b = null;
  while($i > 0)
    {
      if($uno)
	{
	  $uno = false;
	  $b .= "1";
	}
      else
	$b .= "0";
      $i--;
    }

  $b .= $s_barcode;

  return (int) generate_EAN13Barcode($b);
}



function generate_EAN13Barcode($barcode)
{
  // check to see if barcode is 13 digits long
  if(!preg_match("/^[0-9]{12}$/",$barcode)) {
    return false;
  }

  $digits = (string) $barcode;
  

  // 1. Add the values of the digits in the even-numbered positions: 2, 4, 6, etc.
  $even_sum = $digits[1] + $digits[3] + $digits[5] + $digits[7] + $digits[9] + $digits[11];
  // 2. Multiply this result by 3.
  $even_sum_three = $even_sum * 3;
  // 3. Add the values of the digits in the odd-numbered positions: 1, 3, 5, etc.
  $odd_sum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];
  // 4. Sum the results of steps 2 and 3.
  $total_sum = $even_sum_three + $odd_sum;
  // 5. The check character is the smallest number which, when added to the result in step 4, produces a multiple of 10.
  $next_ten = (ceil($total_sum/10))*10;
  $check_digit = $next_ten - $total_sum;
  
  return (string) $digits.$check_digit;



}



function especifica_alumno($m)
{
echo "
<div class=\"especifica\">
<p class=\"titulo\"> Especifica Alumna/o </p>
<form id=\"especificaalumna\" method=\"post\">
<input type=\"hidden\" name=\"alumno_id\" id=\"alumno_id\">
<input type=\"hidden\" name=\"menu\" value=\"$m\">
<table class=\"forma\">
";

echo "

<tr>
<td class=\"rotulo-autocomplete\">Nombre de la alumna/o</td>
</tr>

<tr>
<td class=\"rotulo-entrada\"><input class=\"autocomplete\" type=\"text\" maxlength=\"32\" required id=\"nombre\" autofocus></td>


</tr></table>
</form>
</div>

";

echo "<script>
$(document).ready(function() {

//alert('hola1');

$(\"#nombre\").autocomplete({
         source:  function (a,b)
                 {
                    ;
                    id = $(\"#nombre\").val();
                    //alert('hola2');
                    $.post(\"util/json_alumno.php\",
                           { \"alumno\" : id, \"all_data\" : \"\" },
                           function(data){
                               // alert('hola3');
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

         select: function(event, alumno)
                  {
                   $(\"#alumno_id\").val(alumno.item.id);
                   $(\"#nombre\").val(alumno.item.label);
                   $(\"#nombre\").prop('disabled', true);
                   $(\"#especificaalumna\").attr('action', './util/19.php');
                   $(\"#especificaalumna\").submit();
                  },
        
        open:   function(event, ui) {
            $(\".autocomplete\").css(\"z-index\", 1000000);
           }

   })
//alert('hola1');


});
</script>
";

return;

}




//Create the list from data in DB, selecting the field
function create_select($query, $name, $selected, $evento, $o_db, $class){
  $o_db->query_rows($query);
  //echo $query;
  //return;

  echo"<select class=\"$class\" name=\"$name\" id=\"$name\" $evento>";
  foreach($o_db->query_result as $result_row){
    $val = $result_row[0];
    $label = $result_row[1];

    if($selected==$val)
     echo "<option value=\"$val\" selected>$label</option>";
    else
     echo "<option value=\"$val\" >$label</option>";
  }
  echo "</select>";
}



//Create the list from data in DB, selecting the field
function create_select_json($query, $a_names, $name, $selected, $evento, $o_db, $class){
  $o_db->query_rows($query);

  echo"<select required class=\"$class\" name=\"$name\" id=\"$name\" $evento>";
  echo " <option disabled selected value> -- Selecciona un Pago -- </option>";
  $contador  = 0;
  $ciclo_ant = null;
  $tipo_ant  = null;
  foreach($o_db->query_result as $result_row){

    if($contador > 0){
      $ciclo_ant = $ciclo;
      $tipo_ant  = $tipo;
    }
   
    $val0  = $result_row[0];
    $val1  = $result_row[1];
    $label = $result_row[2];
    $ciclo = $result_row[3];
    $tipo  = $result_row[4];

    $contador++;
    if(($ciclo_ant != $ciclo or $tipo_ant != $tipo) and $contador !=1)
      echo " <option disabled value> -- Cambio de Semestre -- </option>";

    if($selected==$val0)
      echo "<option value='{\"$a_names[0]\":$val0,\"$a_names[1]\":$val1}' selected>$label</option>";
    else
      echo "<option value='{\"$a_names[0]\":$val0,\"$a_names[1]\":$val1}'>$label</option>";

  }
  echo "</select>";
}




function addSpaces($s, $len, $lado)
{
  $regresa = $s;
  $i = $len - mb_strlen($s, mb_detect_encoding($s));
  //echo   mb_detect_encoding($s)."  ".mb_strlen($s, mb_detect_encoding($s))."  ".strlen($s)."<br />";
  while($i > 0)
    {
      switch($lado)
	{
	case 0:
	  $regresa = " ".$regresa;
	  break;
	default:
	  $regresa = $regresa." ";
	  break;	
	}
      $i--;
    }
  return $regresa;
}



function imirimeTicket($sale_id, $o_database, $o_user)
{

  $path  = "/tmp/";
  $archivo  = "T-".$o_user->id.".txt";
  $archivo1 = fopen("$path$archivo", "w");

  $q = "select date from sale where id = $sale_id";
  $o_database->query_fetch_field($q);
  $fecha = $o_database->query_field;
  
  $ticket = "\n$fecha - [$sale_id]\n\n";

  $q = "select shp.quantity, substr(p.name,1,22), round(shp.quantity*(shp.price/(1+(shp.tax/100))),2)+ round(shp.quantity*(shp.price/(1+(shp.tax/100))*shp.tax/100),2) from sale_has_product as shp join product as p on shp.product_id = p.id  where shp.sale_id = $sale_id";
  $o_database->query_rows($q);
  $result = $o_database->query_result;
  
  foreach($result as $row)
    {
      $quantity = addSpaces($row[0],  3, 0);
      $nombre   = addSpaces($row[1], 22, 1);
      $precio   = addSpaces(number_format($row[2],2,".",","), 9, 0);
      
      $ticket .= $quantity." ".$nombre." $".$precio."\n";
    }
  $ticket .= "-------------------------------------\n";
  $ticket .= "Total                      $";
  
  $q = "select total_c_tax from sale where id = $sale_id";
  $o_database->query_fetch_field($q);
  $ticket .= addSpaces(number_format($o_database->query_field,2,".",","), 9, 0)."\n\n";
  
  $ticket .= "GRACIAS POR SU COMPRA";
  
  fwrite($archivo1,$ticket);
  fclose($archivo1);

  exec("lpr -o cpi=20 -o lpi=8 $path$archivo");
}


//setsebool -P httpd_can_network_connect 1


function imprimeCorteDeCaja($fecha, $o_database, $o_user)
{

  $path  = "/tmp/";
  $archivo = "CC-".$o_user->id.".txt";
  $archivo1 = fopen("$path$archivo", "w");
  echo $archivo;
  $ticket  = "=====================================\n";
  $ticket .= "\n";
  $ticket .= "$fecha - [Corte de Caja]\n";
  $ticket .= "=====================================\n";

  $q = "select id, name from payment_type";
  $o_database->query_rows($q);
  $result = $o_database->query_result;
  $total0 = 0;
  foreach($result as $row)
    {
      $ticket .= "*************************************\n";
      $ticket .= "$row[1]\n";
      $ticket .= "-------------------------------------\n";
      $qq = "select s.id, substr(pt.name,1,5), if(pt.id=2, c.name, null), s.total_c_tax from sale as s join payment_type as pt on s.payment_type_id = pt.id join card as c on s.card_id = c.id where pt.id = $row[0] and status_sale_id = 2 and date(date)='$fecha'";
      $o_database->query_rows($qq);
      $result1 = $o_database->query_result;
      $total1 = 0;
      foreach($result1 as $row1)
        {
          $id       = addSpaces($row1[0], 4, 0);
          $nombre   = addSpaces($row1[1], 5, 1);
          $tarjeta  = addSpaces($row1[2], 15, 1);
          $precio   = addSpaces(number_format($row1[3],2,".",","), 9, 0);
          $ticket .= $id." ".$nombre." ".$tarjeta." $".$precio."\n";
          $total1 += $row1[3];
        }
  $ticket .= "-------------------------------------\n";
  $ticket .= "SubTotal                   $".addSpaces(number_format($total1,2,".",","), 9, 0)."\n";
  $total0 += $total1;
    }
  $ticket .= "*************************************\n";
  $ticket .= "=====================================\n";
  $ticket .= "Total                      $".addSpaces(number_format($total0,2,".",","), 9, 0)."\n";
  $ticket .= "=====================================\n";
  fwrite($archivo1,$ticket);
  fclose($archivo1);

  exec("lpr -o cpi=20 -o lpi=8 $path$archivo");
}







function getSpanishMonth($month)
{
  switch($month)
    {
    case 1:
      return "enero";
      break;
    case 2:
      return "febrero";
      break;
    case 3:
      return "marzo";
      break;
    case 4:
      return "abril";
      break;
    case 5:
      return "mayo";
      break;
    case 6:
      return "junio";
      break;
    case 7:
      return "julio";
      break;
    case 8:
      return "agosto";
      break;
    case 9:
      return "septiembre";
      break;
    case 10:
      return "octubre";
      break;
    case 11:
      return "noviembre";
      break;
    case 12:
      return "diciembre";
      break;
    }
}







?>