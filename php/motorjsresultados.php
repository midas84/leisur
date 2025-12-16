<?php
include('../bdlaboratorio.php');

class motorjs extends bdlaboratorio
{
    function categoriaanalisis($buscar)
    {
        $modo = 'nombre';

        $result = $this->selectw('*', 'categoriaanalisis', 'lower(' . $modo . ") LIKE lower('%" . $buscar . "%') LIMIT 10");
        foreach ($result as $temp) {
            $nombre = $temp['nombre'];
            $id = $temp['id'];
            echo "<li onClick='fill(\"$nombre\", \"$id\")'>$nombre</li>";
        }
    }

    function analisisespecifico($buscar)
    {
        $modo = 'idcategoria';

        echo '<option value="" selected disabled>Escoge una opcion</option>';
        $resultados = $this->selectw('distinct nombre, id', 'analisisespecifico', $modo . '=' . $buscar . ' LIMIT 10');
        foreach ($resultados as $resultado) {
            echo '<option value="' . $resultado['id'] . '">' . $resultado['nombre'] . '</option>';
        }
    }

    function gettiporesultado($buscar)
    {
        $modo = 'idanalisis';

        //echo '<div>si llegamos</div>';
        $result = $this->selectw('*', 'tiporesultado', $modo . '=' . $buscar);

        // Variable para alternar el fondo
        $dark = true;

        foreach ($result as $temp) {
            $id = $temp['id'];
            $nombre = $temp['nombre'] . ' ';
            switch ($temp['filacompleta']) {
                case 0:
                    $nombre .= '│ con referencia';
                    break;
                case 1:
                    $nombre .= '│ filacompleta';
                    break;
                case 2:
                    $nombre .= '│ columna izquierda';
                    break;
                case 3:
                    $nombre .= '│ columna derecha';
                    break;
                case 4:
                    $nombre .= '│ En tabla';
                    break;

            }

            $nombre .= ' ' . ($temp['estado'] ? '│ Activo' : '│ Inactivo');

            // Elegir color según la variable $dark
            $bgColor = $dark ? '#e0e0e0' : '#ffffff';
            // oscuro y claro ( puedes ajustar los colores )

            echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; width: 100%; background-color: ' . $bgColor . '; padding: 4px 8px;">';
            echo '  <span>' . htmlspecialchars($nombre, ENT_QUOTES, 'utf-8') . '</span>';
            echo '<button class="botone" type="button" onclick="window.location.href=\'index.php?ver=admtiporesultado&accion=buscar&modo=id&tabla=tiporesultado&nombre=' . $id . '\'">';
            echo 'Editar';
            echo '</button>';
            echo '</div>';

            // Cambiar el valor para la siguiente iteración
            $dark = !$dark;

        }
    }
}
header('Content-type: text/html; charset=charset=utf-8');
$motorjs = new motorjs();
$funcion = isset($_POST['funcion']) ? $_POST['funcion'] : '';
$buscar = isset($_POST['buscar']) ? $_POST['buscar'] : '';
$motorjs->$funcion($buscar);
