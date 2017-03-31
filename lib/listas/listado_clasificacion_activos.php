<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
//	------------------------------------
include("../fphp.php");
//	------------------------------------
if ($filtrar == "default") {
	$fOrdenar = "CodClasificacion";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fOrdenar != "") { $cOrdenar = "checked"; $Ordenar = "ORDER BY $fOrdenar"; } else $dOrdenar = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (CodClasificacion LIKE '%".$fBuscar."%' OR
				      Descripcion LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
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

<form name="frmentrada" id="frmentrada" action="listado_clasificacion_activos.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<div class="divBorder" style="width:600px;">
<table width="600" class="tblFiltro">
	<tr>
		<td align="right">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
            <input type="text" name="fBuscar" id="fBuscar" style="width:200px;" value="<?=$fBuscar?>" <?=$dBuscar?> />
		</td>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cOrdenar?> onclick="this.checked=!this.checked;" />
            <select name="fOrdenar" id="fOrdenar" style="width:100px;" <?=$dOrdenar?>>
                <?=loadSelectGeneral("ORDENAR-CLASIFICACION-ACTIVO", $fOrdenar, 0)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="600" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:600px; height:235px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th width="75" scope="col">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	</thead>
	<?php
	//	consulto todos
	$sql = "SELECT *
			FROM af_clasificacionactivo
			WHERE Estado = 'A' $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT *
			FROM af_clasificacionactivo
			WHERE Estado = 'A' $filtro
			ORDER BY $fOrdenar
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		if ($ventana == "selListadoLista") {
			?><tr class="trListaBody" onclick="selListadoLista('<?=$seldetalle?>', '<?=$field["CodClasificacion"]?>', '<?=$field["Descripcion"]?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodClasificacion']?>"><?
		}
		else {
			?><tr class="trListaBody" onclick="selListado2('<?=$field['CodClasificacion']?>', '<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodClasificacion']?>"><?
		}
        ?>
			<td><?=$field['CodClasificacion']?></td>
			<td><?=htmlentities($field['Descripcion'])?></td>
			<td align="center"><?=printValoresGeneral("ESTADO", $field['Estado'])?></td>
        </tr>
		<?
	}
	?>
</table>
</div>
<table width="600">
	<tr>
    	<td>
        	Mostrar: 
            <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td align="right">
        	<?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows_total?>));
</script>
</body>
</html>