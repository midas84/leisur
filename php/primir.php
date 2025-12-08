<?php
$idatencion = isset($_GET["idatencion"]) ? $_GET["idatencion"] : '';
$cantidad = isset($_GET["cantidad"]) ? $_GET["cantidad"] : '';
$accion = isset($_GET["accion"]) ? $_GET["accion"] : '';
$analisisseleccionado = isset($_GET["analisisseleccionado"]) ? $_GET["analisisseleccionado"] : null;
$doctor = isset($_GET["doctor"]) ? $_GET["doctor"] : '';
$doctor1 = isset($_GET["doctor1"]) ? $_GET["doctor1"] : '';
$boton = isset($_GET["volver"]) ? $_GET["volver"] : '';
$preciof = isset($_GET["precio"]) ? $_GET["precio"] : '';
$muestra = isset($_GET["muestra"]) ? $_GET["muestra"] : null;
$diagnostico = isset($_GET["diagnostico"]) ? $_GET["diagnostico"] : '';
$pago = isset($_GET["pago"]) ? $_GET["pago"] : '';

echo '<form action="index.php" name="form" method="GET"><table border="3">';
if ($boton == 'volver') {
	$accion = 'nuevo';
}
switch ($accion) {
	case '':
		echo '<tr><td>Introducir id de atencion</td><td>
				  <input type="text" name="idatencion"></td></tr><tr>';
		$accionenv = 'nuevo';
		break;
	case 'nuevo':

		$todo = $base->todosolicitud($idatencion);
		$accionenv = 'editar';
		if ($todo[0]['autorizado']) {
			echo '<a href="reportes/resultados.php?idsol=' . $idatencion . '" target="_blank">Imprimir resultados</a>';
		} else
			echo 'No puede imprimir estos resultados por que todavia no estan autorizados';
		echo '<table border="1" >';
		echo '<tr><th colspan="4" align="center"><input type="hidden" name="idatencion" value=	"' . $idatencion . '"/>Ficha de atencion</th></tr>';
		echo '<tr><td colspan="2">
		<img width="200" src="http://localhost/laboratorio0.7/reportes/codigob.php?codigo=' . $idatencion . '"></td><td>nombrepaciente:</td><td>' . $todo[0]['nombres'] . ' ' . $todo[0]['apellidos'] . '</td></tr>
					<tr><td>Fecha</td><td>' . $todo[0]['fecha'] . '</td><td>Numero atencion del dia</td><td>' . $todo[0]['numerodia'] . '</td></tr>
					<tr><td colspan="2">Diagnostico Presuntivo</td><td colspan="2">' . $todo[0]['diagnostico'] . '</td></tr>
					';
		echo '<tr><th>analisis</th><th>Resultado</th><th>Valor</th><th colspan="2">Parametro</th></tr>';
	/*	$paquetes = $base->selectw(
			'resultados.id id, tiporesultado.nombre resultado, paquete.nombre paquete, resultados.valor, resultados.id idresultados, tiporesultado.unidadmedicion, tiporesultado.parametroinferior, tiporesultado.parametrosuperior'
			,
			'resultados INNER JOIN perfiladquirido ON resultados.idpaquete = perfiladquirido.id INNER JOIN paquete ON perfiladquirido.idpaquete = paquete.id inner join tiporesultado on tiporesultado.id = resultados.idtiporesultado'
			,
			'perfiladquirido.estado=true and resultados.estado=true and perfiladquirido.idsolicitud=' . $idatencion
		);
		$cuenta = 0;
		for ($i = 0; $i < count($paquetes); $i++) {
			echo '<tr><td>' . $paquetes[$i]['paquete'] . '</td>
            <td>' . $paquetes[$i]['resultado'] . '</td>
            <td><textarea name="a' . $i . '" rows=1 cols=20 />' . $paquetes[$i]['valor'] . '</textarea>' . $paquetes[$i]['unidadmedicion'] . '</td>
            <td>' . $paquetes[$i]['parametroinferior'] . ' - ' . $paquetes[$i]['parametrosuperior'] .
				'<input type="hidden" name="b' . $i . '" value="' . $paquetes[$i]['id'] . '"></td></tr>';
			$cuenta = $i + 1;
		}

*/



		$resultados = $base->resultados($idatencion);
		for ($i = 0; $i < count($resultados); $i++) {
			echo '<tr><td>' . $resultados[$i]['analisis'] . '</td><td>' . $resultados[$i]['resultado'] . '</td><td><input name="a' . $i . '" value="' . $resultados[$i]['valor'] . '">' . $resultados[$i]['unidadmedicion'] . '</td><td>' . $resultados[$i]['parametroinferior'] . ' - ' . $resultados[$i]['parametrosuperior'] . '<input type="hidden" name="b' . $i . '" value="' . $resultados[$i]['id'] . '"></td></tr>';
		}
		echo '<input name="cantidad" type="hidden" value="' . count($resultados) . '">';
		break;

	case 'editar': 	//$base->autorizar($idatencion);
		$todo = $base->todosolicitud($idatencion);
		$accionenv = 'editar';

		echo '<table border="1" >';
		if ($todo[0]['autorizado']) {
			echo '<tr><th colspan="4">Ya se autorizo estos resultados</th></tr>';
		} else {
			echo '<tr><th colspan="4">No se autorizo todavia estos resultados</th></tr>';
		}
		echo '<tr><th colspan="4" align="center"><input type="hidden" name="idatencion" value=	"' . $idatencion . '"/>Ficha de atencion</th></tr>';
		echo '<tr><td colspan="2"><img width="200" src="http://localhost/laboratorio/reportes/codigob.php?codigo=' . $idatencion . '"></td><td>nombrepaciente:</td><td>' . $todo[0]['nombres'] . '</td></tr>
					<tr><td>Fecha</td><td>' . $todo[0]['fecha'] . '</td><td>Numero atencion del dia</td><td>' . $todo[0]['numerodia'] . '</td></tr>
					<tr><td>Diagnostico</td><td>' . $todo[0]['diagnostico'] . '</td></tr>
					';
		echo '<tr><th>analisis</th><th>Resultado</th><th>Valor</th><th colspan="2">Parametro</th></tr>';
		$resultados = $base->resultados($idatencion);
		for ($i = 0; $i < count($resultados); $i++) {
			echo '<tr><td>' . $resultados[$i]['analisis'] . '</td><td>' . $resultados[$i]['resultado'] . '</td><td><input name="a' . $i . '" value="' . $resultados[$i]['valor'] . '">' . $resultados[$i]['unidadmedicion'] . '</td><td>' . $resultados[$i]['parametroinferior'] . ' - ' . $resultados[$i]['parametrosuperior'] . '<input type="hidden" name="b' . $i . '" value="' . $resultados[$i]['id'] . '"></td></tr>';
		}
		echo '<input name="cantidad" type="hidden" value="' . count($resultados) . '">';
		break;
}



echo '<tr><td cospan="2"><input type="hidden" name="ver" value="primir"/><input type="hidden" name="accion" value="' . $accionenv . '"/></td></tr>';
echo '</table></form>';
?>