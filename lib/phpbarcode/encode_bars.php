<?php
function barcode_gen_ean_sum($ean)
{
  $even=true; $esum=0; $osum=0;
  for ($i=strlen($ean)-1;$i>=0;$i--){
	if ($even) $esum+=$ean[$i];	else $osum+=$ean[$i];
	$even=!$even;
  }
  return (10-((3*$esum+$osum)%10))%10;
}
function barcode_encode_ean($ean, $encoding = "EAN-13"){
    $digits=array(3211,2221,2122,1411,1132,1231,1114,1312,1213,3112);
    $mirror=array("000000","001011","001101","001110","010011","011001","011100","010101","010110","011010");
    $guards=array("9a1a","1a1a1","a1a");

    $ean=trim($ean);
    if (eregi("[^0-9]",$ean)){
	return array("text"=>"Invalid EAN-Code");
    }
    $encoding=strtoupper($encoding);
    if ($encoding=="ISBN"){
	if (!ereg("^978", $ean)) $ean="978".$ean;
    }
    if (ereg("^978", $ean)) $encoding="ISBN";
    if (strlen($ean)<12 || strlen($ean)>13){
	return array("text"=>"Invalid $encoding Code (must have 12/13 numbers)");
    }

    $ean=substr($ean,0,12);
    $eansum=barcode_gen_ean_sum($ean);
    $ean.=$eansum;
    $line=$guards[0];
    for ($i=1;$i<13;$i++){
	$str=$digits[$ean[$i]];
	if ($i<7 && $mirror[$ean[0]][$i-1]==1) $line.=strrev($str); else $line.=$str;
	if ($i==6) $line.=$guards[1];
    }
    $line.=$guards[2];

    /* create text */
    $pos=0;
    $text="";
    for ($a=0;$a<13;$a++){
	if ($a>0) $text.=" ";
	$text.="$pos:12:{$ean[$a]}";
	if ($a==0) $pos+=12;
	else if ($a==6) $pos+=12;
	else $pos+=7;
    }

    return array(
		"encoding" => $encoding,
		"bars" => $line,
		"text" => $text
		);
}

?>
