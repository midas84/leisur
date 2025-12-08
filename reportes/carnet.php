<?php
require_once("dompdf/dompdf_config.inc.php");
$grupoSanguineo=$_GET['gruposanguineo'];//'O';
$nombre=$_GET['nombres'];//'Miguel Angel';
$apellido=$_GET['apellidos'];//'Vargas';
//$fechanac=$_GET['fechanac'];//'2012-12-12';
$factorrh=$_GET['factorrh'];//'negativo';
$id=$_GET['id'];//'10003434';
$seguro=$_GET['seguro'];
$observacion=$_GET['observacion'];
$telefono=$_GET['telefono'];
$cab='<head>
  <title>Mi primera página con estilo</title>
  <style type="text/css">
  @page { margin: 0cm 0cm 00cm 0cm;} 
  #image {  
		left: 12px;		
		position: absolute;
		width:8 cm;
	}
 #image2 {  
		position: absolute;
		left: 68px;
		width:8 cm;
	}
  #serial{
		
		position: absolute;  
		top: 140px;  
		left: 160px;
		width:3.7cm;
  }	
  table {
		font-size: 10px;
		position: absolute;  
		top: -05px;  
		left: 12px;  
	}
  </style>
</head><body>';   
$s=$cab.'<img id="image" src="cred2.jpg">
<table  >
<tr><td height="50"> </td></tr>
<tr><td  height="10"><b>Nombre:</b></td><td colspan="3">'.strtoupper($nombre).' '.strtoupper($apellido).'</td></tr>
<tr><td  height="10"><b>Telefono:</b></td><td>'.$telefono.'</td></tr>
<tr><td height="10"><b>Grupo:  </b>"'.strtoupper($grupoSanguineo).'"</td><td ><b>Factor RH: </b>'.strtoupper($factorrh).'</td></tr>
<tr><td  height="10"><b>Paciente:</b></td><td>'.strtoupper($seguro).'</td></tr>
<tr><td  height="10"><b>Observaci&oacute;n:</b></td><td>'.strtoupper($observacion).'</td></tr>
<tr><td></td></tr>
</table>
<img id="serial" src="http://10.10.10.2/laboratorio0.7/reportes/codigob.php?codigo='.$id.'" />
<div style="page-break-before: always;"></div><img id="image2" src="credencialp.jpg">
';
$html=$s.'</body>';

$h['compress']=1;
$h['Attachment']=0;
$html = $s;//"<img src='http://10.10.10/laboratorio0.7/reportes/barcodegen/test.php' /> <table><tr><td>jgjghjhg</td><td>ghjghjghjgh</td></tr></table>";
//file_get_contents('http://localhost/laboratorio/barcodegen/test.php');
//Obtenemos el código html de la página web que nos interesa
$dompdf = new DOMPDF();
// Creamos una instancia a la clase
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("ejemplo.pdf",$h);
exit();
//array $options: accepted options are:

   // 'compress' = > 1 or 0 - apply content stream compression, this is on (1) by default
   // 'Attachment' => 1 or 0 - if 1, force the browser to open a download dialog, on (1) by default 

  
  

$pdf->Output();
?>
