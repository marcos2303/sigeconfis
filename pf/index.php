<?php
session_start();
session_name("SIACEDA");
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<html>
<head>
<meta charset="utf-8">
<link href="../imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />
<link type="text/css" rel="stylesheet" href="../css/estilo.css" charset="utf-8" />
<title>M&oacute;dulo de Planificaci&oacute;n Fiscal | <?=$_SESSION['NOMBRE_USUARIO_ACTUAL']?></title>
<script language='JavaScript' type='text/JavaScript' src='fscript.js'></script>
</head>
<!--<frameset id='frmSet' frameborder='no' border='0' rows='75px, *'>
	<frame noresize scrolling='no'  src='frametop.php'></frame>
	<frame noresize scrolling='no'  src='framemain.php'></frame>
	<frame noresize scrolling='no'  src='menu.php'></frame>
</frameset>-->
<table border='0' width='100%'>
	<tr>

		<td style="background-color: #630000;" align="center"><img src="../imagenes/header-gif.gif" width="70%" height="100" /></td>
	</tr>
	<tr style="background-color: #CCCCCC;">
		<td><?php include('menu.php')?></td>
	</tr>
	<tr>
		<td><iframe noresize scrolling='no'  src='framemain.php' name="main" id="main" width="100%" height="800" frameborder="0"></iframe></td>
	</tr>
	<tr>
		<td><iframe noresize scrolling='no'  src='framebottom.php' width="100%" height="100" frameborder="0"></iframe></td>
	</tr>	
</table>

</html>