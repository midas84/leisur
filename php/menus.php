<?php

/**
 * @author Miguel Angel
 * @copyright 2012
 */
/*echo '
<li id="menu_active"><a href="index.php?ver=paciente">Paciente</a></li>
<li><a href="index.php?ver=atencion">Atenci�n</a></li>
<li><a href="index.php?ver=resultados">Resultados</a></li>
<li><a href="index.php?ver=primir">Imprimir Resultados</a></li>
<div id="control"><table><tr><td>Usuario:'.$_SESSION['nombre'].' </td></tr><tr><td><a href="index.php?ver=deslogear">Salir</a></td></tr></table></div>
*/
echo'
  <div id="control"> Usuario:'.$_SESSION['nombre'].'<br> <a href="index.php?ver=deslogear">Salir</a></div>
<div id="menu">
<ul class="mi-menu">
  <li><a href="#"> Reg. Info. </a>
    <ul>   
      <li><a href="index.php?ver=paciente2">Adm. paciente </a></li>
      <li><a href="index.php?ver=atencion">Adm. atencion </a></li>
      <li><a href="index.php?ver=admres">Adm. Resultados </a></li>
    </ul>
  </li>
  
  <li><a href="#"> Reportes </a>
    <ul> 
      <li><a href="#">Atendidos</a></li>
      <li><a href="#">Res. Llenos</a></li>
      <li><a href="#">Alguno Mas</a></li>
    </ul>
  </li>
  <li>
    <a href="#"> Herramientas </a>
    <ul>
      <li><a href="index.php?ver=admtiporesultado">Adm. Tipos de Resultado</a></li>
      <li><a href="index.php?ver=admcatanalisis">Adm. Categoria Analisis</a></li>
      <li><a href="index.php?ver=admanalisisespec">Adm. Analisis Especificos</a></li>
    </ul>
  </li>
  
</ul>


</div>
';
?>
