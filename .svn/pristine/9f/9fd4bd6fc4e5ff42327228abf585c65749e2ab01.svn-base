<?php
class bdlaboratorio{
	public $host; // para conectarnos a localhost o el ip del servidor de postgres
    public $db; // seleccionar la base de datos que vamos a utilizar
    public $user; // seleccionar el usuario con el que nos vamos a conectar
    public $pass; // la clave del usuario
    public $conexion;  //donde se guardara la conexión
    public $url; //dirección de la conexión que se usara para destruirla mas adelante
    function __construct(){
	    $this->host='localhost';
        $this->db='labo';
        $this->user='root';
        $this->pass='';
    }
	 //creación de la función para cargar los valores de la conexión.
    //función que se utilizara al momento de hacer la instancia de la clase
    function mysql_fetch_all($res) { 
		$data = array(); 
		while ($row = mysql_fetch_array($res)) 
			$data[] = $row; 
		return $data; 	
	}	 
	function _conectar(){
        $this->url=mysql_connect($this->host,$this->user,$this->pass);
		mysql_select_db($this->db, $this->url);
        return true;
    }
    //función para destruir la conexión.
    function destruir(){
        mysql_close($this->url);
    }
	function _consulta($sql){
		if ($this->_conectar()){
			$result = mysql_query($sql);
		}
		else {
			$result=false;
		}
		return $result;
	}
	function select($select, $from){
		$sql='select '.$select.' from '.$from;
		$rs=$this->_consulta($sql);
		$row = $this->mysql_fetch_all($rs);
		return $row;
	}
	function selectw($select, $from, $where){
		$sql='select '.$select.' from '.$from.' where '.$where;
		$rs=$this->_consulta($sql);
		$row= $this->mysql_fetch_all($rs);
		return $row;
	}
	function selectw1($select, $from, $where){
		$sql='select '.$select.' from '.$from.' where '.$where;
		return $sql;
	}
	function update($update, $set, $where){		
		$sql = 'UPDATE '.$update.' SET '.$set.', fechamodificacion=now() WHERE '.$where;
		$rs=$this->_consulta($sql);
		$my_error = mysql_error();
		if(!empty($my_error)) { 
				return false;
			} else{
				return true;
			}
	}
	function insert($into,$campos,$values){
			$sql="insert into ".$into." (".$campos.") values (".$values.")";
			$rs=$this->_consulta($sql);
			$my_error = mysql_error();
			if(!empty($my_error)) { 
				return false;
			} else{
				return true;
			}
	}
	function insert1($into,$campos,$values){
			return $sql="insert into ".$into." (".$campos.") values (".$values.")";
			
	}
	function login($login,$pass){
		$sql="select personal.nombres nombre, personal.idseguridad idseguridad from personal inner join seguridad on personal.idseguridad=seguridad.id where (personal.usuario='".$login."')and(personal.clave='".$pass."')and(personal.estado=true)";
		$rs=$this->_consulta($sql);
		$row = mysql_fetch_array($rs);
		return $row;
		//$row = mysql_fetch_array($rs);
		//return $row;
	}
	
	function buscarpaciente($nombre, $apellido){
	if ($this->selectw('*','paciente',"(nombres='".$nombre."')and(apellidos='".$apellido."')")==null){ 
			return true;
		}
		else {
			return false;
		}
	}
	 
	function nuevopaciente($ci, $nombre, $apellido, $fechanac, $procedencia,$direccion,$sex, $telefono, $celular, $email, $gruposangre, $factorrh, $observaciones, $seguro, $peso){
		$ed=$this->calcular_edad($fechanac);
		$into='paciente';
		$campos='peso,edad, ci, nombres, apellidos, fechanacimiento,procedencia,direccion,sexo,telefono,celular,email,gruposanguineo,factorrh,observacion,seguro';
		$values= $peso.",".$ed.",'".$ci."','".$nombre."','".$apellido."','".$fechanac."','".$procedencia."','".$direccion."','".$sex."','".$telefono."','".$celular."','".$email."','".$gruposangre."','".$factorrh."','".$observaciones."','".$seguro."'";
		return $this->insert($into,$campos,$values);
		//
	}
	
