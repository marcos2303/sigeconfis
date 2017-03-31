<?php
include("../../lib/fphp.php");
include("fphp.php");
//	--------------------------

//	--------------------------

//	inserto linea en la lista de distribucion en la obligacion
if ($accion == "obligacion_distribucion_insertar") {
	if (!afectaTipoServicio($CodTipoServicio)) { $cFlagNoAfectoIGV = "checked"; $dFlagNoAfectoIGV = "disabled"; }
	if ($FlagPresupuesto == "checked") $disabled_presupuesto = ""; else $disabled_presupuesto = "disabled";
	?>
    <th><?=$nrodetalle?></th>
    <td align="center" width="75">
        <input type="text" name="cod_partida" id="cod_partida_<?=$nrodetalle?>" style="width:99%; text-align:center;" maxlength="12" class="cell cod_partida" onChange="getDescripcionLista2('accion=getDescripcionPartidaDisponible&CodOrganismo='+$('CodOrganismo').val(), this, $('#NomPartida_<?=$nrodetalle?>'));" <?=$disabled_distribucion?> />
    </td>
    <td align="center" width="225">
        <input type="text" name="NomPartida" id="NomPartida_<?=$nrodetalle?>" style="width:99%;" class="cell2" readonly="readonly" />
    </td>
    <td align="center" width="80">
        <input type="text" name="CodCuenta" id="CodCuenta_<?=$nrodetalle?>" maxlength="13" style="width:99%; text-align:center;" class="cell" onChange="getDescripcionLista2('accion=getDescripcionCuenta', this, $('#NomCuenta_<?=$nrodetalle?>'));" />
    </td>
    <td align="center" width="220">
        <input type="text" name="NomCuenta" id="NomCuenta_<?=$nrodetalle?>" style="width:99%;" class="cell2" readonly="readonly" />
    </td>
    <td align="center" width="80">
        <input type="text" name="CodCuentaPub20" id="CodCuentaPub20_<?=$nrodetalle?>" maxlength="13" style="width:99%; text-align:center;" class="cell2" readonly="readonly" />
    </td>
    <td align="center" width="220">
        <input type="text" name="NomCuentaPub20" id="NomCuentaPub20_<?=$nrodetalle?>" style="width:99%;" class="cell2" readonly="readonly" />
    </td>
    <td align="center">
        <input type="text" name="CodCentroCosto" id="CodCentroCosto_<?=$nrodetalle?>" value="<?=$CodCentroCosto?>" style="text-align:center;" class="cell" onChange="getDescripcionLista2('accion=getDescripcionCCosto', this, $('#NomCentroCosto_<?=$nrodetalle?>'));" />
        <input type="hidden" name="NomCentroCosto" id="NomCentroCosto_<?=$nrodetalle?>" />
    </td>
    <td align="center">
        <input type="checkbox" name="FlagNoAfectoIGV" class="FlagNoAfectoIGV" <?=$cFlagNoAfectoIGV?> <?=$dFlagNoAfectoIGV?> onchange="actualizarMontosObligacion();" />
    </td>
    <td align="center">
        <input type="text" name="Monto" value="0,00" style="text-align:right;" class="cell" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" onchange="actualizarMontosObligacion();" />
    </td>
    <td align="center" width="25">
        <input type="text" name="TipoOrden" maxlength="2" style="width:99%; text-align:center;" class="cell" />
    </td>
    <td align="center" width="85">
        <input type="text" name="NroOrden" maxlength="100" style="width:99%;" class="cell" />
    </td>
    <td align="center">
        <input type="text" name="Referencia" class="cell" maxlength="25" />
    </td>
    <td align="center">
        <input type="text" name="Descripcion" class="cell" maxlength="255" />
    </td>
    <td align="center">
        <input type="text" name="CodPersona" id="CodPersona_<?=$nrodetalle?>" maxlength="6" style="text-align:center;" class="cell" onChange="getDescripcionLista2('accion=getDescripcionPersona', this, $('#NomPersona_<?=$nrodetalle?>'));" />
        <input type="hidden" name="NomPersona" id="NomPersona_<?=$nrodetalle?>" />
    </td>
    <td align="center">
        <input type="text" name="NroActivo" id="NroActivo_<?=$nrodetalle?>" maxlength="15" style="text-align:center;" class="cell2" readonly="readonly" />
    </td>
    <td align="center">
        <input type="checkbox" name="FlagDiferido" />
    </td>
	<?
}
//	--------------------------

