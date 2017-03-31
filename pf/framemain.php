<?php @session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="../bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
<link href="css1.css" rel="stylesheet" type="text/css" />

</head>
<body style="margin-top:0px; margin-left:0px;">
<!--<div style="position:absolute; top:20%; left:35%;">
<img src="../imagenes/fondo_main.jpg" width="60%" height="60%" />
</div>-->
<div class="col-xs-offset-4 col-xs-4">
	<ul class="list-group">
		<li class="list-group-item"><strong>Funcionario(a):</strong> <?php echo $_SESSION["NOMBRE_USUARIO_ACTUAL"]?></li>
		<li class="list-group-item"><strong>Organismo:</strong> <?php echo $_SESSION["NOMBRE_ORGANISMO_ACTUAL"]?></li>
	</ul>
</div>

<div class="col-xs-offset-2 col-xs-8" id="list_actuaciones_auditores">
	

</div>
</body>
</html>
<script type="text/javascript"src="../js/jquery-ui-1.11.4.custom/external/jquery/jquery.js"></script>
<script type="text/javascript"src="../js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<script type="text/javascript" language="javascript" src="../fscript.js"></script>
<script type="text/javascript" language="javascript" src="../bootstrap/js/bootstrap.min.js"></script>
<script>
	$(document).ready(function(){
		
		$.ajax({
		  method: "POST",
		  url: "pf_lista_actuaciones_auditor.php",
		  data: { action: "lista_actuaciones_auditor" }
		}).done(function( html ) {
			$("#list_actuaciones_auditores").append(html);
		});

	});

</script>