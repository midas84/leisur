<?php
session_start();
 $donde = null;
include ('bdlaboratorio.php');
$base = new bdlaboratorio();
//vemos si el usuario y contraseña son válidos
$bandera = isset($_SESSION["autenticado"]) ? $_SESSION["autenticado"] : false;
if (!($bandera)) {
    $pasword = isset($_POST['pasword']) ? $_POST['pasword'] : null;
    $log = isset($_POST['log']) ? $_POST['log'] : null;
    if (($pasword != '') && ($log != '')) {
        $logeado = $base->login($log, $pasword);
        if ($logeado[0]['nombre'] != '') {
            $_SESSION["nombre"] = $logeado['nombre'];
            $_SESSION["autenticado"] = true;
            header("Location: index.php");
        } else {
            $_SESSION["error"] = true;
            $donde = 'inicio';
        }
    } else {
        //$_SESSION["error"]=true;
        $donde = 'inicio';
    }
} else {
    echo $_SESSION["nombre"];
    include ('php/inicio1.php');

    $ver = isset($_GET["ver"]) ? $_GET["ver"] : null;

    if (!($ver == null)) {
        $donde = $ver;
    }
}
if ($donde != null) {
    include ('php/' . $donde . '.php');
}

?>