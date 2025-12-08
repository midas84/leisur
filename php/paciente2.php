<?php
class admpaciente extends bdlaboratorio
{
    function whoiam($accion)
    {
        //    <input type="hidden" name="accion" value="' . $accionenv . '"/>
        $ver = isset($_GET["ver"]) ? $_GET["ver"] : '';
        return '<input type="hidden" name="ver" value="' . $ver .
            '" ><input type="hidden" name="accion" value="' . $accion . '" >';
    }

    function formulario($id, $ci, $nombre, $apellidos, $fechanac, $procedencia, $direccion,
        $sexo, $telefono, $celular, $email, $sangre, $factorrh, $observaciones, $seguro,
        $peso, $ciudad, $accion)
    {
        $res = '<form action="index.php" method="GET"><table>';
        if ($id != '') {
            $res .= '<td><a href="reportes/carnet.php?gruposanguineo=' . $sangre .
                '&nombres=' . $nombre . '&apellidos=' . $apellidos . '&seguro=' . $seguro .
                '&factorrh=' . $factorrh . '&telefono=' . $telefono . '&id=' . $id .
                '&observacion=' . $observaciones .
                '" target="_blank">Imprimir Carnet</a></td><td><a href="index.php?nombre=' . $id .
                '&ver=atencion&accion=nuevo&modo=id&tabla=pacienteb">Llenar atencion</a></td>';
            $res .= '<tr ><td id="tablaf1">Id usuario:</td><td><input type="text" name="id" size="25" value="' .
                $id . '" readonly="true" /></td></tr>';
        }
        $res .= '<tr><td>C.I.:</td><td><input name="ci" size="25" value="' . $ci .
            '" type="text1"/>
       </td><td>Expedido en:</td><td>' . $this->ciudad($ciudad) . ' </td></tr>
<tr><td>nombres:</td><td><input name="nombre" size="25" value="' . $nombre .
            '" type="text"/></td></tr>
<td>Apellidos:</td><td><input name="apellidos" size="25" value="' . $apellidos .
            '" type="text"/></td></tr>
<tr><td>Fecha Nacimiento(aaaa-mm-dd):</td><td><input name="fechanac" size="25" value="' .
            $fechanac . '" type="text"/></td></tr>
<tr><td>peso:</td><td><input name="peso" size="25" value="' . $peso .
            '" type="text"/></td></tr>
<tr><td>procedencia:</td><td><input name="procedencia" size="25" value="' . $procedencia .
            '" type="text"/></td></tr>
<tr><td>direccion:</td><td><input name="direccion" size="25" value="' . $direccion .
            '" type="text"/></td></tr>
<tr><td>telefono:</td><td><input name="telefono" size="25" value="' . $telefono .
            '" type="text"/></td></tr>
<tr><td>celular:</td><td><input name="celular" size="25" value="' . $celular .
            '" type="text"/></td></tr>
<tr><td>email:</td><td><input name="email" size="25" value="' . $email .
            '" type="text"/></td></tr>
<tr><td>observaciones:</td><td><textarea name="observaciones" size="25" />' . $observaciones .
            '</textarea></td></tr>
<tr><td>seguro:</td><td><input name="seguro" size="25" value="' . $seguro .
            '" type="text"/></td></tr>
<tr><td>tipo de Sangre:</td><td>' . $this->sangre($sangre) .
            '</td> <td>Factor Rh:</td>
<td>' . $this->factorrh($factorrh) . '</td></tr>
<tr><td>Sexo:</td><td>' . $this->sexo($sexo) . $this->whoiam($accion) .
            '</td></tr>
<tr><td cospan="2"><input type="submit" value="guardar"/></td>
            </tr>
</table>';
        return $res;
    }
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
</style><form name="i" method="GET" action="index.php" >
<table><tr><th><h2>Ingresar paciente nuevo o que se va a editar</h2></th></tr><tr><td>' .
            $this->whoiam('nuevo') . 'Buscar por:
<input type="radio" name="modo" value="nombres" checked>Nombre (nombre,apellido)
<input type="radio" name="modo" value="apellidos" checked>Apellido (apellido,nombre)    
<input type="radio" name="modo" value="ci">CI  
<input type="radio" name="modo" value="id">ID paciente</td></tr>
<input type="hidden" name="tabla" value="pacienteb">
<tr><td colspan="2">
<input type="text" autocomplete="off" type="text" id="nombre" name="nombre" onkeyup="lookup(this.value, document.i.modo, document.i.tabla.value);" >
<div class="suggestionsBox" id="suggestions" style="display: none;">
				<div class="suggestionList" id="autoSuggestionsList">
					&nbsp;
				</div></div></td></tr><tr><td><input type="submit" value="Buscar"/></td></tr></table></form><br>
                <form name="ii" method="GET" action="index.php"><table><tr><td>' .
            $this->whoiam('todo') .
            '<input type="submit" value="Mostrar Todos los Pacientes"/></td></tr></table></form>';
        return $contenido;
    }

    function nuevo($nombre, $modo)
    {
        $ret = '';
        if ($modo=="apellidos") $modo="nombres";
        $usuario = $this->buscarnombre($nombre, $modo);
        if (count($usuario) > 0) {
            return $this->editar($usuario[0]['id']);
        } else {
            $ret = '<h2>Ingresar Nuevo Usuario</h2>';
            
            switch ($modo) {
                case 'ci':
                    $ret .= $this->formulario('', $nombre, '', '', '', '', '', '', '', '', '', '',
                        '', '', '', '', '', 'guardar');
                    break;
                case 'nombres':
                    $sep = explode(",", $nombre);
                    $ret .= $this->formulario('', '', $sep[0], $sep[1], '', '', '', '', '', '', '',
                        '', '', '', '', '', '', 'guardar');
                    break;
                case 'id':
                    $ret .= 'no existe paciente debe crear uno nuevo o volver a buscar por otro metodo' .
                        $this->formulario1();
                    break;
            }

        }
        return $ret;

    }

    function guardar($ci, $nombre, $apellidos, $fechanac, $procedencia, $direccion,
        $sexo, $telefono, $celular, $email, $sangre, $factorrh, $observaciones, $seguro,
        $peso, $ciudad)
    {
        $res = '';
        if (!($this->buscarpaciente($nombre, $apellidos))) {
            $res .= 'existe revisa los datos';
            $res .= $this->formulario('', $ci, $nombre, $apellidos, $fechanac, $procedencia,
                $direccion, $sexo, $telefono, $celular, $email, $sangre, $factorrh, $observaciones,
                $seguro, $peso, $ciudad, 'guardar');
        } else {
            $band = $this->nuevopaciente($ci, $nombre, $apellidos, $fechanac, $procedencia,
                $direccion, $sexo, $telefono, $celular, $email, $sangre, $factorrh, $observaciones,
                $seguro, $peso, $ciudad);
            if ($band) {
                $res .= 'Se introdujo bien';
                $rs = $this->ultimopaciente();
                $res .= $this->editar($rs[0]['max']);

            } else {
                $res = 'error, disculpe el inconveniente, informar al administrador de este problema y anotar los datos que introdujo';
                $res .= $this->formulario('', $ci, $nombre, $apellidos, $fechanac, $procedencia,
                    $direccion, $sexo, $telefono, $celular, $email, $sangre, $factorrh, $observaciones,
                    $seguro, $peso, $ciudad, 'guardar');
            }


        }
        return $res;
    }   
    function editar($id)
    {

        $rs = $this->llenareditarpaciente($id);
        $res = '<center><h2>Editar Paciente</h2></center>';
        $res .= $this->formulario($rs[0]['id'], $rs[0]['ci'], $rs[0]['nombres'], $rs[0]['apellidos'],
            $rs[0]['fechanacimiento'], $rs[0]['procedencia'], $rs[0]['direccion'], $rs[0]['sexo'],
            $rs[0]['telefono'], $rs[0]['celular'], $rs[0]['email'], $rs[0]['gruposanguineo'],
            $rs[0]['factorrh'], $rs[0]['observacion'], $rs[0]['seguro'], $rs[0]['peso'], $rs[0]['ciudad'],
            'editar1');
        return $res;

    }
    function editar1($id, $ci, $nombre, $apellidos, $fechanac, $procedencia, $direccion,
        $sexo, $telefono, $celular, $email, $sangre, $factorrh, $observaciones, $seguro,
        $peso, $ciudad)
    {
        $this->editarpaciente($id, $ci, $nombre, $apellidos, $fechanac, $procedencia, $direccion,
            $sexo, $telefono, $celular, $email, $sangre, $factorrh, $observaciones, $seguro,
            $peso, $ciudad);
        $res = 'Se edito correctamente' . $this->editar($id);
        return $res;
    }
    function eleminar1($id)
    {
        $this->update('usuario', "estado=false", 'id=' . $id);
        $this->insert('bitacora', 'idusuario, tabla, accion, idtupla', $_SESSION['id'] .
            ", 'usuario','delete'," . $id);
        $this->update('seguridad', "estado=false", 'idusuario=' . $id);
        $temp1 = $this->selectw('id', 'seguridad', 'estado=false and idusuario=' . $id);
        foreach ($temp1 as $temp2) {
            $this->insert('bitacora', 'idusuario, tabla, accion, idtupla', $_SESSION['id'] .
                ", 'seguridad','delete'," . $temp2['id']);
        }
        return $this->formulario1() . '<h3>Se elimino correctamente</h3>';
    }
    function eliminar($id)
    {
        return '<form name="ii" method="GET" action="index.php">' . $this->whoiam('eliminar1') .
            '<input type="hidden" name="id" value="' . $id .
            '"><a href="javascript:document.ii.submit();">eliminar</a></form>';
    }
    function verificar($nombre, $apellido)
    {
        $rs = $this->selectw('id', 'paciente', "lower(nombres)=lower('" . $nombre .
            "') and lower(apellidos)=lower('" . $apellido . "')");
        if (count($rs) > 0) {
            return false;
        } else {
            return true;
        }
    }
    function buscarnombre($nombre, $modo)
    {

        if ($modo == 'nombres') {
            $nombresep = explode(",", $nombre);
            $rs = $this->selectw('id', 'paciente', $modo . "='" . $nombresep[0] .
                "' and apellidos='" . $nombresep[1] . "' and estado=true");
        } else {
            $rs = $this->selectw('id', 'paciente', $modo . "='" . $nombre .
                "' and estado=true");
        }
        return $rs;
    }
    function buscarid($id)
    {
        $rs = $this->selectw('*', 'paciente', "id='" . $id . "' and estado=true");
        return $rs;
    }
    function todo()
    {
        $temp1 = $this->selectw('*', 'paciente', 'estado=true');
        $i = 0;
        $i++;
        $rs = '<h2>Lista de Pacientes Registrados</h2><table><tr><th>No.</th><th>C&oacute;digo de usuario</th><th>Nombre</th><th>Apellido</th><th>CI</th><th>Ciudad de Carnet</th><th>Observaciones</th></tr>';
        foreach ($temp1 as $temp2) {
            $rs .= '<tr><td>';
            $rs .= '<form method="GET" name="editar' . $i . '" action="index.php">' . $this->
                whoiam('editar') . '<input type="hidden" name="id" value="' . $temp2['id'] .
                '"><a title="Click para editar" href="javascript:document.editar' . $i .
                '.submit();">' . $i . '</a></form></td><td>' . $temp2['id'] . '</td><td>' . $temp2['nombres'] .
                '</td><td>' . $temp2['apellidos'] . '</td><td>' . $temp2['ci'] . '</td><td>' . $temp2['ciudad'] .
                '</td><td>' . $temp2['observacion'] . '</td></tr>';
            $i++;
        }
        $rs .= '</table>';
        return $rs;
    }
    function factorrh($fac)
    {
        $res = '<SELECT NAME="factorrh" SIZE=1">';
        $factor = array(
            '',
            'positivo',
            'negativo');
        foreach ($factor as $temp1) {
            if ($temp1 == $fac) {
                $res .= '<OPTION VALUE="' . $temp1 . '" selected>' . $temp1 . '</OPTION>';
            } else {
                $res .= '<OPTION VALUE="' . $temp1 . '" >' . $temp1 . '</OPTION>';
            }
        }
        $res .= '</select>';
        return $res;
    }
    function sangre($sang)
    {
        $res = '<SELECT NAME="sangre" SIZE=1" > ';
        $sangre = array(
            '',
            'A',
            'B',
            'AB',
            'O');
        foreach ($sangre as $temp1) {
            if (strtolower($temp1) == strtolower($sang)) {
                $res .= '<OPTION VALUE="' . $temp1 . '" selected>' . $temp1 . '</OPTION>';
            } else {
                $res .= '<OPTION VALUE="' . $temp1 . '" >' . $temp1 . '</OPTION>';
            }
        }
        $res .= '</select>';
        return $res;
    }
    function sexo($sex)
    {
        $res = '';
        if ($sex == 'h') {
            $res = '<input type="radio" name="sexo" value="h" checked>Hombre
        <input type="radio" name="sexo" value="m" >Mujer';
        } else {
            if ($sex = 'm') {
                $res = '<input type="radio" name="sexo" value="h" >Hombre
        <input type="radio" name="sexo" value="m" checked>Mujer';
            } else {
                $res = '<input type="radio" name="sexo" value="h" >Hombre
        <input type="radio" name="sexo" value="m">Mujer';
            }

        }
        return $res;
    }
    function ciudad($ciu)
    {
        $ciudad = array(
            '',
            'Chuquisaca',
            'Cochabamba',
            'Tarija',
            'La Paz',
            'Oruro',
            'Potosi',
            'Santa Cruz',
            'Pando',
            'Beni');
        $res = '<SELECT NAME="ciudad" SIZE=1">';
        foreach ($ciudad as $temp1) {
            if (strtolower($temp1) == strtolower($ciu)) {
                $res .= '<OPTION VALUE="' . $temp1 . '" selected>' . $temp1 . '</OPTION>';
            } else {
                $res .= '<OPTION VALUE="' . $temp1 . '" >' . $temp1 . '</OPTION>';
            }
        }
        $res .= '</select>';
        return $res;
    }

}
$pagina = new admpaciente();
$errorlogin = isset($_SESSION["error"]) ? $_SESSION["error"] : false;
$ci = isset($_GET["ci"]) ? $_GET["ci"] : '';
$nombre = isset($_GET["nombre"]) ? $_GET["nombre"] : '';
$apellidos = isset($_GET["apellidos"]) ? $_GET["apellidos"] : '';
$fechanac = isset($_GET["fechanac"]) ? $_GET["fechanac"] : '';
$peso = isset($_GET["peso"]) ? $_GET["peso"] : '';
$procedencia = isset($_GET["procedencia"]) ? $_GET["procedencia"] : '';
$direccion = isset($_GET["direccion"]) ? $_GET["direccion"] : '';
$telefono = isset($_GET["telefono"]) ? $_GET["telefono"] : '';
$celular = isset($_GET["celular"]) ? $_GET["celular"] : '';
$email = isset($_GET["email"]) ? $_GET["email"] : '';
$observaciones = isset($_GET["observaciones"]) ? $_GET["observaciones"] : '';
$seguro = isset($_GET["seguro"]) ? $_GET["seguro"] : '';
$sexo = isset($_GET["sexo"]) ? $_GET["sexo"] : '';
$factorrh = isset($_GET["factorrh"]) ? $_GET["factorrh"] : '';
$sangre = isset($_GET["sangre"]) ? $_GET["sangre"] : '';
$sexo = isset($_GET["sexo"]) ? $_GET["sexo"] : '';
$accion = isset($_GET["accion"]) ? $_GET["accion"] : '';
$id = isset($_GET["id"]) ? $_GET["id"] : '';
$modo = isset($_GET["modo"]) ? $_GET["modo"] : '';
$ciudad = isset($_GET["ciudad"]) ? $_GET["ciudad"] : '';
$contenido = '<div id="content" align="center"><div class="contactof">';
switch ($accion) {
    case 'nuevo':
        $contenido .= $pagina->nuevo($nombre, $modo);
        break;
    case 'guardar':
        $contenido .= $pagina->guardar($ci, $nombre, $apellidos, $fechanac, $procedencia,
            $direccion, $sexo, $telefono, $celular, $email, $sangre, $factorrh, $observaciones,
            $seguro, $peso, $ciudad);
        break;
    case 'editar':
        $contenido .= $pagina->editar($id);
        break;
    case 'eliminar':
        $contenido .= $pagina->eliminar($id);
        break;
    case 'eliminar1':
        $contenido .= $pagina->eleminar1($id);
        break;
    case 'editar1':
        $contenido .= $pagina->editar1($id, $ci, $nombre, $apellidos, $fechanac, $procedencia,
            $direccion, $sexo, $telefono, $celular, $email, $sangre, $factorrh, $observaciones,
            $seguro, $peso, $ciudad);
        break;
    case 'todo':
        $contenido .= $pagina->todo();
        break;
    case '':
        $contenido .= $pagina->formulario1();
        break;
}
$contenido .= '</div><div><br /></div>';
echo $contenido;
?>
