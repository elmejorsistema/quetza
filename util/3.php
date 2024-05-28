<?php

//Define the control_structure
if(!empty($_GET['id']))
  $chm_id=trim(addslashes($_GET['id']));
else
  {
    echo "<script>alert(\"".$o_message->show_message(6,$o_database)."\")</script>";
    echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=index.html\"></head></html>";
    return;
  }


$q = "select s.id from ciclo_has_materia as chm join grupo as g on chm.grupo_id = g.id join semestre as s on s.id = g.semestre_id where chm.id = $chm_id";
$o_database->query_fetch_field($q);
$semestre = $o_database->query_field;

//echo $semestre;
//return;



// El grupo y la materia
$q = "select chm.grupo_name, m.name, m.id from ciclo_has_materia as chm join materia as m on m.id = chm.materia_id  where chm.id = $chm_id"; 
$o_database->query_fetch_row($q);
$grupo_id     = $o_database->query_row[0];
$materia      = $o_database->query_row[1];
$materia_id   = $o_database->query_row[2];




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


/*
0 por nombre de alumno
1 por capacitación
2 por grupo
*/
$index = 0;

switch($semestre)
  {
  case 1:
  case 2:
  case 3:
  case 4:

    // Estos son los talleres para primer año
      if(($materia_id > 95 and $materia_id < 106) or $materia_id == 114 or ($materia_id >=  120 and $materia_id <= 121) or ($materia_id >=  124 and $materia_id <= 133) or ($materia_id >=  172 and $materia_id <= 177) or ($materia_id >= 187 and $materia_id <= 190))
      {
	$q = "select a.id, a.1_apellido, a.2_apellido, a.nombre, ahe.id, ahe.calif_1, ahe.calif_2, ahe.calif_3, ahe.calif_4, ahe.calif_5, ahe.faltas_1, ahe.faltas_2, ahe.faltas_3, a.grupo_id, g.name  from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id and ahe.ciclo_has_materia_id = $chm_id join grupo as g on g.id = a.grupo_id  order by 14,2,3,4";
	$index = 2;
      }
    else
      $q = "select a.id, a.1_apellido, a.2_apellido, a.nombre, ahe.id, ahe.calif_1, ahe.calif_2, ahe.calif_3, ahe.calif_4, ahe.calif_5, ahe.faltas_1, ahe.faltas_2, ahe.faltas_3 from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id and ahe.ciclo_has_materia_id = $chm_id  order by 2,3,4";
    break;
  default:
    // Estos son las capacitaciones para tercer año
    if(($materia_id > 58 and $materia_id < 65) or ($materia_id > 38 and $materia_id < 45) or ($materia_id > 109 and $materia_id < 114) or ($materia_id > 117 and $materia_id < 120) or ($materia_id > 135 and $materia_id < 144) or ($materia_id > 144 and $materia_id < 153) or ($materia_id > 155 and $materia_id < 172))
      {
	$q = "select a.id, a.1_apellido, a.2_apellido, a.nombre, ahe.id, ahe.calif_1, ahe.calif_2, ahe.calif_3, ahe.calif_4, ahe.calif_5, ahe.faltas_1, ahe.faltas_2, ahe.faltas_3, a.grupo_id, g.name  from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id and ahe.ciclo_has_materia_id = $chm_id join grupo as g on g.id = a.grupo_id  order by 14,2,3,4";
	$index = 2;
      }
    // Todos los demás terceros se indexan por capacitación
    else
      {
	$q = "select a.id, a.1_apellido, a.2_apellido, a.nombre, ahe.id, ahe.calif_1, ahe.calif_2, ahe.calif_3, ahe.calif_4, ahe.calif_5, ahe.faltas_1, ahe.faltas_2, ahe.faltas_3, a.capacitacion_id, c.name  from alumno as a join alumno_has_evaluacion as ahe on a.id = ahe.alumno_id and ahe.ciclo_has_materia_id = $chm_id join capacitacion as c on c.id = a.capacitacion_id  order by 14,2,3,4";
	$index = 1;
      }
    break;
  }

//echo $q;
$o_database->query_rows($q);
$result = $o_database->query_result;
echo "
<form id=\"formaalumno\">
<input type=\"hidden\" name=\"cs1\" value=\"$o_config->control_structure_id1\">
<input type=\"hidden\" name=\"cs2\" value=\"$o_config->control_structure_id2\">
<input type=\"hidden\" name=\"chm_id\" value=\"$chm_id\">
<table class=\"alumno\">";