//	consulto si se puede modificar una obligacion
elseif ($accion == "obligacion_modificar") {
	list($CodOrganismo, $CodProveedor, $CodTipoDocumento, $NroDocumento) = split("[_]", $codigo);
	$sql = "SELECT Estado
			FROM ap_obligaciones
			WHERE
				CodOrganismo = '".$CodOrganismo."' AND
				CodProveedor = '".$CodProveedor."' AND
				CodTipoDocumento = '".$CodTipoDocumento."' AND
				NroDocumento = '".$NroDocumento."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] != "PR") die("No se puede modificar esta obligación");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	consulto si se puede anular una obligacion
elseif ($accion == "obligacion_anular") {
	list($CodOrganismo, $CodProveedor, $CodTipoDocumento, $NroDocumento) = split("[_]", $codigo);
	$sql = "SELECT Estado
			FROM ap_obligaciones
			WHERE
				CodOrganismo = '".$CodOrganismo."' AND
				CodProveedor = '".$CodProveedor."' AND
				CodTipoDocumento = '".$CodTipoDocumento."' AND
				NroDocumento = '".$NroDocumento."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "AN") die("La obligación ya se encuentra <strong>Anulada</strong>");
		elseif ($field['Estado'] == "AP") die("No se puede anular una obligación <strong>Aprobada</strong><br />Debe anular primero la <strong>Orden de Pago</strong> generada");
		elseif ($field['Estado'] == "PA") die("No se puede anular una obligación <strong>Pagada</strong><br />Debe anular primero el <strong>Pago</strong> y despues la <strong>Orden de Pago</strong> generada");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	muestro los detalles de los documentos seleccionados en la facturacion de logisticas
elseif ($accion == "mostrarDocumentosObligacion") {
	//	documentos
	if ($detalles_documento != "") {
		$linea_documento = split(";", $detalles_documento);	$_Linea=0;
		foreach ($linea_documento as $registro) {	$_Linea++;
			list($_Anio, $_DocumentoReferencia) = split("[.]", $registro);
			if ($grupo != $_DocumentoReferencia) {
				$grupo = $_DocumentoReferencia;
				?><tr class="trListaBody2"><td colspan="7">Documento: <?=$_DocumentoReferencia?></td></tr><?
			}
			//	consulto
			$sql = "SELECT * 
					FROM ap_documentosdetalle 
					WHERE 
						Anio = '".$_Anio."' AND
						CodProveedor = '".$CodProveedor."' AND
						DocumentoClasificacion = '".$DocumentoClasificacion."' AND
						DocumentoReferencia = '".$_DocumentoReferencia."'
					ORDER BY Secuencia";
			$query_det = mysql_query($sql) or die ($sql.mysql_error());
			$rows_det = mysql_num_rows($query_det); $suma_rows_det += $rows_det;
			while ($field_det = mysql_fetch_array($query_det)) { $i++;
				if ($field_det['CodItem'] != "") $coddetalle = $field_det['CodItem']; else $coddetalle = $field_det['CommoditySub'];
				$total = $field_det['Cantidad'] * $field_det['PrecioUnit'];
				?>
				<tr class="trListaBody">
					<td align="center"><?=$i?></td>
					<td align="center"><?=$coddetalle?></td>
					<td><?=($field_det['Descripcion'])?></td>
					<td align="center"><?=$field_det['CodCentroCosto']?></td>
					<td align="right"><?=number_format($field_det['Cantidad'], 2, ',', '.')?></td>
					<td align="right"><?=number_format($field_det['PrecioUnit'], 2, ',', '.')?></td>
					<td align="right"><?=number_format($total, 2, ',', '.')?></td>
				</tr>
				<?
			}
		}
	}
}
//	--------------------------

//	consulto si se puede modificar una orden
elseif ($accion == "orden_pago_modificar") {
	list($Anio, $CodOrganismo, $NroOrden) = split("[_]", $codigo);
	$sql = "SELECT Estado
			FROM ap_ordenpago
			WHERE
				Anio = '".$Anio."' AND
				CodOrganismo = '".$CodOrganismo."' AND
				NroOrden = '".$NroOrden."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "AN") die("No se puede modificar esta orden");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	consulto si se puede anular una orden
elseif ($accion == "orden_pago_anular") {
	list($Anio, $CodOrganismo, $NroOrden) = split("[_]", $codigo);
	$sql = "SELECT Estado
			FROM ap_ordenpago
			WHERE
				Anio = '".$Anio."' AND
				CodOrganismo = '".$CodOrganismo."' AND
				NroOrden = '".$NroOrden."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "AN") die("La orden ya se encuentra <strong>Anulada</strong>");
		elseif ($field['Estado'] == "PA") die("No se puede anular una orden <strong>Pagada</strong><br />Debe anular primero el <strong>Pago</strong>");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	consulto si se puede modificar un pago
elseif ($accion == "pago_modificar") {
	list($NroProceso, $Secuencia, $CodTipoPago) = split("[_]", $codigo);
	$sql = "SELECT Estado
			FROM ap_pagos
			WHERE
				NroProceso = '".$NroProceso."' AND
				Secuencia = '".$Secuencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "AN") die("No se puede modificar un pago <strong>Anulado</strong>");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	consulto si se puede anular un pago
elseif ($accion == "pago_anular") {
	list($NroProceso, $Secuencia, $CodTipoPago) = split("[_]", $codigo);
	$sql = "SELECT Estado
			FROM ap_pagos
			WHERE
				NroProceso = '".$NroProceso."' AND
				Secuencia = '".$Secuencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "AN") die("El pago ya se encuentra <strong>Anulado</strong>");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	
elseif ($accion == "documentos_prepago") {
	list($NroProceso, $Secuencia) = split("[.]", $registro);
	$sql = "SELECT
				p.Secuencia,
				p.NomProveedorPagar,
				p.MontoPago,
				p.MontoRetenido,
				(p.MontoPago + MontoRetenido) AS MontoPagar,
				mp.NomCompleto AS NomProveedor
			FROM
				ap_pagos p
				INNER JOIN mastpersonas mp ON (p.CodProveedor = mp.CodPersona)
			WHERE
				p.NroProceso = '".$NroProceso."' AND
				p.Secuencia = '".$Secuencia."'
			ORDER BY Secuencia";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody">
			<th align="center"><?=$field['Secuencia']?></th>
			<td><?=($field['NomProveedorPagar'])?></td>
			<td><?=($field['NomProveedor'])?></td>
			<td align="right"><?=number_format($field['MontoPagar'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['MontoRetenido'], 2, ',', '.')?></td>
			<td align="right"><strong><?=number_format($field['MontoPago'], 2, ',', '.')?></strong></td>
		</tr>
		<?
	}
}
//	--------------------------

//	
elseif ($accion == "mostrarTabDistribucionObligacion") {
	//	obtengo detalles
	$_TOTAL = 0;
	list($_cod_partida_igv, $_CodCuenta_igv, $_CodCuentaPub20_igv) = getPartidaCuentaFromIGV($_PARAMETRO["IGVCODIGO"]);
	$detalle = split(";char:tr;", $detalles);
	foreach ($detalle as $linea) {
		list($_cod_partida, $_CodCuenta, $_CodCuentaPub20, $_Monto) = split(";char:td;", $linea);
		if ($_codpartida != "" || $_CodCuenta != "" || $_CodCuentaPub20 != "") {
			$_CUENTA[$_CodCuenta] = $_CodCuenta;
			$_CUENTA20[$_CodCuentaPub20] = $_CodCuentaPub20;
			$_PARTIDA[$_cod_partida] = $_cod_partida;
			$_PARTIDA_CUENTA[$_cod_partida] = $_CodCuenta;
			$_PARTIDA_CUENTA20[$_cod_partida] = $_CodCuentaPub20;
			$_CUENTA_MONTO[$_CodCuenta] += $_Monto;
			$_CUENTA_MONTO20[$_CodCuentaPub20] += $_Monto;
			$_PARTIDA_MONTO[$_cod_partida] += $_Monto;
		}
	}
	if ($MontoImpuesto > 0) {
		$_CUENTA[$_CodCuenta_igv] = $_CodCuenta_igv;
		$_CUENTA20[$_CodCuentaPub20_igv] = $_CodCuentaPub20_igv;
		$_PARTIDA[$_cod_partida_igv] = $_cod_partida_igv;
		$_PARTIDA_CUENTA[$_cod_partida_igv] = $_CodCuenta_igv;
		$_PARTIDA_CUENTA20[$_cod_partida_igv] = $_CodCuentaPub20_igv;
		$_CUENTA_MONTO[$_CodCuenta_igv] = $MontoImpuesto;
		$_CUENTA_MONTO20[$_CodCuentaPub20_igv] = $MontoImpuesto;
		$_PARTIDA_MONTO[$_cod_partida_igv] = $MontoImpuesto;
	}
	//	imprimo cuentas
	foreach ($_CUENTA as $CodCuenta) {
		$Descripcion = getValorCampo("ac_mastplancuenta", "CodCuenta", "Descripcion", $CodCuenta);
		if ($Descripcion != "") {
			?>
			<tr class="trListaBody">
				<td align="center">
					<?=$CodCuenta?>
				</td>
				<td>
					<?=$Descripcion?>
				</td>
				<td align="right">
					<?=number_format($_CUENTA_MONTO[$CodCuenta], 2, ',', '.')?>
				</td>
			</tr>
			<?
		}
	}
	echo "|";
	//	imprimo cuentas
	foreach ($_CUENTA20 as $CodCuentaPub20) {
		$Descripcion = getValorCampo("ac_mastplancuenta20", "CodCuenta", "Descripcion", $CodCuentaPub20);
		if ($Descripcion != "") {
			?>
			<tr class="trListaBody">
				<td align="center">
					<?=$CodCuentaPub20?>
				</td>
				<td>
					<?=$Descripcion?>
				</td>
				<td align="right">
					<?=number_format($_CUENTA_MONTO20[$CodCuentaPub20], 2, ',', '.')?>
				</td>
			</tr>
			<?
		}
	}
	echo "|";
	if ($FlagPresupuesto == "S") {
		//	imprimo partidas
		foreach ($_PARTIDA as $cod_partida) {
			$Descripcion = getValorCampo("pv_partida", "cod_partida", "denominacion", $cod_partida);
			list($MontoAjustado, $MontoCompromiso, $MontoPendiente) = disponibilidadPartida($Anio, $CodOrganismo, $cod_partida, $CodPresupuesto);
			if ($FlagCompromiso == "S") $MontoDisponible = $MontoAjustado - $MontoCompromiso;
			else $MontoDisponible = $MontoAjustado - $MontoCompromiso + $_PARTIDA_MONTO[$cod_partida];
			if ($Estado == "PR" && $NroOrden != "") $MontoPendiente -= $_PARTIDA_MONTO[$cod_partida];
			//	valido
			if ($MontoDisponible < $_PARTIDA_MONTO[$cod_partida] && $FlagCompromiso == "S") $style = "style='font-weight:bold; background-color:#F8637D;'";
			elseif($MontoDisponible < ($_PARTIDA_MONTO[$cod_partida] + $MontoPendiente) && $FlagCompromiso == "S") $style = "style='font-weight:bold; background-color:#FFC;'";
			else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
			?>
			<tr class="trListaBody" <?=$style?>>
				<td align="center">
					<input type="hidden" name="cod_partida" value="<?=$cod_partida?>" />
					<input type="hidden" name="Monto" value="<?=$_PARTIDA_MONTO[$cod_partida]?>" />
					<input type="hidden" name="MontoDisponible" value="<?=$MontoDisponible?>" />
					<input type="hidden" name="MontoPendiente" value="<?=$MontoPendiente?>" />
					<?=$cod_partida?>
				</td>
				<td>
					<?=$Descripcion?>
				</td>
				<td align="right">
					<?=number_format($_PARTIDA_MONTO[$cod_partida], 2, ',', '.')?>
				</td>
			</tr>
			<?
		}
	}
}
//	--------------------------

//	
elseif ($accion == "cajaChicaMontoPagado") {
	//	obtengo impuesto
	$sql = "SELECT i.FactorPorcentaje
			FROM
				masttiposervicio ts
				INNER JOIN masttiposervicioimpuesto tsi ON (tsi.CodTipoServicio = ts.CodTipoServicio)
				INNER JOIN mastimpuestos i ON (i.CodImpuesto = tsi.CodImpuesto)
			WHERE ts.CodTipoServicio = '".$CodTipoServicio."'";
	$query_impuesto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_impuesto)) $field_impuesto = mysql_fetch_array($query_impuesto);
	$FactorPorcentaje = $field_impuesto['FactorPorcentaje'];
	//	montos
	if ($FactorPorcentaje > 0) {
		$MontoAfecto = $MontoPagado / (($FactorPorcentaje / 100) + 1);
		$MontoNoAfecto = 0.00;
		$MontoImpuesto = $MontoAfecto * $FactorPorcentaje / 100;
	} else {
		$MontoAfecto = 0.00;
		$MontoNoAfecto = $MontoPagado;
		$MontoImpuesto = 0.00;
	}
	echo "$MontoAfecto|$MontoNoAfecto|$MontoImpuesto";
}
//	--------------------------

//	
elseif ($accion == "cajaChicaMontoAfecto") {
	//	obtengo impuesto
	$sql = "SELECT i.FactorPorcentaje
			FROM
				masttiposervicio ts
				INNER JOIN masttiposervicioimpuesto tsi ON (tsi.CodTipoServicio = ts.CodTipoServicio)
				INNER JOIN mastimpuestos i ON (i.CodImpuesto = tsi.CodImpuesto)
			WHERE ts.CodTipoServicio = '".$CodTipoServicio."'";
	$query_impuesto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_impuesto)) $field_impuesto = mysql_fetch_array($query_impuesto);
	$FactorPorcentaje = $field_impuesto['FactorPorcentaje'];
	//	montos
	if ($FactorPorcentaje > 0) {
		$MontoImpuesto = $MontoAfecto * $FactorPorcentaje / 100;
	} else {
		$MontoAfecto = 0.00;
		$MontoImpuesto = 0.00;
	}
	$MontoPagado = $MontoAfecto + $MontoNoAfecto + $MontoImpuesto;
	echo "$MontoPagado|$MontoImpuesto";
}
//	--------------------------

//	
elseif ($accion == "mostrarTabDistribucionCajaChica") {
	//	obtengo detalles
	$_TOTAL = 0;
	list($_cod_partida_igv, $_CodCuenta_igv, $_CodCuentaPub20_igv) = getPartidaCuentaFromIGV($_PARAMETRO["IGVCODIGO"]);
	$detalle = split(";char:tr;", $detalles_conceptos);
	foreach ($detalle as $linea) {
		list($_Monto, $_cod_partida, $_CodCuenta, $_CodCuentaPub20, $_Distribucion) = split(";char:td;", $linea);
		
		if ($_Distribucion != "") {
			$distribucion = split(";", $_Distribucion);
			foreach ($distribucion as $detalle) {
				list($_dCodConceptoGasto, $_dCodCentroCosto, $_dCodPartida, $_dCodCuenta, $_dCodCuentaPub20, $_dMonto) = split("[|]", $detalle);
				if ($_dCodPartida != "" || $_dCodCuenta != "" || $_CodCuentaPub20 != "") {
					$_CUENTA[$_dCodCuenta] = $_dCodCuenta;
					$_CUENTA20[$_dCodCuentaPub20] = $_dCodCuentaPub20;
					$_PARTIDA[$_dCodPartida] = $_dCodPartida;
					$_PARTIDA_CUENTA[$_dCodPartida] = $_dCodCuenta;
					$_PARTIDA_CUENTA20[$_dCodPartida] = $_dCodCuentaPub20;
					$_CUENTA_MONTO[$_dCodCuenta] += $_dMonto;
					$_CUENTA_MONTO20[$_dCodCuentaPub20] += $_dMonto;
					$_PARTIDA_MONTO[$_dCodPartida] += $_dMonto;
				}
			}
		} else {
			if ($_cod_partida != "" || $_CodCuenta != "" || $_CodCuentaPub20 != "") {
				$_CUENTA[$_CodCuenta] = $_CodCuenta;
				$_CUENTA20[$_CodCuentaPub20] = $_CodCuentaPub20;
				$_PARTIDA[$_cod_partida] = $_cod_partida;
				$_PARTIDA_CUENTA[$_cod_partida] = $_CodCuenta;
				$_PARTIDA_CUENTA20[$_cod_partida] = $_CodCuentaPub20;
				$_CUENTA_MONTO[$_CodCuenta] += $_Monto;
				$_CUENTA_MONTO20[$_CodCuentaPub20] += $_Monto;
				$_PARTIDA_MONTO[$_cod_partida] += $_Monto;
			}
		}
	}
	if ($MontoImpuesto > 0) {
		$_CUENTA[$_CodCuenta_igv] = $_CodCuenta_igv;
		$_CUENTA20[$_CodCuentaPub20_igv] = $_CodCuentaPub20_igv;
		$_PARTIDA[$_cod_partida_igv] = $_cod_partida_igv;
		$_PARTIDA_CUENTA[$_cod_partida_igv] = $_CodCuenta_igv;
		$_PARTIDA_CUENTA20[$_cod_partida_igv] = $_CodCuentaPub20_igv;
		$_CUENTA_MONTO[$_CodCuenta_igv] = $MontoImpuesto;
		$_CUENTA_MONTO20[$_CodCuentaPub20_igv] = $MontoImpuesto;
		$_PARTIDA_MONTO[$_cod_partida_igv] = $MontoImpuesto;
	}
	//	imprimo cuentas
	foreach ($_CUENTA as $CodCuenta) {
		$Descripcion = getValorCampo("ac_mastplancuenta", "CodCuenta", "Descripcion", $CodCuenta);
		if ($Descripcion != "") {
			?>
			<tr class="trListaBody">
				<td align="center">
					<?=$CodCuenta?>
				</td>
				<td>
					<?=$Descripcion?>
				</td>
				<td align="right">
					<?=number_format($_CUENTA_MONTO[$CodCuenta], 2, ',', '.')?>
				</td>
			</tr>
			<?
		}
	}
	echo "|";
	//	imprimo cuentas
	foreach ($_CUENTA20 as $CodCuentaPub20) {
		$Descripcion = getValorCampo("ac_mastplancuenta20", "CodCuenta", "Descripcion", $CodCuentaPub20);
		if ($Descripcion != "") {
			?>
			<tr class="trListaBody">
				<td align="center">
					<?=$CodCuentaPub20?>
				</td>
				<td>
					<?=$Descripcion?>
				</td>
				<td align="right">
					<?=number_format($_CUENTA_MONTO20[$CodCuentaPub20], 2, ',', '.')?>
				</td>
			</tr>
			<?
		}
	}
	echo "|";
	//	imprimo partidas
	foreach ($_PARTIDA as $cod_partida) {
		$Descripcion = getValorCampo("pv_partida", "cod_partida", "denominacion", $cod_partida);
		list($MontoAjustado, $MontoCompromiso, $MontoPendiente) = disponibilidadPartida($Anio, $CodOrganismo, $cod_partida, $CodPresupuesto);
		$MontoDisponible = $MontoAjustado - $MontoCompromiso;
		$MontoPendiente -= $field_partidas['Monto'];
		//	valido
		if ($_PARTIDA_MONTO[$cod_partida] > $MontoDisponible) $style = "style='font-weight:bold; background-color:#F8637D;'";
		elseif($_PARTIDA_MONTO[$cod_partida] > ($MontoDisponible + $MontoPendiente)) $style = "style='font-weight:bold; background-color:#FFC;'";
		else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
		?>
		<tr class="trListaBody" <?=$style?>>
			<td align="center">
				<input type="hidden" name="cod_partida" value="<?=$cod_partida?>" />
				<input type="hidden" name="Monto" value="<?=$_PARTIDA_MONTO[$cod_partida]?>" />
				<input type="hidden" name="MontoDisponible" value="<?=$MontoDisponible?>" />
				<input type="hidden" name="MontoPendiente" value="<?=$MontoPendiente?>" />
				<?=$cod_partida?>
			</td>
			<td>
				<?=$Descripcion?>
			</td>
			<td align="right">
				<?=number_format($_PARTIDA_MONTO[$cod_partida], 2, ',', '.')?>
			</td>
		</tr>
		<?
	}

}
//	--------------------------

//	consulto si se puede modificar
elseif ($accion == "transacciones_bancarias_modificar") {
	list($NroTransaccion, $Secuencia) = split("[_]", $codigo);
	$sql = "SELECT Estado
			FROM ap_bancotransaccion
			WHERE
				NroTransaccion = '".$NroTransaccion."' AND
				Secuencia = '".$Secuencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] != "PR") die("No se puede modificar este registro");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	consulto si se puede modificar
elseif ($accion == "transacciones_bancarias_actualizar") {
	list($NroTransaccion, $Secuencia) = split("[_]", $codigo);
	$sql = "SELECT Estado
			FROM ap_bancotransaccion
			WHERE
				NroTransaccion = '".$NroTransaccion."' AND
				Secuencia = '".$Secuencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] != "PR") die("No se puede actualizar este registro");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	consulto si se puede modificar
elseif ($accion == "transacciones_bancarias_desactualizar") {
	list($NroTransaccion, $Secuencia) = split("[_]", $codigo);
	$sql = "SELECT Estado
			FROM ap_bancotransaccion
			WHERE
				NroTransaccion = '".$NroTransaccion."' AND
				Secuencia = '".$Secuencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] != "AP") die("No se puede desactualizar este registro");
	} else die("No se encuentra el registro");
}
//	--------------------------
?>