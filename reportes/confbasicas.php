<?
class confbasicas
{
    public $estilos = '<title>laboratorio celldiagnostic</title>
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
      top: 25.27 cm;	
      left: 0cm;
      width: 0.3cm;
    }
      #instagram{ 
      top: 24.85 cm;
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
          }  
    </style>
  </head>';
    function cabecera($todo, $cab, $idsol)
    {
        if ($todo[0]['ci'] == '') {
            $ci = "-";
        } else {
            $ci = $todo[0]['ci'];
        }
        $cab = $cab . '<img id="logo" src="http://localhost/laboratorio0.7/reportes/logo.jpg" />
    <img id="titulo" src="http://localhost/laboratorio0.7/reportes/titulo.jpg" />';
        return $cab . '
    <table  border="1" cellspacing="0" cellpadding="5" id="cabecera">
                    <tr><td><table><tr><td colspan=2>Paciente:' . $todo[0]['nombres'] .
            ' ' . $todo[0]['apellidos'] .
            '<br>CI:' . $ci .
            '<br> Doctor:</td></tr></table>
<table id="edad"><tr><td colspan="2">Edad:' . $todo[0]['edad'] . '<br>Pac./dia. :' . $todo[0]['numerodia'] .
            '<tr><td>Fecha:' . $todo[0]['fecha'] . '</td><td></td><td></td><td></td></td></tr></table></td></tr>
<table><tr><td><img src="http://localhost/laboratorio0.7/reportes/codigob.php?codigo=' .
            $idsol . '"></td></tr>
</table></tr></td></table>

        <img id="marca" src="http://localhost/laboratorio0.7/reportes/logo.jpg"  /><img id="instagram" src="http://localhost/laboratorio0.7/reportes/instagramico.jpg" ><img id="facebook" src="http://localhost/laboratorio0.7/reportes/facebookico.jpg" >
        <img id="lugar" src="http://localhost/laboratorio0.7/reportes/lugarico.jpg" >
<div id="direcciones" >75777151 / 76123455 <br /> cell.diagnostic.sc@gmail.com <br />Reg. SEDES No. 721/2023 </div><table id="ho"><div id="pie"><hr /> Santa Cruz: Calle Cuellar No. 152 entre 21 de Mayo y Libertad <br /> @laboratoriocelldiagnostic <br />  Laboratorio Cell-Diagnostic SC </diV>';
    }
    function generarqr($todo)
    {
        $PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
        $PNG_WEB_DIR = 'temp/';
        include "phpqrcode/qrlib.php";
        if (!file_exists($PNG_TEMP_DIR))
            mkdir($PNG_TEMP_DIR);
        $filename = $PNG_TEMP_DIR . 'test.png';
        $errorCorrectionLevel = 'L';
        $matrixPointSize = 3;
        $mensajeqr = 'Cell-Diagnostic Paciente: ' . $todo[0]['nombres'] . ' ' . $todo[0]['apellidos'] . 'ci:' . $todo[0]['ci'] . ' solicitud: ' . $idsol . ' fecha: ' . $todo[0]['fecha'] . ' Edad:' . $todo[0]['edad'];
        $bandera = FALSE;
        for ($i = 0; $i < count($todo); $i++) {
            if (($todo[$i]['idana'] == 226) || ($todo[$i]['idana'] == 227) || ($todo[$i]['idana'] == 228) || ($todo[$i]['idana'] == 229) || ($todo[$i]['idana'] == 237)) {
                $bandera = true;
            }
        }
        if ($bandera) {
            for ($i = 0; $i < count($resultados); $i++) {
                if (($i == 0) || ($resultados[$i]['analisis'] != $resultados[$i - 1]['analisis'])) {
                    $mensajeqr = $mensajeqr .  ' ' . $resultados[$i]['analisis'] . '|';
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
        //display generated file
        return '<img id="qr" src="' . $PNG_WEB_DIR . basename($filename) . '" />';
    }
}
