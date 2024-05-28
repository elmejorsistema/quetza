<?php

// Abre los parciales 

//var_dump($o_config);return;

echo "
<form id=\"formaconcentrdos\">
<input type=\"hidden\" name=\"cs1\" value=\"$o_config->control_structure_id1\">
<input type=\"hidden\" name=\"cs2\" value=\"$o_config->control_structure_id2\">
<table class=\"concentrados\">
<tbody>
<tr>
<td class=\"titulo-alumno\">Universo</td><td class=\"titulo-alumno\">Indexación</td><td class=\"titulo-alumno\">Reprobadas (<i>r</i>)</td></tr>
<td>
<input type=\"radio\" id=\"semestre\" name=\"universo\" value=\"semestre\" checked>Por semestre<br>
<input type=\"radio\" id=\"grupo\" name=\"universo\" value=\"grupo\">Por grupo<br>";

$q = "select s.id, s.id from semestre as s join ciclo as c on c.tipo = s.tipo where c.status = \"Activo\"";
create_select($q, "o-semestre", "", "", $o_database, "a-select");

//$q = "select chm.id, chm.grupo_name from ciclo_has_materia as chm join ciclo as c on c.id = chm.ciclo_id where  c.status = \"Activo\" and chm.grupo_id < 700 group by chm.grupo_id";
$q = "select g.id, chm.grupo_name from grupo as g join ciclo_has_materia as chm on g.id = chm.grupo_id where chm.ciclo_id = '$o_config->ciclo' and  chm.ciclo_tipo = '$o_config->tipo'and chm.grupo_id < 700 group by .g.id order by g.id";
//echo $q;
create_select($q, "o-grupo", "", "", $o_database, "a-select-hidden");
echo "</td>
<td>
<input type=\"radio\" name=\"indexacion\" value=\"alfabetica\" checked>Alfabética<br>
<input type=\"radio\" name=\"indexacion\" value=\"promedio\">Por promedio<br>
</td>
<td>
<i>r</i>&ge;<input class=\"reprobadas\" type=\"number\" name=\"reprobada1\" id=\"reprobada1\" max=\"20\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"0\" title=\"Ingresa un número mayor a 0\"> y 
<i>r</i>&le;<input class=\"reprobadas\" type=\"number\" name =\"reprobada2\"id=\"reprobada2\" max=\"20\" min=\"0\" size=\"5\" pattern=\"^[0-9]{1,2}$\" step=\"1\" value=\"0\" title=\"Ingresa un número mayor a 0\"></td>


</tr>
<tr><td class=\"titulo-alumno\" colspan=\"3\"><button onclick=\"espera()\" type=\"submit\" onFocus=\"\" onBlur=\"\" class=\"alumno-salvado\" formaction=\"util/15.php\" formmethod=\"POST\">Enviar</button></td></tr>
</tbody>
</table>
</form>";


echo "<script>

$(document).ready(function() {

   $(\"#o-grupo\").hide();

   $(\"#semestre\").change(function(){
      $(\"#o-grupo\").hide();
      $(\"#o-semestre\").show();
      });

   $(\"#grupo\").change(function(){
      $(\"#o-semestre\").hide();
      $(\"#o-grupo\").show();
      });

  $(\"#reprobada1\").change(function(){
      var a = $(this).val();
      var b = $(\"#reprobada2\").val();
      if(parseInt(a) > parseInt(b))
      {
        alert('Error: valor inválido: '+ a +' -- '+ b)
        $(this).val(b);
       }
      });

  $(\"#reprobada2\").change(function(){
      var a = $(this).val();
      var b = $(\"#reprobada1\").val();
      if(parseInt(a) < parseInt(b))
      {
        alert('Error: valor inválido: '+ a +' -- '+ b)
        $(this).val(b);
       }
      });
});


function espera()
{
   alert('Esta acción puede tomar varios minutos.');
};

</script>";





return;

?>