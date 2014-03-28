<?php
require_once("../lib/dbconn.php");
require('../barcodegen/class/BCGFontFile.php');
require('../barcodegen/class/BCGColor.php');
require('../barcodegen/class/BCGDrawing.php');
require('../barcodegen/class/BCGcode39.barcode.php');
 
$font = new BCGFontFile('../barcodegen/font/Arial.ttf', 8);
$color_black = new BCGColor(0, 0, 0, 0);
$color_white = new BCGColor(255, 255, 255);
 
// Barcode Part
$code = new BCGcode39();
$code->setScale(1);
$code->setThickness(25);
$code->setForegroundColor($color_black);
$code->setBackgroundColor($color_white);
$code->setFont($font);
$code->setChecksum(false);
$code->parse($_GET['rg']);
 
// Drawing Part
$drawing = new BCGDrawing('', $color_white);
$drawing->setBarcode($code);
$drawing->draw();
 
header('Content-Type: image/png');
 
$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

?>
