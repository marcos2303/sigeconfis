<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_sia.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_sia.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Centros de Costos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="centros_costos.php" method="POST">
<table width="800" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
		<td align="right">
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'centros_costos_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'centros_costos_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'centros_costos_ver.php', 'BLANK', 'height=450, width=750, left=200, top=200, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro(this.form, document.getElementById('registro').value, 'CENTROS-COSTOS', 'ELIMINAR');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'centros_costos_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="800" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">Centro</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="175" scope="col">Grupo</th>
		<th width="175" scope="col">Sub-Grupo</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php
	$filtro = trim($filtro); 
	if ($filtro != "") $filtro = "WHERE (mcc.CodCentroCosto LIKE '%".$filtro."%' OR mcc.Descripcion LIKE '%".$filtro."%' OR sgcc.Descripcion LIKE '%".$filtro."%' OR gcc.Descripcion LIKE '%".$filtro."%')"; 
	else $filtro = "";
	//	CONSULTO LA TABLA
	$sql = "SELECT
				mcc.*,
				sgcc.Descripcion AS NomSubGrupo,
				gcc.Descripcion AS NomGrupo
			FROM
				ac_mastcentrocosto mcc
				INNER JOIN ac_subgrupocentrocosto sgcc ON (mcc.CodSubGrupoCentroCosto = sgcc.CodSubGrupoCentroCosto AND mcc.CodGrupoCentroCosto = sgcc.CodGrupoCentroCosto)
				INNER JOIN ac_grupocentrocosto gcc ON (mcc.CodGrupoCentroCosto = gcc.CodGrupoCentroCosto)
			$filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "A") $status = "Activo"; else $status = "Inactivo";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodCentroCosto']?>">
			<td align="center"><?=$field['CodCentroCosto']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td><?=($field['NomGrupo'])?></td>
			<td><?=($field['NomSubGrupo'])?></td>
			<td align="center"><?=($status)?></td>
		</tr>
		<?
	}
	?>
	<script type="text/javascript" language="javascript">
		totalRegistros(<?=intval($rows)?>, "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
	</script>
</table>
</form>
</body>
</html>