<?php
$data = $_GET['data'];
$size = isset($_GET['size']) ? $_GET['size'] : '200x200';
$logo = 'https://wesocial.space/logo.png';

$QR = imagecreatefrompng('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs='.$size.'&chl='.urlencode($data));
if ($logo !== FALSE){
	$logo = imagecreatefromstring(file_get_contents($logo));

	$QR_width = imagesx($QR);
	$QR_height = imagesy($QR);

	$logo_width = imagesx($logo);
	$logo_height = imagesy($logo);

	$logo_qr_width = $QR_width/3;
	$scale = $logo_width/$logo_qr_width;
	$logo_qr_height = $logo_height/$scale;

	imagecopyresampled($QR, $logo, $QR_width/3, $QR_height/3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
}
imagepng($QR,'../qr/'.$data.'.png');
imagedestroy($QR);
?>