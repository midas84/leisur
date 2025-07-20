<?php

class admautorizar extends bdlaboratorio
{
    public $contenido;
    function whoiam($accion)
    {
        $ver = isset($_GET["ver"]) ? $_GET["ver"] : '';
        return '<input type="hidden" name="ver" value="' . $ver .
            '" ><input type="hidden" name="accion" value="' . $accion . '" >';
    }
    function defaul()
    {
        $this->contenido .= '
            <script type="text/javascript" src="js/buscar.js" ></script><style type="text/css">
                .suggestionsBox {
                    position: relative;
                    left: 0px;
                    margin: 10px 0px 0px 0px;
                    width: 200px;
                    background-color: #212427;
                    border: 2px solid #000; 
                    color: #fff;
                }
                .suggestionList {
                    margin: 0px;
                    padding: 0px;
                }   
                .suggestionList li {    
                    margin: 0px 0px 3px 0px;
                    padding: 3px;
                    cursor: pointer;
                }
                .suggestionList li:hover {
                    background-color: #659CD8;
                }
            </style>
            <h2>Administrar Resultados</h2>
            <form name="o" method="GET" action="index.php">
                            ' . $this->whoiam('mostrar') . '
                <input type="hidden" name="tabla" value="pacienteb">
                <input type="hidden" name="modo" value="atencion">
                <table>
                <tr><td>Buscar Atención, introdusca idatencion:</td>
                <td></td>
                <td>
                    <input type="text" autocomplete="off" type="text" id="nombre" name="nombre" onkeyup="lookup(this.value, document.o.modo.value, document.o.tabla.value);" >
                    <div class="suggestionsBox" id="suggestions" style="display: none;" />
                    <div class="suggestionList" id="autoSuggestionsList">
                        &nbsp;
                    </div>
                </td></tr>
                            </table></form>'; 
        $this->contenido .= '<h2>Lista de Atenciones</h2>
        ';
        $this->mostrarresultados();
        
    }
    function mostrarresultados()
{
    $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
    $por_pagina = 50;
    $offset = ($pagina - 1) * $por_pagina;

    // Obtener el total de resultados
    $resultado = $this->selectw('COUNT(*) AS total', 'solicitud', 'autorizado=false and estado=true');
$total_resultados = isset($resultado[0]['total']) ? intval($resultado[0]['total']) : 0;
$total_paginas = ceil($total_resultados / $por_pagina);

    // Obtener solo los resultados paginados
    $listaresultados = $this->selectw('*', 'solicitud',
        //'autorizado=false and 
        'estado=true order by id desc limit ' . $por_pagina . ' offset ' . $offset);

    $this->contenido .= "<form><table>
        <thead>
    <tr>
        <th>No.</th>
        <th>Acci&oacute;n</th>
        <th>Número Atenci&oacute;n</th>
        <th>Nombre Paciente</th>
        <th>Fecha de Creaci&oacute;n</th>
        <th>Estado</th>
    </tr>
</thead>
        <tbody>";

    $no = $offset + 1;
    foreach ($listaresultados as $resultado) {
        $persona = $this->selectw('*', 'paciente', 'id=' . $resultado['idpaciente']);
        $this->contenido .= '<tr id="ab' . $resultado['id'] . '"><td>' . $no++ .'</td>
            <td id="borra' . $resultado['id'] . '">' .
            $this->whoiam('autorizar2') . '
            <input type="hidden" class="dato" id="b' . $resultado['id'] . '" name="idatencion" value="' . $resultado['id'] . '">
            <input class="detalles" id="b' . $resultado['id'] . '" type="submit" value="detalles" /></td>
            <td>' . $resultado['id'] . '</td><td>' . $persona[0]['nombres'] . ' ' . $persona[0]['apellidos'] . '</td>
            <td>' . $resultado['fechacreacion'] . '</td>';
            
            if($resultado['autorizado']==true){
                $this->contenido .= '<td id="banderaAutorizacion" style="background-color: green;">autorizado</td></tr>';
            }
                else {
                $this->contenido .= '<td id="banderaAutorizacion" style="background-color: red;">No autorizado</td></tr>';
            }

            
            $this->contenido .= '<tr><td colspan=5 class="hola" id="b' . $resultado['id'] . '" value="true"></td></tr>';
    }

    $this->contenido .= "</tbody></table></form>";

    // Agregar enlaces de paginación
    $this->contenido .= '<div style="margin-top: 10px;">Páginas: ';
    for ($i = 1; $i <= $total_paginas; $i++) {
        $this->contenido .= '<a href="?ver=autorizar&pagina=' . $i . '" style="margin:0 5px;' .
            ($i == $pagina ? 'font-weight:bold;text-decoration:underline;' : '') . '">' . $i . '</a>';
    }
    $this->contenido .= '</div>';
}
 
    
}

$autorizar = new admautorizar();
$autorizar->contenido =
    '<script type="text/javascript" src="js/detalles.js" ></script>
	<div id="content" align="center"><div class="contactof">';

$accion = isset($_GET["accion"]) ? $_GET["accion"] : 'defaul';
$autorizar->$accion();
$autorizar->contenido .= '</div><div><br /></div>';
echo $autorizar->contenido;
