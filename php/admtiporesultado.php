<?php 
class admresultados extends bdlaboratorio {
	function formulario1()
    {
        $contenido = '<script type="text/javascript" src="js/buscarresultados.js" ></script>';
        $contenido .= '<style type="text/css">
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
					<form name="i" method="GET" action="index.php" >
						<table>
							<tr>
								<th colspan=2>
									<h2>Administración de Tipo de Resultados</h2>
									<h3>Escoja primero categoria de analisis y luego el analisis especifico para mostrar los tipos de resultados dependientes</h3>
								</th>
							</tr>
							<tr>
								<td>' .$this->whoiam('buscar') . 'Categoria:
								<input autocomplete="off" type="text" id="nombre" name="nombre" onkeyup="lookup(this.value);" >
									<div class="suggestionsBox" id="suggestions" style="display: none;">
										<div class="suggestionList" id="autoSuggestionsList">
											&nbsp;
										</div>
									</div>
									</td>
									<td>
								Analisis:
								<select  id="selectresultado" name="selectanalisis" onchange="mostrarresultados(this)">
								</select>
									
									
								</td>
							</tr>
							<input type="hidden" name="tablacat" value="categoriaanalisis">
							<input type="hidden" name="tablaanalisis" value="analisisespecifico">
							<tr>
								<td>
									<input type="submit" value="Buscar"/>
								</td>
							</tr>
						</table>
					</form>
					<br />
					<form name="ii" method="GET" action="index.php">
						<table>
							<tr>
								<td>' . $this->whoiam('todo') .
									'<input type="submit" value="Mostrar Todas las categorias"/>
								</td>
							</tr>
						</table>
					</form>';
        return $contenido;
    }
	function formulario($id, $nombre, $descripcion, $estado, $unidadmedicion, $parametroinferior, $parametrosuperior, $idanalisis, $accion){
		$contenido='<form action="index.php" method="GET">';
		if (!($id=='')){
			$contenido.='<h2>Tipo de resultado encontrado <br />Editar Tipo de Resultado</h2>
					<table>
					<tr>
						<td>
							id
						</td>
						<td>
							'.$id.' <input type="hidden" name="id" value="'.$id.'"/><input type="hidden" name="modo" value="id"/> 
						</td>
					</tr>';
		} 
		else {
			$contenido.='<h2>No existe el tipo de resultado se creara uno nuevo <br />Ingresar Nuevo tipo de resultado</h2>
					<table>';
		}
		$contenido.='
					<tr>
						<td>
							nombre
						</td>
						<td>
							<input type="text" name="nombre" value="'.$nombre.'"/> 
						</td>
					</tr>
					<tr>
						<td>
						descripcion
						</td>
						<td>
							<input type="text" name="descripcion" value="'.$descripcion.'"/> 
						</td>
					</tr>
					<tr>
						<td>
						Unidad de medicion
						</td>
						<td>
							<input type="text" name="unidadmedicion" value="'.$unidadmedicion.'"/> 
						</td>
					</tr>
					<tr>
						<td>
						Analisis al que pertenece
						</td>
						<td>
							'.$this->analisis($idanalisis).'
						</td>
					</tr>
					<tr>
						<td>
						parametro inferior
						</td>
						<td>
							<input type="text" name="parametroinferior" value="'.$parametroinferior.'"/> 
						</td>
					</tr>
					<tr>
						<td>
						parametro superior
						</td>
						<td>
							<input type="text" name="parametrosuperior" value="'.$parametrosuperior.'"/> 
						</td>
					</tr>
					<tr>
						<td>
							estado
						</td>
						<td>';
		if ($estado){
			$contenido.='		<input type="radio" name="estado" value=true checked />Activo <br />
								<input type="radio" name="estado" value=false />Inactivo';
		}
		else {
			$contenido.='		<input type="radio" name="estado" value=true />Activo <br />
								<input type="radio" name="estado" value=false checked/>Inactivo';
		}
		$contenido.='		</td>
						</tr>
						<tr>' . $this->whoiam($accion) .
									'
							<td colspan="2">
								<input type="submit" value="Guardar"/>
							</td>
						</tr>
					</table></form>';
		
		return $contenido;
	}
	function buscar(){
		$nombre=isset($_GET["id"]) ? $_GET["id"] : ($nombre=isset($_GET["nombre"]) ? $_GET["nombre"] : '' );
		//$nombre=isset($_GET["nombre"]) ? $_GET["nombre"] : '';
		$modo=isset($_GET["modo"]) ? $_GET["modo"] : 'nombre';
		$idcategoria=isset($_GET["idanalisis"]) ? $_GET["idanalisis"] : '';
		if (!($idcategoria=='')){
			$resultado=$this->selectw('*','tiporesultado',"lower(".$modo.") like lower('".$nombre."') and idanalisis=".$idcategoria);
		} 
		else {
			$resultado=$this->selectw('*','tiporesultado',"lower(".$modo.") like lower('".$nombre."')"); 
		}
		if (count($resultado)>0){
			if (count($resultado)>1){
				return $this->escogeranalisis();
			}
			else {
				return $this->formulario($resultado[0]['id'],$resultado[0]['nombre'],$resultado[0]['descripcion'], $resultado[0]['estado'], $resultado[0]['unidadmedicion'], $resultado[0]['parametroinferior'], $resultado[0]['parametrosuperior'],$resultado[0]['idanalisis'], "editar");
			}
		} else {
			return $this->formulario('',$nombre,'',true,'','','','',"crear");
		}
	}
	function editar(){
		$unidadmedicion=isset($_GET["unidadmedicion"]) ? $_GET["unidadmedicion"] : '';
		$parametroinferior=isset($_GET["parametroinferior"]) ? $_GET["parametroinferior"] : '';
		$parametrosuperior=isset($_GET["parametrosuperior"]) ? $_GET["parametrosuperior"] : '';
		$idanalisis=isset($_GET["idanalisis"]) ? $_GET["idanalisis"] : '';
		$nombre=isset($_GET["nombre"]) ? $_GET["nombre"] : '';
		$descripcion=isset($_GET["descripcion"]) ? $_GET["descripcion"] : '';
		$estado=isset($_GET["estado"]) ? $_GET["estado"] : true;
		$id=isset($_GET["id"]) ? $_GET["id"] : "";
		$res="";
		$c=$this->update('tiporesultado',"nombre='".$nombre."', descripcion='".$descripcion."',
		 estado=".$estado.", unidadmedicion='".$unidadmedicion."', 
		 parametroinferior='".$parametroinferior."', parametrosuperior='".$parametrosuperior."',
		  idanalisis=".$idanalisis,"id='".$id."'");
		if ($c){
			$res="<h2>Se modifico correctamente</h2>";
		} 
		else {
			$res="<h2>Error, no se pudo actualizar</h2>";
		}		
		return $res.$this->buscar();
	}
	function crear(){
		$unidadmedicion=isset($_GET["unidadmedicion"]) ? $_GET["unidadmedicion"] : '';
		$parametroinferior=isset($_GET["parametroinferior"]) ? $_GET["parametroinferior"] : '';
		$parametrosuperior=isset($_GET["parametrosuperior"]) ? $_GET["parametrosuperior"] : '';
		$idanalisis=isset($_GET["idanalisis"]) ? $_GET["idanalisis"] : '';
		$nombre=isset($_GET["nombre"]) ? $_GET["nombre"] : '';
		$descripcion=isset($_GET["descripcion"]) ? $_GET["descripcion"] : '';
		$estado=isset($_GET["estado"]) ? $_GET["estado"] : true;
		if ($this->insert('tiporesultado','nombre, descripcion, estado,unidadmedicion,parametroinferior,parametrosuperior,idanalisis',"'".$nombre."','".$descripcion."',".$estado.",'".$unidadmedicion."','".$parametroinferior."','".$parametrosuperior."',".$idanalisis)){
		 return "<h2>Se creo la categoria correctamente, la puede modificar</h2>".$this->buscar();
		} else {
		 return "<h2>Error</h2>".$this->formulario('',$nombre,$descripcion,'true','','','','',"crear");
		}
	}
	function todo(){
        $temp1 = $this->select('*', 'categoriaanalisis');
        $i = 0;
        $i++;
        $rs = '<h2>Lista de Categorias de Analisis</h2>
		<table>
			<tr>
				<th>
					id.
				</th>
				<th>
					Nombre
				</th>
				<th>
					Descripcion
				</th>
				<th>
					Estado
				</th>
			</tr>';
        foreach ($temp1 as $temp2) {
            $rs .= '<tr><td>';
            $rs .= '<form method="GET" name="editar' . $i . '" action="index.php">' . $this->
                whoiam('buscar') . '<input type="hidden" name="nombre" value="' . $temp2['nombre'] .
                '"><input type="hidden" name="modo" value="nombre"><a title="Click para editar" href="javascript:document.editar' . $i .
                '.submit();">' . $i . '</a></form></td><td>' . $temp2['nombre'] . '</td><td>' . $temp2['descripcion'] .
                '</td><td>';
			if ($temp2['estado']){
				$rs.="activo";
			}
			else{
				$rs.="inactivo";
			} 
			$rs.= '</td></tr>';
            $i++;
        }
        $rs .= '</table>';
        return $rs;
    }
    function analisis($id){
		
		$analisis=$this->selectw('id, nombre','analisisespecifico','estado=true order by nombre');
		$res='<select name="idanalisis" style="width:400px;">';
		foreach ($analisis as $analisi){
			if ($analisi['id']==$id){
				$res .='<option value="'.$analisi['id'].'" selected>'.$analisi['nombre'].'</option>'; 
			}
			else {
				$res .='<option value="'.$analisi['id'].'">'.$analisi['nombre'].'</option>'; 
			}
		}
		$res.="</select>";
		return $res;
	}
    function escogeranalisis(){
		$nombre=isset($_GET["nombre"]) ? $_GET["nombre"] : '';
		$res='<h2>Se encontro varios tipo de resultado con el mismo nombre en diferentes analisis especificos,<br /> por favor escoger la categoria de la que desea modificar</h2>
		<form action="index.php" method="GET"><table><tr><th>Analisis</th><th>Categorias</th></tr><tr><td>
		<select name="idanalisis"><option value=""></option>';
		$categorias=$this->selectw('categoriaanalisis.nombre nombre, analisisespecifico.id id, analisisespecifico.nombre nombreanalisis','analisisespecifico inner join categoriaanalisis on analisisespecifico.idcategoria=categoriaanalisis.id inner join tiporesultado on tiporesultado.idanalisis=analisisespecifico.id',"lower(tiporesultado.nombre) like lower('%".$nombre."')");
		foreach($categorias as $categoria){
			$res.='<option value="'.$categoria['id'].'">'.$categoria['nombreanalisis'].', '.$categoria['nombre'].'</option>';
		} 
		$res.='</select><input type="hidden" name="modo" value="nombre" /><input type="hidden" name="nombre" value="'.$nombre.'" /></td></tr><tr><td>
		'.$this->whoiam('buscar').'<input type="submit" value="Siguiente"/></td></tr></table></form>';
		return $res;
	}
	function whoiam($accion)
    {
        $ver = isset($_GET["ver"]) ? $_GET["ver"] : '';
        return '<input type="hidden" name="ver" value="' . $ver .
        '" ><input type="hidden" name="accion" value="' . $accion . '" >';
    }
	
}
$accion = isset($_GET["accion"]) ? $_GET["accion"] : 'formulario1';
$admcatanalisis=new admresultados();
$contenido = '<div id="content" align="center"><div class="contactof">';
$contenido.= $admcatanalisis->$accion();
$contenido .= '</div><div><br /></div>';
echo $contenido;

?>