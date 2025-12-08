<?php
require_once ("dompdf/dompdf_config.inc.php");
$html='<head>
  <title>celldiagnostic</title>
  <style type="text/css">
	@page { margin: 0cm 0cm 0cm 1cm;} 
  img {  
		position: relative;
		
	}
  	
  table {
		font-size: 10px;
		  
	}
  </style>
</head><body>';   

$idsol=isset($_GET["idsol"]) ? $_GET["idsol"] : '' ;
include('../bdlaboratorio.php');
	$base=new bdlaboratorio();
$todo=$base->todosolicitud($idsol);	

$html.= '<table ><tr><th colspan="4" align="center">Ficha de atencion</th></tr>';
$html.= '<tr><td colspan="4"><img width="200" src="codigob.php?codigo='.$idsol.'"></td></tr>
<tr><td>nombrepaciente:</td><td>'.$todo[0]['nombres'].' '.$todo[0]['apellidos'].'</td><td>Diagnostico</td><td>'.$todo[0]['diagnostico'].'</td></tr>
<tr><td>Fecha</td><td>'.$todo[0]['fecha'].'</td><td>Numero atencion del dia</td><td>'.$todo[0]['numerodia'].'</td></tr>

<tr><td>precio total</td><td>'.$todo[0]['precio'].'</td></tr>';

$html.= '<tr><th colspan="2">No. de analisis</th><th colspan="2"> Nombre</th></tr>';
for ($i=0; $i<count($todo); $i++){
	$indice=$i+1;
	$html.= '<tr><td colspan="2">'.$indice.'</td><td colspan="2">'.$todo[$i]['analisis'].'</td></tr>';
}

$html.= '<tr><td colspan="4"><input type="button" name="imprimir" value="Imprimir" onclick="window.print();"></td></tr>';
$html.= '</table>';
$html.= '</body>';

$h['compress']=1;
$h['Attachment']=0;
//$html = $s;//"<img src='http://10.10.10/laboratorio/reportes/barcodegen/test.php' /> <table><tr><td>jgjghjhg</td><td>ghjghjghjgh</td></tr></table>";
//file_get_contents('http://localhost/laboratorio/barcodegen/test.php');
//Obtenemos el c�digo html de la p�gina web que nos interesa
/* $dompdf = new DOMPDF();
// Creamos una instancia a la clase
$hoja = array(
    0.0,
    0.0,
    400.0,
    550.2);
$dompdf->set_paper($hoja);
$dompdf->load_html($html);
$dompdf->render();

$dompdf->stream("ejemplo.pdf", $h);
exit();


$pdf->Output();*/
echo $html;
?>
