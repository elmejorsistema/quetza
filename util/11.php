<?php

// Abre los parciales 

//var_dump($o_config);return;

echo "
<form id=\"formalistas\">
<input type=\"hidden\" name=\"cs1\" value=\"$o_config->control_structure_id1\">
<input type=\"hidden\" name=\"cs2\" value=\"$o_config->control_structure_id2\">
<table>
<tr>
<td class=\"titulo-alumno\">Grupo</td><td class=\"titulo-alumno\">Tipo de Lista</td></tr>
<tr><td>";

if($o_user->tipo == "Profesor")
  $q = "select chm.id, concat(concat(concat(chm.grupo_name, \" [\"), m.name), \"]\") from ciclo_has_materia as chm join materia as m on m.id = chm.materia_id where chm.user_id = ".$o_user->id." and chm.ciclo_id = \"".$o_config->ciclo."\" and chm.ciclo_tipo = \"".$o_config->tipo."\" order by chm.grupo_id, m.name";
else
  $q = "select chm.id, concat(concat(concat(chm.grupo_name, \" [\"), m.name), \"]\") from ciclo_has_materia as chm join materia as m on m.id = chm.materia_id where chm.ciclo_id = \"".$o_config->ciclo."\" and chm.ciclo_tipo = \"".$o_config->tipo."\" order by chm.grupo_id, m.name";

//echo $q;
create_select($q, "chm", "", "", $o_database, "a-select");

echo "</td><td>
<input type=\"radio\" name=\"lista\" value=\"1\" checked ><span class=\"a-radio\">Calificaciones</span><br>
<input type=\"radio\" name=\"lista\" value=\"2\"><span class=\"a-radio\">Calificaciones Finales</span><br>
<input type=\"radio\" name=\"lista\" value=\"3\"><span class=\"a-radio\">Lista de Control</span><br>
</tr>
<tr><td class=\"titulo-alumno\" colspan=\"2\"><button onclick=\"espera()\" type=\"submit\" onFocus=\"\" onBlur=\"\" class=\"alumno-salvado\" formaction=\"util/12.php\" formmethod=\"POST\">Enviar</button></td></tr>
</table>";


echo "<script>

function espera()
{
  alert('Esta acción puede tomar varios minutos');
}

</script>";





return;

?>