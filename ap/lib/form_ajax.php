<?php
session_start();
include("../../lib/fphp.php");
include("fphp.php");
//	$__archivo = fopen("$modulo-$accion.sql", "w+");
//	fwrite($__archivo, $sql.";\n\n");
///////////////////////////////////////////////////////////////////////////////
//	PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	generacion de vouchers
if ($modulo == "generar_vouchers") {
	mysql_query("BEGIN");
	$Creditos = setNumero($Creditos);
	$Debitos = setNumero($Debitos);
	##
	if (formatFechaAMD($FechaVoucher) == "") die("No puede generar el voucher sin la fecha");
	elseif ($Periodo == "") die("No puede generar el voucher sin el periodo");
	//	genero nuevo voucher
	$NroVoucher = getCodigo("ac_vouchermast", "NroVoucher", 4, "CodOrganismo", $CodOrganismo, "Periodo", $Periodo, "CodVoucher", $CodVoucher, "CodContabilidad", $CodContabilidad);
	$NroInterno = getCodigo("ac_vouchermast", "NroInterno", 10);
	$Voucher = "$CodVoucher-$NroVoucher";
	
	//	inserto voucher
	$sql = "INSERT INTO ac_vouchermast
			SET
				CodOrganismo = '".$CodOrganismo."',
				Periodo = '".$Periodo."',
				Voucher = '".$Voucher."',
				CodContabilidad = '".$CodContabilidad."',
				Prefijo = '".$CodVoucher."',
				NroVoucher = '".$NroVoucher."',
				CodVoucher = '".$CodVoucher."',
				CodDependencia = '".$CodDependencia."',
				CodSistemaFuente = '".$CodSistemaFuente."',
				Creditos = '".$Creditos."',
				Debitos = '".$Debitos."',
				Lineas = '".$Lineas."',
				PreparadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
				FechaPreparacion = '".formatFechaAMD($FechaPreparacion)."',
				AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
				FechaAprobacion = '".formatFechaAMD($FechaAprobacion)."',
				TituloVoucher = '".$ComentariosVoucher."',
				ComentariosVoucher = '".$ComentariosVoucher."',
				FechaVoucher = '".formatFechaAMD($FechaVoucher)."',
				NroInterno = '".$NroInterno."',
				FlagTransferencia = 'N',
				Estado = 'MA',
				CodLibroCont = '".$CodLibroCont."',
				UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
				UltimaFecha = NOW()";
	$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	
	//	inserto los detalles
	$linea = split(";char:tr;", $detalles);
	foreach ($linea as $registro) {
		list($_Linea, $_CodCuenta, $_Descripcion, $_MontoVoucher, $_CodPersona, $_ReferenciaTipoDocumento, $_ReferenciaNroDocumento, $_CodCentroCosto, $_FechaVoucher) = split(';char:td;', $registro);
		
		//	inserto detalle
		$sql = "INSERT INTO ac_voucherdet
				SET
					CodOrganismo = '".$CodOrganismo."',
					Periodo = '".$Periodo."',
					Voucher = '".$Voucher."',
					CodContabilidad = '".$CodContabilidad."',
					Linea = '".$_Linea."',
					CodCuenta = '".$_CodCuenta."',
					MontoVoucher = '".$_MontoVoucher."',
					MontoPost = '".$_MontoVoucher."',
					CodPersona = '".$_CodPersona."',
					FechaVoucher = '".formatFechaAMD($_FechaVoucher)."',
					CodCentroCosto = '".$_CodCentroCosto."',
					ReferenciaTipoDocumento = '".$_ReferenciaTipoDocumento."',
					ReferenciaNroDocumento = '".$_ReferenciaNroDocumento."',
					Descripcion = '".$_Descripcion."',
					Estado = 'MA',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	si genere el voucher desde generar voucher de transacciones bancarias
	if ($accion == "transacciones") {
		//	actualizo transaccion banco
		$sql = "UPDATE ap_bancotransaccion
				SET
					Voucher = '".$Voucher."',
					VoucherPeriodo = '".$Periodo."',
					FlagContabilizacionPendiente = 'N',
					Estado = 'CO'
				WHERE NroTransaccion = '".$NroTransaccion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	si genere el voucher desde generar voucher de transacciones bancarias
	elseif ($accion == "transacciones-pub20") {
		//	actualizo transaccion banco
		$sql = "UPDATE ap_bancotransaccion
				SET
					VoucherPub20 = '".$Voucher."',
					VoucherPeriodoPub20 = '".$Periodo."',
					FlagContPendientePub20 = 'N',
					Estado = 'CO'
				WHERE NroTransaccion = '".$NroTransaccion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	si genere el voucher desde generar voucher de obligaciones
	elseif ($accion == "provision") {
		//	actualizo obligacion
		$sql = "UPDATE ap_obligaciones
				SET
					FlagContabilizacionPendiente = 'N',
					Voucher = '".$Voucher."',
					VoucherPeriodo = '".$Periodo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	si genere el voucher desde generar voucher de obligaciones
	elseif ($accion == "provision-pub20") {
		//	actualizo obligacion
		$sql = "UPDATE ap_obligaciones
				SET
					FlagContPendientePub20 = 'N',
					VoucherPub20 = '".$Voucher."',
					VoucherPeriodoPub20 = '".$Periodo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	si genere el voucher desde generar voucher de obligaciones
	elseif ($accion == "ordenacion-pub20") {
		//	actualizo orden de pago
		$sql = "UPDATE ap_ordenpago
				SET
					FlagContPendienteOrdPub20 = 'N',
					Voucher = '".$Voucher."',
					VoucherPeriodo = '".$Periodo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	si genere el voucher desde generar voucher de pagos
	elseif ($accion == "pagos") {
		//	actualizo pago
		$sql = "UPDATE ap_pagos
				SET
					FlagContabilizacionPendiente = 'N',
					VoucherPago = '".$Voucher."',
					VoucherPeriodo = '".$Periodo."'
				WHERE
					NroProceso = '".$NroProceso."' AND
					CodTipoPago = '".$CodTipoPago."' AND
					NroCuenta = '".$NroCuenta."'";
		$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	si genere el voucher desde generar voucher de pagos
	elseif ($accion == "pagos-pub20") {
		//	actualizo pago
		$sql = "UPDATE ap_pagos
				SET
					FlagContPendientePub20 = 'N',
					VoucherPagoPub20 = '".$Voucher."',
					PeriodoPagoPub20 = '".$Periodo."'
				WHERE
					NroProceso = '".$NroProceso."' AND
					CodTipoPago = '".$CodTipoPago."' AND
					NroCuenta = '".$NroCuenta."'";
		$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	mysql_query("COMMIT");
}

//	obligacion
elseif ($modulo == "obligacion") {
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
	
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//--------------------
		//	verifico valores ingresados
		if (valObligacion($CodProveedor, $CodTipoDocumento, $NroDocumento)) die("Nro. de Obligacion Ya ingresado");
		
		//	obtengo el numero de las ordenes
		$ReferenciaTipoDocumento = "";
		$ReferenciaNroDocumento = "";
		$linea_documento = split(";char:tr;", $detalles_documento);
		foreach ($linea_documento as $registro) {
			list($_Porcentaje, $_DocumentoClasificacion, $_DocumentoReferencia, $_Fecha, $_ReferenciaTipoDocumento, $_ReferenciaNroDocumento, $_MontoTotal, $_MontoAfecto, $_MontoImpuestos, $_MontoAfecto, $_MontoNoAfecto, $_Comentarios) = split(";char:td;", $registro);
			$ReferenciaTipoDocumento = $_ReferenciaTipoDocumento;
			if ($k == 0) $ReferenciaNroDocumento .= $_ReferenciaNroDocumento;
			else $ReferenciaNroDocumento .= "-".$_ReferenciaNroDocumento;
			$k++;
		}
		
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
		
		//	documentos
		if ($detalles_documento != "") {
			$linea_documento = split(";char:tr;", $detalles_documento);	$_Linea=0;
			foreach ($linea_documento as $registro) {	$_Linea++;
				list($_Porcentaje, $_DocumentoClasificacion, $_DocumentoReferencia, $_Fecha, $_ReferenciaTipoDocumento, $_ReferenciaNroDocumento, $_MontoTotal, $_MontoAfecto, $_MontoImpuestos, $_MontoAfecto, $_MontoNoAfecto, $_Comentarios) = split(";char:td;", $registro);
				
				//	consulto si existe el documento
				$sql = "SELECT *
						FROM ap_documentos
						WHERE
							Anio = '".$Anio."' AND
							CodProveedor = '".$CodProveedor."' AND
							DocumentoClasificacion = '".$_DocumentoClasificacion."' AND
							DocumentoReferencia = '".$_DocumentoReferencia."'";
				$query_documento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_documento) != 0) {
					//	actualizo documento
					$sql = "UPDATE ap_documentos
							SET 
								ObligacionTipoDocumento = '".$CodTipoDocumento."',
								ObligacionNroDocumento = '".$NroDocumento."',
								Estado = 'RV',
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()
							WHERE
								Anio = '".$Anio."' AND
								CodProveedor = '".$CodProveedor."' AND
								DocumentoClasificacion = '".$_DocumentoClasificacion."' AND
								DocumentoReferencia = '".$_DocumentoReferencia."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					//	inserto documento
					$secuencia_referencia = getCorrelativoSecuencia_2("ap_documentos", "ReferenciaTipoDocumento", "ReferenciaNroDocumento", $_ReferenciaTipoDocumento, $_ReferenciaNroDocumento);
					$sql = "INSERT INTO ap_documentos
							SET
								CodOrganismo = '".$CodOrganismo."',
								CodProveedor = '".$CodProveedor."',
								DocumentoClasificacion = '".$_DocumentoClasificacion."',
								DocumentoReferencia = '".$_DocumentoReferencia."',
								Fecha = NOW(),
								ReferenciaTipoDocumento = '".$_ReferenciaTipoDocumento."',
								ReferenciaNroDocumento = '".$_ReferenciaNroDocumento."',
								Estado = 'RV',
								ObligacionTipoDocumento = '".$CodTipoDocumento."',
								ObligacionNroDocumento = '".$NroDocumento."',
								MontoAfecto = '".$_MontoAfecto."',
								MontoNoAfecto = '".$_MontoNoAfecto."',
								MontoImpuestos = '".$_MontoImpuestos."',
								MontoTotal = '".$_MontoTotal."',
								MontoPendiente = '".$_MontoTotal."',
								Anio = '".$Anio."',
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					
					//	documentos detalle
					if ($_ReferenciaTipoDocumento == "OC") {
						$sql = "SELECT *
								FROM lg_ordencompradetalle
								WHERE
									Anio = '".$Anio."' AND
									CodOrganismo = '".$CodOrganismo."' AND
									NroOrden = '".$_ReferenciaNroDocumento."'";
					} else {
						$sql = "SELECT *, (CantidadRecibida * PrecioUnit) As PrecioCantidad
								FROM lg_ordenserviciodetalle
								WHERE
									Anio = '".$Anio."' AND
									CodOrganismo = '".$CodOrganismo."' AND
									NroOrden = '".$_ReferenciaNroDocumento."'";
					}
					$query_od = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					while ($field_od = mysql_fetch_array($query_od)) {
						$sql = "INSERT INTO ap_documentosdetalle
								SET
									CodProveedor = '".$CodProveedor."',
									DocumentoClasificacion = '".$_DocumentoClasificacion."',
									DocumentoReferencia = '$_ReferenciaTipoDocumento-$_ReferenciaNroDocumento-$secuencia_referencia',
									Secuencia = '".$field_od['Secuencia']."',
									CodItem = '".$field_od['CodItem']."',
									CommoditySub = '".$field_od['CommoditySub']."',
									Descripcion = '".$field_od['Descripcion']."',
									Cantidad = '".$field_od['CantidadPedida']."',
									PrecioUnit = '".$field_od['PrecioUnit']."',
									PrecioCantidad = '".$field_od['PrecioCantidad']."',
									Total = '".$field_od['Total']."',
									CodCentroCosto = '".$field_od['CodCentroCosto']."',
									Anio = '".$Anio."',
									UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
									UltimaFecha = NOW()";
						$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					}
				}
							
				//	verifico si la orden tiene activos fijos
				if ($_ReferenciaTipoDocumento == "OC") {
					$sql = "SELECT ocd.*
							FROM 
								lg_ordencompradetalle ocd
								INNER JOIN lg_commoditysub cs ON (ocd.CommoditySub = cs.Codigo)
								INNER JOIN lg_commoditymast cm ON (cs.CommodityMast = cm.CommodityMast)
							WHERE 
								(cm.Clasificacion = 'ACT' OR cm.Clasificacion = 'BME') AND
								ocd.Anio = '".$Anio."' AND
								ocd.CodOrganismo = '".$CodOrganismo."' AND
								ocd.NroOrden = '".$_ReferenciaNroDocumento."'";
					$query_comm = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					if (mysql_num_rows($query_comm) != 0) {
						$sql = "UPDATE lg_activofijo
								SET
									FlagFacturado = 'S',
									ObligacionTipoDocumento = '".$CodTipoDocumento."',
									ObligacionNroDocumento = '".$NroDocumento."',
									ObligacionFechaDocumento = '".formatFechaAMD($FechaRegistro)."'
								WHERE
									Anio = '".$Anio."' AND
									CodOrganismo = '".$CodOrganismo."' AND
									NroOrden = '".$_ReferenciaNroDocumento."'";
						$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					}
				}
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
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		
		//	actualizo obligacion
		$sql = "UPDATE ap_obligaciones
				SET
					CodProveedorPagar = '".$CodProveedorPagar."',
					NroControl = '".$NroControl."',
					NroCuenta = '".$NroCuenta."',
					CodTipoPago = '".$CodTipoPago."',
					CodTipoServicio = '".$CodTipoServicio."',
					MontoObligacion = '".($MontoObligacion)."',
					MontoImpuestoOtros = '".($MontoImpuestoOtros)."',
					MontoNoAfecto = '".($MontoNoAfecto)."',
					MontoAfecto = '".($MontoAfecto)."',
					MontoAdelanto = '".($MontoAdelanto)."',
					MontoImpuesto = '".($MontoImpuesto)."',
					MontoPagoParcial = '".($MontoPagoParcial)."',
					Comentarios = '".$Comentarios."',
					ComentariosAdicional = '".$ComentariosAdicional."',
					FechaRegistro = '".formatFechaAMD($FechaRegistro)."',
					FechaVencimiento = '".formatFechaAMD($FechaVencimiento)."',
					FechaRecepcion = '".formatFechaAMD($FechaRecepcion)."',
					FechaDocumento = '".formatFechaAMD($FechaDocumento)."',
					FechaProgramada = '".formatFechaAMD($FechaProgramada)."',
					FechaFactura = '".formatFechaAMD($FechaFactura)."',
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
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	impuestos
		$sql = "DELETE FROM ap_obligacionesimpuesto
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
		$sql = "DELETE FROM ap_obligacionescuenta
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
		$sql = "DELETE FROM ap_distribucionobligacion
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($FlagCompromiso == "S") {
			$sql = "DELETE FROM lg_distribucioncompromisos
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
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
		mysql_query("COMMIT");
	}
	
	//	revisar
	elseif ($accion == "revisar") {
		mysql_query("BEGIN");
		//	actualizo obligacion
		$sql = "UPDATE ap_obligaciones
				SET
					Estado = 'RV',
					RevisadoPor = '".$RevisadoPor."',
					FechaRevision = '".formatFechaAMD($FechaRevision)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo distribucion
		if ($FlagCompromiso == "S") {
			$sql = "UPDATE lg_distribucioncompromisos
					SET
						Estado = 'CO',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		$sql = "UPDATE ap_distribucionobligacion
				SET
					Estado = 'CA',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		mysql_query("COMMIT");
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		mysql_query("BEGIN");
		//	documentos
		$sql = "SELECT *
				FROM ap_documentos
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					ObligacionTipoDocumento = '".$CodTipoDocumento."' AND
					ObligacionNroDocumento = '".$NroDocumento."'";
		$query_documentos = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));	$linea=0;
		while ($field_documentos = mysql_fetch_array($query_documentos)) {
			//	actualizo (orden)
			if ($field_documentos['ReferenciaTipoDocumento'] == "OC") {
				$sql = "UPDATE lg_ordencompra 
						SET
							MontoPendiente = (MontoPendiente - (".floatval($MontoAfecto)." + ".floatval($MontoNoAfecto)." + ".floatval($MontoImpuesto).")),
							MontoPagado = (MontoPagado + (".floatval($MontoAfecto)." + ".floatval($MontoNoAfecto)." + ".floatval($MontoImpuesto).")),
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$field_documentos['ReferenciaNroDocumento']."'";
				$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				$sql = "UPDATE lg_ordenservicio
						SET
							MontoPendiente = (MontoPendiente - (".floatval($MontoAfecto)." + ".floatval($MontoNoAfecto)." + ".floatval($MontoImpuesto).")),
							MontoGastado = (MontoGastado + (".floatval($MontoAfecto)." + ".floatval($MontoNoAfecto)." + ".floatval($MontoImpuesto).")),
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$field_documentos['ReferenciaNroDocumento']."'";
				$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	actualizo obligacion
		$sql = "UPDATE ap_obligaciones
				SET
					Estado = 'AP',
					AprobadoPor = '".$AprobadoPor."',
					FechaAprobado = '".formatFechaAMD($FechaAprobado)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto (orden de pago)
		$NroOrden = getCodigo("ap_ordenpago", "NroOrden", 10, "CodOrganismo", $CodOrganismo, "Anio", date("Y"));
		$sql = "INSERT INTO ap_ordenpago
				SET
					Anio = '".$Anio."',
					CodOrganismo = '".$CodOrganismo."',
					NroOrden = '".$NroOrden."',
					CodAplicacion = 'AP',
					CodProveedor = '".$CodProveedor."',
					CodTipoDocumento = '".$CodTipoDocumento."',
					NroDocumento = '".$NroDocumento."',
					FechaDocumento = '".formatFechaAMD($FechaDocumento)."',
					FechaVencimiento = '".formatFechaAMD($FechaVencimiento)."',
					FechaOrdenPago = '".formatFechaAMD($FechaAprobado)."',
					FechaVencimientoReal = '".formatFechaAMD($FechaVencimiento)."',
					FechaProgramada = '".formatFechaAMD($FechaProgramada)."',
					FechaRevisado = '".formatFechaAMD($FechaRevision)."',
					CodProveedorPagar = '".$CodProveedorPagar."',
					NomProveedorPagar = '".changeUrl($NomProveedorPagar)."',
					Concepto = '".$Comentarios."',
					NroCuenta = '".$NroCuenta."',
					CodTipoPago = '".$CodTipoPago."',
					MontoTotal = '".$MontoObligacion."',
					NroRegistro = '".$NroRegistro."',
					FlagPagoDiferido = '".$FlagPagoDiferido."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodSistemaFuente = '".$field_fuente['CodSistemaFuente']."',
					Periodo = '".$Periodo."',
					RevisadoPor = '".$RevisadoPor."',
					AprobadoPor = '".$AprobadoPor."',
					Estado = 'PE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto (orden de pago detalles)
		$sql = "SELECT *
				FROM ap_distribucionobligacion
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_distribucion = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));	$Linea=0;
		while ($field_distribucion = mysql_fetch_array($query_distribucion)) {	$Linea++;
			$sql = "INSERT INTO ap_ordenpagodistribucion
					SET
						CodOrganismo = '".$CodOrganismo."',
						NroOrden = '".$NroOrden."',
						CodProveedor = '".$CodProveedor."',
						CodTipoDocumento = '".$CodTipoDocumento."',
						NroDocumento = '".$NroDocumento."',
						Linea = '".$Linea."',
						CodCentroCosto = '".$CodCentroCosto."',
						Monto = '".$field_distribucion['Monto']."',
						CodCuenta = '".$field_distribucion['CodCuenta']."',
						CodCuentaPub20 = '".$field_distribucion['CodCuentaPub20']."',
						cod_partida = '".$field_distribucion['cod_partida']."',
						Anio = '".$Anio."',
						Periodo = '".$Periodo."',
						CodPresupuesto = '".$CodPresupuesto."',
						Origen = 'OP',
						Estado = 'PE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		if ($_PARAMETRO['CONTONCO'] == "S") {
			$Secuencia = 0;
			//	inserto (orden de pago contabilidad)
			$sql = "(SELECT
						td.CodCuentaProv AS CodCuenta,
						oc.ReferenciaTipoDocumento AS TipoOrden,
						oc.ReferenciaNroDocumento AS NroOrden,
						pc.Descripcion AS NomCuenta,
						(oc.MontoObligacion) AS MontoVoucher,
						pc.TipoSaldo,
						'01' AS Orden,
						'Haber' AS Columna
					 FROM
						ap_obligaciones oc
						INNER JOIN ap_tipodocumento td ON (oc.CodTipoDocumento = td.CodTipoDocumento)
						INNER JOIN ac_mastplancuenta pc ON (td.CodCuentaProv = pc.CodCuenta)
					 WHERE
						oc.CodProveedor = '".$CodProveedor."' AND
						oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
						oc.NroDocumento = '".$NroDocumento."'
					 GROUP BY CodCuenta)
					UNION
					(SELECT
						(SELECT CodCuenta FROM mastimpuestos WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS CodCuenta,
						oc.ReferenciaTipoDocumento AS TipoOrden,
						oc.ReferenciaNroDocumento AS NroOrden,
						(SELECT pc2.Descripcion
						 FROM
							mastimpuestos i2
							INNER JOIN ac_mastplancuenta pc2 ON (i2.CodCuenta = pc2.CodCuenta)
						 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS NomCuenta,
						oc.MontoImpuesto AS MontoVoucher,
						(SELECT pc2.TipoSaldo
						 FROM
							mastimpuestos i2
							INNER JOIN ac_mastplancuenta pc2 ON (i2.CodCuenta = pc2.CodCuenta)
						 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS TipoSaldo,
						'02' AS Orden,
						'Debe' AS Columna
					 FROM ap_obligaciones oc
					 WHERE
						oc.CodProveedor = '".$CodProveedor."' AND
						oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
						oc.NroDocumento = '".$NroDocumento."' AND
						oc.MontoImpuesto > 0
					 GROUP BY CodCuenta)
					UNION
					(SELECT
						oc.CodCuenta,
						o.ReferenciaTipoDocumento AS TipoOrden,
						o.ReferenciaNroDocumento AS NroOrden,
						pc.Descripcion AS NomCuenta,
						ABS(SUM(oc.MontoImpuesto)) AS MontoVoucher,
						pc.TipoSaldo,
						'03' AS Orden,
						'Haber' AS Columna
					 FROM
						ap_obligacionesimpuesto oc
						INNER JOIN ap_obligaciones o ON (oc.CodProveedor = o.CodProveedor AND
														 oc.CodTipoDocumento = o.CodTipoDocumento AND
														 oc.NroDocumento = o.NroDocumento)
						INNER JOIN ac_mastplancuenta pc ON (oc.CodCuenta = pc.CodCuenta)
					 WHERE
						oc.FlagProvision = 'N' AND
						oc.CodProveedor = '".$CodProveedor."' AND
						oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
						oc.NroDocumento = '".$NroDocumento."'
					 GROUP BY oc.CodCuenta)
					UNION
					(SELECT
						oc.CodCuenta,
						oc.TipoOrden,
						oc.NroOrden,
						pc.Descripcion AS NomCuenta,
						SUM(oc.Monto) AS MontoVoucher,
						pc.TipoSaldo,
						'04' AS Orden,
						'Debe' AS Columna
					 FROM
						ap_obligacionescuenta oc
						INNER JOIN ac_mastplancuenta pc ON (oc.CodCuenta = pc.CodCuenta)
					 WHERE
						oc.CodProveedor = '".$CodProveedor."' AND
						oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
						oc.NroDocumento = '".$NroDocumento."'
					 GROUP BY oc.CodCuenta)
					ORDER BY CodCuenta";
			$query_det = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));	$Secuencia=0;
			while($field_det = mysql_fetch_array($query_det)) {	$Secuencia++;
				if ($field_det['Orden'] == "01") {
					$sql = "SELECT ABS(SUM(oi1.MontoImpuesto)) AS MontoRetencion
							FROM
								ap_obligacionesimpuesto oi1
								INNER JOIN ap_obligaciones o1 ON (oi1.CodProveedor = o1.CodProveedor AND
																  oi1.CodTipoDocumento = o1.CodTipoDocumento AND
																  oi1.NroDocumento = o1.NroDocumento)
								INNER JOIN mastimpuestos i1 ON (oi1.CodImpuesto = i1.CodImpuesto)
								INNER JOIN ac_mastplancuenta pc1 ON (i1.CodCuenta = pc1.CodCuenta)
							WHERE
								oi1.FlagProvision = 'P' AND
								oi1.CodProveedor = '".$CodProveedor."' AND
								oi1.CodTipoDocumento = '".$CodTipoDocumento."' AND
								oi1.NroDocumento = '".$NroDocumento."'
							GROUP BY i1.FlagProvision, oi1.CodProveedor, oi1.CodTipoDocumento, oi1.NroDocumento";
					$query_orden1 = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
					if (mysql_num_rows($query_orden1) != 0) $field_orden1 = mysql_fetch_array($query_orden1);
					$Monto = $field_det['MontoVoucher'] + $field_orden1['MontoRetencion'];
				} else $Monto = $field_det['MontoVoucher'];
				
				if ($field_det['Columna'] == "Haber") {
					$Monto = abs($Monto) * (-1);
					$Debitos += $Monto;
				} else {
					$style = "";
					$Monto = abs($Monto);
					$Creditos += $Monto;
				}
				$sql = "INSERT INTO ap_ordenpagocontabilidad
						SET
							Anio = '".$Anio."',
							CodOrganismo = '".$CodOrganismo."',
							NroOrden = '".$NroOrden."',
							CodContabilidad = 'T',
							Secuencia = '".$Secuencia."',
							CodCuenta = '".$field_det['CodCuenta']."',
							Monto = '".$Monto."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		if ($_PARAMETRO['CONTPUB20'] == "S") {
			$Secuencia = 0;
			//	impuestos FlagProvision=N
			$sql = "SELECT SUM(oi1.MontoImpuesto) AS Monto
					FROM
						ap_obligacionesimpuesto oi1
						INNER JOIN ap_obligaciones o1 ON (oi1.CodProveedor = o1.CodProveedor AND
														  oi1.CodTipoDocumento = o1.CodTipoDocumento AND
														  oi1.NroDocumento = o1.NroDocumento)
					WHERE
						oi1.FlagProvision = 'N' AND
						oi1.CodProveedor = '".$CodProveedor."' AND
						oi1.CodTipoDocumento = '".$CodTipoDocumento."' AND
						oi1.NroDocumento = '".$NroDocumento."'
					GROUP BY oi1.FlagProvision, oi1.CodProveedor, oi1.CodTipoDocumento, oi1.NroDocumento";
			$query_impueston = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_impueston) != 0) $field_impueston = mysql_fetch_array($query_impueston);
			
			//	impuestos FlagProvision=P
			$sql = "SELECT SUM(oi1.MontoImpuesto) AS Monto
					FROM
						ap_obligacionesimpuesto oi1
						INNER JOIN ap_obligaciones o1 ON (oi1.CodProveedor = o1.CodProveedor AND
														  oi1.CodTipoDocumento = o1.CodTipoDocumento AND
														  oi1.NroDocumento = o1.NroDocumento)
					WHERE
						oi1.FlagProvision = 'P' AND
						oi1.CodProveedor = '".$CodProveedor."' AND
						oi1.CodTipoDocumento = '".$CodTipoDocumento."' AND
						oi1.NroDocumento = '".$NroDocumento."'
					GROUP BY oi1.FlagProvision, oi1.CodProveedor, oi1.CodTipoDocumento, oi1.NroDocumento";
			$query_impuestop = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_impuestop) != 0) $field_impuestop = mysql_fetch_array($query_impuestop);
			
			$sql = "(SELECT
						td.CodCuentaProvPub20 AS CodCuenta,
						oc.ReferenciaTipoDocumento AS TipoOrden,
						oc.ReferenciaNroDocumento AS NroOrden,
						pc.Descripcion AS NomCuenta,
						(oc.MontoObligacion + ".abs($field_impueston['Monto'])." + ".abs($field_impuestop['Monto']).") AS MontoVoucher,
						pc.TipoSaldo,
						'01' AS Orden,
						'Debe' AS Columna
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
						p.CtaOrdPagoPub20 AS CodCuenta,
						oc.TipoOrden,
						oc.NroOrden,
						pc.Descripcion AS NomCuenta,
						(SUM(oc.Monto) - ".abs($field_impueston['Monto']).") AS MontoVoucher,
						pc.TipoSaldo,
						'02' AS Orden,
						'Haber' AS Columna
					 FROM
						ap_obligacionescuenta oc
						INNER JOIN pv_partida p ON (p.cod_partida = oc.cod_partida)
						INNER JOIN ac_mastplancuenta20 pc ON (pc.CodCuenta = p.CtaOrdPagoPub20)
						INNER JOIN ap_obligaciones o ON (o.CodProveedor = oc.CodProveedor AND
														 o.CodTipoDocumento = oc.CodTipoDocumento AND
														 o.NroDocumento = oc.NroDocumento)
						INNER JOIN ap_tipodocumento td ON (td.CodTipoDocumento = o.CodTipoDocumento AND td.FlagProvision = 'S')
					 WHERE
						oc.CodProveedor = '".$CodProveedor."' AND
						oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
						oc.NroDocumento = '".$NroDocumento."'
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
						'03' AS Orden,
						'Haber' AS Columna
					 FROM ap_obligaciones oc
					 WHERE
						oc.CodProveedor = '".$CodProveedor."' AND
						oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
						oc.NroDocumento = '".$NroDocumento."' AND
						oc.MontoImpuesto > 0
					 GROUP BY CodCuenta)
					UNION
					(SELECT
						oc.CodCuentaPub20 AS CodCuenta,
						o.ReferenciaTipoDocumento AS TipoOrden,
						o.ReferenciaNroDocumento AS NroOrden,
						pc.Descripcion AS NomCuenta,
						ABS(SUM(oc.MontoImpuesto)) AS MontoVoucher,
						pc.TipoSaldo,
						'04' AS Orden,
						'Haber' AS Columna
					 FROM
						ap_obligacionesimpuesto oc
						INNER JOIN ap_obligaciones o ON (oc.CodProveedor = o.CodProveedor AND
														 oc.CodTipoDocumento = o.CodTipoDocumento AND
														 oc.NroDocumento = o.NroDocumento)
						INNER JOIN ac_mastplancuenta20 pc ON (oc.CodCuentaPub20 = pc.CodCuenta)
					 WHERE
						oc.FlagProvision = 'N' AND
						oc.CodProveedor = '".$CodProveedor."' AND
						oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
						oc.NroDocumento = '".$NroDocumento."'
					 GROUP BY oc.CodCuenta)
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
				$sql = "INSERT INTO ap_ordenpagocontabilidad
						SET
							Anio = '".$Anio."',
							CodOrganismo = '".$CodOrganismo."',
							NroOrden = '".$NroOrden."',
							CodContabilidad = 'F',
							Secuencia = '".++$Secuencia."',
							CodCuenta = '".$field_det['CodCuenta']."',
							Monto = '".$Monto."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			if (mysql_num_rows($query_det) == 0) {
				$sql = "SELECT
							oc.CodCuentaPub20 AS CodCuenta,
							oc.TipoOrden,
							oc.NroOrden,
							pc.Descripcion AS NomCuenta,
							SUM(oc.Monto) AS MontoVoucher,
							pc.TipoSaldo,
							'04' AS Orden,
							'Debe' AS Columna
						FROM
							ap_obligacionescuenta oc
							INNER JOIN ac_mastplancuenta20 pc ON (oc.CodCuentaPub20 = pc.CodCuenta)
						WHERE
							oc.CodProveedor = '".$CodProveedor."' AND
							oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
							oc.NroDocumento = '".$NroDocumento."'
						GROUP BY oc.CodCuenta";
				$query_det = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));	$Secuencia=0;
				while($field_det = mysql_fetch_array($query_det)) {	$Secuencia++;
					$Monto = $field_det['MontoVoucher'];
					if ($field_det['Columna'] == "Haber") {
						$Monto = abs($Monto) * (-1);
						$Debitos += $Monto;
					} else {
						$style = "";
						$Monto = abs($Monto);
						$Creditos += $Monto;
					}
					$sql = "INSERT INTO ap_ordenpagocontabilidad
							SET
								Anio = '".$Anio."',
								CodOrganismo = '".$CodOrganismo."',
								NroOrden = '".$NroOrden."',
								CodContabilidad = 'F',
								Secuencia = '".$Secuencia."',
								CodCuenta = '".$field_det['CodCuenta']."',
								Monto = '".$Monto."',
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()";
					$query_insert = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		
		echo "|$NroOrden";
		mysql_query("COMMIT");
	}
	
	//	anular
	elseif ($accion == "anular") {
		mysql_query("BEGIN");
		list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
		$FechaActual = "$AnioActual-$MesActual-$DiaActual";
		$PeriodoActual = "$AnioActual-$MesActual";
		if ($Estado == "PR") {
			//	ELIMINO O ANULO
			if ($_PARAMETRO["OBLIGANUL"] == "S") {
				//	partidas
				$sql = "DELETE FROM ap_distribucionobligacion
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				//	impuestos
				$sql = "DELETE FROM ap_obligacionesimpuesto
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				//	cuentas/partidas
				$sql = "DELETE FROM ap_obligacionescuenta
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				//	obligacion
				$sql = "DELETE FROM ap_obligaciones
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				//	partidas
				$sql = "UPDATE ap_distribucionobligacion
						SET
							Estado = 'AN',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if ($FlagCompromiso == "S") {
					$sql = "UPDATE lg_distribucioncompromisos
							SET
								Estado = 'AN',
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()
							WHERE
								Anio = '".substr($Periodo, 0, 4)."' AND
								CodOrganismo = '".$CodOrganismo."' AND
								CodProveedor = '".$CodProveedor."' AND
								CodTipoDocumento = '".$CodTipoDocumento."' AND
								NroDocumento = '".$NroDocumento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
				//	obligacion
				$sql = "UPDATE ap_obligaciones
						SET
							Estado = 'AN',
							MotivoAnulacion = '".$MotivoAnulacion."',
							FechaAnulacion = NOW(),
							AnuladoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			//	actualizo documento
			$sql = "UPDATE ap_documentos
					SET
						Estado = 'PR',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".substr($Periodo, 0, 4)."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						CodProveedor = '".$CodProveedor."' AND
						ObligacionTipoDocumento = '".$CodTipoDocumento."' AND
						ObligacionNroDocumento = '".$NroDocumento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		elseif ($Estado == "RV") {
			if ($FlagCompromiso == "S") {
				$sql = "UPDATE lg_distribucioncompromisos
						SET
							Estado = 'PE',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							Anio = '".substr($Periodo, 0, 4)."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			//	partidas
			$sql = "UPDATE ap_distribucionobligacion
					SET
						Estado = 'PE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	vouchers
			if ($FlagContPendientePub20 == "N" && $_PARAMETRO['CONTPUB20'] == "S") {
				//	genero nuevo voucher
				$CodVoucher = substr($VoucherPub20, 0, 2);
				$NroVoucher = getCodigo("ac_vouchermast", "NroVoucher", 4, "CodOrganismo", $CodOrganismo, "Periodo", $PeriodoActual, "CodVoucher", $CodVoucher, "CodContabilidad", "F");
				$NroInterno = getCodigo("ac_vouchermast", "NroInterno", 10);
				$Voucher = "$CodVoucher-$NroVoucher";
				
				//	voucher mast
				$sql = "INSERT INTO ac_vouchermast (
									CodOrganismo,
									Periodo,
									Voucher,
									CodContabilidad,
									Prefijo,
									NroVoucher,
									CodVoucher,
									CodDependencia,
									CodModeloVoucher,
									CodSistemaFuente,
									Creditos,
									Debitos,
									Lineas,
									PreparadoPor,
									FechaPreparacion,
									AprobadoPor,
									FechaAprobacion,
									TituloVoucher,
									ComentariosVoucher,
									FechaVoucher,
									NroInterno,
									FlagTransferencia,
									Estado,
									CodLibroCont,
									UltimoUsuario,
									UltimaFecha
						)
								SELECT
									CodOrganismo,
									NOW() AS Periodo,
									'$Voucher' AS Voucher,
									'F' AS CodContabilidad,
									'$CodVoucher' AS Prefijo,
									'$NroVoucher' AS NroVoucher,
									'$CodVoucher' AS CodVoucher,
									CodDependencia,
									CodModeloVoucher,
									CodSistemaFuente,
									Creditos,
									Debitos,
									Lineas,
									'".$_SESSION["CODPERSONA_ACTUAL"]."' AS PreparadoPor,
									NOW() AS FechaPreparacion,
									'".$_SESSION["CODPERSONA_ACTUAL"]."' AS AprobadoPor,
									NOW() AS FechaAprobacion,
									CONCAT('$MotivoAnulacion (', TituloVoucher, ')') AS TituloVoucher,
									CONCAT('$MotivoAnulacion (', ComentariosVoucher, ')') AS ComentariosVoucher,
									NOW() AS FechaVoucher,
									'$NroInterno' AS NroInterno,
									FlagTransferencia,
									Estado,
									CodLibroCont,
									'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
									NOW() AS UltimaFecha
								FROM ac_vouchermast
								WHERE
									CodOrganismo = '".$CodOrganismo."' AND
									Periodo = '".$VoucherPeriodoPub20."' AND
									Voucher = '".$VoucherPub20."' AND
									CodContabilidad = 'F'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	voucher detalles
				$sql = "INSERT INTO ac_voucherdet (
									CodOrganismo,
									Periodo,
									Voucher,
									CodContabilidad,
									Linea,
									CodCuenta,
									MontoVoucher,
									MontoPost,
									CodPersona,
									NroCheque,
									FechaVoucher,
									CodCentroCosto,
									ReferenciaTipoDocumento,
									ReferenciaNroDocumento,
									Descripcion,
									Estado,
									UltimoUsuario,
									UltimaFecha
						)
								SELECT
									CodOrganismo,
									NOW() AS Periodo,
									'$Voucher' AS Voucher,
									'F' AS CodContabilidad,
									Linea,
									CodCuenta,
									(MontoVoucher*(-1)) AS MontoVoucher,
									(MontoPost*(-1)) AS MontoPost,
									CodPersona,
									NroCheque,
									NOW() AS FechaVoucher,
									CodCentroCosto,
									ReferenciaTipoDocumento,
									ReferenciaNroDocumento,
									CONCAT('$MotivoAnulacion (', Descripcion, ')') AS Descripcion,
									Estado,
									'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
									NOW() AS UltimaFecha
								FROM ac_voucherdet
								WHERE
									CodOrganismo = '".$CodOrganismo."' AND
									Periodo = '".$VoucherPeriodoPub20."' AND
									Voucher = '".$VoucherPub20."' AND
									CodContabilidad = 'F'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	obligacion
			$sql = "UPDATE ap_obligaciones
					SET
						Estado = 'PR',
						FlagContPendientePub20 = 'S',
						VoucherAnulPub20 = '".$Voucher."',
						PeriodoAnulPub20 = NOW(),
						MotivoAnulacion = '".$MotivoAnulacion."',
						FechaAnulacion = NOW(),
						AnuladoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//die("fin");
		echo "|$CodProveedor"."_"."$CodTipoDocumento"."_"."$NroDocumento";
		//die("");
		mysql_query("COMMIT");
	}
}

//	orden de pago
elseif ($modulo == "orden_pago") {
	$Concepto = changeUrl($Concepto);
	$MotivoAnulacion = changeUrl($MotivoAnulacion);
	
	//	modificar
	if ($accion == "modificar") {
		mysql_query("BEGIN");
		//-------------------
		list($DiaOrden, $MesOrden, $AnioOrden) = split("[./-]", $FechaOrdenPago);
		$Periodo = "$AnioOrden-$MesOrden";
		$Anio = $AnioOrden;
		//	actualizo orden
		$sql = "UPDATE ap_ordenpago
				SET
					FechaOrdenPago = '".formatFechaAMD($FechaOrdenPago)."',
					CodTipoPago = '".$CodTipoPago."',
					NroCuenta = '".$NroCuenta."',
					RevisadoPor = '".$RevisadoPor."',
					AprobadoPor = '".$AprobadoPor."',
					Concepto = '".$Concepto."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	actualizo obligacion
		$sql = "UPDATE ap_obligaciones
				SET
					CodTipoPago = '".$CodTipoPago."',
					NroCuenta = '".$NroCuenta."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	actualizo orden
		$sql = "UPDATE ap_ordenpagodistribucion
				SET Periodo = '".$Periodo."'
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//-------------------
		mysql_query("COMMIT");
	}
	
	//	pre-pago
	elseif ($accion == "prepago") {
		mysql_query("BEGIN");
		list($DiaPago, $MesPago, $AnioPago) = split("[./-]", $FechaPago);
		$Periodo = "$AnioPago-$MesPago";
		$Anio = $AnioPago;
		//	consulto orden
		$NroProceso = getCodigo("ap_pagos", "NroProceso", 6);
		$sql = "SELECT
					op.CodProveedor,
					op.CodTipoDocumento,
					op.NroDocumento,
					op.CodTipoPago,
					op.CodOrganismo,
					op.NroCuenta,
					op.NroOrden,
					op.Anio,
					op.NomProveedorPagar,
					op.FechaProgramada,
					op.MontoTotal,
					op.RevisadoPor,
					op.AprobadoPor,
					o.MontoObligacion,
					o.MontoImpuestoOtros
				FROM
					ap_ordenpago op
					INNER JOIN ap_obligaciones o ON (op.CodProveedor = o.CodProveedor AND
													 op.CodTipoDocumento = o.CodTipoDocumento AND
													 op.NroDocumento = o.NroDocumento)
				WHERE
					op.Anio = '".$Anio."' AND
					op.CodOrganismo = '".$CodOrganismo."' AND
					op.NroOrden = '".$NroOrden."'";
		$query_op = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_op) != 0) $field_op = mysql_fetch_array($query_op);
		
		//	actualizo orden de pago
		$sql = "UPDATE ap_ordenpago
				SET
					Estado = 'GE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$field_op['CodProveedor']."' AND
					CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
					NroDocumento = '".$field_op['NroDocumento']."' AND
					Estado = 'PE'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto pago
		$sql = "INSERT INTO ap_pagos
				SET
					NroProceso = '".$NroProceso."',
					Secuencia = '1',
					CodTipoPago = '".$field_op['CodTipoPago']."',
					CodOrganismo = '".$field_op['CodOrganismo']."',
					NroCuenta = '".$field_op['NroCuenta']."',
					CodProveedor = '".$field_op['CodProveedor']."',
					NroOrden = '".$field_op['NroOrden']."',
					Anio = '".$field_op['Anio']."',
					NomProveedorPagar = '".$field_op['NomProveedorPagar']."',
					MontoPago = '".$field_op['MontoTotal']."',
					MontoRetenido = '".$field_op['MontoImpuestoOtros']."',
					FechaPago = '".formatFechaAMD($FechaPago)."',
					OrigenGeneracion = 'A',
					Estado = 'GE',
					EstadoEntrega = 'C',
					EstadoChequeManual = '',
					FlagContabilizacionPendiente = 'S',
					FlagNegociacion = 'N',
					FlagNoNegociable = 'N',
					FlagCobrado = 'N',
					FlagCertificadoImpresion = 'N',
					FlagPagoDiferido = 'N',
					Periodo = '".$Periodo."',
					GeneradoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					ConformadoPor = '".$field_op['RevisadoPor']."',
					AprobadoPor = '".$field_op['AprobadoPor']."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		mysql_query("COMMIT");
	}
	
	//	imprimir/transferir
	elseif ($accion == "transferir") {
		mysql_query("BEGIN");
		list($DiaPago, $MesPago, $AnioPago) = split("[./-]", $FechaPago);
		$Periodo = "$AnioPago-$MesPago";
		$Anio = $AnioPago;
		//	consulto pagos
		$NroPago = getNroOrdenPago($CodTipoPago, $NroCuenta);
		$sql = "SELECT
					p.NroProceso,
					p.Secuencia,
					p.CodOrganismo,
					p.NroOrden,
					p.NroCuenta,
					p.CodTipoPago,
					p.CodProveedor,
					p.MontoPago,
					p.Anio,
					op.CodTipoDocumento,
					op.NroDocumento,
					op.Concepto,
					op.CodCentroCosto,
					op.Periodo
				FROM
					ap_pagos p
					INNER JOIN ap_ordenpago op ON (p.Anio = op.Anio AND
												   p.CodOrganismo = op.CodOrganismo AND
												   p.NroOrden = op.NroOrden)
				WHERE
					p.NroProceso = '".$NroProceso."' AND
					p.Secuencia = '".$Secuencia."'
				ORDER BY Secuencia";
		$query_op = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_op = mysql_fetch_array($query_op)) {
			//	consulto
			$sql = "SELECT TipoTransaccion, FlagVoucher
					FROM ap_bancotipotransaccion
					WHERE CodTipoTransaccion = '".$_PARAMETRO["TRANSPAGO"]."'";
			$query_flag = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_flag) != 0) $field_flag = mysql_fetch_array($query_flag);
			if ($field_flag['TipoTransaccion'] == "I") $signo = "1";
			elseif ($field_flag['TipoTransaccion'] == "E") $signo = "-1";
			
			//	inserto transaccion
			$NroTransaccion = getCodigo("ap_bancotransaccion", "NroTransaccion", 5);
			$sql = "INSERT INTO ap_bancotransaccion
					SET
						NroTransaccion = '".$NroTransaccion."',
						Secuencia = '1',
						CodOrganismo = '".$field_op['CodOrganismo']."',
						CodTipoTransaccion = '".$_PARAMETRO["TRANSPAGO"]."',
						TipoTransaccion = '".$field_flag['TipoTransaccion']."',
						NroCuenta = '".$field_op['NroCuenta']."',
						CodTipoDocumento = '".$field_op['CodTipoDocumento']."',
						CodProveedor = '".$field_op['CodProveedor']."',
						CodCentroCosto = '".$field_op['CodCentroCosto']."',
						PreparadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
						FechaPreparacion = NOW(),
						FechaTransaccion = '".formatFechaAMD($FechaPago)."',
						PeriodoContable = '".$Periodo."',
						Monto = '".($field_op['MontoPago']*$signo)."',
						Comentarios = '".$field_op['Concepto']."',
						PagoNroProceso = '".$field_op['NroProceso']."',
						PagoSecuencia = '".$field_op['Secuencia']."',
						NroPago = '".$NroPago."',
						FlagConciliacion = 'N',
						Estado = 'AP',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo obligacion
			$sql = "UPDATE ap_obligaciones
					SET
						FechaPago = '".formatFechaAMD($FechaPago)."',
						NroPago = '".$NroPago."',
						Estado = 'PA',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodProveedor = '".$field_op['CodProveedor']."' AND
						CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
						NroDocumento = '".$field_op['NroDocumento']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	si viene de nomina
			$FlagNomina = getVar2("ap_obligaciones", "FlagNomina", array("CodProveedor","CodTipoDocumento","NroDocumento"), array($field_op['CodProveedor'],$field_op['CodTipoDocumento'],$field_op['NroDocumento']));
			if ($FlagNomina == "S") {
				//	consulto datos de nomina
				$sql = "SELECT
							CodTipoNom,
							Periodo,
							PeriodoNomina,
							CodTipoProceso
						FROM pr_obligaciones
						WHERE
							CodProveedor = '".$field_op['CodProveedor']."' AND
							CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
							NroDocumento = '".$field_op['NroDocumento']."'";
				$query_proceso = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_proceso) != 0) $field_proceso = mysql_fetch_array($query_proceso);
				
				//	actualizo pr obligacion
				$sql = "UPDATE pr_obligaciones
						SET
							FechaPago = '".formatFechaAMD($FechaPago)."',
							NroPago = '".$NroPago."',
							Estado = 'PA',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							CodProveedor = '".$field_op['CodProveedor']."' AND
							CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
							NroDocumento = '".$field_op['NroDocumento']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	actualizo payroll
				$sql = "UPDATE pr_tiponominaempleado
						SET
							FechaPago = '".formatFechaAMD($FechaPago)."',
							EstadoPago = 'PA'
						WHERE
							CodProveedor = '".$field_op['CodProveedor']."' AND
							CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
							NroDocumento = '".$field_op['NroDocumento']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	actualizo prestaciones
				$sql = "UPDATE pr_liquidacionempleado
						SET
							Fliquidacion = '".formatFechaAMD($FechaPago)."',
							FechaPago = '".formatFechaAMD($FechaPago)."',
							EstadoPago = 'PA'
						WHERE
							CodProveedor = '".$field_op['CodProveedor']."' AND
							CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
							NroDocumento = '".$field_op['NroDocumento']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				##	prestaciones
				if ($field_proceso['CodTipoProceso'] == "PRS") {
					$sql = "UPDATE mastempleado
							SET Fliquidacion = '".formatFechaAMD($FechaPago)."'
							WHERE
								CodPersona IN (SELECT CodPersona
											   FROM pr_tiponominaempleado
											   WHERE
													CodProveedor = '".$field_op['CodProveedor']."' AND
													CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
													NroDocumento = '".$field_op['NroDocumento']."')";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
				
				//	verifico si se completaron todos
				$sql = "SELECT Estado
						FROM pr_obligaciones
						WHERE
							CodTipoNom = '".$field_proceso['CodTipoNom']."' AND
							Periodo = '".$field_proceso['PeriodoNomina']."' AND
							CodTipoProceso = '".$field_proceso['CodTipoProceso']."' AND
							CodOrganismo = '".$field_op['CodOrganismo']."' AND
							Estado <> 'PA'";
				$query_tne_verifico = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_tne_verifico) == 0) {
					//	consulto los datos de nomina
					$sql = "SELECT
								CodTipoNom,
								PeriodoNomina AS Periodo,
								CodOrganismo,
								CodTipoProceso
							FROM pr_obligaciones
							WHERE
								CodProveedor = '".$field_op['CodProveedor']."' AND
								CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
								NroDocumento = '".$field_op['NroDocumento']."'";
					$query_tne = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					if (mysql_num_rows($query_tne) != 0) $field_tne = mysql_fetch_array($query_tne);
					
					//	actualizo estado
					$sql = "UPDATE pr_procesoperiodo
							SET
								FechaPago = '".formatFechaAMD($FechaPago)."',
								EstadoPago = 'PA',
								FlagPagado = 'S'
							WHERE
								CodTipoNom = '".$field_tne['CodTipoNom']."' AND
								Periodo = '".$field_tne['Periodo']."' AND
								CodOrganismo = '".$field_tne['CodOrganismo']."' AND
								CodTipoProceso = '".$field_tne['CodTipoProceso']."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
				
				//	si es vacaciones
				if ($field_tne['CodTipoProceso'] == $_PARAMETRO['PROCESOBVC']) {
					//	inserto los dias pagados en vacaciones pagadas
					$sql = "SELECT
								tne.CodPersona,
								tne.CodProveedor,
								tne.Periodo,
								SUBSTRING(tne.Periodo, 1, 4) AS Anio,
								tne.CodTipoNom,
								tne.CodTipoDocumento,
								tne.NroDocumento,
								tne.FechaPago,
								tnec.Cantidad,
								pp.FechaDesde,
								pp.FechaHasta
							FROM
								pr_tiponominaempleado tne
								INNER JOIN pr_tiponominaempleadoconcepto tnec ON (tnec.CodTipoNom = tne.CodTipoNom AND
																				  tnec.Periodo = tne.Periodo AND
																				  tnec.CodPersona = tne.CodPersona AND
																				  tnec.CodOrganismo = tne.CodOrganismo AND
																				  tnec.CodTipoProceso = tne.CodTipoProceso)
								INNER JOIN pr_procesoperiodo pp ON (pp.CodTipoNom = tne.CodTipoNom AND
																	pp.Periodo = tne.Periodo AND
																	pp.CodOrganismo = tne.CodOrganismo AND
																	pp.CodTipoProceso = tne.CodTipoProceso)
							WHERE
								tne.CodProveedor = '".$field_op['CodProveedor']."' AND
								tne.CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
								tne.NroDocumento = '".$field_op['NroDocumento']."' AND
								tnec.CodConcepto = '".$_PARAMETRO['CONCEPTOBVC']."'";
					$query_detalle = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					while ($field_detalle = mysql_fetch_array($query_detalle)) {
						$Anio = $field_detalle['Anio'] - 1;
						$_NroPeriodo = getVar2("rh_vacacionperiodo", "NroPeriodo", array("CodPersona","Anio"), array($field_detalle['CodPersona'],$Anio));
						if ($_NroPeriodo == "") die("No se han actualizado los periodos vacacionales del empleado.");
						$_Secuencia = getCodigo("rh_vacacionpago", "Secuencia", 2, "CodPersona", $field_detalle['CodPersona'], "NroPeriodo", $_NroPeriodo, "CodTipoNom", $field_detalle['CodTipoNom']);	$_Secuencia = intval($_Secuencia);
						//	inserto
						$sql = "INSERT INTO rh_vacacionpago
								SET
									CodPersona = '".$field_detalle['CodPersona']."',
									NroPeriodo = '".$_NroPeriodo."',
									Secuencia = '".$_Secuencia."',
									CodTipoNom = '".$field_detalle['CodTipoNom']."',
									DiasPago = '".$field_detalle['Cantidad']."',
									Periodo = '".$field_detalle['Periodo']."',
									CodConcepto = '".$_PARAMETRO['CONCEPTOBVC']."',
									FechaInicio = '".$field_detalle['FechaDesde']."',
									FechaFin = '".$field_detalle['FechaHasta']."',
									CodProveedor = '".$field_detalle['CodProveedor']."',
									CodTipoDocumento = '".$field_detalle['CodTipoDocumento']."',
									NroDocumento = '".$field_detalle['NroDocumento']."',
									FechaPago = '".$field_detalle['FechaPago']."',
									UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
									UltimaFecha = NOW()";
						$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					}
				}
			}
			
			//	actualizo orden de pago
			$sql = "UPDATE ap_ordenpago
					SET
						Estado = 'PA',
						NroPago = '".$NroPago."',
						FechaTransferencia = '".formatFechaAMD($FechaPago)."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$field_op['Anio']."' AND
						CodOrganismo = '".$field_op['CodOrganismo']."' AND
						NroOrden = '".$field_op['NroOrden']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo orden de pago distribucion
			$sql = "UPDATE ap_ordenpagodistribucion
					SET
						Estado = 'PA',
						Periodo = '".$Periodo."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$field_op['Anio']."' AND
						CodOrganismo = '".$field_op['CodOrganismo']."' AND
						NroOrden = '".$field_op['NroOrden']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo pagos
			$sql = "UPDATE ap_pagos
					SET
						FechaPago = '".formatFechaAMD($FechaPago)."',
						NroPago = '".$NroPago."',
						Estado = 'IM',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						NroProceso = '".$NroProceso."' AND
						Secuencia = '".$Secuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo ultimo numero de pago
			$sql = "UPDATE ap_ctabancariatipopago
					SET
						UltimoNumero = '".$NroPago."',	
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						NroCuenta = '".$NroCuenta."' AND
						CodTipoPago = '".$CodTipoPago."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	consulto e inserto las retenciones
		$sql = "SELECT
					op.CodOrganismo,
					op.NroOrden,
					op.CodProveedor,
					op.CodTipoDocumento,
					op.NroDocumento,
					o.NroControl,
					o.FechaRegistro,
					o.FechaFactura,
					oi.MontoImpuesto AS MontoRetenido,
					oi.FactorPorcentaje AS Porcentaje,
					(o.MontoAfecto + o.MontoNoAfecto + o.MontoImpuesto) AS MontoFactura,
					o.MontoImpuesto,
					o.MontoAfecto,
					o.MontoNoAfecto,
					i.CodImpuesto,
					i.TipoComprobante
				FROM
					ap_ordenpago op
					INNER JOIN ap_obligaciones o ON (op.NroPago = o.NroPago)
					INNER JOIN ap_obligacionesimpuesto oi ON (o.CodProveedor = oi.CodProveedor AND
													  		  o.CodTipoDocumento = oi.CodTipoDocumento AND
													  		  o.NroDocumento = oi.NroDocumento)
					INNER JOIN mastimpuestos i ON (oi.CodImpuesto = i.CodImpuesto)
				WHERE op.NroPago = '".$NroPago."'";
		$query_retenciones = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_retenciones = mysql_fetch_array($query_retenciones)) {
			$NroComprobante = getCodigo_3("ap_retenciones", "NroComprobante", "Anio", "TipoComprobante", $Anio, $field_retenciones['TipoComprobante'], 8);
			$sql = "INSERT INTO ap_retenciones
					SET
						Anio = '".$Anio."',
						CodOrganismo = '".$field_retenciones['CodOrganismo']."',
						NroOrden = '".$field_retenciones['NroOrden']."',
						NroComprobante = '".$NroComprobante."',
						PeriodoFiscal = '".$Periodo."',
						CodImpuesto = '".$field_retenciones['CodImpuesto']."',
						FechaComprobante = '".formatFechaAMD($FechaPago)."',
						CodProveedor = '".$field_retenciones['CodProveedor']."',
						CodTipoDocumento = '".$field_retenciones['CodTipoDocumento']."',
						NroDocumento = '".$field_retenciones['NroDocumento']."',
						NroControl = '".$field_retenciones['NroControl']."',
						FechaFactura = '".$field_retenciones['FechaFactura']."',
						MontoAfecto = '".$field_retenciones['MontoAfecto']."',
						MontoNoAfecto = '".$field_retenciones['MontoNoAfecto']."',
						MontoImpuesto = '".$field_retenciones['MontoImpuesto']."',
						MontoFactura = '".$field_retenciones['MontoFactura']."',
						Porcentaje = '".$field_retenciones['Porcentaje']."',
						MontoRetenido = '".$field_retenciones['MontoRetenido']."',
						TipoComprobante = '".$field_retenciones['TipoComprobante']."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		mysql_query("COMMIT");
	}
	
	//	anular
	elseif ($accion == "anular") {
		mysql_query("BEGIN");
		list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
		$FechaActual = "$AnioActual-$MesActual-$DiaActual";
		$PeriodoActual = "$AnioActual-$MesActual";
		
		//	vouchers
		if ($FlagContabilizacionPendiente == "N" && $_PARAMETRO['CONTONCO'] == "S") {
			//	genero nuevo voucher
			$CodVoucher1 = substr($VoucherProv, 0, 2);
			$NroVoucher1 = getCodigo("ac_vouchermast", "NroVoucher", 4, "CodOrganismo", $CodOrganismo, "Periodo", $PeriodoActual, "CodVoucher", $CodVoucher1, "CodContabilidad", "T");
			$NroInterno1 = getCodigo("ac_vouchermast", "NroInterno", 10);
			$Voucher1 = "$CodVoucher1-$NroVoucher1";
			
			//	voucher mast
			$sql = "INSERT INTO ac_vouchermast (
								CodOrganismo,
								Periodo,
								Voucher,
								CodContabilidad,
								Prefijo,
								NroVoucher,
								CodVoucher,
								CodDependencia,
								CodModeloVoucher,
								CodSistemaFuente,
								Creditos,
								Debitos,
								Lineas,
								PreparadoPor,
								FechaPreparacion,
								AprobadoPor,
								FechaAprobacion,
								TituloVoucher,
								ComentariosVoucher,
								FechaVoucher,
								NroInterno,
								FlagTransferencia,
								Estado,
								CodLibroCont,
								UltimoUsuario,
								UltimaFecha
					)
							SELECT
								CodOrganismo,
								NOW() AS Periodo,
								'$Voucher1' AS Voucher,
								'T' AS CodContabilidad,
								'$CodVoucher1' AS Prefijo,
								'$NroVoucher1' AS NroVoucher,
								'$CodVoucher1' AS CodVoucher,
								CodDependencia,
								CodModeloVoucher,
								CodSistemaFuente,
								Creditos,
								Debitos,
								Lineas,
								'".$_SESSION["CODPERSONA_ACTUAL"]."' AS PreparadoPor,
								NOW() AS FechaPreparacion,
								'".$_SESSION["CODPERSONA_ACTUAL"]."' AS AprobadoPor,
								NOW() AS FechaAprobacion,
								CONCAT('$MotivoAnulacion (', TituloVoucher, ')') AS TituloVoucher,
								CONCAT('$MotivoAnulacion (', ComentariosVoucher, ')') AS ComentariosVoucher,
								NOW() AS FechaVoucher,
								'$NroInterno1' AS NroInterno,
								FlagTransferencia,
								Estado,
								CodLibroCont,
								'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
								NOW() AS UltimaFecha
							FROM ac_vouchermast
							WHERE
								CodOrganismo = '".$CodOrganismo."' AND
								Periodo = '".$PeriodoProv."' AND
								Voucher = '".$VoucherProv."' AND
								CodContabilidad = 'T'";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	voucher detalles
			$sql = "INSERT INTO ac_voucherdet (
								CodOrganismo,
								Periodo,
								Voucher,
								CodContabilidad,
								Linea,
								CodCuenta,
								MontoVoucher,
								MontoPost,
								CodPersona,
								NroCheque,
								FechaVoucher,
								CodCentroCosto,
								ReferenciaTipoDocumento,
								ReferenciaNroDocumento,
								Descripcion,
								Estado,
								UltimoUsuario,
								UltimaFecha
					)
							SELECT
								CodOrganismo,
								NOW() AS Periodo,
								'$Voucher1' AS Voucher,
								'T' AS CodContabilidad,
								Linea,
								CodCuenta,
								(MontoVoucher*(-1)) AS MontoVoucher,
								(MontoPost*(-1)) AS MontoPost,
								CodPersona,
								NroCheque,
								NOW() AS FechaVoucher,
								CodCentroCosto,
								ReferenciaTipoDocumento,
								ReferenciaNroDocumento,
								CONCAT('$MotivoAnulacion (', Descripcion, ')') AS Descripcion,
								Estado,
								'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
								NOW() AS UltimaFecha
							FROM ac_voucherdet
							WHERE
								CodOrganismo = '".$CodOrganismo."' AND
								Periodo = '".$PeriodoProv."' AND
								Voucher = '".$VoucherProv."' AND
								CodContabilidad = 'T'";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		if ($FlagContPendienteOrdPub20 == "N" && $_PARAMETRO['CONTPUB20'] == "S") {
			//	genero nuevo voucher
			$CodVoucher2 = substr($VoucherOrdPago, 0, 2);
			$NroVoucher2 = getCodigo("ac_vouchermast", "NroVoucher", 4, "CodOrganismo", $CodOrganismo, "Periodo", $PeriodoActual, "CodVoucher", $CodVoucher2, "CodContabilidad", "F");
			$NroInterno2 = getCodigo("ac_vouchermast", "NroInterno", 10);
			$Voucher2 = "$CodVoucher2-$NroVoucher2";
			
			//	voucher mast
			$sql = "INSERT INTO ac_vouchermast (
								CodOrganismo,
								Periodo,
								Voucher,
								CodContabilidad,
								Prefijo,
								NroVoucher,
								CodVoucher,
								CodDependencia,
								CodModeloVoucher,
								CodSistemaFuente,
								Creditos,
								Debitos,
								Lineas,
								PreparadoPor,
								FechaPreparacion,
								AprobadoPor,
								FechaAprobacion,
								TituloVoucher,
								ComentariosVoucher,
								FechaVoucher,
								NroInterno,
								FlagTransferencia,
								Estado,
								CodLibroCont,
								UltimoUsuario,
								UltimaFecha
					)
							SELECT
								CodOrganismo,
								NOW() AS Periodo,
								'$Voucher2' AS Voucher,
								'F' AS CodContabilidad,
								'$CodVoucher2' AS Prefijo,
								'$NroVoucher2' AS NroVoucher,
								'$CodVoucher2' AS CodVoucher,
								CodDependencia,
								CodModeloVoucher,
								CodSistemaFuente,
								Creditos,
								Debitos,
								Lineas,
								'".$_SESSION["CODPERSONA_ACTUAL"]."' AS PreparadoPor,
								NOW() AS FechaPreparacion,
								'".$_SESSION["CODPERSONA_ACTUAL"]."' AS AprobadoPor,
								NOW() AS FechaAprobacion,
								CONCAT('$MotivoAnulacion (', TituloVoucher, ')') AS TituloVoucher,
								CONCAT('$MotivoAnulacion (', ComentariosVoucher, ')') AS ComentariosVoucher,
								NOW() AS FechaVoucher,
								'$NroInterno2' AS NroInterno,
								FlagTransferencia,
								Estado,
								CodLibroCont,
								'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
								NOW() AS UltimaFecha
							FROM ac_vouchermast
							WHERE
								CodOrganismo = '".$CodOrganismo."' AND
								Periodo = '".$PeriodoOrdPago."' AND
								Voucher = '".$VoucherOrdPago."' AND
								CodContabilidad = 'F'";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	voucher detalles
			$sql = "INSERT INTO ac_voucherdet (
								CodOrganismo,
								Periodo,
								Voucher,
								CodContabilidad,
								Linea,
								CodCuenta,
								MontoVoucher,
								MontoPost,
								CodPersona,
								NroCheque,
								FechaVoucher,
								CodCentroCosto,
								ReferenciaTipoDocumento,
								ReferenciaNroDocumento,
								Descripcion,
								Estado,
								UltimoUsuario,
								UltimaFecha
					)
							SELECT
								CodOrganismo,
								NOW() AS Periodo,
								'$Voucher2' AS Voucher,
								'F' AS CodContabilidad,
								Linea,
								CodCuenta,
								(MontoVoucher*(-1)) AS MontoVoucher,
								(MontoPost*(-1)) AS MontoPost,
								CodPersona,
								NroCheque,
								NOW() AS FechaVoucher,
								CodCentroCosto,
								ReferenciaTipoDocumento,
								ReferenciaNroDocumento,
								CONCAT('$MotivoAnulacion (', Descripcion, ')') AS Descripcion,
								Estado,
								'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
								NOW() AS UltimaFecha
							FROM ac_voucherdet
							WHERE
								CodOrganismo = '".$CodOrganismo."' AND
								Periodo = '".$PeriodoOrdPago."' AND
								Voucher = '".$VoucherOrdPago."' AND
								CodContabilidad = 'F'";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
			
		//	actualizo obligacion
		$sql = "UPDATE ap_obligaciones
				SET
					VoucherAnulacion = '".$Voucher1."',
					PeriodoAnulacion = NOW(),
					FlagContabilizacionPendiente = 'S',
					Estado = 'RV',
					Voucher = '',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo orden de pago
		$sql = "UPDATE ap_ordenpago
				SET
					Estado = 'AN',
					Voucher = '',
					MotivoAnulacion = '".$MotivoAnulacion."',
					AnuladoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAnulacion = NOW(),
					VoucherPagoAnulacion = '".$Voucher2."',
					PeriodoPagoAnulacion = NOW(),					
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	
		//	documentos
		$sql = "SELECT *
				FROM ap_documentos
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					ObligacionTipoDocumento = '".$CodTipoDocumento."' AND
					ObligacionNroDocumento = '".$NroDocumento."'";
		$query_documentos = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));	$linea=0;
		while ($field_documentos = mysql_fetch_array($query_documentos)) {
			//	actualizo (orden)
			if ($field_documentos['ReferenciaTipoDocumento'] == "OC") {
				$sql = "UPDATE lg_ordencompra 
						SET
							MontoPendiente = (MontoPendiente + (".floatval($field_documentos['MontoAfecto'])." + ".floatval($field_documentos['MontoNoAfecto'])." + ".floatval($field_documentos['MontoImpuestos']).")),
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$field_documentos['ReferenciaNroDocumento']."'";
				$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				$sql = "UPDATE lg_ordenservicio
						SET
							MontoGastado = (MontoGastado + (".floatval($field_documentos['MontoAfecto'])." + ".floatval($field_documentos['MontoNoAfecto'])." + ".floatval($field_documentos['MontoImpuestos']).")),
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$field_documentos['ReferenciaNroDocumento']."'";
				$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	actualizo orden distribucion
		$sql = "UPDATE ap_ordenpagodistribucion
				SET
					Estado = 'AN',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		echo "|$Anio"."_"."$CodOrganismo"."_"."$NroOrden";
		mysql_query("COMMIT");
	}
}

