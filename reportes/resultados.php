<?php

function cabecera($todo,  $idsol, $direccionqr, $firma='')
    {
        if ($todo[0]['ci'] == '') {
            $ci = "-";
        } else {
            $ci = $todo[0]['ci'];
        }
         
        date_default_timezone_set('America/La_Paz');
        $fechadeimpresion= date('d/m/Y H:i:s');
        ($firma=="")? $imagenFirma="" : $imagenFirma = '<img id="firma" src="../firmas/'.$firma.'.png" />  ';
        return  ' 
            <div id="cabecera">
            <div id="numeroatencion"><b>Reg. SEDES:</b> CH. N° 09/2026<br /><b>No. de atencion:</b> ' . $idsol . '</div>
            <div style="width: 400px; display: inline-block;"><b>CI:</b>' . $ci .'<br>'.'<b>PACIENTE:</b>' . strtoupper($todo[0]['nombres']).
            ' ' . strtoupper($todo[0]['apellidos']) .
            
            '<br> <b>DOCTOR: </b>'.$todo[0]['nombredoctor'].
            '<br> <b>DIAGNOSTICO PRESUNTIVO:</b>'.$todo[0]['diagnostico'].'
            </div><div style=" display: inline-block;"><b>EDAD:</b>' . $todo[0]['edad'] . '<br><b>NO. PACIENTE:</b>' . $todo[0]['numerodia'] .
            '<br><b>FECHA TOMA DE MUESTRA:</b>' . date("d/m/Y",strtotime($todo[0]['fecha'])) . '
            </div></div>
            <img id="logo" src="leisur.jpg" />           
            <br>
            <img id="whatsapp" src="whatsapp.jpg" ><img id="correo" src="email.jpg" >
            <img id="lugar" src="lugarico.jpg" >
            <div id="direcciones" >Destacamento 111 No. 165 </div><div id="celular">78691072 - 74443471</div><div id="email">leisur.lab@gmail.com</div>
            <div id="pie" style="font-size: 13px;" >
            <div id="fechaimpresion">Fecha y hora de impresi&oacute;n: '.$fechadeimpresion.'</div></diV>
            <img id="qr" src="' . $direccionqr . '" />
            '.$imagenFirma.'
            
            <hr><table id="ho" >';
    }

function qr($todo, $idsol,$resultados)
{
    $PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
    $PNG_WEB_DIR = 'temp/';
    include 'phpqrcode/qrlib.php';
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    $filename = $PNG_TEMP_DIR . 'test.png';
    $errorCorrectionLevel = 'L';
    $matrixPointSize = 3;
    $mensajeqr = 'Leisur Paciente: ' . $todo[0]['nombres'] . ' ' . $todo[0]['apellidos'] . 'ci:' . $todo[0]['ci'] . ' solicitud: ' . $idsol . ' fecha: ' . $todo[0]['fecha'] . ' Edad:' . $todo[0]['edad'];
    $bandera = false;
    for ($i = 0; $i < count($todo); $i++) {
        if (($todo[$i]['idana'] == 226) || ($todo[$i]['idana'] == 227) || ($todo[$i]['idana'] == 228) || ($todo[$i]['idana'] == 229) || ($todo[$i]['idana'] == 237)) {
            $bandera = true;
        }
    }
    if ($bandera) {
        for ( $i = 0; $i < count($resultados); $i++ ) {
            if (($i == 0) || ($resultados[$i]['analisis'] != $resultados[$i - 1]['analisis'])) {
                $mensajeqr = $mensajeqr . ' ' . $resultados[$i]['analisis'] . '|';
            }
            if (!(($resultados[$i]['valor'] == '') || ($resultados[$i]['valor'] == ' '))) {
                $mensajeqr = $mensajeqr . ' ' . $resultados[$i]['resultado'] . ' ';
                $mensajeqr = $mensajeqr . '(res:' . $resultados[$i]['valor'] . ' ' . $resultados[$i]['unidadmedicion'] . ')';
                $mensajeqr = $mensajeqr . '(tipo de muestra:' . $resultados[$i]['muestra'] . ')';
                $mensajeqr = $mensajeqr . '|';
            }

        }
    }
    $matrixPointSize = min(max(10, 1), 10);
    $filename = $PNG_TEMP_DIR . 'test' . md5($mensajeqr . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
    QRcode::png($mensajeqr, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
    return $PNG_WEB_DIR . basename($filename);
}
function divisor(){
    return '<tr id="divisor"><th align="left" style="width: 26%;">Prueba</th><th align="left" valign="middle" style="padding-left: 30px; width: 30%;">Resultado</th><th align="left" valign="middle" style="width: 43%; padding-left: 30px;">Parametro</th></tr>';
}
require_once('dompdf/dompdf_config.inc.php');
$id = 4000;
$cab = '<head><meta charset="UTF-8"><title>leisur</title>
  <style type="text/css">
 
@page { margin: 1cm 2cm 0.5cm 2cm;} 
#titulop{
    position: absolute;
    top: -0.8cm;	
    left: 2cm;
    font-size: 50px;
    font-family: "Arial Black", Gadget, sans-serif;
    font-weight: 900;
    color: #3ab5d6;
    
}
#firma{
 position: absolute;
 top: 23 cm;
 left: 13 cm;
 width : 4 cm;
 z-index: 3;
}
#pa {
    position: absolute;
    top: 0.5cm;	
    left: 2.1cm;
    z-index: 3;
}   
#subtitulo{
    position: absolute;
    top: 0.5cm;	
    left: 2.1cm;
    font-family: "Arial";
    color: #ffffff;
    font-size: 11px;
    z-index: 3;
}

