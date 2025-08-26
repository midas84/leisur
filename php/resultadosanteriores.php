<?php
class admresultadosanteriores extends bdlaboratorio
{
    public $contenido;
    function whoiam($accion)
    {
        $ver = isset($_GET["ver"]) ? $_GET["ver"] : '';
        return '<input type="hidden" name="ver" value="' . $ver .
            '" ><input type="hidden" name="accion" value="' . $accion . '" >';
    }
    function defaul(){
        return $this->analisisanteriores();
    }
    function analisisanteriores(){
        $paciente=isset($_GET['id']) ? $_GET['id'] : '';
        $atenciones=$this->selectw("*","solicitud","idpaciente=".$paciente);
        $pac=$this->selectw('nombres, apellidos','paciente','id='.$paciente);
        $this->contenido.="<form><table><tr><td></td>
        <input type='hidden' name='ver' value='atencion'>
        <input type='hidden' name='accion' value='nuevo'>
        <input type='hidden' name='modo' value='id'>
        <input type='hidden' name='nombre' value='".$paciente."'>
        <input type='submit' value='volver'></tr></table></form>";
        $this->contenido.="<h2>Consultas realizadas al Paciente</h2><h3>".$pac[0]['nombres']." ".$pac[0]['apellidos']."</h3><br />";
        foreach ($atenciones as $atencion){
            $this->contenido.= '<form><table><tr><td>'.$this->whoiam('detalle').'<input type="hidden" name="idsolicitud" value="'.$atencion['id'].'"><input type="submit" value="detalles" /></td><td>'.$atencion['id'].'</td><td>'.$atencion['fechacreacion'].'</td></form>';
        }
    }
    function detalle(){
        header('Content-type: text/html; charset=iso-8859-1');  
        $idatencion = isset($_GET['idsolicitud']) ? $_GET['idsolicitud'] : '';
        $sol=$this->selectw('fechacreacion, idpaciente','solicitud','id='.$idatencion);
        $pac=$this->selectw('id, nombres, apellidos','paciente','id='.$sol[0]['idpaciente']);
        $todo=$this->todosolicitud($idatencion);
        $this->contenido.='<h2>Resultados de la atencion No.'.$idatencion.' en fecha '.$sol[0]['fechacreacion'].' pertenenciente a <br />'.$pac[0]['nombres'].' '.$pac[0]['apellidos'].'</h2>';
        $this->contenido.='<table>';
        $this->contenido.='<tr><th>analisis</th><th>Resultado</th><th>Valor</th><th colspan="2">Parametro</th></tr>';
        $resultados=$this->resultados($idatencion);
        for ($i=0; $i<count($resultados); $i++){
            $this->contenido.='<tr><td>'.$resultados[$i]['analisis'].'</td><td>'.$resultados[$i]['resultado'].'</td>
            <td>'.$resultados[$i]['valor'].' '
            .$resultados[$i]['unidadmedicion'].'</td><td>'.$resultados[$i]['parametroinferior'].' - '.$resultados[$i]['parametrosuperior'].'</td></tr>';
        }
        $this->contenido.='</table><form><table><tr><td><input type="hidden" name="id" value="'.$pac[0]['id'].'" >'.$this->whoiam('defaul').'<input type="submit" value="volver"></td></tr></table></form>';
    }
    function nuevo(){
        header('Content-type: text/html; charset=iso-8859-1');  
        $idatencion= isset($_GET['idatencion']) ? $_GET['idatencion'] : '';
        $todo=$this->todosolicitud($idatencion);    $accionenv='editar';
        $this->contenido.= '<form name="resultados" action="index.php" method="GET" ><table border="1" >
        <tr><th colspan="4" align="center">
        <input type="hidden" name="idatencion" value="'.$idatencion.'"/>Ficha de atencion</th></tr>';
        $this->contenido.= '<tr><td colspan="2">
        <img width="200" src="http://10.10.10.2/laboratorio0.9/reportes/codigob.php?codigo='.$idatencion.'"></td>
        <td>nombrepaciente:</td><td>'.$todo[0]['nombres'].$todo[0]['apellidos'].'</td></tr>
        <tr><td>Fecha</td><td>'.$todo[0]['fecha'].'</td>
        <td>Numero atencion del dia</td><td>'.$todo[0]['numerodia'].'</td></tr>
        <tr><td>Diagnostico</td><td>'.$todo[0]['diagnostico'].'</td></tr>';
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
}
header('Content-type: text/html; charset=iso-8859-1');
$resultados = new admresultadosanteriores();
$resultados->contenido =
    '<div id="content" align="center"><div class="contactof">';

$accion = isset($_GET["accion"]) ? $_GET["accion"] : 'defaul';
$resultados->$accion();
$resultados->contenido .= '</div><div><br /></div>';
echo $resultados->contenido;

?>