//	pagos (modificacion restringida)
elseif ($modulo == "pago") {
	$MotivoAnulacion = changeUrl($MotivoAnulacion);
	
	//	modificar
	if ($accion == "modificar") {
		list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
		list($d, $m, $a) = split("[/.-]", $FechaPago);
		if ($m != $Mes) die("La fecha de pago ingresada pertenece a un periodo distinto a lo contabilizado");
		
		//	actualizo orden
		$sql = "UPDATE ap_pagos
				SET
					GeneradoPor = '".$GeneradoPor."',
					ConformadoPor = '".$ConformadoPor."',
					AprobadoPor = '".$AprobadoPor."',
					FechaPago = '".formatFechaAMD($FechaPago)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					NroProceso = '".$NroProceso."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	anular
	elseif ($accion == "anular") {
		mysql_query("BEGIN");
		//-------------------
		list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
		$FechaActual = "$AnioActual-$MesActual-$DiaActual";
		$PeriodoActual = "$AnioActual-$MesActual";
		//	consulto pago
		$sql = "SELECT
					p.NroProceso,
					p.Secuencia,
					p.CodOrganismo,
					p.NroOrden,
					p.NroCuenta,
					p.CodTipoPago,
					p.CodProveedor,
					p.MontoPago,
					p.Anio,
					p.FlagContabilizacionPendiente,
					p.FlagContPendientePub20,
					op.CodTipoDocumento,
					op.NroDocumento,
					op.Concepto,
					op.CodCentroCosto
				FROM
					ap_pagos p
					INNER JOIN ap_ordenpago op ON (p.Anio = op.Anio AND
												   p.CodOrganismo = op.CodOrganismo AND
												   p.NroOrden = op.NroOrden)
				WHERE
					p.NroProceso = '".$NroProceso."' AND
					p.Secuencia = '".$Secuencia."'
				ORDER BY Secuencia";
		$query_op = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_op = mysql_fetch_array($query_op)) {
			//	consulto
			$sql = "SELECT TipoTransaccion, FlagVoucher 
					FROM ap_bancotipotransaccion 
					WHERE CodTipoTransaccion = '".$_PARAMETRO["TRANSANUL"]."'";
			$query_flag = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_flag) != 0) $field_flag = mysql_fetch_array($query_flag);
			if ($field_flag['TipoTransaccion'] == "I") $signo = "1";
			elseif ($field_flag['TipoTransaccion'] == "E") $signo = "-1";
			
			//	vouchers
			if ($field_op['FlagContabilizacionPendiente'] == "N" && $_PARAMETRO['CONTONCO'] == "S") {
				//	genero nuevo voucher
				$NroVoucher1 = getCodigo("ac_vouchermast", "NroVoucher", 4, "CodOrganismo", $CodOrganismo, "Periodo", $PeriodoActual, "CodVoucher", $CodVoucher, "CodContabilidad", "T");
				$NroInterno1 = getCodigo("ac_vouchermast", "NroInterno", 10);
				$Voucher1 = "$CodVoucher-$NroVoucher1";
				
				//	voucher mast
				$sql = "INSERT INTO ac_vouchermast (
									CodOrganismo,
									Periodo,
									Voucher,
									CodContabilidad,
									Prefijo,
									NroVoucher,
									CodVoucher,
									CodDependencia,
									CodModeloVoucher,
									CodSistemaFuente,
									Creditos,
									Debitos,
									Lineas,
									PreparadoPor,
									FechaPreparacion,
									AprobadoPor,
									FechaAprobacion,
									TituloVoucher,
									ComentariosVoucher,
									FechaVoucher,
									NroInterno,
									FlagTransferencia,
									Estado,
									CodLibroCont,
									UltimoUsuario,
									UltimaFecha
						)
								SELECT
									CodOrganismo,
									'".$PeriodoActual."' AS Periodo,
									'$Voucher1' AS Voucher,
									'T' AS CodContabilidad,
									'$CodVoucher' AS Prefijo,
									'$NroVoucher1' AS NroVoucher,
									'$CodVoucher' AS CodVoucher,
									CodDependencia,
									CodModeloVoucher,
									CodSistemaFuente,
									Creditos,
									Debitos,
									Lineas,
									'".$_SESSION["CODPERSONA_ACTUAL"]."' AS PreparadoPor,
									NOW() AS FechaPreparacion,
									'".$_SESSION["CODPERSONA_ACTUAL"]."' AS AprobadoPor,
									NOW() AS FechaAprobacion,
									CONCAT('$MotivoAnulacion (', TituloVoucher, ')') AS TituloVoucher,
									CONCAT('$MotivoAnulacion (', ComentariosVoucher, ')') AS ComentariosVoucher,
									NOW() AS FechaVoucher,
									'".$NroInterno1."' AS NroInterno,
									FlagTransferencia,
									Estado,
									CodLibroCont,
									'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
									NOW() AS UltimaFecha
								FROM ac_vouchermast
								WHERE
									CodOrganismo = '".$CodOrganismo."' AND
									Periodo = '".$Periodo."' AND
									Voucher = '".$VoucherPago."' AND
									CodContabilidad = 'T'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	voucher detalles
				$sql = "INSERT INTO ac_voucherdet (
									CodOrganismo,
									Periodo,
									Voucher,
									CodContabilidad,
									Linea,
									CodCuenta,
									MontoVoucher,
									MontoPost,
									CodPersona,
									NroCheque,
									FechaVoucher,
									CodCentroCosto,
									ReferenciaTipoDocumento,
									ReferenciaNroDocumento,
									Descripcion,
									Estado,
									UltimoUsuario,
									UltimaFecha
						)
								SELECT
									CodOrganismo,
									'".$PeriodoActual."' AS Periodo,
									'$Voucher1' AS Voucher,
									'T' AS CodContabilidad,
									Linea,
									CodCuenta,
									(MontoVoucher*(-1)) AS MontoVoucher,
									(MontoPost*(-1)) AS MontoPost,
									CodPersona,
									NroCheque,
									NOW() AS FechaVoucher,
									CodCentroCosto,
									ReferenciaTipoDocumento,
									ReferenciaNroDocumento,
									CONCAT('$MotivoAnulacion (', Descripcion, ')') AS Descripcion,
									Estado,
									'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
									NOW() AS UltimaFecha
								FROM ac_voucherdet
								WHERE
									CodOrganismo = '".$CodOrganismo."' AND
									Periodo = '".$Periodo."' AND
									Voucher = '".$VoucherPago."' AND
									CodContabilidad = 'T'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	vouchers
			if ($field_op['FlagContPendientePub20'] == "N" && $_PARAMETRO['CONTPUB20'] == "S") {
				//	genero nuevo voucher
				$NroVoucher2 = getCodigo("ac_vouchermast", "NroVoucher", 4, "CodOrganismo", $CodOrganismo, "Periodo", $PeriodoActual, "CodVoucher", $CodVoucherPub20, "CodContabilidad", "F");
				$NroInterno2 = getCodigo("ac_vouchermast", "NroInterno", 10);
				$Voucher2 = "$CodVoucherPub20-$NroVoucher2";
				
				//	voucher mast
				$sql = "INSERT INTO ac_vouchermast (
									CodOrganismo,
									Periodo,
									Voucher,
									CodContabilidad,
									Prefijo,
									NroVoucher,
									CodVoucher,
									CodDependencia,
									CodModeloVoucher,
									CodSistemaFuente,
									Creditos,
									Debitos,
									Lineas,
									PreparadoPor,
									FechaPreparacion,
									AprobadoPor,
									FechaAprobacion,
									TituloVoucher,
									ComentariosVoucher,
									FechaVoucher,
									NroInterno,
									FlagTransferencia,
									Estado,
									CodLibroCont,
									UltimoUsuario,
									UltimaFecha
						)
								SELECT
									CodOrganismo,
									'".$PeriodoActual."' AS Periodo,
									'$Voucher2' AS Voucher,
									'F' AS CodContabilidad,
									'$CodVoucherPub20' AS Prefijo,
									'$NroVoucher2' AS NroVoucher,
									'$CodVoucherPub20' AS CodVoucher,
									CodDependencia,
									CodModeloVoucher,
									CodSistemaFuente,
									Creditos,
									Debitos,
									Lineas,
									'".$_SESSION["CODPERSONA_ACTUAL"]."' AS PreparadoPor,
									NOW() AS FechaPreparacion,
									'".$_SESSION["CODPERSONA_ACTUAL"]."' AS AprobadoPor,
									NOW() AS FechaAprobacion,
									CONCAT('$MotivoAnulacion (', TituloVoucher, ')') AS TituloVoucher,
									CONCAT('$MotivoAnulacion (', ComentariosVoucher, ')') AS ComentariosVoucher,
									NOW() AS FechaVoucher,
									'".$NroInterno2."' AS NroInterno,
									FlagTransferencia,
									Estado,
									CodLibroCont,
									'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
									NOW() AS UltimaFecha
								FROM ac_vouchermast
								WHERE
									CodOrganismo = '".$CodOrganismo."' AND
									Periodo = '".$PeriodoPagoPub20."' AND
									Voucher = '".$VoucherPagoPub20."' AND
									CodContabilidad = 'F'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	voucher detalles
				$sql = "INSERT INTO ac_voucherdet (
									CodOrganismo,
									Periodo,
									Voucher,
									CodContabilidad,
									Linea,
									CodCuenta,
									MontoVoucher,
									MontoPost,
									CodPersona,
									NroCheque,
									FechaVoucher,
									CodCentroCosto,
									ReferenciaTipoDocumento,
									ReferenciaNroDocumento,
									Descripcion,
									Estado,
									UltimoUsuario,
									UltimaFecha
						)
								SELECT
									CodOrganismo,
									'".$PeriodoActual."' AS Periodo,
									'$Voucher2' AS Voucher,
									'F' AS CodContabilidad,
									Linea,
									CodCuenta,
									(MontoVoucher*(-1)) AS MontoVoucher,
									(MontoPost*(-1)) AS MontoPost,
									CodPersona,
									NroCheque,
									NOW() AS FechaVoucher,
									CodCentroCosto,
									ReferenciaTipoDocumento,
									ReferenciaNroDocumento,
									CONCAT('$MotivoAnulacion (', Descripcion, ')') AS Descripcion,
									Estado,
									'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
									NOW() AS UltimaFecha
								FROM ac_voucherdet
								WHERE
									CodOrganismo = '".$CodOrganismo."' AND
									Periodo = '".$PeriodoPagoPub20."' AND
									Voucher = '".$VoucherPagoPub20."' AND
									CodContabilidad = 'F'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	inserto transaccion
			$NroTransaccion = getCodigo("ap_bancotransaccion", "NroTransaccion", 5);
			$sql = "INSERT INTO ap_bancotransaccion
					SET
						NroTransaccion = '".$NroTransaccion."',
						Secuencia = '1',
						CodOrganismo = '".$field_op['CodOrganismo']."',
						CodTipoTransaccion = '".$_PARAMETRO["TRANSANUL"]."',
						TipoTransaccion = '".$field_flag['TipoTransaccion']."',
						NroCuenta = '".$field_op['NroCuenta']."',
						CodTipoDocumento = '".$field_op['CodTipoDocumento']."',
						CodProveedor = '".$field_op['CodProveedor']."',
						CodCentroCosto = '".$field_op['CodCentroCosto']."',
						PreparadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
						FechaPreparacion = NOW(),
						FechaTransaccion = NOW(),
						PeriodoContable = '".$PeriodoActual."',
						Monto = '".($field_op['MontoPago']*$signo)."',
						Comentarios = '".$field_op['Concepto']."',
						PagoNroProceso = '".$field_op['NroProceso']."',
						PagoSecuencia = '".$field_op['Secuencia']."',
						NroPago = '".$NroPago."',
						FlagConciliacion = 'N',
						Estado = 'AP',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo obligacion
			$sql = "UPDATE ap_obligaciones
					SET
						Estado = 'AP',
						NroPago = '',
						NroProceso = '',
						ProcesoSecuencia = '',
						FechaPago = '',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodProveedor = '".$field_op['CodProveedor']."' AND
						CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
						NroDocumento = '".$field_op['NroDocumento']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	si viene de nomina
			$FlagNomina = getVar2("ap_obligaciones", "FlagNomina", array("CodProveedor","CodTipoDocumento","NroDocumento"), array($field_op['CodProveedor'],$field_op['CodTipoDocumento'],$field_op['NroDocumento']));
			if ($FlagNomina == "S") {
				//	actualizo pr obligacion
				$sql = "UPDATE pr_obligaciones
						SET
							FechaPago = '',
							NroPago = '',
							Estado = 'PE',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							CodProveedor = '".$field_op['CodProveedor']."' AND
							CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
							NroDocumento = '".$field_op['NroDocumento']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	actualizo payroll
				$sql = "UPDATE pr_tiponominaempleado
						SET
							FechaPago = '',
							EstadoPago = 'TR'
						WHERE
							CodProveedor = '".$field_op['CodProveedor']."' AND
							CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
							NroDocumento = '".$field_op['NroDocumento']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	actualizo prestaciones
				$sql = "UPDATE pr_liquidacionempleado
						SET
							Fliquidacion = '',
							FechaPago = '',
							EstadoPago = 'TR'
						WHERE
							CodProveedor = '".$field_op['CodProveedor']."' AND
							CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
							NroDocumento = '".$field_op['NroDocumento']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				##	prestaciones
				if ($field_proceso['CodTipoProceso'] == "PRS") {
					$sql = "UPDATE mastempleado
							SET Fliquidacion = ''
							WHERE
								CodPersona IN (SELECT CodPersona
											   FROM pr_tiponominaempleado
											   WHERE
													CodProveedor = '".$field_op['CodProveedor']."' AND
													CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
													NroDocumento = '".$field_op['NroDocumento']."')";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
				
				//	consulto de la obligacion de nomina
				$sql = "SELECT
							CodTipoNom,
							PeriodoNomina,
							Periodo,
							CodOrganismo,
							CodTipoProceso
						FROM pr_obligaciones
						WHERE
							CodProveedor = '".$field_op['CodProveedor']."' AND
							CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
							NroDocumento = '".$field_op['NroDocumento']."'";
				$query_tne = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_tne) != 0) $field_tne = mysql_fetch_array($query_tne);
				
				//	actualizo estado
				$sql = "UPDATE pr_procesoperiodo
						SET
							FechaPago = '',
							EstadoPago = 'PE',
							FlagPagado = 'N'
						WHERE
							CodTipoNom = '".$field_tne['CodTipoNom']."' AND
							Periodo = '".$field_tne['PeriodoNomina']."' AND
							CodOrganismo = '".$field_tne['CodOrganismo']."' AND
							CodTipoProceso = '".$field_tne['CodTipoProceso']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	si es vacaciones
				if ($field_tne['CodTipoProceso'] == $_PARAMETRO['PROCESOBVC']) {
					//	elimino los dias pagados en vacaciones pagadas
					$sql = "SELECT
								tne.CodPersona,
								tne.Periodo,
								SUBSTRING(tne.Periodo, 1, 4) AS Anio,
								tne.CodTipoNom,
								tne.CodTipoDocumento,
								tne.NroDocumento,
								tne.FechaPago,
								tnec.Cantidad,
								pp.FechaDesde,
								pp.FechaHasta
							FROM
								pr_tiponominaempleado tne
								INNER JOIN pr_tiponominaempleadoconcepto tnec ON (tnec.CodTipoNom = tne.CodTipoNom AND
																				  tnec.Periodo = tne.Periodo AND
																				  tnec.CodPersona = tne.CodPersona AND
																				  tnec.CodOrganismo = tne.CodOrganismo AND
																				  tnec.CodTipoProceso = tne.CodTipoProceso)
								INNER JOIN pr_procesoperiodo pp ON (pp.CodTipoNom = tne.CodTipoNom AND
																	pp.Periodo = tne.Periodo AND
																	pp.CodOrganismo = tne.CodOrganismo AND
																	pp.CodTipoProceso = tne.CodTipoProceso)
							WHERE
								tne.CodProveedor = '".$field_op['CodProveedor']."' AND
								tne.CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
								tne.NroDocumento = '".$field_op['NroDocumento']."' AND
								tnec.CodConcepto = '".$_PARAMETRO['CONCEPTOBVC']."'";
					$query_detalle = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					while ($field_detalle = mysql_fetch_array($query_detalle)) {
						//	elimino
						$sql = "DELETE FROM rh_vacacionpago
								WHERE
									CodPersona = '".$field_detalle['CodPersona']."' AND
									CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
									NroDocumento = '".$field_op['NroDocumento']."'";
						$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					}
				}
			}
			
			//	actualizo orden de pago
			$sql = "UPDATE ap_ordenpago
					SET
						Estado = 'PE',
						NroPago = '',
						FechaTransferencia = '0000-00-00',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$field_op['Anio']."' AND
						CodOrganismo = '".$field_op['CodOrganismo']."' AND
						NroOrden = '".$field_op['NroOrden']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo orden de pago distribucion
			$sql = "UPDATE ap_ordenpagodistribucion
					SET
						Estado = 'PE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$field_op['Anio']."' AND
						CodOrganismo = '".$field_op['CodOrganismo']."' AND
						NroOrden = '".$field_op['NroOrden']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo pagos
			$sql = "UPDATE ap_pagos
					SET
						FechaAnulacion = NOW(),
						MotivoAnulacion = '".$MotivoAnulacion."',
						VoucherAnulacion = '".$Voucher1."',
						PeriodoAnulacion = '".$PeriodoActual."',
						VoucherAnulPub20 = '".$Voucher2."',
						PeriodoAnulPub20 = '".$PeriodoActual."',
						AnuladoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
						Estado = 'AN',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						NroProceso = '".$NroProceso."' AND
						Secuencia = '".$Secuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
			//	anulo retenciones
			$sql = "UPDATE ap_retenciones
					SET
						Estado = 'AN',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodOrganismo = '".$CodOrganismo."' AND
						Anio = '".$Anio."' AND
						NroOrden = '".$NroOrden."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		echo "|$NroProceso"."_"."$Secuencia";
		//-------------------
		mysql_query("COMMIT");
	}
}

