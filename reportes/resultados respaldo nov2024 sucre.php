 <?php
    require_once("dompdf/dompdf_config.inc.php");
    $id = 4000;
    $cab = '<title>laboratorio celldiagnostic</title>
  <style type="text/css">
 
@page { margin: 1cm 2cm 0.5cm 2cm;} 
  img {  
		position: absolute;
        top: 2.3cm;  
		left: 14cm;
		width: 3.5cm;	
	}
 #logo {  
		position: absolute;
        top: -0.7cm;  
		left: -1cm;
		width: 3.5cm;	
	}
#qr {  
		position: absolute;
        top: 24.2cm;  
		left: 15.5cm;
		width: 2.2cm;
	}
  #ho{
    position: absolute;
    top: 3.7cm;	
  }
#pie{
    position: absolute;
    top: 24cm;
    left: 0.5 cm	
  }
    #direcciones{
    position: absolute;
    top: 0 cm;	
    left: 12.5cm;
  }
    #facebook, #instagram, #lugar{
    position: absolute;
    top: 25.72 cm;	
    left: 0cm;
    width: 0.3cm;
  }
    #instagram{ 
    top: 25.30 cm;
    }
    #lugar{ 
    top: 24.4 cm;
    }
    #titulo{
    position: absolute;
    top: -0.5cm;	
    left: 2.8cm;
    width:9cm; 
  }
  table {
		font-size: 13px;
		width:19cm; 
		left:-1cm;
	}
#cabecera{
        position: absolute;
	    top: 2cm;
        border:1;
        width: 108%; 
    }
table#edad{
	position: absolute;
	top: 0.0cm;
	left: 11.8cm;
}	
td.sep {
    width:0.3cm;
}
#marca {
            position: absolute;
            top: 9.0cm;
            left: 4.5cm;
            width: 70%;
            transform: translate(-50%, -50%);
            opacity: 0.1; /* Ajusta la opacidad según necesites */
            pointer-events: none; /* Para que no interfiera con otros elementos */
        }  </style>
