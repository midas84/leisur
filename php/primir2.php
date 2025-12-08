<?php
	class primir extends bdlaboratorio
	{
	function javascript(){	
		$jas='<script type="text/javascript">
			function javascript(){
				$(function() {
					$(".hola").hide();
					$(".hola").attr("value",false);
					

					$(".detalles").click(function() {	
						punto=".hola#"+$(this).attr("id");	
						if ($(punto).attr("value")){		
							$(punto).hide();
							$(punto).attr("value",false);
							$(punto).html("");
						}
						else {
							$(".hola").attr("value",false);
							$(".hola").hide();
							$(".hola").html("");
							$(punto).show();
							$(punto).attr("value",true);
							tabla="verdetalles";
							inputString=$(".dato#"+$(this).attr("id")).attr("value");
							elegido="id";
							$.post("php/motorjs.php", {buscar: inputString ,funcion:tabla, modo: elegido}, function(data){
							if(data.length >0) {
								$(punto).html("<td>"+data+"</td>");
							} else {
								$(punto).html("vacio");
							}
							
							});
						}
						return false;
						});	
					$("#autorizar").live("click", function(){
						if($("input#autorizar").attr("checked")) {
				    		$("input#enviarauto").attr("disabled",false);
				  		} else {
				    		$("input#enviarauto").attr("disabled",true);
				  		} 
				  	})
				  	$("#enviarauto").live("click", function(){
				  		//aqui debemos mandar el formulario
				  		$.post("php/autorizacion.php", {idatencion:$("input#sol").attr("value"),accion:"autorizar2"}, function(data){
							alert(data);
						});
				  		//aqui borramos la tupla autorizada
				  		$(this).parent().parent().parent().html("");
				  		$("td#borra+$("input#sol").attr("value")).html("autorizado");
				  		return false;
				  		  	})
				  	$("#corregir").live("click", function(){
				  		$.post("php/motorjs.php",$("#formdet").serialize(), function(data){
				  			alert(data);
				  		})
				  		
				  		return false;
				  	})
				});
				</script>';
				return $jas;
		}
		function defaul(){
			$this->contenido .= $this->javascript().'<h2>Lista de resultados autorizados para imprimir</h2>
	        <table>
	            <thead>
	                <tr><th>No.</th><th>accion</th><th>N&uacute;mero atenci&oacute;n</th><th>Nombre Paciente</th>
	                <th>Fecha de creaci&oacute;n</th></tr>
	            </thead>
	            <tbody>';
	            $this->mostrarautorizados();
	        $this->contenido .= '</tbody>
	        </table>';
		}
		function mostrarautorizados(){
			$listaresultados = $this->selectw('*', 'solicitud', 'autorizado=true and despachado=false and estado=true order by id desc');
        	$no = 0;
        	foreach ($listaresultados as $resultado) {
				$persona=$this->selectw('*','paciente','id='.$resultado['idpaciente']);
	            $this->contenido .= '<tr id="ab'.$resultado['id'].'"><td>' . $no++ . '</td>
				<td id="borra'.$resultado['id'].'">
				'.$this->whoiam('imprimir').'
				<input type="hidden" class="dato" id="b'.$resultado['id'].'" name="idatencion" value="'.$resultado['id'].'">
				<input class="detalles" id="b'.$resultado['id'].'" type="submit" value="Imprimir" /></td>
				<td>' . $resultado['id'] .'</td><td>'.$persona[0]['nombres'].' '.$persona[0]['apellidos'].'</td>
	                <td>' . $resultado['fechacreacion'] .'</td></tr><tr><td colspan=5 class="hola" id="b'.$resultado['id'].'" value="true">
	                </td></tr>';
	        }
		}
		function whoiam($accion)
	    {
	        $ver = isset($_GET["ver"]) ? $_GET["ver"] : '';
	        return '<input type="hidden" name="ver" value="' . $ver .
	            '" ><input type="hidden" name="accion" value="' . $accion . '" >';
	    }
	}
	header('Content-type: text/html; charset=utf-8');
	$autorizar = new primir();
	$autorizar->contenido =
	    '<div id="content" align="center"><div class="contactof">';
	$accion = isset($_GET["accion"]) ? $_GET["accion"] : 'defaul';
	$autorizar->$accion();
	$autorizar->contenido .= '</div><div><br /></div>';
	echo $autorizar->contenido;
?>