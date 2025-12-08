<?
require_once ("dompdf/dompdf_config.inc.php");
$id = 4000;
$cab = '<title>laboratorio celldiagnostic</title>
  <style type="text/css">
 
  @page { margin: 2.5cm 1cm 2.5cm 2cm;} 
  img {  
		position: absolute;
        	top: 0.8cm;  
		left: 8.2cm;
		width: 3.5cm;
		
	}
	#qr {  
		position: absolute;
        	top: 13.6cm;  
		left: 4.85cm;
		width: 2.5cm;
		
	}
  #ho{
    position: absolute;
    top: 2.7cm;
    
		
  }
  table {
		font-size: 14px;
		width:12cm; 
		left:-1cm;
	}
  table#edad{
	position: absolute;
	top: 0.0cm;
	left: 9.8cm;
  }	
  td.sep {
  	width:0.5cm;
  }
  </style>
</head><body>';
$idsol = isset($_GET["idsol"]) ? $_GET["idsol"] : '';
include ('../bdlaboratorio.php');
$base = new bdlaboratorio();
$todo = $base->todosolicitud($idsol);
$cab= cabecera($todo,$cab, $idsol);

function cabecera($todo,$cab,$idsol){
	return $cab . '<table ><tr><td colspan=2>Nombre:' . $todo[0]['nombres'] .
    ' ' . $todo[0]['apellidos'] . '</td></tr></table>
<table id="edad"><tr><td colspan="2">Edad:' . $todo[0]['edad'] .
    '</td></tr></table><table>
<tr><td>Fecha:' . $todo[0]['fecha'] . '</td><td></td><td></td><td></td></tr><tr><td>No.:' . $todo[0]['numerodia'] .
    '</td></tr></table>
<table><tr><td><img src="http://localhost/laboratorio0.7/reportes/codigob.php?codigo=' .
    $idsol . '"></td></tr>
</table><table id="ho">';
}


//QR    	
$PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
$PNG_WEB_DIR = 'temp/';
include "phpqrcode/qrlib.php";
if (!file_exists($PNG_TEMP_DIR))
    mkdir($PNG_TEMP_DIR);
