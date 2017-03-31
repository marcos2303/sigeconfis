<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$AnioActual-$MesActual-$DiaActual";
//	consulto datos generales de la transaccion
list($CodOrganismo, $CodProveedor, $CodTipoDocumento, $NroDocumento, $CodVoucher) = split("[_]", $registro);
$sql = "SELECT 
			o.*,
			(o.MontoObligacion - o.MontoAdelanto) AS MontoPagar,
			(o.MontoObligacion - o.MontoAdelanto - MontoPagoParcial) AS MontoPendiente,
			mp3.CodPersona AS PreparadoPor,
			mp3.NomCompleto AS NomPreparadoPor,
			mp4.CodPersona AS AprobadoPor,
			mp4.NomCompleto AS NomAprobadoPor,
			cc.Abreviatura AS NomCentroCosto,
			td.CodVoucher
		FROM 
			ap_obligaciones o
			INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
			INNER JOIN mastpersonas mp2 ON (o.CodProveedorPagar = mp2.CodPersona)
			INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			LEFT JOIN mastpersonas mp3 ON (o.IngresadoPor = mp3.CodPersona)
			LEFT JOIN mastpersonas mp4 ON (o.RevisadoPor = mp4.CodPersona)
			LEFT JOIN ac_mastcentrocosto cc ON (cc.CodCentroCosto = o.CodCentroCosto)
		WHERE 
			o.CodOrganismo = '".$CodOrganismo."' AND
			o.CodProveedor = '".$CodProveedor."' AND
			o.CodTipoDocumento = '".$CodTipoDocumento."' AND
			o.NroDocumento = '".$NroDocumento."'";
$query_mast = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_mast) != 0) $field_mast = mysql_fetch_array($query_mast);

//	consulto si el periodo esta abierto
$sql = "SELECT Estado
		FROM ac_controlcierremensual
		WHERE
			TipoRegistro = 'AB' AND
			CodOrganismo = '".$field_mast['CodOrganismo']."' AND
			Periodo = '".substr($field_mast['FechaRevision'], 0, 7)."'";
$query_periodo = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_periodo) != 0) $field_periodo = mysql_fetch_array($query_periodo);

