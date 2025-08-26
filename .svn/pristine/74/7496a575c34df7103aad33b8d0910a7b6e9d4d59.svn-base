<?php
session_start();
$donde = null;
include ('bdlaboratorio.php');
$base = new bdlaboratorio();
$donde = 'inicio';
$ver = isset($_GET['ver']) ? $_GET['ver'] : '';
$_SESSION["autenticado"]=isset($_SESSION["autenticado"]) ? $_SESSION["autenticado"] : false;
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

        $donde = 'inicio';
    }
} else {
    //echo $_SESSION["nombre"];
    //include ('php/inicio1.php');

    $ver = isset($_GET["ver"]) ? $_GET["ver"] : null;

    if (!($ver == null)) {
        $donde = $ver;
    }else {
        $donde='inicio1';
    }
}

?>

<!DOCTYPE html>
<html >
<head>
<script language="JavaScript" src="jsm/fecha.js" type="text/javascript">
</script>

<script type="text/javascript" src="jsm/menuheader.js"></script>



<title>Cell Diagnostic</title>

<link rel="stylesheet" href="css/reset.css" type="text/css" media="all" />
<link rel="stylesheet" href="css/layout.css" type="text/css" media="all" />
<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.22.custom.css" type="text/css" media="all" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js" ></script>
<script type="text/javascript" src="js/jquery-ui-1.8.22.custom.min.js" ></script>
<script src="js/jquery.ui.datepicker-es.js"></script>
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/cufon-replace.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_italic_600.font.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_italic_400.font.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_400.font.js"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="js/ie6_script_other.js"></script>
<script type="text/javascript" src="js/html5.js"></script>
<![endif]-->


</head>
<body id="page1">
<!-- START PAGE SOURCE -->
<div class="body1">
  <div class="main">
    <header>
      <div class="wrapper">
        <h1><div id="logo"></div><span id="slogan">Sucre-Bolivia</span></h1>
        <div class="right">
          <nav>
            <ul id="top_nav">
              
            </ul>
          </nav>
          <nav>
          <div id="fecha" align="right">
            <script language="javascript" type="text/javascript">calendario()</script>
         </div>
          </nav>
        </div>
      </div>
    </header>
  </div>
</div>
<div class="main">
  <div id="banner">
  
<ul id="menu">
            <?php if($_SESSION['autenticado']){
           
			   include ('php/menus.php');
            } ?>
              
             
            </ul>
</div>
<div class="main">
  <section id="content">
        
       
      

 <?php include ('php/' . $donde . '.php'); ?>
      
          

        </section>
      </div>
  
</div>
<div class="body2">
  <div class="main">
    <footer>
      <div class="footerlink">
        <p class="lf"><form name="form_reloj" >
<input type="text" name="reloj" size="10"
style="background-color :#FFFFFF; color :#313439; font-family : Verdana, Arial, Helvetica; font-size : 8pt; text-align : center;" onFocus="window.document.form_reloj.reloj.blur()" >
</form></p>
  <script language="JavaScript">
function mueveReloj(){
    momentoActual = new Date()
    hora = momentoActual.getHours()
    minuto = momentoActual.getMinutes()
    segundo = momentoActual.getSeconds()

    str_segundo = new String (segundo)
    if (str_segundo.length == 1)
       segundo = "0" + segundo

    str_minuto = new String (minuto)
    if (str_minuto.length == 1)
       minuto = "0" + minuto

    str_hora = new String (hora)
    if (str_hora.length == 1)
       hora = "0" + hora

    horaImprimible = hora + " : " + minuto + " : " + segundo

    document.form_reloj.reloj.value = horaImprimible

    setTimeout("mueveReloj()",1000)
}
</script>     
        <div style="clear:both;"></div>
      </div>
    </footer>
  </div>
</div>

<!-- END PAGE SOURCE -->
</body>
</html>