$filename = $PNG_TEMP_DIR . 'test.png';
$errorCorrectionLevel = 'H';
$matrixPointSize = 3;
$mensajeqr = 'Laboratorio: Cell-Diagnostic nombre Paciente: '. $todo[0]['nombres'] .' ' . $todo[0]['apellidos'].' solicitud: '. $idsol. ' fecha: '. $todo[0]['fecha'] . ' Edad:' . $todo[0]['edad'] . ' atencion del dia: '.$todo[0]['numerodia'];
$matrixPointSize = min(max(10, 1), 10);
$filename = $PNG_TEMP_DIR . 'test' . md5($mensajeqr . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
QRcode::png($mensajeqr, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
//display generated file
$cab = $cab . '<img id="qr" src="' . $PNG_WEB_DIR . basename($filename) . '" />';  


$resultados = $base->resultados($idsol);
$pa = 3;
$pagina=0;
for ($i = 0; $i < count($resultados); $i++) {
	  $array = explode("<br>", $resultados[$i]['valor'] );
        $pa= $pa+count($array)-1;  //esto aumenta el numero de filas que tiene en total el valor del resultado
    if (($i == 0) || ($resultados[$i]['analisis'] != $resultados[$i - 1]['analisis'])) {
        if (($i == 0) || ($resultados[$i]['categoria'] != $resultados[$i - 1]['categoria'])) {
            
            if ($pa >= 18) {
                $pagina++;
                $cab = $cab . '</table>';
                $cab = $cab . '<div style="page-break-before: always;"></div>';
                $cab = cabecera($todo,$cab, $idsol);
                
                $pa = 3;
            }
            $pa++; 
            $cab = $cab . '<tr><th colspan="5"><center>' . $resultados[$i]['categoria'] .
                '</center></th></tr>';
        }
        if ($pa >= 20) {
                $pagina++;
                $cab = $cab . '</table>';
                $cab = $cab . '<div style="page-break-before: always;"></div>';
                $cab = cabecera($todo,$cab, $idsol);   
                $pa = 3;
            }
       $pa++;

        $cab = $cab .  '<tr><th>' . $resultados[$i]['analisis'] .
            '</th><th colspan="3"></th></tr>';
             $pa++;
			$cab=$cab.'<tr><th>Prueba</th><th></th><th>Resultado</th><th colspan="2">Parametro</th></tr>';
    }
    if (!(($resultados[$i]['valor'] == '')||($resultados[$i]['valor'] == ' '))) {
        $pa++;
			


        $cab = $cab . '<tr><td>' . $resultados[$i]['resultado'] .
            '</td><td class="sep"></td>';

		if ((($resultados[$i]['parametroinferior']=='0')&&($resultados[$i]['parametrosuperior']=='0'))||(($resultados[$i]['parametroinferior']=='')||($resultados[$i]['parametroinferior']==''))){

      
	   	$cab = $cab . '<td colspan="3">' . $resultados[$i]['valor'] . ' ' . $resultados[$i]['unidadmedicion'] .'</td>';
	   	
		}else{
			$cab = $cab . '<td>' . $resultados[$i]['valor'] . ' ' . $resultados[$i]['unidadmedicion'] .
            '</td><td class="sep"></td>';
			$cab=$cab.'<td>' . $resultados[$i]['parametroinferior'] . ' - ' . $resultados[$i]['parametrosuperior'].' '.$resultados[$i]['unidadmedicion'].'</td>';
		}
      $cab=$cab.'</tr>';
    }
    
    if ($pa >= 20) {
 		  $pagina++;
        $cab = $cab . '</table>';
        $cab = $cab . '<div style="page-break-before: always;"></div>';
        $cab = cabecera($todo,$cab, $idsol);
        
        $pa = 3;

$mensajeqr = 'Laboratorio: Cell-Diagnostic nombre Paciente: '. $todo[0]['nombres'] .' ' . $todo[0]['apellidos'].' solicitud: '. $idsol. ' fecha: '. $todo[0]['fecha'] . ' Edad:' . $todo[0]['edad'] . ' atencion del dia: '.$todo[0]['numerodia'];
$matrixPointSize = min(max(10, 1), 10);
$filename = $PNG_TEMP_DIR . 'test' . md5($mensajeqr . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
QRcode::png($mensajeqr, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
//display generated file
$cab = $cab . '<img id="qr" src="' . $PNG_WEB_DIR . basename($filename) . '" />';  
    }
}
// aqui para codigo qr

$mensajeqr = 'Laboratorio: Cell-Diagnostic nombre Paciente: '. $todo[0]['nombres'] .' ' . $todo[0]['apellidos'].' solicitud: '. $idsol. ' fecha: '. $todo[0]['fecha'] . ' Edad:' . $todo[0]['edad'] . ' atencion del dia: '.$todo[0]['numerodia'];
$matrixPointSize = min(max(10, 1), 10);
$filename = $PNG_TEMP_DIR . 'test' . md5($mensajeqr . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
QRcode::png($mensajeqr, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
//display generated file
$cab = $cab . '<img id="qr" src="' . $PNG_WEB_DIR . basename($filename) . '" />';  





$cab = $cab . '</table>';
$s = $cab;
$html = $s . '</body>';

$h['compress'] = 1;
$h['Attachment'] = 0;
$html = $s;
$html;
//Obtenemos el código html de la página web que nos interesa

$dompdf = new DOMPDF();
// Creamos una instancia a la clase
$hoja = array(
    0.0,
    0.0,
    400.0,
    550.2);
$dompdf->set_paper($hoja);
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("resultados.pdf", $h);
exit(); 
//array $options: accepted options are:

// 'compress' = > 1 or 0 - apply content stream compression, this is on (1) by default
// 'Attachment' => 1 or 0 - if 1, force the browser to open a download dialog, on (1) by default


$pdf->Output();

?>