	function listapacientes (){
		return $this->selectw('id, ci, nombres, apellidos','paciente','estado=true');
	}
	function ultimopaciente(){
		return $this->select('MAX( id ) max','paciente');
	}
	function llenareditarpaciente ($id){
		return $this->selectw('*','paciente','id='.$id);
	}
	function calcular_edad($fecha){
		$dias = explode("-", $fecha, 3);
		$dias = mktime(0,0,0,$dias[1],$dias[2],$dias[0]);
		$edad = (int)((time()-$dias)/31556926 );
		return $edad;
	}
	function editarpaciente($id, $ci, $nombre, $apellido, $fechanac, $procedencia,$direccion,$sex, $telefono, $celular, $email, $gruposangre, $factorrh, $observaciones, $seguro, $peso){
		$ed=$this->calcular_edad($fechanac);
		$update='paciente';
		$set="peso=".$peso.",edad=".$ed.", ci='".$ci."', nombres='".$nombre."', apellidos='".$apellido."', fechanacimiento='".$fechanac."',procedencia='".$procedencia."',direccion='".$direccion."',sexo='".$sex."',telefono='".$telefono."',celular='".$celular."',email='".$email."',gruposanguineo='".$gruposangre."',factorrh='".$factorrh."',observacion='".$observaciones."',seguro='".$seguro."'";
		$where="id='".$id."'";
		return $this->update($update, $set, $where);		
	}
//formulario atencion
	function buscarpaciente2($nombre,$apellido){
		$res=$this->selectw('id','paciente', "(nombres='".$nombre."')and(apellidos='".$apellido."')",'(estado=true)');
			if($res[0][id]==null){
				$r='';
			}
			else {
				$r=$res[0][id];
			}
		return $r;
	}
	function llenardatospaciente($id){
		return $this->selectw('*','paciente', '(id='.$id.')and(estado=true)');
	}
	function listapaciente(){
		return $this->selectw('id "data", id label','paciente', 'estado=true');
	}
	function listadoctor(){
		return $this->selectw('id "data", nombre label','doctor', 'estado=true');
	}
	
	function listaanalisis(){
		return $this->selectw('analisisespecifico.id id, analisisespecifico.nombre nombrea, categoriaanalisis.nombre nombrec','analisisespecifico inner join categoriaanalisis on analisisespecifico.idcategoria=categoriaanalisis.id', 'analisisespecifico.estado=true order by categoriaanalisis.nombre');
	}
	function listadoctor2(){
		return $this->selectw('id "data", nombre label','doctor', 'estado=true');
	}
	
