<?php
if ($opcion == "nuevo") {
	$field['Estado'] = "A";
	##
	$titulo = "Nuevo Registro";
	$accion = "nuevo";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
	$disabled_voucher = "disabled";
	$visible_voucher = "visibility:hidden;";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
	$focus = "CodTipoTransaccion";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales
	$sql = "SELECT *
			FROM ap_bancotipotransaccion
			WHERE CodTipoTransaccion = '".$sel_registros."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$display_ver = "display:none;";
		if ($field['FlagVoucher'] != "S") { $disabled_voucher = "disabled"; $visible_voucher = "visibility:hidden;"; }
		if ($field['FlagTransaccion'] == "S") $disabled_transaccion = "disabled";
		$label_submit = "Modificar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
		$focus = "Descripcion";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_voucher = "disabled";
		$disabled_transaccion = "disabled";
		$display_submit = "display:none;";
		$visible_voucher = "visibility:hidden;";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$clkCancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_tipo_transaccion_bancaria_lista" method="POST" enctype="multipart/form-data" onsubmit="return tipo_transaccion_bancaria(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fCodVoucher" id="fCodVoucher" value="<?=$fCodVoucher?>" />
<input type="hidden" name="fTipoTransaccion" id="fTipoTransaccion" value="<?=$fTipoTransaccion?>" />

<table width="600" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos del Registro</td>
    </tr>
	<tr>
		<td class="tagForm" width="150">* C&oacute;digo:</td>
		<td>
            <input type="text" id="CodTipoTransaccion" style="width:93px;" class="codigo" maxlength="4" value="<?=$field['CodTipoTransaccion']?>" <?=$disabled_modificar?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
            <input type="text" id="Descripcion" style="width:90%;" maxlength="50" value="<?=htmlentities($field['Descripcion'])?>" <?=$disabled_ver?> />
		</td>
    </tr>
	<tr>
		<td class="tagForm">* Tipo:</td>
		<td>
			<select id="TipoTransaccion" style="width:100px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=loadSelectValores("TIPO-TRANSACCION-BANCARIA", $field['TipoTransaccion'], 0)?>
			</select>
		</td>
    </tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td>
			<input type="checkbox" id="FlagVoucher" <?=chkOpt($field['FlagVoucher'], "S")?> onclick="setFlagVoucher(this.checked);" <?=$disabled_ver?> /> Genera Voucher
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Tipo de Voucher:</td>
		<td>
			<select id="CodVoucher" style="width:250px;" <?=$disabled_voucher?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("ac_voucher", "CodVoucher", "Descripcion", $field['CodVoucher'], 10)?>
			</select>
		</td>
    </tr>
	<tr>
		<td class="tagForm">* Cta. Contable:</td>
		<td class="gallery clearfix">
			<input type="text" id="CodCuenta" style="width:93px;" value="<?=$field['CodCuenta']?>" disabled="disabled" />
            <a id="a_CodCuenta" href="../lib/listas/listado_plan_cuentas.php?filtrar=default&cod=CodCuenta&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$visible_voucher?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
    </tr>
	<tr>
		<td class="tagForm">* Cta. Contable (Pub.20):</td>
		<td class="gallery clearfix">
			<input type="text" id="CodCuentaPub20" style="width:93px;" value="<?=$field['CodCuentaPub20']?>" disabled="disabled" />
            <a id="a_CodCuentaPub20" href="../lib/listas/listado_plan_cuentas_pub20.php?filtrar=default&cod=CodCuentaPub20&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" style=" <?=$visible_voucher?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
    </tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td>
			<input type="checkbox" id="FlagTransaccion" value="A" <?=chkOpt($field['FlagTransaccion'], "S")?> <?=$disabled_transaccion?> /> Transacci&oacute;n del Sistema
		</td>
	</tr>
    <tr>
		<td class="tagForm">Estado:</td>
		<td colspan="3">
			<input type="radio" name="Estado" id="Activo" value="A" <?=chkOpt($field['Estado'], "A")?> <?=$disabled_nuevo?> /> Activo
            &nbsp; &nbsp; 
            <input type="radio" name="Estado" id="Inactivo" value="I" <?=chkOpt($field['Estado'], "I");?> <?=$disabled_nuevo?> /> Inactivo
		</td>
	</tr>
    <tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input type="text" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:75px;" onclick="<?=$clkCancelar?>" />
</center>
</form>
<div style="width:600px" class="divMsj">Campos Obligatorios *</div>