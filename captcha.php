<?php
session_start();
header('Content-Type: image/jpeg');

$text = $_SESSION['captcha'];

$font_size = 25;
$image_width = 140;
$image_height = 40;

$image = imagecreate($image_width, $image_height);
imagecolorallocate($image, 255, 255, 255);

$font_color = imagecolorallocate($image, 0, 0, 0);
$font_style = realpath('HPSimplified_Rg.ttf');

imagettftext($image, $font_size, 0, 15, 30, $font_color, $font_style, $text);
imagejpeg($image);