<?php
include('../bdlaboratorio.php');
class motorjs extends bdlaboratorio
{
	function categoriaanalisis($buscar)
	{
		$modo = "nombre";
		header('Content-type: text/html; charset=iso-8859-1');
		$result = $this->selectw('*', 'categoriaanalisis', "lower(" . $modo . ") LIKE lower('%" . $buscar . "%') LIMIT 10");
		foreach ($result as $temp) {
			$nombre = $temp['nombre'];
			$id = $temp['id'];
			echo "<li onClick=\"fill('$nombre', '$id');\">".$nombre."</li>";
		}
	}
	function analisisespecifico($buscar)
	{
		$modo = "idcategoria";
		header('Content-type: text/html; charset=iso-8859-1');
		$resultados = $this->selectw('distinct nombre, id', 'analisisespecifico',   $modo .  "=" . $buscar . " LIMIT 10");
		foreach ($resultados as $resultado) {
			echo '<option value="' . $resultado['id'] . '">' . $resultado['nombre'] . '</option>';
		}
	}
	function gettiporesultado($buscar)
	{
		$modo = "nombre";
		header('Content-type: text/html; charset=iso-8859-1');
		if ($modo == 'id') {
			$result = $this->selectw('*', 'tiporesultado', "lower(" . $modo . ") LIKE lower('%" . $buscar . "%') ");
			foreach ($result as $temp) {
				echo '<li onClick="fill(\'' . $temp['id'] . '\');">' . $temp['id'] .
					'</li>';
			}
		} else {
			$result = $this->selectw('distinct nombre', 'analisisespecifico', "lower(" . $modo . ") LIKE lower('%" . $buscar . "%') LIMIT 10");
			foreach ($result as $temp) {
				echo '<li onClick="fill(\'' . $temp[$modo] . '\');">' . $temp[$modo] . '</li>';
			}
		}
	}
}
$motorjs = new motorjs();
$funcion = isset($_POST['funcion']) ? $_POST['funcion'] : '';
$buscar = isset($_POST['buscar']) ? $_POST['buscar'] : '';
$motorjs->$funcion($buscar);
