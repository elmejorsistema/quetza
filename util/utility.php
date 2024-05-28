<?php

define("CSS", "./css/");
define("JS", "./js/");
define("IMG", "./img/");




//Creates HTML header
function head_HTML($o_config, $o_user, $o_database)
{
 echo "<!DOCTYPE HTML>
<html>
<head>
<title>$o_config->company_name</title>


  
    <script type=\"text/javascript\" src=\"jquery/jquery.tools.min.js\"></script>
    <script type=\"text/javascript\" src=\"jquery/jquery-ui.js\"></script>
    <!--<script src=\"//code.jquery.com/ui/1.10.4/jquery-ui.js\"></script>-->
    

    <!--
     <script type=\"text/javascript\" src=\"jquery/jquery.tools.min.js\"></script>
     <script src=\"//code.jquery.com/jquery-1.9.1.js\"></script>
     <script src=\"//code.jquery.com/ui/1.10.4/jquery-ui.js\"></script>
     <script type=\"text/javascript\" src=\"jquery/jquery.tools.min-02.js\"></script>-->



    <link rel=\"icon\" href=\"./img/favicon.png\" type=\"image/x-icon\">
    <link rel=\"icon\" type=\"image/vnd.microsoft.icon\" href=\"./img/favicon.ico\">
    <link rel=\"shortcut icon\" href=\"./img/favicon.ico\" type=\"image/x-icon\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\"/>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/jquery-ui-1.10.4.custom.css\"/>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/date.css\"/>
  

    <!---<link rel=\"stylesheet\" href=\"http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css\">-->

    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
</head>
";
}


//Creates header
function head($o_config, $o_user, $o_database)
{
 
  //$fecha = date("j-", mktime()).getSpanishMonth(date("n", mktime())).date("-Y", mktime());

 echo "<body>
<table class=\"a-compania-user\">
<tr>
 <td class=\"a-compania\">$o_config->company_name</td><td class=\"a-fecha\">Ciclo: $o_config->ciclo ($o_config->tipo) </td><td class=\"a-user\">$o_user->humanname</td><td class=\"a-cerrar\"><a class=\"a-cerrar\" href=\"logout.php\">Cerrar Sesión</a></td>
</tr>
</table>
"; 
}

// Creates the content
function menu($o_config, $o_user, $o_database)
{

  echo "<table class=\"a-menu\"><tr>";

  $q = "select cs.id, cs.name, cs.status from control_structure as cs join user_has_control_structure as uhcs on cs.id = uhcs.control_structure_id where uhcs.user_id = $o_user->id and cs.control_structure_id is null order by cs.sequence asc";

  $o_database->query_rows($q);
  $result = $o_database->query_result;

  while($row = mysql_fetch_row($result))
    {

      if($row[2])
      {
          $alumno = null;
          
          //if($o_config->alumno_id and $row[0] == 16 and $o_config->control_structure_id2 != 21)
          //if($o_config->alumno_id and $row[0] == 16)
          if($o_config->alumno_id and $row[0] == 16)
              $alumno = "<br /><small>(".$o_config->alumno_id.")</small>";
          
          echo "<td class=\"a-menu";

          if($o_config->control_structure_id1 == $row[0])
              echo "-d\">$row[1]$alumno";
          else
              echo "\"><a class=\"a-menu\" href=\"?cs1=$row[0]\">$row[1]</a>$alumno";
          echo "</td>";
      }
    }

    echo "</tr></table>"
;
}   

// Creates the content
function content($o_config, $o_user, $o_database, $o_message)
{

  // var_dump($o_config);

  echo "<table class=\"content\">
<tr><td class=\"a-submenu\">
<table class=\"a-submenu\">
";

  $q = "select cs.id, cs.name, cs.status from control_structure as cs join user_has_control_structure as uhcs on cs.id = uhcs.control_structure_id where uhcs.user_id = $o_user->id and cs.control_structure_id = $o_config->control_structure_id1 order by cs.sequence asc";
  
  $o_database->query_rows($q);
  $result = $o_database->query_result;

  //echo $o_config->control_structure_id1 . "||". $o_config->control_structure_id2;
  while($row = mysql_fetch_row($result))
    {
   if($row[2])
	{
	  echo "<tr><td class=\"a-submenu-item";
	  if($o_config->control_structure_id2 == $row[0])
	    echo "-d\">".$row[1];
	  else
	    echo "\"><a class=\"a-menusubmenu\" href=\"?cs1=$o_config->control_structure_id1&cs2=$row[0]\">$row[1]</a>";
	  echo "</td></tr>";
	}
     }

  echo "</table>
";
  echo "</td>
<td class=\"a-content\">";

  if(file_exists("util/$o_config->control_structure_id2".".php"))
    include "$o_config->control_structure_id2".".php";
  else
    echo "<table class=\"a-contenido\"><tr><td class=\"a-menu-contenido\"><h3><b>Zona en Desarrollo</b></h3></td</tr></table>";


echo "</td>
</tr></table>";

}

//Creates footer
function foot()
{
 echo "<table class=\"agenteel\"><tr><td class=\"agenteel\">Sistema Desarrollado por <a class=\"Agenteel\" href=\"https://elmejorsistema.mx\" target=\"_blank\">El Mejor Sistema<img class=\"agenteel\" src=\"./img/EMS.png\"></a></td></tr></table></body></html>";
}

?>
