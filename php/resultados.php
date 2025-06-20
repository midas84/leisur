<?php
class admresultados extends bdlaboratorio
{
    public $contenido;
    function whoiam($accion)
    {
        $ver = isset($_GET["ver"]) ? $_GET["ver"] : '';
        return '<input type="hidden" name="ver" value="' . $ver .
            '" ><input type="hidden" name="accion" value="' . $accion . '" >';
    }
    function defaul(){
        $this->contenido .= '<h2>Llenar resultados</h2>
        <form action="index.php" name="form" method="GET">
        <table border="3"><tr>
        <td>Introducir id de atencion</td>
        <td><input type="text" name="idatencion"></td></tr>
        <tr>'.$this->whoiam('nuevo').'</table></form>'; 
    }
    function nuevo(){
        header('Content-type: text/html; charset=iso-8859-1');  
        $idatencion= isset($_GET['idatencion']) ? $_GET['idatencion'] : '';

        $todo=$this->todosolicitud($idatencion);    $accionenv='editar';
        
        $this->contenido.= '<form name="resultados" action="index.php" method="GET" ><table border="1" >
        <tr><th colspan="4" align="center">
        <input type="hidden" name="idatencion" value="'.$idatencion.'"/>Ficha de atencion</th></tr>
        <tr><td><img width="200" src="http://localhost/laboratorio1.0/reportes/codigob.php?codigo='.$idatencion.'"></td>
        <td>nombrepaciente: '.$todo[0]['nombres'].$todo[0]['apellidos'].'</td></tr>
        <tr><td>Fecha: '.$todo[0]['fecha'].'</td>
        <td>Numero atencion del dia: '.$todo[0]['numerodia'].'</td></tr>
        <tr><td>Diagnostico: '.$todo[0]['diagnostico'].'</td></tr>
        ';
        $this->contenido.= '<tr><th>analisis</th><th>Resultado</th><th>Valor</th><th colspan="2">Parametro</th></tr>';
        $resultados=$this->resultados($idatencion);
        for ($i=0; $i<count($resultados); $i++){
            $this->contenido.= '<tr><td>'.$resultados[$i]['analisis'].'</td>
            <td>'.$resultados[$i]['resultado'].'</td>
            <td><textarea name="a'.$i.'" rows=1 cols=20 />'.$resultados[$i]['valor'].'</textarea>'.$resultados[$i]['unidadmedicion'].'</td>
            <td>'.$resultados[$i]['parametroinferior'].' - '.$resultados[$i]['parametrosuperior'].
            '<input type="hidden" name="b'.$i.'" value="'.$resultados[$i]['id'].'"></td></tr>';
        }
        $this->contenido.= '<input name="cantidad" type="hidden" value="'.count($resultados).'">'.$this->whoiam('guardar').'
        <tr><td colspan=4><center><input  id="guardar" type="submit" value="Guardar" /></center></td>
        </tr></table></form>';
    }
    function guardar(){
        $cantidad=isset($_GET['cantidad']) ? $_GET['cantidad'] : 0;
        for ($i=0; $i<$cantidad; $i++){
                            $datos[$i]=isset($_GET["a".$i]) ? $_GET["a".$i] : '' ;
                            $ids[$i]=isset($_GET["b".$i]) ? $_GET["b".$i] : '' ;
                    }
                    $this->modificarres($datos,$ids);
                    $this->nuevo();
    }
}
header('Content-type: text/html; charset=iso-8859-1');
$resultados = new admresultados();
$resultados->contenido =
    '<div id="content" align="center"><div class="contactof">';

$accion = isset($_GET["accion"]) ? $_GET["accion"] : 'defaul';
$resultados->$accion();
$resultados->contenido .= '</div><div><br /></div>';
echo $resultados->contenido;

?>