#codigobarras {  
		position: absolute;
        top: 2.3cm;  
		left: 13.5cm;
		width: 3.5cm;
        z-index: 3;	
	}
#fechaimpresion {
        font-size: 13px;
        position: absolute;
        left: 2.5cm;
        top: 1cm;
        z-index: 3;
    }
 #logo {  
		position: absolute;
        top: -1cm;  
		left: -2cm;
		width: 21.6cm;	
        z-index: 1;

	}
#qr {  
		position: absolute;
        top: 23.2cm;  
		left: 0cm;
		width: 2.2cm;
        z-index: 3;
	}
#ho{
    
    border-collapse: separate;
    border-spacing: 0;
    position: absolute;
    top: 4.5cm;
    left: 0cm;	
    z-index: 3;
  }
#pie{
    position: absolute;
    top: 24 cm;
    left: 0 cm;
    z-index: 3;	
  }
#direcciones{
    position: absolute;
    top: 26 cm;	
    left: 2.7cm;
    font-family: "Arial";
    color: #8c58c8;
    font-size: 13px;
    z-index: 3;
  }
#celular{
    position: absolute;
    top: 26 cm;	
    left: 7.7cm;
    font-family: "Arial";
    color: #8c58c8f;
    font-size: 13px;
    z-index: 3;
  }
#email{
    position: absolute;
    top: 26 cm;	
    left: 11.7cm;
    font-family: "Arial";
    color: #8c58c8;
    font-size: 13px;
    z-index: 3;
  }
#correo, #whatsapp, #lugar{
    position: absolute;
    width: 0.3cm;
    z-index: 3;
  }
#whatsapp{ 
    top: 26.05 cm;
    left: 7.2cm;
    z-index: 3;
    }
#correo{ 
    top: 26.1 cm;
    left: 11.2cm;
    z-index: 3;
    }
#lugar{ 
    top: 26 cm;
    left: 2.3cm;
    z-index: 3;
    }
#titulo{
    position: absolute;
    top: -0.5cm;	
    left: 2.8cm;
    width:9cm; 
    z-index: 3;
  }
table {
		font-size: 13px;
		width:19cm; 
		left:-1cm;
        z-index: 3;
	}
#divisor{
    text-transform: uppercase;
    
    height: 5px;
    width: 100%;
    border-radius: 5px;
    z-index: 3;
}
#cabecera{
        
        position: absolute;
	    top: 2.5cm;
        
        width: 100%; 
        height: 55px; 
        font-size: 13px;
        z-index: 3;
    

