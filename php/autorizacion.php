<?php
	include ('../bdlaboratorio.php');
	class autorizacion extends bdlaboratorio{
		function autorizar2(){
			$idatencion=isset($_POST['idatencion']) ? $_POST['idatencion'] : '';
        	$this->autorizar($idatencion);
        	return $contenido='Se autorizo correctamente la atención No.'. $idatencion ;
    	}
    	function defaul(){
    		return "error, no se envio accion";
    	}
	}
	$a=new autorizacion();
	$accion=isset($_POST['accion']) ? $_POST['accion'] : 'defaul';
	echo $a->$accion();

?>