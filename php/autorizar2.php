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
            <h2>Buscar Resultados para Autorizar</h2>
            <form name="o" method="GET" action="index.php">
                            ' . $this->whoiam('mostrar') . '
                <input type="hidden" name="tabla" value="pacienteb">
                <input type="hidden" name="modo" value="atencion">
                <table>
                <tr><td>Introducir id de atencion:</td>
                <td></td>
                <td>
                    <input type="text" autocomplete="off" type="text" id="nombre" name="nombre" onkeyup="lookup(this.value, document.o.modo.value, document.o.tabla.value);" >
                    <div class="suggestionsBox" id="suggestions" style="display: none;" />
                    <div class="suggestionList" id="autoSuggestionsList">
                        &nbsp;
                    </div>
                </td></tr>
                            <tr><td colspan="3"><center>
                            <input type="submit" value="Buscar"/></center></td></tr></table></form>'; 
        $this->contenido .= '<h2>Lista de resultados llenos por autorizar</h2>
        ';
        $this->mostrarresultados();
        
    }
    function mostrarresultados()
    {
        $this->contenido.="<form><table>
            <thead>
                <tr><th>No.</th><th>accion</th><th>N&uacute;mero atenci&oacute;n</th><th>Nombre Paciente</th>
                <th>Fecha de creaci&oacute;n</th></tr>
            </thead>
            <tbody>";
        $listaresultados = $this->selectw('*', 'solicitud',
            'autorizado=false and estado=true order by id desc');
        $no = 0;
        foreach ($listaresultados as $resultado) {
		$persona=$this->selectw('*','paciente','id='.$resultado['idpaciente']);
            $this->contenido .= '<tr id="ab'.$resultado['id'].'"><td>' . $no++ . '</td>
			<td id="borra'.$resultado['id'].'">
			'.$this->whoiam('autorizar2').'
			<input type="hidden" class="dato" id="b'.$resultado['id'].'" name="idatencion" value="'.$resultado['id'].'">
			<input class="detalles" id="b'.$resultado['id'].'" type="submit" value="detalles" /></td>
			<td>' . $resultado['id'] .'</td><td>'.$persona[0]['nombres'].' '.$persona[0]['apellidos'].'</td>
                <td>' . $resultado['fechacreacion'] .'</td></tr><tr><td colspan=5 class="hola" id="b'.$resultado['id'].'" value="true">
                </td></tr>';
        }
        $this->contenido.="</tbody>
        </table>s</form>";
    }
   /* function autorizar2(){
        $this->autorizar($_GET['idatencion']);
        $this->contenido.='Se autorizo correctamente la atenci&oacute;n No.'. $_GET['idatencion'].'</br>';
        $this->defaul();
         
    }*/
    function mostrar(){
        $idatencion=isset($_GET["nombre"]) ? $_GET["nombre"] : '' ;
        $todosolicitud=$this->selectw('paciente.nombres nombres, 
                                paciente.apellidos, 
                                paciente.edad edad,
                                solicitud.autorizado,
                                solicitud.fechacreacion fecha, 
                                solicitud.atenciondia numerodia, 
                                solicitud.diagnostico diagnostico, 
                                solicitud.preciototalestimado precio'
                                ,
                                'solicitud inner join paciente on paciente.id=solicitud.idpaciente '
                                ,
                                '(solicitud.id=' . $idatencion . ') and (solicitud.estado=true)');
        $paquetes=$this->selectw('resultados.id id, tiporesultado.nombre resultado, paquete.nombre paquete, resultados.valor, resultados.id idresultados, tiporesultado.unidadmedicion, tiporesultado.parametroinferior, tiporesultado.parametrosuperior'
        ,
        'resultados INNER JOIN perfiladquirido ON resultados.idpaquete = perfiladquirido.id INNER JOIN paquete ON perfiladquirido.idpaquete = paquete.id inner join tiporesultado on tiporesultado.id = resultados.idtiporesultado'
        ,
        'perfiladquirido.estado=true and resultados.estado=true and perfiladquirido.idsolicitud=' . $idatencion);
        $resultados=$this->resultados($idatencion);
        //$todo=$this->todosolicitud($idatencion);    
        $resultado= '<form action="index.php" name="form" method="GET"><table border="1" >'.$this->whoiam("autorizara");
        if ($todosolicitud[0]['autorizado']){
            $resultado.='<tr><th colspan="4">Ya se autorizo estos resultados</th></tr>';
            $valor="desautorizar";
        }
        else {
            $resultado.='<tr><th colspan="4">No se autorizo todavia estos resultados</th></tr>';
            $valor="autorizar";
        }
        $resultado.='<tr><th colspan="4" align="center"><input type="hidden" name="idatencion" value=  "'.$idatencion.'"/>Ficha de atencion</th></tr>';
        $resultado.='<tr><td colspan="2">
        <img width="200" src="http://localhost/laboratorio/reportes/codigob.php?codigo='.$idatencion.'"></td><td>nombrepaciente:</td><td>'.$todosolicitud[0]['nombres'].'</td></tr>
        <tr><td>Fecha:</td><td>'.$todosolicitud[0]['fecha'].'</td><td>Numero atencion del dia:</td><td>'.$todosolicitud[0]['numerodia'].'</td></tr>
        <tr><td>Diagnostico:</td><td>'.$todosolicitud[0]['diagnostico'].'</td></tr>';
        $resultado.='<tr><th>analisis</th><th>Resultado</th><th>Valor</th><th colspan="2">Parametro</th></tr>';
        $resultados=$this->resultados($idatencion);

/**/    for ($i=0; $i<count($paquetes); $i++){
            $resultado.='<tr><td>'.$paquetes[$i]['paquete'].'</td><td>'.$paquetes[$i]['resultado'].'</td><td><input name="a'.$i.'" value="'.$paquetes[$i]['valor'].'">'.$paquetes[$i]['unidadmedicion'].'</td><td>'.$paquetes[$i]['parametroinferior'].' - '.$paquetes[$i]['parametrosuperior'].'<input type="hidden" name="b'.$i.'" value="'.$paquetes[$i]['idresultados'].'"></td></tr>';
        }

/**/    for ($i=0; $i<count($resultados); $i++){
            $resultado.='<tr><td>'.$resultados[$i]['analisis'].'</td><td>'.$resultados[$i]['resultado'].'</td><td><input name="a'.$i.'" value="'.$resultados[$i]['valor'].'">'.$resultados[$i]['unidadmedicion'].'</td><td>'.$resultados[$i]['parametroinferior'].' - '.$resultados[$i]['parametrosuperior'].'<input type="hidden" name="b'.$i.'" value="'.$resultados[$i]['idresultados'].'"></td></tr>';
        }
        
        $contador=count($resultados)+count($paquetes);
        $resultado.='<input name="cantidad" type="hidden" value="'.$contador.'">
        <tr><td colspan="4"><center><input type="submit" value="'.$valor.'"/></center></td></tr></table></form>';
        $this->contenido .= $resultado;
    }
    function autorizara(){
        $idatencion=isset($_GET["idatencion"]) ? $_GET["idatencion"] : '' ;
        $this->autorizar($idatencion);
        $_GET["nombre"]=$idatencion;
        $this->mostrar();
    }
}
header('Content-type: text/html; charset=utf-8');
$autorizar = new admautorizar();
$autorizar->contenido =
    '<script type="text/javascript" src="js/detalles.js" ></script>
	<div id="content" align="center"><div class="contactof">';

$accion = isset($_GET["accion"]) ? $_GET["accion"] : 'defaul';
$autorizar->$accion();
$autorizar->contenido .= '</div><div><br /></div>';
echo $autorizar->contenido;
