<?php
session_start();
$random_alpha = md5(rand());
$captcha_code = substr($random_alpha, 0, 6);
$_SESSION["captcha_code"] = $captcha_code;
$target_layer = imagecreatetruecolor(90,20);
$captcha_background = imagecolorallocate($target_layer, 104, 132, 135);
imagefill($target_layer,0,0,$captcha_background);
$captcha_text_color = imagecolorallocate($target_layer, 250, 250, 250);
imagestring($target_layer, 5, 19, 2, $captcha_code, $captcha_text_color);
header("Content-type: image/png");
imagepng($target_layer);
?>