<?php
require('barcode/class/BCGFontFile.php');
require('barcode/class/BCGColor.php');
require('barcode/class/BCGDrawing.php');
require('barcode/class/BCGean13.barcode.php');
 
if(empty($_GET['barcode']))
  {
    echo "Variable no recibida";
    return;
  }
else
  $barcode = $_GET['barcode'];



$font = new BCGFontFile('./barcode/font/Arial.ttf', 24);
$color_black = new BCGColor(0, 0, 0);
$color_white = new BCGColor(255, 255, 255);
 
// Barcode Part
$code = new BCGean13();
$code->setScale(3);
$code->setThickness(30);
$code->setForegroundColor($color_black);
$code->setBackgroundColor($color_white);
$code->setFont($font);
$code->parse($barcode);
 
// Drawing Part
$drawing = new BCGDrawing('', $color_white);
$drawing->setBarcode($code);
$drawing->draw();
 
header('Content-Type: image/png');
 
$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
?>