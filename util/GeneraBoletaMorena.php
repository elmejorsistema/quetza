<?php


include "functions.php";

require_once ('../dompdf/dompdf_config.inc.php');



$html = "<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
<link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Tangerine\">
<link href=\"https://fonts.googleapis.com/css?family=Fjalla+One\" rel=\"stylesheet\"> 
<style>

html {
  margin: 5mm;
}

h1{
  margin: 3mm;
}
h2{
  margin: 3mm;
}



body {
  font-family:  \"Fjalla One\", serif;;
  font-size: 10pt;
  margin: 0mm;
}

table {
  width: 100%;
  border-spacing: 1mm, 1mm, 0mm, 0mm;
  border-collapse: separate;
}

table.nombre {
  width: 100%;
  border: 0mm solid black;

}


td {
  border: .5mm solid black;
  padding: 5mm;
  text-align: center;
}

td.nombre {
  border-collapse: collapse;
  border: 0mm solid black;
  padding: 1mm;
  text-align: left;
  font-size: 15pt;
}




img
{
  margin: 0;
  padding 0;
  width: 9mm;

}

img.logo-chico
{
  margin: 1mm,0mm,1mm,0mm;
  padding 0;
  width: 25mm;
}

hr {
  page-break-after: always;
  border:  0;
  margin:  0;
  padding: 0;
}


</style>
</head>
<body>";

$html .="
<table>";

for($contador = 700; $contador<800; $contador = $contador +2)
  {
 
    $contador1 = $contador+1;

$s_contador  = str_pad($contador,  3, '0', STR_PAD_LEFT);
$s_contador1 = str_pad($contador1, 3, '0', STR_PAD_LEFT);



$html .="

<tr>


<td>
<h1>Boleta de Votación</h1>
<h2>$s_contador</h2>


<table class=\"nombre\">

<tr>
<td class=\"nombre\">
<img src=\"square.png\">
</td>
<td class=\"nombre\">
Ángeles Piedra
</td></tr>

<tr>
<td class=\"nombre\">
<img src=\"square.png\">
</td>
<td class=\"nombre\">
Manuel José Contreras Maya
</td></tr>

<tr>
<td class=\"nombre\">
<img src=\"square.png\">
</td>
<td class=\"nombre\">
Sergio Vargas Bello
</td></tr>

<tr>
<td class=\"nombre\">
<img src=\"square.png\">
</td>
<td class=\"nombre\">
Miguel Hidalgo
</td></tr>

</table>
</td>

<td>
<h1>Boleta de Votación</h1>
<h2>$s_contador1</h2>


<table class=\"nombre\">

<tr>
<td class=\"nombre\">
<img src=\"square.png\">
</td>
<td class=\"nombre\">
Ángeles Piedra
</td></tr>

<tr>
<td class=\"nombre\">
<img src=\"square.png\">
</td>
<td class=\"nombre\">
Manuel José Contreras Maya
</td></tr>

<tr>
<td class=\"nombre\">
<img src=\"square.png\">
</td>
<td class=\"nombre\">
Sergio Vargas Bello
</td></tr>

<tr>
<td class=\"nombre\">
<img src=\"square.png\">
</td>
<td class=\"nombre\">
Miguel Hidalgo
</td></tr>

</table>
</td>

</tr>";
  }


$html .= "</table></body></html>";


$pdf = new DOMPDF();

$pdf->set_paper("Letter", "portrait");

$pdf->load_html($html);

$pdf->render();
 
$nombre_archivo = "BoletaVotacion.pdf";

$pdf->stream($nombre_archivo);

//echo $html;





return;


?>