echo "
<tr><td class=\"titulo-materia\" colspan=\"9\">$grupo_id ($materia)</td><tr>";

echo"
<tr><td id=\"tdenvia1\" class=\"titulo-alumno\" colspan=\"9\"><button id=\"benvia1\" type=\"submit\" onFocus=\"\" onBlur=\"\" class=\"alumno-salvado\" formaction=\"util/6.php\" formmethod=\"POST\">Datos Guardados</button></td></tr>";

//echo "<tr><td class=\"tresparciales\" colspan=\"9\">Se promediarán solamente los tres parciales</td></tr>";




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

/*
  Estas son las dos variables para las diferentes indexaciones
*/

$capacitacion = null;
//$grupo        = null

foreach($result as $row)
  {


    //$row[5] = number_format($row[5],1,".",",");

    /*
    if($semestre > 4)
      if($capacitacion != $row[13])
	{
	  $capacitacion = $row[13];
	  echo "<tr><td class=\"capacitacion\" colspan=\"9\">$row[14]</td></tr>";
	}
    */

    switch($index)
      {
      case 0:
      break;
      case 1:
      case 2:
      if($capacitacion != $row[13])
	{
	  $capacitacion = $row[13];
	  echo "<tr><td class=\"capacitacion\" colspan=\"9\">$row[14]</td></tr>";
	}
      break;
      }


    
    $nombre = $row[1]." ".$row[2]." ".$row[3];
    echo "
         <tr><td class=\"alumno$rengloncss\">$nombre</td>";
    
    //echo "<td class=\"alumno\"><input tabindex=\"$tabindex\" class=\"calificacion\" type=\"number\" name=\"ahe$row[4]-1\" id=\"ahe$row[4]-1\" max=\"10\" min=\"0\" size=\"5\" pattern=\"[0-9]+([\.,][0-9]+)?\" step=\"0.1\" value=\"$row[5]\"></td>";


    if($row[5] < 6)
      $calificacion="calificacion-menos";
    else
      $calificacion="calificacion";



    echo "
<td class=\"alumno$rengloncss\">
<input $ev1 tabindex=\"$tabindex\" class=\"$calificacion\" type=\"number\" name=\"ahe-$row[4]-1\" id=\"ahe$row[4]-1\" max=\"10\" min=\"-1\" size=\"5\" pattern=\"^[0-9]{1,2}+.[0-9]{1}$\" step=\"0.1\" value=\"$row[5]\" title=\"Ingresa un número entre 0 y 10\"></td>";

    echo "
<td class=\"alumno$rengloncss\">
<input $ev1 tabindex=\"$tabindexF\" class=\"faltas\" type=\"number\" name=\"ahef-$row[4]-1\" id=\"ahef$row[4]-1\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[10]\" title=\"Ingresa un número positivo\"></td>";

    //echo "<td class=\"alumno$rengloncss\"><input $ev1 tabindex=\"$tabindex\" class=\"calificacion\" type=\"number\" name=\"ahe-$row[4]-1\" id=\"ahe$row[4]-1\" max=\"10\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[5]\" title=\"Ingresa un número entre 0 y 10\"></td>";

    $tabindexA  = (2*$numcampos) + $renglon;
    $tabindexFA = $tabindexA + 1;


if($row[6] < 6)
      $calificacion="calificacion-menos";
    else
      $calificacion="calificacion";



   //echo "
//<td class=\"alumno$rengloncss\">
//<input $ev2 tabindex=\"$tabindexA\" class=\"$calificacion\" type=\"number\" name=\"ahe-$row[4]-2\" id=\"ahe$row[4]-2\" max=\"10\" min=\"-1\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[6]\" title=\"Ingresa un número entre 0 y 10\"></td>";

   echo "
<td class=\"alumno$rengloncss\">
<input $ev2 tabindex=\"$tabindexA\" class=\"$calificacion\" type=\"number\" name=\"ahe-$row[4]-2\" id=\"ahe$row[4]-2\" max=\"10\" min=\"-1\" size=\"5\" pattern=\"^[0-9]{1,2}+.[0-9]{1}$\" step=\"0.1\" value=\"$row[6]\" title=\"Ingresa un número entre 0 y 10\"></td>";







    echo "
<td class=\"alumno$rengloncss\">
<input $ev2 tabindex=\"$tabindexFA\" class=\"faltas\" type=\"number\" name=\"ahef-$row[4]-2\" id=\"ahef$row[4]-2\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[11]\" title=\"Ingresa un número positivo\"></td>";






    //echo "<td class=\"alumno$rengloncss\"><input $ev2 tabindex=\"$tabindexA\" class=\"calificacion\" type=\"number\" name=\"ahe-$row[4]-2\" id=\"ahe$row[4]-2\" max=\"10\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[6]\" data-message=\"Ingresa un número entre 0 y 10\"></td>";
    $tabindexA = (4*$numcampos) + $renglon;
    $tabindexFA = $tabindexA + 1;


if($row[7] < 6)
      $calificacion="calificacion-menos";
    else
      $calificacion="calificacion";




   echo "
<td class=\"alumno$rengloncss\">
<input $ev3 tabindex=\"$tabindexA\" class=\"$calificacion\" type=\"number\" name=\"ahe-$row[4]-3\" id=\"ahe$row[4]-3\" max=\"10\" min=\"-1\" size=\"5\" pattern=\"^[0-9]{1,2}+.[0-9]{1}$\" step=\"0.1\" value=\"$row[7]\" title=\"Ingresa un número entre 0 y 10\"></td>";

    echo "
<td class=\"alumno$rengloncss\">
<input $ev3 tabindex=\"$tabindexFA\" class=\"faltas\" type=\"number\" name=\"ahef-$row[4]-3\" id=\"ahef$row[4]-3\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[12]\" title=\"Ingresa un número positivo\"></td>

";







    //echo "<td class=\"alumno$rengloncss\"><input $ev3 tabindex=\"$tabindexA\" class=\"calificacion\" type=\"number\" name=\"ahe-$row[4]-3\" id=\"ahe$row[4]-3\" max=\"10\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"$row[7]\" data-message=\"Ingresa un número entre 0 y 10\"></td>";
    $tabindexA = (6*$numcampos) + $renglon;
    $tabindexFA = $tabindexA + 1;



    if($row[8] < 6)
      $calificacion="calificacion-menos";
    else
      $calificacion="calificacion";


    echo "<td class=\"alumno$rengloncss\"><input $ev4 tabindexA=\"$tabindexA\" class=\"$calificacion\" type=\"number\" name=\"ahe-$row[4]-4\" id=\"ahe$row[4]-4\" max=\"10\" min=\"-1\" size=\"5\" pattern=\"^[0-9]{1,2}$+.[0-9]{1}\" step=\"0.1\" value=\"$row[8]\" data-message=\"Ingresa un número entre 0 y 10\"></td>";

    $tabindexA = (7*$numcampos) + $renglon;
    $tabindexFA = $tabindexA + 1;


    if($row[9] < 6)
      $calificacion="calificacion-menos";
    else
      $calificacion="calificacion";



    echo "<td class=\"alumno$rengloncss\"><input class=\"$calificacion\" type=\"text  \" name=\"ahe$row[4]-5\" id=\"ahe-$row[4]-5\"                      size=\"5\" disabled value=\"$row[9]\"></td>";

    echo "</tr>";

    $tabindex  = $tabindex  + 2;
    $tabindexF = $tabindexF + 2;
    $renglon++;

    if($rengloncss)
      $rengloncss = 0;
    else
      $rengloncss = 1;
  }

echo"<tr><td id=\"tdenvia2\" class=\"titulo-alumno\" colspan=\"9\"><button id=\"benvia2\" type=\"submit\" onFocus=\"\" onBlur=\"\" class=\"alumno-salvado\" formaction=\"util/6.php\" formmethod=\"POST\">Datos Guardados</button></tr>";

//<tr><td class=\"tresparciales\" colspan=\"9\">Se promediarán solamente los tres parciales</td></tr>";



echo "</table></form>";

//[id^=AAA_][id$=_BBB]"

echo "<script>
$('[id^=ahe]').on('change', function(event)
   {
     $('#tdenvia1').attr('class', 'titulo-alumno-enviar');
     $('#benvia1').html('Guardar Datos');
     $('#benvia1').attr('class', 'alumno-nosalvado');
     $('#tdenvia2').attr('class', 'titulo-alumno-enviar');
     $('#benvia2').html('Guardar Datos');
     $('#benvia2').attr('class', 'alumno-nosalvado');

     if($(this).attr('id').match(/ahe[0-9]+/))
      {
        if($(this).val() < 6)
          $(this).attr('class', 'calificacion-menos');
        else
          $(this).attr('class', 'calificacion');
      }
   });
</script>";

return;
?>