	function agregarsolicitud($iddoctor, $idpaciente,$diagnostico, $precio){
		$res=$this->selectw('max(atenciondia) max','solicitud',"fechacreacion='".strftime( "%Y-%m-%d", time() )."'");
		if ($res[0]['max']!=''){
			$idsolicitud=$res[0]['max']+1;
		}
		else {
			$idsolicitud=10000;
		}
		$into='solicitud';
		$campos='atenciondia, fechacreacion, iddoctor, idpaciente, diagnostico, preciototalestimado';
		$values= $idsolicitud.",now(),".$iddoctor.",".$idpaciente.",'".$diagnostico."',".$precio;
		$this->insert($into,$campos,$values);
		return $this->selectw('MAX( id ) max','solicitud',"estado=true");
	}
	function agregarmuestra($idsolicitud, $muestra){
		//$re=$this->selectw('id','tipomuestra',"(nombre='".$muestra."') and (estado=true)");
		//echo $muestra.'??????????????????????????????';
		$into='muestra';
		$campos='idsolicitud,idtipomuestra';
		$values= $idsolicitud.",'".$muestra."'";
		return $this->insert($into,$campos,$values);
	}
	function precio ($analisis){
		return $this->selectw('precio','analisisespecifico', '('.$analisis.'=id) and (estado=true)');
	}
	function nuevodoctor($nombredoctor){
		$into='doctor';
		$campos='nombre';
		$values= "'".$nombredoctor."'";
		$this->insert($into,$campos,$values);
		return $this->listadoctor();
	}
	function checkanalisis(){
		return $this->selectw('analisisespecifico.id id, analisisespecifico.precio precio, analisisespecifico.nombre nombrea, categoriaanalisis.nombre nombrec, analisisespecifico.idtipomuestra idtipomuestra, tipomuestra.nombre muestra','analisisespecifico INNER JOIN categoriaanalisis ON analisisespecifico.idcategoria = categoriaanalisis.id inner join tipomuestra on analisisespecifico.idtipomuestra=tipomuestra.id', 'analisisespecifico.estado = true');
	}
	function agregaratencionanalisis($idsolicitud,$analisis){
		$into='atencion';
		$campos='idsolicitud,  idanalisis';
		$values=$idsolicitud.",".$analisis; 
		return $this->insert($into,$campos,$values);
		
	}
	function  agregarpago($idsolicitud,$pago){
		$into='pago';
		$campos='idsolicitud, monto';
		$values=$idsolicitud.",".$pago; 
		return $this->insert($into,$campos,$values);	
	}
	function agregaratencion($doctor, $pacientes,$diagnostico, $muestras, $analisis, $pago, $precio){
		$idsolicitud=$this->agregarsolicitud($doctor,$pacientes,$diagnostico, $precio);
		foreach ($analisis as $i){
		//	if ($i['seleccionado']){
				$this->agregaratencionanalisis($idsolicitud[0]['max'],$i);
				$aten=$this->select('MAX( id ) max, idanalisis','atencion');
				$resultados=$this->selectw('id','tiporesultado','idanalisis='.$aten[0]['idanalisis']);
				foreach ($resultados as $m){
					$this->insert('resultados','idatencion, idtiporesultado',$aten[0]['max'].','.$m['id']);
				}
		//	}

		}
		foreach ($muestras as $o){
			if ($o!=''){
				$this->agregarmuestra($idsolicitud[0]['max'], $o);
			}
		}
		if ($pago!=0){
				$this->agregarpago($idsolicitud[0]['max'],$pago);
		}
		//return $res;
	}
	function verificaratencion($doctor, $pacientes){
	if ($this->selectw('*','solicitud',"(idpaciente='".$paciente."')and(iddoctor='".$doctor."') and (estado=true)")==null){ 
			return true;
		}
		else {
			return false;
		}
	}
	function ultimasolicitud(){
		$res=$this->select('MAX( id ) max','solicitud');
		
		return $res[0]['max']; 
	}
	function todosolicitud($idsol){
		return $this->selectw('doctor.nombre doctor, paciente.nombres paciente, solicitud.fechacreacion fecha, solicitud.atenciondia numerodia, solicitud.diagnostico diagnostico, analisisespecifico.nombre analisis, solicitud.preciototalestimado precio','solicitud inner join doctor on solicitud.iddoctor=doctor.id inner join paciente on paciente.id=solicitud.idpaciente inner join atencion on atencion.idsolicitud=solicitud.id inner join analisisespecifico on atencion.idanalisis=analisisespecifico.id',"(solicitud.id=".$idsol.") and (solicitud.estado=true)");
	}
	//formulario categoria
	function listacategoria (){
			return $this->selectw('id, descripcion, nombre','categoriaanalisis','estado=true');
		}
	function verificarcategoria($nombre, $descripcion){
		if ($this->selectw('*','categoriaanalisis',"((nombre='".$nombre."')and(descripcion='".$descripcion."') and (estado=true))")==null){ 
				return true;
			}
			else {
				return false;
			}
		}
	function nuevacategoria($nombre, $descripcion){
		$into='categoriaanalisis';
		$campos='nombre, descripcion';
		$values="'".$nombre."','".$descripcion."'"; 
		$this->insert($into,$campos,$values);
		}
	function listacategoria1(){
		return $this->selectw('id "data", nombre label','categoriaanalisis', 'estado=true');
	}	
	function listaanalisis1(){
		return $this->selectw('atencion.idsolicitud idsolicitud, atencion.fechasolicitud fechasolicitud, analisisespecifico.nombre nombre, atencion.idanalisis idanalisis','atencion inner join analisisespecifico on atencion.idanalisis=analisisespecifico.id','atencion.estado=true');
	}
	function combosolicitud(){
	 $res=$this->selectw('id, fechacreacion','solicitud','estado=true');
		$a=0;
		foreach ($res as $i){
			$resultado[$a]=$i['id'].'/'.$i['fechacreacion'];
			$a++;
		}
		return $resultado;
	 }
	function llenarresultados($solicitud){
		$sol= explode("/",$solicitud);
		return $this->selectw("resultados.id resultadosid, tiporesultado.nombre nombreresultado, analisisespecifico.nombre nombreanalisis, tiporesultado.unidadmedicion unidadmedicion, resultados.valor valor","atencion inner join tiporesultado on atencion.idanalisis=tiporesultado.idanalisis inner join analisisespecifico on analisisespecifico.id=atencion.idanalisis inner join resultados on resultados.idtiporesultado=tiporesultado.id ","((atencion.idsolicitud=".$sol[0].") and (atencion.fechasolicitud='".$sol[1]."') and (atencion.estado=true))"); 
	}
	function nuevoresultado($resultadoa,$solicitud){
		$sol= explode("/",$solicitud);
		$idatencion=$this->selectw('id','atencion',"idsolicitud=".$sol[0]." and fechasolicitud='".$sol[1]."'");
		foreach ($resultadoa as $i){
			$this->update('resultados',"valor='".$i['valor']."'", 'idatencion='.$idatencion[0]['id'].' and id='.$i['resultadosid']);	
		}
		return $idatencion;//'echo';	
	}
	function resultados($idatencion){
		return $this->selectw('*','resultados innerjoin atencion on resultados.idatencion=atencion.id innerjoin analisisespecifico on atencion.analisis=analisisespecifico.id','id='.$idatencion);
	}

}
?>