th {
    text-align: left; 
    z-index: 3;
}
td.sep {
    width:0.3cm;
    z-index: 3;
}
hr {
position: absolute;
	    top: 4cm;
  border: none;               /* Quitamos el borde por defecto */
  height: 5px;                /* Grosor de la línea */
  background-color: #8c58c8;     /* Color de la línea */
  width: 100%;                 /* Para que no ocupe toda la pantalla */
  border-radius: 5px;         /* Bordes redondeados */
  z-index: 3;
}
#numeroatencion{
    position: absolute;
    top: -2.5 cm;
    left: 12cm;
    
    
    font-size: 15px;
    z-index: 3;
}
</style>
</head><body>';
$idsol = isset($_GET['idsol']) ? $_GET['idsol'] : '';
$firma = isset($_GET['firma']) ? $_GET['firma'] : '';
include('../bdlaboratorio.php');
$base = new bdlaboratorio();
$todo = $base->todosolicitud($idsol);
$direccionqr = qr($todo, $idsol, $resultados);
$cab = $cab . cabecera($todo, $idsol, $direccionqr, $firma);
$resultados = $base->resultados($idsol);
$pa = 3;
$pagina = 0;
//QR

$banderaprimerapagina = true;
$columnaizquierda = '';
$columnaderecha = '';
$tabladerecha = [];
$a = 0; //es el indice de valores dentro de los resultados que van en tabla
$analisis = '';
for (
    $i = 0;
    $i < count($resultados);
    $i++
) {
    //$array = explode('<br>', $resultados[$i]['valor']);
    $array = mb_strlen($resultados[$i]['valor'], 'UTF-8')+ mb_strlen($resultados[$i]['parametrosuperior'], 'UTF-8')+mb_strlen($resultados[$i]['parametroinferio'], 'UTF-8') ; 
    $array = (int)($array / 15);
    if ( $array == 0 ) $array = 1; 
    //echo '<div id="pa">'.$i."=>".$array.'</div>';
    $pa = $pa + $array - 1;
    //esto aumenta el numero de filas que tiene en total el valor del resultado
    // }
    if (($i == 0) || ($resultados[$i]['analisis'] != $resultados[$i - 1]['analisis'])) {
        //aqui imprime toda la fila a dos columnas

        if (($i == 0) || ($resultados[$i]['categoria'] != $resultados[$i - 1]['categoria'])) { 
            

            if ($pa >= 40) {
                $pagina++;
                $cab = $cab . '</table>';
                $cab = $cab . '<div style="page-break-before: always;"></div>';
                $cab = $cab . cabecera($todo, $idsol, $direccionqr);
                if ((!($i == 0)) || ($resultados[$i]['categoria'] == $resultados[$i - 1]['categoria'])) {
                    $cab=$cab. divisor();
                }
                $pa = 3;
            }
            $pa++; 
            $cab = $cab . '<tr><th colspan="3" style="font-size:16px;"><center>' . $resultados[$i]['categoria'] .
            '</center></th></tr>';
        
        }
        /*if ($pa >= 47) {
            $pagina++;
            $cab = $cab . '</table>';
            $cab = $cab . '<div style="page-break-before: always;"></div>';
            $cab = $cab . cabecera($todo, $idsol);
            $pa = 3;
        }  */
        $pa++;
        $cab = $cab . '<tr><th align="left" colspan=3 style="font-size:15px;"><u>' . $resultados[$i]['analisis'] .
            '</u></th></tr>';
        $pa++;
        if ($resultados[$i]['filacompleta'] == '0') {
            $cab = $cab . divisor();
        $pa++;
        }
    }
    //######### ----------- Parte central del reporte ----------- #########
    if (!(($resultados[$i]['valor'] == '') || ($resultados[$i]['valor'] == ' '))) {
        switch ($resultados[$i]['filacompleta']) {
            case 0:  //caso normal
                $cab = $cab . '<tr><td style="width: 20%;"  >' . $resultados[$i]['resultado'] . '</td>';
                if ((($resultados[$i]['parametroinferior'] == '0') && ($resultados[$i]['parametrosuperior'] == '0')) || (($resultados[$i]['parametroinferior'] == '') || ($resultados[$i]['parametroinferior'] == ''))) {
                    $cab = $cab . '<td colspan="2" style="padding-left: 30px; padding-right: 50px; vertical-align: middle;" >' . nl2br($resultados[$i]['valor']) . ' ' . $resultados[$i]['unidadmedicion'] . '</td>';
                }else {
                    $cab = $cab . '<td style="padding-left: 30px; padding-right: 10px; vertical-align: middle;">' . nl2br($resultados[$i]['valor']) . ' ' . $resultados[$i]['unidadmedicion'] .
                        '</td>';
                    $cab = $cab . '<td  style="padding-left: 30px;  padding-right: 5px; vertical-align: middle;" >' . $resultados[$i]['parametroinferior'] . ' - ' . $resultados[$i]['parametrosuperior'] . ' ' . $resultados[$i]['unidadmedicion'] . '</td>';
                }
                $cab = $cab . '</tr>';
                $pa++;
                break;
            case 1: //caso fila completa
                $cab = $cab . '<tr><td style="width: 25%;"><b>' . $resultados[$i]['resultado'] . '</b></td><td colspan="2">' . nl2br($resultados[$i]['valor']) .
                    '</td>';
                $cab = $cab . '</tr>';
                $pa++;
                break;
            case 2: //caso columna derecha
                $columnaderecha .= '<b>' . $resultados[$i]['resultado'] . '</b> ' . nl2br($resultados[$i]['valor']) . '<br> ';
                $pa++;
                break;
            case 3://caso columna izquierda
                $columnaizquierda .= '<b>' . $resultados[$i]['resultado'] . '</b> ' . nl2br($resultados[$i]['valor']) . '<br> ';
                $pa++;
                break;
            case 4://tabla columna izquierda
                $tabla['titulo'][] = $resultados[$i]['resultado'];
                $tabla['resultado'][$a] = nl2br($resultados[$i]['valor']);
                $analisis = $resultados[$i]['analisis'];
                $a++;
                $pa++;
                break;
        }
    }
    if ((!empty($columnaderecha) || !empty($columnaizquierda)) && (($i == (count($resultados) - 1)) || ($resultados[$i]['analisis'] != $resultados[$i + 1]['analisis']))) {
        $cab .= "<tr><td colspan=3 style='vertical-align: top;'><table style='width: 100%; border-collapse: collapse;'><tr ><td style='width: 50%; vertical-align: top;'>" . $columnaderecha . "</td>
<td style='width: 50%; vertical-align: top;'>" . $columnaizquierda . "</td></tr></table></td></tr>";
        $columnaizquierda = '';
        $columnaderecha = '';
    }
    if ((!empty($tabla)) && (($i == (count($resultados) - 1)) || ($resultados[$i]['analisis'] != $resultados[$i + 1]['analisis']) && ($resultados[$i]['analisis'] == $columnaderecha['analisis']))) {
        $titulos = '';
        $contenidos = '';
        for ($fila = 0; $fila < $columnas = count($tabla['titulo']); $fila++) {
            switch ($columnas) {
                case 1:
                    $ancho = "100%";
                    break;
                case 2:
                    $ancho = "50%";
                    break;
                case 3:
                    $ancho = "33%";
                    break;
            }
            $titulos .= "<th style=\"background-color: #AAAAAA; text-align: center; font-weight: bold; width: {$ancho}px;\">"
                . $tabla['titulo'][$fila] . "</th>";
            $contenidos .= "<td style=\"width: {$ancho}px;\">"
                . $tabla['resultado'][$fila] . "</td>";
        }
        $cab .= "<tr><td colspan=3 style='vertical-align: top;'>
        <table border='1' style='width: 100%; border-collapse: collapse;'>
        <tr >";
        $cab .= $titulos . "</tr><tr>" . $contenidos . "</tr></table></td></tr>";
        $tabla = [];
    }
    if ($pa >= 40) {
        $pagina++;
        $cab = $cab . '</table>';
        $cab = $cab . '<div style="page-break-before: always;"></div>';
        $cab = $cab . cabecera($todo, $idsol, $direccionqr, $firma);
        $pa = 3;

    }
}

// aqui para codigo qr

$matrixPointSize = min(max(10, 1), 10);

$cab = $cab . '</table></body>';
$h['compress'] = 1;
$h['Attachment'] = 0;
$dompdf = new DOMPDF();
// Creamos una instancia a la clase
$hoja = array(
    0.0,     // Margen izquierdo
    0.0,     // Margen superior
    610.0,   // Margen derecho ( 215.9 mm )
    793.0    // Margen inferior ( 279.4 mm )
);
$dompdf->set_paper($hoja);
$dompdf->load_html($cab);
$dompdf->render();
if (file_exists($direccionqr)) {
    unlink($direccionqr);
}
//echo $cab;
$dompdf->stream('resultados.pdf', $h);
exit();
$pdf->Output();

?>
