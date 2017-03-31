<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
//	------------------------------------
include("../fphp.php");

//	------------------------------------
list($AnioActual, $MesActual, $DiaActual) = preg_split("/[\/.-]+/", substr($Ahora, 0, 10));
$FechaActual = "$AnioActual-$MesActual-$DiaActual";
//	------------------------------------
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="../../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="../../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/fscript.js" charset="utf-8"></script>
</head>

<body>
<!-- ui-dialog -->
<div id="cajaModal"></div>
<div style="overflow:scroll; width:100%; height:250px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="" onclick="order('CodEmpleado')"><a href="javascript:">C&oacute;digo</a></th>
        <th scope="col" width="" onclick="order('NomCompleto')"><a href="javascript:">Nombre Completo</a></th>
    </tr>
    </thead>
    
    <tbody>
	<?php
        if(!isset($limit)) $limit = '';
	$sql = "SELECT *
                FROM pf_riesgos_componentes
                WHERE Estado = 'A' 
                AND IdRiesgo = '$idRiesgo'
                ORDER BY ORDEN";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if(!isset($ventana) or $ventana == '') $ventana = '';
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {

        ?>
            <tr class="trListaBody" onclick='agregaRiesgoComponente(<?php echo $idRiesgo;?>,<?php echo $field['IdComponente']?>,"<?php echo $field['Descripcion']?>")'>
			<td align="center"><?php echo $field['IdComponente']?></td>
			<td><?php echo $field['Descripcion']?></td>
            </tr>
	<?php
        }
	?>
    </tbody>
</table>
</div>
</form>
</body>
</html>