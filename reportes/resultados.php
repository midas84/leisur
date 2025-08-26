 <?php
    require_once("dompdf/dompdf_config.inc.php");
    $id = 4000;
    $cab = '<title>laboratorio celldiagnostic</title>
  <style type="text/css">
 
@page { margin: 1cm 2cm 0.5cm 2cm;} 
#codigobarras {  
		position: absolute;
        top: 2.3cm;  
		left: 13.5cm;
		width: 3.5cm;	
	}
#fechaimpresion {
        font-size: 13px;
        position: absolute;
        left: 10.8cm;
        top: -0.5 cm;
    }
 #logo {  
		position: absolute;
        top: -0.7cm;  
		left: 0cm;
		width: 3.5cm;	
	}
#qr {  
		position: absolute;
        top: 24.4cm;  
		left: 15.5cm;
		width: 2.2cm;
	}
  #ho{
    position: absolute;
    top: 3.7cm;
    left: 0cm;	
  }
#pie{
    position: absolute;
    top: 24 cm;
    left: 0.5 cm	
  }
    #direcciones{
    position: absolute;
    top: 0 cm;	
    left: 12cm;
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
        padding-left: 7px;
        padding-top: 5px;
        position: absolute;
	    top: 2cm;
        border: 2px solid black;
        width: 100%; 
        height: 55px; 
        font-size: 13px;
    }