//	sistema fuente
$sql = "SELECT CodSistemaFuente FROM mastaplicaciones WHERE CodAplicacion = 'AP'";
$query_prefpa = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_prefpa) != 0) $field_prefpa = mysql_fetch_array($query_prefpa);
?>
<form name="frmentrada" id="frmentrada" method="POST" onsubmit="return vouchers(this, 'provision-pub20');">
<input type="hidden" id="CodOrganismo" value="<?=$CodOrganismo?>" />
<input type="hidden" id="CodProveedor" value="<?=$CodProveedor?>" />
<input type="hidden" id="CodTipoDocumento" value="<?=$CodTipoDocumento?>" />
<input type="hidden" id="NroDocumento" value="<?=$NroDocumento?>" />
<input type="hidden" id="PeriodoEstado" value="<?=($field_periodo['Estado'])?>" />
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
                        <select id="CodOrganismo" style="width:300px;">
                            <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field_mast['CodOrganismo'], 1)?>
                        </select>
                    </td>
                    <td class="tagForm">Descripci&oacute;n:</td>
                    <td><input type="text" id="ComentariosVoucher" style="width:305px;" value="<?=htmlentities($field_mast['Comentarios'])?>" /></td>
                </tr>
                <tr>
                    <td class="tagForm">* Fecha:</td>
                    <td><input type="text" id="FechaVoucher" value="<?=formatFechaDMA($field_mast['FechaRevision'])?>" style="width:75px;" disabled /></td>
                    <td class="tagForm">Preparado Por:</td>
                    <td>
                        <input type="hidden" id="PreparadoPor" value="<?=$field_mast['PreparadoPor']?>" />
                        <input type="text" style="width:235px;" value="<?=htmlentities($field_mast['NomPreparadoPor'])?>" disabled />
                        <input type="text" id="FechaPreparacion" style="width:60px;" value="<?=formatFechaDMA($field_mast['FechaPreparacion'])?>" disabled />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Voucher:</td>
                    <td>
						<input type="text" id="Periodo" value="<?=substr($field_mast['FechaRevision'], 0, 7)?>" style="width:50px;" />
                        <select id="CodVoucher">
                            <?=loadSelect("ac_voucher", "CodVoucher", "CodVoucher", $field_mast['CodVoucher'], 1)?>
                        </select>
                        <input type="text" id="NroVoucher" style="width:50px;" disabled="disabled" />
                    </td>
                    <td class="tagForm">Aprobado Por:</td>
                    <td>
                        <input type="hidden" id="AprobadoPor" value="<?=$field_mast['AprobadoPor']?>" />
                        <input type="text" style="width:235px;" value="<?=htmlentities($field_mast['NomAprobadoPor'])?>" disabled />
                        <input type="text" id="FechaAprobacion" style="width:60px;" value="<?=formatFechaDMA($field_mast['FechaRevision'])?>" disabled />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">* Libro Contable:</td>
                    <td>
                        <select id="CodLibroCont" style="width:150px;">
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
                        <input type="submit" class="btLista" value="Aceptar" id="btAceptar" />
                        <input type="button" class="btLista" value="Rechazar" onclick="parent.$.prettyPhoto.close();" />
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
				$sql = "(SELECT
							td.CodCuentaProvPub20 AS CodCuenta,
							oc.ReferenciaTipoDocumento AS TipoOrden,
							oc.ReferenciaNroDocumento AS NroOrden,
							pc.Descripcion AS NomCuenta,
							(oc.MontoObligacion + ABS(oc.MontoImpuestoOtros)) AS MontoVoucher,
							pc.TipoSaldo,
							'01' AS Orden,
							'Haber' AS Columna
						 FROM
							ap_obligaciones oc
							INNER JOIN ap_tipodocumento td ON (oc.CodTipoDocumento = td.CodTipoDocumento)
							INNER JOIN ac_mastplancuenta20 pc ON (td.CodCuentaProvPub20 = pc.CodCuenta)
						 WHERE
							oc.CodProveedor = '".$CodProveedor."' AND
							oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
							oc.NroDocumento = '".$NroDocumento."'
						 GROUP BY CodCuenta)
						UNION
						(SELECT
							oc.CodCuentaPub20 AS CodCuenta,
							oc.TipoOrden,
							oc.NroOrden,
							pc.Descripcion AS NomCuenta,
							SUM(oc.Monto) AS MontoVoucher,
							pc.TipoSaldo,
							'02' AS Orden,
							'Debe' AS Columna
						 FROM
							ap_obligacionescuenta oc
							INNER JOIN ac_mastplancuenta20 pc ON (oc.CodCuentaPub20 = pc.CodCuenta)
						 WHERE
							oc.CodProveedor = '".$CodProveedor."' AND
							oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
							oc.NroDocumento = '".$NroDocumento."'
						 GROUP BY CodCuenta)
						UNION
						(SELECT
							(SELECT CodCuentaPub20 FROM mastimpuestos WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS CodCuenta,
							oc.ReferenciaTipoDocumento AS TipoOrden,
							oc.ReferenciaNroDocumento AS NroOrden,
							(SELECT pc2.Descripcion
							 FROM
								mastimpuestos i2
								INNER JOIN ac_mastplancuenta20 pc2 ON (i2.CodCuentaPub20 = pc2.CodCuenta)
							 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS NomCuenta,
							oc.MontoImpuesto AS MontoVoucher,
							(SELECT pc2.TipoSaldo
							 FROM
								mastimpuestos i2
								INNER JOIN ac_mastplancuenta20 pc2 ON (i2.CodCuenta = pc2.CodCuenta)
							 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS TipoSaldo,
							'03' AS Orden,
							'Debe' AS Columna
						 FROM ap_obligaciones oc
						 WHERE
							oc.CodProveedor = '".$CodProveedor."' AND
							oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
							oc.NroDocumento = '".$NroDocumento."' AND
							oc.MontoImpuesto > 0
						 GROUP BY CodCuenta)
						ORDER BY Columna DESC, Orden, CodCuenta";
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
                        	<input type="text" name="CodPersona" value="<?=$CodProveedor?>" class="cell2" style="text-align:center;" readonly />
                        </td>
                    	<td width="25">
                        	<input type="text" name="ReferenciaTipoDocumento" value="<?=$CodTipoDocumento?>" class="cell2" readonly />
                        </td>
                    	<td width="125">
                        	<input type="text" name="ReferenciaNroDocumento" value="<?=$NroDocumento?>" class="cell2" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="CodCentroCosto" value="<?=$_PARAMETRO['CCOSTOVOUCHER']?>" class="cell2" style="text-align:center;" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="FechaVoucher" value="<?=formatFechaDMA($field_mast['FechaRevision'])?>" class="cell2" style="width:75px; text-align:center;" readonly />
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

<!-- JS	-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	validarErroresVoucher();
});
</script>