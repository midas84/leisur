<?php
class admatencion extends bdlaboratorio
{
    function whoiam($accion)
    {
        //    <input type="hidden" name="accion" value="' . $accionenv . '"/>
        $ver = isset($_GET["ver"]) ? $_GET["ver"] : '';
        return '<input type="hidden" name="ver" value="' . $ver .
            '" ><input type="hidden" name="accion" value="' . $accion . '" >';
    }
    function formulario($paqueteseleccionado, $id, $fecha, $diagnostico, $analisisseleccionado,
        $doctor, $accion)
    {
        $res = '
        <script type="text/javascript">
			$(function(){
			    $.datepicker.setDefaults( $.datepicker.regional[ "es" ] );
                $("#datepicker").datepicker({
                    changeYear: true
                });
                $( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" ); 

                $( "#datepicker" ).val("' . $fecha . '");
			 });
			 </script>
             <link rel="stylesheet" href="css/example.css" TYPE="text/css" MEDIA="screen">
<script type="text/javascript" src="js/tabber.js"></script>
<script type="text/javascript" src="js/resultadosanteriores.js"></script>
<script type="text/javascript" src="js/resultados.js"></script>';
        $res .= "<center><form><input type='hidden' name='ver' value='resultadosanteriores'><input type='hidden' name='id' value='" .
            $id . "'> <input id='resultadosanteriores' type='submit' value='Ver Resultados Anteriores del paciente'> </form></center>";
        $res .= '<form name="i" method="GET" action="index.php"><table>';
        $res .= $this->whoiam($accion);
        $datospaciente = $this->llenardatospaciente($id);
        // aqui se llena el id del paciente;
        $res .= '<tr><td>Paciente</td><td>' . $datospaciente[0]['nombres'] . ' ' . $datospaciente[0]['apellidos'] .
            '<input type="hidden" name="id" value="' . $datospaciente[0]['id'] .
            '"></td></tr><tr><td>Diagnostico presuntivo</td><td ><input type="text" name="diagnostico" value="' .
            $diagnostico . '" /></td></tr>
            <tr><td >Fecha de la atenci&oacute;n mm/dd/aaaa</td><td ><input type="text" id="datepicker" name="fecha" value="" /></td></tr>';
        // select del doctor
        $res .= '<tr><td >Nombre del Doctor:</td><td >' . $this->doctor('') .
            '</td></tr>';
        // desde aqui es el check
        $res .= '<tr><td colspan="2">' . $this->paquete($paqueteseleccionado) .
            '</td></tr>';
        $res .= '<tr><td colspan="2">' . $this->check($analisisseleccionado) .
            '</td></tr>
        <tr><td><input type="submit" value="guardar" /></td></tr></table>';
        return $res;
    }
    function paquete($seleccionados)
    {
        $columna = 0;
        $paquetes = $this->select("*", "paquete");
        $resultado = '<div class="tabber" id="tab1"><div class="tabbertab">
                    <h2>Paquetes</h2><table>';
        foreach ($paquetes as $paquete) {
            if ($columna == 0) {
                $resultado .= '<tr>';
            }
            $columna++;
            $chequeado = "";
            foreach ($seleccionados as $seleccion) {
                if ($seleccion == $paquete['id']) {
                    $chequeado = "checked";
                }
            }
            $resultado .= '<td><input type="checkbox" name="paquete[]" value="' . $paquete['id'] .
                '" ' . $chequeado . ' />' . $paquete['nombre'] . '</td>';
            if ($columna == 5) {
                $resultado .= '</tr>';
                $columna = 0;
            }
        }
        if ($columna != 0) {
            $resultado .= '</tr>';
        }
        $resultado .= '</table></div></div>';
        return $resultado;
    }
    function formularioedicion($solicitud)
    {
        $res = '
        <script type="text/javascript">
            $(function(){
                $.datepicker.setDefaults( $.datepicker.regional[ "es" ] );
                $("#datepicker").datepicker({
                    changeYear: true
                    
                });
                $( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" ); 

                $( "#datepicker" ).val("' . $solicitud['fecha'] . '");
             });
             </script>
             <link rel="stylesheet" href="css/example.css" TYPE="text/css" MEDIA="screen">
<script type="text/javascript" src="js/tabber.js"></script>
<script type="text/javascript" src="js/resultados.js"></script>
<form name="i" method="GET" action="index.php"><table>';
        $res .= $this->whoiam($solicitud['accion']);
        $datospaciente = $this->llenardatospaciente($solicitud['idpaciente']);
        // aqui se llena el id del paciente;
        $res .= '<tr><td>ID de la Atencion</td><td>' . $solicitud['id'] .
            '</td></tr><tr><td>Paciente</td><td>' . $datospaciente[0]['nombres'] . ' ' . $datospaciente[0]['apellidos'] .
            '<input type="hidden" name="id" value="' . $solicitud['id'] .
            '"></td></tr><tr><td>Diagnostico presuntivo</td><td ><input type="text" name="diagnostico" value="' .
            $solicitud['diagnostico'] . '" /></td></tr>
            <tr><td >Fecha de la atenci&oacute;n mm/dd/aaaa</td><td ><input type="text" id="datepicker" name="fecha" value="" /></td></tr>';
        // select del doctor
        $res .= '<tr><td >Nombre del Doctor:</td><td >' . $this->doctor($solicitud['doctor']) .
            '</td></tr>';
        // desde aqui es el check
        $res .= '<tr><td colspan="2">' . $this->check2($solicitud['analisis']) .
            '</td></tr>
        <tr><td><input type="submit" value="Editar" /></td></tr></table>';
        return $res;
    }
    function resultados2()
    {
        $res = '<div style="color: #448; background: #dde9ec;"><input checked id="' . $_GET['id'] .
            '" type="checkbox" onclick="hace(this.id);"/>Marcar Todos</div>';
        $temp1 = $this->selectw('*', 'tiporesultado', 'estado=true and idanalisis=' . $_GET['id']);
        foreach ($temp1 as $temp2) {
            $res .= '<div style="color: #7A91A2; "><input checked class="resultado' . $_GET['id'] .
                '" type="checkbox"  name="b[]" value="' . $temp2['id'] . '">' . $temp2['nombre'] .
                '</div>';
        }
        return $res;
    }
    function check2($analisisseleccionado)
    {
        $analisis = $this->checkanalisis();
        $checkanalisis = '';
        $o = 0;
        $e = 0;
        for ($i = 0; $i < sizeof($analisis); $i++) {
            if ($o == 5) {
                $checkanalisis = $checkanalisis . '</tr></table></div></div>';
                $o = 0;
                $e = 0;
            }
            if ($o == 0) {
                $o++;
                $checkanalisis = $checkanalisis .
                    '<div class="tabber" id="tab1"><div class="tabbertab">
                <h2>' . $analisis[$i]['nombrec'] . '</h2><p><table>';
            } else {
                if ($analisis[$i]['nombrec'] != $analisis[$i - 1]['nombrec']) {
                    $o++;
                    $e = 0;
                    $checkanalisis = $checkanalisis .
                        '</tr></table></p></div><div class="tabbertab">
                    <h2>' . $analisis[$i]['nombrec'] . '</h2><p><table>';
                }
            }
            if ($analisis[$i]['idanalisis'] == null) { //  ver si esto esta bien asi
                $band = true;
                if ($e == 4) {
                    $e = 0;
                    $checkanalisis .= '</tr>';
                }
                if ($e == 0) {
                    $checkanalisis .= '<tr>';
                }
                for ($a = 0; $a < sizeof($analisisseleccionado); $a++) {
                    if ($analisis[$i]['id'] == $analisisseleccionado[$a]['idanalisis']) {
                        $e++;
                        $checkanalisis .= '<td><input class="res" type="checkbox" onclick="" name="a[]" value="' .
                            $analisis[$i]['id'] . '" checked />' . $analisis[$i]['nombrea'] .
                            '<div class="res" id="res' . $analisis[$i]['id'] .
                            '" style=" background-color: #eeeeee; border: 1px solid #778;">';
                        $checkanalisis .= '<div style="color: #448; background: #dde9ec;">
                            <input checked id="' . $analisis[$i]['id'] .
                            '" type="checkbox" onclick="hace(this.id);"/>
                            Marcar Todos</div>';

                        $temp1 = $this->selectw('*', 'tiporesultado', 'estado=true and idanalisis=' . $analisis[$i]['id']);
                        $resultadoss = $this->selectw('*', 'resultados', 'estado=true and idatencion=' .
                            $analisisseleccionado[$a]['id']);
                        foreach ($temp1 as $temp2) {
                            $chequeado = '';
                            foreach ($resultadoss as $resul)
                                if ($temp2['id'] == $resul['idtiporesultado']) {
                                    $chequeado = "checked";
                                }
                            $checkanalisis .= '<div style="color: #7A91A2; "><input ' . $chequeado .
                                ' class="resultado' . $analisis[$i]['id'] .
                                '" type="checkbox"  name="b[]" value="' . $temp2['id'] . '">' . $temp2['nombre'] .
                                '</div>';
                        }
                        $checkanalisis .= '</div></td>';
                        $band = false;
                    }
                }
                if ($band) {
                    $e++;
                    $checkanalisis .= '<td><input class="res" type="checkbox" onclick="" name="a[]" value="' .
                        $analisis[$i]['id'] . '" />' . $analisis[$i]['nombrea'] .
                        '<div class="res" id="res' . $analisis[$i]['id'] .
                        '" style="display: none; background-color: #eeeeee; border: 1px solid #778;"></div></div></td>';
                } else {
                    $band = false;
                }

            }
        }
        $checkanalisis = $checkanalisis . '</tr></table></div></div>';
        return $checkanalisis;
    }
    function check($analisisseleccionado)
    {
        $analisis = $this->checkanalisis();
        $checkanalisis = '';
        $o = 0;
        $e = 0;
        for ($i = 0; $i < sizeof($analisis); $i++) {
            if ($o == 5) {
                $checkanalisis = $checkanalisis . '</tr></table></div></div>';
                $o = 0;
                $e = 0;
            }
            if ($o == 0) {
                $o++;
                $checkanalisis = $checkanalisis .
                    '<div class="tabber" id="tab1"><div class="tabbertab">
                <h2>' . $analisis[$i]['nombrec'] . '</h2><p><table>';
            } else {
                if ($analisis[$i]['nombrec'] != $analisis[$i - 1]['nombrec']) {
                    $o++;
                    $e = 0;
                    $checkanalisis = $checkanalisis .
                        '</tr></table></p></div><div class="tabbertab">
                    <h2>' . $analisis[$i]['nombrec'] . '</h2><p><table>';
                }
            }
            if ($analisis[$i]['idanalisis'] == null) { //  ver si esto esta bien asi
                $band = true;
                if ($e == 4) {
                    $e = 0;
                    $checkanalisis .= '</tr>';
                }
                if ($e == 0) {
                    $checkanalisis .= '<tr>';
                }
                for ($a = 0; $a < sizeof($analisisseleccionado); $a++) {
                    if ($analisis[$i]['id'] == $analisisseleccionado[$a]) {
                        $e++;
                        $checkanalisis .= '<td><input class="res" type="checkbox" onclick="" name="a[]" value="' .
                            $analisis[$i]['id'] . '" checked />' . $analisis[$i]['nombrea'] .
                            '<div class="res" id="res' . $analisis[$i]['id'] .
                            '" style="display: none; background-color: #eeeeee; border: 1px solid #778;"></div></td>';
                        $band = false;
                    }
                }
                if ($band) {
                    $e++;
                    $checkanalisis .= '<td><input class="res" type="checkbox" onclick="" name="a[]" value="' .
                        $analisis[$i]['id'] . '" />' . $analisis[$i]['nombrea'] .
                        '<div class="res" id="res' . $analisis[$i]['id'] .
                        '" style="display: none; background-color: #eeeeee; border: 1px solid #778;"></div></div></td>';
                } else {
                    $band = false;
                }
            }
        }
        $checkanalisis = $checkanalisis . '</tr></table></div></div>';
        return $checkanalisis;
    }
    function formulario1()
    {
        $contenido = '';
        $contenido .= '<script type="text/javascript" src="js/buscar.js" ></script><style type="text/css">
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
            <form name="i" method="GET" action=
            "index.php">
            <table><tr><th><h2>Ingresar paciente o atencion</h2></th></tr><tr><td>' .
            $this->whoiam('nuevo') . 'Buscar por:
            <input id="seleccionmodo" type="radio" name="modo" value="id" checked>ID paciente
            <input id="seleccionmodo" type="radio" name="modo" value="nombres" >Nombre (nombre,apellido)  
            <input id="seleccionmodo" type="radio" name="modo" value="apellidos" >apellido (apellido, nombre)   
            <input id="seleccionmodo" type="radio" name="modo" value="ci">CI  
            <input id="seleccionmodo" type="radio" name="modo" value="atencion" >ID de la Atencion</td></tr>
            <input type="hidden" name="tabla" value="pacienteb">
            <tr><td colspan="2">
            <center><input type="text" autocomplete="off" type="text" id="nombre" name="nombre" onkeyup="lookup(this.value, document.i.modo, document.i.tabla.value);" >
            <div class="suggestionsBox" id="suggestions" style="display: none;" />
            				<div class="suggestionList" id="autoSuggestionsList">
            					&nbsp;
            				</div></center></td></tr><tr><td colspan="2"><center><input type="submit" value="Buscar"/></center></td></tr></table></form><br />
                            <form name="ii" method="GET" action="index.php"><table><tr><td>' .
            $this->whoiam('todo2') .
            '<input type="submit" value="Mostrar Todas las atenciones"/></td></tr></table></form><br />
                        <form name="iii" method="GET" action="index.php"><table><tr><td>
                        ' . $this->whoiam('todo') .
            '<input type="submit" value="Mostrar Todos los pacientes"/></td></tr></table></form>';
        return $contenido;
    }
    function nuevo($nombre, $modo)
    {
        $ret = '';
        if ($modo == 'apellidos')
            $modo = 'nombres';
        switch ($modo) {
            case 'ci':
                $usuario = $this->selectw('*', 'paciente', "estado=true and ci='" . $nombre .
                    "'");
                if (count($usuario) > 0) {
                    return $this->formulario(array(), $usuario[0]['id'], strftime("%Y-%m-%d", time()),
                        '', array(), '', 'preguardar'); //($usuario[0]['id']);
                } else {
                    $ret = '<h2>No existe paciente, vuela a intentar</h2>' . $this->formulario1();
                }
                break;
            case 'nombres':
                $sep = explode(",", $nombre);
                $usuario = $this->selectw('*', 'paciente', "estado=true and nombres='" . $sep[0] .
                    "' and apellidos='" . $sep[1] . "'");
                if (count($usuario) > 0) {
                    return $this->formulario(array(), $usuario[0]['id'], strftime("%Y-%m-%d", time()),
                        '', array(), '', 'preguardar'); //($usuario[0]['id']);
                } else {
                    $ret = '<h2>No existe paciente, vuela a intentar</h2>' . $this->formulario1();
                }
                break;
            case 'id':
                $usuario = $this->selectw('*', 'paciente', 'estado=true and id=' . $nombre);
                if (count($usuario) > 0) {
                    return $this->formulario(array(), $nombre, strftime("%Y-%m-%d", time()), '',
                        array(), '', 'preguardar'); //($usuario[0]['id']);
                } else {
                    $ret = '<h2>No existe paciente, vuela a intentar</h2>' . $this->formulario1();
                }
                break;
            case 'atencion':
                $usuario = $this->selectw('*', 'solicitud', 'id=' . $nombre);
                if (count($usuario) > 0) {
                    $_GET['id'] = $usuario[0]['id'];
                    return $this->editar(); //($usuario[0]['id']);
                } else {
                    $ret = '<h2>No existe atencion, vuela a intentar</h2>' . $this->formulario1();
                }
                break;
        }
        return $ret;
    }
    function todo()
    {
        $temp1 = $this->selectw('*', 'paciente', 'estado=true');
        $i = 1;
        $rs = '<h2>Lista de Pacientes Registrados</h2><table><tr><th>No.</th><th>C&oacute;digo de usuario</th><th>Nombre</th><th>Apellido</th><th>CI</th><th>Ciudad de Carnet</th><th>Observaciones</th></tr>';
        foreach ($temp1 as $temp2) {
            $rs .= '<tr><td><form method="GET" name="editar' . $i . '" action="index.php">' .
                $this->whoiam('nuevo') .
                '<input type="hidden" name="modo" value="id"><input type="hidden" name="nombre" value="' .
                $temp2['id'] . '"><a title="Click para editar" href="javascript:document.editar' .
                $i . '.submit();">' . $i . '</a></form></td><td>' . $temp2['id'] . '</td><td>' .
                $temp2['nombres'] . '</td><td>' . $temp2['apellidos'] . '</td><td>' . $temp2['ci'] .
                '</td><td>' . $temp2['ciudad'] . '</td><td>' . $temp2['observacion'] .
                '</td></tr>';
            $i++;
        }
        $rs .= '</table>';
        return $rs;
    }

    function todo2()
    {
        $temp1 = $this->selectw('solicitud.id idatencion, paciente.id idpaciente, solicitud.fechacreacion fechacreacion, paciente.nombres nombre, paciente.apellidos apellido',
            'solicitud inner join paciente on solicitud.idpaciente=paciente.id',
            'solicitud.estado=true');
        $i = 1;
        $rs = '<h2>Lista de Atencion</h2><table><tr><th>No.</th><th>Nombre Paciente</th><th>ID Paciente</th><th>ID Atencion</th><th>Fecha de Creaci&oacute;n Atenci&oacute;n</th></tr>';
        foreach ($temp1 as $temp2) {
            $rs .= '<tr><td><form method="GET" name="editar' . $i . '" action="index.php">' .
                $this->whoiam('editar') . '<input type="hidden" name="id" value="' . $temp2['idatencion'] .
                '"><input type="submit" value="' . $i . '"/></form></td><td>' . $temp2['nombre'] .
                ' ' . $temp2['apellido'] . '</td><td>' . $temp2['idpaciente'] . '</td><td>' . $temp2['idatencion'] .
                '</td><td>' . $temp2['fechacreacion'] . '</td></tr>';
            $i++;
        }
        $rs .= '</table>';
        return $rs;
    }
    function doctor($seleccionado)
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
            <input type="hidden" name="tabla" value="doctorb" />
            <input type="hidden" name="modo" value="nombre" />
            <input type="text" value="' . $seleccionado .
            '" autocomplete="off" type="text" id="nombre" 
            name="doctor" onkeyup="lookup(this.value, document.i.modo.value, document.i.tabla.value);" />
            <div class="suggestionsBox" id="suggestions" style="display: none;">
            				<div class="suggestionList" id="autoSuggestionsList">
            					&nbsp;
            				</div></div>';
        return $contenido;
    }
    function guardar($paquetes, $paciente, $diagnostico, $fecha, $resultados, $doctor, $precio)
    {
        //aqui se guarda doctor
        $doc = $this->selectw('*', 'doctor', "lower(nombre)=lower('" . $doctor . "')");
        if (count($doc) == 0) {
            $this->insert('doctor', 'nombre', '"' . $doctor . '"');
            $idd = $this->selectw('id', 'doctor', "nombre='" . $doctor . "'");
            $iddoctor = $idd[0]['id'];
        } else {
            $iddoctor = $doc[0]['id'];
        }
        //aqui se guarda solicitud
        $res = $this->selectw('max(atenciondia) max', 'solicitud', "fechacreacion='" . $fecha .
            "'"); 
        if ($res[0]['max'] != '') {
            $idsolicitud = $res[0]['max'] + 1;
        } else {
            $idsolicitud = 1;
        }
        $into = 'solicitud';
        $campos = 'atenciondia, fechacreacion, iddoctor, idpaciente, diagnostico, preciototalestimado';
        $values = $idsolicitud . ",'" . $fecha . "'," . $iddoctor . "," . $paciente .
            ",'" . $diagnostico . "'," . $precio;
        $this->insert($into, $campos, $values);
        //colocar eso de que en caso de error avise
        $idsolicitud = $this->selectw('MAX( id ) max', 'solicitud', "estado=true");
        $banderaatencion = true;
        $banderaatencion2 = '';
        $atencion = '';
        $banderasubgrupo = false;
        //agregamos los paquetes
        foreach ($paquetes as $paquete){
             $idsolicitud[0]['max']; 
             $paquete;
             $existe= $this->selectw('*','perfiladquirido','idsolicitud='.$idsolicitud[0]['max'].' and idpaquete='.$paquete);
             if (count(!($existe)>0)){
                $this->insert('perfiladquirido', 'idsolicitud, idpaquete', $idsolicitud[0]['max'].', '. $paquete);
                $idperfil = $this->select('MAX( id ) max', 'perfiladquirido');
                $resultadosbd=$this->selectw('idresultado','perfilpaquete','idpaquete='.$paquete);
                foreach ($resultadosbd as $resultadobd){
                    $into = 'resultados';
                    $campos = 'idtiporesultado,  idpaquete';
                    //sacamos el idanalisis de los resultados
                    $values = $resultadobd[0] . "," . $idperfil[0]['max'];
                    $this->insert($into, $campos, $values);
                }
             }     
        }
        //agregamos los resultados
        foreach ($resultados as $resultado) {
            $analisis = $this->selectw('idanalisis', 'tiporesultado', 'estado=true and id=' .
                $resultado);
            if ($banderaatencion2 == $analisis[0]['idanalisis']) {
                $banderaatencion = false;
            } else {
                $banderaatencion = true;
                $banderasubgrupo = true;
            }
            $banderaatencion2 = $analisis[0]['idanalisis'];
            if ($banderaatencion) {
                $into = 'atencion';
                $campos = 'idsolicitud,  idanalisis';
                //sacamos el idanalisis de los resultados
                $values = $idsolicitud[0]['max'] . "," . $analisis[0]['idanalisis'];
                $this->insert($into, $campos, $values);
                $atencion = $this->select('MAX( id ) max', 'atencion');
            }
            $this->insert('resultados', 'idatencion, idtiporesultado', $atencion[0]['max'] .
                ',' . $resultado);
            //subgrupos
            if ($banderasubgrupo) {
                $banderasubgrupo = false;
                $subgrupo = $this->selectw('id', 'analisisespecifico', 'idanalisis=' . $analisis[0]['idanalisis']);
                foreach ($subgrupo as $a) {
                    $values2 = $idsolicitud[0]['max'] . "," . $a['id'];
                    $this->insert($into, $campos, $values2);
                    $aten2 = $this->select('MAX( id ) max', 'atencion');
                    $aten3 = $this->selectw('idanalisis', 'atencion', 'id=' . $aten2[0]['max']);
                    $resultados2 = $this->selectw('tiporesultado.id',
                        'tiporesultado INNER JOIN analisisespecifico ON tiporesultado.idanalisis = analisisespecifico.id',
                        'analisisespecifico.id=' . $aten3[0]['idanalisis']);
                    foreach ($resultados2 as $b) {
                        $this->insert('resultados', 'idatencion, idtiporesultado', $aten2[0]['max'] .
                            ',' . $b['id']);
                    }
                }
            }
            //metemos los resultados
        }
       return ' <div  style=" width=500px;"><embed src="reportes/atencion.php?idsol=' .
           $idsolicitud[0]['max'] .
           '" type="application/pdf" width="500px" ></div>';
    }
    function editar()
    {
        $res = $this->selectw('*', 'solicitud', 'id=' . $_GET['id']);
        $resultado['id'] = $_GET['id'];
        $resultado['diagnostico'] = $res[0]['diagnostico'];
        $resultado['fecha'] = $res[0]['fechacreacion'];
        $pac = $this->selectw('*', 'paciente', 'id=' . $res[0]['idpaciente']);
        $resultado['nombrepaciente'] = $pac[0]['nombres'] . ' ' . $pac[0]['apellidos'];
        $resultado['idpaciente'] = $res[0]['idpaciente'];
        $doc = $this->selectw('*', 'doctor', 'id=' . $res[0]['iddoctor']);
        $resultado['doctor'] = $doc[0]['nombre'];
	$resultado['paquete']=$this->selectw('id, idanalisis', 'atencion', 'estado=true and idsolicitud=' . $resultado['id']);
        $resultado['analisis'] = $this->selectw('id, idanalisis', 'atencion',
            'estado=true and idsolicitud=' . $resultado['id']);
        $resultado['accion'] = 'editarf';
        return $this->formularioedicion($resultado);
    }
    function editarf()
    {
        $datos = $_GET;
        $diagnostico = isset($datos['diagnostico']) ? $datos['diagnostico'] : '';
        $atenciones = $this->selectw("*", "atencion", "estado=true and idsolicitud=" . $datos['id']);
        foreach ($atenciones as $atencion) {
            $this->update("resultados", "estado=false", "idatencion=" . $atencion['id']);
            $this->update("atencion", "estado=false", "id=" . $atencion['id']);
        }
        $doctor = $this->selectw('*', 'doctor', "lower(nombre)=lower('" . $datos['doctor'] .
            "')");
        if (count($doctor) == 0) {
            $this->insert('doctor', 'nombre', '"' . $doctor . '"');
            $idd = $this->selectw('id', 'doctor', "nombre='" . $doctor . "'");
            $iddoctor = $idd[0]['id'];
        } else {
            $iddoctor = $doctor[0]['id'];
        }
        if (($iddoctor != null) && ($datos['fecha'] != '') && ($datos['id'] != '')) {
            $this->update("solicitud", "fechacreacion=" . $datos['fecha'] . " , iddoctor=" .
                $iddoctor . ", diagnostico=" . $datos['diagnostico'], "id=" . $datos['id']);
        } else {
            return $this->editar($datos['id']);
        }
        foreach ($datos['a'] as $idanalisis) {
            $atenciones = $this->selectw('*', 'atencion', 'idsolicitud=' . $datos['id'] .
                " and idanalisis=" . $idanalisis);
            if (0 != count($atenciones)) {
                $this->update('atencion', 'estado=true', 'idsolicitud=' . $datos['id'] .
                    " and idanalisis=" . $idanalisis);
            } else {
                $this->insert('atencion', "idsolicitud, idanalisis", $datos['id'] . ", " . $idanalisis);
                $atenciones = $this->selectw('*', 'atencion', 'idsolicitud=' . $datos['id'] .
                    " and idanalisis=" . $idanalisis);
            }
            $tiporesultados = $this->selectw('id', 'tiporesultado', "idanalisis=" . $idanalisis);
            foreach ($tiporesultados as $tiporesultado) {
                foreach ($datos['b'] as $idtiporesultado) {
                    if ($idtiporesultado == $tiporesultado['id']) {
                        $encontrados = $this->selectw("id", "resultados", "idtiporesultado=" . $idtiporesultado .
                            " and idatencion=" . $atenciones[0]['id']);
                        if (count($encontrados) > 0) {
                            $this->update("resultados", "estado=true", "idtiporesultado=" . $idtiporesultado .
                                " and idatencion=" . $atenciones[0]['id']);
                        } else {
                            $this->insert("resultados", "idtiporesultado, idatencion", $idtiporesultado .
                                ", " . $atenciones[0]['id']);
                        }
                    }
                }
            }
        }
        return ' <div  style="height:670px; width=500px;"><embed src="reportes/atencion.php?idsol=' .
            $datos['id'] . '" type="application/pdf" width="50%" height="100%"></div>';
    }
    function eliminar()
    {

    }
    function preguardar($paquetes, $paciente, $diagnostico, $fecha, $resultados, $analisis,
        $doctor)
    {
        $resultadosimprimibles =
            '<script type="text/javascript" src="js/ir.js" ></script>
        <table><tr><th>Resumen de Atenci&oacute;n</th></tr>
        <tr><td>ID del paciente: ' . $paciente . '</td><td></td><td>Receptor: ' .
            $doctor . '</td></tr>
        <tr><td>Fecha emici&oacute;n: ' . $fecha .
            ' </td><td>. .</td><td>Diagn&oacute;stico presuntivo: ' . $diagnostico .
            '</td></tr></table><table>';
        $preciototal = 0;
        $resultadosimprimibles .= '<th>paquetes</th>';
        foreach ($paquetes as $paquete) {
            foreach ($this->selectw('*', 'paquete', 'id=' . $paquete) as $datospaquete) {
                $resultadosimprimibles .= '<tr><td>' . $datospaquete['nombre'] . '</td></tr>';
                
                
                
               // $precio = ($datospaquete[0]['precio'] / $cantidadresultados[0]['cantidad']) * (sizeof
               // ($nombres));
            $preciototal += $datospaquete['precio'];
            $resultadosimprimibles .= '<tr><td colspan="2">Precio:' . number_format($datospaquete['precio'],
                2, ".", ",") . ' Bs.</td></tr>';
                
                
            }
        }
        $resultadosimprimibles .= '<th>Analisis</th>';
        foreach ($analisis as $analis) {
            $nombres = array();
            $ids = array();
            foreach ($resultados as $resu) {
                $todores = $this->selectw('*', 'tiporesultado', 'id=' . $resu);
                $bandera = true;
                if ($todores[0]['idanalisis'] == $analis) {
                    $nombres[] = $todores[0]['nombre'];
                    $ids[] = $todores[0]['id'];
                }
            }
            $todoanalisis = $this->selectw('*', 'analisisespecifico', 'id=' . $analis);
            $resultadosimprimibles .= '<tr><td>' . $todoanalisis[0]['nombre'] .
                '</td></tr>';
            $cantidadresultados = $this->selectw('count(id) cantidad', 'tiporesultado',
                'idanalisis=' . $analis);
            $precio = ($todoanalisis[0]['precio'] / $cantidadresultados[0]['cantidad']) * (sizeof
                ($nombres));
            $preciototal += $precio;
            $resultadosimprimibles .= '<tr><td colspan="2">Precio:' . number_format($precio,
                2, ".", ",") . ' Bs.</td></tr>';
            $resultadosimprimibles .= '<tr><td><br /></td></tr>';
        }
        $resultadosimprimibles .= '<tr><th colspan="2">Precio Total:' . number_format($preciototal,
            1, ".", ",") . '0 Bs.</th></tr><tr><td><br /></td></tr></table>
        <form method="GET" name="guardar" action="index.php"><table><tr><td>
            ' . $this->whoiam('guardar') . $this->llenar($paquetes, $paciente, $diagnostico,
            $fecha, $resultados, $analisis, $doctor, $preciototal) . '
            <input type="submit" id="guardar" value="Crear atenci&oacute;n" onclick="return ir(this.id);" /></td>
            <td><input type="submit" id="cotizar" value="Crear cotizaci&oacute;n" onclick="return ir(this.id);" /></td>
            <td><input type="submit" id="atras" value="Volver Atras" onclick="javascript:history.back(1)" /></td></tr></table></form>';
        return $resultadosimprimibles;
    }
    function cotizar($id, $fecha, $diagnostico, $analisisseleccionado)
    {
        return ' <div  style="height:670px;width="500px;"><embed src="http://localhost/laboratorio0.7/reportes/resultados.php?idsol=1000012" type="application/pdf" width="50%" height="100%"></div> ';
    }
    function atras($id, $fecha, $diagnostico, $analisisseleccionado)
    {
        return $this->formulario($id, $fecha, $diagnostico, $analisisseleccionado,
            'preguardar');
    }
    function llenar($paquetes, $id, $diagnostico, $fecha, $resultados, $analisis, $doctor,
        $precio)
    {
        $res = '<input type="hidden" name="id" value="' . $id . '">
        <input type="hidden" name="diagnostico" value="' . $diagnostico . '">
        <input type="hidden" name="fecha" value="' . $fecha . '">
        <input type="hidden" name="doctor" value="' . $doctor . '">
        <input type="hidden" name="precio" value="' . number_format($precio, 1,
            ".", ",") . '0">';
        foreach ($resultados as $resultado) {
            $res .= '<input type="hidden" name="b[]" value="' . $resultado . '">';
        }
        foreach ($paquetes as $paquete) {
            $res .= '<input type="hidden" name="paquete[]" value="' . $paquete . '">';
        }
        return $res;
    }
    function aatencion($doctor, $pacientes, $diagnostico, $analisis)
    {
        $idsolicitud = $this->agregarsolicitud($doctor, $pacientes, $diagnostico, $precio);
        foreach ($analisis as $i) {
            if ($i['seleccionado']) {
                $this->agregaratencionanalisis($idsolicitud[0]['max'], $i);
                $aten1 = $this->select('MAX( id ) max', 'atencion');
                $aten = $this->selectw('idanalisis', 'atencion', 'id=' . $aten1[0]['max']);
                $resultados = $this->selectw('tiporesultado.id',
                    'tiporesultado INNER JOIN analisisespecifico ON tiporesultado.idanalisis = analisisespecifico.id',
                    'analisisespecifico.id=' . $aten[0]['idanalisis']);
                foreach ($resultados as $m) {
                    $this->insert('resultados', 'idatencion, idtiporesultado', $aten1[0]['max'] .
                        ',' . $m['id']);
                }
                $subgrupo = $this->selectw('id', 'analisisespecifico', 'idanalisis=' . $i);
                foreach ($subgrupo as $a) {
                    $this->agregaratencionanalisis($idsolicitud[0]['max'], $a['id']);
                    $aten2 = $this->select('MAX( id ) max', 'atencion');
                    $aten3 = $this->selectw('idanalisis', 'atencion', 'id=' . $aten2[0]['max']);
                    $resultados2 = $this->selectw('tiporesultado.id',
                        'tiporesultado INNER JOIN analisisespecifico ON tiporesultado.idanalisis = analisisespecifico.id',
                        'analisisespecifico.id=' . $aten3[0]['idanalisis']);
                    foreach ($resultados2 as $b) {
                        $this->insert('resultados', 'idatencion, idtiporesultado', $aten2[0]['max'] .
                            ',' . $b['id']);
                    }
                }
            }
        }
    }
    function asolicitud($iddoctor, $idpaciente, $diagnostico, $precio)
    {
        $res = $this->selectw('max(atenciondia) max', 'solicitud', "fechacreacion='" .
            strftime("%Y-%m-%d", time()) . "'");
        if ($res[0]['max'] != '') {
            $idsolicitud = $res[0]['max'] + 1;
        } else {
            $idsolicitud = 1;
        }
        $into = 'solicitud';
        $campos = 'atenciondia, fechacreacion, iddoctor, idpaciente, diagnostico, preciototalestimado';
        $values = $idsolicitud . ",now()," . $iddoctor . "," . $idpaciente . ",'" . $diagnostico .
            "'," . $precio;
        $this->insert($into, $campos, $values);
        return $this->selectw('MAX( id ) max', 'solicitud', "estado=true");
    }
    function amuestra($idsolicitud, $muestra)
    {
        //$re=$this->selectw('id','tipomuestra',"(nombre='".$muestra."') and (estado=true)");
        $into = 'muestra';
        $campos = 'idsolicitud,idtipomuestra';
        $values = $idsolicitud . ",'" . $muestra . "'";
        return $this->insert($into, $campos, $values);
    }
    function pprecio($analisis)
    {
        return $this->selectw('precio', 'analisisespecifico', '(' . $analisis .
            '=id) and (estado=true)');
    }
    function ndoctor($nombredoctor)
    {
        $into = 'doctor';
        $campos = 'nombre';
        $values = "'" . $nombredoctor . "'";
        $this->insert($into, $campos, $values);
        return $this->listadoctor();
    }
    function checkanalisis()
    {
        return $this->selectw('analisisespecifico.id id, analisisespecifico.precio precio, analisisespecifico.nombre nombrea,
         categoriaanalisis.nombre nombrec, analisisespecifico.idanalisis idanalisis',
            'analisisespecifico INNER JOIN categoriaanalisis ON analisisespecifico.idcategoria = categoriaanalisis.id',
            'analisisespecifico.estado = true');
    }
    function aatencionanalisis($idsolicitud, $analisis)
    {
        $into = 'atencion';
        $campos = 'idsolicitud,  idanalisis';
        $values = $idsolicitud . "," . $analisis;
        return $this->insert($into, $campos, $values);
    }
    function apago($idsolicitud, $pago)
    {
        $into = 'pago';
        $campos = 'idsolicitud, monto';
        $values = $idsolicitud . "," . $pago;
        return $this->insert($into, $campos, $values);
    }
}
$accion = isset($_GET["accion"]) ? $_GET["accion"] : '';
$id = isset($_GET["id"]) ? $_GET["id"] : '';
$nombre = isset($_GET["nombre"]) ? $_GET["nombre"] : '';
$modo = isset($_GET["modo"]) ? $_GET["modo"] : '';
$b = isset($_GET["b"]) ? $_GET["b"] : array();
$a = isset($_GET["a"]) ? $_GET["a"] : array();
$doctor = isset($_GET["doctor"]) ? $_GET["doctor"] : '';
$analisisseleccionado = isset($_GET["a"]) ? $_GET["a"] : null;
$boton = isset($_GET["volver"]) ? $_GET["volver"] : '';
$preciof = isset($_GET["precio"]) ? $_GET["precio"] : '';
$muestra = isset($_GET["muestra"]) ? $_GET["muestra"] : null;
$diagnostico = isset($_GET["diagnostico"]) ? $_GET["diagnostico"] : '';
$pago = isset($_GET["pago"]) ? $_GET["pago"] : '';
$fecha = isset($_GET["fecha"]) ? $_GET["fecha"] : '';
$precio = isset($_GET["precio"]) ? $_GET["precio"] : 0.0;
$paquetes = isset($_GET["paquete"]) ? $_GET["paquete"] : array();
$atencion = new admatencion();
header('Content-Type: text/html; charset=utf-8');
$contenido = '<div id="content" align="center"><div class="contactof">';
switch ($accion) {
    case 'nuevo':
        $contenido .= $atencion->nuevo($nombre, $modo);
        break;
    case 'preguardar':
        $contenido .= $atencion->preguardar($paquetes, $id, $diagnostico, $fecha, $b, $a,
            $doctor);
        break;
    case 'guardar':
        $contenido .= $atencion->guardar($paquetes, $id, $diagnostico, $fecha, $b, $doctor, $precio);
        break;
    case 'todo':
        $contenido .= $atencion->todo();
        break;
    case 'todo2':
        $contenido .= $atencion->todo2();
        break;
    case 'cotizar':
        $contenido .= $atencion->cotizar($id, $fecha, $diagnostico, $analisisseleccionado);
        break;
    case 'atras':
        $contenido .= $atencion->atras($id, $fecha, $diagnostico, $analisisseleccionado);
        break;
    case 'todo2':
        $contenido .= $atencion->todo2();
        break;
    case '':
        $contenido .= $atencion->formulario1();
        break;
    case 'editar':
        $contenido .= $atencion->editar($id);
        break;
    case 'eliminar':
        $contenido .= $atencion->eliminar($id);
        break;
    case 'eliminar1':
        $contenido .= $atencion->eleminar1($id);
        break;

    case 'editarf':
        $contenido .= $atencion->editarf();
        break;
    default:
        $contenido .= '<h2>No existe la accion</h2>';
}
$contenido .= '</form></div><div><br /></div>';
echo $contenido;
?>
