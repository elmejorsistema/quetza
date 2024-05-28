<?php

//Define the control_structure


  //$q = "select chm.id, chm.grupo_id, m.name, u.nombre, u.1_apellido, u.2_apellido from ciclo_has_materia as chm join materia as m on chm.materia_id = m.id join ciclo as c on chm.ciclo_id = c.id join user as u on chm.user_id = u.id where c.id = \"".$o_config->ciclo."\" and c.tipo = \"".$o_config->tipo."\"";

$q = "select chm.id, chm.grupo_id, m.name, u.nombre, u.1_apellido, u.2_apellido from ciclo_has_materia as chm join materia as m on chm.materia_id = m.id join ciclo as c on chm.ciclo_id = c.id and chm.ciclo_tipo = c.tipo join user as u on chm.user_id = u.id where c.id = \"".$o_config->ciclo."\" and c.tipo = \"".$o_config->tipo."\"";



//echo $q;
$o_database->query_rows($q);
$result = $o_database->query_result;
$grupo = null;

$inicio = true;

echo "

<table  id=\"boletas\">";

while($row = mysql_fetch_row($result))
  {


    if($grupo != $row[1])
      {
	/*
	if(!$inicio)
	  echo "</table>";
	else
	  $inicio = false;
	*/

	$grupo = $row[1];
	echo "

<tr><td class=\"separador-grupo\" colspan=\"6\">&nbsp;</tr>";

	//<form rel=\"div.modal\" id=\"bajar-".$row[1]."\" action=\"util/7.php\" method=\"get\"><input type=\"hidden\" name=\"grupo\" value=\"2\"><input type=\"hidden\" name=\"final\" value=\"0\"><button rel=\"div.modal\" type=\"submit\">Boletas</button></form>
	//<a rel=\"div.modal\" class=\"materia\" target=\"_self\" nclick=\"espera($row[1],0)\" ref=\"util/7.php?grupo=".$row[1]."&final=0\">Boletas</a>
        //<a class=\"materia\" target=\"_self\" href=\"util/7.php?grupo=".$row[1]."&final=0\">Boletas</a>
	//<form rel=\"div.modal\" id=\"bajar-".$row[1]."\" action=\"util/7.php\" method=\"get\"><input type=\"hidden\" name=\"grupo\" value=\"".$row[1]."\"><input type=\"hidden\" name=\"final\" value=\"0\"><button rel=\"div.modal\" type=\"submit\">Boletas</button></form>


	echo"<tr><td class=\"grupo\" colspan=\"2\">$row[1]<td  colspan=\"4\" class=\"genera-boleta\">

<span class=\"boleta\">
<a class=\"materia\" target=\"_self\" onclick=\"espera()\" href=\"util/33.php?grupo=".$row[1]."&final=0\">Boletas</a>
</span>
<span class=\"boleta\">
<a class=\"materia\" target=\"_self\" onclick=\"espera()\" href=\"util/33.php?grupo=".$row[1]."&final=1\">Boletas Finales</a>
</span>

</td></tr>";



//echo"<tr><td class=\"grupo\" colspan=\"2\">$row[1]<td  colspan=\"4\" class=\"genera-boleta\"><span class=\"boleta\"><a rel=\"div.modal\" class=\"materia\" target=\"_self\" nclick=\"espera($row[1],0)\" ref=\"util/7.php?grupo=".$row[1]."&final=0\">Boletas</a></span><span class=\"boleta\"><a class=\"materia\" href=\"util/7.php?grupo=".$row[1]."&final=1\">Boletas Finales</a></span></td></tr>";





      }
    
      echo "<tr><td class=\"materia\">$row[2]</td><td class=\"materia\">$row[3] $row[4] $row[5]</td>";

      $porcentaje_parcial = porcentaje_parcial($row[0], $o_database);

      for($i=0; $i<4; $i++)
	{
	  echo "<td class=\"materia\">[".$porcentaje_parcial[$i]."%]</td>";
	}
    echo "</tr>";
  }
echo "</table>

";

echo "<div class=\"modal\">Generando archivo, por favor espere <img width=\"25px\" height=\"25px\" src=\"./img/wait.gif\"></div>

<iframe id=\"downloadFileiFrame\" style=\"display:none;\"></iframe>

";


echo "<script>

function espera()
{
  alert('Esta acción puede tomar varios minutos');
}

</script>";





return;

function porcentaje_parcial($chm_id, $o_database)
{
  $resultado = array();
  for($i=1; $i<5; $i++)
    {
      $q = "select count(*) from alumno_has_evaluacion where ciclo_has_materia_id = $chm_id";
      //echo $q."<br />";
      $o_database->query_fetch_field($q);
      $total = $o_database->query_field;


      $q = "select count(*) from alumno_has_evaluacion where ciclo_has_materia_id = $chm_id and calif_$i is not null";
      //echo $q."<br />";
      $o_database->query_fetch_field($q);
      $con_calificacion = $o_database->query_field;

      if($total != 0)
	$resultado[] = round(($con_calificacion / $total) * 100);
      else
	$resultado[] = 0;
    }

  return $resultado;
}

