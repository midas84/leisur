<?php
session_start();
$_SESSION["autenticado"]=false;
$_SESSION["error"]=false;
header("Location: index.php");
exit();
