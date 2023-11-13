<?php

extract($_REQUEST, EXTR_SKIP);
$im = imagecreate(75, 30);
$bg = imagecolorallocate($im,rand (150,200),rand (150,200), rand (150,200));
$textcolor = imagecolorallocate($im, 0, 0, 255);
$d_SVK="TYSYHBR";
$prePASS=str_replace("0","1",substr(md5($m_TEXT."$d_SVK"), 5, 5));
imagestring($im, 5, rand (5,15), rand (5,10), "$prePASS", $textcolor);
$rot=imagerotate ($im , rand (-10,10) , $bg);
$imd = imagecreate(550, 40);
imagecopyresized($imd, $rot, rand (100,350), 0, 0, 0, rand (100,150), rand (30,40), 75, 30);

// Output the image
header('Content-type: image/png');

imagepng($imd);
imagedestroy($im);
imagedestroy($rot);
imagedestroy($imd);

?> 
