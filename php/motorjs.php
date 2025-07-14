<?php
include ('../bdlaboratorio.php');
class motorjs extends bdlaboratorio
{
    function pacienteb($buscar, $modo)
    { 
        if ($modo=="atencion"){
            $result= $this->selectw('*','solicitud',"lower(id) LIKE lower('$buscar%') and estado=true LIMIT 10");
            foreach ($result as $result) {
                echo '<li onClick="fill(\'' . $result['id'] . '\');">' . $result['id'] .
                        '</li>';
            }            
        }            
        else{ 
            $result = $this->selectw('*', 'paciente', "lower(".$modo.") LIKE lower('$buscar%') and estado=true LIMIT 10");
            foreach ($result as $temp) {
                if ($modo=='nombres'){
                    echo '<li onClick="fill(\'' . $temp['nombres'].','.$temp['apellidos']. '\');">' . $temp['nombres'].', '.$temp['apellidos'].
                    '</li>';
                } else {
                    if ($modo=='apellidos'){
                    echo '<li onClick="fill(\'' . $temp['nombres'].','.$temp['apellidos']. '\');">' .$temp['apellidos'] .', '.$temp['nombres'].
                    '</li>';
                }


                    echo '<li onClick="fill(\'' . $temp[$modo] . '\');">' . $temp[$modo] .
                        '</li>';
                }
            }
        }
    }
    function doctorb($buscar, $modo)
    {
        $result = $this->selectw('*', 'doctor', "lower(".$modo.") LIKE lower('".$buscar."%') and estado=true LIMIT 10");
        foreach ($result as $temp) {
            if ($modo=='nombres'){
                echo '<li onClick="fill(\'' . $temp['nombres'].','.$temp['apellidos']. '\');">' . $temp['nombres'].', '.$temp['apellidos'].
                '</li>';
            } else {
            echo '<li onClick="fill(\'' . $temp[$modo] . '\');">' . $temp[$modo] .
                '</li>';
            }
        }
    }
	function solicitud($buscar, $modo)
    {
        $result = $this->selectw('*', 'solicitud', "lower(".$modo.") LIKE lower('".$buscar."%') and estado=true LIMIT 10");
        foreach ($result as $temp) {
            if ($modo=='nombres'){
                echo '<li onClick="fill(\'' . $temp['nombres'].','.$temp['apellidos']. '\');">' . $temp['nombres'].', '.$temp['apellidos'].
                '</li>';
            } else {
            echo '<li onClick="fill(\'' . $temp[$modo] . '\');">' . $temp[$modo] .
                '</li>';
            }
        }
    }
	function categoriaanalisis($buscar, $modo)
    {
	header('Content-type: text/html; charset=iso-8859-1');
        $result = $this->selectw('*', 'categoriaanalisis', "lower(".$modo.") LIKE lower('%".$buscar."%') LIMIT 10");
        foreach ($result as $temp) {
            if ($modo=='nombres'){
                echo '<li onClick="fill(\'' . $temp['nombre']. '\');">' . $temp['nombre'].
                '</li>';
            } else {
            echo '<li onClick="fill(\'' . $temp[$modo] . '\');">' . $temp[$modo] .
                '</li>';
            }
        }
    }
	function analisisespecifico($buscar, $modo)
    {
	header('Content-type: text/html; charset=iso-8859-1');
        if ($modo=='id'){
		    $result = $this->selectw('*', 'analisisespecifico', "lower(".$modo.") LIKE lower('%".$buscar."%') LIMIT 10");
			foreach ($result as $temp) {
				echo '<li onClick="fill(\'' . $temp['id']. '\');">' . $temp['id'] . 
				'</li>';
			}
		}
		else {
			$result = $this->selectw('distinct nombre', 'analisisespecifico', "lower(".$modo.") LIKE lower('%".$buscar."%') LIMIT 10");
			foreach ($result as $temp) {
				echo '<li onClick="fill(\'' . $temp[$modo] . '\');">' . $temp[$modo] .'</li>';
			}
        }        
    }
	function verdetalles($buscar, $modo){
	    header('Content-type: text/html; charset=iso-8859-1');	
	    $idatencion = $buscar;
		$todo=$this->todosolicitud($idatencion);	$accionenv='editar';
		echo '<form name="detalle" id="formdet" method="POST">
        <table><input type="hidden" id="sol" name="idatencion" value=	"'.$idatencion.'"/>';
		
        echo '<input type="hidden" name="accion" value="autorizar2" >';

        echo '<tr><th>analisis</th><th>Resultado</th><th>Valor</th><th colspan="2">Parametro</th></tr>';
		$resultados=$this->resultados($idatencion);
		for ($i=0; $i<count($resultados); $i++){
			echo '<tr><td>'.$resultados[$i]['analisis'].'</td><td>'.$resultados[$i]['resultado'].'</td>
            <td><textarea name="a'.$i.'" rows=1 cols=20 >'.$resultados[$i]['valor'].'</textarea>'
            .$resultados[$i]['unidadmedicion'].'</td><td>'.$resultados[$i]['parametroinferior'].' - '.$resultados[$i]['parametrosuperior'].'<input type="hidden" name="b'.$i.'" value="'.$resultados[$i]['id'].'"></td></tr>';
		}
		echo '<input type="hidden" value="guardarresultadosautorizar" name="funcion" /><input name="cantidad" type="hidden" value="'.count($resultados).'">
		<tr><td></td><td><input  id="corregir" type="submit" value="Corregir" /></td>
		<td><input id="enviarauto" value="Autorizar" type="submit" onclick="h(); return false;" disabled="true" />
		<input type="checkbox" id="autorizar" /></td></tr></table></form>';    
	}
	/*    header('Content-type: text/html; charset=iso-8859-1');	
	    $idatencion = $buscar;
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
        
		//$todo=$this->todosolicitud($idatencion);	
        $accionenv='editar';
		echo '<form name="detalle" id="formdet" method="POST">
        <table><input type="hidden" id="sol" name="idatencion" value=	"'.$idatencion.'"/>';
		
        echo '<input type="hidden" name="accion" value="autorizar2" >';

        echo '<tr><th>analisis</th><th>Resultado</th><th>Valor</th><th colspan="2">Parametro</th></tr>';
		$resultados=$this->resultados($idatencion);
		for ($i=0; $i<count($paquetes); $i++){
            echo '<tr><td>'.$paquetes[$i]['paquete'].'</td><td>'.$paquetes[$i]['resultado'].
            '</td><td><input name="a'.$i.'" value="'.$paquetes[$i]['valor'].'">'.
            $paquetes[$i]['unidadmedicion'].'</td><td>'.$paquetes[$i]['parametroinferior'].
            ' - '.$paquetes[$i]['parametrosuperior'].'<input type="hidden" name="b'.$i.
            '" value="'.$paquetes[$i]['idresultados'].'"></td></tr>';
        }
        
        
        
        
        
        
        for ($i=0; $i<count($resultados); $i++){
			echo '<tr><td>'.$resultados[$i]['analisis'].'</td><td>'.$resultados[$i]['resultado'].'</td>
            <td><textarea name="a'.$i.'" rows=1 cols=20 >'.$resultados[$i]['valor'].'</textarea>'
            .$resultados[$i]['unidadmedicion'].'</td><td>'.$resultados[$i]['parametroinferior'].' - '.
            $resultados[$i]['parametrosuperior'].'<input type="hidden" name="b'.$i.'" value="'.$resultados[$i]['id'].'"></td></tr>';
		}
        $contador=count($resultados)+count($paquetes);
		echo '<input type="hidden" value="guardarresultadosautorizar" name="funcion" /><input name="cantidad" type="hidden" value="'.$contador.'">
		<tr><td></td><td><input  id="corregir" type="submit" value="Corregir" /></td>
		<td><input id="enviarauto" value="Autorizar" type="submit" onclick="h(); return false;" disabled="true" />
		<input type="checkbox" id="autorizar" /></td></tr></table></form>';    
	}
    function guardarresultadosautorizar($a, $b){
        $cantidad=isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
        for ($i=0; $i<$cantidad; $i++){
                            $datos[$i]=isset($_POST["a".$i]) ? $_POST["a".$i] : '' ;
                            $ids[$i]=isset($_POST["b".$i]) ? $_POST["b".$i] : '' ;
                    }
                    $this->modificarres($datos,$ids);
        echo "la atencion se modifico correctamente";
    }
    function tiporesultado($buscar,$modo){
        header('Content-type: text/html; charset=iso-8859-1');
        
        if ($modo=='nombre'){
            $result = $this->selectw('distinct nombre', 'tiporesultado', "lower(".$modo.") LIKE lower('".$buscar."%') LIMIT 10");
            foreach ($result as $temp) {
                echo '<li onClick="fill(\'' . $temp['nombre']. '\');">' . $temp['nombre'].
                '</li>';
            }
        } else {
        
            $result = $this->selectw('*', 'tiporesultado', "lower(".$modo.") LIKE lower('".$buscar."%') LIMIT 10");
            foreach ($result as $temp) {
                echo '<li onClick="fill(\'' . $temp['nombre']. '\');">' . $temp['nombre'].
                '</li>';
            }
        }  
    }*/
   
    function resultadosanteriores(){
        $id=$_GET['idpaciente'];
        $atencionesanteriores=$this->selectw("*","solicitud","estado=true and idpaciente=".$id);
        
 //aqui estan los anteriores analisis;
        $res.="<table><tr><th colspan='4'>Atenciones realizadas anteriormente</th></tr><tr><th>Acci&oacute;n</th><th>Fecha</th><th>diagnostico</th><th>doctor</th></tr>";
        foreach ($atencionesanteriores as $atencionanterior){
            $res.="<tr><td><form name='anterior' action='' ><input type='submit' value='ver'></form></td><td>".$atencionanterior['fechacreacion']."</td><td>".$atencionanterior['diagnostico']."</td><td>".$atencionanterior['iddoctor']."</td></tr>";
        }
        $res.="</table>";
    } 
    
}
$motorjs = new motorjs();
$funcion = isset($_POST['funcion']) ? $_POST['funcion'] : '';
$buscar = isset($_POST['buscar']) ? $_POST['buscar'] : '';
$modo = isset($_POST['modo']) ? $_POST['modo'] : '';
$motorjs->$funcion($buscar, $modo);
?>
