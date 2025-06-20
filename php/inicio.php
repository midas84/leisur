 <div id="content" align="center">
<?php

$errorlogin=isset($_SESSION["error"]) ? $_SESSION["error"] : false ;

if ($errorlogin){
echo '<font color="red"><b>Datos incorrectos</b></font>';
}else{
echo '';
}

echo '
<form action="index.php" method="POST" class="contacto" >
<table  width="150">
<tr><td height="35"><label>Usuario:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td><td><input name="log"  value="" /></td></tr>
<tr><td height="35"><label>Contrase&ntilde;a:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td><td><input name="pasword" type="password"/></td></tr>
<tr><td height="35"> </td><td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Iniciar sesi&oacute;n" /></td></tr>
</table>
</form>
';
?>
<br /><br /><br />
</div>