//echo $q;
$o_database->query_rows($q);
$result = $o_database->query_result;





// El grupo y la materia
$q = "select chm.grupo_name, m.name from ciclo_has_materia as chm join materia as m on m.id = chm.materia_id  where chm.id = $chm_id"; 
$o_database->query_fetch_row($q);
$grupo_id     = $o_database->query_row[0];
$materia      = $o_database->query_row[1];


// Las evaluaciones que están abiertas para captura
$ev1 = "disabled";
$ev2 = "disabled";
$ev3 = "disabled";
$ev4 = "disabled";

$q = "select evaluacion1,evaluacion2,evaluacion3,evaluacion4 from ciclo where id = \"".$o_config->ciclo."\" and tipo = \"".$o_config->tipo."\""; 

//echo $q; return;

$o_database->query_fetch_row($q);
if($o_database->query_row[0])
  $ev1 = null;
if($o_database->query_row[1])
  $ev2 = null;
if($o_database->query_row[2])
  $ev3 = null;
if($o_database->query_row[3])
  $ev4 = null;


//echo $q;return;



$q = "select a.id, a.1_apellido, a.2_apellido, a.nombre, ahe.id, ahe.calif_1, ahe.calif_2, ahe.calif_3, ahe.calif_4, ahe.calif_5, ahe.faltas_1, ahe.faltas_2, ahe.faltas_3 from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id and ahe.ciclo_has_materia_id = $chm_id  order by 2,3,4";

//echo $q;return;
$o_database->query_rows($q);
$result = $o_database->query_result;
echo "
<form id=\"formaalumno\">
<input type=\"hidden\" name=\"cs1\" value=\"$o_config->control_structure_id1\">
<input type=\"hidden\" name=\"cs2\" value=\"$o_config->control_structure_id2\">
<input type=\"hidden\" name=\"chm_id\" value=\"$chm_id\">
<table class=\"alumno\">";

echo "
<tr><td class=\"titulo-alumno\" colspan=\"9\">$grupo_id ($materia)</td><tr>";

echo"
<tr><td id=\"tdenvia1\" class=\"titulo-alumno\" colspan=\"9\"><button id=\"benvia1\" type=\"submit\" onFocus=\"\" onBlur=\"\" class=\"alumno-salvado\" formaction=\"util/ActualizaCalis.php\" formmethod=\"POST\">Datos Guardados</button></td></tr>";

echo "
<tr>
<td class=\"titulo-alumno\">Nombre</td>
<td colspan=\"2\" class=\"titulo-alumno\">Parcial 1</td>
<td colspan=\"2\" class=\"titulo-alumno\">Parcial 2</td>
<td colspan=\"2\" class=\"titulo-alumno\">Parcial 3</td>
<td class=\"titulo-alumno\">Examen</td>
<td class=\"titulo-alumno\">Final</td></tr>";


echo "
<tr>
<td class=\"titulo-alumno\">&nbsp;</td>
<td class=\"titulo-alumno\">Ev.</td><td class=\"titulo-alumno\">F</td>
<td class=\"titulo-alumno\">Ev.</td><td class=\"titulo-alumno\">F</td>
<td class=\"titulo-alumno\">Ev.</td><td class=\"titulo-alumno\">F</td>
<td class=\"titulo-alumno\">Ev.</td>
<td class=\"titulo-alumno\">&nbsp;</td></tr>";


