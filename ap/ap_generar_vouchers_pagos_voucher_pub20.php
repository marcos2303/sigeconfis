<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$AnioActual-$MesActual-$DiaActual";
if ($opcion == "ver") {
	$disabled_ver = "disabled";
	$display_ver = "display:none;";
}
//	consulto datos generales de la transaccion
list($NroProceso, $Secuencia) = split("[_]", $registro);
$sql = "SELECT
			p.CodOrganismo,
			p.CodProveedor,
			p.NroPago,
			p.CodTipoPago,
			p.MontoPago,
			p.NroOrden,
			p.Periodo,
			p.NroCuenta,
			p.FechaPago,
			op.Concepto,
			op.CodCentroCosto,
			op.CodTipoDocumento,
			op.NroDocumento,
			op.NroOrden,
			o.CodCuentaPub20 AS CodCuentaPago,
			o.Comentarios,
			td.Descripcion AS NomCuenta,
			td.FlagProvision,
			b.Banco,
			mp3.CodPersona AS PreparadoPor,
			mp3.NomCompleto AS NomPreparadoPor,
			mp4.CodPersona AS AprobadoPor,
			mp4.NomCompleto AS NomAprobadoPor,
			(SELECT PrefVoucherPA FROM mastaplicaciones WHERE CodAplicacion = 'AP') AS CodVoucher,
			(SELECT CodSistemaFuente FROM mastaplicaciones WHERE CodAplicacion = 'AP') AS CodSistemaFuente
		FROM
			ap_pagos p
			INNER JOIN ap_ordenpago op ON (p.CodOrganismo = op.CodOrganismo AND p.NroOrden = op.NroOrden AND p.Anio = op.Anio)
			INNER JOIN ap_tipodocumento td ON (op.CodTipoDocumento = td.CodTipoDocumento)
			INNER JOIN ap_ctabancaria cb ON (p.NroCuenta = cb.NroCuenta)
			INNER JOIN mastbancos b ON (cb.CodBanco = b.CodBanco)
			INNER JOIN ap_obligaciones o ON (op.CodProveedor = o.CodProveedor AND
											 op.CodTipoDocumento = o.CodTipoDocumento AND
											 op.NroDocumento = o.NroDocumento)
			LEFT JOIN mastpersonas mp3 ON (p.GeneradoPor = mp3.CodPersona)
			LEFT JOIN mastpersonas mp4 ON (p.AprobadoPor = mp4.CodPersona)
		WHERE
			p.NroProceso = '".$NroProceso."' AND
			p.Secuencia = '".$Secuencia."'";
$query_mast = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_mast) != 0) $field_mast = mysql_fetch_array($query_mast);

//	consulto si el periodo esta abierto
$sql = "SELECT Estado
		FROM ac_controlcierremensual
		WHERE
			TipoRegistro = 'AB' AND
			CodOrganismo = '".$field_mast['CodOrganismo']."' AND
			Periodo = '".$field_mast['Periodo']."'";
$query_periodo = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_periodo) != 0) $field_periodo = mysql_fetch_array($query_periodo);

//	sistema fuente
$sql = "SELECT CodSistemaFuente FROM mastaplicaciones WHERE CodAplicacion = 'AP'";
$query_prefpa = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_prefpa) != 0) $field_prefpa = mysql_fetch_array($query_prefpa);
?>

