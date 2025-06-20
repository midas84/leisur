<?php 
class admcatanalisis extends bdlaboratorio {
	function formulario1()
    {
        $contenido = '<script type="text/javascript" src="js/buscar.js" ></script>';
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
								<th>
									<h2>Ingresar Categoria Analisis nuevo o que se va a editar</h2>
								</th>
							</tr>
							<tr>
								<td>' .$this->whoiam('buscar') . 'Buscar por:
									<input type="radio" name="modo" value="nombre" checked>Nombre    
									<input type="radio" name="modo" value="id">ID categoria  
								</td>
							</tr>
							<input type="hidden" name="tabla" value="categoriaanalisis">
							<tr>
								<td colspan="2">
									<input autocomplete="off" type="text" id="nombre" name="nombre" onkeyup="lookup(this.value, document.i.modo, document.i.tabla.value);" >
									<div class="suggestionsBox" id="suggestions" style="display: none;">
										<div class="suggestionList" id="autoSuggestionsList">
											&nbsp;
										</div>
									</div>
								</td>
							</tr>
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
	function formulario($id, $nombre, $descripcion, $estado, $accion){
		$contenido='<form action="index.php" method="GET">';
		if (!($id=='')){
		$contenido.='<h2>Categoria Encontrada <br />Editar Categoria</h2>
					<table>
					<tr>
						<td>
							id
						</td>
						<td>
							'.$id.' <input type="hidden" name="id" value="'.$id.'"/> 
						</td>
					</tr>';
		} 
		else {
			$contenido.='<h2>No existe la categoria se creara una nueva <br />Ingresar Nueva Categoria</h2>
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
		$nombre=isset($_GET["nombre"]) ? $_GET["nombre"] : '';
		$modo=isset($_GET["modo"]) ? $_GET["modo"] : 'nombre';
		$resultado=$this->selectw('*','categoriaanalisis',"lower(".$modo.") like lower('".$nombre."')");
		if (count($resultado)>0){
			return $this->formulario($resultado[0]['id'],$resultado[0]['nombre'],$resultado[0]['descripcion'], $resultado[0]['estado'],"editar");
		} else {
			return $this->formulario('',$nombre,'',true,"crear");
		}	
	}
	function editar(){
		$nombre=isset($_GET["nombre"]) ? $_GET["nombre"] : '';
		$descripcion=isset($_GET["descripcion"]) ? $_GET["descripcion"] : '';
		$estado=isset($_GET["estado"]) ? $_GET["estado"] : true;
		$id=isset($_GET["id"]) ? $_GET["id"] : "";
		$res="";
		$c=$this->update('categoriaanalisis',"nombre='".$nombre."', descripcion='".$descripcion."', estado=".$estado,"id='".$id."'");
		if ($c){
			$res="<h2>Se modifico correctamente</h2>";
		} 
		else {
			$res="<h2>Error, no se pudo actualizar</h2>";
		}		
		return $res.$this->buscar();
	}
	function crear(){
		$nombre=isset($_GET["nombre"]) ? $_GET["nombre"] : '';
		$descripcion=isset($_GET["descripcion"]) ? $_GET["descripcion"] : '';
		$estado=isset($_GET["estado"]) ? $_GET["estado"] : true;
		if ($this->insert('categoriaanalisis','nombre, descripcion, estado',"'".$nombre."','".$descripcion."',".$estado)){
		 return "<h2>Se creo la categoria correctamente, la puede modificar</h2>".$this->buscar();
		} else {
		 return "<h2>Error</h2>".$this->formulario('',$nombre,$descripcion,'true',"crear");
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
	function whoiam($accion)
    {
        $ver = isset($_GET["ver"]) ? $_GET["ver"] : '';
        return '<input type="hidden" name="ver" value="' . $ver .
            '" ><input type="hidden" name="accion" value="' . $accion . '" >';
    }
	
}
$accion = isset($_GET["accion"]) ? $_GET["accion"] : 'formulario1';
$admcatanalisis=new admcatanalisis();
$contenido = '<div id="content" align="center"><div class="contactof">';
$contenido.= $admcatanalisis->$accion();
$contenido .= '</div><div><br /></div>';
echo $contenido;
?>