//	
elseif ($modulo == "registro_compra") {
	//	importar
	if ($accion == "importar") {
		$nrocp = 0;
		$nrocf = 0;
		//	eliminar los registros del periodo actual
		$sql = "DELETE FROM ap_registrocompras
				WHERE
					Periodo = '".$Periodo."' AND
					(SistemaFuente = 'CP' OR SistemaFuente = 'CC')";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto las obligaciones
		if ($FlagCP == "S") {
			$sql = "SELECT
						o.CodProveedor,
						o.CodTipoDocumento,
						o.NroDocumento,
						mp.NomCompleto,
						mp.DocFiscal,
						o.CodOrganismo,
						o.FechaRegistro,
						o.Voucher,
						o.Periodo,
						o.NroRegistro,
						o.NroControl,
						'N' AS FlagCajaChica,
						o.Comentarios,
						o.MontoAfecto,
						o.MontoNoAfecto,
						o.MontoImpuestoOtros,
						o.MontoObligacion,
						o.MontoImpuesto
					FROM
						ap_obligaciones o
						INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
						INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
					WHERE
						o.Periodo = '".$Periodo."' AND
						o.CodOrganismo = '".$CodOrganismo."' AND
						(o.Estado = 'AP' OR o.Estado = 'PA') AND
						td.FlagFiscal = 'S'";
			$query_obligaciones = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field_obligaciones = mysql_fetch_array($query_obligaciones)) {	$nrocp++;
				//	consulto el monto de los impuestos 
				$sql = "SELECT SUM(oi.MontoImpuesto) AS MontoImpuesto
						FROM
							ap_obligacionesimpuesto oi
							INNER JOIN mastimpuestos i ON (oi.CodImpuesto = i.CodImpuesto)
						WHERE
							oi.CodProveedor = '".$field_obligaciones['CodProveedor']."' AND
							oi.CodTipoDocumento = '".$field_obligaciones['CodTipoDocumento']."' AND
							oi.NroDocumento = '".$field_obligaciones['NroDocumento']."' AND
							i.CodRegimenFiscal = 'R' AND
							i.TipoComprobante = 'IVA'";
				$query_impuestos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_impuestos) != 0) $field_impuestos = mysql_fetch_array($query_impuestos);
				
				//	inserto el registro de compras
				$sql = "INSERT INTO ap_registrocompras (
									Periodo,
									SistemaFuente,
									Secuencia,
									CodProveedor,
									CodTipoDocumento,
									NroDocumento,
									NomProveedor,
									RifProveedor,
									CodOrganismo,
									FechaDocumento,
									Voucher,
									VoucherPeriodo,
									NroRegistro,
									NroDocumentoInterno,
									EstadoDocumento,
									Comentarios,									
									MontoImponible,
									FiscalImponible,
									ImponibleGravado,									
									MontoImpuestoVentas,
									MontoCreditoFiscal,
									FiscalImpuestoVentas,
									IGVGravado,									
									MontoObligacion,
									FiscalObligacion,
									MontoNoAfecto,
									FiscalNoAfecto,
									FiscalImpuestoRetenido,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$Periodo."',
									'CP',
									'".$nrocp."',
									'$field_obligaciones[CodProveedor]',
									'$field_obligaciones[CodTipoDocumento]',
									'$field_obligaciones[NroDocumento]',
									'$field_obligaciones[NomCompleto]',
									'$field_obligaciones[DocFiscal]',
									'$field_obligaciones[CodOrganismo]',
									'$field_obligaciones[FechaRegistro]',
									'$field_obligaciones[Voucher]',
									'$field_obligaciones[Periodo]',
									'$field_obligaciones[NroRegistro]',
									'$field_obligaciones[NroControl]',
									'IN',
									'$field_obligaciones[Comentarios]',									
									'$field_obligaciones[MontoAfecto]',
									'$field_obligaciones[MontoAfecto]',
									'$field_obligaciones[MontoAfecto]',									
									'$field_obligaciones[MontoImpuesto]',
									'$field_obligaciones[MontoImpuesto]',
									'$field_obligaciones[MontoImpuesto]',
									'$field_obligaciones[MontoImpuesto]',									
									'$field_obligaciones[MontoObligacion]',
									'$field_obligaciones[MontoObligacion]',
									'$field_obligaciones[MontoNoAfecto]',
									'$field_obligaciones[MontoNoAfecto]',
									'$field_impuestos[MontoImpuesto]',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	inserto caja chica
		if ($FlagCC == "S") {
			$sql = "SELECT
						cc.CodOrganismo,
						cc.FlagCajaChica,
						cc.NroCajaChica,
						cc.Descripcion,
						o.Voucher,
						o.Periodo,
						o.NroRegistro,
						ccd.CodTipoDocumento,
						ccd.NroDocumento,
						ccd.CodProveedor,
						ccd.NomProveedor,
						ccd.DocFiscal,
						ccd.NroRecibo,
						ccd.FechaDocumento,
						ccd.MontoAfecto,
						ccd.MontoNoAfecto,
						ccd.MontoImpuesto,
						ccd.MontoRetencion,
						ccd.MontoPagado AS MontoObligacion
					FROM
						ap_cajachicadetalle ccd
						INNER JOIN ap_cajachica cc ON (ccd.FlagCajaChica = cc.FlagCajaChica AND
													   ccd.NroCajaChica = cc.NroCajaChica)
						INNER JOIN ap_obligaciones o ON (cc.CodBeneficiario = o.CodProveedor AND
														 cc.CodTipoDocumento = o.CodTipoDocumento AND
														 cc.NroDocumento = o.NroDocumento)
						INNER JOIN mastpersonas mp ON (cc.CodBeneficiario = mp.CodPersona)
					WHERE
						o.Periodo = '".$Periodo."' AND
						o.CodOrganismo = '".$CodOrganismo."' AND
						ccd.CodRegimenFiscal = 'I' AND
						(o.Estado = 'AP' OR o.Estado = 'PA')";
			$query_cajachica = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field_cajachica = mysql_fetch_array($query_cajachica)) {	$nrocf++;
				//	consulto el monto de los impuestos 
				$sql = "SELECT SUM(oi.MontoImpuesto) AS MontoImpuesto
						FROM
							ap_obligacionesimpuesto oi
							INNER JOIN mastimpuestos i ON (oi.CodImpuesto = i.CodImpuesto)
						WHERE
							oi.CodProveedor = '".$field_obligaciones['CodProveedor']."' AND
							oi.CodTipoDocumento = '".$field_obligaciones['CodTipoDocumento']."' AND
							oi.NroDocumento = '".$field_obligaciones['NroDocumento']."' AND
							i.CodRegimenFiscal = 'R' AND
							i.TipoComprobante = 'IVA'";
				$query_impuestos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_impuestos) != 0) $field_impuestos = mysql_fetch_array($query_impuestos);
				
				//	inserto el registro de compras
				$sql = "INSERT INTO ap_registrocompras (
									Periodo,
									SistemaFuente,
									Secuencia,
									CodProveedor,
									CodTipoDocumento,
									NroDocumento,
									NomProveedor,
									RifProveedor,
									CodOrganismo,
									FechaDocumento,
									Voucher,
									VoucherPeriodo,
									NroRegistro,
									NroDocumentoInterno,
									EstadoDocumento,
									Comentarios,									
									MontoImponible,
									FiscalImponible,
									ImponibleGravado,									
									MontoImpuestoVentas,
									MontoCreditoFiscal,
									FiscalImpuestoVentas,
									IGVGravado,									
									MontoObligacion,
									FiscalObligacion,
									MontoNoAfecto,
									FiscalNoAfecto,
									FiscalImpuestoRetenido,
									FlagCajaChica,
									NroCajaChica,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$Periodo."',
									'CC',
									'".$nrocf."',
									'$field_cajachica[CodProveedor]',
									'$field_cajachica[CodTipoDocumento]',
									'$field_cajachica[NroDocumento]',
									'$field_cajachica[NomProveedor]',
									'$field_cajachica[DocFiscal]',
									'$field_cajachica[CodOrganismo]',
									'$field_cajachica[FechaDocumento]',
									'$field_cajachica[Voucher]',
									'$field_cajachica[Periodo]',
									'$field_cajachica[NroRegistro]',
									'$field_cajachica[NroRecibo]',
									'IN',
									'$field_cajachica[Descripcion]',									
									'$field_cajachica[MontoAfecto]',
									'$field_cajachica[MontoAfecto]',
									'$field_cajachica[MontoAfecto]',									
									'$field_cajachica[MontoImpuesto]',
									'$field_cajachica[MontoImpuesto]',
									'$field_cajachica[MontoImpuesto]',
									'$field_cajachica[MontoImpuesto]',									
									'$field_cajachica[MontoObligacion]',
									'$field_cajachica[MontoObligacion]',
									'$field_cajachica[MontoNoAfecto]',
									'$field_cajachica[MontoNoAfecto]',
									'$field_impuestos[MontoImpuesto]',
									'$field_cajachica[FlagCajaChica]',
									'$field_cajachica[NroCajaChica]',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		echo "|$nrocp|$nrocf";
	}
	
	//	eliminar
	elseif ($accion == "eliminar") {
		list($Periodo, $SistemaFuente, $Secuencia) = split("[.]", $registro);
		//	eliminar
		$sql = "DELETE FROM ap_registrocompras
				WHERE
					Periodo = '".$Periodo."' AND
					SistemaFuente = '".$SistemaFuente."' AND
					Secuencia = '".$Secuencia."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	conciliacion bancaria
elseif ($modulo == "conciliacion-bancaria") {
	//	nuevo
	if ($accion == "actualizar") {
		//	impuestos
		if ($registro != "") {
			$linea = split(";char:tr;", $registro);
			foreach ($linea as $transaccion) {
				list($NroTransaccion, $Secuencia) = split("[.]", $transaccion);
				//	actualizo
				$sql = "UPDATE ap_bancotransaccion
						SET 
							FlagConciliacion = 'S',
							FechaConciliacion = NOW(),
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							NroTransaccion = '".$NroTransaccion."' AND
							Secuencia = '".$Secuencia."'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
	}
}

//	tipos de documentos ctas. x pagar
elseif ($modulo == "tipo_documento_cxp") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto
		$sql = "INSERT INTO ap_tipodocumento
				SET
					CodTipoDocumento = '".$CodTipoDocumento."',
					Descripcion = '".changeUrl($Descripcion)."',
					Clasificacion = '".$Clasificacion."',
					CodRegimenFiscal = '".$CodRegimenFiscal."',
					CodVoucher = '".$CodVoucher."',
					CodVoucherOrdPago = '".$CodVoucherOrdPago."',
					FlagProvision = '".$FlagProvision."',
					CodCuentaProv = '".$CodCuentaProv."',
					CodCuentaProvPub20 = '".$CodCuentaProvPub20."',
					FlagAdelanto = '".$FlagAdelanto."',
					CodCuentaAde = '".$CodCuentaAde."',
					CodCuentaAdePub20 = '".$CodCuentaAdePub20."',
					FlagFiscal = '".$FlagFiscal."',
					CodFiscal = '".$CodFiscal."',
					FlagAutoNomina = '".$FlagAutoNomina."',
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
		$sql = "UPDATE ap_tipodocumento
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					Clasificacion = '".$Clasificacion."',
					CodRegimenFiscal = '".$CodRegimenFiscal."',
					CodVoucher = '".$CodVoucher."',
					CodVoucherOrdPago = '".$CodVoucherOrdPago."',
					FlagProvision = '".$FlagProvision."',
					CodCuentaProv = '".$CodCuentaProv."',
					CodCuentaProvPub20 = '".$CodCuentaProvPub20."',
					FlagAdelanto = '".$FlagAdelanto."',
					CodCuentaAde = '".$CodCuentaAde."',
					CodCuentaAdePub20 = '".$CodCuentaAdePub20."',
					FlagFiscal = '".$FlagFiscal."',
					CodFiscal = '".$CodFiscal."',
					FlagAutoNomina = '".$FlagAutoNomina."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodTipoDocumento = '".$CodTipoDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		//	elimino
		$sql = "DELETE FROM ap_tipodocumento WHERE CodTipoDocumento = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	impuestos
elseif ($modulo == "impuestos") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto
		$sql = "INSERT INTO mastimpuestos
				SET
					CodImpuesto = '".$CodImpuesto."',
					Descripcion = '".changeUrl($Descripcion)."',
					CodRegimenFiscal = '".$CodRegimenFiscal."',
					Signo = '".$Signo."',
					CodCuenta = '".$CodCuenta."',
					CodCuentaPub20 = '".$CodCuentaPub20."',
					cod_partida = '".$cod_partida."',
					FactorPorcentaje = '".setNumero($FactorPorcentaje)."',
					FlagProvision = '".$FlagProvision."',
					FlagImponible = '".$FlagImponible."',
					TipoComprobante = '".$TipoComprobante."',
					FlagGeneral = '".$FlagGeneral."',
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
		$sql = "UPDATE mastimpuestos
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					CodRegimenFiscal = '".$CodRegimenFiscal."',
					Signo = '".$Signo."',
					CodCuenta = '".$CodCuenta."',
					CodCuentaPub20 = '".$CodCuentaPub20."',
					cod_partida = '".$cod_partida."',
					FactorPorcentaje = '".setNumero($FactorPorcentaje)."',
					FlagProvision = '".$FlagProvision."',
					FlagImponible = '".$FlagImponible."',
					TipoComprobante = '".$TipoComprobante."',
					FlagGeneral = '".$FlagGeneral."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodImpuesto = '".$CodImpuesto."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		//	elimino
		$sql = "DELETE FROM mastimpuestos WHERE CodImpuesto = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	cuentas bancarias
elseif ($modulo == "cuentas_bancarias") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto
		$sql = "INSERT INTO ap_ctabancaria
				SET
					NroCuenta = '".$NroCuenta."',
					CodOrganismo = '".$CodOrganismo."',
					CodBanco = '".$CodBanco."',
					Descripcion = '".changeUrl($Descripcion)."',
					CtaBanco = '".$CtaBanco."',
					TipoCuenta = '".$TipoCuenta."',
					FechaApertura = '".formatFechaAMD($FechaApertura)."',
					CodCuenta = '".$CodCuenta."',
					CodCuentaPub20 = '".$CodCuentaPub20."',
					Agencia = '".changeUrl($Agencia)."',
					Distrito = '".changeUrl($Distrito)."',
					Atencion = '".changeUrl($Atencion)."',
					Cargo = '".changeUrl($Cargo)."',
					FlagConciliacionBancaria = '".$FlagConciliacionBancaria."',
					FlagConciliacionCP = '".$FlagConciliacionCP."',
					FlagDebitoBancario = '".$FlagDebitoBancario."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	tipos de pago
		if ($detalles_tipopagos != "") {
			$tipopagos = split(";char:tr;", $detalles_tipopagos);
			foreach ($tipopagos as $_linea) {
				list($_CodTipoPago, $_UltimoNumero) = split(";char:td;", $_linea);
				//	inserto
				$sql = "INSERT INTO ap_ctabancariatipopago
						SET
							NroCuenta = '".$NroCuenta."',
							CodTipoPago = '".$_CodTipoPago."',
							UltimoNumero = '".$_UltimoNumero."',
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
		//	actualizar
		$sql = "UPDATE ap_ctabancaria
				SET
					CodOrganismo = '".$CodOrganismo."',
					CodBanco = '".$CodBanco."',
					Descripcion = '".changeUrl($Descripcion)."',
					CtaBanco = '".$CtaBanco."',
					TipoCuenta = '".$TipoCuenta."',
					FechaApertura = '".formatFechaAMD($FechaApertura)."',
					CodCuenta = '".$CodCuenta."',
					CodCuentaPub20 = '".$CodCuentaPub20."',
					Agencia = '".changeUrl($Agencia)."',
					Distrito = '".changeUrl($Distrito)."',
					Atencion = '".changeUrl($Atencion)."',
					Cargo = '".changeUrl($Cargo)."',
					FlagConciliacionBancaria = '".$FlagConciliacionBancaria."',
					FlagConciliacionCP = '".$FlagConciliacionCP."',
					FlagDebitoBancario = '".$FlagDebitoBancario."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroCuenta = '".$NroCuenta."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	tipos de pago
		$sql = "DELETE FROM ap_ctabancariatipopago WHERE NroCuenta = '".$NroCuenta."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($detalles_tipopagos != "") {
			$tipopagos = split(";char:tr;", $detalles_tipopagos);
			foreach ($tipopagos as $_linea) {
				list($_CodTipoPago, $_UltimoNumero) = split(";char:td;", $_linea);
				//	inserto
				$sql = "INSERT INTO ap_ctabancariatipopago
						SET
							NroCuenta = '".$NroCuenta."',
							CodTipoPago = '".$_CodTipoPago."',
							UltimoNumero = '".$_UltimoNumero."',
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
		$sql = "DELETE FROM ap_ctabancaria WHERE NroCuenta = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	tipo de transaccion bancaria
elseif ($modulo == "tipo_transaccion_bancaria") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto
		$sql = "INSERT INTO ap_bancotipotransaccion
				SET
					CodTipoTransaccion = '".$CodTipoTransaccion."',
					Descripcion = '".changeUrl($Descripcion)."',
					TipoTransaccion = '".$TipoTransaccion."',
					FlagVoucher = '".$FlagVoucher."',
					CodVoucher = '".$CodVoucher."',
					CodCuenta = '".$CodCuenta."',
					CodCuentaPub20 = '".$CodCuentaPub20."',
					FlagTransaccion = '".$FlagTransaccion."',
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
		$sql = "UPDATE ap_bancotipotransaccion
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					TipoTransaccion = '".$TipoTransaccion."',
					FlagVoucher = '".$FlagVoucher."',
					CodVoucher = '".$CodVoucher."',
					CodCuenta = '".$CodCuenta."',
					CodCuentaPub20 = '".$CodCuentaPub20."',
					FlagTransaccion = '".$FlagTransaccion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodTipoTransaccion = '".$CodTipoTransaccion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	-----------------
		$FlagTransaccion = getValorCampo("ap_bancotipotransaccion", "CodTipoTransaccion", "FlagTransaccion", $registro);
		if ($FlagTransaccion == "S") die("No puede eliminar este registro.<br /><strong>Transacci&oacute;n del Sistema</strong>");
		else {
			//	elimino
			$sql = "DELETE FROM ap_bancotipotransaccion WHERE CodTipoTransaccion = '".$registro."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	conceptos de gastos
elseif ($modulo == "concepto_gastos") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	genero codigo
		$CodConceptoGasto = getCodigo("ap_conceptogastos", "CodConceptoGasto", 4);
		
		//	inserto
		$sql = "INSERT INTO ap_conceptogastos
				SET
					CodConceptoGasto = '".$CodConceptoGasto."',
					Descripcion = '".changeUrl($Descripcion)."',
					CodGastoGrupo = '".$CodGastoGrupo."',
					CodPartida = '".$CodPartida."',
					CodCuenta = '".$CodCuenta."',
					CodCuentaPub20 = '".$CodCuentaPub20."',
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
		$sql = "UPDATE ap_conceptogastos
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					CodGastoGrupo = '".$CodGastoGrupo."',
					CodPartida = '".$CodPartida."',
					CodCuenta = '".$CodCuenta."',
					CodCuentaPub20 = '".$CodCuentaPub20."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodConceptoGasto = '".$CodConceptoGasto."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	-----------------
		//	elimino
		$sql = "DELETE FROM ap_conceptogastos WHERE CodConceptoGasto = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	cuentas bancarias
elseif ($modulo == "transacciones_bancarias") {
	$PeriodoContable = substr(formatFechaAMD($FechaTransaccion), 0, 7);
	$Anio = substr($PeriodoContable, 0, 4);
	$Mes = substr($PeriodoContable, 5, 2);
	
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	genero codigo
		$NroTransaccion = getCodigo("ap_bancotransaccion", "NroTransaccion", 5);
		//	inserto
		$_Secuencia = 0;
		$detalles = split(";char:tr;", $detalles_transacciones);
		foreach ($detalles as $_linea) {
			list($_SecuenciaTransaccion, $_CodTipoTransaccion, $_TipoTransaccion, $_NroCuenta, $_Monto, $_CodTipoDocumento, $_CodigoReferenciaBanco, $_CodProveedor, $_CodCentroCosto, $_CodPartida) = split(";char:td;", $_linea);
			//	verifico disponibilidad
			if ($FlagPresupuesto == "S" && $_TipoTransaccion == "E") {
				list($MontoAjustado, $MontoCompromiso, $Pre) = disponibilidadPartida($Anio, $CodOrganismo, $_CodPartida, $CodPresupuesto);
				$MontoDisponible = $MontoAjustado - $MontoCompromiso;
				$MontoFinal = $MontoDisponible - abs($_Monto);
				if ($MontoFinal < 0) die("Se encontr&oacute; la partida $_CodPartida sin disponibilidad presupuestaria");
			}
			//	consulto si genra voucher
			$_FlagGeneraVoucher = getValorCampo("ap_bancotipotransaccion", "CodTipoTransaccion", "FlagVoucher", $_CodTipoTransaccion);
			//	si el tipo es 
			if ($_TipoTransaccion == "E") $_Monto = abs($_Monto) * -1;
			elseif ($_TipoTransaccion == "I") $_Monto = abs($_Monto);
			//	inserto
			$sql = "INSERT INTO ap_bancotransaccion
					SET
						NroTransaccion = '".$NroTransaccion."',
						CodOrganismo = '".$CodOrganismo."',
						FechaTransaccion = '".formatFechaAMD($FechaTransaccion)."',
						PeriodoContable = '".$PeriodoContable."',
						PreparadoPor = '".$PreparadoPor."',
						FechaPreparacion = '".formatFechaAMD($FechaPreparacion)."',
						Comentarios = '".changeUrl($Comentarios)."',
						FlagPresupuesto = '".$FlagPresupuesto."',
						CodPresupuesto = '".$CodPresupuesto."',
						Estado = '".$Estado."',
						Secuencia = '".++$_Secuencia."',
						CodTipoTransaccion = '".$_CodTipoTransaccion."',
						TipoTransaccion = '".$_TipoTransaccion."',
						NroCuenta = '".$_NroCuenta."',
						Monto = '".$_Monto."',						
						CodTipoDocumento = '".$_CodTipoDocumento."',
						CodigoReferenciaBanco = '".$_CodigoReferenciaBanco."',
						CodigoReferenciaInterno = '".$_CodigoReferenciaBanco."',						
						CodProveedor = '".$_CodProveedor."',
						CodCentroCosto = '".$_CodCentroCosto."',
						CodPartida = '".$_CodPartida."',
						FlagGeneraVoucher = '".$_FlagGeneraVoucher."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto
		$sql = "DELETE FROM ap_bancotransaccion WHERE NroTransaccion = '".$NroTransaccion."'";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$_Secuencia = 0;
		$detalles = split(";char:tr;", $detalles_transacciones);
		foreach ($detalles as $_linea) {
			list($_SecuenciaTransaccion, $_CodTipoTransaccion, $_TipoTransaccion, $_NroCuenta, $_Monto, $_CodTipoDocumento, $_CodigoReferenciaBanco, $_CodProveedor, $_CodCentroCosto, $_CodPartida) = split(";char:td;", $_linea);
			//	verifico disponibilidad
			if ($FlagPresupuesto == "S" && $_TipoTransaccion == "E") {
				list($MontoAjustado, $MontoCompromiso, $Pre) = disponibilidadPartida($Anio, $CodOrganismo, $_CodPartida, $CodPresupuesto);
				$MontoDisponible = $MontoAjustado - $MontoCompromiso;
				$MontoFinal = $MontoDisponible - abs($_Monto);
				if ($MontoFinal < 0) die("Se encontr&oacute; la partida $_CodPartida sin disponibilidad presupuestaria");
			}
			//	consulto si genra voucher
			$_FlagGeneraVoucher = getValorCampo("ap_bancotipotransaccion", "CodTipoTransaccion", "FlagVoucher", $_CodTipoTransaccion);
			//	si el tipo es 
			if ($_TipoTransaccion == "E") $_Monto = abs($_Monto) * -1;
			elseif ($_TipoTransaccion == "I") $_Monto = abs($_Monto);
			//	inserto
			$sql = "INSERT INTO ap_bancotransaccion
					SET
						NroTransaccion = '".$NroTransaccion."',
						CodOrganismo = '".$CodOrganismo."',
						FechaTransaccion = '".formatFechaAMD($FechaTransaccion)."',
						PeriodoContable = '".$PeriodoContable."',
						PreparadoPor = '".$PreparadoPor."',
						FechaPreparacion = '".formatFechaAMD($FechaPreparacion)."',
						Comentarios = '".changeUrl($Comentarios)."',
						FlagPresupuesto = '".$FlagPresupuesto."',
						CodPresupuesto = '".$CodPresupuesto."',
						Estado = '".$Estado."',
						Secuencia = '".++$_Secuencia."',
						CodTipoTransaccion = '".$_CodTipoTransaccion."',
						TipoTransaccion = '".$_TipoTransaccion."',
						NroCuenta = '".$_NroCuenta."',
						Monto = '".$_Monto."',						
						CodTipoDocumento = '".$_CodTipoDocumento."',
						CodigoReferenciaBanco = '".$_CodigoReferenciaBanco."',
						CodigoReferenciaInterno = '".$_CodigoReferenciaBanco."',						
						CodProveedor = '".$_CodProveedor."',
						CodCentroCosto = '".$_CodCentroCosto."',
						CodPartida = '".$_CodPartida."',
						FlagGeneraVoucher = '".$_FlagGeneraVoucher."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	actualizar
	elseif ($accion == "actualizar") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto
		$sql = "UPDATE ap_bancotransaccion
				SET
					Estado = 'AP',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroTransaccion = '".$NroTransaccion."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		$_Secuencia = 0;
		$detalles = split(";char:tr;", $detalles_transacciones);
		foreach ($detalles as $_linea) {
			list($_SecuenciaTransaccion, $_CodTipoTransaccion, $_TipoTransaccion, $_NroCuenta, $_Monto, $_CodTipoDocumento, $_CodigoReferenciaBanco, $_CodProveedor, $_CodCentroCosto, $_CodPartida) = split(";char:td;", $_linea);
			//	si el tipo es 
			if ($_TipoTransaccion == "E") $_Monto = abs($_Monto) * -1;
			elseif ($_TipoTransaccion == "I") $_Monto = abs($_Monto);
			//	verifico disponibilidad
			if ($FlagPresupuesto == "S") {
				if ($_TipoTransaccion == "E") {
					list($MontoAjustado, $MontoCompromiso, $Pre) = disponibilidadPartida($Anio, $CodOrganismo, $_CodPartida, $CodPresupuesto);
					$MontoDisponible = $MontoAjustado - $MontoCompromiso;
					$MontoFinal = $MontoDisponible - abs($_Monto);
					if ($MontoFinal < 0) die("Se encontr&oacute; la partida $_CodPartida sin disponibilidad presupuestaria");
					$_MontoPresupuesto = abs($_Monto);
				}
				elseif ($_TipoTransaccion == "I") $_MontoPresupuesto = abs($_Monto) * -1;
				//	compromisos
				$sql = "INSERT INTO lg_distribucioncompromisos
						SET
							Anio = '".$Anio."',
							CodOrganismo = '".$CodOrganismo."',
							CodProveedor = '".$_CodProveedor."',
							CodTipoDocumento = '".$_CodTipoDocumento."',
							NroDocumento = '".$_CodigoReferenciaBanco."',
							Secuencia = '".$_Secuencia."',
							Linea = '1',
							Mes = '".$Mes."',
							CodCentroCosto = '".$_CodCentroCosto."',
							cod_partida = '".$_CodPartida."',
							Monto = '".$_MontoPresupuesto."',
							Periodo = '".$PeriodoContable."',
							CodPresupuesto = '".$CodPresupuesto."',
							Origen = 'TB',
							Estado = 'CO',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				//	causados
				$sql = "INSERT INTO ap_distribucionobligacion
						SET
							CodProveedor = '".$_CodProveedor."',
							CodTipoDocumento = '".$_CodTipoDocumento."',
							NroDocumento = '".$_CodigoReferenciaBanco."',
							cod_partida = '".$_CodPartida."',
							CodCentroCosto = '".$_CodCentroCosto."',
							Monto = '".$_MontoPresupuesto."',
							Periodo = '".$PeriodoContable."',
							FlagCompromiso = 'S',
							Anio = '".$Anio."',
							CodOrganismo = '".$CodOrganismo."',
							CodPresupuesto = '".$CodPresupuesto."',
							Estado = 'CA',
							Origen = 'TB',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				//	pagadas
				$sql = "INSERT INTO ap_ordenpagodistribucion
						SET
							Anio = '".$Anio."',
							CodOrganismo = '".$CodOrganismo."',
							NroOrden = '".$_CodigoReferenciaBanco."',
							Linea = '".$_Secuencia."',
							CodProveedor = '".$_CodProveedor."',
							CodTipoDocumento = '".$_CodTipoDocumento."',
							NroDocumento = '".$_CodigoReferenciaBanco."',
							Monto = '".$_MontoPresupuesto."',
							CodCentroCosto = '".$_CodCentroCosto."',
							cod_partida = '".$_CodPartida."',
							CodPresupuesto = '".$CodPresupuesto."',
							FlagNoAfectoIGV = 'S',
							Periodo = '".$PeriodoContable."',
							Origen = 'TB',
							Estado = 'PA',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	actualizar
	elseif ($accion == "desactualizar") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto
		$sql = "UPDATE ap_bancotransaccion
				SET
					Estado = 'PR',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroTransaccion = '".$NroTransaccion."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		$_Secuencia = 0;
		$detalles = split(";char:tr;", $detalles_transacciones);
		foreach ($detalles as $_linea) {
			list($_SecuenciaTransaccion, $_CodTipoTransaccion, $_TipoTransaccion, $_NroCuenta, $_Monto, $_CodTipoDocumento, $_CodigoReferenciaBanco, $_CodProveedor, $_CodCentroCosto, $_CodPartida) = split(";char:td;", $_linea);
			//	si el tipo es 
			if ($_TipoTransaccion == "E") $_Monto = abs($_Monto) * -1;
			elseif ($_TipoTransaccion == "I") $_Monto = abs($_Monto);
			//	verifico disponibilidad
			if ($FlagPresupuesto == "S") {
				if ($_TipoTransaccion == "I") {
					list($MontoAjustado, $MontoCompromiso, $Pre) = disponibilidadPartida($Anio, $CodOrganismo, $_CodPartida, $CodPresupuesto);
					$MontoDisponible = $MontoAjustado - $MontoCompromiso;
					$MontoFinal = $MontoDisponible - abs($_Monto);
					if ($MontoFinal < 0) die("Se encontr&oacute; la partida $_CodPartida sin disponibilidad presupuestaria");
				}
				//	compromisos
				$sql = "DELETE FROM lg_distribucioncompromisos
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND 
							CodProveedor = '".$_CodProveedor."' AND
							CodTipoDocumento = '".$_CodTipoDocumento."' AND
							NroDocumento = '".$_CodigoReferenciaBanco."'";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				//	causados
				$sql = "DELETE FROM ap_distribucionobligacion
						WHERE
							CodProveedor = '".$_CodProveedor."' AND
							CodTipoDocumento = '".$_CodTipoDocumento."' AND
							NroDocumento = '".$_CodigoReferenciaBanco."'";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				//	pagadas
				$sql = "DELETE FROM ap_ordenpagodistribucion
						WHERE
							Anio = '".$Anio."',
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$_CodigoReferenciaBanco."'";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	autorizacion de caja chica
elseif ($modulo == "autorizacion_cajachica") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto
		$sql = "INSERT INTO ap_cajachicaautorizacion
				SET
					CodOrganismo = '".$CodOrganismo."',
					CodEmpleado = '".$CodPersona."',
					Monto = '".setNumero($Monto)."',
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
		$sql = "UPDATE ap_cajachicaautorizacion
				SET
					Monto = '".setNumero($Monto)."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodEmpleado = '".$CodPersona."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		list($CodOrganismo, $CodPersona) = split("[_]", $registro);
		//	elimino
		$sql = "DELETE FROM ap_cajachicaautorizacion
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodEmpleado = '".$CodPersona."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	caja chica
elseif ($modulo == "caja_chica") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		if (setNumero($MontoTotal) > setNumero($MontoAutorizado)) die("El Monto Total excede al Monto Autorizado");
		
		//	genero codigo
		$NroCajaChica = getCodigo("ap_cajachica", "NroCajaChica", 4, "Periodo", $Periodo, "FlagCajaChica", $FlagCajaChica);
		
		//	inserto
		$sql = "INSERT INTO ap_cajachica
				SET
					FlagCajaChica = '".$FlagCajaChica."',
					Periodo = '".$Periodo."',
					NroCajaChica = '".$NroCajaChica."',
					CodOrganismo = '".$CodOrganismo."',
					CodDependencia = '".$CodDependencia."',
					CodResponsable = '".$CodBeneficiario."',
					CodClasificacion = '".$CodClasificacion."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodBeneficiario = '".$CodBeneficiario."',
					CodPersonaPagar = '".$CodPersonaPagar."',
					NomPersonaPagar = '".changeUrl($NomPersonaPagar)."',
					CodTipoPago = '".$CodTipoPago."',
					PreparadoPor = '".$PreparadoPor."',
					FechaPreparacion = '".formatFechaAMD($FechaPreparacion)."',
					Descripcion = '".changeUrl($Descripcion)."',
					MontoAfecto = '".setNumero($MontoAfecto)."',
					MontoNoAfecto = '".setNumero($MontoNoAfecto)."',
					MontoImpuesto = '".setNumero($MontoImpuesto)."',
					MontoRetencion = '".setNumero($MontoRetencion)."',
					MontoTotal = '".setNumero($MontoTotal)."',
					MontoNeto = '".setNumero($MontoTotal)."',
					CodPresupuesto = '".$CodPresupuesto."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto
		$MontoTotal = 0;
		$_Secuencia = 0;
		$conceptos = split(";char:tr;", $detalles_conceptos);
		foreach ($conceptos as $detalle) {
			list($_Fecha, $_CodConceptoGasto, $_Descripcion, $_MontoPagado, $_CodRegimenFiscal, $_CodTipoServicio, $_MontoAfecto, $_MontoNoAfecto, $_MontoImpuesto, $_MontoRetencion, $_CodTipoDocumento, $_NroDocumento, $_NroRecibo, $_DocFiscal, $_CodProveedor, $_NomProveedor, $_CodPartida, $_CodCuenta, $_CodCuentaPub20, $_Distribucion) = split(";char:td;", $detalle);
			$MontoTotal += $_MontoPagado;
			$_MontoBruto = $_MontoAfecto + $_MontoNoAfecto;
			$_MontoBruto = round($_MontoBruto, 2);
			if ($_CodRegimenFiscal == "N") $_FlagNoAfectoIGV = "S"; else $_FlagNoAfectoIGV = "N";
			
			//	inserto
			$sql = "INSERT INTO ap_cajachicadetalle
					SET
						FlagCajaChica = '".$FlagCajaChica."',
						Periodo = '".$Periodo."',
						NroCajaChica = '".$NroCajaChica."',
						Secuencia = '".++$_Secuencia."',
						CodConceptoGasto = '".$_CodConceptoGasto."',
						Fecha = '".$_Fecha."',
						Descripcion = '".$_Descripcion."',
						CodRegimenFiscal = '".$_CodRegimenFiscal."',
						DocFiscal = '".$_DocFiscal."',
						CodProveedor = '".$_CodProveedor."',
						NomProveedor = '".$_NomProveedor."',
						MontoAfecto = '".$_MontoAfecto."',
						MontoNoAfecto = '".$_MontoNoAfecto."',
						MontoImpuesto = '".$_MontoImpuesto."',
						MontoRetencion = '".$_MontoRetencion."',
						MontoPagado = '".$_MontoPagado."',
						CodTipoServicio = '".$_CodTipoServicio."',
						Comentarios = '".$_Descripcion."',
						NroRecibo = '".$_NroRecibo."',
						CodTipoDocumento = '".$_CodTipoDocumento."',
						NroDocumento = '".$_NroDocumento."',
						FlagNoAfectoIGV = '".$_FlagNoAfectoIGV."',
						CodEmpleado = '".$CodBeneficiario."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			if ($_Distribucion == "") {
				$_Distribucion = "$_CodConceptoGasto|$CodCentroCosto|$_CodPartida|$_CodCuenta|$_CodCuentaPub20|$_MontoBruto";
			}
			
			//	distribuion
			$_MontoDistribuido = 0;
			$_Linea = 0;
			$distribucion = split(";", $_Distribucion);
			foreach ($distribucion as $detalle) {
				list($_dCodConceptoGasto, $_dCodCentroCosto, $_dCodPartida, $_dCodCuenta, $_dCodCuentaPub20, $_dMonto) = split("[|]", $detalle);
				$_MontoDistribuido += $_dMonto;
				//	inserto
				$sql = "INSERT INTO ap_cajachicadistribucion
						SET
							FlagCajaChica = '".$FlagCajaChica."',
							Periodo = '".$Periodo."',
							NroCajaChica = '".$NroCajaChica."',
							Secuencia = '".$_Secuencia."',
							Linea = '".++$_Linea."',
							CodConceptoGasto = '".$_dCodConceptoGasto."',
							Monto = '".$_dMonto."',
							CodOrganismo = '".$CodOrganismo."',
							CodCentroCosto = '".$_dCodCentroCosto."',
							CodPartida = '".$_dCodPartida."',
							CodCuenta = '".$_dCodCuenta."',
							CodCuentaPub20 = '".$_dCodCuentaPub20."',
							CodPresupuesto = '".$CodPresupuesto."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			$_MontoDistribuido = round($_MontoDistribuido, 2);
			if ($_MontoBruto != $_MontoDistribuido) die("Se encontraron inconsistencias en la Distribucion.<br><strong>($_Descripcion)</strong>");
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		if (setNumero($MontoTotal) > setNumero($MontoAutorizado)) die("El Monto Total excede al Monto Autorizado");
		
		//	modifico
		$sql = "UPDATE ap_cajachica
				SET
					CodPersonaPagar = '".$CodPersonaPagar."',
					NomPersonaPagar = '".changeUrl($NomPersonaPagar)."',
					Descripcion = '".changeUrl($Descripcion)."',
					MontoAfecto = '".setNumero($MontoAfecto)."',
					MontoNoAfecto = '".setNumero($MontoNoAfecto)."',
					MontoImpuesto = '".setNumero($MontoImpuesto)."',
					MontoRetencion = '".setNumero($MontoRetencion)."',
					MontoTotal = '".setNumero($MontoTotal)."',
					MontoNeto = '".setNumero($MontoTotal)."',
					CodPresupuesto = '".$CodPresupuesto."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					FlagCajaChica = '".$FlagCajaChica."' AND
					Periodo = '".$Periodo."' AND
					NroCajaChica = '".$NroCajaChica."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	elimino
		$sql = "DELETE FROM ap_cajachicadetalle
				WHERE
					FlagCajaChica = '".$FlagCajaChica."' AND
					Periodo = '".$Periodo."' AND
					NroCajaChica = '".$NroCajaChica."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$sql = "DELETE FROM ap_cajachicadistribucion
				WHERE
					FlagCajaChica = '".$FlagCajaChica."' AND
					Periodo = '".$Periodo."' AND
					NroCajaChica = '".$NroCajaChica."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	inserto
		$MontoTotal = 0;
		$_Secuencia = 0;
		$conceptos = split(";char:tr;", $detalles_conceptos);
		foreach ($conceptos as $detalle) {
			list($_Fecha, $_CodConceptoGasto, $_Descripcion, $_MontoPagado, $_CodRegimenFiscal, $_CodTipoServicio, $_MontoAfecto, $_MontoNoAfecto, $_MontoImpuesto, $_MontoRetencion, $_CodTipoDocumento, $_NroDocumento, $_NroRecibo, $_DocFiscal, $_CodProveedor, $_NomProveedor, $_CodPartida, $_CodCuenta, $_CodCuentaPub20, $_Distribucion) = split(";char:td;", $detalle);
			$MontoTotal += $_MontoPagado;
			$_MontoBruto = $_MontoAfecto + $_MontoNoAfecto;
			$_MontoBruto = round($_MontoBruto, 2);
			if ($_CodRegimenFiscal == "N") $_FlagNoAfectoIGV = "S"; else $_FlagNoAfectoIGV = "N";
			
			//	inserto
			$sql = "INSERT INTO ap_cajachicadetalle
					SET
						FlagCajaChica = '".$FlagCajaChica."',
						Periodo = '".$Periodo."',
						NroCajaChica = '".$NroCajaChica."',
						Secuencia = '".++$_Secuencia."',
						CodConceptoGasto = '".$_CodConceptoGasto."',
						Fecha = '".$_Fecha."',
						Descripcion = '".$_Descripcion."',
						CodRegimenFiscal = '".$_CodRegimenFiscal."',
						DocFiscal = '".$_DocFiscal."',
						CodProveedor = '".$_CodProveedor."',
						NomProveedor = '".$_NomProveedor."',
						MontoAfecto = '".$_MontoAfecto."',
						MontoNoAfecto = '".$_MontoNoAfecto."',
						MontoImpuesto = '".$_MontoImpuesto."',
						MontoRetencion = '".$_MontoRetencion."',
						MontoPagado = '".$_MontoPagado."',
						CodTipoServicio = '".$_CodTipoServicio."',
						Comentarios = '".$_Descripcion."',
						NroRecibo = '".$_NroRecibo."',
						CodTipoDocumento = '".$_CodTipoDocumento."',
						NroDocumento = '".$_NroDocumento."',
						FlagNoAfectoIGV = '".$_FlagNoAfectoIGV."',
						CodEmpleado = '".$CodBeneficiario."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			if ($_Distribucion == "") {
				$_Distribucion = "$_CodConceptoGasto|$CodCentroCosto|$_CodPartida|$_CodCuenta|$_CodCuentaPub20|$_MontoBruto";
			}
			
			//	distribuion
			$_MontoDistribuido = 0;
			$_Linea = 0;
			$distribucion = split(";", $_Distribucion);
			foreach ($distribucion as $detalle) {
				list($_dCodConceptoGasto, $_dCodCentroCosto, $_dCodPartida, $_dCodCuenta, $_dCodCuentaPub20, $_dMonto) = split("[|]", $detalle);
				$_MontoDistribuido += $_dMonto;
				//	inserto
				$sql = "INSERT INTO ap_cajachicadistribucion
						SET
							FlagCajaChica = '".$FlagCajaChica."',
							Periodo = '".$Periodo."',
							NroCajaChica = '".$NroCajaChica."',
							Secuencia = '".$_Secuencia."',
							Linea = '".++$_Linea."',
							CodConceptoGasto = '".$_dCodConceptoGasto."',
							Monto = '".$_dMonto."',
							CodOrganismo = '".$CodOrganismo."',
							CodCentroCosto = '".$_dCodCentroCosto."',
							CodPartida = '".$_dCodPartida."',
							CodCuenta = '".$_dCodCuenta."',
							CodCuentaPub20 = '".$_dCodCuentaPub20."',
							CodPresupuesto = '".$CodPresupuesto."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			$_MontoDistribuido = round($_MontoDistribuido, 2);
			if ($_MontoBruto != $_MontoDistribuido) die("Se encontraron inconsistencias en la Distribucion.<br><strong>($_Descripcion)</strong>");
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		mysql_query("BEGIN");
		//	-----------------
		if (setNumero($MontoTotal) < (setNumero($MontoAutorizado) * $_PARAMETRO['REPCC'] / 100)) die("El Monto a Reembolsar es menor al $_PARAMETRO[REPCC]% del Monto Autorizado");
		
		//	genero nro para la obligacion
		$NroRegistro = getCodigo("ap_obligaciones", "NroRegistro", 6, "CodOrganismo", $CodOrganismo);		
		//	genero nro de documento para la obligacion
		$sql = "SELECT *
				FROM ap_obligaciones
				WHERE
					CodProveedor = '".$CodBeneficiario."' AND
					CodTipoDocumento = '".$CodClasificacion."' AND
					(NroDocumento = '00$NroCajaChica' OR NroDocumento LIKE '00$NroCajaChica-%')";
		$query_nro = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_nro)) {
			$nro = mysql_num_rows($query_nro) + 1;
			$NroDocumento = "00$NroCajaChica-$nro";
		} else $NroDocumento = "00$NroCajaChica";
		
		//	modifico
		$sql = "UPDATE ap_cajachica
				SET
					AprobadoPor = '".$AprobadoPor."',
					FechaAprobacion = '".formatFechaAMD($FechaAprobacion)."',
					CodTipoDocumento = '".$CodClasificacion."',
					NroDocumento = '".$NroDocumento."',
					NroDocumentoInterno = '".$NroDocumento."',
					Estado = 'AP',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					FlagCajaChica = '".$FlagCajaChica."' AND
					Periodo = '".$Periodo."' AND
					NroCajaChica = '".$NroCajaChica."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	obligacion
		$sql = "INSERT INTO ap_obligaciones (
					CodProveedor,
					CodTipoDocumento,
					NroDocumento,
					NroControl,
					CodOrganismo,
					CodTipoPago,
					NroCuenta,
					CodTipoServicio,
					FechaRegistro,
					FechaVencimiento,
					FechaRecepcion,
					FechaDocumento,
					FechaProgramada,
					NroRegistro,
					MontoObligacion,
					MontoImpuesto,
					MontoImpuestoOtros,
					MontoNoAfecto,
					MontoAfecto,
					IngresadoPor,
					FechaPreparacion,
					RevisadoPor,
					FechaRevision,
					Comentarios,
					ComentariosAdicional,
					CodCentroCosto,
					CodProveedorPagar,
					CodResponsable,
					FlagCajaChica,
					Estado,
					UltimoUsuario,
					UltimaFecha,
					Periodo,
					CodPresupuesto
				)
				SELECT
					cc.CodBeneficiario,
					cc.CodClasificacion,
					'".$NroDocumento."' AS NroDocumento,
					'".$NroDocumento."' AS NroControl,
					cc.CodOrganismo,
					cc.CodTipoPago,
					cbd.NroCuenta,
					'".$_PARAMETRO[TSERVCC]."' AS CodTipoServicio,
					NOW(),
					cc.FechaAprobacion,
					cc.FechaPreparacion,
					cc.FechaPreparacion,
					cc.FechaAprobacion,
					'".$NroRegistro."' AS NroRegistro,
					'".setNumero($MontoTotal)."' AS MontoObligacion,
					'".setNumero($MontoImpuesto)."' AS MontoImpuesto,
					'".setNumero($MontoRetencion)."' AS MontoImpuestoOtros,
					'".setNumero($MontoNoAfecto)."' AS MontoNoAfecto,
					'".setNumero($MontoAfecto)."' AS MontoAfecto,
					cc.PreparadoPor,
					cc.FechaPreparacion,
					cc.PreparadoPor,
					cc.FechaPreparacion,
					cc.Descripcion,
					cc.Descripcion,
					cc.CodCentroCosto,
					cc.CodPersonaPagar,
					cc.CodResponsable,
					'S' AS FlagCajaChica,
					'PR' AS Estado,
					'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
					NOW() AS UltimaFecha,
					NOW() AS Periodo,
					'".$CodPresupuesto."' AS CodPresupuesto
				FROM
					ap_cajachica cc
					LEFT JOIN ap_ctabancariadefault cbd ON (cc.CodOrganismo = cbd.CodOrganismo AND cc.CodTipoPago = cbd.CodTipoPago)
				WHERE
					FlagCajaChica = '".$FlagCajaChica."' AND
					Periodo = '".$Periodo."' AND
					NroCajaChica = '".$NroCajaChica."'";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	obligacion x cuentas
		$sql = "SELECT
					ccd.*,
					cg.Descripcion,
					cc.MontoImpuesto
				FROM
					ap_cajachicadistribucion ccd
					INNER JOIN ap_conceptogastos cg ON (ccd.CodConceptoGasto = cg.CodConceptoGasto)
					INNER JOIN ap_cajachicadetalle cc ON (cc.FlagCajaChica = ccd.FlagCajaChica AND
													      cc.Periodo = ccd.Periodo AND
														  cc.NroCajaChica = ccd.NroCajaChica AND
														  cc.Secuencia = ccd.Secuencia)
				WHERE
					ccd.FlagCajaChica = '".$FlagCajaChica."' AND
					ccd.Periodo = '".$Periodo."' AND
					ccd.NroCajaChica = '".$NroCajaChica."'
				ORDER BY Secuencia, Linea";
		$query_distribucion = mysql_query($sql) or die($sql.mysql_error());
		while($field_distribucion = mysql_fetch_array($query_distribucion)) {
			if ($field_distribucion['MontoImpuesto'] > 0) $FlagNoAfectoIGV = "N"; else $FlagNoAfectoIGV = "S";
			$sql = "INSERT INTO ap_obligacionescuenta
					SET
						CodProveedor = '".$CodBeneficiario."',
						CodTipoDocumento = '".$CodClasificacion."',
						NroDocumento = '".$NroDocumento."',
						Secuencia = '".$field_distribucion['Secuencia']."',
						Linea = '".$field_distribucion['Linea']."',
						Descripcion = '".$field_distribucion['Descripcion']."',
						Monto = '".$field_distribucion['Monto']."',
						CodCentroCosto = '".$field_distribucion['CodCentroCosto']."',
						CodCuenta = '".$field_distribucion['CodCuenta']."',
						CodCuentaPub20 = '".$field_distribucion['CodCuentaPub20']."',
						cod_partida = '".$field_distribucion['CodPartida']."',
						FlagNoAfectoIGV = '".$FlagNoAfectoIGV."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	obligacion x impuestos
		$sql = "SELECT
					i.CodImpuesto,
					i.FactorPorcentaje,
					i.Signo,
					i.CodRegimenFiscal,
					i.FlagImponible,
					ccd.MontoAfecto,
					ccd.MontoImpuesto
				FROM
					mastimpuestos i
					INNER JOIN masttiposervicioimpuesto tsi ON (i.CodImpuesto = tsi.CodImpuesto)
					INNER JOIN ap_cajachicadetalle ccd ON (tsi.CodTipoServicio = ccd.CodTipoServicio)
				WHERE
					i.CodRegimenFiscal = 'R' AND
					ccd.FlagCajaChica = '".$FlagCajaChica."' AND
					ccd.Periodo = '".$Periodo."' AND
					ccd.NroCajaChica = '".$NroCajaChica."'";
		$query_impuestos = mysql_query($sql) or die($sql.mysql_error());	$_Linea = 0;
		while ($field_impuestos = mysql_fetch_array($query_impuestos)) {	$_Linea++;
			if ($field_impuestos['FlagImponible'] == "N") $_MontoAfecto = $field_impuestos['MontoAfecto'];
			elseif ($field_impuestos['FlagImponible'] == "I") $_MontoAfecto = $field_impuestos['MontoImpuesto'];
			$_MontoImpuesto = $_MontoAfecto * $field_impuestos['FactorPorcentaje'] / 100;
			if ($field_impuestos['Signo'] == "N") $_MontoRetencion *= (-1);
			#			
			$sql = "INSERT INTO ap_obligacionesimpuesto
					SET
						CodProveedor = '".$CodBeneficiario."',
						CodTipoDocumento = '".$CodClasificacion."',
						NroDocumento = '".$NroDocumento."',
						Linea = '".$_Linea."',
						CodImpuesto = '".$field_impuestos['CodImpuesto']."',
						FactorPorcentaje = '".$field_impuestos['FactorPorcentaje']."',
						MontoImpuesto = '".$_MontoImpuesto."',
						MontoAfecto = '".$_MontoAfecto."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	obligacion distribucion
		$Secuencia = 0;
		list($_cod_partida_igv, $_CodCuenta_igv, $_CodCuentaPub20_igv) = getPartidaCuentaFromIGV($_PARAMETRO["IGVCODIGO"]);
		$sql = "(SELECT
					cod_partida,
					CodCuenta,
					CodCuentaPub20,
					CodCentroCosto,
					SUM(Monto) AS Monto
				FROM ap_obligacionescuenta 
				WHERE
					CodProveedor = '".$CodBeneficiario."' AND
					CodTipoDocumento = '".$CodClasificacion."' AND
					NroDocumento = '".$NroDocumento."'
				GROUP BY cod_partida, CodCuenta, CodCentroCosto)
				UNION
				(SELECT
					'".$_cod_partida_igv."' AS cod_partida,
					'".$_CodCuenta_igv."' AS CodCuenta_igv,
					'".$_CodCuentaPub20_igv."' AS CodCuentaPub20_igv,
					'".$CodCentroCosto."' AS CodCentroCosto,
					'".setNumero($MontoImpuesto)."' AS Monto)
				ORDER BY cod_partida";
		$query_distribucion = mysql_query($sql) or die($sql.mysql_error());
		while ($field_distribucion = mysql_fetch_array($query_distribucion)) {
			//	compromisos
			$sql = "INSERT INTO lg_distribucioncompromisos
					SET
						Anio = NOW(),
						CodOrganismo = '".$CodOrganismo."',
						CodProveedor = '".$CodBeneficiario."',
						CodTipoDocumento = '".$CodClasificacion."',
						NroDocumento = '".$NroDocumento."',
						Secuencia = '".++$Secuencia."',
						Linea = '1',
						Mes = SUBSTRING(NOW(), 6, 2),
						CodCentroCosto = '".$field_distribucion['CodCentroCosto']."',
						cod_partida = '".$field_distribucion['cod_partida']."',
						Monto = '".$field_distribucion['Monto']."',
						Periodo = NOW(),
						Origen = 'OB',
						CodPresupuesto = '".$CodPresupuesto."',
						Estado = 'PE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	causado
			$sql = "INSERT INTO ap_distribucionobligacion
					SET
						CodProveedor = '".$CodBeneficiario."',
						CodTipoDocumento = '".$CodClasificacion."',
						NroDocumento = '".$NroDocumento."',
						CodCuenta = '".$field_distribucion['CodCuenta']."',
						CodCuentaPub20 = '".$field_distribucion['CodCuentaPub20']."',
						cod_partida = '".$field_distribucion['cod_partida']."',
						CodCentroCosto = '".$field_distribucion['CodCentroCosto']."',
						Monto = '".$field_distribucion['Monto']."',
						Periodo = NOW(),
						Estado = 'PE',
						Anio = NOW(),
						FlagCompromiso = 'S',
						Origen = 'OB',
						CodPresupuesto = '".$CodPresupuesto."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
}
?>