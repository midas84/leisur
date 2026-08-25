<?php
ob_start();
    require_once("dompdf/dompdf_config.inc.php");

    $cab = '<head><meta charset="UTF-8"><title>Leisur</title>
  <style type="text/css">
@page { margin: 1cm 2cm 0.5cm 6cm;} 
.rotar{
    position absolute;
    transform:rotate(90deg);
    transform-origin: left top;
    top: 354pt;
    left:0;
}
#titulop{
    position: absolute;
    top: -0.5cm;	
    left: 3.5cm;
    font-size: 50px;
    font-family: "Arial Black", Gadget, sans-serif;
    font-weight: 900;
    color: #3ab5d6;
}
#subtitulo{
    position: absolute;
    top: 1cm;	
    left: 3.6cm;
    font-family: "Arial";
    color: #ffffff;
    font-size: 11px;
}
 #resaltar{
    
    font-family: "Arial";
   
    font-size: 15px;
}
#datos{
white-space: nowrap;       
    overflow: hidden;          
    text-overflow: ellipsis;   
    width: 12cm; 
}
#derechasuperior{
position: absolute;
        top: -1.8cm;  
		left: 16.5cm;

}

#codigobarras {  
		position: absolute;
        top: 2.3cm;  
		left: 13.5cm;
		width: 3.5cm;	
	}
#fechaimpresion {
        font-size: 13px;
        position: absolute;
        left: 2.5cm;
        top: 1cm;
    }
 #logo {  
		position: absolute;
        top: -1cm;  
		left: -2cm;
		width: 23.3 cm;	
        height: 12.6 cm;
        z-index: -1;
	}
#qr {  
		position: absolute;
        top: 9cm;  
		left: 18cm;
		width: 2.2cm;
	}
#ho{
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    position: absolute;
    top: 7cm;
    left: 0cm;	
  }
#pie{
    position: absolute;
    top: 8 cm;
    left: 0 cm	
  }
#direcciones{
    position: absolute;
    top: 9.5 cm;	
    left: 2.5cm;
    font-family: "Arial";
    color: #8c58c8;
    font-size: 13px;
  }
#celular{
    position: absolute;
    top: 9.5 cm;	
    left: 7.4cm;
    font-family: "Arial";
    color: #8c58c8f;
    font-size: 13px;
  }
#email{
    position: absolute;
    top: 9.5 cm;	
    left: 11.5cm;
    font-family: "Arial";
    color: #8c58c8;
    font-size: 13px;
  }
#correo, #whatsapp, #lugar{
    position: absolute;
    width: 0.3cm;
  }
#whatsapp{ 
    top: 9.55 cm;
    left: 6.9cm;
    }
#correo{ 
    top: 9.6 cm;
    left: 11cm;
    }
#lugar{ 
    top: 9.5 cm;
    left: 2cm;
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
#divisor{
    text-transform: uppercase;
    background-color:rgb(215, 195, 238);
    height: 5px;
    width: 100%;
    border-radius: 5px;
}
#cabecera{
        
        position: absolute;
	    top: 5cm;
        
         
        height: 55px; 
        font-size: 13px;
    }

th {
    text-align: left; 
    
}
td.sep {
    width:0.3cm;
}
hr {
position: absolute;
	    top: 6.6cm;
  border: none;               /* Quitamos el borde por defecto */
  height: 5px;                /* Grosor de la línea */
  background-color: #8c58c8;     /* Color de la línea */
  width: 100%;                 /* Para que no ocupe toda la pantalla */
  border-radius: 5px;         /* Bordes redondeados */
}
#numeroatencion{
    position: absolute;
    top: -5 cm;
    left: 13.5 cm;
    font-family: "Arial";
    
    font-size: 16px;
}
  
</style>
</head><body><div class="rotar">';
    $idsol = isset($_GET["idsol"]) ? $_GET["idsol"] : '';
    include('../bdlaboratorio.php');
    $base = new bdlaboratorio();
    $todo = $base->todosolicitud($idsol);
    $cab = $cab.cabecera($todo,  $idsol);
    function cabecera($todo,  $idsol)
    {
        if ($todo[0]['ci'] == '') {
            $ci = "-";
        } else {
            $ci = $todo[0]['ci'];
        }
         
        date_default_timezone_set('America/La_Paz');
        $fechadeimpresion= date('d/m/Y');
    
        return  ' 
            <div id="cabecera">
            <div id="numeroatencion"><b>Reg. SEDES:</b> CH. N° 09/2026<br /> <b>No. de atencion:</b> ' . $idsol . '</div><br><br> 
            <div id="derechasuperior">
            <b>Fecha:</b>'.$fechadeimpresion.'<br>
            <b>NO. PAC.: ' . $todo[0]['numerodia'] .'</b>
            </div><div id="datos"><span id="resaltar"><b>Señor(a)<br> Dr (a). : </span></b>'.$todo[0]['nombredoctor'].
            '</div><br><div id="datos"><span id="resaltar"><b>PACIENTE:</b></span>' . strtoupper($todo[0]['nombres']).
            ' ' . strtoupper($todo[0]['apellidos']) . '</div></div>
            <img id="logo" src="sobre.jpg" />
            
                        
                         
            <br>
            <img id="whatsapp" src="whatsapp.jpg" ><img id="correo" src="email.jpg" >
            <img id="lugar" src="lugarico.jpg" >
            <div id="direcciones" >Destacamento 111 No. 165 </div><div id="celular">78691072 - 74443471</div><div id="email">leisur.lab@gmail.com</div>
            
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
    $mensajeqr = 'Paciente: ' . $todo[0]['nombres'] . ' ' . $todo[0]['apellidos'] . 'ci:' . $todo[0]['ci'] . ' solicitud: ' . $idsol . ' fecha: ' . $todo[0]['fecha'] . ' Edad:' . $todo[0]['edad'];
     $bandera = false;
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

    $matrixPointSize = 5;
    $filename = $PNG_TEMP_DIR . 'test' . md5($mensajeqr . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
    QRcode::png($mensajeqr, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
    //display generated file
    $cab = $cab . '<img id="qr" src="' . $PNG_WEB_DIR . basename($filename) . '" />';

    //qr

    
    //$cab = $cab . '</table>';
    $s = $cab;
    $html = $s . '</div></body>';
    $h['compress'] = 1;
    $h['Attachment'] = 0;
    $dompdf = new DOMPDF();
    // Creamos una instancia a la clase
    $hoja = array(
    0.0,   // izquierda
    0.0,   // arriba
    793.0, // ancho (9.5 pulgadas)
    354.0  // alto (4.125 pulgadas)
);
    $dompdf->set_paper($hoja);
    $dompdf->load_html($html);
    $dompdf->render();
    //if (file_exists($PNG_WEB_DIR . basename($filename))) {
    //    unlink($PNG_WEB_DIR . basename($filename));
    //}
    //echo $html;
    ob_end_clean();
    $dompdf->stream("resultados.pdf", $h);
    exit();
    $pdf->Output();

    ?>