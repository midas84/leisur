<?php

require_once("dompdf/dompdf_config.inc.php");

class ReportePDF {
    private $dompdf;

    public function __construct() {
        // Inicializar DOMPDF
        $this->dompdf = new DOMPDF();
    }

    public function generarPDF($html) {
        // Cargar contenido HTML en DOMPDF
        $this->dompdf->load_html($html);
        // Renderizar el PDF
        $this->dompdf->render();
        // Enviar el PDF al navegador
        $this->dompdf->stream("resultado.pdf");
    }

    public function agregarCabecera($titulo, $estilos) {
        // Crear cabecera con título y estilos
        return '<title>' . $titulo . '</title><style type="text/css">' . $estilos . '</style>';
    }

    public function agregarContenido($contenido) {
        // Agregar contenido HTML adicional (tablas, etc.)
        return $contenido;
    }

    public function agregarPie($pieHtml) {
        // Agregar el pie de página
        return $pieHtml;
    }
}

class ResultadosLaboratorio {
    private $id;

    public function __construct($id) {
        $this->id = $id;
    }

    public function generarHTMLResultados() {
        // Aquí se genera todo el HTML basado en los resultados del laboratorio
        // Por ejemplo:
        $html = "<div>Resultados para el ID: " . $this->id . "</div>";
        // Puedes agregar más tablas, contenido dinámico, etc.
        return $html;
    }
}

// Uso del sistema

$reporte = new ReportePDF();
$estilosCSS = '
@page { margin: 1cm 2cm 0.5cm 2cm;} 
img { position: absolute; top: 2.3cm; left: 14cm; width: 3.5cm; }
#logo { position: absolute; top: -0.7cm; left: -1cm; width: 3.5cm; }
#qr { position: absolute; top: 24.2cm; left: 15.5cm; width: 2.2cm; }
#ho { position: absolute; top: 3.7cm; }
#pie { position: absolute; top: 24cm; left: 0.5 cm; }
#direcciones { position: absolute; top: 0 cm; left: 12.5cm; }
#facebook, #instagram, #lugar { position: absolute; top: 25.27 cm; left: 0cm; width: 0.3cm; }
#instagram { top: 24.85 cm; }
#lugar { top: 24.4 cm; }
#titulo { position: absolute; top: -0.5cm; left: 2.8cm; width: 9cm; }
table { font-size: 13px; width: 19cm; left: -1cm; }
#cabecera { position: absolute; top: 2cm; border: 1; width: 108%; }
table#edad { position: absolute; top: 0.0cm; left: 11.8cm; }
td.sep { width: 0.3cm; }
#marca { position: absolute; top: 9.0cm; left: 4.5cm; width: 70%; transform: translate(-50%, -50%); opacity: 0.1; pointer-events: none; }
';

$titulo = 'laboratorio celldiagnostic';
$cabecera = $reporte->agregarCabecera($titulo, $estilosCSS);

// Generación de resultados
$resultados = new ResultadosLaboratorio(4000);  // ID de ejemplo
$contenidoResultados = $resultados->generarHTMLResultados();

// Crear el PDF
$htmlCompleto = $cabecera . $reporte->agregarContenido($contenidoResultados);
$reporte->generarPDF($htmlCompleto);

?>
