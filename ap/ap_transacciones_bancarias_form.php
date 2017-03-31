<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$AnioActual-$MesActual-$DiaActual";
$PeriodoActual = "$AnioActual-$MesActual";
$AnioTransaccion = "$AnioActual";
if ($opcion == "nuevo") {
	$field['Estado'] = "PR";
	$field['PeriodoContable'] = $PeriodoActual;
	$field['FechaTransaccion'] = $FechaActual;
	$field['PreparadoPor'] = $_SESSION["CODPERSONA_ACTUAL"];
	$field['NomPreparadoPor'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$field['FechaPreparacion'] = $FechaActual;
	//	presupuesto
	$sql = "SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$AnioTransaccion."' AND Organismo = '".$fCodOrganismo."'";
	$query_presupuesto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_presupuesto)) $field_presupuesto = mysql_fetch_array($query_presupuesto);
	$field['CodPresupuesto'] = $field_presupuesto['CodPresupuesto'];
	##
	$titulo = "Nueva Transacci&oacute;n";
	$accion = "nuevo";
	$label_submit = "Guardar";
	$disabled_partida = "";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
	$focus = "Comentarios";
}
elseif ($opcion == "modificar" || $opcion == "ver" || $opcion == "actualizar" || $opcion == "desactualizar") {
	list($NroTransaccion, $Secuencia) = split("[_]", $sel_registros);
	//	consulto datos generales
	$sql = "SELECT
				bt.*,
				p.NomCompleto AS NomPreparadoPor
			FROM
				ap_bancotransaccion bt
				INNER JOIN mastpersonas p ON (p.CodPersona = bt.PreparadoPor)
			WHERE NroTransaccion = '".$NroTransaccion."'
			GROUP BY NroTransaccion";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$label_submit = "Modificar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
		$focus = "Comentarios";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_transacciones = "disabled";
		$display_ver = "display:none;";
		$display_submit = "display:none;";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	elseif ($opcion == "actualizar") {
		$accion = "actualizar";
		$titulo = "Actualizar Transacci&oacute;n";
		$label_submit = "Actualizar";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_transacciones = "disabled";
		$display_ver = "display:none;";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	elseif ($opcion == "desactualizar") {
		$accion = "desactualizar";
		$titulo = "Desactualizar Transacci&oacute;n";
		$label_submit = "Desactualizar";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_transacciones = "disabled";
		$display_ver = "display:none;";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	list($AnioTransaccion, $MesTransaccion, $DiaTransaccion) = split("[/.-]", $field['PeriodoContable']);
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$clkCancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_transacciones_bancarias_lista" method="POST" onsubmit="return transacciones_bancarias(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fFechaTransaccionD" id="fFechaTransaccionD" value="<?=$fFechaTransaccionD?>" />
<input type="hidden" name="fFechaTransaccionH" id="fFechaTransaccionH" value="<?=$fFechaTransaccionH?>" />
<input type="hidden" name="fCodTipoTransaccion" id="fCodTipoTransaccion" value="<?=$fCodTipoTransaccion?>" />
<input type="hidden" name="fCodTipoDocumento" id="fCodTipoDocumento" value="<?=$fCodTipoDocumento?>" />
<input type="hidden" name="fCodBanco" id="fCodBanco" value="<?=$fCodBanco?>" />
<input type="hidden" name="fNroCuenta" id="fNroCuenta" value="<?=$fNroCuenta?>" />
<input type="hidden" name="Anio" id="Anio" value="<?=$AnioTransaccion?>" />
<input type="hidden" name="CodPresupuesto" id="CodPresupuesto" value="<?=$field['CodPresupuesto']?>" />
<input type="hidden" name="imprimir" id="imprimir" />

<table width="900" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Informacion de la Transacci&oacute;n</td>
    </tr>
	<tr>
		<td class="tagForm">* Organismo</td>
		<td>
			<select id="CodOrganismo" style="width:300px;" <?=$disabled_ver?>>
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td class="tagForm"># Transacci&oacute;n:</td>
		<td>
            <input type="text" id="NroTransaccion" style="width:75px;" class="codigo" value="<?=$field['NroTransaccion']?>" disabled />
		</td>
    </tr>
	<tr>
		<td class="tagForm">Periodo:</td>
		<td>
            <input type="text" id="PeriodoContable" style="width:60px;" class="codigo" value="<?=$field['PeriodoContable']?>" disabled />
		</td>
		<td class="tagForm">Estado:</td>
		<td>
            <input type="hidden" id="Estado" value="<?=$field['Estado']?>" />
            <input type="text" style="width:75px;" class="codigo" value="<?=strtoupper(printValores("ESTADO-TRANSACCION-BANCARIA", $field['Estado']))?>" disabled />
		</td>
    </tr>
    <tr>
		<td class="tagForm">* Fecha:</td>
		<td colspan="3">
        	<input type="text" id="FechaTransaccion" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaTransaccion'])?>" onkeyup="setFechaDMA(this)" onchange="setPeriodoFromFecha($(this).val(), $('#PeriodoContable')); setPresupuesto($('#CodOrganismo').val(), $(this).val(), $('#CodPresupuesto'), $('#Anio'));" <?=$disabled_ver?> />
		</td>
    </tr>
	<tr>
		<td class="tagForm">Comentarios:</td>
		<td colspan="3"><textarea id="Comentarios" style="width:95%; height:45px;" <?=$disabled_ver?>><?=htmlentities($field['Comentarios'])?></textarea></td>
	</tr>
    <tr>
        <td class="tagForm">Preparado Por:</td>
        <td colspan="3">
            <input type="hidden" id="PreparadoPor" value="<?=$field['PreparadoPor']?>" />
            <input type="text" id="NomPreparadoPor" value="<?=htmlentities($field['NomPreparadoPor'])?>" style="width:245px;" disabled="disabled" />
            <input type="text" id="FechaPreparacion" value="<?=formatFechaDMA($field['FechaPreparacion'])?>" style="width:60px;" disabled="disabled" />
        </td>
    </tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td colspan="3">
			<input type="checkbox" id="FlagPresupuesto" value="A" <?=chkOpt($field['FlagPresupuesto'], "S")?> onclick="flagPresupuesto(this.checked)" <?=$disabled_ver?> /> Afecta Presupuesto
		</td>
	</tr>
</table>
<center>
<input type="submit" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:75px;" onclick="<?=$clkCancelar?>" />
</center>
</form>
<div style="width:900px" class="divMsj">Campos Obligatorios *</div>
<br />

<center>
<form name="frm_transacciones" id="frm_transacciones">
<input type="hidden" id="sel_transacciones" />
<table width="900" class="tblBotones">
	<thead>
    	<th class="divFormCaption" colspan="2">Transacciones Bancarias</th>
    </thead>
    <tbody>
    <tr>
    	<td class="gallery clearfix">
            <a id="aSelProveedor" href="../lib/listas/listado_personas.php?filtrar=default&cod=CodProveedor&ventana=selListadoLista&seldetalle=sel_transacciones&iframe=true&width=925&height=500" rel="prettyPhoto[iframe1]" style="display:none;"></a>
            <input type="button" class="btLista" id="btSelProveedor" value="Sel. Persona" onclick="validarAbrirLista('sel_transacciones', 'aSelProveedor');" <?=$disabled_ver?> />
            
            <a id="aSelCCosto" href="../lib/listas/listado_centro_costos.php?filtrar=default&cod=CodCentroCosto&ventana=selListadoLista&seldetalle=sel_transacciones&iframe=true&width=850&height=500" rel="prettyPhoto[iframe2]" style="display:none;"></a>
            <input type="button" class="btLista" id="btSelCCosto" value="Sel. C.Costo" onclick="validarAbrirLista('sel_transacciones', 'aSelCCosto');" <?=$disabled_ver?> />
            
			<a id="aSelPartida" href="pagina.php?iframe=true" rel="prettyPhoto[iframe3]" style="display:none;"></a>
            <input type="button" class="btLista btpartida" id="btSelPartida" value="Sel. Partida" onclick="listado_clasificador_presupuestario_disponible_abrir();" <?=$disabled_ver?> />
        </td>
        
        <td align="right" class="gallery clearfix">
            <a id="a_transacciones" href="../lib/listas/listado_tipo_transaccion_bancaria.php?filtrar=default&ventana=transacciones_bancarias_tipo_insertar&detalle=transacciones&iframe=true&width=925&height=500" rel="prettyPhoto[iframe4]" style="display:none;"></a>
            <input type="button" class="btLista" value="Insertar" onclick="$('#a_transacciones').click();" <?=$disabled_ver?> />
            <input type="button" class="btLista" value="Borrar" onclick="quitar(this, 'transacciones');" <?=$disabled_ver?> />
        </td>
    </tr>
    </tbody>
</table>
<div style="overflow:scroll; width:900px; height:250px;">
<table width="1200" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">&nbsp;</th>
        <th scope="col" colspan="2" align="left">Tipo de Transacci&oacute;n</th>
        <th scope="col" width="15">I/E</th>
        <th scope="col" width="125">Cta. Bancaria</th>
        <th scope="col" width="100" align="right">Monto</th>
        <th scope="col" width="200">Documento</th>
        <th scope="col" width="150">Doc. Referencia</th>
        <th scope="col" width="50">Persona</th>
        <th scope="col" width="50">C.C.</th>
        <th scope="col" width="100">Partida</th>
    </tr>
    </thead>
    
    <tbody id="lista_transacciones">
    <?php
	$sql = "SELECT
				bt.*,
				btt.Descripcion AS NomTipoTransaccion
			FROM
				ap_bancotransaccion bt
				INNER JOIN ap_bancotipotransaccion btt ON (btt.CodTipoTransaccion = bt.CodTipoTransaccion)
			WHERE NroTransaccion = '".$NroTransaccion."'
			ORDER BY Secuencia";
	$query_transacciones = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_transacciones = mysql_fetch_array($query_transacciones)) {	++$nro_transacciones;
		$id = $nro_transacciones;
		?>
        <tr class="trListaBody" onclick="clk($(this), 'transacciones', 'transacciones_<?=$id?>');" id="transacciones_<?=$id?>">
			<th>
				<input type="hidden" name="Secuencia" value="<?=$field_transacciones['Secuencia']?>" />
				<?=$nro_transacciones?>
			</th>
			<td align="center" width="35">
				<input type="text" name="CodTipoTransaccion" class="cell2" value="<?=$field_transacciones['CodTipoTransaccion']?>" readonly />
			</td>
			<td align="center">
				<input type="text" name="NomTipoTransaccion" class="cell2" value="<?=htmlentities($field_transacciones['NomTipoTransaccion'])?>" readonly />
			</td>
			<td align="center">
				<input type="text" name="TipoTransaccion" class="cell2" value="<?=$field_transacciones['TipoTransaccion']?>" readonly />
			</td>
			<td align="center">
                <select name="NroCuenta" class="cell" <?=$disabled_transacciones?>>
                    <option value="">&nbsp;</option>
                    <?=loadSelect("ap_ctabancaria", "NroCuenta", "NroCuenta", $field_transacciones['NroCuenta'], 0)?>
                </select>
			</td>
			<td align="center">
                <input type="text" name="Monto" class="cell" style="text-align:right;" value="<?=number_format($field_transacciones['Monto'], 2, ',', '.')?>" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" <?=$disabled_transacciones?> />
			</td>
			<td align="center">
                <select name="CodTipoDocumento" class="cell" <?=$disabled_transacciones?>>
                    <option value="">&nbsp;</option>
                    <?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", $field_transacciones['CodTipoDocumento'], 10)?>
                </select>
			</td>
			<td align="center">
                <input type="text" name="CodigoReferenciaBanco" class="cell" value="<?=$field_transacciones['CodigoReferenciaBanco']?>" <?=$disabled_transacciones?> />
			</td>
			<td align="center">
                <input type="text" name="CodProveedor" id="CodProveedor_<?=$id?>" class="cell" style="text-align:center;" value="<?=$field_transacciones['CodProveedor']?>" maxlength="6" <?=$disabled_transacciones?> />
			</td>
			<td align="center">
                <input type="text" name="CodCentroCosto" id="CodCentroCosto_<?=$id?>" class="cell" style="text-align:center;" value="<?=$field_transacciones['CodCentroCosto']?>" maxlength="4" <?=$disabled_transacciones?> />
			</td>
			<td align="center">
                <input type="text" name="CodPartida" id="CodPartida_<?=$id?>" class="cell partida" style="text-align:center;" value="<?=$field_transacciones['CodPartida']?>" maxlength="12" <?=$disabled_transacciones?> />
			</td>
		</tr>
		<?
	}
    ?>
    </tbody>
</table>
</div>
<input type="hidden" id="nro_transacciones" value="<?=$nro_transacciones?>" />
<input type="hidden" id="can_transacciones" value="<?=$nro_transacciones?>" />
</form>
</center>