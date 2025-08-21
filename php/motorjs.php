<?php
include('../bdlaboratorio.php');
class motorjs extends bdlaboratorio
{
    function pacienteb($buscar, $modo)
    {
        if ($modo == "atencion") {
            $result = $this->selectw('*', 'solicitud', "lower(id) LIKE lower('$buscar%') and estado=true LIMIT 10");
            foreach ($result as $result) {
                echo '<li onClick="fill(\'' . $result['id'] . '\');">' . $result['id'] .
                    '</li>';
            }
        } else {
            $result = $this->selectw('*', 'paciente', "lower(" . $modo . ") LIKE lower('$buscar%') and estado=true LIMIT 10");
            foreach ($result as $temp) {
                if ($modo == 'nombres') {
                    echo '<li onClick="fill(\'' . $temp['nombres'] . ',' . $temp['apellidos'] . '\');">' . $temp['nombres'] . ', ' . $temp['apellidos'] .
                        '</li>';
                } else {
                    if ($modo == 'apellidos') {
                        echo '<li onClick="fill(\'' . $temp['nombres'] . ',' . $temp['apellidos'] . '\');">' . $temp['apellidos'] . ', ' . $temp['nombres'] .
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
        $result = $this->selectw('*', 'doctor', "lower(" . $modo . ") LIKE lower('" . $buscar . "%') and estado=true LIMIT 10");
        foreach ($result as $temp) {
            if ($modo == 'nombres') {
                echo '<li onClick="fill(\'' . $temp['nombres'] . ',' . $temp['apellidos'] . '\');">' . $temp['nombres'] . ', ' . $temp['apellidos'] .
                    '</li>';
            } else {
                echo '<li onClick="fill(\'' . $temp[$modo] . '\');">' . $temp[$modo] .
                    '</li>';
            }
        }
    }
    function solicitud($buscar, $modo)
    {
        $result = $this->selectw('*', 'solicitud', "lower(" . $modo . ") LIKE lower('" . $buscar . "%') and estado=true LIMIT 10");
        foreach ($result as $temp) {
            if ($modo == 'nombres') {
                echo '<li onClick="fill(\'' . $temp['nombres'] . ',' . $temp['apellidos'] . '\');">' . $temp['nombres'] . ', ' . $temp['apellidos'] .
                    '</li>';
            } else {
                echo '<li onClick="fill(\'' . $temp[$modo] . '\');">' . $temp[$modo] .
                    '</li>';
            }
        }
    }
    function categoriaanalisis($buscar, $modo)
    {

        $result = $this->selectw('*', 'categoriaanalisis', "lower(" . $modo . ") LIKE lower('%" . $buscar . "%') LIMIT 10");
        foreach ($result as $temp) {
            if ($modo == 'nombres') {
                echo '<li onClick="fill(\'' . $temp['nombre'] . '\');">' . $temp['nombre'] .
                    '</li>';
            } else {
                echo '<li onClick="fill(\'' . $temp[$modo] . '\');">' . $temp[$modo] .
                    '</li>';
            }
        }
    }
    function analisisespecifico($buscar, $modo)
    {

        if ($modo == 'id') {
            $result = $this->selectw('*', 'analisisespecifico', "lower(" . $modo . ") LIKE lower('%" . $buscar . "%') LIMIT 10");
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
    function verdetalles($buscar, $modo)
    {

        $idatencion = $buscar;
        $todo = $this->todosolicitud($idatencion);
        $accionenv = 'editar';
        echo '<form name="detalle" id="formdet" method="POST">
        <table><input type="hidden" id="sol" name="idatencion" value=	"' . $idatencion . '"/>';

        echo '<input type="hidden" name="accion" value="autorizar2" >';

        echo '<tr><th>analisis</th><th>Resultado</th><th>Valor</th><th colspan="2">Parametro</th></tr>';
        $resultados = $this->resultados($idatencion);
        for ($i = 0; $i < count($resultados); $i++) {
            echo '<tr><td>' . $resultados[$i]['analisis'] . '</td><td>' . $resultados[$i]['resultado'] . '</td>
            <td><textarea name="a' . $i . '" rows=1 cols=20 >' . $resultados[$i]['valor'] . '</textarea>'
                . $resultados[$i]['unidadmedicion'] . '</td><td>' . $resultados[$i]['parametroinferior'] . ' - ' . $resultados[$i]['parametrosuperior'] . '<input type="hidden" name="b' . $i . '" value="' . $resultados[$i]['id'] . '"></td></tr>';
        }
        echo '<input type="hidden" value="guardarresultadosautorizar" name="funcion" /><input name="cantidad" type="hidden" value="' . count($resultados) . '">
		<tr><td></td><td><input  id="corregir" type="submit" value="Guardar" /></td>
		<td><input id="enviarauto" value="Autorizar" type="submit" onclick="return false;" disabled="true" />
		<input type="checkbox" id="autorizar" /></td>
        <td>';
        if (  $todo[0]['autorizado'] == true){
            echo '<input id="imprimir" value="Imprimir" type="submit" onclick="return false;" /></td></tr></table></form>';
        } else {
            echo '<input id="imprimir" value="Imprimir" type="submit" onclick="return false;" disabled="true" /></td></tr></table></form>';
        }    
    }


    function resultadosanteriores()
    {
        $res = '';
        $id = $_GET['idpaciente'];
        $atencionesanteriores = $this->selectw("*", "solicitud", "estado=true and idpaciente=" . $id);

        //aqui estan los anteriores analisis
        $res .= "<table><tr><th colspan='4'>Atenciones realizadas anteriormente</th></tr><tr><th>Acci&oacute;n</th><th>Fecha</th><th>diagnostico</th><th>doctor</th></tr>";
        foreach ($atencionesanteriores as $atencionanterior) {
            $res .= "<tr><td><form name='anterior' action='' ><input type='submit' value='ver'></form></td><td>" . $atencionanterior['fechacreacion'] . "</td><td>" . $atencionanterior['diagnostico'] . "</td><td>" . $atencionanterior['iddoctor'] . "</td></tr>";
        }
        $res .= "</table>";
    }
    function guardarresultadosautorizar($a, $b)
    {
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
        for ($i = 0; $i < $cantidad; $i++) {
            $datos[$i] = isset($_POST["a" . $i]) ? $_POST["a" . $i] : '';
            $ids[$i] = isset($_POST["b" . $i]) ? $_POST["b" . $i] : '';
        }
        $this->modificarres($datos, $ids);
        echo "la atencion se modifico correctamente";
    }

}
header('Content-Type: text/html; charset=utf-8');
$motorjs = new motorjs();
$funcion = isset($_POST['funcion']) ? $_POST['funcion'] : '';
$buscar = isset($_POST['buscar']) ? $_POST['buscar'] : '';
$modo = isset($_POST['modo']) ? $_POST['modo'] : '';
$motorjs->$funcion($buscar, $modo);
?>