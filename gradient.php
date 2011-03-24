<?php

/* Version 1.3 by jtGraphic */

/*
  Appears to be a glitch in images greater than 250px high - never reaches final colour?
*/

class Gradient {
  var $height = 25;
  var $width = 25;
  var $startcol = '777777';
  var $endcol = '000000';

  function draw() {
    $im = imagecreate ($this->width, $this->height);
     
    $b = hexdec($this->startcol);
    $c = hexdec($this->endcol);
    $white = imagecolorallocate($im,255,255,255);

    $sr = ($b & 0xFF0000) >> 16;
    $sg = ($b & 0xFF00) >> 8;
    $sb = ($b & 0xFF);

    $er = ($c & 0xFF0000) >> 16;
    $eg = ($c & 0xFF00) >> 8;
    $eb = ($c & 0xFF);

    $r = $er - $sr;
    $g = $eg - $sg;
    $b = $eb - $sb;

    for ($line = 0; $line < $this->height; $line++)
    {
      $cRed = (($sr += ($r / $this->height)) < 0) ? (int)0: (int)$sr;
      $cGreen = (($sg += ($g / $this->height)) < 0) ? (int)0: (int)$sg;
      $cBlue = (($sb += ($b / $this->height)) < 0) ? (int)0: (int)$sb;
      $clr[$line] = imagecolorallocate($im, $cRed, $cGreen, $cBlue);
      imageline($im, 0, $line, ($this->width - 1), $line, $clr[$line]);
    }
    
    imagejpeg($im,null,100);
  }
}

header("Content-type: image/jpg");

$img = new Gradient;

if(isset($_GET['color']) && $_GET['color'] != "") {
	$breaker['r'] = substr($_GET['color'],0,2);
	$breaker['g'] = substr($_GET['color'],2,2);
	$breaker['b'] = substr($_GET['color'],4,2);
	
	$breaker['top']['r'] = hexdec($breaker['r']) + 50;
	if($breaker['top']['r'] > 255) $breaker['top']['r'] = 255;
	if($breaker['top']['r'] < 0) $breaker['top']['r'] = 0;
	$breaker['top']['g'] = hexdec($breaker['g']) + 50;
	if($breaker['top']['g'] > 255) $breaker['top']['g'] = 255;
	if($breaker['top']['g'] < 0) $breaker['top']['g'] = 0;
	$breaker['top']['b'] = hexdec($breaker['b']) + 50;
	if($breaker['top']['b'] > 255) $breaker['top']['b'] = 255;
	if($breaker['top']['b'] < 0) $breaker['top']['b'] = 0;
	$breaker['top']['hex'] =
		str_pad(dechex($breaker['top']['r']),2,"0",STR_PAD_LEFT).
		str_pad(dechex($breaker['top']['g']),2,"0",STR_PAD_LEFT).
		str_pad(dechex($breaker['top']['b']),2,"0",STR_PAD_LEFT);
	
	$breaker['bottom']['r'] = hexdec($breaker['r']) - 50;
	if($breaker['bottom']['r'] > 255) $breaker['bottom']['r'] = 255;
	if($breaker['bottom']['r'] < 0) $breaker['bottom']['r'] = 0;
	$breaker['bottom']['g'] = hexdec($breaker['g']) - 50;
	if($breaker['bottom']['g'] > 255) $breaker['bottom']['g'] = 255;
	if($breaker['bottom']['g'] < 0) $breaker['bottom']['g'] = 0;
	$breaker['bottom']['b'] = hexdec($breaker['b']) - 50;
	if($breaker['bottom']['b'] > 255) $breaker['bottom']['b'] = 255;
	if($breaker['bottom']['b'] < 0) $breaker['bottom']['b'] = 0;
	
	$breaker['top']['r'] = str_pad($breaker['top']['r'],3,"0",STR_PAD_LEFT);
	$breaker['top']['g'] = str_pad($breaker['top']['g'],3,"0",STR_PAD_LEFT);
	$breaker['top']['b'] = str_pad($breaker['top']['b'],3,"0",STR_PAD_LEFT);
	
	$breaker['bottom']['r'] = str_pad($breaker['bottom']['r'],3,"0",STR_PAD_LEFT);
	$breaker['bottom']['g'] = str_pad($breaker['bottom']['g'],3,"0",STR_PAD_LEFT);
	$breaker['bottom']['b'] = str_pad($breaker['bottom']['b'],3,"0",STR_PAD_LEFT);
	
	$breaker['bottom']['hex'] =
		str_pad(dechex($breaker['bottom']['r']),2,"0",STR_PAD_LEFT).
		str_pad(dechex($breaker['bottom']['g']),2,"0",STR_PAD_LEFT).
		str_pad(dechex($breaker['bottom']['b']),2,"0",STR_PAD_LEFT);
	
	$img->startcol = $breaker['top']['hex']." ";
	$img->endcol = $breaker['bottom']['hex'];
}

if (isset($_GET['height'])) $img->height = $_GET['height'];
if (isset($_GET['width'])) $img->width = $_GET['width'];
  
if(isset($_GET['top']) && $_GET['top'] != "") $img->startcol = $_GET['top'];
if(isset($_GET['bottom']) && $_GET['bottom'] != "") $img->endcol = $_GET['bottom'];

$img->draw();

?>