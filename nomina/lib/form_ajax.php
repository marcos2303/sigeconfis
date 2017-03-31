<?php
session_start();
include("../../lib/fphp.php");
include("fphp.php");
//	$__archivo = fopen("$modulo-$accion.sql", "w+");
//	fwrite($__archivo, $sql.";\n\n");
///////////////////////////////////////////////////////////////////////////////
//	PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	interfase cuentas por pagar
if ($modulo == "interfase_cuentas_por_pagar") {
	//	calcular
	if ($accion == "calcular") {
		mysql_query("BEGIN");
		//	-----------------
		list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
		$PeriodoActual = "$AnioActual-$MesActual";
		list($PeriodoAnio, $PeriodoMes) = split("[-]", $Periodo);
		
		//	consulto para obtener las tranmsferidas
		$filtro_transferidos1 = "";
		$filtro_transferidos2 = "";
		$filtro_transferidos3 = "";
		$sql = "SELECT po.CodProveedor
				FROM
					pr_obligaciones po
					INNER JOIN ap_obligaciones ao ON (po.CodProveedor = ao.CodProveedor AND
													  po.CodTipoDocumento = ao.CodTipoDocumento AND
													  po.NroDocumento = ao.NroDocumento)
				WHERE
					po.CodOrganismo = '".$CodOrganismo."' AND
					po.CodTipoNom = '".$CodTipoNom."' AND
					po.PeriodoNomina = '".$Periodo."' AND
					po.CodTipoProceso = '".$CodTipoProceso."' AND
					po.TipoObligacion = '".$TipoObligacion."' AND
					po.FlagTransferido = 'S' AND
					ao.Estado <> 'AN'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field = mysql_fetch_array($query)) {
			if ($TipoObligacion == "01" || $TipoObligacion == "02") $filtro_transferidos1 .= " AND (tnec.CodPersona <> '".$field['CodProveedor']."')";
			if ($TipoObligacion == "03") $filtro_transferidos2 .= " AND (c.CodPersona <> '".$field['CodProveedor']."')";
			if ($TipoObligacion == "04") $filtro_transferidos3 .= " AND (rj.Demandante <> '".$field['CodProveedor']."')";
		}
		
		//	consulto para eliminar las obligaciones de cxp y las no transferidas
		$sql = "(SELECT
					po.CodProveedor,
					po.CodTipoDocumento,
					po.NroDocumento
				 FROM
					pr_obligaciones po
					INNER JOIN ap_obligaciones ao ON (po.CodProveedor = ao.CodProveedor AND
													  po.CodTipoDocumento = ao.CodTipoDocumento AND
													  po.NroDocumento = ao.NroDocumento)
				 WHERE
					po.CodOrganismo = '".$CodOrganismo."' AND
					po.CodTipoNom = '".$CodTipoNom."' AND
					po.PeriodoNomina = '".$Periodo."' AND
					po.CodTipoProceso = '".$CodTipoProceso."' AND
					po.TipoObligacion = '".$TipoObligacion."' AND
					po.FlagTransferido = 'S' AND
					ao.Estado = 'AN')
				UNION
				(SELECT
					po.CodProveedor,
					po.CodTipoDocumento,
					po.NroDocumento
				 FROM pr_obligaciones po
				 WHERE
					po.CodOrganismo = '".$CodOrganismo."' AND
					po.CodTipoNom = '".$CodTipoNom."' AND
					po.PeriodoNomina = '".$Periodo."' AND
					po.CodTipoProceso = '".$CodTipoProceso."' AND
					po.TipoObligacion = '".$TipoObligacion."' AND
					po.FlagTransferido = 'N')";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field = mysql_fetch_array($query)) {
			//	obligacion
			$sql = "DELETE FROM ap_obligaciones
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	cuentas
			$sql = "DELETE FROM ap_obligacionescuenta
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	impuestos/retenciones
			$sql = "DELETE FROM ap_obligacionesimpuesto
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	causados
			$sql = "DELETE FROM ap_distribucionobligacion
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	compromisos
			$sql = "DELETE FROM lg_distribucioncompromisos
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	elimino las obligaciones
			$sql = "DELETE FROM pr_obligaciones
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	elimino las obligaciones x cuentas
			$sql = "DELETE FROM pr_obligacionescuenta
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	elimino las obligaciones x retenciones
			$sql = "DELETE FROM pr_obligacionesretenciones
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	actualizo el payroll
			$sql = "UPDATE pr_tiponominaempleado
					SET EstadoPago = 'PE'
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	obtengo el tipo de documento
		$sql = "SELECT CodTipoDocumento
				FROM pr_tiponominaproceso
				WHERE
					CodTipoNom = '".$CodTipoNom."' AND
					CodTipoProceso = '".$CodTipoProceso."'";
		$query_doc = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_doc) != 0) $field_doc = mysql_fetch_array($query_doc);
		else die("No se encontró un <strong>Tipo de Documento</strong> asociado al Proceso");
		
		//	obtengo la obligaciones a insertar
		if ($_PARAMETRO['INTERFASEAP'] == "S") {
			if ($TipoObligacion == "01" || $TipoObligacion == "02") {
				if ($TipoObligacion == "01") $CodTipoPago = "01";
				elseif ($TipoObligacion == "02") $CodTipoPago = "02";
				$sql = "SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							mp1.CodPersona AS CodProveedor,
							mp1.NomCompleto AS NomProveedor,
							'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
							me.CodTipoPago,
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETRO['CTANOMINA']."' AS CodCuenta,
							'".$_PARAMETRO['CTANOMINAPUB20']."' AS CodCuentaPub20,
							'S' AS FlagCompromiso,
							'S' AS FlagPresupuesto,
							'N' AS FlagDistribucionManual
						FROM
							pr_tiponominaempleadoconcepto tnec
							INNER JOIN pr_tiponominaempleado tne ON (tne.CodTipoNom = tnec.CodTipoNom AND
																	 tne.Periodo = tnec.Periodo AND
																	 tne.CodOrganismo = tnec.CodOrganismo AND
																	 tne.CodTipoProceso = tnec.CodTipoProceso AND
																	 tne.CodPersona = tnec.CodPersona)
							INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
							INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
							INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
							INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
							INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
							INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
							INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)    
							INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
							INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																		tnec.CodTipoProceso = cpd.CodTipoProceso AND
																		tnec.CodConcepto = cpd.CodConcepto)
						WHERE
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."' AND
							tne.EstadoPago = 'PE' AND
							me.CodTipoPago = '".$CodTipoPago."' AND
							c.Tipo = 'I' $filtro_transferidos1
						GROUP BY tnec.CodPersona";
			}
			elseif ($TipoObligacion == "03") {
				$sql = "SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							c.CodPersona AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
							p.CodTipoPago,
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							SUM(tnec.Monto) AS MontoIngreso,
							cpd.CuentaHaber AS CodCuenta,
							cpd.CuentaHaberPub20 AS CodCuentaPub20,
							'S' AS FlagCompromiso,
							'S' AS FlagPresupuesto,
							'N' AS FlagDistribucionManual
						FROM
							pr_tiponominaempleadoconcepto tnec
							INNER JOIN pr_tiponominaempleado tne ON (tne.CodTipoNom = tnec.CodTipoNom AND
																	 tne.Periodo = tnec.Periodo AND
																	 tne.CodOrganismo = tnec.CodOrganismo AND
																	 tne.CodTipoProceso = tnec.CodTipoProceso AND
																	 tne.CodPersona = tnec.CodPersona)
							INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
							INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
							INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
							INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
							INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
							INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
							INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																		tnec.CodTipoProceso = cpd.CodTipoProceso AND
																		tnec.CodConcepto = cpd.CodConcepto)
							INNER JOIN mastpersonas mp2 ON (c.CodPersona = mp2.CodPersona)
							INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						WHERE
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."' AND
							tne.EstadoPago = 'PE' AND
							c.Tipo = 'A' $filtro_transferidos2
						GROUP BY c.CodPersona";
			}
			elseif ($TipoObligacion == "04") {
				$sql = "SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							rj.Demandante AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'".$_PARAMETRO['TIPODOCCXP']."' AS CodTipoDocumento,
							p.CodTipoPago,
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETRO['CTANOMINA']."' AS CodCuenta,
							'".$_PARAMETRO['CTANOMINAPUB20']."' AS CodCuentaPub20,
							'N' AS FlagCompromiso,
							'N' AS FlagPresupuesto,
							'S' AS FlagDistribucionManual
						FROM
							pr_tiponominaempleadoconcepto tnec
							INNER JOIN pr_tiponominaempleado tne ON (tne.CodTipoNom = tnec.CodTipoNom AND
																	 tne.Periodo = tnec.Periodo AND
																	 tne.CodOrganismo = tnec.CodOrganismo AND
																	 tne.CodTipoProceso = tnec.CodTipoProceso AND
																	 tne.CodPersona = tnec.CodPersona)
							INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
							INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
							INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
							INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
							INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
							INNER JOIN rh_retencionjudicial rj ON (tnec.CodPersona = rj.CodPersona AND
																   tnec.CodOrganismo = rj.CodOrganismo)
							INNER JOIN rh_retencionjudicialconceptos rjc ON (rj.CodRetencion = rjc.CodRetencion AND
																			 tnec.CodOrganismo = rjc.CodOrganismo AND
																			 tnec.CodConcepto = rjc.CodConcepto)
							INNER JOIN mastpersonas mp2 ON (rj.Demandante = mp2.CodPersona)
							INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						WHERE
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."' $filtro_transferidos
						GROUP BY rj.Demandante";
			}
		}
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field = mysql_fetch_array($query)) {
			unset($field_descuento);
			unset($field_retencion);
			//	obtengo retenciones y descuentos
			if ($TipoObligacion == "01" || $TipoObligacion == "02") {
				//	descuento
				$sql = "SELECT SUM(tnec2.Monto) AS MontoDescuento
						FROM
							pr_tiponominaempleadoconcepto tnec2
							INNER JOIN tiponomina tn2 ON (tnec2.CodTipoNom = tn2.CodTipoNom)
							INNER JOIN pr_tipoproceso tp2 ON (tnec2.CodTipoProceso = tp2.CodTipoProceso)
							INNER JOIN mastorganismos o2 ON (tnec2.CodOrganismo = o2.CodOrganismo)
							INNER JOIN mastpersonas mp21 ON (tnec2.CodPersona = mp21.CodPersona)
							INNER JOIN mastempleado me2 ON (mp21.CodPersona = me2.CodPersona)
							INNER JOIN mastpersonas mp22 ON (o2.CodPersona = mp22.CodPersona)
							INNER JOIN mastproveedores p2 ON (mp22.CodPersona = p2.CodProveedor)
							INNER JOIN pr_concepto c2 ON (tnec2.CodConcepto = c2.CodConcepto)
							INNER JOIN pr_conceptoperfil cp2 ON (tn2.CodPerfilConcepto = cp2.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd2 ON (cp2.CodPerfilConcepto = cpd2.CodPerfilConcepto AND
																		 tnec2.CodTipoProceso = cpd2.CodTipoProceso AND
																		 tnec2.CodConcepto = cpd2.CodConcepto)
						WHERE
							me2.CodTipoPago = '".$CodTipoPago."' AND
							tnec2.CodTipoNom = '".$CodTipoNom."' AND
							tnec2.Periodo = '".$Periodo."' AND
							tnec2.CodOrganismo = '".$CodOrganismo."' AND
							tnec2.CodTipoProceso = '".$CodTipoProceso."' AND
							tnec2.CodPersona = '".$field['CodProveedor']."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N'
						GROUP BY tnec2.CodOrganismo";
				$query_descuento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_descuento) != 0) $field_descuento = mysql_fetch_array($query_descuento);
				//	retenciones
				$sql = "SELECT SUM(tnec2.Monto) AS MontoRetencion
						FROM
							pr_tiponominaempleadoconcepto tnec2
							INNER JOIN tiponomina tn2 ON (tnec2.CodTipoNom = tn2.CodTipoNom)
							INNER JOIN pr_tipoproceso tp2 ON (tnec2.CodTipoProceso = tp2.CodTipoProceso)
							INNER JOIN mastorganismos o2 ON (tnec2.CodOrganismo = o2.CodOrganismo)
							INNER JOIN mastpersonas mp21 ON (tnec2.CodPersona = mp21.CodPersona)
							INNER JOIN mastempleado me2 ON (mp21.CodPersona = me2.CodPersona)
							INNER JOIN mastpersonas mp22 ON (o2.CodPersona = mp22.CodPersona)
							INNER JOIN mastproveedores p2 ON (mp22.CodPersona = p2.CodProveedor)
							INNER JOIN pr_concepto c2 ON (tnec2.CodConcepto = c2.CodConcepto)
							INNER JOIN pr_conceptoperfil cp2 ON (tn2.CodPerfilConcepto = cp2.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd2 ON (cp2.CodPerfilConcepto = cpd2.CodPerfilConcepto AND
																		 tnec2.CodTipoProceso = cpd2.CodTipoProceso AND
																		 tnec2.CodConcepto = cpd2.CodConcepto)
						WHERE
							me2.CodTipoPago = '".$CodTipoPago."' AND
							tnec2.CodTipoNom = '".$CodTipoNom."' AND
							tnec2.Periodo = '".$Periodo."' AND
							tnec2.CodOrganismo = '".$CodOrganismo."' AND
							tnec2.CodTipoProceso = '".$CodTipoProceso."' AND
							tnec2.CodPersona = '".$field['CodProveedor']."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'S'
						GROUP BY tnec2.CodOrganismo";
				$query_retencion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_retencion) != 0) $field_retencion = mysql_fetch_array($query_retencion); 
			}
			//	obtengo algunos valores a insertar
			$NroDocumento = $CodOrganismo.$PeriodoAnio.$PeriodoMes.$CodTipoNom.$CodTipoProceso.$TipoObligacion;
			##	valido nro de documento
			$sql = "SELECT *
					FROM ap_obligaciones
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento LIKE '".$NroDocumento."%'";
			$query_doc = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			$_nro = mysql_num_rows($query_doc);
			if ($_nro > 0) $NroDocumento .= "-".(++$_nro);
			##
			$NroCuenta = getCuentaBancariaDefault($CodOrganismo, $field['CodTipoPago']);
			$Comentarios = "PERIODO $Periodo NOMINA DE $field[Nomina] $field[NomProceso]";
			$MontoNoAfecto = $field['MontoIngreso'] - $field_descuento['MontoDescuento'];
			$MontoObligacion = $MontoNoAfecto - $field_retencion['MontoRetencion'];
			//	inserto la obligacion
			$sql = "INSERT INTO pr_obligaciones
					SET
						TipoObligacion = '".$TipoObligacion."',
						CodOrganismo = '".$CodOrganismo."',
						CodTipoNom = '".$CodTipoNom."',
						Periodo = '".$PeriodoActual."',
						PeriodoNomina = '".$Periodo."',
						CodTipoProceso = '".$CodTipoProceso."',
						CodProveedor = '".$field['CodProveedor']."',
						CodTipoDocumento = '".$field['CodTipoDocumento']."',
						NroDocumento = '".$NroDocumento."',
						NroControl = '".$NroDocumento."',
						NroCuenta = '".$NroCuenta."',
						CodTipoPago = '".$field['CodTipoPago']."',
						CodTipoServicio = '".$field['CodTipoServicio']."',
						FechaRegistro = NOW(),
						CodProveedorPagar = '".$field['CodProveedor']."',
						NomProveedorPagar = '".$field['NomProveedor']."',
						Comentarios = '".$Comentarios."',
						ComentariosAdicional = '".$Comentarios."',
						MontoObligacion = '".$MontoObligacion."',
						MontoNoAfecto = '".$MontoNoAfecto."',
						MontoImpuestoOtros = '".abs(($MontoNoAfecto-$MontoObligacion))."',
						CodCuenta = '".$field['CodCuenta']."',
						CodCuentaPub20 = '".$field['CodCuentaPub20']."',
						CodCentroCosto = '".$_PARAMETRO['CCOSTOPR']."',
						FlagCompromiso = '".$field['FlagCompromiso']."',
						FlagPresupuesto = '".$field['FlagPresupuesto']."',
						FlagDistribucionManual = '".$field['FlagDistribucionManual']."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	actualizo el payroll
			$sql = "UPDATE pr_tiponominaempleado
					SET
						CodProveedor = '".$field['CodProveedor']."',
						CodTipoDocumento = '".$field['CodTipoDocumento']."',
						NroDocumento = '".$NroDocumento."'
					WHERE
						CodTipoNom = '".$CodTipoNom."' AND
						Periodo = '".$Periodo."' AND
						CodPersona = '".$field['CodProveedor']."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						CodTipoProceso = '".$CodTipoProceso."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	actualizo prestaciones
			$sql = "UPDATE pr_liquidacionempleado
					SET
						CodProveedor = '".$field['CodProveedor']."',
						CodTipoDocumento = '".$field['CodTipoDocumento']."',
						NroDocumento = '".$NroDocumento."'
					WHERE
						CodPersona = '".$field['CodProveedor']."' AND
						Periodo = '".$Periodo."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	consulto las partidas a insertar
		if ($TipoObligacion == "01" || $TipoObligacion == "02") {
			$sql = "SELECT
						mp1.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'01' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
					FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)    
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
						LEFT JOIN pv_partida pv ON (cpd.cod_partida = pv.cod_partida)
					WHERE
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
						me.CodTipoPago = '".$CodTipoPago."' AND
						c.Tipo = 'I' $filtro_transferidos1
					GROUP BY tnec.CodPersona, cpd.cod_partida";
		}
		elseif ($TipoObligacion == "03") {
			$sql = "SELECT
						c.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'03' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
					FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
						INNER JOIN mastpersonas mp2 ON (c.CodPersona = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						LEFT JOIN pv_partida pv ON (cpd.cod_partida = pv.cod_partida)
					WHERE
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
						c.Tipo = 'A' $filtro_transferidos2
					GROUP BY c.CodPersona, cpd.cod_partida";
		}
		elseif ($TipoObligacion == "04") {
			$sql = "SELECT
						rj.Demandante AS CodProveedor,
						'".$_PARAMETRO['TIPODOCCXP']."' AS CodTipoDocumento,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
					FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN rh_retencionjudicial rj ON (tnec.CodPersona = rj.CodPersona AND
															   tnec.CodOrganismo = rj.CodOrganismo)
						INNER JOIN rh_retencionjudicialconceptos rjc ON (rj.CodRetencion = rjc.CodRetencion AND
																		 tnec.CodOrganismo = rjc.CodOrganismo AND
																		 tnec.CodConcepto = rjc.CodConcepto)
						INNER JOIN mastpersonas mp2 ON (rj.Demandante = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
						LEFT JOIN pv_partida pv ON (cpd.cod_partida = pv.cod_partida)
					WHERE
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' $filtro_transferidos3
					GROUP BY rj.Demandante, cpd.cod_partida";
		}
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$Linea=0;
		while ($field = mysql_fetch_array($query)) {	$Linea++;
			unset($field_descuento);
			unset($field_adelanto);
			//	obtengo retenciones y descuentos
			if ($TipoObligacion == "01" || $TipoObligacion == "02") {
				//	descuento
				$sql = "SELECT SUM(tnec2.Monto) AS MontoDescuento
						FROM
							pr_tiponominaempleadoconcepto tnec2
							INNER JOIN tiponomina tn2 ON (tnec2.CodTipoNom = tn2.CodTipoNom)
							INNER JOIN pr_tipoproceso tp2 ON (tnec2.CodTipoProceso = tp2.CodTipoProceso)
							INNER JOIN mastorganismos o2 ON (tnec2.CodOrganismo = o2.CodOrganismo)
							INNER JOIN mastpersonas mp21 ON (tnec2.CodPersona = mp21.CodPersona)
							INNER JOIN mastempleado me2 ON (mp21.CodPersona = me2.CodPersona)
							INNER JOIN mastpersonas mp22 ON (o2.CodPersona = mp22.CodPersona)
							INNER JOIN mastproveedores p2 ON (mp22.CodPersona = p2.CodProveedor)
							INNER JOIN pr_concepto c2 ON (tnec2.CodConcepto = c2.CodConcepto)
							INNER JOIN pr_conceptoperfil cp2 ON (tn2.CodPerfilConcepto = cp2.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd2 ON (cp2.CodPerfilConcepto = cpd2.CodPerfilConcepto AND
																		 tnec2.CodTipoProceso = cpd2.CodTipoProceso AND
																		 tnec2.CodConcepto = cpd2.CodConcepto)
						WHERE
							tnec2.CodTipoNom = '".$CodTipoNom."' AND
							tnec2.Periodo = '".$Periodo."' AND
							tnec2.CodOrganismo = '".$CodOrganismo."' AND
							tnec2.CodTipoProceso = '".$CodTipoProceso."' AND
							tnec2.CodPersona = '".$field['CodProveedor']."' AND
							cpd2.cod_partida = '".$field['cod_partida']."' AND
							me2.CodTipoPago = '".$CodTipoPago."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N'
						GROUP BY tnec2.CodOrganismo";
				$query_descuento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_descuento) != 0) $field_descuento = mysql_fetch_array($query_descuento);
				//	si el proceso es fin de mes
				if ($CodTipoProceso == "FIN") {
					$sql = "SELECT SUM(tnec.Monto) AS MontoAdelanto
							FROM
								pr_tiponominaempleadoconcepto tnec
								INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
								INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
								INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
								INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
								INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
								INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
								INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)    
								INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
								INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
								INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																			tnec.CodTipoProceso = cpd.CodTipoProceso AND
																			tnec.CodConcepto = cpd.CodConcepto)
								LEFT JOIN pv_partida pv ON (cpd.cod_partida = pv.cod_partida)
							WHERE
								tnec.CodTipoNom = '".$CodTipoNom."' AND
								tnec.Periodo = '".$Periodo."' AND
								tnec.CodOrganismo = '".$CodOrganismo."' AND
								tnec.CodPersona = '".$field['CodProveedor']."' AND
								tnec.CodTipoProceso = 'ADE' AND
								cpd.cod_partida = '".$field['cod_partida']."' AND
								me.CodTipoPago = '".$CodTipoPago."' AND
								c.Tipo = 'I'
							GROUP BY o.CodPersona, cpd.cod_partida";
					$query_adelanto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					if (mysql_num_rows($query_adelanto) != 0) $field_adelanto = mysql_fetch_array($query_adelanto);
				}
			}
			//	valido las cuentas
			if ($TipoObligacion == "04") {
				if ($field['CuentaDebe'] != "") $CodCuenta = $field['CuentaDebe'];
				else $CodCuenta = $field['CuentaHaber'];
				if ($field['CuentaDebePub20'] != "") $CodCuentaPub20 = $field['CuentaDebePub20'];
				else $CodCuentaPub20 = $field['CuentaHaberPub20'];
			} else {
				$CodCuenta = $field['CodCuenta'];
				$CodCuentaPub20 = $field['CodCuentaPub20'];
			}
			//	montos
			$cod_partida = $field['cod_partida'];
			$NroDocumento = $CodOrganismo.$PeriodoAnio.$PeriodoMes.$CodTipoNom.$CodTipoProceso.$TipoObligacion;
			$Monto = floatval($field['MontoIngreso']) - floatval($field_descuento['MontoDescuento']) - floatval($field_adelanto['MontoAdelanto']);
			//	inserto la cuenta
			$sql = "INSERT INTO pr_obligacionescuenta
					SET
						CodProveedor = '".$field['CodProveedor']."',
						CodTipoDocumento = '".$field['CodTipoDocumento']."',
						NroDocumento = '".$NroDocumento."',
						Linea = '".$Linea."',
						Descripcion = '".$field['Descripcion']."',
						Monto = '".$Monto."',
						CodCentroCosto = '".$_PARAMETRO['CCOSTOPR']."',
						CodCuenta = '".$CodCuenta."',
						CodCuentaPub20 = '".$CodCuentaPub20."',
						cod_partida = '".$field['cod_partida']."',
						FlagNoAfectoIGV = 'N',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	consulto las retenciones a insertar
		if ($TipoObligacion == "01" || $TipoObligacion == "02") {
			$sql = "SELECT
						mp1.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'01' AS Ficha,
						SUM(tnec.Monto) AS MontoImpuesto,
						c.CodConcepto AS CodRetencion,
						cpd.CuentaHaber AS CodCuenta,
						cpd.CuentaHaberPub20 AS CodCuentaPub20
					 FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
					 WHERE
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
						me.CodTipoPago = '".$CodTipoPago."' AND
						c.Tipo = 'D' AND
						c.FlagRetencion = 'S' $filtro_transferidos1
					 GROUP BY mp1.CodPersona, CodRetencion";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$Linea=0;
			while ($field = mysql_fetch_array($query)) {	$Linea++;
				$NroDocumento = $CodOrganismo.$PeriodoAnio.$PeriodoMes.$CodTipoNom.$CodTipoProceso.$TipoObligacion;
				//	inserto las retenciones
				$sql = "INSERT INTO pr_obligacionesretenciones
						SET
							CodProveedor = '".$field['CodProveedor']."',
							CodTipoDocumento = '".$field['CodTipoDocumento']."',
							NroDocumento = '".$NroDocumento."',
							Linea = '".$Linea."',
							CodConcepto = '".$field['CodRetencion']."',
							MontoImpuesto = '".$field['MontoImpuesto']."',
							MontoAfecto = '".$field['MontoAfecto']."',
							CodCuenta = '".$field['CodCuenta']."',
							CodCuentaPub20 = '".$field['CodCuentaPub20']."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	consolidar
	elseif ($accion == "consolidar") {
		mysql_query("BEGIN");
		//	-----------------
		list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
		$PeriodoActual = "$AnioActual-$MesActual";
		list($PeriodoAnio, $PeriodoMes) = split("[-]", $Periodo);
		
		//	consulto el proveedor del organismo
		$sql = "SELECT
					o.CodPersona,
					p.NomCompleto AS NomPersona
				FROM
					mastorganismos o
					INNER JOIN mastpersonas p ON (o.CodPersona = p.CodPersona)
				WHERE o.CodOrganismo = '".$CodOrganismo."'";
		$query_proveedor = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_proveedor) != 0) $field_proveedor = mysql_fetch_array($query_proveedor);
		else die("Debe asociar una Persona al Organismo para Consolidar.");

		if ($detalles_bancos != "") $detalles_obligacion = $detalles_bancos;
		elseif ($detalles_cheques != "") $detalles_obligacion = $detalles_cheques;
		elseif ($detalles_terceros != "") $detalles_obligacion = $detalles_terceros;
		
		$filtro = "";
		$detalles = split(";", $detalles_obligacion);
		foreach ($detalles as $detalle) {
			list($CodProveedor, $CodTipoDocumento, $NroDocumento, $TipoObligacion) = split("[_]", $detalle);
			//	verifico si la obligacion transferida a cxp esta anulada
			$sql = "SELECT FlagTransferido
					FROM pr_obligaciones
					WHERE
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."' AND
						FlagTransferido = 'S'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query) != 0) die("Algunas obligaciones seleccionadas deben ser calculadas nuevamente.");
			##
			if ($filtro != "") $filtro .= " OR ";
			$filtro .= "(CodProveedor = '".$CodProveedor."' AND
						 CodTipoDocumento = '".$CodTipoDocumento."' AND
						 NroDocumento = '".$NroDocumento."')";
		}
		
		//	consulto la tabla general
		$sql = "SELECT
					TipoObligacion,
					CodOrganismo,
					CodTipoNom,
					Periodo,
					PeriodoNomina,
					CodTipoProceso,
					CodTipoDocumento,
					NroDocumento,
					CodTipoPago,
					CodTipoServicio,
					CodProveedorPagar,
					NomProveedorPagar,
					Comentarios,
					ComentariosAdicional,
					CodCuenta,
					CodCuentaPub20,
					CodCentroCosto,
					SUM(MontoObligacion) AS MontoObligacion,
					SUM(MontoImpuestoOtros) AS MontoImpuestoOtros,
					SUM(MontoNoAfecto) AS MontoNoAfecto
				FROM pr_obligaciones
				WHERE $filtro
				GROUP BY CodTipoDocumento, NroDocumento";
		$query = mysql_query($sql) or die($sql.mysql_error());
		while($field = mysql_fetch_array($query)) {
			//	elimino las seleccionadas
			$sql = "DELETE FROM pr_obligaciones WHERE $filtro";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	consulto el numero de obligaciones que he consolidado
			$sql = "SELECT *
					FROM pr_obligaciones
					WHERE
						CodProveedor = '".$field_proveedor['CodPersona']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento LIKE '".$field['NroDocumento']."-%'";
			$query_numero = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			$rows = intval(mysql_num_rows($query_numero));	$rows++;
			
			//	obtengo algunos valores a insertar
			$NroDocumento = $field['NroDocumento']."-".$rows;
			$NroCuenta = getCuentaBancariaDefault($CodOrganismo, $field['CodTipoPago']);
			
			//	inserto la obligacion
			$sql = "INSERT INTO pr_obligaciones
					SET
						TipoObligacion = '".$field['TipoObligacion']."',
						CodOrganismo = '".$field['CodOrganismo']."',
						CodTipoNom = '".$field['CodTipoNom']."',
						Periodo = '".$field['Periodo']."',
						PeriodoNomina = '".$field['PeriodoNomina']."',
						CodTipoProceso = '".$field['CodTipoProceso']."',
						CodProveedor = '".$field_proveedor['CodPersona']."',
						CodTipoDocumento = '".$field['CodTipoDocumento']."',
						NroDocumento = '".$NroDocumento."',
						NroControl = '".$NroDocumento."',
						NroCuenta = '".$NroCuenta."',
						CodTipoPago = '".$field['CodTipoPago']."',
						CodTipoServicio = '".$field['CodTipoServicio']."',
						FechaRegistro = NOW(),
						CodProveedorPagar = '".$field_proveedor['CodPersona']."',
						NomProveedorPagar = '".$field_proveedor['NomPersona']."',
						Comentarios = '".$field['Comentarios']."',
						ComentariosAdicional = '".$field['ComentariosAdicional']."',
						MontoObligacion = '".$field['MontoObligacion']."',
						MontoNoAfecto = '".$field['MontoNoAfecto']."',
						MontoImpuestoOtros = '".$field['MontoImpuestoOtros']."',
						CodCuenta = '".$field['CodCuenta']."',
						CodCuentaPub20 = '".$field['CodCuentaPub20']."',
						CodCentroCosto = '".$field['CodCentroCosto']."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo el payroll
			$sql = "UPDATE pr_tiponominaempleado
					SET
						CodProveedor = '".$field_proveedor['CodPersona']."',
						CodTipoDocumento = '".$field['CodTipoDocumento']."',
						NroDocumento = '".$NroDocumento."'
					WHERE 1 AND $filtro";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo prestaciones
			$sql = "UPDATE pr_liquidacionempleado
					SET
						CodProveedor = '".$field_proveedor['CodPersona']."',
						CodTipoDocumento = '".$field['CodTipoDocumento']."',
						NroDocumento = '".$NroDocumento."'
					WHERE 1 AND $filtro";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	consulto las cuentas
		$sql = "SELECT
					CodTipoDocumento,
					NroDocumento,
					CodCentroCosto,
					CodCuenta,
					CodCuentaPub20,
					cod_partida,
					FlagNoAfectoIGV,
					SUM(Monto) AS Monto
				FROM pr_obligacionescuenta
				WHERE $filtro
				GROUP BY cod_partida, CodCuentaPub20, CodCuenta, CodTipoDocumento, NroDocumento";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$i=0;
		while($field = mysql_fetch_array($query)) {	$i++;
			//	elimino las seleccionadas
			$sql = "DELETE FROM pr_obligacionescuenta WHERE $filtro";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
			//	inserto la obligacion x cuenta
			$sql = "INSERT INTO pr_obligacionescuenta
					SET
						CodProveedor = '".$field_proveedor['CodPersona']."',
						CodTipoDocumento = '".$field['CodTipoDocumento']."',
						NroDocumento = '".$NroDocumento."',
						Linea = '".$i."',
						CodCentroCosto = '".$field['CodCentroCosto']."',
						CodCuenta = '".$field['CodCuenta']."',
						CodCuentaPub20 = '".$field['CodCuentaPub20']."',
						cod_partida = '".$field['cod_partida']."',
						FlagNoAfectoIGV = '".$field['FlagNoAfectoIGV']."',
						Monto = '".$field['Monto']."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	consulto las retenciones
		$sql = "SELECT
					CodTipoDocumento,
					NroDocumento,
					CodConcepto,
					SUM(MontoImpuesto) AS MontoImpuesto,
					SUM(MontoAfecto) AS MontoAfecto,
					CodCuenta,
					CodCuentaPub20,
					FlagProvision
				FROM pr_obligacionesretenciones
				WHERE MontoImpuesto > 0 AND ($filtro)
				GROUP BY CodCuentaPub20, CodCuenta, CodTipoDocumento, NroDocumento";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$i=0;
		while($field = mysql_fetch_array($query)) {	$i++;
			//	elimino las seleccionadas
			$sql = "DELETE FROM pr_obligacionesretenciones WHERE $filtro";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
			//	inserto la obligacion retenciones
			$sql = "INSERT INTO pr_obligacionesretenciones
					SET
						CodProveedor = '".$field_proveedor['CodPersona']."',
						CodTipoDocumento = '".$field['CodTipoDocumento']."',
						NroDocumento = '".$NroDocumento."',
						Linea = '".$i."',
						CodConcepto = '".$field['CodConcepto']."',
						MontoImpuesto = '".$field['MontoImpuesto']."',
						MontoAfecto = '".$field['MontoAfecto']."',
						CodCuenta = '".$field['CodCuenta']."',
						CodCuentaPub20 = '".$field['CodCuentaPub20']."',
						FlagProvision = '".$field['FlagProvision']."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	verificar
	elseif ($accion == "verificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE pr_obligaciones
				SET
					FlagVerificado = 'S',
					VerificadoPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
					FechaVerificado = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	generar
	elseif ($accion == "generar") {
		mysql_query("BEGIN");
		//--------------------
		$MontoObligacion = setNumero($MontoObligacion);
		$MontoImpuestoOtros = setNumero($MontoImpuestoOtros);
		$MontoNoAfecto = setNumero($MontoNoAfecto);
		$MontoAfecto = setNumero($MontoAfecto);
		$MontoAdelanto = setNumero($MontoAdelanto);
		$MontoImpuesto = setNumero($MontoImpuesto);
		$MontoPagoParcial = setNumero($MontoPagoParcial);
		$Comentarios = changeUrl($Comentarios);
		$ComentariosAdicional = changeUrl($ComentariosAdicional);
		$MotivoAnulacion = changeUrl($MotivoAnulacion);
		$detalles_impuesto = changeUrl($detalles_impuesto);
		$detalles_documento = changeUrl($detalles_documento);
		$detalles_distribucion = changeUrl($detalles_distribucion);
		list($DiaObligacion, $MesObligacion, $AnioObligacion) = split("[./-]", $FechaRegistro);
		$Periodo = "$AnioObligacion-$MesObligacion";
		$Anio = $AnioObligacion;
		//	verifico valores ingresados
		if (valObligacion($CodProveedor, $CodTipoDocumento, $NroDocumento)) die("Nro. de Obligacion Ya ingresado");
		
		//	obtengo el numero de las ordenes
		$ReferenciaTipoDocumento = $CodTipoDocumento;
		$ReferenciaNroDocumento = $NroDocumento;
		
		//	inserto obligacion
		$NroRegistro = getCodigo_2("ap_obligaciones", "NroRegistro", "CodOrganismo", $CodOrganismo, 6);
		$sql = "INSERT INTO ap_obligaciones
				SET
					CodProveedor = '".$CodProveedor."',
					CodTipoDocumento = '".$CodTipoDocumento."',
					NroDocumento = '".$NroDocumento."',
					CodOrganismo = '".$CodOrganismo."',
					CodProveedorPagar = '".$CodProveedorPagar."',
					NroControl = '".$NroControl."',
					NroCuenta = '".$NroCuenta."',
					CodTipoPago = '".$CodTipoPago."',
					CodTipoServicio = '".$CodTipoServicio."',
					ReferenciaTipoDocumento = '".$ReferenciaTipoDocumento."',
					ReferenciaNroDocumento = '".$ReferenciaNroDocumento."',
					MontoObligacion = '".($MontoObligacion)."',
					MontoImpuestoOtros = '".($MontoImpuestoOtros)."',
					MontoNoAfecto = '".($MontoNoAfecto)."',
					MontoAfecto = '".($MontoAfecto)."',
					MontoAdelanto = '".($MontoAdelanto)."',
					MontoImpuesto = '".($MontoImpuesto)."',
					MontoPagoParcial = '".($MontoPagoParcial)."',
					NroRegistro = '".$NroRegistro."',
					Comentarios = '".$Comentarios."',
					ComentariosAdicional = '".$ComentariosAdicional."',
					FechaRegistro = '".formatFechaAMD($FechaRegistro)."',
					FechaVencimiento = '".formatFechaAMD($FechaVencimiento)."',
					FechaRecepcion = '".formatFechaAMD($FechaRecepcion)."',
					FechaDocumento = '".formatFechaAMD($FechaDocumento)."',
					FechaProgramada = '".formatFechaAMD($FechaProgramada)."',
					FechaFactura = '".formatFechaAMD($FechaFactura)."',
					IngresadoPor = '".($IngresadoPor)."',
					FechaPreparacion = '".formatFechaAMD($FechaPreparacion)."',
					Periodo = '".$Periodo."',
					CodCentroCosto = '".$CodCentroCosto."',
					FlagGenerarPago = '".$FlagGenerarPago."',
					FlagAfectoIGV = '".$FlagAfectoIGV."',
					FlagDiferido = '".$FlagDiferido."',
					FlagPagoDiferido = '".$FlagPagoDiferido."',
					FlagCompromiso = '".$FlagCompromiso."',
					FlagPresupuesto = '".$FlagPresupuesto."',
					FlagPagoIndividual = '".$FlagPagoIndividual."',
					FlagCajaChica = '".$FlagCajaChica."',
					FlagDistribucionManual = '".$FlagDistribucionManual."',
					CodPresupuesto = '".$CodPresupuesto."',
					FlagNomina = '".$FlagNomina."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo pr_obligacion
		$sql = "UPDATE pr_obligaciones
				SET
					NroRegistro = '".$NroRegistro."',
					FlagTransferido = 'S'
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo payroll
		$sql = "UPDATE pr_tiponominaempleado
				SET EstadoPago = 'TR'
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo prestaciones
		$sql = "UPDATE pr_liquidacionempleado
				SET EstadoPago = 'TR'
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	impuestos
		if ($detalles_impuesto != "") {
			$linea_impuesto = split(";char:tr;", $detalles_impuesto);	$_Linea=0;
			foreach ($linea_impuesto as $registro) {	$_Linea++;
				list($_CodImpuesto, $_CodConcepto, $_Signo, $_FlagImponible, $_FlagProvision, $_CodCuenta, $_CodCuentaPub20, $_MontoAfecto, $_FactorPorcentaje, $_MontoImpuesto) = split(";char:td;", $registro);
				//	inserto
				$sql = "INSERT INTO ap_obligacionesimpuesto
						SET
							CodProveedor = '".$CodProveedor."',
							CodTipoDocumento = '".$CodTipoDocumento."',
							NroDocumento = '".$NroDocumento."',
							Linea = '".$_Linea."',
							CodImpuesto = '".$_CodImpuesto."',
							CodConcepto = '".$_CodConcepto."',
							FactorPorcentaje = '".$_FactorPorcentaje."',
							MontoImpuesto = '".$_MontoImpuesto."',
							MontoAfecto = '".$_MontoAfecto."',
							CodCuenta = '".$_CodCuenta."',
							CodCuentaPub20 = '".$_CodCuentaPub20."',
							FlagProvision = '".$_FlagProvision."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	distribucion
		if ($detalles_distribucion != "") {
			$linea_distribucion = split(";char:tr;", $detalles_distribucion);	$_Secuencia=0;
			foreach ($linea_distribucion as $registro) {	$_Secuencia++;
				list($_cod_partida, $_CodCuenta, $_CodCuentaPub20, $_CodCentroCosto, $_FlagNoAfectoIGV, $_Monto, $_TipoOrden, $_NroOrden, $_Referencia, $_Descripcion, $_CodPersona, $_NroActivo, $_FlagDiferido) = split(";char:td;", $registro);
				//	inserto distribucion x cuentas
				$sql = "INSERT INTO ap_obligacionescuenta
						SET
							CodProveedor = '".$CodProveedor."',
							CodTipoDocumento = '".$CodTipoDocumento."',
							NroDocumento = '".$NroDocumento."',
							Secuencia = '".$_Secuencia."',
							Linea = '1',
							Descripcion = '".$_Descripcion."',
							Monto = '".$_Monto."',
							CodCentroCosto = '".$_CodCentroCosto."',
							CodCuenta = '".$_CodCuenta."',
							CodCuentaPub20 = '".$_CodCuentaPub20."',
							cod_partida = '".$_cod_partida."',
							TipoOrden = '".$_TipoOrden."',
							NroOrden = '".$_NroOrden."',
							FlagNoAfectoIGV = '".$_FlagNoAfectoIGV."',
							Referencia = '".$_Referencia."',
							CodPersona = '".$_CodPersona."',
							NroActivo = '".$_NroActivo."',
							FlagDiferido = '".$_FlagDiferido."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	resumen
		if ($FlagNomina == "S") $Origen = "NO"; else $Origen = "OB";
		if ($MontoImpuesto > 0) {
			list($_cod_partida_igv, $_CodCuenta_igv, $_CodCuentaPub20_igv) = getPartidaCuentaFromIGV($_PARAMETRO["IGVCODIGO"]);
			$sql = "(SELECT
						SUM(Monto) AS Monto,
						cod_partida,
						CodCuenta,
						CodCuentaPub20,
						CodCentroCosto
					FROM ap_obligacionescuenta
					WHERE
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'
					GROUP BY cod_partida, CodCuenta, CodCuentaPub20)
					UNION
					(SELECT
						'".($MontoImpuesto)."' AS Monto,
						'".$_cod_partida_igv."' AS cod_partida,
						'".$_CodCuenta_igv."' AS CodCuenta,
						'".$_CodCuentaPub20_igv."' AS CodCuentaPub20,
						'".$CodCentroCosto."' AS CodCentroCosto)";
		} else {
			$sql = "SELECT
						SUM(Monto) AS Monto,
						cod_partida,
						CodCuenta,
						CodCuentaPub20,
						CodCentroCosto
					FROM ap_obligacionescuenta
					WHERE
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'
					GROUP BY cod_partida, CodCuenta, CodCuentaPub20";
		}
		$query_res = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$_Secuencia = 0;
		while ($field_res = mysql_fetch_array($query_res)) {
			if ($FlagCompromiso == "S") {	$_Secuencia++;
				//	inserto en distribucion compromisos
				$sql = "INSERT INTO lg_distribucioncompromisos
						SET
							CodOrganismo = '".$CodOrganismo."',
							CodProveedor = '".$CodProveedor."',
							CodTipoDocumento = '".$CodTipoDocumento."',
							NroDocumento = '".$NroDocumento."',
							Secuencia = '".$_Secuencia."',
							Linea = '1',
							CodCentroCosto = '".$field_res['CodCentroCosto']."',
							cod_partida = '".$field_res['cod_partida']."',
							Monto = '".$field_res['Monto']."',
							Anio = '".$Anio."',
							Periodo = '".$Periodo."',
							Mes = '".substr($Periodo, 5, 2)."',
							CodPresupuesto = '".$CodPresupuesto."',
							Origen = '".$Origen."',
							Estado = 'PE',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	inserto en la distribucion
			$sql = "INSERT INTO ap_distribucionobligacion
					SET
						CodProveedor = '".$CodProveedor."',
						CodTipoDocumento = '".$CodTipoDocumento."',
						NroDocumento = '".$NroDocumento."',
						CodCentroCosto = '".$field_res['CodCentroCosto']."',
						Monto = '".$field_res['Monto']."',
						CodCuenta = '".$field_res['CodCuenta']."',
						CodCuentaPub20 = '".$field_res['CodCuentaPub20']."',
						cod_partida = '".$field_res['cod_partida']."',
						Anio = '".$Anio."',
						Periodo = '".$Periodo."',
						CodPresupuesto = '".$CodPresupuesto."',
						FlagCompromiso = '".$FlagCompromiso."',
						Origen = 'OB',
						Estado = 'PE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//--------------------
		mysql_query("COMMIT");
	}
}

//	conceptos
elseif ($modulo == "conceptos") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	genero codigo
		$CodConcepto = getCodigo("pr_concepto", "CodConcepto", 4);
		
		//	inserto
		$sql = "INSERT INTO pr_concepto
				SET
					CodConcepto = '".$CodConcepto."',
					Descripcion = '".changeUrl($Descripcion)."',
					Tipo = '".$Tipo."',
					TextoImpresion = '".changeUrl($TextoImpresion)."',
					PlanillaOrden = '".$PlanillaOrden."',
					Formula = '".changeUrl($Formula)."',
					FormulaEditor = '".changeUrl($FormulaEditor)."',
					FlagFormula = '".$FlagFormula."',
					FlagAutomatico = '".$FlagAutomatico."',
					Abreviatura = '".changeUrl($Abreviatura)."',
					FlagBono = '".$FlagBono."',
					FlagRetencion = '".$FlagRetencion."',
					FlagObligacion = '".$FlagObligacion."',
					CodPersona = '".$CodPersona."',
					FlagBonoRemuneracion = '".$FlagBonoRemuneracion."',
					FlagRelacionIngreso = '".$FlagRelacionIngreso."',
					FlagJubilacion = '".$FlagJubilacion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	tipos de nomina
		if ($detalles_nominas != "") {
			$nominas = split(";char:tr;", $detalles_nominas);
			foreach ($nominas as $_CodTipoNom) {
				//	inserto
				$sql = "INSERT INTO pr_conceptotiponomina
						SET
							CodConcepto = '".$CodConcepto."',
							CodTipoNom = '".$_CodTipoNom."'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	tipos de proceso
		if ($detalles_procesos != "") {
			$procesos = split(";char:tr;", $detalles_procesos);
			foreach ($procesos as $_CodTipoProceso) {
				//	inserto
				$sql = "INSERT INTO pr_conceptoproceso
						SET
							CodConcepto = '".$CodConcepto."',
							CodTipoProceso = '".$_CodTipoProceso."'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizar
		$sql = "UPDATE pr_concepto
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					Tipo = '".$Tipo."',
					TextoImpresion = '".changeUrl($TextoImpresion)."',
					PlanillaOrden = '".$PlanillaOrden."',
					Formula = '".changeUrl($Formula)."',
					FormulaEditor = '".changeUrl($FormulaEditor)."',
					FlagFormula = '".$FlagFormula."',
					FlagAutomatico = '".$FlagAutomatico."',
					Abreviatura = '".changeUrl($Abreviatura)."',
					FlagBono = '".$FlagBono."',
					FlagRetencion = '".$FlagRetencion."',
					FlagObligacion = '".$FlagObligacion."',
					CodPersona = '".$CodPersona."',
					FlagBonoRemuneracion = '".$FlagBonoRemuneracion."',
					FlagRelacionIngreso = '".$FlagRelacionIngreso."',
					FlagJubilacion = '".$FlagJubilacion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodConcepto = '".$CodConcepto."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	tipos de nomina
		$sql = "DELETE FROM pr_conceptotiponomina WHERE CodConcepto = '".$CodConcepto."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($detalles_nominas != "") {
			$nominas = split(";char:tr;", $detalles_nominas);
			foreach ($nominas as $_CodTipoNom) {
				//	inserto
				$sql = "INSERT INTO pr_conceptotiponomina
						SET
							CodConcepto = '".$CodConcepto."',
							CodTipoNom = '".$_CodTipoNom."'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	tipos de proceso
		$sql = "DELETE FROM pr_conceptoproceso WHERE CodConcepto = '".$CodConcepto."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($detalles_procesos != "") {
			$procesos = split(";char:tr;", $detalles_procesos);
			foreach ($procesos as $_CodTipoProceso) {
				//	inserto
				$sql = "INSERT INTO pr_conceptoproceso
						SET
							CodConcepto = '".$CodConcepto."',
							CodTipoProceso = '".$_CodTipoProceso."'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	-----------------
		//	elimino
		$sql = "DELETE FROM pr_concepto WHERE CodConcepto = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	perfil de conceptos
elseif ($modulo == "conceptos_perfil") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	genero codigo
		$CodPerfilConcepto = getCodigo("pr_conceptoperfil", "CodPerfilConcepto", 4);
		
		//	inserto
		$sql = "INSERT INTO pr_conceptoperfil
				SET
					CodPerfilConcepto = '".$CodPerfilConcepto."',
					Descripcion = '".changeUrl($Descripcion)."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizar
		$sql = "UPDATE pr_conceptoperfil
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodPerfilConcepto = '".$CodPerfilConcepto."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	perfil de conceptos
	elseif ($accion == "conceptos") {
		mysql_query("BEGIN");
		//	-----------------
		//	conceptos
		$sql = "DELETE FROM pr_conceptoperfildetalle WHERE CodPerfilConcepto = '".$CodPerfilConcepto."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($detalles_conceptos != "") {
			$conceptos = split(";char:tr;", $detalles_conceptos);
			foreach ($conceptos as $_linea) {
				list($_CodTipoProceso, $_CodConcepto, $_cod_partida, $_CuentaDebe, $_CuentaDebePub20, $_FlagDebeCC, $_CuentaHaber, $_CuentaHaberPub20, $_FlagHaberCC) = split(";char:td;", $_linea);
				//	inserto
				$sql = "INSERT INTO pr_conceptoperfildetalle
						SET
							CodPerfilConcepto = '".$CodPerfilConcepto."',
							CodTipoProceso = '".$_CodTipoProceso."',
							CodConcepto = '".$_CodConcepto."',
							cod_partida = '".$_cod_partida."',
							CuentaDebe = '".$_CuentaDebe."',
							CuentaDebePub20 = '".$_CuentaDebePub20."',
							FlagDebeCC = '".$_FlagDebeCC."',
							CuentaHaber = '".$_CuentaHaber."',
							CuentaHaberPub20 = '".$_CuentaHaberPub20."',
							FlagHaberCC = '".$_FlagHaberCC."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	-----------------
		//	elimino
		$sql = "DELETE FROM pr_conceptoperfil WHERE CodPerfilConcepto = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	control de procesos
elseif ($modulo == "procesos_control") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto
		$sql = "INSERT INTO pr_procesoperiodo
				SET
					CodOrganismo = '".$CodOrganismo."',
					CodTipoNom = '".$CodTipoNom."',
					Periodo = '".$Periodo."',
					CodTipoProceso = '".$CodTipoProceso."',
					PeriodoNomina = '".$PeriodoNomina."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					CreadoPor = '".$CreadoPor."',
					FechaCreado = '".formatFechaAMD($FechaCreado)."',
					FlagProcesado = 'N',
					FlagAprobado = 'N',
					FlagMensual = '".$FlagMensual."',
					FlagPagado = 'N',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizar
		$sql = "UPDATE pr_procesoperiodo
				SET
					PeriodoNomina = '".$PeriodoNomina."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					FlagMensual = '".$FlagMensual."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodTipoNom = '".$CodTipoNom."' AND
					Periodo = '".$Periodo."' AND
					CodTipoProceso = '".$CodTipoProceso."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizar
		$sql = "UPDATE pr_procesoperiodo
				SET
					AprobadoPor = '".$AprobadoPor."',
					FechaAprobado = '".formatFechaAMD($FechaAprobado)."',
					FlagAprobado = 'S',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodTipoNom = '".$CodTipoNom."' AND
					Periodo = '".$Periodo."' AND
					CodTipoProceso = '".$CodTipoProceso."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	activar/desactivar
	elseif ($accion == "activar") {
		mysql_query("BEGIN");
		//	-----------------
		list($CodOrganismo, $CodTipoNom, $Periodo, $CodTipoProceso) = split("[_]", $registro);
		//	consulto estado
		$sql = "SELECT Estado
				FROM pr_procesoperiodo
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodTipoNom = '".$CodTipoNom."' AND
					Periodo = '".$Periodo."' AND
					CodTipoProceso = '".$CodTipoProceso."'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query)) {
			$field = mysql_fetch_array($query);
			if ($field['Estado'] == "A") $Estado = "I"; else $Estado = "A";
			//	actualizo
			$sql = "UPDATE pr_procesoperiodo
					SET Estado = '".$Estado."'
					WHERE
						CodOrganismo = '".$CodOrganismo."' AND
						CodTipoNom = '".$CodTipoNom."' AND
						Periodo = '".$Periodo."' AND
						CodTipoProceso = '".$CodTipoProceso."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		} else die("No se encontr&oacute; el registro");
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	ejecucion de procesos
elseif ($modulo == "procesos_control_ejecucion") {
	//	nuevo
	if ($accion == "ejecutar") {
		mysql_query("BEGIN");
		//	-----------------
		include("funciones_globales_nomina.php");
		##	parametros
		$_PARAMETROS_FORMULA = PARAMETROS_FORMULA();
		extract($_PARAMETROS_FORMULA);
		##	variables generales
		$ALIVAC = $_PARAMETRO['ALIVAC'];
		$ALIFIN = $_PARAMETRO['ALIFIN'];
		$_ARGS['_ORGANISMO'] = $fCodOrganismo;
		$_ARGS['_NOMINA'] = $fCodTipoNom;
		$_ARGS['_PERIODO'] = $fPeriodo;
		$_ARGS['_PROCESO'] = $fCodTipoProceso;
		$_ARGS['_PERIODONOMINA'] = getVar2("pr_procesoperiodo","PeriodoNomina",array('CodOrganismo','CodTipoNom','Periodo','CodTipoProceso'),array($fCodOrganismo,$fCodTipoNom,$fPeriodo,$fCodTipoProceso));
		$_ARGS['_RETROACTIVO'] = getVar3("SELECT FlagRetroactivo FROM pr_tipoproceso WHERE CodTipoproceso = '".$_ARGS['_PROCESO']."'");
		list($_ARGS['_DESDE'], $_ARGS['_HASTA']) = FECHA_PROCESO($_ARGS);
		$_ARGS['_DIAS'] = DIAS_PROCESO($_ARGS);
		list($_ARGS['_ANO_PROCESO'], $_ARGS['_MES_PROCESO']) = split("[./-]", $_ARGS['_PERIODO']);
		//	empleados
		if ($detalles_personas != "") {
			//	proceso
			if ($PreNomina == "S") {
				$sql = "UPDATE pr_procesoperiodoprenomina
						SET
							FlagProcesado = 'S',
							ProcesadoPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
							FechaProceso = NOW(),
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							CodTipoNom = '".$fCodTipoNom."' AND 
							Periodo = '".$fPeriodo."' AND 
							CodOrganismo = '".$fCodOrganismo."' AND 
							CodTipoProceso = '".$fCodTipoProceso."'";
			} else {
				$sql = "UPDATE pr_procesoperiodo
						SET
							FlagProcesado = 'S',
							ProcesadoPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
							FechaProceso = NOW(),
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							CodTipoNom = '".$fCodTipoNom."' AND 
							Periodo = '".$fPeriodo."' AND 
							CodOrganismo = '".$fCodOrganismo."' AND 
							CodTipoProceso = '".$fCodTipoProceso."'";
			}
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	empleados selecionados
			$personas = split(";char:tr;", $detalles_personas);
			foreach ($personas as $CodPersona) {
				//	elimino datos anteriores
				if ($PreNomina == "S") {
					$sql = "DELETE FROM pr_tiponominaempleadoconceptoprenomina
							WHERE
								CodPersona = '".$CodPersona."' AND 
								CodTipoNom = '".$fCodTipoNom."' AND 
								Periodo = '".$fPeriodo."' AND 
								CodOrganismo = '".$fCodOrganismo."' AND 
								CodTipoProceso = '".$fCodTipoProceso."'";
					$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					##
					$sql = "DELETE FROM pr_tiponominaempleadoprenomina
							WHERE
								CodPersona = '".$CodPersona."' AND
								CodTipoNom = '".$fCodTipoNom."' AND
								Periodo = '".$fPeriodo."' AND
								CodOrganismo = '".$fCodOrganismo."' AND
								CodTipoProceso = '".$fCodTipoProceso."'";
					$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					$sql = "DELETE FROM pr_tiponominaempleadoconcepto
							WHERE
								CodPersona = '".$CodPersona."' AND 
								CodTipoNom = '".$fCodTipoNom."' AND 
								Periodo = '".$fPeriodo."' AND 
								CodOrganismo = '".$fCodOrganismo."' AND 
								CodTipoProceso = '".$fCodTipoProceso."'";
					$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					##
					$sql = "DELETE FROM pr_tiponominaempleado
							WHERE
								CodPersona = '".$CodPersona."' AND
								CodTipoNom = '".$fCodTipoNom."' AND
								Periodo = '".$fPeriodo."' AND
								CodOrganismo = '".$fCodOrganismo."' AND
								CodTipoProceso = '".$fCodTipoProceso."'";
					$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					##	interfase ctas x pagar
					$sql = "SELECT
								CodProveedor,
								CodTipoDocumento,
								NroDocumento
							FROM pr_obligaciones
							WHERE
								CodTipoNom = '".$fCodTipoNom."' AND
								PeriodoNomina = '".$fPeriodo."' AND
								CodTipoProceso = '".$fCodTipoProceso."' AND
								CodOrganismo = '".$fCodOrganismo."' AND
								Estado = 'PE'";
					$query_probligacion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					while($field_probligacion = mysql_fetch_array($query_probligacion)) {
						$sql = "DELETE FROM pr_obligacionescuenta
								WHERE
									CodProveedor = '".$field_probligacion['CodProveedor']."' AND
									CodTipoDocumento = '".$field_probligacion['CodTipoDocumento']."' AND
									NroDocumento = '".$field_probligacion['NroDocumento']."'";
						$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
						##
						$sql = "DELETE FROM pr_obligacionesretenciones
								WHERE
									CodProveedor = '".$field_probligacion['CodProveedor']."' AND
									CodTipoDocumento = '".$field_probligacion['CodTipoDocumento']."' AND
									NroDocumento = '".$field_probligacion['NroDocumento']."'";
						$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					}
					##
					$sql = "DELETE FROM pr_obligaciones
							WHERE
								CodTipoNom = '".$fCodTipoNom."' AND
								PeriodoNomina = '".$fPeriodo."' AND
								CodTipoProceso = '".$fCodTipoProceso."' AND
								CodOrganismo = '".$fCodOrganismo."' AND
								Estado = 'PE'";
					$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
				//	consulto empleado
				$sql = "SELECT
							p.CodPersona,
							e.CodEmpleado,
							e.Fingreso,
							e.Estado,
							e.Fegreso,
							e.CodTipoPago,
							e.CodCargo,
							e.CodMotivoCes,
							e.ObsCese,
							e.SueldoActual,
							bp.Ncuenta,
							bp.CodBanco,
							bp.TipoCuenta
						FROM
							mastpersonas p
							INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
							LEFT JOIN bancopersona bp ON (e.CodPersona = bp.CodPersona AND FlagPrincipal = 'S')
						WHERE p.CodPersona = '".$CodPersona."'";
				$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_empleado)) $field_empleado = mysql_fetch_array($query_empleado);
				##	variables empleados
				$_ARGS['_PERSONA'] = $CodPersona;
				$_ARGS['_EMPLEADO'] = $field_empleado['CodEmpleado'];
				$_ARGS['_FECHA_INGRESO'] = $field_empleado['Fingreso'];
				list($_ARGS['_ANO_INGRESO'], $_ARGS['_MES_INGRESO'], $_ARGS['_DIA_INGRESO']) = split("[./-]", $_ARGS['_FECHA_INGRESO']);
				$_ARGS['_FECHA_EGRESO'] = $field_empleado['Fegreso'];
				list($_ARGS['_ANO_EGRESO'], $_ARGS['_MES_EGRESO'], $_ARGS['_DIA_EGRESO']) = split("[./-]", $_ARGS['_FECHA_EGRESO']);
				$_ARGS['_ESTADO'] = $field_empleado['Estado'];
				$_ARGS['_TIPO_PAGO'] = $field_empleado['CodTipoPago'];
				$_ARGS['_CTA_BANCARIA'] = $field_empleado['Ncuenta'];
				$_ARGS['_BANCO'] = $field_empleado['CodBanco'];
				$_ARGS['_TIPO_CUENTA'] = $field_empleado['TipoCuenta'];
				$_ARGS['_CARGO'] = $field_empleado['CodCargo'];
				$_ARGS['_MOTIVO_CESE'] = $field_empleado['CodMotivoCes'];
				$_ARGS['_SUELDO_ACTUAL'] = $field_empleado['SueldoActual'];
				$_ARGS['_SUELDO_BASICO'] = SUELDO_BASICO();
				$_ARGS['_SUELDO_BASICO_DIARIO'] = round(($_ARGS['_SUELDO_BASICO'] / 30), 2);
				$_ARGS['_DIAS_SUELDO_BASICO'] = DIAS_SUELDO_BASICO();
				$_ARGS['_SUELDO_NORMAL'] = 0;
				$_ARGS['_SUELDO_NORMAL_DIARIO'] = 0;
				$_ARGS['_SUELDO_INTEGRAL'] = 0;
				$_ARGS['_SUELDO_INTEGRAL_DIARIO'] = 0;
				$_ARGS['_ASIGNACIONES'] = 0;
				$_ARGS['_PROVISIONES'] = 0;
				$_ARGS['_DEDUCCIONES'] = 0;
				$_ARGS['_APORTES'] = 0;
				unset($_CONCEPTO);
				//	consulto los conceptos
				$sql = "(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							pec.Monto,
							pec.Cantidad,
							'1' AS Orden,
							pec.FlagManual
						 FROM
							pr_empleadoconcepto pec
							INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$_ARGS['_NOMINA']."' )
						 WHERE
							(pec.Estado = 'A' AND pc.Estado = 'A') AND
							(pc.Tipo = 'I') AND	(pec.CodPersona = '".$CodPersona."') AND
							(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['_PROCESO']."%') AND
							((pec.TipoAplicacion = 'T' AND 
							  pec.PeriodoHasta >= '".$_ARGS['_PERIODO']."' AND 
							  pec.PeriodoDesde <= '".$_ARGS['_PERIODO']."') OR 
							  (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$_ARGS['_PERIODO']."'))
						 GROUP BY CodConcepto)
						UNION
						(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							'' AS Monto,
							'' AS Cantidad,
							'1' AS Orden,
							'N' AS FlagManual
						 FROM
							pr_concepto pc
							INNER JOIN pr_conceptoproceso pcp ON (pc.CodConcepto = pcp.CodConcepto AND 
																  pcp.CodTipoProceso = '".$_ARGS['_PROCESO']."')
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND 
																	  pctn.CodTipoNom = '".$_ARGS['_NOMINA']."' )
						 WHERE
							(pc.Estado = 'A') AND
							 pc.Tipo = 'I' AND pc.FlagAutomatico = 'S' AND 
							 pc.CodConcepto NOT IN (SELECT CodConcepto FROM pr_empleadoconcepto WHERE CodPersona = '".$CodPersona."')
						 GROUP BY CodConcepto)
						UNION
						(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							pec.Monto,
							pec.Cantidad,
							'2' AS Orden,
							pec.FlagManual
						FROM
							pr_empleadoconcepto pec
							INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$_ARGS['_NOMINA']."' )
						WHERE
							(pec.Estado = 'A' AND pc.Estado = 'A') AND
							(pc.Tipo = 'P') AND	(pec.CodPersona = '".$CodPersona."') AND
							(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['_PROCESO']."%') AND
							((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$_ARGS['_PERIODO']."' AND pec.PeriodoDesde <= '".$_ARGS['_PERIODO']."') OR 
							 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$_ARGS['_PERIODO']."'))
						GROUP BY CodConcepto)
						UNION
						(SELECT
								pc.CodConcepto,
								pc.Descripcion,
								pc.PlanillaOrden,
								pc.FlagAutomatico,
								pc.Formula,
								pc.Tipo,
								pc.FlagBono,
								'' AS Monto,
								'' AS Cantidad,
								'2' AS Orden,
								'N' AS FlagManual
							FROM
								pr_concepto pc
								INNER JOIN pr_conceptoproceso pcp ON (pc.CodConcepto = pcp.CodConcepto AND pcp.CodTipoProceso = '".$_ARGS['_PROCESO']."')
								INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$_ARGS['_NOMINA']."' )
							WHERE
								(pc.Estado = 'A') AND
								pc.Tipo = 'P' AND pc.FlagAutomatico = 'S' AND 
								pc.CodConcepto NOT IN (SELECT CodConcepto FROM pr_empleadoconcepto WHERE CodPersona = '".$CodPersona."')
							GROUP BY CodConcepto)
						UNION
						(SELECT
								pc.CodConcepto,
								pc.Descripcion,
								pc.PlanillaOrden,
								pc.FlagAutomatico,
								pc.Formula,
								pc.Tipo,
								pc.FlagBono,
								pec.Monto,
								pec.Cantidad,
								'3' AS Orden,
							pec.FlagManual
							FROM
								pr_empleadoconcepto pec
								INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
								INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$_ARGS['_NOMINA']."' )
							WHERE
								(pec.Estado = 'A' AND pc.Estado = 'A') AND
								(pc.Tipo = 'D') AND	(pec.CodPersona = '".$CodPersona."') AND
								(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['_PROCESO']."%') AND
								((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$_ARGS['_PERIODO']."' AND pec.PeriodoDesde <= '".$_ARGS['_PERIODO']."') OR 
								 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$_ARGS['_PERIODO']."'))
							GROUP BY CodConcepto)
						UNION
						(SELECT
								pc.CodConcepto,
								pc.Descripcion,
								pc.PlanillaOrden,
								pc.FlagAutomatico,
								pc.Formula,
								pc.Tipo,
								pc.FlagBono,
								'' AS Monto,
								'' AS Cantidad,
								'3' AS Orden,
								'N' AS FlagManual
							FROM
								pr_concepto pc
								INNER JOIN pr_conceptoproceso pcp ON (pc.CodConcepto = pcp.CodConcepto AND pcp.CodTipoProceso = '".$_ARGS['_PROCESO']."')
								INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$_ARGS['_NOMINA']."' )
							WHERE
								(pc.Estado = 'A') AND
								pc.Tipo = 'D' AND pc.FlagAutomatico = 'S' AND 
								pc.CodConcepto NOT IN (SELECT CodConcepto FROM pr_empleadoconcepto WHERE CodPersona = '".$CodPersona."')
							GROUP BY CodConcepto)
						UNION
						(SELECT
								pc.CodConcepto,
								pc.Descripcion,
								pc.PlanillaOrden,
								pc.FlagAutomatico,
								pc.Formula,
								pc.Tipo,
								pc.FlagBono,
								pec.Monto,
								pec.Cantidad,
								'4' AS Orden,
							pec.FlagManual
							FROM
								pr_empleadoconcepto pec
								INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
								INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$_ARGS['_NOMINA']."' )
							WHERE
								(pec.Estado = 'A' AND pc.Estado = 'A') AND
								(pc.Tipo = 'A') AND	(pec.CodPersona = '".$CodPersona."') AND
								(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['_PROCESO']."%') AND
								((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$_ARGS['_PERIODO']."' AND pec.PeriodoDesde <= '".$_ARGS['_PERIODO']."') OR 
								 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$_ARGS['_PERIODO']."'))
							GROUP BY CodConcepto)
						UNION
						(SELECT
								pc.CodConcepto,
								pc.Descripcion,
								pc.PlanillaOrden,
								pc.FlagAutomatico,
								pc.Formula,
								pc.Tipo,
								pc.FlagBono,
								'' AS Monto,
								'' AS Cantidad,
								'4' AS Orden,
								'N' AS FlagManual
							FROM
								pr_concepto pc
								INNER JOIN pr_conceptoproceso pcp ON (pc.CodConcepto = pcp.CodConcepto AND pcp.CodTipoProceso = '".$_ARGS['_PROCESO']."')
								INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$_ARGS['_NOMINA']."' )
							WHERE
								(pc.Estado = 'A') AND
								pc.Tipo = 'A' AND pc.FlagAutomatico = 'S' AND 
								pc.CodConcepto NOT IN (SELECT CodConcepto FROM pr_empleadoconcepto WHERE CodPersona = '".$CodPersona."')
							GROUP BY CodConcepto)
						ORDER BY Orden, PlanillaOrden";
				$query_conceptos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				while ($field_conceptos = mysql_fetch_array($query_conceptos)) {
					##	variables conceptos
					$ID = $field_conceptos['CodConcepto'];
					$_ARGS['_CONCEPTO'] = $field_conceptos['CodConcepto'];
					$_ARGS['_TIPO_CONCEPTO'] = $field_conceptos['Tipo'];
					$_C = "\$C_".$_ARGS['_CONCEPTO'];
					//	si tiene formula
					if (trim($field_conceptos['Formula']) != "" && $field_conceptos['FlagManual'] != "S") {
						##	variables conceptos
						$_ARGS['_FORMULA'] = $field_conceptos['Formula'];
						$_ARGS['_FLAGBONO'] = $field_conceptos['FlagBono'];
						$_ARGS['_MONTO'] = 0;
						$_ARGS['_CANTIDAD'] = 0;
						$_MONTO = 0;
						$_CANTIDAD = 0;
						//	ejecuto
						extract($_ARGS);
						eval($field_conceptos['Formula']);
						$_ARGS['_MONTO'] = round($_MONTO, 2);
						$_ARGS['_CANTIDAD'] = round($_CANTIDAD, 2);
					} else {
						$_ARGS['_MONTO'] = round($field_conceptos['Monto'], 2);
						$_ARGS['_CANTIDAD'] = round($field_conceptos['Cantidad'], 2);
					}
					##	valor del concepto
					$_VALOR[$ID] = $_ARGS['_MONTO'];
					$CONCEPTO = "$_C = $_ARGS[_MONTO];";
					eval($CONCEPTO);
					//echo "$_C = $_ARGS[_MONTO];<br />";
					//	inserto el concepto
					if ($_ARGS['_MONTO'] > 0) {
						##	sumadores de los totales por tipo
						if ($_ARGS['_TIPO_CONCEPTO'] == "I") {
							$_ARGS['_ASIGNACIONES'] += $_ARGS['_MONTO'];
							$_ARGS['_SUELDO_NORMAL'] += $_ARGS['_MONTO'];
							$_ARGS['_SUELDO_NORMAL_DIARIO'] = round(($_ARGS['_SUELDO_NORMAL'] / 30), 2);
						}
						elseif ($_ARGS['_TIPO_CONCEPTO'] == "P") {
							$_ARGS['_PROVISIONES'] += $_ARGS['_MONTO'];
							if ($_ARGS['_CONCEPTO'] == $_PARAMETRO['ALIVAC'] || $_ARGS['_CONCEPTO'] == $_PARAMETRO['ALIFIN']) {
								$_ARGS['_SUELDO_INTEGRAL'] = $_ARGS['_SUELDO_NORMAL'] + ($_VALOR[$ALIVAC] * 30) + ($_VALOR[$ALIFIN] * 30);
								$_ARGS['_SUELDO_INTEGRAL_DIARIO'] = round(($_ARGS['_SUELDO_INTEGRAL'] / 30), 2);
								$_ARGS['_SUELDO_INTEGRAL_PARCIAL'] = $_ARGS['_SUELDO_NORMAL'] + ($_VALOR[$ALIVAC] * 30);
								$_ARGS['_SUELDO_INTEGRAL_PARCIAL_DIARIO'] = round(($_ARGS['_SUELDO_INTEGRAL_PARCIAL'] / 30), 2);
							}
						}
						elseif ($_ARGS['_TIPO_CONCEPTO'] == "D") $_ARGS['_DEDUCCIONES'] += $_ARGS['_MONTO'];
						elseif ($_ARGS['_TIPO_CONCEPTO'] == "A") $_ARGS['_APORTES'] += $_ARGS['_MONTO'];
						
						//	inserto
						if ($PreNomina == "S") {
							$sql = "INSERT INTO pr_tiponominaempleadoconceptoprenomina
									SET
										CodTipoNom = '".$_ARGS['_NOMINA']."',
										Periodo = '".$_ARGS['_PERIODO']."',
										CodPersona = '".$_ARGS['_PERSONA']."',
										CodOrganismo = '".$_ARGS['_ORGANISMO']."',
										CodConcepto = '".$_ARGS['_CONCEPTO']."',
										CodTipoProceso = '".$_ARGS['_PROCESO']."',
										Monto = '".$_ARGS['_MONTO']."',
										Cantidad = '".$_ARGS['_CANTIDAD']."',
										UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										UltimaFecha = NOW()";
						} else {
							$sql = "INSERT INTO pr_tiponominaempleadoconcepto
									SET
										CodTipoNom = '".$_ARGS['_NOMINA']."',
										Periodo = '".$_ARGS['_PERIODO']."',
										CodPersona = '".$_ARGS['_PERSONA']."',
										CodOrganismo = '".$_ARGS['_ORGANISMO']."',
										CodConcepto = '".$_ARGS['_CONCEPTO']."',
										CodTipoProceso = '".$_ARGS['_PROCESO']."',
										Monto = '".$_ARGS['_MONTO']."',
										Cantidad = '".$_ARGS['_CANTIDAD']."',
										UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										UltimaFecha = NOW()";
						}
						$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					}
				}
				//	inserto empleado
				if ($_ARGS['_ASIGNACIONES'] > 0 || $_ARGS['_PROVISIONES'] > 0) {
					//	inserto
					$_ARGS['_TOTAL_NETO'] = $_ARGS['_ASIGNACIONES'] - $_ARGS['_DEDUCCIONES'];
					if ($PreNomina == "S") {
						$sql = "INSERT INTO pr_tiponominaempleadoprenomina
								SET
									CodTipoNom = '".$_ARGS['_NOMINA']."',
									Periodo = '".$_ARGS['_PERIODO']."',
									CodPersona = '".$_ARGS['_PERSONA']."',
									CodOrganismo = '".$_ARGS['_ORGANISMO']."',
									CodTipoProceso = '".$_ARGS['_PROCESO']."',
									SueldoBasico = '".$_ARGS['_SUELDO_ACTUAL']."',
									TotalIngresos = '".$_ARGS['_ASIGNACIONES']."',
									TotalEgresos = '".$_ARGS['_DEDUCCIONES']."',
									TotalPatronales = '".$_ARGS['_APORTES']."',
									TotalProvisiones = '".$_ARGS['_PROVISIONES']."',
									TotalNeto = '".$_ARGS['_TOTAL_NETO']."',
									GeneradoPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
									FechaGeneracion = NOW(),
									UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
									UltimaFecha = NOW()";
					} else {
						$sql = "INSERT INTO pr_tiponominaempleado
								SET
									CodTipoNom = '".$_ARGS['_NOMINA']."',
									Periodo = '".$_ARGS['_PERIODO']."',
									CodPersona = '".$_ARGS['_PERSONA']."',
									CodOrganismo = '".$_ARGS['_ORGANISMO']."',
									CodTipoProceso = '".$_ARGS['_PROCESO']."',
									SueldoBasico = '".$_ARGS['_SUELDO_ACTUAL']."',
									TotalIngresos = '".$_ARGS['_ASIGNACIONES']."',
									TotalEgresos = '".$_ARGS['_DEDUCCIONES']."',
									TotalPatronales = '".$_ARGS['_APORTES']."',
									TotalProvisiones = '".$_ARGS['_PROVISIONES']."',
									TotalNeto = '".$_ARGS['_TOTAL_NETO']."',
									CodBanco = '".$_ARGS['_BANCO']."',
									TipoCuenta = '".$_ARGS['_TIPO_CUENTA']."',
									Ncuenta = '".$_ARGS['_CTA_BANCARIA']."',
									CodTipoPago = '".$_ARGS['_TIPO_PAGO']."',
									GeneradoPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
									FechaGeneracion = NOW(),
									UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
									UltimaFecha = NOW()";
					}
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
				//	fin de mes
				if ($_ARGS['_PROCESO'] == "FIN" && $PreNomina != "S") {
					//	inserto en sueldos
					$sql = "INSERT INTO rh_sueldos
							SET
								CodPersona = '".$_ARGS['_PERSONA']."',
								Periodo = '".$_ARGS['_PERIODO']."',
								Sueldo = '".$_ARGS['_SUELDO_ACTUAL']."',
								SueldoNormal = '".$_ARGS['_SUELDO_NORMAL']."',
								SueldoIntegral = '".$_ARGS['_SUELDO_INTEGRAL']."',
								SueldoIntegralParcial = '".$_ARGS['_SUELDO_INTEGRAL_PARCIAL']."',
								AliVac = '".$_VALOR[$ALIVAC]."',
								AliFin = '".$_VALOR[$ALIFIN]."',
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFecha = NOW()
							ON DUPLICATE KEY UPDATE
								Sueldo = '".$_ARGS['_SUELDO_BASICO']."',
								SueldoNormal = '".$_ARGS['_SUELDO_NORMAL']."',
								SueldoIntegral = '".$_ARGS['_SUELDO_INTEGRAL']."',
								SueldoIntegralParcial = '".$_ARGS['_SUELDO_INTEGRAL_PARCIAL']."',
								AliVac = '".$_VALOR[$ALIVAC]."',
								AliFin = '".$_VALOR[$ALIFIN]."',
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFecha = NOW()";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
				//	prestaciones sociales
				elseif ($_ARGS['_PROCESO'] == "PRS" && $PreNomina != "S") {
					if (getNumRows2("pr_liquidacionempleado", array("CodPersona","Periodo"), array($_ARGS['_PERSONA'],$_ARGS['_PERIODO']))) {
						$sql = "DELETE FROM pr_liquidacionempleado
								WHERE
									CodPersona = '".$_ARGS['_PERSONA']."' AND
									Periodo = '".$_ARGS['_PERIODO']."'";
						$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					}
					$_SecuenciaLiquidacion = getCodigo("pr_liquidacionempleado", "Secuencia", 2, "CodPersona", $_ARGS['_PERSONA']);
					//	inserto en control de prestaciones
					$sql = "INSERT INTO pr_liquidacionempleado
							SET
								CodPersona = '".$_ARGS['_PERSONA']."',
								Secuencia = '".$_SecuenciaLiquidacion."',
								CodTipoNom = '".$_ARGS['_NOMINA']."',
								Periodo = '".$_ARGS['_PERIODO']."',
								CodOrganismo = '".$_ARGS['_ORGANISMO']."',
								CodTipoProceso = '".$_ARGS['_PROCESO']."',
								ProcesadoPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
								FechaProceso = NOW(),
								SueldoBasico = '".$_ARGS['_SUELDO_ACTUAL']."',
								TotalIngresos = '".$_ARGS['_ASIGNACIONES']."',
								TotalEgresos = '".$_ARGS['_DEDUCCIONES']."',
								TotalPatronales = '".$_ARGS['_APORTES']."',
								TotalProvisiones = '".$_ARGS['_PROVISIONES']."',
								TotalNeto = '".$_ARGS['_TOTAL_NETO']."',
								Fingreso = '".$_ARGS['_FECHA_INGRESO']."',
								Fegreso = '".$_ARGS['_FECHA_EGRESO']."',
								CodCargo = '".$_ARGS['_CARGO']."',
								CodMotivoCes = '".$_ARGS['_MOTIVO_CESE']."',
								ObsCese = '".$field_empleado['ObsCese']."',
								EstadoPago = 'PE',
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFecha = NOW()";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
				//	retroactivo
				elseif ($_ARGS['_RETROACTIVO'] == "S") {
					##	obtengo periodo anterior
					$sql = "SELECT *
							FROM rh_sueldos
							WHERE
								CodPersona = '".$_ARGS['_PERSONA']."' AND
								Periodo = '".$_ARGS['_PERIODONOMINA']."'";
					$field_anterior = getRecord($sql);
					##	
					$SueldoBasico = $field_anterior['Sueldo'] + $_C0001;
					$SueldoNormal = $field_anterior['SueldoNormal'] + $_ARGS['_SUELDO_NORMAL'];
					$SueldoDiario = round(($SueldoNormal / 30), 2);
					$AliVac = round(($SueldoDiario * $P_PAGOVACADC / 360), 2);
					$AliFin = round((($SueldoDiario + $AliVac) * $P_PAGOFINDC / 360), 2);
					$SueldoIntegral = $SueldoNormal + ($AliVac * 30) + ($AliFin * 30);
					$SueldoIntegralParcial = $SueldoNormal + ($AliVac * 30);
					//	actualizo sueldo
					$sql = "UPDATE rh_sueldos
							SET
								Sueldo = '".$SueldoBasico."',
								SueldoNormal = '".$SueldoNormal."',
								SueldoIntegral = '".$SueldoIntegral."',
								SueldoIntegralParcial = '".$SueldoIntegralParcial."',
								AliVac = '".$AliVac."',
								AliFin = '".$AliFin."',
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFecha = NOW()
							WHERE
								CodPersona = '".$_ARGS['_PERSONA']."' AND
								Periodo = '".$_ARGS['_PERIODONOMINA']."'";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	eliminar
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	-----------------
		if ($detalles_personas != "") {
			$personas = split(";char:tr;", $detalles_personas);
			foreach ($personas as $CodPersona) {
				//	eliminar
				$sql = "DELETE FROM pr_tiponominaempleado
						WHERE
							CodOrganismo = '".$fCodOrganismo."' AND
							CodTipoNom = '".$fCodTipoNom."' AND
							Periodo = '".$fPeriodo."' AND
							CodTipoProceso = '".$fCodTipoProceso."' AND
							CodPersona = '".$CodPersona."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				//	eliminar
				$sql = "DELETE FROM pr_tiponominaempleadoconcepto
						WHERE
							CodOrganismo = '".$fCodOrganismo."' AND
							CodTipoNom = '".$fCodTipoNom."' AND
							Periodo = '".$fPeriodo."' AND
							CodTipoProceso = '".$fCodTipoProceso."' AND
							CodPersona = '".$CodPersona."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	fideicomiso
elseif ($modulo == "fideicomiso") {
	//	actualizar acumulados
	if ($accion == "actualizar") {
		mysql_query("BEGIN");
		//	-----------------
		$Tasa = tasaInteres($fPeriodo);
		$DiasMes = getDiasMes($fPeriodo);
		//	empleados selecionados
		$personas = split(";char:tr;", $detalles_personas);
		foreach ($personas as $registro) {
			list($_CodPersona, $_Transaccion, $_Dias, $_Complemento, $_DiasAdicional, $_FlagFraccionado) = split(";char:td;", $registro);
			
			//	elimino datos actuales y siguientes al periodo
			$sql = "DELETE FROM pr_acumuladofideicomisodetalle
					WHERE
						CodPersona = '".$_CodPersona."' AND  
						Periodo >= '".$fPeriodo."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	consulto acumulado
			$sql = "SELECT * FROM pr_acumuladofideicomiso WHERE CodPersona = '".$_CodPersona."'";
			$query_acumulado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_acumulado)) $field_acumulado = mysql_fetch_array($query_acumulado);
			
			//	consulto detalles
			$sql = "SELECT
						SUM(Transaccion) AS Transaccion,
						SUM(TransaccionFide) AS TransaccionFide
					FROM pr_acumuladofideicomisodetalle
					WHERE
						CodPersona = '".$_CodPersona."' AND
						Periodo < '".$fPeriodo."'";
			$query_detalle = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_detalle)) $field_detalle = mysql_fetch_array($query_detalle);
			
			##	valores
			$AnteriorProv = $field_acumulado['AcumuladoInicialProv'] + $field_detalle['Transaccion'];
			$AnteriorFide = $field_acumulado['AcumuladoInicialFide'] + $field_acumulado['AcumuladoFide'];
			$TransaccionFide = (($field_acumulado['AcumuladoInicialProv'] + $field_acumulado['AcumuladoProv'] + $_Transaccion + $_Complemento) * $Tasa / 100) * $DiasMes / 365;
			
			//	inserto
			$sql = "INSERT INTO pr_acumuladofideicomisodetalle
					SET
						CodPersona = '".$_CodPersona."',
						Periodo = '".$fPeriodo."',
						CodOrganismo = '".$fCodOrganismo."',
						AnteriorProv = '".$AnteriorProv."',
						AnteriorFide = '".$AnteriorFide."',
						Transaccion = '".$_Transaccion."',
						TransaccionFide = '".$TransaccionFide."',
						Dias = '".$_Dias."',
						Complemento = '".$_Complemento."',
						DiasAdicional = '".$_DiasAdicional."',
						FlagFraccionado = '".$_FlagFraccionado."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	procesar calculo
	elseif ($accion == "procesar") {
		mysql_query("BEGIN");
		//	-----------------
		//	elimino
		$sql = "DELETE FROM pr_fideicomisocalculo
				WHERE
					CodPersona = '".$CodPersona."' AND
					Periodo >= '".$Periodo."-01'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$periodos = split(";char:tr;", $detalles_periodos);
		foreach ($periodos as $registro) {
			list($_Periodo, $_SueldoMensual, $_Bonificaciones, $_AliVac, $_AliFin, $_SueldoDiario, $_SueldoDiarioAli, $_Dias, $_PrestAntiguedad, $_DiasComplemento, $_PrestComplemento, $_PrestAcumulada, $_Tasa, $_DiasMes, $_InteresMensual, $_InteresAcumulado, $_Anticipo) = split(";char:td;", $registro);
			//	inserto
			$sql = "INSERT INTO pr_fideicomisocalculo
					SET
						Periodo = '".$_Periodo."',
						CodPersona = '".$CodPersona."',
						SueldoMensual = '".$_SueldoMensual."',
						Bonificaciones = '".$_Bonificaciones."',
						AliVac = '".$_AliVac."',
						AliFin = '".$_AliFin."',
						SueldoDiario = '".$_SueldoDiario."',
						SueldoDiarioAli = '".$_SueldoDiarioAli."',
						Dias = '".$_Dias."',
						PrestAntiguedad = '".$_PrestAntiguedad."',
						DiasComplemento = '".$_DiasComplemento."',
						PrestComplemento = '".$_PrestComplemento."',
						PrestAcumulada = '".$_PrestAcumulada."',
						Tasa = '".$_Tasa."',
						DiasMes = '".$_DiasMes."',
						InteresMensual = '".$_InteresMensual."',
						InteresAcumulado = '".$_InteresAcumulado."',
						Anticipo = '".$_Anticipo."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	actualizo
			$sql = "UPDATE pr_acumuladofideicomisodetalle
					SET
						TransaccionFide = ".floatval($_InteresMensual).",
						AnteriorFide = ".floatval($_InteresAcumulado-$_InteresMensual)."
					WHERE
						CodPersona = '".$CodPersona."' AND
						Periodo = '".$_Periodo."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	actualizar acumulado
	elseif ($accion == "acumulado") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto/actualizo
		$sql = "INSERT INTO pr_acumuladofideicomiso
				SET
					CodPersona = '".$CodPersona."',
					CodOrganismo = '".$CodOrganismo."',
					PeriodoInicial = '".$PeriodoInicial."',
					AcumuladoInicialDias = '".setNumero($AcumuladoInicialDias)."',
					AcumuladoDiasAdicionalInicial = '".setNumero($AcumuladoDiasAdicionalInicial)."',
					AcumuladoInicialProv = '".setNumero($AcumuladoInicialProv)."',
					AcumuladoInicialFide = '".setNumero($AcumuladoInicialFide)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					PeriodoInicial = '".$PeriodoInicial."',
					AcumuladoInicialDias = '".setNumero($AcumuladoInicialDias)."',
					AcumuladoDiasAdicionalInicial = '".setNumero($AcumuladoDiasAdicionalInicial)."',
					AcumuladoInicialProv = '".setNumero($AcumuladoInicialProv)."',
					AcumuladoInicialFide = '".setNumero($AcumuladoInicialFide)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	tipo de proeso
elseif ($modulo == "tipo_proceso") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto
		$sql = "INSERT INTO pr_tipoproceso
				SET
					CodTipoProceso = '".$CodTipoProceso."',
					Descripcion = '".changeUrl($Descripcion)."',
					FlagAdelanto = '".$FlagAdelanto."',
					FlagRetroactivo = '".$FlagRetroactivo."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizar
		$sql = "UPDATE pr_tipoproceso
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					FlagAdelanto = '".$FlagAdelanto."',
					FlagRetroactivo = '".$FlagRetroactivo."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodTipoProceso = '".$CodTipoProceso."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	-----------------
		//	elimino
		$sql = "DELETE FROM pr_tipoproceso WHERE CodTipoProceso = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	conceptos asignacion
elseif ($modulo == "conceptos_asignacion") {
	mysql_query("BEGIN");
	//	-----------------
	$empleados = split(";char:tr;", $detalles_empleados);
	foreach ($empleados as $registro) {
		list($CodPersona, $PeriodoDesde, $PeriodoHasta, $FlagManual, $Monto, $Cantidad, $Procesos, $Estado) = split(";char:td;", $registro);
		##	inserto
		$sql = "INSERT INTO pr_empleadoconcepto
				SET
					CodPersona = '".$CodPersona."',
					CodConcepto = '".$CodConcepto."',
					TipoAplicacion = '".$TipoAplicacion."',
					PeriodoDesde = '".$PeriodoDesde."',
					PeriodoHasta = '".$PeriodoHasta."',
					FlagManual = '".$FlagManual."',
					Monto = '".setNumero($Monto)."',
					Cantidad = '".setNumero($Cantidad)."',
					FlagTipoProceso = '".$FlagTipoProceso."',
					Procesos = '".trim($Procesos)."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					TipoAplicacion = '".$TipoAplicacion."',
					PeriodoDesde = '".$PeriodoDesde."',
					PeriodoHasta = '".$PeriodoHasta."',
					FlagManual = '".$FlagManual."',
					Monto = '".setNumero($Monto)."',
					Cantidad = '".setNumero($Cantidad)."',
					FlagTipoProceso = '".$FlagTipoProceso."',
					Procesos = '".trim($Procesos)."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	//	-----------------
	mysql_query("COMMIT");
}

//	ajuste salarial (grado salarial)
elseif ($modulo == "ajuste_salarial") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	codigo
		$Secuencia = getCodigo("pr_ajustesalarial", "Secuencia", 2, "CodOrganismo", $CodOrganismo, "Periodo", $Periodo);
		$Secuencia = intval($Secuencia);
		//	inserto
		$sql = "INSERT INTO pr_ajustesalarial
				SET
					CodOrganismo = '".$CodOrganismo."',
					Periodo = '".$Periodo."',
					Secuencia = '".$Secuencia."',
					Descripcion = '".changeUrl($Descripcion)."',
					NroResolucion = '".changeUrl($NroResolucion)."',
					NroGaceta = '".changeUrl($NroGaceta)."',
					PreparadoPor = '".$PreparadoPor."',
					FechaPreparacion = '".formatFechaAMD($FechaPreparacion)."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	ajustes
		if ($detalles_ajustes != "") {
			$ajustes = split(";char:tr;", $detalles_ajustes);
			foreach ($ajustes as $ajuste) {
				list($_CodNivel, $_SueldoBasico, $_Porcentaje, $_Monto, $_SueldoNuevo) = split(";char:td;", $ajuste);
				//	inserto
				$sql = "INSERT INTO pr_ajustesalarialajustes
						SET
							CodOrganismo = '".$CodOrganismo."',
							Periodo = '".$Periodo."',
							Secuencia = '".$Secuencia."',
							CodNivel = '".$_CodNivel."',
							SueldoBasico = '".$_SueldoBasico."',
							Porcentaje = '".$_Porcentaje."',
							Monto = '".$_Monto."',
							SueldoPromedio = '".$_SueldoNuevo."',
							Estado = '".$Estado."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE pr_ajustesalarial
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					NroResolucion = '".changeUrl($NroResolucion)."',
					NroGaceta = '".changeUrl($NroGaceta)."',
					PreparadoPor = '".$PreparadoPor."',
					FechaPreparacion = '".formatFechaAMD($FechaPreparacion)."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Periodo = '".$Periodo."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	ajustes
		if ($detalles_ajustes != "") {
			$sql = "DELETE FROM pr_ajustesalarialajustes
					WHERE
						CodOrganismo = '".$CodOrganismo."' AND
						Periodo = '".$Periodo."' AND
						Secuencia = '".$Secuencia."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			##
			$ajustes = split(";char:tr;", $detalles_ajustes);
			foreach ($ajustes as $ajuste) {
				list($_CodNivel, $_SueldoBasico, $_Porcentaje, $_Monto, $_SueldoNuevo) = split(";char:td;", $ajuste);
				//	inserto
				$sql = "INSERT INTO pr_ajustesalarialajustes
						SET
							CodOrganismo = '".$CodOrganismo."',
							Periodo = '".$Periodo."',
							Secuencia = '".$Secuencia."',
							CodNivel = '".$_CodNivel."',
							SueldoBasico = '".$_SueldoBasico."',
							Porcentaje = '".$_Porcentaje."',
							Monto = '".$_Monto."',
							SueldoPromedio = '".$_SueldoNuevo."',
							Estado = '".$Estado."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE pr_ajustesalarial
				SET
					Estado = 'AP',
					AprobadoPor = '".$AprobadoPor."',
					FechaAprobado = '".formatFechaAMD($FechaAprobado)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Periodo = '".$Periodo."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo
		$sql = "UPDATE pr_ajustesalarialajustes
				SET
					Estado = 'AP',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Periodo = '".$Periodo."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	ajustes
		$ajustes = split(";char:tr;", $detalles_ajustes);
		foreach ($ajustes as $ajuste) {
			list($_CodNivel, $_SueldoBasico, $_Porcentaje, $_Monto, $_SueldoNuevo) = split(";char:td;", $ajuste);
			##	
			$sql = "UPDATE rh_nivelsalarial
					SET
						SueldoMinimo = '".$_SueldoNuevo."',
						SueldoMaximo = '".$_SueldoNuevo."',
						SueldoPromedio = '".$_SueldoNuevo."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE CodNivel = '".$_CodNivel."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			##
			$_Secuencia = getCodigo("rh_nivelsalarialajustes", "Secuencia", 6, "CodNivel", $_CodNivel);
			$_Secuencia = intval($_Secuencia);
			$sql = "INSERT INTO rh_nivelsalarialajustes
					SET
						CodNivel = '".$_CodNivel."',
						Secuencia = '".$_Secuencia."',
						SueldoPromedio = '".$_SueldoNuevo."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			##
			$sql = "UPDATE rh_puestos p
					SET p.NivelSalarial = (SELECT ns.SueldoPromedio
										   FROM rh_nivelsalarial ns
										   WHERE
												ns.CategoriaCargo = p.CategoriaCargo AND
												ns.Grado = p.Grado)
					WHERE p.Estado = 'A'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		##
		$sql = "UPDATE mastempleado e
				SET
					e.SueldoAnterior = e.SueldoActual,
					e.SueldoActual = (SELECT pt.NivelSalarial
									  FROM rh_puestos pt
									  WHERE pt.CodCargo = e.CodCargo)
				WHERE e.Estado = 'A'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	anular
	elseif ($accion == "anular") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE pr_ajustesalarial
				SET
					Estado = 'AN',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Periodo = '".$Periodo."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo
		$sql = "UPDATE pr_ajustesalarialajustes
				SET
					Estado = 'AN',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Periodo = '".$Periodo."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	ajuste salarial (empleados)
elseif ($modulo == "ajuste_salarial_emp") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	codigo
		$Secuencia = getCodigo("pr_ajustesalarialemp", "Secuencia", 2, "CodOrganismo", $CodOrganismo, "Periodo", $Periodo);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO pr_ajustesalarialemp
				SET
					CodOrganismo = '".$CodOrganismo."',
					Periodo = '".$Periodo."',
					Secuencia = '".$Secuencia."',
					Descripcion = '".changeUrl($Descripcion)."',
					NroResolucion = '".changeUrl($NroResolucion)."',
					NroGaceta = '".changeUrl($NroGaceta)."',
					PreparadoPor = '".$PreparadoPor."',
					FechaPreparacion = '".formatFechaAMD($FechaPreparacion)."',
					CodTipoNom = '".$CodTipoNom."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	ajustes
		if ($detalles_ajustes != "") {
			$ajustes = split(";char:tr;", $detalles_ajustes);
			foreach ($ajustes as $ajuste) {
				list($_CodPersona, $_SueldoBasico, $_Porcentaje, $_Monto, $_SueldoNuevo) = split(";char:td;", $ajuste);
				//	inserto
				$sql = "INSERT INTO pr_ajustesalarialajustesemp
						SET
							CodOrganismo = '".$CodOrganismo."',
							Periodo = '".$Periodo."',
							Secuencia = '".$Secuencia."',
							CodPersona = '".$_CodPersona."',
							SueldoBasico = '".$_SueldoBasico."',
							Porcentaje = '".$_Porcentaje."',
							Monto = '".$_Monto."',
							SueldoPromedio = '".$_SueldoNuevo."',
							Estado = '".$Estado."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE pr_ajustesalarialemp
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					NroResolucion = '".changeUrl($NroResolucion)."',
					NroGaceta = '".changeUrl($NroGaceta)."',
					PreparadoPor = '".$PreparadoPor."',
					FechaPreparacion = '".formatFechaAMD($FechaPreparacion)."',
					CodTipoNom = '".$CodTipoNom."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Periodo = '".$Periodo."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	ajustes
		if ($detalles_ajustes != "") {
			$sql = "DELETE FROM pr_ajustesalarialajustesemp
					WHERE
						CodOrganismo = '".$CodOrganismo."' AND
						Periodo = '".$Periodo."' AND
						Secuencia = '".$Secuencia."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			##
			$ajustes = split(";char:tr;", $detalles_ajustes);
			foreach ($ajustes as $ajuste) {
				list($_CodPersona, $_SueldoBasico, $_Porcentaje, $_Monto, $_SueldoNuevo) = split(";char:td;", $ajuste);
				//	inserto
				$sql = "INSERT INTO pr_ajustesalarialajustesemp
						SET
							CodOrganismo = '".$CodOrganismo."',
							Periodo = '".$Periodo."',
							Secuencia = '".$Secuencia."',
							CodPersona = '".$_CodPersona."',
							SueldoBasico = '".$_SueldoBasico."',
							Porcentaje = '".$_Porcentaje."',
							Monto = '".$_Monto."',
							SueldoPromedio = '".$_SueldoNuevo."',
							Estado = '".$Estado."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE pr_ajustesalarialemp
				SET
					Estado = 'AP',
					AprobadoPor = '".$AprobadoPor."',
					FechaAprobado = '".formatFechaAMD($FechaAprobado)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Periodo = '".$Periodo."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo
		$sql = "UPDATE pr_ajustesalarialajustesemp
				SET
					Estado = 'AP',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Periodo = '".$Periodo."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	ajustes
		$ajustes = split(";char:tr;", $detalles_ajustes);
		foreach ($ajustes as $ajuste) {
			list($_CodPersona, $_SueldoBasico, $_Porcentaje, $_Monto, $_SueldoNuevo) = split(";char:td;", $ajuste);
			$_Estado = getVar2("mastempleado", "Estado", array("CodPersona"), array($_CodPersona));
			if ($_Estado == "I") $_SueldoJubilacion = ", MontoJubilacion = ".floatval($_SueldoNuevo)."";
			else $_SueldoJubilacion = "";
			//	actualizo
			$sql = "UPDATE mastempleado
					SET
						SueldoAnterior = SueldoActual,
						SueldoActual = ".floatval($_SueldoNuevo)."
						$_SueldoJubilacion
					WHERE CodPersona = '".$_CodPersona."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	anular
	elseif ($accion == "anular") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE pr_ajustesalarialemp
				SET
					Estado = 'AN',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Periodo = '".$Periodo."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo
		$sql = "UPDATE pr_ajustesalarialajustesemp
				SET
					Estado = 'AN',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Periodo = '".$Periodo."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	control de procesos (prenomina)
elseif ($modulo == "prenomina_procesos") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto
		$sql = "INSERT INTO pr_procesoperiodoprenomina
				SET
					CodOrganismo = '".$CodOrganismo."',
					CodTipoNom = '".$CodTipoNom."',
					Periodo = '".$Periodo."',
					CodTipoProceso = '".$CodTipoProceso."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					CreadoPor = '".$CreadoPor."',
					FechaCreado = '".formatFechaAMD($FechaCreado)."',
					FlagProcesado = 'N',
					FlagMensual = '".$FlagMensual."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizar
		$sql = "UPDATE pr_procesoperiodo
				SET
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					FlagMensual = '".$FlagMensual."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodTipoNom = '".$CodTipoNom."' AND
					Periodo = '".$Periodo."' AND
					CodTipoProceso = '".$CodTipoProceso."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
}
?>