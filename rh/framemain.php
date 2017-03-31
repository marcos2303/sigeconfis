<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("../lib/fphp.php");
include("lib/fphp.php");
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/estilo.css" charset="utf-8" />
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.formatCurrency-1.4.0.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.formatCurrency.all.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
<script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="js/fscript.js" charset="utf-8"></script>
<script type="text/javascript" src="js/form.js" charset="utf-8"></script>
</head>

<body style="margin-top:0px; margin-left:0px;">
<!-- ui-dialog -->
<div id="cajaModal"></div>

<?php
if ($_SESSION["PRIMERA_VEZ"]) {
	$_SESSION["PRIMERA_VEZ"] = false;
	list ($Ejecutar) = opcionesPermisos('', '01-0032');
	if ($Ejecutar == "S") avisoPeriodosVacacionales();
}
?>
<div style="position:absolute; top:20%; left:35%;">
<img src="../imagenes/fondo_main.jpg" width="60%" height="60%" />
</div>
</body>
</html>