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
			echo "<li onClick=\"fill('$nombre', '$id');\">" . $nombre . "</li>";
		}
	}
	function analisisespecifico($buscar)
	{
		$modo = "idcategoria";
		header('Content-type: text/html; charset=iso-8859-1');
		echo '<option value="" selected disabled>Escoge una opcion</option>';
		$resultados = $this->selectw('distinct nombre, id', 'analisisespecifico', $modo . "=" . $buscar . " LIMIT 10");
		foreach ($resultados as $resultado) {
			echo '<option value="' . $resultado['id'] . '">' . $resultado['nombre'] . '</option>';
		}
	}
	function gettiporesultado($buscar)
	{
		$modo = "idanalisis";
		header('Content-type: text/html; charset=iso-8859-1');
		//echo "<div>si llegamos</div>";
		$result = $this->selectw('*', 'tiporesultado', $modo . "=" . $buscar);
		foreach ($result as $temp) {
			$id = $temp['id'];
			$nombre = $temp['nombre'];

			echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; width: 100%; max-width: 400px;">';
			echo '  <span>' . htmlspecialchars($nombre, ENT_QUOTES, 'ISO-8859-1') . '</span>';
			echo '<button type="button" onclick="window.location.href=\'index.php?ver=admtiporesultado&accion=buscar&modo=id&tabla=tiporesultado&nombre=' . $id . '\'">';
			echo 'Editar';
			echo '</button>';
			echo '</div>';
		}
	}
}
$motorjs = new motorjs();
$funcion = isset($_POST['funcion']) ? $_POST['funcion'] : '';
$buscar = isset($_POST['buscar']) ? $_POST['buscar'] : '';
$motorjs->$funcion($buscar);