//$renglon   = 1;
$renglon    = 1;
$tabindex   = 1;
$tabindexF  = 2;
$tabindexA  = null;
$tabindexFA = null;
$numcampos = $o_database->query_num_rows;
$rengloncss = 0;
while($row = mysql_fetch_row($result))
  {
    $nombre = $row[1]." ".$row[2]." ".$row[3];
    echo "
         <tr><td class=\"alumno$rengloncss\">$nombre</td>";
    
    //echo "<td class=\"alumno\"><input tabindex=\"$tabindex\" class=\"calificacion\" type=\"number\" name=\"ahe$row[4]-1\" id=\"ahe$row[4]-1\" max=\"10\" min=\"0\" size=\"5\" pattern=\"[0-9]+([\.,][0-9]+)?\" step=\"0.1\" value=\"$row[5]\"></td>";

    echo "
<td class=\"alumno$rengloncss\">
<input $ev1 tabindex=\"$tabindex\" class=\"calificacion\" type=\"number\" name=\"ahe-$row[4]-1\" id=\"ahe$row[4]-1\" max=\"10\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[5]\" title=\"Ingresa un número entre 0 y 10\"></td>";

    echo "
<td class=\"alumno$rengloncss\">
<input $ev1 tabindex=\"$tabindexF\" class=\"calificacion\" type=\"number\" name=\"ahef-$row[4]-1\" id=\"ahef$row[4]-1\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[10]\" title=\"Ingresa un número positivo\"></td>";

    //echo "<td class=\"alumno$rengloncss\"><input $ev1 tabindex=\"$tabindex\" class=\"calificacion\" type=\"number\" name=\"ahe-$row[4]-1\" id=\"ahe$row[4]-1\" max=\"10\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[5]\" title=\"Ingresa un número entre 0 y 10\"></td>";

    $tabindexA  = (2*$numcampos) + $renglon;
    $tabindexFA = $tabindexA + 1;


   echo "
<td class=\"alumno$rengloncss\">
<input $ev2 tabindex=\"$tabindexA\" class=\"calificacion\" type=\"number\" name=\"ahe-$row[4]-2\" id=\"ahe$row[4]-2\" max=\"10\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[6]\" title=\"Ingresa un número entre 0 y 10\"></td>";

    echo "
<td class=\"alumno$rengloncss\">
<input $ev2 tabindex=\"$tabindexFA\" class=\"calificacion\" type=\"number\" name=\"ahef-$row[4]-2\" id=\"ahef$row[4]-2\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[11]\" title=\"Ingresa un número positivo\"></td>";






    //echo "<td class=\"alumno$rengloncss\"><input $ev2 tabindex=\"$tabindexA\" class=\"calificacion\" type=\"number\" name=\"ahe-$row[4]-2\" id=\"ahe$row[4]-2\" max=\"10\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[6]\" data-message=\"Ingresa un número entre 0 y 10\"></td>";
    $tabindexA = (4*$numcampos) + $renglon;
    $tabindexFA = $tabindexA + 1;



   echo "
<td class=\"alumno$rengloncss\">
<input $ev3 tabindex=\"$tabindexA\" class=\"calificacion\" type=\"number\" name=\"ahe-$row[4]-3\" id=\"ahe$row[4]-3\" max=\"10\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[7]\" title=\"Ingresa un número entre 0 y 10\"></td>";

    echo "
<td class=\"alumno$rengloncss\">
<input $ev3 tabindex=\"$tabindexFA\" class=\"calificacion\" type=\"number\" name=\"ahef-$row[4]-3\" id=\"ahef$row[4]-3\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[12]\" title=\"Ingresa un número positivo\"></td>

";







    //echo "<td class=\"alumno$rengloncss\"><input $ev3 tabindex=\"$tabindexA\" class=\"calificacion\" type=\"number\" name=\"ahe-$row[4]-3\" id=\"ahe$row[4]-3\" max=\"10\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[7]\" data-message=\"Ingresa un número entre 0 y 10\"></td>";
    $tabindexA = (6*$numcampos) + $renglon;
    $tabindexFA = $tabindexA + 1;

    echo "<td class=\"alumno$rengloncss\"><input $ev4 tabindexA=\"$tabindexA\" class=\"calificacion\" type=\"number\" name=\"ahe$-row[4]-4\" id=\"ahe$row[4]-4\" max=\"10\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[8]\" data-message=\"Ingresa un número entre 0 y 10\"></td>";

    $tabindexA = (7*$numcampos) + $renglon;
    $tabindexFA = $tabindexA + 1;


    echo "<td class=\"alumno$rengloncss\"><input class=\"calificacion\" type=\"text  \" name=\"ahe$row[4]-5\" id=\"ahe-$row[4]-5\"                      size=\"5\" disabled value=\"$row[9]\"></td>";

    echo "</tr>";

    $tabindex  = $tabindex  + 2;
    $tabindexF = $tabindexF + 2;
    $renglon++;

    if($rengloncss)
      $rengloncss = 0;
    else
      $rengloncss = 1;
  }

echo"<tr><td id=\"tdenvia2\" class=\"titulo-alumno\" colspan=\"9\"><button id=\"benvia2\" type=\"submit\" onFocus=\"\" onBlur=\"\" class=\"alumno-salvado\" formaction=\"util/ActualizaCalis.php\" formmethod=\"POST\">Datos Guardados</button>";

echo "</table></form>";



echo "<script>
$('[id^=ahe]').on('change', function(event)
   {
     $('#tdenvia1').attr('class', 'titulo-alumno-enviar');
     $('#benvia1').html('Es Necesario Guardar los Datos');
     $('#tdenvia2').attr('class', 'titulo-alumno-enviar');
     $('#benvia2').html('Es Necesario Guardar los Datos');

   });


function espera()
{
 $(\"body\").css(\"cursor\", \"progress\");

}



</script>";

return;
?>