th {
    text-align: left; 
    
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
    $cab = $cab.cabecera($todo,  $idsol);
    
    function cabecera($todo,  $idsol)
    {
        
        $nacimiento = new DateTime($todo[0]['fechanacimiento']);
        $hoy = new DateTime();
        $diferencia = $hoy->diff($nacimiento);
        if ($diferencia->y >= 1)
        {
            $edad= $diferencia->y. " a&ntilde;os";
        } 
        else 
        {
            $edad= $diferencia -> m . " meses " .  $diferencia->d . " d&iacute;as ";
        }

        if ($todo[0]['ci'] == '') {
            $ci = "-";
        } else {
            $ci = $todo[0]['ci'];
        }
         
        date_default_timezone_set('America/La_Paz');
        $fechadeimpresion= date('d/m/Y H:i:s');
    
        return  ' 
            <div id="cabecera">
            <div style="width: 370px; display: inline-block;">Paciente:' . $todo[0]['nombres'] .
            ' ' . $todo[0]['apellidos'] .
            '<br>CI:' . $ci .
            '<br> Doctor: '.$todo[0]['nombredoctor'].'
            </div><div style=" display: inline-block;">Edad:' . $edad . '<br>Pac./d&iacute;a. :' . $todo[0]['numerodia'] .
            '<br>Fecha:' . $todo[0]['fecha'] . '</div></div>
            <img id="logo" src="logo.jpg" />
                        <img id="titulo" src="titulo.jpg" />
            <img id="codigobarras" src="http://localhost/laboratorio0.71/reportes/codigob.php?codigo=' .
            $idsol . '">
            <br>
            <img id="marca" src="logo.jpg"  /><img id="instagram" src="instagramico.jpg" ><img id="facebook" src="facebookico.jpg" >
            <img id="lugar" src="lugarico.jpg" >
            <div id="direcciones" font-size: 13px; >75777151 / 76123455 <br /> cell.diagnostic.sc@gmail.com </br>Reg. SEDES: 242/RC/705/NII</div>
            <div id="pie" style="font-size: 13px;" >
            <div id="fechaimpresion">Fecha de impresi&oacute;n: '.$fechadeimpresion.'</div><hr /> <div style=" padding-left: 20px;"></div>Santa Cruz: Calle Cuellar No. 152 entre 21 de Mayo y Libertad <br>Sucre: Destacamento 111 No. 165; Descatamento 130 No. 296A <br /> @laboratoriocelldiagnostic <br />  Laboratorio Cell-Diagnostic SC </div></diV>
            <table id="ho">';
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
     $bandera = false;





     // aqui se elabora el contenido del qr

    for ($i = 0; $i < count($todo); $i++) {
        if (($todo[$i]['idana']==226)||($todo[$i]['idana']==227)||($todo[$i]['idana']==228)||($todo[$i]['idana']==229)||($todo[$i]['idana']==237)){
            $bandera=true;
        }    
    }
	if ($bandera){
        for ($i = 0; $i < count($resultados); $i++) {
            if (($i == 0) || ($resultados[$i]['analisis'] != $resultados[$i - 1]['analisis'])) {
               $mensajeqr = $mensajeqr .  ' ' . $resultados[$i]['analisis'] .'|';
            }
            if (!(($resultados[$i]['valor'] == '')||($resultados[$i]['valor'] == ' '))) {
               $mensajeqr = $mensajeqr . ' ' . $resultados[$i]['resultado'] .' ';
                  $mensajeqr = $mensajeqr . '(res:' . $resultados[$i]['valor'] . ' ' . $resultados[$i]['unidadmedicion'] .')';
                  $mensajeqr = $mensajeqr . '(tipo de muestra:' . $resultados[$i]['muestra'] .')';
                  
                $mensajeqr = $mensajeqr.'|'; 	  
             }	
        }
    }
    $matrixPointSize = min(max(10, 1), 10);
    $filename = $PNG_TEMP_DIR . 'test' . md5($mensajeqr . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
    QRcode::png($mensajeqr, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
    $banderaprimerapagina=true;
    for ($i = 0; $i < count($resultados); $i++) {
        $array = explode("<br>", $resultados[$i]['valor']);
        $pa = $pa + count($array) - 1;  //esto aumenta el numero de filas que tiene en total el valor del resultado
        if (($i == 0) || ($resultados[$i]['analisis'] != $resultados[$i - 1]['analisis'])) {
            if (($i == 0) || ($resultados[$i]['categoria'] != $resultados[$i - 1]['categoria'])) {

                if ($banderaprimerapagina) {
                    $banderaprimerapagina=false;
                    $cab = $cab . '<img id="qr" src="' . $PNG_WEB_DIR . basename($filename) . '" />';//aqui añador el qr agregarlo en la cabezera
                }
                else {
                    $pagina++;
                    $cab = $cab . '</table>';
                    $cab = $cab . '<div style="page-break-before: always;"></div>';
                    $cab = $cab . cabecera($todo, $idsol);
                    $cab = $cab . '<img id="qr" src="' . $PNG_WEB_DIR . basename($filename) . '" />';//aqui añador el qr agregarlo en la cabezera
                    $pa = 3;
                }
                $pa++; 
                $cab = $cab . '<tr><th colspan="3"><center>' . $resultados[$i]['categoria'] .
                    '</center></th></tr>';
            }
            if ($pa >= 37) {
                $pagina++;
                $cab = $cab . '</table>';
                $cab = $cab . '<div style="page-break-before: always;"></div>';
                $cab = $cab . cabecera($todo, $idsol);
                $pa = 3;
            }
            $pa++;
            $cab = $cab .  '<tr><th>' . $resultados[$i]['analisis'] .
                '</th><th colspan="2"></th></tr>';
            $pa++;
            $cab=$cab.'<tr ><th style="width: 200px;">Prueba</th><th style="width: 200px;">Resultado</th><th style="width: 200px;">Parametro</th></tr>';
                }
            if (!(($resultados[$i]['valor'] == '')||($resultados[$i]['valor'] == ' '))) {
                $pa++;
                    
        
        
                $cab = $cab . '<tr><td>' . $resultados[$i]['resultado'] .
                    '</td>';
        
                if ((($resultados[$i]['parametroinferior']=='0')&&($resultados[$i]['parametrosuperior']=='0'))||(($resultados[$i]['parametroinferior']=='')||($resultados[$i]['parametroinferior']==''))){
        
              
                   $cab = $cab . '<td colspan="2">' . $resultados[$i]['valor'] . ' ' . $resultados[$i]['unidadmedicion'] .'</td>';
                   
                }else{
                    $cab = $cab . '<td>' . $resultados[$i]['valor'] . ' ' . $resultados[$i]['unidadmedicion'] .
                    '</td>';
                    $cab=$cab.'<td>' . $resultados[$i]['parametroinferior'] . ' - ' . $resultados[$i]['parametrosuperior'].' '.$resultados[$i]['unidadmedicion'].'</td>';
                }
              $cab=$cab.'</tr>';
            }
            
            if ($pa >= 37) {
                   $pagina++;
                $cab = $cab . '</table>';
                $cab = $cab . '<div style="page-break-before: always;"></div>';
                $cab = $cab . cabecera($todo, $idsol);
                
                $pa = 3;
        
        
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