<form name="frmentrada" id="frmentrada" method="POST" onsubmit="return vouchers_pago(this, 'pagos-pub20', 'li2');">
<input type="hidden" id="NroProceso" value="<?=$NroProceso?>" />
<input type="hidden" id="Secuencia" value="<?=$Secuencia?>" />
<input type="hidden" id="CodProveedor" value="<?=$field_mast['CodProveedor']?>" />
<input type="hidden" id="Anio" value="<?=substr($field_mast['Periodo'], 0, 4)?>" />
<input type="hidden" id="NroOrden" value="<?=$field_mast['NroOrden']?>" />
<input type="hidden" id="CodProveedor" value="<?=$field_mast['CodProveedor']?>" />
<input type="hidden" id="CodTipoDocumento" value="<?=$field_mast['CodTipoDocumento']?>" />
<input type="hidden" id="NroDocumento" value="<?=$field_mast['NroDocumento']?>" />
<input type="hidden" id="PeriodoEstado" value="<?=($field_periodo['Estado'])?>" />
<input type="hidden" id="CodTipoPago" value="<?=$field_mast['CodTipoPago']?>" />
<input type="hidden" id="NroCuenta" value="<?=$field_mast['NroCuenta']?>" />
<input type="hidden" id="CodDependencia" value="<?=$_SESSION['DEPENDENCIA_ACTUAL']?>" />
<input type="hidden" id="CodSistemaFuente" value="<?=$field_prefpa['CodSistemaFuente']?>" />
<table align="center">
	<tr>
    	<td valign="top">
            <table width="400" class="tblBotones">
                <tr><td align="right">&nbsp;</td></tr>
            </table>
            
            <table><tr><td><div style="overflow:scroll; width:400px; height:100px;">
            <table width="500" class="tblLista">
            	<thead>
                <tr>
                    <th width="75" scope="col">Periodo</th>
                    <th width="75" scope="col">Voucher</th>
                    <th width="75" scope="col">Fecha</th>
                    <th width="75" scope="col">Status</th>
                    <th scope="col">Organismo</th>
                </tr>
                </thead>
                
                <tbody id="lista1">
                </tbody>
            </table>
            </div></td></tr></table>
        </td>
        
        <td valign="top">
            <table width="550" class="tblBotones">
                <tr><td align="right">&nbsp;</td></tr>
            </table>
            
            <table><tr><td><div style="overflow:scroll; width:550px; height:100px;">
            <table width="700" class="tblLista">
            	<thead>
                <tr>
                    <th width="50" scope="col">Linea</th>
                    <th scope="col">Errores Encontrados</th>
                    <th width="75" scope="col">Periodo</th>
                    <th width="75" scope="col">Voucher</th>
                    <th width="75" scope="col">Organismo</th>
                </tr>
                </thead>
                
                <tbody id="lista_errores">
                </tbody>
            </table>
            </div></td></tr></table>
        </td>
    </tr>
    
    <tr>
    	<td colspan="2">
            <table width="960" class="tblForm">
                <tr>
                    <td class="tagForm" width="125">* Organismo:</td>
                    <td>
                        <select id="CodOrganismo" style="width:300px;" <?=$disabled_ver?>>
                            <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field_mast['CodOrganismo'], 1)?>
                        </select>
                    </td>
                    <td class="tagForm">Descripci&oacute;n:</td>
                    <td><input type="text" id="ComentariosVoucher" style="width:305px;" value="<?=htmlentities($field_mast['Comentarios'])?>" <?=$disabled_ver?> /></td>
                </tr>
                <tr>
                    <td class="tagForm">* Fecha:</td>
                    <td><input type="text" id="FechaVoucher" value="<?=formatFechaDMA($field_mast['FechaPago'])?>" style="width:75px;" disabled /></td>
                    <td class="tagForm">Preparado Por:</td>
                    <td>
                        <input type="hidden" id="PreparadoPor" value="<?=$field_mast['PreparadoPor']?>" />
                        <input type="text" style="width:235px;" value="<?=htmlentities($field_mast['NomPreparadoPor'])?>" disabled />
                        <input type="text" id="FechaPreparacion" style="width:60px;" value="<?=formatFechaDMA($field_mast['Fechapago'])?>" disabled />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Voucher:</td>
                    <td>
						<input type="text" id="Periodo" value="<?=$field_mast['Periodo']?>" style="width:50px;" />
                        <select id="CodVoucher" <?=$disabled_ver?>>
                            <?=loadSelect("ac_voucher", "CodVoucher", "CodVoucher", $field_mast['CodVoucher'], 1)?>
                        </select>
                        <input type="text" id="NroVoucher" style="width:50px;" disabled="disabled" />
                    </td>
                    <td class="tagForm">Aprobado Por:</td>
                    <td>
                        <input type="hidden" id="AprobadoPor" value="<?=$field_mast['AprobadoPor']?>" />
                        <input type="text" style="width:235px;" value="<?=htmlentities($field_mast['NomAprobadoPor'])?>" disabled />
                        <input type="text" id="FechaAprobacion" style="width:60px;" value="<?=formatFechaDMA($FechaActual)?>" disabled />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">* Libro Contable:</td>
                    <td>
                        <select id="CodLibroCont" style="width:150px;" <?=$disabled_ver?>>
                            <?=loadSelect("ac_librocontable", "CodLibroCont", "Descripcion", "", 0)?>
                        </select>
                    </td>
                    <td class="tagForm">* Contabilidad:</td>
                    <td>
                        <select id="CodContabilidad" style="width:150px;">
                            <?=loadSelect("ac_contabilidades", "CodContabilidad", "Descripcion", "F", 1)?>
                        </select>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
	<tr>
    	<td valign="top" colspan="2">
            <table width="960" class="tblBotones">
                <tr>
                    <td align="right">
                        <input type="submit" class="btLista" value="Aceptar" id="btAceptar" style=" <?=$display_ver?>" />
                        <input type="button" class="btLista" value="Rechazar" onclick="javascript:window.close();" style=" <?=$display_ver?>" />
                    </td>
                </tr>
            </table>
            
            <table><tr><td><div style="overflow:scroll; width:960px; height:175px;">
            <table width="1100" class="tblLista">
            	<thead>
                <tr>
                    <th scope="col" width="30">#</th>
                    <th scope="col" width="110">Cuenta</th>
                    <th scope="col">Descripci&oacute;n</th>
                    <th scope="col" width="125">Monto</th>
                    <th scope="col" width="75">Persona</th>
                    <th scope="col" colspan="2">Documento</th>
                    <th scope="col" width="75">C.Costo</th>
                    <th scope="col" width="75">Fecha</th>
                </tr>
                </thead>
                
                <tbody>
                <?php
				//	impuestos que provisionan en el documento
				$sql = "SELECT ABS(SUM(oi1.MontoImpuesto)) AS Monto
						FROM
							ap_obligacionesimpuesto oi1
							INNER JOIN ap_obligaciones o1 ON (oi1.CodProveedor = o1.CodProveedor AND
															  oi1.CodTipoDocumento = o1.CodTipoDocumento AND
															  oi1.NroDocumento = o1.NroDocumento)
						WHERE
							oi1.FlagProvision = 'N' AND
							oi1.CodProveedor = '".$field_mast['CodProveedor']."' AND
							oi1.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
							oi1.NroDocumento = '".$field_mast['NroDocumento']."'
						GROUP BY oi1.FlagProvision";
				$query_impuesto = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($query_impuesto) != 0) $field_impuesto = mysql_fetch_array($query_impuesto);
				
				//	impuestos que provisionan en el pago
				$sql = "SELECT ABS(SUM(oi1.MontoImpuesto)) AS Monto
						FROM
							ap_obligacionesimpuesto oi1
							INNER JOIN ap_obligaciones o1 ON (oi1.CodProveedor = o1.CodProveedor AND
															  oi1.CodTipoDocumento = o1.CodTipoDocumento AND
															  oi1.NroDocumento = o1.NroDocumento)
						WHERE
							oi1.FlagProvision = 'P' AND
							oi1.CodProveedor = '".$field_mast['CodProveedor']."' AND
							oi1.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
							oi1.NroDocumento = '".$field_mast['NroDocumento']."'
						GROUP BY oi1.FlagProvision";
				$query_impuesto3 = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($query_impuesto3) != 0) $field_impuesto3 = mysql_fetch_array($query_impuesto3);
				
				//	si el tipo de documento no provisiona
				if ($field_mast['FlagProvision'] == "N") {
					$sql = "SELECT ABS(SUM(oi1.MontoImpuesto)) AS Monto
							FROM ap_obligacionesimpuesto oi1
							WHERE
								oi1.CodProveedor = '".$field_mast['CodProveedor']."' AND
								oi1.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								oi1.NroDocumento = '".$field_mast['NroDocumento']."'";
					$query_impuesto2 = mysql_query($sql) or die($sql.mysql_error());
					if (mysql_num_rows($query_impuesto2) != 0) $field_impuesto2 = mysql_fetch_array($query_impuesto2);
				} else $field_impuesto2['Monto'] = 0.00;
				
				if ($field_mast['FlagProvision'] == "S") {
					$sql = "(SELECT
								cb.CodCuentaPub20 AS CodCuenta,
								o.ReferenciaTipoDocumento AS TipoOrden,
								o.ReferenciaNroDocumento AS NroOrden,
								pc.Descripcion AS NomCuenta,
								(o.MontoObligacion) AS MontoVoucher,
								pc.TipoSaldo,
								'01' AS Orden,
								'Haber' AS Columna
							 FROM
								ap_obligaciones o
								INNER JOIN ap_ctabancaria cb ON (o.NroCuenta = cb.NroCuenta)
								INNER JOIN ac_mastplancuenta20 pc ON (cb.CodCuentaPub20 = pc.CodCuenta)
							 WHERE
								o.CodProveedor = '".$field_mast['CodProveedor']."' AND
								o.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								o.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY CodCuenta)
							UNION
							(SELECT
								i.CodCuentaPub20 AS CodCuenta,
								o.ReferenciaTipoDocumento AS TipoOrden,
								o.ReferenciaNroDocumento AS NroOrden,
								pc.Descripcion AS NomCuenta,
								ABS(SUM(oc.MontoImpuesto)) AS MontoVoucher,
								pc.TipoSaldo,
								'02' AS Orden,
								'Haber' AS Columna
							 FROM
								ap_obligacionesimpuesto oc
								INNER JOIN ap_obligaciones o ON (oc.CodProveedor = o.CodProveedor AND
																 oc.CodTipoDocumento = o.CodTipoDocumento AND
																 oc.NroDocumento = o.NroDocumento)
								INNER JOIN mastimpuestos i ON (oc.CodImpuesto = i.CodImpuesto)
								INNER JOIN ac_mastplancuenta20 pc ON (i.CodCuentaPub20 = pc.CodCuenta)
							 WHERE
								i.FlagProvision = 'P' AND
								oc.CodProveedor = '".$field_mast['CodProveedor']."' AND
								oc.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY CodCuenta)
							 UNION
							(SELECT
								p.CtaOrdPagoPub20 AS CodCuenta,
								oc.TipoOrden,
								oc.NroOrden,
								pc.Descripcion AS NomCuenta,
								(SUM(oc.Monto) - ".floatval($field_impuesto['Monto']).") AS MontoVoucher,
								pc.TipoSaldo,
								'03' AS Orden,
								'Debe' AS Columna
							 FROM
								ap_obligacionescuenta oc
								INNER JOIN pv_partida p ON (p.cod_partida = oc.cod_partida)
								INNER JOIN ac_mastplancuenta20 pc ON (pc.CodCuenta = p.CtaOrdPagoPub20)
								INNER JOIN ap_obligaciones o ON (o.CodProveedor = oc.CodProveedor AND
																 o.CodTipoDocumento = oc.CodTipoDocumento AND
																 o.NroDocumento = oc.NroDocumento)
							 WHERE
								oc.CodProveedor = '".$field_mast['CodProveedor']."' AND
								oc.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY CodCuenta)
							UNION
							(SELECT
								(SELECT pc2.CodCuenta
								 FROM
									mastimpuestos i2
									INNER JOIN pv_partida pv2 ON (pv2.cod_partida = i2.cod_partida)
									INNER JOIN ac_mastplancuenta20 pc2 ON (pc2.CodCuenta = pv2.CtaOrdPagoPub20)
								 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS CodCuenta,
								oc.ReferenciaTipoDocumento AS TipoOrden,
								oc.ReferenciaNroDocumento AS NroOrden,
								(SELECT pc2.Descripcion
								 FROM
									mastimpuestos i2
									INNER JOIN pv_partida pv2 ON (pv2.cod_partida = i2.cod_partida)
									INNER JOIN ac_mastplancuenta20 pc2 ON (pc2.CodCuenta = pv2.CtaOrdPagoPub20)
								 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS NomCuenta,
								oc.MontoImpuesto AS MontoVoucher,
								(SELECT pc2.TipoSaldo
								 FROM
									mastimpuestos i2
									INNER JOIN pv_partida pv2 ON (pv2.cod_partida = i2.cod_partida)
									INNER JOIN ac_mastplancuenta20 pc2 ON (pc2.CodCuenta = pv2.CtaOrdPagoPub20)
								 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS TipoSaldo,
								'04' AS Orden,
								'Debe' AS Columna
							 FROM ap_obligaciones oc
							 WHERE
								oc.CodProveedor = '".$field_mast['CodProveedor']."' AND
								oc.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field_mast['NroDocumento']."' AND
								oc.MontoImpuesto > 0
							 GROUP BY CodCuenta)
							ORDER BY Columna DESC, Orden, CodCuenta";
				} else {
					/*
					$sql = "(SELECT
								cb.CodCuentaPub20 AS CodCuenta,
								o.ReferenciaTipoDocumento AS TipoOrden,
								o.ReferenciaNroDocumento AS NroOrden,
								pc.Descripcion AS NomCuenta,
								(o.MontoObligacion) AS MontoVoucher,
								pc.TipoSaldo,
								'01' AS Orden,
								'Haber' AS Columna
							 FROM
								ap_obligaciones o
								INNER JOIN ap_ctabancaria cb ON (o.NroCuenta = cb.NroCuenta)
								INNER JOIN ac_mastplancuenta20 pc ON (cb.CodCuentaPub20 = pc.CodCuenta)
							 WHERE
								o.CodProveedor = '".$field_mast['CodProveedor']."' AND
								o.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								o.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY CodCuenta)
							UNION
							(SELECT
								i.CodCuentaPub20 AS CodCuenta,
								o.ReferenciaTipoDocumento AS TipoOrden,
								o.ReferenciaNroDocumento AS NroOrden,
								pc.Descripcion AS NomCuenta,
								ABS(SUM(oc.MontoImpuesto)) AS MontoVoucher,
								pc.TipoSaldo,
								'02' AS Orden,
								'Haber' AS Columna
							 FROM
								ap_obligacionesimpuesto oc
								INNER JOIN ap_obligaciones o ON (oc.CodProveedor = o.CodProveedor AND
																 oc.CodTipoDocumento = o.CodTipoDocumento AND
																 oc.NroDocumento = o.NroDocumento)
								INNER JOIN mastimpuestos i ON (oc.CodImpuesto = i.CodImpuesto)
								INNER JOIN ac_mastplancuenta20 pc ON (i.CodCuentaPub20 = pc.CodCuenta)
							 WHERE
								i.FlagProvision = 'P' AND
								oc.CodProveedor = '".$field_mast['CodProveedor']."' AND
								oc.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY CodCuenta)
							 UNION
							(SELECT
								p.CtaOrdPagoPub20 AS CodCuenta,
								oc.TipoOrden,
								oc.NroOrden,
								pc.Descripcion AS NomCuenta,
								(SUM(oc.Monto) - ".floatval($field_impuesto['Monto']).") AS MontoVoucher,
								pc.TipoSaldo,
								'03' AS Orden,
								'Debe' AS Columna
							 FROM
								ap_obligacionescuenta oc
								INNER JOIN pv_partida p ON (p.cod_partida = oc.cod_partida)
								INNER JOIN ac_mastplancuenta20 pc ON (pc.CodCuenta = p.CtaOrdPagoPub20)
								INNER JOIN ap_obligaciones o ON (o.CodProveedor = oc.CodProveedor AND
																 o.CodTipoDocumento = oc.CodTipoDocumento AND
																 o.NroDocumento = oc.NroDocumento)
							 WHERE
								oc.CodProveedor = '".$field_mast['CodProveedor']."' AND
								oc.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY CodCuenta)
							UNION
							(SELECT
								(SELECT pc2.CodCuenta
								 FROM
									mastimpuestos i2
									INNER JOIN pv_partida pv2 ON (pv2.cod_partida = i2.cod_partida)
									INNER JOIN ac_mastplancuenta20 pc2 ON (pc2.CodCuenta = pv2.CtaOrdPagoPub20)
								 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS CodCuenta,
								oc.ReferenciaTipoDocumento AS TipoOrden,
								oc.ReferenciaNroDocumento AS NroOrden,
								(SELECT pc2.Descripcion
								 FROM
									mastimpuestos i2
									INNER JOIN pv_partida pv2 ON (pv2.cod_partida = i2.cod_partida)
									INNER JOIN ac_mastplancuenta20 pc2 ON (pc2.CodCuenta = pv2.CtaOrdPagoPub20)
								 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS NomCuenta,
								oc.MontoImpuesto AS MontoVoucher,
								(SELECT pc2.TipoSaldo
								 FROM
									mastimpuestos i2
									INNER JOIN pv_partida pv2 ON (pv2.cod_partida = i2.cod_partida)
									INNER JOIN ac_mastplancuenta20 pc2 ON (pc2.CodCuenta = pv2.CtaOrdPagoPub20)
								 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS TipoSaldo,
								'04' AS Orden,
								'Debe' AS Columna
							 FROM ap_obligaciones oc
							 WHERE
								oc.CodProveedor = '".$field_mast['CodProveedor']."' AND
								oc.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field_mast['NroDocumento']."' AND
								oc.MontoImpuesto > 0
							 GROUP BY CodCuenta)
							UNION
							(SELECT
								pc.CodCuenta,
								oc.TipoOrden,
								oc.NroOrden,
								pc.Descripcion AS NomCuenta,
								(SUM(oc.Monto) - ".floatval(abs($field_impuesto['Monto']+$field_impuesto2['Monto'])).") AS MontoVoucher,
								pc.TipoSaldo,
								'05' AS Orden,
								'Debe' AS Columna
							 FROM
								ap_obligacionescuenta oc
								INNER JOIN ac_mastplancuenta20 pc ON (oc.CodCuentaPub20 = pc.CodCuenta)
							 WHERE
								oc.CodProveedor = '".$field_mast['CodProveedor']."' AND
								oc.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY CodCuenta)
							ORDER BY Columna DESC, Orden, CodCuenta";
					*/
					
					$sql = "(SELECT
								cb.CodCuentaPub20 AS CodCuenta,
								o.ReferenciaTipoDocumento AS TipoOrden,
								o.ReferenciaNroDocumento AS NroOrden,
								pc.Descripcion AS NomCuenta,
								(o.MontoObligacion) AS MontoVoucher,
								pc.TipoSaldo,
								'01' AS Orden,
								'Haber' AS Columna
							 FROM
								ap_obligaciones o
								INNER JOIN ap_ctabancaria cb ON (o.NroCuenta = cb.NroCuenta)
								INNER JOIN ac_mastplancuenta20 pc ON (cb.CodCuentaPub20 = pc.CodCuenta)
							 WHERE
								o.CodProveedor = '".$field_mast['CodProveedor']."' AND
								o.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								o.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY CodCuenta)
							UNION
							(SELECT
								i.CodCuentaPub20 AS CodCuenta,
								o.ReferenciaTipoDocumento AS TipoOrden,
								o.ReferenciaNroDocumento AS NroOrden,
								pc.Descripcion AS NomCuenta,
								ABS(SUM(oc.MontoImpuesto)) AS MontoVoucher,
								pc.TipoSaldo,
								'02' AS Orden,
								'Haber' AS Columna
							 FROM
								ap_obligacionesimpuesto oc
								INNER JOIN ap_obligaciones o ON (oc.CodProveedor = o.CodProveedor AND
																 oc.CodTipoDocumento = o.CodTipoDocumento AND
																 oc.NroDocumento = o.NroDocumento)
								INNER JOIN mastimpuestos i ON (oc.CodImpuesto = i.CodImpuesto)
								INNER JOIN ac_mastplancuenta20 pc ON (i.CodCuentaPub20 = pc.CodCuenta)
							 WHERE
								i.FlagProvision = 'P' AND
								oc.CodProveedor = '".$field_mast['CodProveedor']."' AND
								oc.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY CodCuenta)
							UNION
							(SELECT
								(SELECT pc2.CodCuenta
								 FROM
									mastimpuestos i2
									INNER JOIN pv_partida pv2 ON (pv2.cod_partida = i2.cod_partida)
									INNER JOIN ac_mastplancuenta20 pc2 ON (pc2.CodCuenta = pv2.CtaOrdPagoPub20)
								 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS CodCuenta,
								oc.ReferenciaTipoDocumento AS TipoOrden,
								oc.ReferenciaNroDocumento AS NroOrden,
								(SELECT pc2.Descripcion
								 FROM
									mastimpuestos i2
									INNER JOIN pv_partida pv2 ON (pv2.cod_partida = i2.cod_partida)
									INNER JOIN ac_mastplancuenta20 pc2 ON (pc2.CodCuenta = pv2.CtaOrdPagoPub20)
								 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS NomCuenta,
								oc.MontoImpuesto AS MontoVoucher,
								(SELECT pc2.TipoSaldo
								 FROM
									mastimpuestos i2
									INNER JOIN pv_partida pv2 ON (pv2.cod_partida = i2.cod_partida)
									INNER JOIN ac_mastplancuenta20 pc2 ON (pc2.CodCuenta = pv2.CtaOrdPagoPub20)
								 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS TipoSaldo,
								'04' AS Orden,
								'Debe' AS Columna
							 FROM ap_obligaciones oc
							 WHERE
								oc.CodProveedor = '".$field_mast['CodProveedor']."' AND
								oc.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field_mast['NroDocumento']."' AND
								oc.MontoImpuesto > 0
							 GROUP BY CodCuenta)
							UNION
							(SELECT
								pc.CodCuenta,
								oc.TipoOrden,
								oc.NroOrden,
								pc.Descripcion AS NomCuenta,
								(SUM(oc.Monto) - ".floatval(abs($field_impuesto['Monto']+$field_impuesto2['Monto'])).") AS MontoVoucher,
								pc.TipoSaldo,
								'05' AS Orden,
								'Debe' AS Columna
							 FROM
								ap_obligacionescuenta oc
								INNER JOIN ac_mastplancuenta20 pc ON (oc.CodCuentaPub20 = pc.CodCuenta)
							 WHERE
								oc.CodProveedor = '".$field_mast['CodProveedor']."' AND
								oc.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY CodCuenta)
							ORDER BY Columna DESC, Orden, CodCuenta";
				}
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
				while($field_det = mysql_fetch_array($query_det)) {
					$Monto = $field_det['MontoVoucher'];
					if ($field_det['Columna'] == "Haber") {
						$style = " color:red;";
						$Monto = abs($Monto) * (-1);
						$Debitos += $Monto;
					} else {
						$style = "";
						$Monto = abs($Monto);
						$Creditos += $Monto;
					}
					?>
					<tr class="trListaBody">
                    	<td>
                            <input type="text" name="Linea" value="<?=++$Linea?>" class="cell2" style="text-align:center;" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="CodCuenta" value="<?=$field_det['CodCuenta']?>" class="cell2" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="Descripcion" value="<?=htmlentities($field_det['NomCuenta'])?>" class="cell2" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="MontoVoucher" value="<?=number_format($Monto, 2, ',', '.')?>" class="cell2" style="text-align:right; <?=$style?>" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="CodPersona" value="<?=$field_mast['CodProveedor']?>" class="cell2" style="text-align:center;" readonly />
                        </td>
                    	<td width="25">
                        	<input type="text" name="ReferenciaTipoDocumento" value="OP" class="cell2" readonly />
                        </td>
                    	<td width="125">
                        	<input type="text" name="ReferenciaNroDocumento" value="<?=$field_mast['NroOrden']?>" class="cell2" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="CodCentroCosto" value="<?=$_PARAMETRO['CCOSTOVOUCHER']?>" class="cell2" style="text-align:center;" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="FechaVoucher" value="<?=formatFechaDMA($field_mast['FechaPago'])?>" class="cell2" style="text-align:center;" readonly />
                        </td>
					</tr>
					<?
				}
				?>
                </tbody>
            </table>
            </div></td></tr></table>
            
            <table>
                <tr>
                    <th scope="col" width="140">Nro Lineas: <input type="text" id="Lineas" value="<?=$Linea?>" class="cell2" style="text-align:center; font-weight:bold; font-size:12px; width:20px;" readonly /></th>
                    <th scope="col" width="75">&nbsp;</th>
                    <th scope="col" width="150">&nbsp;</th>
                    <th scope="col" width="75">Total:</th>
                    <th scope="col" width="125">
                    	<input type="text" id="Creditos" value="<?=number_format($Creditos, 2, ',', '.')?>" class="cell2" style="text-align:right; font-weight:bold; font-size:12px;" readonly />
                    </th>
                    <th scope="col" width="125">
                    	<input type="text" id="Debitos" value="<?=number_format($Debitos, 2, ',', '.')?>" class="cell2" style="text-align:right; font-weight:bold; font-size:12px; color:red;" readonly />
					</th>
                    <th scope="col" width="125">&nbsp;</th>
                </tr>
			</table>
            
        </td>
    </tr>
</table>
</form>

<?php
if ($opcion != "ver") {
	?>
    <!-- JS	-->
    <script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        validarErroresVoucher();
    });
    </script>
    <?
}
?>