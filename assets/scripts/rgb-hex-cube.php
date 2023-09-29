<?php
/**
 * Project: RGB HEX CUBE
 * Author: Marco Spitzbarth
 * Date: 15. Mai, 2012
 * Website: https://ibulla.com/rgb-algorithm-cube.php
 * Description: create an rgb cube from 00 fo FF
 * Version: 1.0
 *
 * Copyright (c) 2012, Marco Spitzbarth
 * All Rights Reserved.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */


function RGB_CUBE($rgb){
  $printer = "<div class='bigbox'>";
    switch($rgb){
      case 0:
      for ($z=255; $z>=16; $z=$z-16){
      for ($x=255; $x>=16; $x=$x-16){
      $r = str_pad(dechex($z),2);
      $g = str_pad(dechex($x),2);
      $b = "00";
      $hex_color =$r.$g.$b;
      $printer.= "<div class='pixel' style='background-color:#".$hex_color.";'></div>";
      }}
      break;
      case 1:
      for ($z=255; $z>=16; $z=$z-16){
      for ($x=16; $x<=255; $x=$x+16){
      $r = str_pad(dechex($z),2);
      $g = "00";
      $b = str_pad(dechex($x),2);
      $hex_color =$r.$g.$b;
      $printer.= "<div class='pixel' style='background-color:#".$hex_color.";'></div>";
      }}
      break;
      case 2:
      for ($z=16; $z<=255; $z=$z+16){
      for ($x=255; $x>=16; $x=$x-16){
      $r = "00";
      $g = str_pad(dechex($x),2);
      $b = str_pad(dechex($z),2);
      $hex_color =$r.$g.$b;
      $printer.= "<div class='pixel' style='background-color:#".$hex_color.";'></div>";
      }}
      break;
    }
  $printer.= "</div>";
  return $printer;
}
$cube = "";
for ($i=0;$i<3;$i++){
  $cube.= "<div style='float:left;'>".RGB_CUBE($i)."</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RGB CUBE</title>
    <style>
        .bigbox {
            width: 315px; /* Set the body width to 800px */
            height: 315px;
            padding: 0px;
            margin: 0px;
        }

        .pixel {
            float: left;
            width: 20px;
            height: 20px;
            padding: 0px;
            overflow: auto;
            margin: 0px 1px 1px 0px;
        }
        .wrap{
          width: 630px;
        }
    </style>
</head>
<body>
  <div class="wrap">
    <?php
    echo $cube;
    ?>
  </div>
</body>
</html>
