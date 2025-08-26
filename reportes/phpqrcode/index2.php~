<?php
$PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
$PNG_WEB_DIR = 'temp/';
include "qrlib.php";
if (!file_exists($PNG_TEMP_DIR))
    mkdir($PNG_TEMP_DIR);
$filename = $PNG_TEMP_DIR . 'test.png';
$errorCorrectionLevel = 'H';
$matrixPointSize = 4;
$mensajeqr = "miguel angel vargas ortega";
$matrixPointSize = min(max(10, 1), 10);
$filename = $PNG_TEMP_DIR . 'test' . md5($mensajeqr . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
QRcode::png($mensajeqr, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
//display generated file
$qr= '<img src="' . $PNG_WEB_DIR . basename($filename) . '" />';
//config form