</head><body>';
    $idsol = isset($_GET["idsol"]) ? $_GET["idsol"] : '';
    include('../bdlaboratorio.php');
    $base = new bdlaboratorio();
    $todo = $base->todosolicitud($idsol);
    $cab = cabecera($todo, $cab, $idsol);
    function cabecera($todo, $cab, $idsol)
    {
        if ($todo[0]['ci'] == '') {
            $ci = "-";
        } else {
            $ci = $todo[0]['ci'];
        }
        $cab = $cab . '<img id="logo" src="http://localhost/laboratorio0.71/reportes/logo.jpg" />
    <img id="titulo" src="http://localhost/laboratorio0.71/reportes/titulo.jpg" />';
        return $cab . '
    <table  border="1" cellspacing="0" cellpadding="5" id="cabecera">
                    <tr><td><table><tr><td colspan=2>Paciente:' . $todo[0]['nombres'] .
            ' ' . $todo[0]['apellidos'] .
            '<br>CI:' . $ci .
            '<br> Doctor:</td></tr></table>
<table id="edad"><tr><td colspan="2">Edad:' . $todo[0]['edad'] . '<br>Pac./dia. :' . $todo[0]['numerodia'] .
            '<tr><td>Fecha:' . $todo[0]['fecha'] . '</td><td></td><td></td><td></td></td></tr></table></td></tr>
<table><tr><td><img src="http://localhost/laboratorio0.71/reportes/codigob.php?codigo=' .
            $idsol . '"></td></tr>
</table></tr></td></table>

        <img id="marca" src="http://localhost/laboratorio0.71/reportes/logo.jpg"  /><img id="instagram" src="http://localhost/laboratorio0.71/reportes/instagramico.jpg" ><img id="facebook" src="http://localhost/laboratorio0.71/reportes/facebookico.jpg" >
        <img id="lugar" src="http://localhost/laboratorio0.71/reportes/lugarico.jpg" >
<div id="direcciones" >Cel.:76116115 <br /> cell.diagnostic@gmail.com <br />Reg.SEDES CH. N. 10/2024 </div><table id="ho"><div id="pie"><hr /> Sucre: Destacamento 111 No. 165; Descatamento 130 No. 296A <br/>Santa Cruz: Calle Cuellar No. 152 entre 21 de Mayo y Libertad <br /> @laboratoriocelldiagnostic <br />  Laboratorio Cell-Diagnostic </diV>';
    }
    $resultados = $base->resultados($idsol);
    $pa = 3;
    $pagina = 0;
    //QR   

    $PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
    $PNG_WEB_DIR = 'temp/';
    include "phpqrcode/qrlib.php";
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    $filename = $PNG_TEMP_DIR . 'test.png';
    $errorCorrectionLevel = 'L';
    $matrixPointSize = 3;
    $mensajeqr = 'Cell-Diagnostic Paciente: ' . $todo[0]['nombres'] . ' ' . $todo[0]['apellidos'] . 'ci:' . $todo[0]['ci'] . ' solicitud: ' . $idsol . ' fecha: ' . $todo[0]['fecha'] . ' Edad:' . $todo[0]['edad'];
    $bandera = true;
   
    $matrixPointSize = min(max(10, 1), 10);
    $filename = $PNG_TEMP_DIR . 'test' . md5($mensajeqr . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
    QRcode::png($mensajeqr, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
    //display generated file
    $cab = $cab . '<img id="qr" src="' . $PNG_WEB_DIR . basename($filename) . '" />';

    //qr
$banderaprimerapagina=true;
    for ($i = 0; $i < count($resultados); $i++) {
        $array = explode("<br>", $resultados[$i]['valor']);
        $pa = $pa + count($array) - 1;  //esto aumenta el numero de filas que tiene en total el valor del resultado
        if (($i == 0) || ($resultados[$i]['analisis'] != $resultados[$i - 1]['analisis'])) {
            if (($i == 0) || ($resultados[$i]['categoria'] != $resultados[$i - 1]['categoria'])) {

                if ($banderaprimerapagina) {
                    $banderaprimerapagina=false;
                }
                else {
                    $pagina++;
                    $cab = $cab . '</table>';
                    $cab = $cab . '<div style="page-break-before: always;"></div>';
                    $cab = cabecera($todo, $cab, $idsol);

                    $pa = 3;
                }
                $pa++;
                $cab = $cab . '<tr><th colspan="5"><center>' . $resultados[$i]['categoria'] .
                    '</center></th></tr>';
            }
            if ($pa >= 40) {
                $pagina++;
                $cab = $cab . '</table>';
                $cab = $cab . '<div style="page-break-before: always;"></div>';
                $cab = cabecera($todo, $cab, $idsol);
                $pa = 3;
            }
            $pa++;
            $cab = $cab .  '<tr><th>' . $resultados[$i]['analisis'] .
                '</th><th colspan="3"></th></tr>';
            $pa++;
            $cab = $cab . '<tr><th>Prueba</th><th></th><th>Resultado</th><th colspan="2">Parametro</th></tr>';
        }
        if (!(($resultados[$i]['valor'] == '') || ($resultados[$i]['valor'] == ' '))) {
            $pa++;
            $cab = $cab . '<tr><td>' . $resultados[$i]['resultado'] .
                '</td><td class="sep"></td>';
            if ((($resultados[$i]['parametroinferior'] == '0') && ($resultados[$i]['parametrosuperior'] == '0')) || (($resultados[$i]['parametroinferior'] == '') || ($resultados[$i]['parametroinferior'] == ''))) {
                $cab = $cab . '<td colspan="3">' . $resultados[$i]['valor'] . ' ' . $resultados[$i]['unidadmedicion'] . '</td>';
            } else {
                $cab = $cab . '<td>' . $resultados[$i]['valor'] . ' ' . $resultados[$i]['unidadmedicion'] .
                    '</td><td class="sep"></td>';
                $cab = $cab . '<td>' . $resultados[$i]['parametroinferior'] . ' - ' . $resultados[$i]['parametrosuperior'] . ' ' . $resultados[$i]['unidadmedicion'] . '</td>';
            }
            $cab = $cab . '</tr>';
        }
        if ($pa >= 40) {
            $pagina++;
            $cab = $cab . '</table>';
            $cab = $cab . '<div style="page-break-before: always;"></div>';
            $cab = cabecera($todo, $cab, $idsol);
            $pa = 3;
            //display generated file
            $cab = $cab . '<img id="qr" src="' . $PNG_WEB_DIR . basename($filename) . '" />';
        }
    }
    // aqui para codigo qr
    if (file_exists($PNG_WEB_DIR . basename($filename))) {
        unlink($PNG_WEB_DIR . basename($filename));
    }

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
    $dompdf = new DOMPDF();
    // Creamos una instancia a la clase
    $hoja = array(
        0.0,     // Margen izquierdo
        0.0,     // Margen superior
        610.0,   // Margen derecho (215.9 mm)
        793.0    // Margen inferior (279.4 mm)
    );
    $dompdf->set_paper($hoja);
    $dompdf->load_html($html);
    $dompdf->render();
    if (file_exists($PNG_WEB_DIR . basename($filename))) {
        unlink($PNG_WEB_DIR . basename($filename));
    }
    //echo $html;
    $dompdf->stream("resultados.pdf", $h);
    exit();
    $pdf->Output();

    ?>