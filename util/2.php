<?php
// Esto es para abrir alguno de los overlay
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




$q = "select chm.grupo_name, m.name, chm.id from ciclo_has_materia as chm join materia as m on m.id = chm.materia_id where chm.user_id = ".$o_user->id." and chm.ciclo_id = \"".$o_config->ciclo."\" and chm.ciclo_tipo = \"".$o_config->tipo."\" order by m.id, chm.grupo_id";

$o_database->query_rows($q);
$result = $o_database->query_result;

if(!$o_database->query_num_rows)
  echo "
<table class=\"a-contenido\">
<tr><td class=\"a-menu-contenido\">
No tienes grupos asignados para este periodo
</td></tr>";


echo "
<table class=\"a-contenido\">";
foreach($result as $row)
  {
    
    echo "<tr><td class=\"a-menu-contenido\"><a class=\"materia\" href=\"?&cs1=$o_config->control_structure_id1&cs2=3&id=$row[2]\">$row[0] ($row[1])</td></tr>";


  }

echo "</table>";




//echo $q;
//exit();
return;

 ?>