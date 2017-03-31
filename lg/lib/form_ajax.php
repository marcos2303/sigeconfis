<?php
session_start();
include("../../lib/fphp.php");
include("fphp.php");
	$__archivo = fopen("$modulo-$accion.sql", "w+");
//	fwrite($__archivo, $sql.";\n\n");
///////////////////////////////////////////////////////////////////////////////
//	PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	items
if ($modulo == "items") {
	$Descripcion = changeUrl($Descripcion);
	
	//	nuevo
	if ($accion == "nuevo") {
		//	inserto
		$CodItem = getCodigo("lg_itemmast", "CodItem", 10);
		$sql = "INSERT INTO lg_itemmast (
							CodItem,
							CodInterno,
							Descripcion,
							CodTipoItem,
							CodUnidad,
							CodUnidadComp,
							CodUnidadEmb,
							CodLinea,
							CodFamilia,
							CodSubFamilia,
							FlagLotes,
							FlagItem,
							FlagKit,
							FlagImpuestoVentas,
							FlagAuto,
							FlagDisponible,
							Imagen,
							CodMarca,
							Color,
							CodProcedencia,
							CodBarra,
							StockMin,
							StockMax,
							CtaInventario,
							CtaGasto,
							CtaInventarioPub20,
							CtaGastoPub20,
							CtaVenta,
							PartidaPresupuestal,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodItem."',
							'".$CodInterno."',
							'".$Descripcion."',
							'".$CodTipoItem."',
							'".$CodUnidad."',
							'".$CodUnidadComp."',
							'".$CodUnidadEmb."',
							'".$CodLinea."',
							'".$CodFamilia."',
							'".$CodSubFamilia."',
							'".$FlagLotes."',
							'".$FlagItem."',
							'".$FlagKit."',
							'".$FlagImpuestoVentas."',
							'".$FlagAuto."',
							'".$FlagDisponible."',
							'".$Imagen."',
							'".$CodMarca."',
							'".$Color."',
							'".$CodProcedencia."',
							'".$CodBarra."',
							'".$StockMin."',
							'".$StockMax."',
							'".$CtaInventario."',
							'".$CtaGasto."',
							'".$CtaInventarioPub20."',
							'".$CtaGastoPub20."',
							'".$CtaVenta."',
							'".$PartidaPresupuestal."',
							'".$Estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		//	actualizo
		$sql = "UPDATE lg_itemmast
				SET
					CodInterno = '".$CodInterno."',
					Descripcion = '".$Descripcion."',
					CodTipoItem = '".$CodTipoItem."',
					CodUnidad = '".$CodUnidad."',
					CodUnidadComp = '".$CodUnidadComp."',
					CodUnidadEmb = '".$CodUnidadEmb."',
					CodLinea = '".$CodLinea."',
					CodFamilia = '".$CodFamilia."',
					CodSubFamilia = '".$CodSubFamilia."',
					FlagLotes = '".$FlagLotes."',
					FlagItem = '".$FlagItem."',
					FlagKit = '".$FlagKit."',
					FlagImpuestoVentas = '".$FlagImpuestoVentas."',
					FlagAuto = '".$FlagAuto."',
					FlagDisponible = '".$FlagDisponible."',
					Imagen = '".$Imagen."',
					CodMarca = '".$CodMarca."',
					Color = '".$Color."',
					CodProcedencia = '".$CodProcedencia."',
					CodBarra = '".$CodBarra."',
					StockMin = '".$StockMin."',
					StockMax = '".$StockMax."',
					CtaInventario = '".$CtaInventario."',
					CtaGasto = '".$CtaGasto."',
					CtaInventarioPub20 = '".$CtaInventarioPub20."',
					CtaGastoPub20 = '".$CtaGastoPub20."',
					CtaVenta = '".$CtaVenta."',
					PartidaPresupuestal = '".$PartidaPresupuestal."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodItem = '".$CodItem."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar
	elseif ($accion == "eliminar") {
		//	eliminar
		$sql = "DELETE FROM lg_itemmast WHERE CodItem = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	commodity
elseif ($modulo == "commodity") {
	$Descripcion = changeUrl($Descripcion);
	$detalles = changeUrl($detalles);
	
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	inserto
		$CommodityMast = getCodigo("lg_commoditymast", "CommodityMast", 3);
		$sql = "INSERT INTO lg_commoditymast (
							Clasificacion,
							CommodityMast,
							Descripcion,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$Clasificacion."',
							'".$CommodityMast."',
							'".$Descripcion."',
							'".$Estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	detalles
		if ($detalles != "") {
			$linea = split(";", $detalles);	$_Linea=0;
			foreach ($linea as $registro) {	$_Linea++;
				list($_Codigo, $_CommoditySub, $_Descripcion, $_cod_partida, $_CodCuenta, $_CodCuentaPub20, $_CodClasificacion, $_CodUnidad, $_Estado) = split("[|]", $registro);
				$_Codigo = $CommodityMast.$_CommoditySub;
				
				//	inserto
				$sql = "INSERT INTO lg_commoditysub (
									CommodityMast,
									CommoditySub,
									Codigo,
									Descripcion,
									CodUnidad,
									cod_partida,
									CodCuenta,
									CodCuentaPub20,
									CodClasificacion,
									Estado,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CommodityMast."',
									'".$_CommoditySub."',
									'".$_Codigo."',
									'".$_Descripcion."',
									'".$_CodUnidad."',
									'".$_cod_partida."',
									'".$_CodCuenta."',
									'".$_CodCuentaPub20."',
									'".$_CodClasificacion."',
									'".$_Estado."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	actualizo
		$sql = "UPDATE lg_commoditymast
				SET
					Clasificacion = '".$Clasificacion."',
					Descripcion = '".$Descripcion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CommodityMast = '".$CommodityMast."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	detalles
		if ($eliminados_detalle != "") {
			$linea = split(";", $eliminados_detalle);	$_Linea=0;
			foreach ($linea as $_Codigo) {
				$sql = "DELETE FROM lg_commoditysub WHERE Codigo = '".$_Codigo."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		if ($detalles != "") {
			$linea = split(";", $detalles);	$_Linea=0;
			foreach ($linea as $registro) {	$_Linea++;
				list($_Codigo, $_CommoditySub, $_Descripcion, $_cod_partida, $_CodCuenta, $_CodCuentaPub20, $_CodClasificacion, $_CodUnidad, $_Estado) = split("[|]", $registro);
				if ($_Codigo == "") $_Codigo = $CommodityMast.$_CommoditySub;
				$sql = "INSERT INTO lg_commoditysub
						SET
							CommodityMast = '".$CommodityMast."',
							CommoditySub = '".$_CommoditySub."',
							Codigo = '".$_Codigo."',
							Descripcion = '".$_Descripcion."',
							CodUnidad = '".$_CodUnidad."',
							cod_partida = '".$_cod_partida."',
							CodCuenta = '".$_CodCuenta."',
							CodCuentaPub20 = '".$_CodCuentaPub20."',
							CodClasificacion = '".$_CodClasificacion."',
							Estado = '".$_Estado."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						ON DUPLICATE KEY UPDATE
							Descripcion = '".$_Descripcion."',
							CodUnidad = '".$_CodUnidad."',
							cod_partida = '".$_cod_partida."',
							CodCuenta = '".$_CodCuenta."',
							CodCuentaPub20 = '".$_CodCuentaPub20."',
							CodClasificacion = '".$_CodClasificacion."',
							Estado = '".$_Estado."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		mysql_query("COMMIT");
	}
	
	//	eliminar
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	eliminar
		$sql = "DELETE FROM lg_commoditysub WHERE CommodityMast = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$sql = "DELETE FROM lg_commoditymast WHERE CommodityMast = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		mysql_query("COMMIT");
	}
}

//	linea
elseif ($modulo == "linea") {
	$Descripcion = changeUrl($Descripcion);
	
	//	nuevo
	if ($accion == "nuevo") {
		//	inserto
		$CodLinea = getCodigo("lg_claselinea", "CodLinea", 6);
		$sql = "INSERT INTO lg_claselinea (
							CodLinea,
							Descripcion,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodLinea."',
							'".$Descripcion."',
							'".$Estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		//	actualizo
		$sql = "UPDATE lg_claselinea
				SET
					Descripcion = '".$Descripcion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodLinea = '".$CodLinea."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar
	elseif ($accion == "eliminar") {
		//	eliminar
		$sql = "DELETE FROM lg_claselinea WHERE CodLinea = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}	
}

//	familia
elseif ($modulo == "familia") {
	$Descripcion = changeUrl($Descripcion);
	
	//	nuevo
	if ($accion == "nuevo") {
		//	inserto
		$CodFamilia = getCodigo_2("lg_clasefamilia", "CodFamilia", "CodLinea", $CodLinea, 6);
		$sql = "INSERT INTO lg_clasefamilia (
							CodLinea,
							CodFamilia,
							Descripcion,
							CuentaInventario,
							CuentaGasto,
							CuentaVentas,
							PartidaPresupuestal,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodLinea."',
							'".$CodFamilia."',
							'".$Descripcion."',
							'".$CuentaInventario."',
							'".$CuentaGasto."',
							'".$CuentaVentas."',
							'".$PartidaPresupuestal."',
							'".$Estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		//	actualizo
		$sql = "UPDATE lg_clasefamilia
				SET
					Descripcion = '".$Descripcion."',
					CuentaInventario = '".$CuentaInventario."',
					CuentaGasto = '".$CuentaGasto."',
					CuentaVentas = '".$CuentaVentas."',
					PartidaPresupuestal = '".$PartidaPresupuestal."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodLinea = '".$CodLinea."' AND
					CodFamilia = '".$CodFamilia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar
	elseif ($accion == "eliminar") {
		//	eliminar
		list($CodLinea, $CodFamilia) = split("[.]", $registro);
		$sql = "DELETE FROM lg_clasefamilia
				WHERE
					CodLinea = '".$CodLinea."' AND
					CodFamilia = '".$CodFamilia."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}	
}

//	sub_familia
elseif ($modulo == "subfamilia") {
	$Descripcion = changeUrl($Descripcion);
	
	//	nuevo
	if ($accion == "nuevo") {
		//	inserto
		$CodSubFamilia = getCodigo_3("lg_clasesubfamilia", "CodSubFamilia", "CodLinea", "CodFamilia", $CodLinea, $CodFamilia, 6);
		$sql = "INSERT INTO lg_clasesubfamilia (
							CodLinea,
							CodFamilia,
							CodSubFamilia,
							Descripcion,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodLinea."',
							'".$CodFamilia."',
							'".$CodSubFamilia."',
							'".$Descripcion."',
							'".$Estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		//	actualizo
		$sql = "UPDATE lg_clasesubfamilia
				SET
					Descripcion = '".$Descripcion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodLinea = '".$CodLinea."' AND
					CodFamilia = '".$CodFamilia."' AND
					CodSubFamilia = '".$CodSubFamilia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar
	elseif ($accion == "eliminar") {
		//	eliminar
		list($CodLinea, $CodFamilia, $CodSubFamilia) = split("[.]", $registro);
		$sql = "DELETE FROM lg_clasesubfamilia
				WHERE
					CodLinea = '".$CodLinea."' AND
					CodFamilia = '".$CodFamilia."' AND
					CodSubFamilia = '".$CodSubFamilia."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}	
}

//	requerimiento
elseif ($modulo == "requerimiento") {
	$Comentarios = changeUrl($Comentarios);
	$RazonRechazo = changeUrl($RazonRechazo);
	$NomProveedorSugerido = changeUrl($NomProveedorSugerido);
	$detalles = changeUrl($detalles);
	
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	valido errores
		if ($TipoRequerimiento == "01") {
			$i = 0;
			$detalle = split(";char:tr;", $detalles);
			foreach ($detalle as $linea) {
				list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CodCentroCosto, $_FlagExonerado, $_CantidadPedida, $_FlagCompraAlmacen, $_CodCuenta, $_cod_partida) = split(";char:td;", $linea);
				$var = "$_CodItem.$_CodCentroCosto";
				$item[$i] = $var;
				$j = 0;
				$x = 0;
				for($j=0; $j<=$i; $j++) {
					if ($var == $item[$j]) $x++;
					if ($x > 1) die("Se encontraron varias lineas del Item <strong>$_CodItem</strong> dirigido al Centro de Costo <strong>$_CodCentroCosto</strong>");
				}
				$i++;
			}
		}
		
		//	inserto requerimiento
		##	genero el nuevo codigo
		$CodRequerimiento = getCodigo("lg_requerimientos", "CodRequerimiento", 10);
		$Correlativo = getCodigo_3("lg_requerimientos", "Secuencia", "Anio", "CodDependencia", $Anio, $CodDependencia, 3);
		$Secuencia = intval($Correlativo);
		$CodInternoDependencia = getCodInternoDependencia($CodDependencia);
		$CodInterno = "$CodInternoDependencia-$Correlativo-$Anio";
		##	inserto
		$sql = "INSERT INTO lg_requerimientos
				SET
					CodRequerimiento = '".$CodRequerimiento."',
					CodInterno = '".$CodInterno."',
					CodOrganismo = '".$CodOrganismo."',
					CodDependencia = '".$CodDependencia."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodAlmacen = '".$CodAlmacen."',
					Clasificacion = '".$Clasificacion."',
					Prioridad = '".$Prioridad."',
					TipoClasificacion = '".$TipoClasificacion."',
					PreparadaPor = '".$PreparadaPor."',
					FechaRequerida = '".formatFechaAMD($FechaRequerida)."',
					FechaPreparacion = '".formatFechaAMD($FechaPreparacion)."',
					Comentarios = '".$Comentarios."',
					Anio = '".$Anio."',
					Secuencia = '".$Secuencia."',
					FlagCajaChica = '".$FlagCajaChica."',
					ProveedorSugerido = '".$ProveedorSugerido."',
					ClasificacionOC = '".$ClasificacionOC."',
					ProveedorDocRef = '".$ProveedorDocRef."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CodCentroCosto, $_FlagExonerado, $_CantidadPedida, $_FlagCompraAlmacen, $_CodCuenta, $_CodCuentaPub20, $_cod_partida) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_requerimientosdet
					SET
						CodRequerimiento = '".$CodRequerimiento."',
						Secuencia = '".++$_Secuencia."',
						CodOrganismo = '".$CodOrganismo."',
						CodItem = '".$_CodItem."',
						CommoditySub = '".$_CommoditySub."',
						Descripcion = '".$_Descripcion."',
						CodUnidad = '".$_CodUnidad."',
						CantidadPedida = '".$_CantidadPedida."',
						FlagExonerado = '".$_FlagExonerado."',
						FlagCompraAlmacen = '".$_FlagCompraAlmacen."',
						CodCentroCosto = '".$_CodCentroCosto."',
						Anio = '".$Anio."',
						Estado = '".$Estado."',
						CodCuenta = '".$_CodCuenta."',
						CodCuentaPub20 = '".$_CodCuentaPub20."',
						cod_partida = '".$_cod_partida."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		if ($detalles_anterior != "") {
			##	detalles seleccionados
			$detalle = split(";char:tr;", $detalles_anterior);
			foreach ($detalle as $linea) {
				list($_Requerimiento, $_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CodCentroCosto, $_FlagExonerado, $_CantidadPedida, $_CodCuenta, $_cod_partida, $_Comentarios) = split(";char:td;", $linea);
				list($_CodRequerimiento, $_Secuencia) = split("[.]", $_Requerimiento);
				##	actualizo
				$sql = "UPDATE lg_requerimientosdet 
						SET FlagCompraAlmacen = 'A'
						WHERE
							CodRequerimiento = '".$_CodRequerimiento."' AND
							Secuencia = '".$_Secuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		echo "|Se ha generado el Requerimiento <strong>Nro. $CodInterno</strong>";
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	valido errores
		if ($TipoRequerimiento == "01") {
			$i = 0;
			$detalle = split(";char:tr;", $detalles);
			foreach ($detalle as $linea) {
				list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CodCentroCosto, $_FlagExonerado, $_CantidadPedida, $_FlagCompraAlmacen, $_CodCuenta, $_cod_partida) = split(";char:td;", $linea);
				$var = "$_CodItem.$_CodCentroCosto";
				$item[$i] = $var;
				$j = 0;
				$x = 0;
				for($j=0; $j<=$i; $j++) {
					if ($var == $item[$j]) $x++;
					if ($x > 1) die("Se encontraron varias lineas del Item <strong>$_CodItem</strong> dirigido al Centro de Costo <strong>$_CodCentroCosto</strong>");
				}
				$i++;
			}
		}
		
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientos
				SET
					CodCentroCosto = '".$CodCentroCosto."',
					CodAlmacen = '".$CodAlmacen."',
					Prioridad = '".$Prioridad."',
					FechaRequerida = '".formatFechaAMD($FechaRequerida)."',
					Comentarios = '".$Comentarios."',
					FlagCajaChica = '".$FlagCajaChica."',
					ProveedorSugerido = '".$ProveedorSugerido."',
					ClasificacionOC = '".$ClasificacionOC."',
					ProveedorDocRef = '".$ProveedorDocRef."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		##	elimino
		$sql = "DELETE FROM lg_requerimientosdet WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CodCentroCosto, $_FlagExonerado, $_CantidadPedida, $_FlagCompraAlmacen, $_CodCuenta, $_CodCuentaPub20, $_cod_partida) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_requerimientosdet
					SET
						CodRequerimiento = '".$CodRequerimiento."',
						Secuencia = '".++$_Secuencia."',
						CodOrganismo = '".$CodOrganismo."',
						CodItem = '".$_CodItem."',
						CommoditySub = '".$_CommoditySub."',
						Descripcion = '".$_Descripcion."',
						CodUnidad = '".$_CodUnidad."',
						CantidadPedida = '".$_CantidadPedida."',
						FlagExonerado = '".$_FlagExonerado."',
						FlagCompraAlmacen = '".$_FlagCompraAlmacen."',
						CodCentroCosto = '".$_CodCentroCosto."',
						Anio = '".$Anio."',
						Estado = '".$Estado."',
						CodCuenta = '".$_CodCuenta."',
						CodCuentaPub20 = '".$_CodCuentaPub20."',
						cod_partida = '".$_cod_partida."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	revisar
	elseif ($accion == "revisar") {
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientos
				SET
					Estado = 'RV',
					RevisadaPor = '".$RevisadaPor."',
					FechaRevision = '".formatFechaAMD($FechaRevision)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	conformar
	elseif ($accion == "conformar") {
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientos
				SET
					FlagCajaChica = '".$FlagCajaChica."',
					Estado = 'CN',
					ConformadaPor = '".$ConformadaPor."',
					FechaConformacion = '".formatFechaAMD($FechaConformacion)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientos
				SET
					Estado = 'AP',
					AprobadaPor = '".$AprobadaPor."',
					FechaAprobacion = '".formatFechaAMD($FechaAprobacion)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientosdet
				SET
					Estado = 'PE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	si selecciono proveedor sugerido
		if ($ProveedorSugerido != "") {
			//	numero de cotizacion proveedor
			$Numero = intval(getCodigo("lg_cotizacion", "Numero", 10));
			$NroCotizacionProv = getCodigo("lg_cotizacion", "NroCotizacionProv", 8);
			$NumeroInterno = getCodigo("lg_cotizacion", "NumeroInterno", 8, "Anio", date("Y"));
			$CodFormaPago = getValorCampo("mastproveedores", "CodProveedor", "CodFormaPago", $ProveedorSugerido);
			$NroInvitaciones = 1;
			$FechaLimite = getFechaFin(formatFechaDMA(substr(ahora(), 0, 10)), $_PARAMETRO['DIASLIMCOT']);
			
			//	consulto detalles
			$sql = "SELECT *
					FROM lg_requerimientosdet
					WHERE CodRequerimiento = '".$CodRequerimiento."'
					ORDER BY Secuencia";
			$query_det = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field_det = mysql_fetch_array($query_det)) {
				//	numero de invitacines y el numero de cotizacion
				$CotizacionNumero = getCodigo("lg_cotizacion", "CotizacionNumero", 8);
				##
				$CodFormaPago = getValorCampo("mastproveedores", "CodProveedor", "CodFormaPago", $ProveedorSugerido);
				//	inserto cotizacion
				$sql = "INSERT INTO lg_cotizacion
						SET
							CodOrganismo = '".$field_det['CodOrganismo']."',
							CodRequerimiento = '".$field_det['CodRequerimiento']."',
							Secuencia = '".$field_det['Secuencia']."',
							CotizacionNumero = '".$CotizacionNumero."',
							Numero = '".$Numero."',
							CodProveedor = '".$ProveedorSugerido."',
							NomProveedor = '".$NomProveedorSugerido."',
							CodFormaPago = '".$CodFormaPago."',
							Observaciones = '".($field_det['Comentarios'])."',
							Cantidad = '".$field_det['CantidadPedida']."',
							Estado = 'A',
							NroCotizacionProv = '".$NroCotizacionProv."',
							NumeroInterno = '".$NumeroInterno."',
							FlagAsignado = 'S',
							FlagExonerado = '".$field_det['FlagExonerado']."',
							FechaInvitacion = NOW(),
							FechaDocumento = NOW(),
							NumeroInvitacion = 'AUTOMATICO',
							FechaEntrega = '".formatFechaAMD($FechaLimite)."',
							FechaLimite = '".formatFechaAMD($FechaLimite)."',
							FlagUnidadCompra = 'N',
							CantidadCompra = '".$field_det['CantidadPedida']."',
							CodUnidadCompra = '".$field_det['CodUnidad']."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	
			$sql = "UPDATE lg_requerimientosdet
					SET CotizacionRegistros = '1'
					WHERE CodRequerimiento = '".$CodRequerimiento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
	
	//	rechazar
	elseif ($accion == "rechazar") {
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientos
				SET
					Estado = 'RE',
					RazonRechazo = '".$RazonRechazo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalles
		$sql = "UPDATE lg_requerimientosdet
				SET
					Estado = 'RE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	anular
	elseif ($accion == "anular") {
		if ($Estado == "PR") {
			$EstadoRequerimiento = "AN";
			$EstadoDetalle = "AN";
		}
		elseif ($Estado != "PR") {
			$EstadoRequerimiento = "PR";
			$EstadoDetalle = "PR";
		}
		
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientos
				SET
					Estado = '".$EstadoRequerimiento."',
					RazonRechazo = '".$RazonRechazo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalles
		$sql = "UPDATE lg_requerimientosdet
				SET
					Estado = '".$EstadoDetalle."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	cerrar
	elseif ($accion == "cerrar") {
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientos
				SET
					Estado = 'CE',
					RazonRechazo = '".$RazonRechazo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalles
		$sql = "UPDATE lg_requerimientosdet
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	cerrar linea
	elseif ($accion == "cerrar-detalle") {
		list($CodRequerimiento, $Secuencia) = split("[.]", $registro);
		//	verifico los detalles
		$sql = "SELECT Estado
				FROM lg_requerimientosdet
				WHERE
					CodRequerimiento = '".$CodRequerimiento."' AND
					Secuencia = '".$Secuencia."' AND
					Estado = 'PE'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) == 0) die("Solo se pueden cerrar lineas en Estado <strong>Pendiente</strong>");
		##
		//	modifico detalles
		$sql = "UPDATE lg_requerimientosdet
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodRequerimiento = '".$CodRequerimiento."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##
		//	consulto si no quedan pendientes en el requerimiento
		$sql = "SELECT Estado
				FROM lg_requerimientosdet
				WHERE
					CodRequerimiento = '".$CodRequerimiento."' AND
					Estado = 'PE'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) == 0) {
			//	consulto si se completaron algunas lineas en el requerimiento
			$sql = "SELECT Estado
					FROM lg_requerimientosdet
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Estado = 'CO'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query) != 0) {
				$sql = "UPDATE lg_requerimientos
						SET Estado = 'CO'
						WHERE CodRequerimiento = '".$CodRequerimiento."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				$sql = "UPDATE lg_requerimientos
						SET Estado = 'CE'
						WHERE CodRequerimiento = '".$CodRequerimiento."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
	}
}

//	orden de compra
elseif ($modulo == "orden_compra") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		$CodDependencia = getValorCampo("ac_mastcentrocosto", "CodCentroCosto", "CodDependencia", $_PARAMETRO["CCOSTOCOMPRA"]);
		$FaxProveedor = getValorCampo("mastpersonas", "CodPersona", "Fax", $CodProveedor);
		list($cod_partida, $CodCuenta, $CodCuentaPub20) = getPartidaCuentaFromIGV($_PARAMETRO["IGVCODIGO"]);
		list($DiaOrden, $MesOrden, $AnioOrden) = split("[./-]", $FechaOrden);
		$PeriodoOrden = "$AnioOrden-$MesOrden";
		//	inserto orden
		##	genero el nuevo codigo
		$NroOrden = getCodigo_3("lg_ordencompra", "NroOrden", "Anio", "CodOrganismo", $Anio, $CodOrganismo, 10);
		##	inserto
		$sql = "INSERT INTO lg_ordencompra
				SET
					Anio = '".$Anio."',
					CodOrganismo = '".$CodOrganismo."',
					NroOrden = '".$NroOrden."',
					Mes = '".$MesOrden."',
					Clasificacion = '".$Clasificacion."',
					CodDependencia = '".$CodDependencia."',
					CodProveedor = '".$CodProveedor."',
					NomProveedor = '".changeUrl($NomProveedor)."',
					FaxProveedor = '".changeUrl($FaxProveedor)."',
					CodAlmacen = '".$CodAlmacen."',
					FechaPrometida = '".formatFechaAMD($FechaPrometida)."',
					FechaOrden = '".formatFechaAMD($FechaOrden)."',
					PreparadaPor = '".$PreparadaPor."',
					FechaPreparacion = '".formatFechaAMD($FechaPreparacion)."',
					CodTipoServicio = '".$CodTipoServicio."',
					MontoBruto = '".setNumero($MontoBruto)."',
					MontoIGV = '".setNumero($MontoIGV)."',
					MontoOtros = '".setNumero($MontoOtros)."',
					MontoTotal = '".setNumero($MontoTotal)."',
					MontoPendiente = '".setNumero($MontoPendiente)."',
					MontoAfecto = '".setNumero($MontoAfecto)."',
					MontoNoAfecto = '".setNumero($MontoNoAfecto)."',
					CodFormaPago = '".$CodFormaPago."',
					CodAlmacenIngreso = '".$CodAlmacenIngreso."',
					NomContacto = '".changeUrl($NomContacto)."',
					FaxContacto = '".changeUrl($FaxContacto)."',
					PlazoEntrega = '".$PlazoEntrega."',
					DirEntrega = '".changeUrl($DirEntrega)."',
					InsEntrega = '".changeUrl($InsEntrega)."',
					Entregaren = '".changeUrl($Entregaren)."',
					Observaciones = '".changeUrl($Observaciones)."',
					ObsDetallada = '".changeUrl($ObsDetallada)."',
					TipoClasificacion = '".$TipoClasificacion."',
					CodCuenta = '".$CodCuenta."',
					CodCuentaPub20 = '".$CodCuentaPub20."',
					cod_partida = '".$cod_partida."',
					CodPresupuesto = '".$CodPresupuesto."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CantidadPedida, $_PrecioUnit, $_DescuentoPorcentaje, $_DescuentoFijo, $_FlagExonerado, $_PrecioUnitTotal, $_Total, $_CodUnidadRec, $_CantidadRec, $_FechaPrometida, $_CodCentroCosto, $_cod_partida, $_CodCuenta, $_CodCuentaPub20, $_Comentarios, $_CodRequerimiento, $_RequerimientoSecuencia, $_CotizacionSecuencia, $_CantidadRequerimiento) = split(";char:td;", $linea);
			if ($_CodUnidad == $_CodUnidadRec) $_CantidadRec = $_CantidadPedida;
			$_PrecioCantidad = $_CantidadPedida * $_PrecioUnit;
			##	inserto
			$sql = "INSERT INTO lg_ordencompradetalle
					SET
						Anio = '".$Anio."',
						CodOrganismo = '".$CodOrganismo."',
						NroOrden = '".$NroOrden."',
						Secuencia = '".++$_Secuencia."',
						Mes = '".$MesOrden."',
						CodItem = '".$_CodItem."',
						CommoditySub = '".$_CommoditySub."',
						Descripcion = '".$_Descripcion."',
						CodUnidad = '".$_CodUnidad."',
						CantidadPedida = '".$_CantidadPedida."',
						CodUnidadRec = '".$_CodUnidadRec."',
						CantidadRec = '".$_CantidadRec."',
						PrecioUnit = '".$_PrecioUnit."',
						PrecioCantidad = '".$_PrecioCantidad."',
						Total = '".$_Total."',
						DescuentoPorcentaje = '".$_DescuentoPorcentaje."',
						DescuentoFijo = '".$_DescuentoFijo."',
						FlagExonerado = '".$_FlagExonerado."',
						CodCentroCosto = '".$_CodCentroCosto."',
						Comentarios = '".$_Comentarios."',
						FechaPrometida = '".$_FechaPrometida."',
						CodCuenta = '".$_CodCuenta."',
						CodCuentaPub20 = '".$_CodCuentaPub20."',
						cod_partida = '".$_cod_partida."',
						Estado = 'PR',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	si la orden la estoy generando desde cotizaciones
			if ($GenerarPendiente == "S") {
				//	actualizo cotizacion
				$sql = "UPDATE lg_cotizacion
						SET Estado = 'AD'
						WHERE CotizacionSecuencia = '".$_CotizacionSecuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				if ($_CantidadRec == $_CantidadRequerimiento) $UpdateEstado = "Estado='CO',"; else $UpdateEstado = "";
				//	actualizo detalle del requerimiento
				$sql = "UPDATE lg_requerimientosdet
						SET
							$UpdateEstado
							Anio = '".$AnioOrden."',
							NroOrden = '".$NroOrden."',
							OrdenSecuencia = '".$_Secuencia."',
							CantidadOrdenCompra = '".$_CantidadRec."'
						WHERE
							CodRequerimiento = '".$_CodRequerimiento."' AND
							Secuencia = '".$_RequerimientoSecuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	verifico si completo todos los detalles del requerimeinto
				$sql = "SELECT *
						FROM lg_requerimientosdet
						WHERE
							CodRequerimiento = '".$_CodRequerimiento."' AND
							Estado = 'PE'";
				$query_requerimiento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_requerimiento) == 0) {
					//	completo requerimiento
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CO'
							WHERE CodRequerimiento = '".$_CodRequerimiento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
				
				//	inserto en relacion
				$sql = "INSERT INTO lg_cotizacionordenes
						SET
							CotizacionSecuencia = '".$_CotizacionSecuencia."',
							CodRequerimiento = '".$_CodRequerimiento."',
							SecuenciaRequerimiento = '".$_RequerimientoSecuencia."',
							CodOrganismo = '".$CodOrganismo."',
							Anio = '".$AnioOrden."',
							NroOrden = '".$NroOrden."',
							SecuenciaOrden = '".$_Secuencia."',
							TipoOrden = 'OC',
							CantidadOrden = '".$_CantidadPedida."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	si la orden la estoy generando desde cotizaciones
		if ($GenerarPendiente == "S") {
			//	consulto los requerimientos 
			$sql = "SELECT c.CotizacionSecuencia
					FROM
						lg_cotizacion c
						INNER JOIN lg_requerimientosdet rd ON (c.CodRequerimiento = rd.CodRequerimiento AND
															   c.Secuencia = rd.Secuencia)
					WHERE
						c.Numero = '".$Numero."' AND
						c.FlagAsignado = 'N' AND
						c.Estado = 'PE' AND
						rd.Estado = 'CO'
					ORDER BY c.Secuencia";
			$query_estado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field_estado = mysql_fetch_array($query_estado)) {
				//	actualizo cotizacion
				$sql = "UPDATE lg_cotizacion
						SET Estado = 'NA'
						WHERE CotizacionSecuencia = '".$field_estado['CotizacionSecuencia']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	inserto distribucion
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles_partida);
		foreach ($detalle as $linea) {
			list($_cod_partida, $_CodCuenta, $_CodCuentaPub20, $_Monto) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_distribucionoc
					SET
						Anio = '".$Anio."',
						CodOrganismo = '".$CodOrganismo."',
						NroOrden = '".$NroOrden."',
						Secuencia = '".++$_Secuencia."',
						Mes = '".$MesOrden."',
						cod_partida = '".$_cod_partida."',
						CodCuenta = '".$_CodCuenta."',
						CodCuentaPub20 = '".$_CodCuentaPub20."',
						Monto = '".$_Monto."',
						CodCentroCosto = '".$_PARAMETRO["CCOSTOCOMPRA"]."',
						UltimoUsuario = '".$_PARAMETRO["CCOSTOCOMPRA"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	inserto
			$sql = "INSERT INTO lg_distribucioncompromisos
					SET
						Anio = '".$Anio."',
						CodOrganismo = '".$CodOrganismo."',
						CodProveedor = '".$CodProveedor."',
						CodTipoDocumento = 'OC',
						NroDocumento = '".$NroOrden."',
						Secuencia = '".$_Secuencia."',
						Linea = '1',
						Mes = '".$MesOrden."',
						CodCentroCosto = '".$_PARAMETRO["CCOSTOCOMPRA"]."',
						cod_partida = '".$_cod_partida."',
						Monto = '".$_Monto."',
						Periodo = '".$PeriodoOrden."',
						CodPresupuesto = '".$CodPresupuesto."',
						Origen = 'OC',
						Estado = 'PE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	valido que no cambio el a;o de la orden
		if ($AnioOrden != $Anio) die("No se puede modificar el año de la orden.");
		//	valores
		list($cod_partida, $CodCuenta, $CodCuentaPub20) = getPartidaCuentaFromIGV($_PARAMETRO["IGVCODIGO"]);
		list($DiaOrden, $MesOrden, $AnioOrden) = split("[./-]", $FechaOrden);
		$PeriodoOrden = "$AnioOrden-$MesOrden";
		//	actualizo orden
		##	actualizo
		$sql = "UPDATE lg_ordencompra
				SET
					Mes = '".$MesOrden."',
					Clasificacion = '".$Clasificacion."',
					CodAlmacen = '".$CodAlmacen."',
					FechaPrometida = '".formatFechaAMD($FechaPrometida)."',
					FechaOrden = '".formatFechaAMD($FechaOrden)."',
					MontoBruto = '".setNumero($MontoBruto)."',
					MontoIGV = '".setNumero($MontoIGV)."',
					MontoOtros = '".setNumero($MontoOtros)."',
					MontoTotal = '".setNumero($MontoTotal)."',
					MontoPendiente = '".setNumero($MontoPendiente)."',
					MontoAfecto = '".setNumero($MontoAfecto)."',
					MontoNoAfecto = '".setNumero($MontoNoAfecto)."',
					CodFormaPago = '".$CodFormaPago."',
					CodAlmacenIngreso = '".$CodAlmacenIngreso."',
					NomContacto = '".changeUrl($NomContacto)."',
					FaxContacto = '".changeUrl($FaxContacto)."',
					PlazoEntrega = '".$PlazoEntrega."',
					DirEntrega = '".changeUrl($DirEntrega)."',
					InsEntrega = '".changeUrl($InsEntrega)."',
					Entregaren = '".changeUrl($Entregaren)."',
					Observaciones = '".changeUrl($Observaciones)."',
					ObsDetallada = '".changeUrl($ObsDetallada)."',
					TipoClasificacion = '".$TipoClasificacion."',
					CodCuenta = '".$CodCuenta."',
					CodCuentaPub20 = '".$CodCuentaPub20."',
					cod_partida = '".$cod_partida."',
					CodPresupuesto = '".$CodPresupuesto."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo= '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	detalles
		##	elimino detalles
		$sql = "DELETE FROM lg_ordencompradetalle
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo= '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CantidadPedida, $_PrecioUnit, $_DescuentoPorcentaje, $_DescuentoFijo, $_FlagExonerado, $_PrecioUnitTotal, $_Total, $_CodUnidadRec, $_CantidadRec, $_FechaPrometida, $_CodCentroCosto, $_cod_partida, $_CodCuenta, $_CodCuentaPub20, $_Comentarios, $_CodRequerimiento, $_RequerimientoSecuencia, $_CotizacionSecuencia, $_CantidadRequerimiento) = split(";char:td;", $linea);
			if ($_CodUnidad == $_CodUnidadRec) $_CantidadRec = $_CantidadPedida;
			$_PrecioCantidad = $_CantidadPedida * $_PrecioUnit;
			##	inserto
			$sql = "INSERT INTO lg_ordencompradetalle
					SET
						Anio = '".$Anio."',
						CodOrganismo = '".$CodOrganismo."',
						NroOrden = '".$NroOrden."',
						Secuencia = '".++$_Secuencia."',
						Mes = '".$MesOrden."',
						CodItem = '".$_CodItem."',
						CommoditySub = '".$_CommoditySub."',
						Descripcion = '".$_Descripcion."',
						CodUnidad = '".$_CodUnidad."',
						CantidadPedida = '".$_CantidadPedida."',
						CodUnidadRec = '".$_CodUnidadRec."',
						CantidadRec = '".$_CantidadRec."',
						PrecioUnit = '".$_PrecioUnit."',
						PrecioCantidad = '".$_PrecioCantidad."',
						Total = '".$_Total."',
						DescuentoPorcentaje = '".$_DescuentoPorcentaje."',
						DescuentoFijo = '".$_DescuentoFijo."',
						FlagExonerado = '".$_FlagExonerado."',
						CodCentroCosto = '".$_CodCentroCosto."',
						Comentarios = '".$_Comentarios."',
						FechaPrometida = '".$_FechaPrometida."',
						CodCuenta = '".$_CodCuenta."',
						CodCuentaPub20 = '".$_CodCuentaPub20."',
						cod_partida = '".$_cod_partida."',
						Estado = 'PR',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	distribucion
		##	elimino detalles
		$sql = "DELETE FROM lg_distribucionoc
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo= '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$sql = "DELETE FROM lg_distribucioncompromisos
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = 'OC' AND
					NroDocumento = '".$NroOrden."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	inserto distribucion
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles_partida);
		foreach ($detalle as $linea) {
			list($_cod_partida, $_CodCuenta, $_CodCuentaPub20, $_Monto) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_distribucionoc
					SET
						Anio = '".$Anio."',
						CodOrganismo = '".$CodOrganismo."',
						NroOrden = '".$NroOrden."',
						Secuencia = '".++$_Secuencia."',
						Mes = '".$MesOrden."',
						cod_partida = '".$_cod_partida."',
						CodCuenta = '".$_CodCuenta."',
						CodCuentaPub20 = '".$_CodCuentaPub20."',
						Monto = '".$_Monto."',
						CodCentroCosto = '".$_PARAMETRO["CCOSTOCOMPRA"]."',
						UltimoUsuario = '".$_PARAMETRO["CCOSTOCOMPRA"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	inserto
			$sql = "INSERT INTO lg_distribucioncompromisos
					SET
						Anio = '".$Anio."',
						CodOrganismo = '".$CodOrganismo."',
						CodProveedor = '".$CodProveedor."',
						CodTipoDocumento = 'OC',
						NroDocumento = '".$NroOrden."',
						Secuencia = '".$_Secuencia."',
						Linea = '1',
						Mes = '".$MesOrden."',
						CodCentroCosto = '".$_PARAMETRO["CCOSTOCOMPRA"]."',
						cod_partida = '".$_cod_partida."',
						Monto = '".$_Monto."',
						Periodo = '".$PeriodoOrden."',
						CodPresupuesto = '".$CodPresupuesto."',
						Origen = 'OC',
						Estado = 'PE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	revisar
	elseif ($accion == "revisar") {
		mysql_query("BEGIN");
		//	-----------------
		##	genero el nuevo codigo
		if ($NroInterno == "") $NroInterno = getCodigo_3("lg_ordencompra", "NroInterno", "Anio", "CodOrganismo", $Anio, $CodOrganismo, 10);
		//	modifico orden
		$sql = "UPDATE lg_ordencompra
				SET
					NroInterno = '".$NroInterno."',
					Estado = 'RV',
					RevisadaPor = '".$RevisadaPor."',
					FechaRevision = '".formatFechaAMD($FechaRevision)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo compromisos
		$sql = "UPDATE lg_distribucioncompromisos
				SET
					Estado = 'CO',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = 'OC' AND
					NroDocumento = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		echo "|Se ha generado la Orden de Compra <strong>Nro. $NroInterno</strong>";
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		mysql_query("BEGIN");
		//	-----------------
		//	modifico orden
		$sql = "UPDATE lg_ordencompra
				SET
					Estado = 'AP',
					AprobadaPor = '".$AprobadaPor."',
					FechaAprobacion = '".formatFechaAMD($FechaAprobacion)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalle
		$sql = "UPDATE lg_ordencompradetalle
				SET
					Estado = 'PE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	anular
	elseif ($accion == "anular") {
		mysql_query("BEGIN");
		//	-----------------
		if ($Estado == "PR") {
			$EstadoOrden = "AN";
			$EstadoDetalle = "AN";
			$EstadoCompromiso = "AN";
		}
		elseif ($Estado != "PR") {
			$EstadoOrden = "PR";
			$EstadoDetalle = "PR";
			$EstadoCompromiso = "PE";
		}
		//	modifico orden
		$sql = "UPDATE lg_ordencompra
				SET
					Estado = '".$EstadoOrden."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	modifico detalles
		$sql = "UPDATE lg_ordencompradetalle
				SET
					Estado = '".$EstadoDetalle."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	modifico compromisos
		$sql = "UPDATE lg_distribucioncompromisos
				SET
					Estado = '".$EstadoCompromiso."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = 'OC' AND
					NroDocumento = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		if ($EstadoOrden == "AN") {
			$sql = "SELECT *
					FROM lg_cotizacionordenes
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."' AND
						TipoOrden = 'OC'
					ORDER BY SecuenciaOrden";
			$query_oc = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_oc = mysql_fetch_array($query_oc)) {
				//
				$sql = "UPDATE lg_requerimientos
						SET Estado = 'AP'
						WHERE CodRequerimiento = '".$field_oc['CodRequerimiento']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				//
				$sql = "UPDATE lg_requerimientosdet
						SET
							Estado = 'PE',
							CantidadOrdenCompra = CantidadOrdenCompra - ".floatval($field_oc['CantidadOrden']).",
							NroOrden = '',
							OrdenSecuencia = ''
						WHERE
							CodRequerimiento = '".$field_oc['CodRequerimiento']."' AND
							Secuencia = '".$field_oc['SecuenciaRequerimiento']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				//
				$sql = "UPDATE lg_cotizacion
						SET Estado = 'PE'
						WHERE CotizacionSecuencia = '".$field_oc['CotizacionSecuencia']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	cerrar
	elseif ($accion == "cerrar") {
		mysql_query("BEGIN");
		//	-----------------
		//	modifico orden
		$sql = "UPDATE lg_ordencompra
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalles
		$sql = "UPDATE lg_ordencompradetalle
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	cerrar linea
	elseif ($accion == "cerrar-detalle") {
		mysql_query("BEGIN");
		//	-----------------
		list($Anio, $CodOrganismo, $NroOrden, $Secuencia) = split("[.]", $registro);
		//	verifico los detalles
		$sql = "SELECT Estado
				FROM lg_ordencompradetalle
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."' AND
					Secuencia = '".$Secuencia."' AND
					Estado = 'PE'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) == 0) die("Solo se pueden cerrar lineas en Estado <strong>Pendiente</strong>");
		##
		//	modifico detalles
		$sql = "UPDATE lg_ordencompradetalle
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##
		//	consulto si no quedan pendientes en el requerimiento
		$sql = "SELECT Estado
				FROM lg_ordencompradetalle
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."' AND
					Estado = 'PE'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) == 0) {
			//	consulto si se completaron algunas lineas en el requerimiento
			$sql = "SELECT Estado
					FROM lg_ordencompradetalle
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."' AND
						Estado = 'CO'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query) != 0) {
				$sql = "UPDATE lg_ordencompra
						SET Estado = 'CO'
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$NroOrden."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				$sql = "UPDATE lg_ordencompra
						SET Estado = 'CE'
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$NroOrden."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	orden de servicio
elseif ($modulo == "orden_servicio") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		list($cod_partida, $CodCuenta, $CodCuentaPub20) = getPartidaCuentaFromIGV($_PARAMETRO["IGVCODIGO"]);
		list($DiaOrden, $MesOrden, $AnioOrden) = split("[./-]", $FechaDocumento);
		$PeriodoOrden = "$AnioOrden-$MesOrden";
		//	inserto orden
		##	genero el nuevo codigo
		$NroOrden = getCodigo_3("lg_ordenservicio", "NroOrden", "Anio", "CodOrganismo", $Anio, $CodOrganismo, 10);
		##	inserto
		$sql = "INSERT INTO lg_ordenservicio
				SET
					Anio = '".$Anio."',
					CodOrganismo = '".$CodOrganismo."',
					NroOrden = '".$NroOrden."',
					Mes = '".$MesOrden."',
					CodDependencia = '".$CodDependencia."',
					CodProveedor = '".$CodProveedor."',
					NomProveedor = '".changeUrl($NomProveedor)."',
					CodFormaPago = '".$CodFormaPago."',
					FechaDocumento = '".formatFechaAMD($FechaDocumento)."',
					DiasPago = '".$DiasPago."',
					CodTipoPago = '".$CodTipoPago."',
					CodTipoServicio = '".$CodTipoServicio."',
					PlazoEntrega = '".$PlazoEntrega."',
					FechaEntrega = '".formatFechaAMD($FechaEntrega)."',
					MontoOriginal = '".setNumero($MontoOriginal)."',
					MontoNoAfecto = '".setNumero($MontoNoAfecto)."',
					MontoIva = '".setNumero($MontoIva)."',
					TotalMontoIva = '".setNumero($TotalMontoIva)."',
					MontoPendiente = '".setNumero($MontoPendiente)."',
					Descripcion = '".changeUrl($Descripcion)."',
					DescAdicional = '".changeUrl($DescAdicional)."',
					Observaciones = '".changeUrl($Observaciones)."',
					FechaValidoDesde = '".formatFechaAMD($FechaValidoDesde)."',
					FechaValidoHasta = '".formatFechaAMD($FechaValidoHasta)."',
					CodCentroCosto = '".$CodCentroCosto."',
					PreparadaPor = '".$PreparadaPor."',
					FechaPreparacion = '".formatFechaAMD($FechaPreparacion)."',
					CodCuenta = '".$CodCuenta."',
					CodCuentaPub20 = '".$CodCuentaPub20."',
					cod_partida = '".$cod_partida."',
					CodPresupuesto = '".$CodPresupuesto."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($_CommoditySub, $_Descripcion, $_CantidadPedida, $_CodUnidadRec, $_CantidadRec, $_PrecioUnit, $_FlagExonerado, $_Total, $_FechaEsperadaTermino, $_FechaTermino, $_CodCentroCosto, $_NroActivo, $_FlagTerminado, $_cod_partida, $_CodCuenta, $_CodCuentaPub20, $_Comentarios, $_CodRequerimiento, $_RequerimientoSecuencia, $_CotizacionSecuencia, $_CantidadRequerimiento) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_ordenserviciodetalle
					SET
						Anio = '".$Anio."',
						CodOrganismo = '".$CodOrganismo."',
						NroOrden = '".$NroOrden."',
						Secuencia = '".++$_Secuencia."',
						Mes = '".$MesOrden."',
						CommoditySub = '".$_CommoditySub."',
						Descripcion = '".$_Descripcion."',
						CantidadPedida = '".$_CantidadPedida."',
						CodUnidadRec = '".$_CodUnidadRec."',
						CantidadRec = '".$_CantidadRec."',
						PrecioUnit = '".$_PrecioUnit."',
						Total = '".$_Total."',
						FechaEsperadaTermino = '".$_FechaEsperadaTermino."',
						FechaTermino = '".$_FechaTermino."',
						CodCentroCosto = '".$_CodCentroCosto."',
						NroActivo = '".$_NroActivo."',
						FlagExonerado = '".$_FlagExonerado."',
						FlagTerminado = '".$_FlagTerminado."',
						Comentarios = '".$_Comentarios."',
						cod_partida = '".$_cod_partida."',
						CodCuenta = '".$_CodCuenta."',
						CodCuentaPub20 = '".$_CodCuentaPub20."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	si la orden la estoy generando desde cotizaciones
			if ($GenerarPendiente == "S") {
				//	actualizo cotizacion
				$sql = "UPDATE lg_cotizacion
						SET Estado = 'AD'
						WHERE CotizacionSecuencia = '".$_CotizacionSecuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				if ($_CantidadRec == $_CantidadRequerimiento) $UpdateEstado = "Estado='CO',"; else $UpdateEstado = "";
				//	actualizo detalle del requerimiento
				$sql = "UPDATE lg_requerimientosdet
						SET
							$UpdateEstado
							Anio = '".$AnioOrden."',
							NroOrden = '".$NroOrden."',
							OrdenSecuencia = '".$_Secuencia."',
							CantidadOrdenCompra = '".$_CantidadRec."'
						WHERE
							CodRequerimiento = '".$_CodRequerimiento."' AND
							Secuencia = '".$_RequerimientoSecuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	verifico si completo todos los detalles del requerimeinto
				$sql = "SELECT *
						FROM lg_requerimientosdet
						WHERE
							CodRequerimiento = '".$_CodRequerimiento."' AND
							Estado = 'PE'";
				$query_requerimiento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_requerimiento) == 0) {
					//	completo requerimiento
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CO'
							WHERE CodRequerimiento = '".$_CodRequerimiento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
				
				//	inserto en relacion
				$sql = "INSERT INTO lg_cotizacionordenes
						SET
							CotizacionSecuencia = '".$_CotizacionSecuencia."',
							CodRequerimiento = '".$_CodRequerimiento."',
							SecuenciaRequerimiento = '".$_RequerimientoSecuencia."',
							CodOrganismo = '".$CodOrganismo."',
							Anio = '".$AnioOrden."',
							NroOrden = '".$NroOrden."',
							SecuenciaOrden = '".$_Secuencia."',
							TipoOrden = 'OS',
							CantidadOrden = '".$_CantidadPedida."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	si la orden la estoy generando desde cotizaciones
		if ($GenerarPendiente == "S") {
			//	consulto los requerimientos 
			$sql = "SELECT c.CotizacionSecuencia
					FROM
						lg_cotizacion c
						INNER JOIN lg_requerimientosdet rd ON (c.CodRequerimiento = rd.CodRequerimiento AND
															   c.Secuencia = rd.Secuencia)
					WHERE
						c.Numero = '".$Numero."' AND
						c.FlagAsignado = 'N' AND
						c.Estado = 'PE' AND
						rd.Estado = 'CO'
					ORDER BY c.Secuencia";
			$query_estado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field_estado = mysql_fetch_array($query_estado)) {
				//	actualizo cotizacion
				$sql = "UPDATE lg_cotizacion
						SET Estado = 'NA'
						WHERE CotizacionSecuencia = '".$field_estado['CotizacionSecuencia']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	inserto distribucion
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles_partida);
		foreach ($detalle as $linea) {
			list($_cod_partida, $_CodCuenta, $_CodCuentaPub20, $_Monto) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_distribucionos
					SET
						Anio = '".$Anio."',
						CodOrganismo = '".$CodOrganismo."',
						NroOrden = '".$NroOrden."',
						Secuencia = '".++$_Secuencia."',
						Mes = '".$MesOrden."',
						cod_partida = '".$_cod_partida."',
						CodCuenta = '".$_CodCuenta."',
						CodCuentaPub20 = '".$_CodCuentaPub20."',
						Monto = '".$_Monto."',
						CodCentroCosto = '".$_PARAMETRO["CCOSTOCOMPRA"]."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	inserto
			$sql = "INSERT INTO lg_distribucioncompromisos
					SET
						Anio = '".$Anio."',
						CodOrganismo = '".$CodOrganismo."',
						CodProveedor = '".$CodProveedor."',
						CodTipoDocumento = 'OS',
						NroDocumento = '".$NroOrden."',
						Secuencia = '".$_Secuencia."',
						Linea = '1',
						Mes = '".$MesOrden."',
						CodCentroCosto = '".$_PARAMETRO["CCOSTOCOMPRA"]."',
						cod_partida = '".$_cod_partida."',
						Monto = '".$_Monto."',
						Periodo = '".$PeriodoOrden."',
						CodPresupuesto = '".$CodPresupuesto."',
						Origen = 'OS',
						Estado = 'PE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		list($cod_partida, $CodCuenta, $CodCuentaPub20) = getPartidaCuentaFromIGV($_PARAMETRO["IGVCODIGO"]);
		list($DiaOrden, $MesOrden, $AnioOrden) = split("[./-]", $FechaDocumento);
		$PeriodoOrden = "$AnioOrden-$MesOrden";
		//	actualizo orden
		##	actualizo
		$sql = "UPDATE lg_ordenservicio
				SET
					Mes = '".$MesOrden."',
					CodDependencia = '".$CodDependencia."',
					CodFormaPago = '".$CodFormaPago."',
					FechaDocumento = '".formatFechaAMD($FechaDocumento)."',
					DiasPago = '".$DiasPago."',
					CodTipoPago = '".$CodTipoPago."',
					PlazoEntrega = '".$PlazoEntrega."',
					FechaEntrega = '".formatFechaAMD($FechaEntrega)."',
					MontoOriginal = '".setNumero($MontoOriginal)."',
					MontoNoAfecto = '".setNumero($MontoNoAfecto)."',
					MontoIva = '".setNumero($MontoIva)."',
					TotalMontoIva = '".setNumero($TotalMontoIva)."',
					MontoPendiente = '".setNumero($MontoPendiente)."',
					Descripcion = '".changeUrl($Descripcion)."',
					DescAdicional = '".changeUrl($DescAdicional)."',
					Observaciones = '".changeUrl($Observaciones)."',
					FechaValidoDesde = '".formatFechaAMD($FechaValidoDesde)."',
					FechaValidoHasta = '".formatFechaAMD($FechaValidoHasta)."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodCuenta = '".$CodCuenta."',
					CodCuentaPub20 = '".$CodCuentaPub20."',
					cod_partida = '".$cod_partida."',
					CodPresupuesto = '".$CodPresupuesto."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo= '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	detalles
		##	elimino detalles
		$sql = "DELETE FROM lg_ordenserviciodetalle
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo= '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($_CommoditySub, $_Descripcion, $_CantidadPedida, $_CodUnidadRec, $_CantidadRec, $_PrecioUnit, $_FlagExonerado, $_Total, $_FechaEsperadaTermino, $_FechaTermino, $_CodCentroCosto, $_NroActivo, $_FlagTerminado, $_cod_partida, $_CodCuenta, $_CodCuentaPub20, $_Comentarios) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_ordenserviciodetalle
					SET
						Anio = '".$Anio."',
						CodOrganismo = '".$CodOrganismo."',
						NroOrden = '".$NroOrden."',
						Secuencia = '".++$_Secuencia."',
						Mes = '".$MesOrden."',
						CommoditySub = '".$_CommoditySub."',
						Descripcion = '".$_Descripcion."',
						CantidadPedida = '".$_CantidadPedida."',
						CodUnidadRec = '".$_CodUnidadRec."',
						CantidadRec = '".$_CantidadRec."',
						PrecioUnit = '".$_PrecioUnit."',
						Total = '".$_Total."',
						FechaEsperadaTermino = '".$_FechaEsperadaTermino."',
						FechaTermino = '".$_FechaTermino."',
						CodCentroCosto = '".$_CodCentroCosto."',
						NroActivo = '".$_NroActivo."',
						FlagExonerado = '".$_FlagExonerado."',
						FlagTerminado = '".$_FlagTerminado."',
						Comentarios = '".$_Comentarios."',
						cod_partida = '".$_cod_partida."',
						CodCuenta = '".$_CodCuenta."',
						CodCuentaPub20 = '".$_CodCuentaPub20."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	distribucion
		##	elimino detalles
		$sql = "DELETE FROM lg_distribucionos
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo= '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$sql = "DELETE FROM lg_distribucioncompromisos
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = 'OS' AND
					NroDocumento = '".$NroOrden."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	inserto distribucion
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles_partida);
		foreach ($detalle as $linea) {
			list($_cod_partida, $_CodCuenta, $_CodCuentaPub20, $_Monto) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_distribucionos
					SET
						Anio = '".$Anio."',
						CodOrganismo = '".$CodOrganismo."',
						NroOrden = '".$NroOrden."',
						Secuencia = '".++$_Secuencia."',
						Mes = '".$MesOrden."',
						cod_partida = '".$_cod_partida."',
						CodCuenta = '".$_CodCuenta."',
						CodCuentaPub20 = '".$_CodCuentaPub20."',
						Monto = '".$_Monto."',
						CodCentroCosto = '".$_PARAMETRO["CCOSTOCOMPRA"]."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	inserto
			$sql = "INSERT INTO lg_distribucioncompromisos
					SET
						Anio = '".$Anio."',
						CodOrganismo = '".$CodOrganismo."',
						CodProveedor = '".$CodProveedor."',
						CodTipoDocumento = 'OS',
						NroDocumento = '".$NroOrden."',
						Secuencia = '".$_Secuencia."',
						Linea = '1',
						Mes = '".$MesOrden."',
						CodCentroCosto = '".$_PARAMETRO["CCOSTOCOMPRA"]."',
						cod_partida = '".$_cod_partida."',
						Monto = '".$_Monto."',
						Periodo = '".$PeriodoOrden."',
						CodPresupuesto = '".$CodPresupuesto."',
						Origen = 'OS',
						Estado = 'PE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	revisar
	elseif ($accion == "revisar") {
		mysql_query("BEGIN");
		##	genero el nuevo codigo
		if ($NroInterno == "") $NroInterno = getCodigo_3("lg_ordenservicio", "NroInterno", "Anio", "CodOrganismo", $Anio, $CodOrganismo, 10);
		//	modifico orden
		$sql = "UPDATE lg_ordenservicio
				SET
					NroInterno = '".$NroInterno."',
					Estado = 'RV',
					RevisadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo= '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo compromisos
		$sql = "UPDATE lg_distribucioncompromisos
				SET
					Estado = 'CO',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = 'OS' AND
					NroDocumento = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		echo "|Se ha generado la Orden de Servicio <strong>Nro. $NroInterno</strong>";
		mysql_query("COMMIT");
		
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		mysql_query("BEGIN");
		//	modifico orden
		$sql = "UPDATE lg_ordenservicio
				SET
					Estado = 'AP',
					AprobadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobacion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		mysql_query("COMMIT");
	}
	
	//	anular
	elseif ($accion == "anular") {
		mysql_query("BEGIN");
		//	-----------------
		if ($Estado == "PR") {
			$EstadoOrden = "AN";
			$EstadoDetalle = "S";
			$EstadoCompromiso = "AN";
		}
		elseif ($Estado != "PR") {
			$EstadoOrden = "PR";
			$EstadoDetalle = "N";
			$EstadoCompromiso = "PE";
		}
		//	modifico orden
		$sql = "UPDATE lg_ordenservicio
				SET
					Estado = '".$EstadoOrden."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	modifico detalles
		$sql = "UPDATE lg_ordenserviciodetalle
				SET
					FlagTerminado = '".$EstadoDetalle."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	modifico compromisos
		$sql = "UPDATE lg_distribucioncompromisos
				SET
					Estado = '".$EstadoCompromiso."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = 'OS' AND
					NroDocumento = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		if ($EstadoOrden == "AN") {
			$sql = "SELECT *
					FROM lg_cotizacionordenes
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."' AND
						TipoOrden = 'OS'
					ORDER BY SecuenciaOrden";
			$query_oc = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_oc = mysql_fetch_array($query_oc)) {
				//
				$sql = "UPDATE lg_requerimientos
						SET Estado = 'AP'
						WHERE CodRequerimiento = '".$field_oc['CodRequerimiento']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				//
				$sql = "UPDATE lg_requerimientosdet
						SET
							Estado = 'PE',
							CantidadOrdenCompra = CantidadOrdenCompra - ".floatval($field_oc['CantidadOrden']).",
							NroOrden = '',
							OrdenSecuencia = ''
						WHERE
							CodRequerimiento = '".$field_oc['CodRequerimiento']."' AND
							Secuencia = '".$field_oc['SecuenciaRequerimiento']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				//
				$sql = "UPDATE lg_cotizacion
						SET Estado = 'PE'
						WHERE CotizacionSecuencia = '".$field_oc['CotizacionSecuencia']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	cerrar
	elseif ($accion == "cerrar") {
		mysql_query("BEGIN");
		//	modifico orden
		$sql = "UPDATE lg_ordenservicio
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalles
		$sql = "UPDATE lg_ordenserviciodetalle
				SET
					FlagTerminado = 'S',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		mysql_query("COMMIT");
	}
	
	//	confirmar
	elseif ($accion == "confirmar") {
		mysql_query("BEGIN");
		//	valores
		$FechaTermino = formatFechaAMD($FechaTermino);
		$PorRecibirTotal = setNumero($PorRecibirTotal);
		$CantidadTotal = setNumero($CantidadTotal);
		$SaldoTotal = setNumero($SaldoTotal);
		
		//	confirmo estado
		if (floatval($CantidadTotal) > floatval($SaldoTotal)) {
			$FlagTerminado = "N";
			$FechaTermino = "0000-00-00";
		}
		else {
			$FlagTerminado = "S";
			$FechaTermino = substr(ahora(), 0, 10);
		}
		
		//	inserto confirmacion
		$NroConfirmacion = getCodigo("lg_confirmacionservicio", "NroConfirmacion", 4);
		$DocumentoReferencia = "$NroOrden-$NroConfirmacion";
		$sql = "INSERT INTO lg_confirmacionservicio (
							Anio,
							CodOrganismo,
							NroOrden,
							Secuencia,
							NroConfirmacion,
							DocumentoReferencia,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$Anio."',
							'".$CodOrganismo."',
							'".$NroOrden."',
							'".$Secuencia."',
							'".$NroConfirmacion."',
							'".$DocumentoReferencia."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico servicio detalle
		$sql = "UPDATE lg_ordenserviciodetalle
				SET
					ConfirmadoPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
					FechaConfirmacion = NOW(),
					FlagTerminado = '".$FlagTerminado."',
					FechaTermino = '".$FechaTermino."',
					CantidadRecibida = (CantidadRecibida + ".floatval($PorRecibirTotal)."),
					FechaTermino = '".$FechaTermino."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo estado de la orden si confirme todos los servicios
		$sql = "SELECT *
				FROM lg_ordenserviciodetalle
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."' AND
					FlagTerminado <> 'S'";
		$query_osd = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_osd) == 0) {
			$sql = "UPDATE lg_ordenservicio
					SET Estado = 'CO'
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	recalculo
		if (afectaTipoServicio($CodTipoServicio)) $FactorImpuesto = getPorcentajeIVA($CodTipoServicio);
		else $FactorImpuesto = 0;
		$PrecioCantidad = $PrecioUnit * $PorRecibirTotal;
		if ($FlagExonerado == "S") {
			$MontoAfecto = 0;
			$MontoNoAfecto = $PrecioCantidad;
		} else {
			$MontoAfecto = $PrecioCantidad;
			$MontoNoAfecto = 0;
		}
		
		##	consulto los montos de la orden de compra
		$sql = "SELECT
					MontoOriginal AS MontoAfecto,
					MontoNoAfecto,
					MontoIva AS MontoIGV
				FROM lg_ordenservicio
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_afecto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_afecto) != 0) $field_afecto = mysql_fetch_array($query_afecto);
		
		##	actualizo montos del documento
		if ($MontoAfecto != $field_afecto['MontoAfecto']) {
			$MontoImpuestos = round(($MontoAfecto * $field_afecto['MontoIGV'] / $field_afecto['MontoAfecto']), 2);
		} else {
			$MontoAfecto = $field_afecto['MontoAfecto'];
			$MontoNoAfecto = $field_afecto['MontoNoAfecto'];
			$MontoImpuestos = $field_afecto['MontoIGV'];
		}
		$MontoTotal = $MontoAfecto + $MontoNoAfecto + $MontoImpuestos;
		
		//	inserto el documento
		$sql = "INSERT INTO ap_documentos (
							Anio,
							CodOrganismo,
							CodProveedor,
							DocumentoClasificacion,
							DocumentoReferencia,
							Fecha,
							ReferenciaTipoDocumento,
							ReferenciaNroDocumento,
							MontoAfecto,
							MontoNoAfecto,
							MontoImpuestos,
							MontoTotal,
							MontoPendiente,
							Estado,
							TransaccionTipoDocumento,
							TransaccionNroDocumento,
							Comentarios,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$Anio."',
							'".$CodOrganismo."',
							'".$CodProveedor."',
							'".$_PARAMETRO['DOCREFOS']."',
							'".$DocumentoReferencia."',
							NOW(),
							'OS',
							'".$NroOrden."',
							'".$MontoAfecto."',
							'".$MontoNoAfecto."',
							'".$MontoImpuestos."',
							'".$MontoTotal."',
							'".$MontoTotal."',
							'PR',
							'OS',
							'".$NroConfirmacion."',
							'".$Descripcion."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto el documento detalle
		$sql = "INSERT INTO ap_documentosdetalle (
							Anio,
							CodProveedor,
							DocumentoClasificacion,
							DocumentoReferencia,
							Secuencia,
							ReferenciaSecuencia,
							CommoditySub,
							Descripcion,
							Cantidad,
							PrecioUnit,
							PrecioCantidad,
							Total,
							CodCentroCosto,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$Anio."',
							'".$CodProveedor."',
							'".$_PARAMETRO['DOCREFOS']."',
							'".$DocumentoReferencia."',
							'1',
							'".$Secuencia."',
							'".$CommoditySub."',
							'".$Descripcion."',
							'".$PorRecibirTotal."',
							'".$PrecioUnit."',
							'".$PrecioCantidad."',
							'".$MontoTotal."',
							'".$CodCentroCosto."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		mysql_query("COMMIT");
		
		echo "|$NroConfirmacion|$Anio.$CodProveedor.".$_PARAMETRO['DOCREFOS'].".$DocumentoReferencia";
	}
	
	//	desconfirmar
	elseif ($accion == "desconfirmar") {
		mysql_query("BEGIN");
		list($Anio, $CodProveedor, $DocumentoClasificacion, $DocumentoReferencia) = split("[.]", $registro);
		//	consulto documentos
		$sql = "SELECT Estado, TransaccionNroDocumento
				FROM ap_documentos
				WHERE
					Anio = '".$Anio."' AND
					CodProveedor = '".$CodProveedor."' AND
					DocumentoClasificacion = '".$DocumentoClasificacion."' AND
					DocumentoReferencia = '".$DocumentoReferencia."'";
		$query_doc = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_doc) != 0) $field_doc = mysql_fetch_array($query_doc);
		if ($field_doc['Estado'] == "RV") die("No se puede desconfirmar un documento <strong>Facturado</strong>");
		
		//	consultom documentos detalle
		$sql = "SELECT Cantidad, ReferenciaSecuencia
				FROM ap_documentosdetalle
				WHERE
					Anio = '".$Anio."' AND
					CodProveedor = '".$CodProveedor."' AND
					DocumentoClasificacion = '".$DocumentoClasificacion."' AND
					DocumentoReferencia = '".$DocumentoReferencia."'";
		$query_detalle = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_detalle) != 0) $field_detalle = mysql_fetch_array($query_detalle);
		
		//	actualizo servicio detalle
		$sql = "UPDATE lg_ordenserviciodetalle
				SET
					FlagTerminado = 'N',
					CantidadRecibida = (CantidadRecibida - ".floatval($field_detalle['Cantidad'])."),
					FechaTermino = '',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."' AND
					Secuencia = '".$field_detalle['ReferenciaSecuencia']."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo servicio
		$sql = "UPDATE lg_ordenservicio
				SET
					Estado = 'AP',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	elimino documentos
		$sql = "DELETE FROM ap_documentos
				WHERE
					Anio = '".$Anio."' AND
					CodProveedor = '".$CodProveedor."' AND
					DocumentoClasificacion = '".$DocumentoClasificacion."' AND
					DocumentoReferencia = '".$DocumentoReferencia."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	elimino documentos
		$sql = "DELETE FROM lg_confirmacionservicio
				WHERE NroConfirmacion = '".$field_doc['TransaccionNroDocumento']."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		mysql_query("COMMIT");
	}
}

//	almacen
elseif ($modulo == "almacen") {
	$Comentarios = changeUrl($Comentarios);
	$FechaDocumento = formatFechaAMD($FechaDocumento);
	$AnioDocumento = substr($FechaDocumento, 0, 4);
	
	//	despacho
	if ($accion == "despacho") {
		mysql_query("BEGIN");
		//	periodo
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	inserto transaccion
		##	genero el nuevo codigo
		$NroDocumento = getCodigo_3("lg_transaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		$NroInterno = getCodigo("lg_transaccion", "NroInterno", 6, "Anio", $AnioDocumento, "CodOrganismo", $CodOrganismo, "CodDocumento", $CodDocumento);
		##	inserto
		$sql = "INSERT INTO lg_transaccion
				SET
					CodOrganismo = '".$CodOrganismo."',
					CodDocumento = '".$CodDocumento."',
					NroDocumento = '".$NroDocumento."',
					NroInterno = '".$NroInterno."',
					CodTransaccion = '".$CodTransaccion."',
					FechaDocumento = '".$FechaDocumento."',
					Periodo = '".$Periodo."',
					CodAlmacen = '".$CodAlmacen."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodDocumentoReferencia = '".$CodDocumentoReferencia."',
					NroDocumentoReferencia = '".$NroDocumentoReferencia."',
					IngresadoPor = '".$IngresadoPor."',
					RecibidoPor = '".$RecibidoPor."',
					EjecutadoPor = '".$IngresadoPor."',
					FechaEjecucion = NOW(),
					Comentarios = '".$Comentarios."',
					FlagManual = '".$FlagManual."',
					FlagPendiente = '".$FlagPendiente."',
					ReferenciaAnio = '".$Anio."',
					ReferenciaNroDocumento = '".$ReferenciaNroDocumento."',
					DocumentoReferencia = '".$DocumentoReferencia."',
					DocumentoReferenciaInterno = '".$DocumentoReferenciaInterno."',
					CodDependencia = '".$CodDependencia."',
					Anio = '".$AnioDocumento."',
					Estado = 'CO',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CodItem, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadPedida, $_CantidadPendiente, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaNroInterno, $_ReferenciaSecuencia) = split(";char:td;", $linea);
			##	inserto detalle
			$sql = "INSERT INTO lg_transacciondetalle
					SET
						CodOrganismo = '".$CodOrganismo."',
						CodDocumento = '".$CodDocumento."',
						NroDocumento = '".$NroDocumento."',
						Secuencia = '".$_Secuencia."',
						CodItem = '".$_CodItem."',
						Descripcion = '".$_Descripcion."',
						CodUnidad = '".$_CodUnidad."',
						CantidadPedida = '".$_CantidadPedida."',
						CantidadRecibida = '".$_CantidadRecibida."',
						PrecioUnit = '".$_PrecioUnit."',
						Total = '".$_Total."',
						ReferenciaAnio = '".$Anio."',
						ReferenciaCodDocumento = '".$_ReferenciaCodDocumento."',
						ReferenciaNroDocumento = '".$_ReferenciaNroDocumento."',
						ReferenciaNroInterno = '".$_ReferenciaNroInterno."',
						ReferenciaSecuencia = '".$_ReferenciaSecuencia."',
						CodCentroCosto = '".$_CodCentroCosto."',
						Estado = 'CO',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo requerimientos
			##	si se scompleto el despacho
			if ($_CantidadPendiente == $_CantidadRecibida) {
				##	completo detalle del requerimiento
				$sql = "UPDATE lg_requerimientosdet
						SET Estado = 'CO'
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Secuencia = '".$_ReferenciaSecuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				##	si se completaron los detalles
				$sql = "SELECT *
						FROM lg_requerimientosdet
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Estado = 'PE'";
				$query_pendientes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_pendientes) == 0) {
					##	completo requerimiento
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CO'
							WHERE CodRequerimiento = '".$CodRequerimiento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
			##	actualizo cantidad pendiente
			$sql = "UPDATE lg_requerimientosdet
					SET CantidadRecibida = (CantidadRecibida + ".floatval($_CantidadRecibida).")
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Secuencia = '".$_ReferenciaSecuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		echo "|$NroDocumento|$NroInterno";
		mysql_query("COMMIT");
	}
	
	//	recepcion
	elseif ($accion == "recepcion") {
		mysql_query("BEGIN");
		//-------------------
		//	errores
		##	periodo
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		##	documento
		$sql = "SELECT *
				FROM ap_documentos
				WHERE
					Anio = '".$Anio."' AND
					CodProveedor = '".$CodProveedor."' AND
					DocumentoClasificacion = '".$CodTransaccion."' AND
					DocumentoReferencia = '".$DocumentoReferencia."'";
		$query_documento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_documento) != 0) die("<strong>Doc. Ref / G. Remisión</strong> ya se encuentra registrado");
		
		//	inserto transaccion
		##	genero el nuevo codigo
		$NroDocumento = getCodigo_3("lg_transaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		$NroInterno = getCodigo("lg_transaccion", "NroInterno", 6, "Anio", $AnioDocumento, "CodOrganismo", $CodOrganismo, "CodDocumento", $CodDocumento);
		##	inserto
		$sql = "INSERT INTO lg_transaccion
				SET
					CodOrganismo = '".$CodOrganismo."',
					CodDocumento = '".$CodDocumento."',
					NroDocumento = '".$NroDocumento."',
					NroInterno = '".$NroInterno."',
					CodTransaccion = '".$CodTransaccion."',
					FechaDocumento = '".$FechaDocumento."',
					Periodo = '".$Periodo."',
					CodAlmacen = '".$CodAlmacen."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodDocumentoReferencia = '".$CodDocumentoReferencia."',
					NroDocumentoReferencia = '".$NroDocumentoReferencia."',
					IngresadoPor = '".$IngresadoPor."',
					RecibidoPor = '".$RecibidoPor."',
					EjecutadoPor = '".$IngresadoPor."',
					FechaEjecucion = NOW(),
					Comentarios = '".$Comentarios."',
					FlagManual = '".$FlagManual."',
					FlagPendiente = '".$FlagPendiente."',
					ReferenciaAnio = '".$Anio."',
					ReferenciaNroDocumento = '".$ReferenciaNroDocumento."',
					DocumentoReferencia = '".$DocumentoReferencia."',
					DocumentoReferenciaInterno = '".$DocumentoReferenciaInterno."',
					CodDependencia = '".$CodDependencia."',
					Anio = '".$AnioDocumento."',
					Estado = 'CO',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto documento
		$sql = "INSERT INTO ap_documentos
				SET 
					Anio = '".$Anio."',
					CodOrganismo = '".$CodOrganismo."',
					CodProveedor = '".$CodProveedor."',
					DocumentoClasificacion = '".$CodTransaccion."',
					DocumentoReferencia = '".$DocumentoReferencia."',
					Fecha = NOW(),
					ReferenciaTipoDocumento = 'OC',
					ReferenciaNroDocumento = '".$ReferenciaNroDocumento."',
					Estado = '".$Estado."',
					TransaccionTipoDocumento = '".$CodDocumento."',
					TransaccionNroDocumento = '".$NroDocumento."',
					Comentarios = '".$Comentarios."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CodItem, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadPedida, $_CantidadPendiente, $_CantidadRecibida, $_FlagExonerado, $_PrecioUnit, $_Total, $_CodUnidadCompra, $_CantidadPedidaCompra, $_CantidadCompra, $_PrecioUnitCompra, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaNroInterno, $_ReferenciaSecuencia) = split(";char:td;", $linea);
			$_Cantidad = $_CantidadCompra / $_CantidadPedida * $_CantidadRecibida;
			##	inserto detalle
			$sql = "INSERT INTO lg_transacciondetalle
					SET
						CodOrganismo = '".$CodOrganismo."',
						CodDocumento = '".$CodDocumento."',
						NroDocumento = '".$NroDocumento."',
						Secuencia = '".$_Secuencia."',
						CodItem = '".$_CodItem."',
						Descripcion = '".$_Descripcion."',
						CodUnidad = '".$_CodUnidad."',
						CantidadPedida = '".$_CantidadPedida."',
						CantidadRecibida = '".$_CantidadRecibida."',
						PrecioUnit = '".$_PrecioUnit."',
						Total = '".$_Total."',
						ReferenciaAnio = '".$Anio."',
						ReferenciaCodDocumento = '".$_ReferenciaCodDocumento."',
						ReferenciaNroDocumento = '".$_ReferenciaNroDocumento."',
						ReferenciaNroInterno = '".$_ReferenciaNroInterno."',
						ReferenciaSecuencia = '".$_ReferenciaSecuencia."',
						CodCentroCosto = '".$_CodCentroCosto."',
						CodUnidadCompra = '".$_CodUnidadCompra."',
						CantidadCompra = '".$_CantidadCompra."',
						PrecioUnitCompra = '".$_PrecioUnitCompra."',
						Estado = 'CO',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	
			$_PrecioCantidad = $_CantidadCompra * $_PrecioUnitCompra;
			if ($_FlagExonerado == "S") {
				$MontoNoAfecto += $_PrecioCantidad;
			} else {
				$MontoAfecto += $_PrecioCantidad;
			}
			
			##	inserto documento detalle
			$sql = "INSERT INTO ap_documentosdetalle
					SET
						Anio = '".$Anio."',
						CodProveedor = '".$CodProveedor."',
						DocumentoClasificacion = '".$CodTransaccion."',
						DocumentoReferencia = '".$DocumentoReferencia."',
						Secuencia = '".$_Secuencia."',
						ReferenciaSecuencia = '".$_ReferenciaSecuencia."',
						CodItem = '".$_CodItem."',
						Descripcion = '".$_Descripcion."',
						Cantidad = '".$_CantidadCompra."',
						PrecioUnit = '".$_PrecioUnitCompra."',
						PrecioCantidad = '".$_PrecioCantidad."',
						Total = '".$_Total."',
						CodCentroCosto = '".$_CodCentroCosto."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo orden
			##	si se scompleto el despacho
			if ($_CantidadPendiente == $_CantidadRecibida) {
				##	completo detalle de la orden
				$sql = "UPDATE lg_ordencompradetalle
						SET Estado = 'CO'
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$NroOrden."' AND
							Secuencia = '".$_ReferenciaSecuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				##	si se completaron los detalles
				$sql = "SELECT *
						FROM lg_ordencompradetalle
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$NroOrden."' AND
							Estado = 'PE'";
				$query_pendientes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_pendientes) == 0) {
					##	completo requerimiento
					$sql = "UPDATE lg_ordencompra
							SET Estado = 'CO'
							WHERE
								Anio = '".$Anio."' AND
								CodOrganismo = '".$CodOrganismo."' AND
								NroOrden = '".$NroOrden."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
			##	actualizo cantidad pendiente
			$sql = "UPDATE lg_ordencompradetalle
					SET CantidadRecibida = (CantidadRecibida + ".floatval($_CantidadRecibida).")
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."' AND
						Secuencia = '".$_ReferenciaSecuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}	
		##	consulto los montos de la orden de compra
		$sql = "SELECT
					MontoAfecto,
					MontoNoAfecto,
					MontoIGV
				FROM lg_ordencompra
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$ReferenciaNroDocumento."'";
		$query_afecto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_afecto) != 0) $field_afecto = mysql_fetch_array($query_afecto);
		
		##	actualizo montos del documento
		if ($MontoAfecto != $field_afecto['MontoAfecto']) {
			$MontoImpuestos = round(($MontoAfecto * $field_afecto['MontoIGV'] / $field_afecto['MontoAfecto']), 2);
		} else {
			$MontoAfecto = $field_afecto['MontoAfecto'];
			$MontoNoAfecto = $field_afecto['MontoNoAfecto'];
			$MontoImpuestos = $field_afecto['MontoIGV'];
		}
		$MontoTotal = $MontoAfecto + $MontoNoAfecto + $MontoImpuestos;
		
		##	actualizo montos del documento
		$sql = "UPDATE ap_documentos
				SET
					MontoAfecto = '".$MontoAfecto."',
					MontoNoAfecto = '".$MontoNoAfecto."',
					MontoImpuestos = '".$MontoImpuestos."',
					MontoTotal = '".$MontoTotal."',
					MontoPendiente = '".$MontoTotal."'
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					DocumentoClasificacion = '".$CodTransaccion."' AND
					DocumentoReferencia = '".$DocumentoReferencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		echo "|$NroDocumento|$NroInterno";
		//-------------------
		mysql_query("COMMIT");
	}
	
	//	pasar requerimiento para compras
	elseif ($accion == "dirigir-compras") {
		mysql_query("BEGIN");
		$sql = "UPDATE lg_requerimientosdet
				SET FlagCompraAlmacen = 'C'
				WHERE
					CodRequerimiento = '".$registro."' AND
					Estado = 'PE' AND
					FlagCompraAlmacen = 'A'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		mysql_query("COMMIT");
	}
	
	//	pasar linea para compras
	elseif ($accion == "dirigir-compras-detalle") {
		mysql_query("BEGIN");
		$detalle = split(";char:tr;", $registro);
		foreach ($detalle as $linea) {
			list($CodRequerimiento, $Secuencia) = split("[.]", $linea);
			$sql = "UPDATE lg_requerimientosdet
					SET FlagCompraAlmacen = 'X'
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Secuencia = '".$Secuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		mysql_query("COMMIT");
	}
	
	//	cerrar linea
	elseif ($accion == "cerrar-detalle") {
		mysql_query("BEGIN");
		$detalle = split(";char:tr;", $registro);
		foreach ($detalle as $linea) {
			list($CodRequerimiento, $Secuencia) = split("[.]", $linea);			
			//	modifico detalles
			$sql = "UPDATE lg_requerimientosdet
					SET
						Estado = 'CE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Secuencia = '".$Secuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			##
			//	consulto si no quedan pendientes en el requerimiento
			$sql = "SELECT Estado
					FROM lg_requerimientosdet
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Estado = 'PE'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query) == 0) {
				//	consulto si se completaron algunas lineas en el requerimiento
				$sql = "SELECT Estado
						FROM lg_requerimientosdet
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Estado = 'CO'";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query) != 0) {
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CO'
							WHERE CodRequerimiento = '".$CodRequerimiento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CE'
							WHERE CodRequerimiento = '".$CodRequerimiento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		mysql_query("COMMIT");
	}
	
	//	cerrar linea
	elseif ($accion == "cerrar-detalle-compras") {
		mysql_query("BEGIN");
		$detalle = split(";char:tr;", $registro);
		foreach ($detalle as $linea) {
			list($Anio, $CodOrganismo, $NroOrden, $Secuencia) = split("[.]", $linea);
			//	verifico los detalles
			$sql = "SELECT Estado
					FROM lg_ordencompradetalle
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."' AND
						Secuencia = '".$Secuencia."' AND
						Estado = 'PE'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query) == 0) die("Solo se pueden cerrar lineas en Estado <strong>Pendiente</strong>");
			##
			//	modifico detalles
			$sql = "UPDATE lg_ordencompradetalle
					SET
						Estado = 'CE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."' AND
						Secuencia = '".$Secuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			##
			//	consulto si no quedan pendientes en el requerimiento
			$sql = "SELECT Estado
					FROM lg_ordencompradetalle
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."' AND
						Estado = 'PE'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query) == 0) {
				//	consulto si se completaron algunas lineas en el requerimiento
				$sql = "SELECT Estado
						FROM lg_ordencompradetalle
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$NroOrden."' AND
							Estado = 'CO'";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query) != 0) {
					$sql = "UPDATE lg_ordencompra
							SET Estado = 'CO'
							WHERE
								Anio = '".$Anio."' AND
								CodOrganismo = '".$CodOrganismo."' AND
								NroOrden = '".$NroOrden."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					$sql = "UPDATE lg_ordencompra
							SET Estado = 'CE'
							WHERE
								Anio = '".$Anio."' AND
								CodOrganismo = '".$CodOrganismo."' AND
								NroOrden = '".$NroOrden."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		mysql_query("COMMIT");
	}
	
	//	cerrar linea
	elseif ($accion == "cerrar-detalle-requerimiento") {
		mysql_query("BEGIN");
		$detalle = split(";char:tr;", $registro);
		foreach ($detalle as $linea) {
			list($CodRequerimiento, $Secuencia) = split("[.]", $registro);
			//	verifico los detalles
			$sql = "SELECT Estado
					FROM lg_requerimientosdet
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Secuencia = '".$Secuencia."' AND
						Estado = 'PE'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query) == 0) die("Solo se pueden cerrar lineas en Estado <strong>Pendiente</strong>");
			##
			//	modifico detalles
			$sql = "UPDATE lg_requerimientosdet
					SET
						Estado = 'CE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Secuencia = '".$Secuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			##
			//	consulto si no quedan pendientes en el requerimiento
			$sql = "SELECT Estado
					FROM lg_requerimientosdet
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Estado = 'PE'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query) == 0) {
				//	consulto si se completaron algunas lineas en el requerimiento
				$sql = "SELECT Estado
						FROM lg_requerimientosdet
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Estado = 'CO'";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query) != 0) {
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CO'
							WHERE CodRequerimiento = '".$CodRequerimiento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CE'
							WHERE CodRequerimiento = '".$CodRequerimiento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		mysql_query("COMMIT");
	}
}

//	transaccion (almacen)
elseif ($modulo == "transaccion_almacen") {
	$Comentarios = changeUrl($Comentarios);
	$FechaDocumento = formatFechaAMD($FechaDocumento);
	$AnioDocumento = substr($FechaDocumento, 0, 4);
	
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	periodo
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	inserto transaccion
		##	genero el nuevo codigo
		$NroDocumento = getCodigo_3("lg_transaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		##	inserto
		$sql = "INSERT INTO lg_transaccion
				SET
					CodOrganismo = '".$CodOrganismo."',
					CodDocumento = '".$CodDocumento."',
					NroDocumento = '".$NroDocumento."',
					CodTransaccion = '".$CodTransaccion."',
					FechaDocumento = '".$FechaDocumento."',
					Periodo = '".$Periodo."',
					CodAlmacen = '".$CodAlmacen."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodDocumentoReferencia = '".$CodDocumentoReferencia."',
					NroDocumentoReferencia = '".$NroDocumentoReferencia."',
					IngresadoPor = '".$IngresadoPor."',
					RecibidoPor = '".$RecibidoPor."',
					Comentarios = '".$Comentarios."',
					FlagManual = '".$FlagManual."',
					FlagPendiente = '".$FlagPendiente."',
					ReferenciaAnio = '".$AnioDocumento."',
					ReferenciaNroDocumento = '".$ReferenciaNroDocumento."',
					DocumentoReferencia = '".$DocumentoReferencia."',
					DocumentoReferenciaInterno = '".$DocumentoReferenciaInterno."',
					CodDependencia = '".$CodDependencia."',
					Anio = '".$AnioDocumento."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CodItem, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia) = split(";char:td;", $linea);
			##	inserto detalle
			$sql = "INSERT INTO lg_transacciondetalle
					SET
						CodOrganismo = '".$CodOrganismo."',
						CodDocumento = '".$CodDocumento."',
						NroDocumento = '".$NroDocumento."',
						Secuencia = '".$_Secuencia."',
						CodItem = '".$_CodItem."',
						Descripcion = '".$_Descripcion."',
						CodUnidad = '".$_CodUnidad."',
						CantidadPedida = '".$_CantidadPedida."',
						CantidadRecibida = '".$_CantidadRecibida."',
						PrecioUnit = '".$_PrecioUnit."',
						Total = '".$_Total."',
						ReferenciaAnio = '".$AnioDocumento."',
						ReferenciaCodDocumento = '".$_ReferenciaCodDocumento."',
						ReferenciaNroDocumento = '".$_ReferenciaNroDocumento."',
						ReferenciaSecuencia = '".$_ReferenciaSecuencia."',
						CodCentroCosto = '".$_CodCentroCosto."',
						Estado = '".$Estado."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	anulo la transaccion reversada
		$sql = "UPDATE lg_transaccion
				SET Estado = 'AN'
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumentoReversa."' AND
					NroDocumento = '".$NroDocumentoReversa."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		$sql = "UPDATE lg_transacciondetalle
				SET Estado = 'AN'
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumentoReversa."' AND
					NroDocumento = '".$NroDocumentoReversa."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	periodo
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	modifico transaccion
		$sql = "UPDATE lg_transaccion
				SET
					FechaDocumento = '".$FechaDocumento."',
					Periodo = '".$Periodo."',
					CodAlmacen = '".$CodAlmacen."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodDocumentoReferencia = '".$CodDocumentoReferencia."',
					NroDocumentoReferencia = '".$NroDocumentoReferencia."',
					IngresadoPor = '".$IngresadoPor."',
					RecibidoPor = '".$RecibidoPor."',
					Comentarios = '".$Comentarios."',
					FlagManual = '".$FlagManual."',
					FlagPendiente = '".$FlagPendiente."',
					ReferenciaAnio = '".$AnioDocumento."',
					ReferenciaNroDocumento = '".$ReferenciaNroDocumento."',
					DocumentoReferencia = '".$DocumentoReferencia."',
					DocumentoReferenciaInterno = '".$DocumentoReferenciaInterno."',
					CodDependencia = '".$CodDependencia."',
					Anio = '".$AnioDocumento."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$sql = "DELETE FROM lg_transacciondetalle
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CodItem, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia) = split(";char:td;", $linea);
			##	inserto detalle
			$sql = "INSERT INTO lg_transacciondetalle
					SET
						CodOrganismo = '".$CodOrganismo."',
						CodDocumento = '".$CodDocumento."',
						NroDocumento = '".$NroDocumento."',
						Secuencia = '".$_Secuencia."',
						CodItem = '".$_CodItem."',
						Descripcion = '".$_Descripcion."',
						CodUnidad = '".$_CodUnidad."',
						CantidadPedida = '".$_CantidadPedida."',
						CantidadRecibida = '".$_CantidadRecibida."',
						PrecioUnit = '".$_PrecioUnit."',
						Total = '".$_Total."',
						ReferenciaAnio = '".$AnioDocumento."',
						ReferenciaCodDocumento = '".$_ReferenciaCodDocumento."',
						ReferenciaNroDocumento = '".$_ReferenciaNroDocumento."',
						ReferenciaSecuencia = '".$_ReferenciaSecuencia."',
						CodCentroCosto = '".$_CodCentroCosto."',
						Estado = '".$Estado."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		mysql_query("COMMIT");
	}
	
	//	ejecutar
	elseif ($accion == "ejecutar") {
		mysql_query("BEGIN");
		##	genero el nuevo codigo
		$NroInterno = getCodigo("lg_transaccion", "NroInterno", 6, "Anio", $AnioDocumento, "CodOrganismo", $CodOrganismo, "CodDocumento", $CodDocumento);
		//	
		$sql = "UPDATE lg_transaccion
				SET
					NroInterno = '".$NroInterno."',
					Estado = 'CO',
					EjecutadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaEjecucion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	
		$sql = "UPDATE lg_transacciondetalle
				SET
					Estado = 'CO',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		echo "|$NroInterno";
		mysql_query("COMMIT");
	}
}

//	commodity
elseif ($modulo == "almacen-commodity") {
	$Comentarios = changeUrl($Comentarios);
	$FechaDocumento = formatFechaAMD($FechaDocumento);
	
	//	recepcion
	if ($accion == "recepcion") {
		mysql_query("BEGIN");
		//-------------------
		//	errores
		##	periodo
		$AnioDocumento = substr($FechaDocumento, 0, 4);
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		##	documento
		$sql = "SELECT Estado
				FROM ap_documentos
				WHERE
					Anio = '".$Anio."' AND
					CodProveedor = '".$CodProveedor."' AND
					DocumentoClasificacion = '".$CodTransaccion."' AND
					DocumentoReferencia = '".$DocumentoReferencia."'";
		$query_documento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_documento) != 0) die("<strong>Doc. Ref / G. Remisión</strong> ya se encuentra registrado");
		##	activos
		if ($FlagActivoFijo == "S") {
			$activo = split(";char:tr;", $activos);
			foreach ($activo as $linea) {
				list($_Secuencia, $_NroSecuencia, $_CommoditySub, $_Descripcion, $_CodClasificacion, $_Monto, $_NroSerie, $_FechaIngreso, $_Modelo, $_CodBarra, $_CodUbicacion, $_CodCentroCosto, $_NroPlaca, $_CodMarca, $_Color) = split(";char:td;", $linea);
				//	consulto
				$sql = "SELECT Estado
						FROM lg_activofijo
						WHERE
							CodOrganismo = '".$CodOrganismo."' AND
							Anio = '".$Anio."' AND
							NroOrden = '".$ReferenciaNroDocumento."' AND
							Secuencia = '".$_Secuencia."' AND
							NroSecuencia = '".$_NroSecuencia."'";
				$query_activo = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_activo) != 0) die("Se encontraron lineas en la ficha de <strong>Activos Asociados</strong> ya ingresados");
			}
		}
		
		//	consulto orden
		$sql = "SELECT
					NroInterno,
					FechaOrden
				FROM lg_ordencompra
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$ReferenciaNroDocumento."'";
		$query_oc = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_oc) != 0) $field_oc = mysql_fetch_array($query_oc);
		
		//	inserto transaccion
		##	genero el nuevo codigo
		$NroDocumento = getCodigo_3("lg_commoditytransaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		$NroInterno = getCodigo("lg_commoditytransaccion", "NroInterno", 6, "Anio", $AnioDocumento, "CodOrganismo", $CodOrganismo, "CodDocumento", $CodDocumento);
		##	inserto
		$sql = "INSERT INTO lg_commoditytransaccion
				SET
					CodOrganismo = '".$CodOrganismo."',
					CodDocumento = '".$CodDocumento."',
					NroDocumento = '".$NroDocumento."',
					NroInterno = '".$NroInterno."',
					CodTransaccion = '".$CodTransaccion."',
					FechaDocumento = '".$FechaDocumento."',
					Periodo = '".$Periodo."',
					CodAlmacen = '".$CodAlmacen."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodDocumentoReferencia = '".$CodDocumentoReferencia."',
					NroDocumentoReferencia = '".$NroDocumentoReferencia."',
					IngresadoPor = '".$IngresadoPor."',
					RecibidoPor = '".$RecibidoPor."',
					EjecutadoPor = '".$EjecutadoPor."',
					FechaEjecucion = NOW(),
					Comentarios = '".$Comentarios."',
					ReferenciaAnio = '".$Anio."',
					ReferenciaNroDocumento = '".$ReferenciaNroDocumento."',
					DocumentoReferencia = '".$DocumentoReferencia."',
					DocumentoReferenciaInterno = '".$DocumentoReferenciaInterno."',
					CodUbicacion = '".$CodUbicacion."',
					FlagActivoFijo = '".$FlagActivoFijo."',
					CodDependencia = '".$CodDependencia."',
					Anio = '".$AnioDocumento."',
					FlagManual = '".$FlagManual."',
					FlagPendiente = '".$FlagPendiente."',
					Estado = 'CO',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto documento
		$sql = "INSERT INTO ap_documentos
				SET 
					Anio = '".$Anio."',
					CodOrganismo = '".$CodOrganismo."',
					CodProveedor = '".$CodProveedor."',
					DocumentoClasificacion = '".$CodTransaccion."',
					DocumentoReferencia = '".$DocumentoReferencia."',
					Fecha = NOW(),
					ReferenciaTipoDocumento = 'OC',
					ReferenciaNroDocumento = '".$ReferenciaNroDocumento."',
					Estado = '".$Estado."',
					TransaccionTipoDocumento = '".$CodDocumento."',
					TransaccionNroDocumento = '".$NroDocumento."',
					Comentarios = '".$Comentarios."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CommoditySub, $_Descripcion, $_CodUnidad, $_CantidadPedida, $_CantidadPendiente, $_CantidadRecibida, $_FlagExonerado, $_PrecioUnit, $_Total, $_CodUnidadCompra, $_CantidadPedidaCompra, $_CantidadCompra, $_PrecioUnitCompra, $_CodClasificacion, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaNroInterno, $_ReferenciaSecuencia) = split(";char:td;", $linea);
			$_Descripcion = changeUrl($_Descripcion);
			
			##	
			$_PrecioCantidad = $_CantidadCompra * $_PrecioUnitCompra;
			if ($_FlagExonerado == "S") {
				$MontoNoAfecto += $_PrecioCantidad;
			} else {
				$MontoAfecto += $_PrecioCantidad;
			}
			
			##	inserto detalle
			$sql = "INSERT INTO lg_commoditytransacciondetalle
					SET
						CodOrganismo = '".$CodOrganismo."',
						CodDocumento = '".$CodDocumento."',
						NroDocumento = '".$NroDocumento."',
						Secuencia = '".$_Secuencia."',
						CommoditySub = '".$_CommoditySub."',
						Descripcion = '".$_Descripcion."',
						CodUnidad = '".$_CodUnidad."',
						CantidadKardex = '".$_CantidadRecibida."',
						Cantidad = '".$_CantidadRecibida."',
						PrecioUnit = '".$_PrecioUnit."',
						Total = '".$_Total."',
						ReferenciaAnio = '".$Anio."',
						ReferenciaCodDocumento = '".$_ReferenciaCodDocumento."',
						ReferenciaNroDocumento = '".$_ReferenciaNroDocumento."',
						ReferenciaNroInterno = '".$_ReferenciaNroInterno."',
						ReferenciaSecuencia = '".$_ReferenciaSecuencia."',
						CodAlmacen = '".$CodAlmacen."',
						CodCentroCosto = '".$_CodCentroCosto."',
						Anio = '".$AnioDocumento."',
						CodUnidadCompra = '".$_CodUnidadCompra."',
						CantidadCompra = '".$_CantidadCompra."',
						PrecioUnitCompra = '".$_PrecioUnitCompra."',
						Estado = 'CO',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	inserto documento detalle
			$sql = "INSERT INTO ap_documentosdetalle
					SET
						Anio = '".$Anio."',
						CodProveedor = '".$CodProveedor."',
						DocumentoClasificacion = '".$CodTransaccion."',
						DocumentoReferencia = '".$DocumentoReferencia."',
						Secuencia = '".$_Secuencia."',
						ReferenciaSecuencia = '".$_ReferenciaSecuencia."',
						CommoditySub = '".$_CommoditySub."',
						Descripcion = '".$_Descripcion."',
						Cantidad = '".$_CantidadRecibida."',
						PrecioUnit = '".$_PrecioUnit."',
						PrecioCantidad = '".$_PrecioCantidad."',
						Total = '".$_Total."',
						CodCentroCosto = '".$_CodCentroCosto."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if ($_FlagExonerado == "S") $_SumMontoAfecto += $_PrecioCantidad;
			else $_SumMontoNoAfecto += $_PrecioCantidad;
			$_SumMontoTotal += $_Total;
			$_SumMontoImpuestos += ($_Total - $_PrecioCantidad);
			
			//	actualizo orden
			##	si se scompleto el despacho
			if ($_CantidadPendiente == $_CantidadRecibida) {
				##	completo detalle de la orden
				$sql = "UPDATE lg_ordencompradetalle
						SET Estado = 'CO'
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$NroOrden."' AND
							Secuencia = '".$_ReferenciaSecuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				##	si se completaron los detalles
				$sql = "SELECT *
						FROM lg_ordencompradetalle
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$NroOrden."' AND
							Estado = 'PE'";
				$query_pendientes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_pendientes) == 0) {
					##	completo orden
					$sql = "UPDATE lg_ordencompra
							SET Estado = 'CO'
							WHERE
								Anio = '".$Anio."' AND
								CodOrganismo = '".$CodOrganismo."' AND
								NroOrden = '".$NroOrden."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
			##	actualizo cantidad pendiente
			$sql = "UPDATE lg_ordencompradetalle
					SET CantidadRecibida = (CantidadRecibida + ".floatval($_CantidadRecibida).")
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."' AND
						Secuencia = '".$_ReferenciaSecuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
			
		##	consulto los montos de la orden de compra
		$sql = "SELECT
					MontoAfecto,
					MontoNoAfecto,
					MontoIGV
				FROM lg_ordencompra
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$ReferenciaNroDocumento."'";
		$query_afecto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_afecto) != 0) $field_afecto = mysql_fetch_array($query_afecto);
		
		##	actualizo montos del documento
		if ($MontoAfecto != $field_afecto['MontoAfecto']) {
			$MontoImpuestos = round(($MontoAfecto * $field_afecto['MontoIGV'] / $field_afecto['MontoAfecto']), 2);
		} else {
			$MontoAfecto = $field_afecto['MontoAfecto'];
			$MontoNoAfecto = $field_afecto['MontoNoAfecto'];
			$MontoImpuestos = $field_afecto['MontoIGV'];
		}
		$MontoTotal = $MontoAfecto + $MontoNoAfecto + $MontoImpuestos;
		
		##	actualizo montos del documento
		$sql = "UPDATE ap_documentos
				SET
					MontoAfecto = '".$MontoAfecto."',
					MontoNoAfecto = '".$MontoNoAfecto."',
					MontoImpuestos = '".$MontoImpuestos."',
					MontoTotal = '".$MontoTotal."',
					MontoPendiente = '".$MontoTotal."'
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					DocumentoClasificacion = '".$CodTransaccion."' AND
					DocumentoReferencia = '".$DocumentoReferencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	si es una transaccion de activos fijoss
		if ($FlagActivoFijo == "S") {
			//	inserto activos
			$activo = split(";char:tr;", $activos);
			foreach ($activo as $linea) {
				list($_Secuencia, $_NroSecuencia, $_CommoditySub, $_Descripcion, $_CodClasificacion, $_Monto, $_NroSerie, $_FechaIngreso, $_Modelo, $_CodBarra, $_CodUbicacion, $_CodCentroCosto, $_NroPlaca, $_CodMarca, $_Color) = split(";char:td;", $linea);
				$_Descripcion = changeUrl($_Descripcion);
				
				##	inserto activo				
				$sql = "INSERT INTO lg_activofijo
						SET
							CodOrganismo = '".$CodOrganismo."',
							Anio = '".$Anio."',
							NroOrden = '".$ReferenciaNroDocumento."',
							NroInterno = '".$field_oc['NroInterno']."',
							Secuencia = '".$_Secuencia."',
							NroSecuencia = '".$_NroSecuencia."',
							CommoditySub = '".$_CommoditySub."',
							Descripcion = '".$_Descripcion."',
							CodCentroCosto = '".$_CodCentroCosto."',
							CodClasificacion = '".$_CodClasificacion."',
							CodBarra = '".$_CodBarra."',
							NroSerie = '".$_NroSerie."',
							Modelo = '".$_Modelo."',
							CodProveedor = '".$CodProveedor."',
							CodDocumento = '".$CodDocumento."',
							NroDocumento = '".$NroDocumento."',
							Monto = '".$_Monto."',
							CodUbicacion = '".$_CodUbicacion."',
							FechaIngreso = '".formatFechaAMD($_FechaIngreso)."',
							FlagFacturado = 'N',
							CodMarca = '".$_CodMarca."',
							Color = '".$_Color."',
							NroPlaca = '".$_NroPlaca."',
							NumeroOrdenFecha = '".$field_oc['FechaOrden']."',
							Estado = 'PE',
							Clasificacion = '".$Clasificacion."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		echo "|$NroDocumento|$NroInterno";
		//-------------------
		mysql_query("COMMIT");
	}
	
	//	despacho
	elseif ($accion == "despacho") {
		mysql_query("BEGIN");
		//	errores
		##	periodo
		$AnioDocumento = substr($FechaDocumento, 0, 4);
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	inserto transaccion
		##	genero el nuevo codigo
		$NroDocumento = getCodigo_3("lg_commoditytransaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		$NroInterno = getCodigo("lg_commoditytransaccion", "NroInterno", 6, "Anio", $AnioDocumento, "CodOrganismo", $CodOrganismo, "CodDocumento", $CodDocumento);
		##	inserto
		$sql = "INSERT INTO lg_commoditytransaccion
				SET
					CodOrganismo = '".$CodOrganismo."',
					CodDocumento = '".$CodDocumento."',
					NroDocumento = '".$NroDocumento."',
					NroInterno = '".$NroInterno."',
					CodTransaccion = '".$CodTransaccion."',
					FechaDocumento = '".$FechaDocumento."',
					Periodo = '".$Periodo."',
					CodAlmacen = '".$CodAlmacen."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodDocumentoReferencia = '".$CodDocumentoReferencia."',
					NroDocumentoReferencia = '".$NroDocumentoReferencia."',
					IngresadoPor = '".$IngresadoPor."',
					RecibidoPor = '".$RecibidoPor."',
					EjecutadoPor = '".$EjecutadoPor."',
					FechaEjecucion = NOW(),
					Comentarios = '".$Comentarios."',
					ReferenciaAnio = '".$Anio."',
					ReferenciaNroDocumento = '".$ReferenciaNroDocumento."',
					DocumentoReferencia = '".$DocumentoReferencia."',
					DocumentoReferenciaInterno = '".$DocumentoReferenciaInterno."',
					CodUbicacion = '".$CodUbicacion."',
					FlagActivoFijo = '".$FlagActivoFijo."',
					CodDependencia = '".$CodDependencia."',
					Anio = '".$AnioDocumento."',
					FlagManual = '".$FlagManual."',
					FlagPendiente = '".$FlagPendiente."',
					Estado = 'CO',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CommoditySub, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadPedida, $_CantidadPendiente, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaNroInterno, $_ReferenciaSecuencia, $_CodRequerimiento) = split(";char:td;", $linea);
			$_Descripcion = changeUrl($_Descripcion);
			$_PrecioCantidad = $_CantidadRecibida * $_PrecioUnit;
			
			##	inserto detalle
			$sql = "INSERT INTO lg_commoditytransacciondetalle
					SET
						CodOrganismo = '".$CodOrganismo."',
						CodDocumento = '".$CodDocumento."',
						NroDocumento = '".$NroDocumento."',
						Secuencia = '".$_Secuencia."',
						CommoditySub = '".$_CommoditySub."',
						Descripcion = '".$_Descripcion."',
						CodUnidad = '".$_CodUnidad."',
						CantidadKardex = '".$_CantidadRecibida."',
						Cantidad = '".$_CantidadRecibida."',
						PrecioUnit = '".$_PrecioUnit."',
						Total = '".$_Total."',
						ReferenciaAnio = '".$Anio."',
						ReferenciaCodDocumento = '".$_ReferenciaCodDocumento."',
						ReferenciaNroDocumento = '".$_ReferenciaNroDocumento."',
						ReferenciaNroInterno = '".$_ReferenciaNroInterno."',
						ReferenciaSecuencia = '".$_ReferenciaSecuencia."',
						CodAlmacen = '".$CodAlmacen."',
						CodCentroCosto = '".$_CodCentroCosto."',
						Anio = '".$AnioDocumento."',
						Estado = 'CO',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo requerimiento detalle
			$sql = "UPDATE lg_requerimientosdet
					SET CantidadRecibida = CantidadRecibida + '".$_CantidadRecibida."'
					WHERE
						CodRequerimiento = '".$_CodRequerimiento."' AND
						Secuencia = '".$_ReferenciaSecuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		echo "|$NroDocumento|$NroInterno";
		mysql_query("COMMIT");
	}
}

//	transaccion (commodity)
elseif ($modulo == "transaccion_commodity") {
	$Comentarios = changeUrl($Comentarios);
	$FechaDocumento = formatFechaAMD($FechaDocumento);
	$AnioDocumento = substr($FechaDocumento, 0, 4);
	
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	periodo
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	inserto transaccion
		##	genero el nuevo codigo
		$NroDocumento = getCodigo_3("lg_commoditytransaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		##	inserto
		$sql = "INSERT INTO lg_commoditytransaccion
				SET
					CodOrganismo = '".$CodOrganismo."',
					CodDocumento = '".$CodDocumento."',
					NroDocumento = '".$NroDocumento."',
					CodTransaccion = '".$CodTransaccion."',
					FechaDocumento = '".$FechaDocumento."',
					Periodo = '".$Periodo."',
					CodAlmacen = '".$CodAlmacen."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodDocumentoReferencia = '".$CodDocumentoReferencia."',
					NroDocumentoReferencia = '".$NroDocumentoReferencia."',
					IngresadoPor = '".$IngresadoPor."',
					RecibidoPor = '".$RecibidoPor."',
					Comentarios = '".$Comentarios."',
					ReferenciaAnio = '".$AnioDocumento."',
					ReferenciaNroDocumento = '".$ReferenciaNroDocumento."',
					DocumentoReferencia = '".$DocumentoReferencia."',
					DocumentoReferenciaInterno = '".$DocumentoReferenciaInterno."',
					CodUbicacion = '".$CodUbicacion."',
					FlagActivoFijo = '".$FlagActivoFijo."',
					CodDependencia = '".$CodDependencia."',
					Anio = '".$AnioDocumento."',
					FlagManual = '".$FlagManual."',
					FlagPendiente = '".$FlagPendiente."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CommoditySub, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia, $_CodRequerimiento) = split(";char:td;", $linea);
			$_Descripcion = changeUrl($_Descripcion);
			$_PrecioCantidad = $_CantidadRecibida * $_PrecioUnit;
			
			##	inserto detalle
			$sql = "INSERT INTO lg_commoditytransacciondetalle
					SET
						CodOrganismo = '".$CodOrganismo."',
						CodDocumento = '".$CodDocumento."',
						NroDocumento = '".$NroDocumento."',
						Secuencia = '".$_Secuencia."',
						CommoditySub = '".$_CommoditySub."',
						Descripcion = '".$_Descripcion."',
						CodUnidad = '".$_CodUnidad."',
						CantidadKardex = '".$_CantidadRecibida."',
						Cantidad = '".$_CantidadRecibida."',
						PrecioUnit = '".$_PrecioUnit."',
						Total = '".$_Total."',
						ReferenciaAnio = '".$AnioDocumento."',
						ReferenciaCodDocumento = '".$_ReferenciaCodDocumento."',
						ReferenciaNroDocumento = '".$_ReferenciaNroDocumento."',
						ReferenciaSecuencia = '".$_ReferenciaSecuencia."',
						CodAlmacen = '".$CodAlmacen."',
						CodCentroCosto = '".$_CodCentroCosto."',
						Anio = '".$AnioDocumento."',
						Estado = '".$Estado."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	periodo
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	modifico transaccion
		$sql = "UPDATE lg_commoditytransaccion
				SET
					FechaDocumento = '".$FechaDocumento."',
					Periodo = '".$Periodo."',
					CodAlmacen = '".$CodAlmacen."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodDocumentoReferencia = '".$CodDocumentoReferencia."',
					NroDocumentoReferencia = '".$NroDocumentoReferencia."',
					RecibidoPor = '".$RecibidoPor."',
					Comentarios = '".$Comentarios."',
					ReferenciaAnio = '".$AnioDocumento."',
					ReferenciaNroDocumento = '".$ReferenciaNroDocumento."',
					DocumentoReferencia = '".$DocumentoReferencia."',
					DocumentoReferenciaInterno = '".$DocumentoReferenciaInterno."',
					CodUbicacion = '".$CodUbicacion."',
					FlagActivoFijo = '".$FlagActivoFijo."',
					CodDependencia = '".$CodDependencia."',
					Anio = '".$AnioDocumento."',
					FlagManual = '".$FlagManual."',
					FlagPendiente = '".$FlagPendiente."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$sql = "DELETE FROM lg_commoditytransacciondetalle
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CommoditySub, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia, $_CodRequerimiento) = split(";char:td;", $linea);
			$_Descripcion = changeUrl($_Descripcion);
			$_PrecioCantidad = $_CantidadRecibida * $_PrecioUnit;
			
			##	inserto detalle
			$sql = "INSERT INTO lg_commoditytransacciondetalle
					SET
						CodOrganismo = '".$CodOrganismo."',
						CodDocumento = '".$CodDocumento."',
						NroDocumento = '".$NroDocumento."',
						Secuencia = '".$_Secuencia."',
						CommoditySub = '".$_CommoditySub."',
						Descripcion = '".$_Descripcion."',
						CodUnidad = '".$_CodUnidad."',
						CantidadKardex = '".$_CantidadRecibida."',
						Cantidad = '".$_CantidadRecibida."',
						PrecioUnit = '".$_PrecioUnit."',
						Total = '".$_Total."',
						ReferenciaAnio = '".$AnioDocumento."',
						ReferenciaCodDocumento = '".$_ReferenciaCodDocumento."',
						ReferenciaNroDocumento = '".$_ReferenciaNroDocumento."',
						ReferenciaSecuencia = '".$_ReferenciaSecuencia."',
						CodAlmacen = '".$CodAlmacen."',
						CodCentroCosto = '".$_CodCentroCosto."',
						Anio = '".$AnioDocumento."',
						Estado = '".$Estado."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		mysql_query("COMMIT");
	}
	
	//	ejecutar
	elseif ($accion == "ejecutar") {
		mysql_query("BEGIN");
		##	genero el nuevo codigo
		$NroInterno = getCodigo("lg_commoditytransaccion", "NroInterno", 6, "Anio", $AnioDocumento, "CodOrganismo", $CodOrganismo, "CodDocumento", $CodDocumento);
		
		//	
		$sql = "UPDATE lg_commoditytransaccion
				SET
					NroInterno = '".$NroInterno."',
					Estado = 'CO',
					EjecutadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaEjecucion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	
		$sql = "UPDATE lg_commoditytransacciondetalle
				SET
					Estado = 'CO',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		echo "|$CodDocumento|$NroInterno";
		mysql_query("COMMIT");
	}
}

//	transaccion (caja chica)
elseif ($modulo == "transaccion-cajachica") {
	$Comentarios = changeUrl($Comentarios);
	$FechaDocumento = formatFechaAMD($FechaDocumento);
	
	//	recepcion
	if ($accion == "recepcion") {
		mysql_query("BEGIN");
		//	errores
		##	periodo
		$Anio = substr($FechaDocumento, 0, 4);
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	consulto si es un requerimiento de tipo autoreposicion
		$sql = "SELECT Clasificacion FROM lg_requerimientos WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_rau = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_rau) != 0) $field_rau = mysql_fetch_array($query_rau);
		
		//	consulto orden
		$sql = "SELECT
					NroInterno,
					FechaOrden
				FROM lg_ordencompra
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$ReferenciaNroDocumento."'";
		$query_oc = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_oc) != 0) $field_oc = mysql_fetch_array($query_oc);
		
		//	si es dirigido a commoditys
		if ($FlagCommodity == "S") {
			##	activos
			if ($FlagActivoFijo == "S") {
				$activo = split(";char:tr;", $activos);
				foreach ($activo as $linea) {
					list($_Secuencia, $_NroSecuencia, $_CommoditySub, $_Descripcion, $_CodClasificacion, $_Monto, $_NroSerie, $_FechaIngreso, $_Modelo, $_CodBarra, $_CodUbicacion, $_CodCentroCosto, $_NroPlaca, $_CodMarca, $_Color) = split(";char:td;", $linea);
					//	consulto
					$sql = "SELECT Estado
							FROM lg_activofijo
							WHERE
								CodOrganismo = '".$CodOrganismo."' AND
								Anio = '".$Anio."' AND
								NroOrden = '".$ReferenciaNroDocumento."' AND
								Secuencia = '".$_Secuencia."' AND
								NroSecuencia = '".$_NroSecuencia."'";
					$query_activo = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					if (mysql_num_rows($query_activo) != 0) die("Se encontraron lineas en la ficha de <strong>Activos Asociados</strong> ya ingresados");
				}
			}
			
			//	inserto transaccion
			##	genero el nuevo codigo
			$NroDocumento = getCodigo_3("lg_commoditytransaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
			$NroInterno = getCodigo("lg_commoditytransaccion", "NroInterno", 6, "Anio", $Anio, "CodOrganismo", $CodOrganismo, "CodDocumento", $CodDocumento);
			##	inserto
			$sql = "INSERT INTO lg_commoditytransaccion (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								NroInterno,
								CodTransaccion,
								FechaDocumento,
								Periodo,
								CodAlmacen,
								CodCentroCosto,
								CodDocumentoReferencia,
								NroDocumentoReferencia,
								IngresadoPor,
								RecibidoPor,
								Comentarios,
								ReferenciaNroDocumento,
								DocumentoReferencia,
								DocumentoReferenciaInterno,
								CodUbicacion,
								FlagActivoFijo,
								CodDependencia,
								Anio,
								FlagManual,
								FlagPendiente,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$NroInterno."',
								'".$CodTransaccion."',
								'".$FechaDocumento."',
								'".$Periodo."',
								'".$CodAlmacen."',
								'".$CodCentroCosto."',
								'".$CodDocumentoReferencia."',
								'".$NroDocumentoReferencia."',
								'".$IngresadoPor."',
								'".$RecibidoPor."',
								'".$Comentarios."',
								'".$ReferenciaNroDocumento."',
								'".$DocumentoReferencia."',
								'".$DocumentoReferenciaInterno."',
								'".$CodUbicacion."',
								'".$FlagActivoFijo."',
								'".$CodDependencia."',
								'".$Periodo."',
								'".$FlagManual."',
								'".$FlagPendiente."',
								'CO',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		} else {
			//	inserto transaccion
			##	genero el nuevo codigo
			$NroDocumento = getCodigo_3("lg_transaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
			$NroInterno = getCodigo("lg_transaccion", "NroInterno", 6, "Anio", $Anio, "CodOrganismo", $CodOrganismo, "CodDocumento", $CodDocumento);
			##	inserto
			$sql = "INSERT INTO lg_transaccion (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								NroInterno,
								CodTransaccion,
								FechaDocumento,
								Periodo,
								CodAlmacen,
								CodCentroCosto,
								CodDocumentoReferencia,
								NroDocumentoReferencia,
								IngresadoPor,
								RecibidoPor,
								Comentarios,
								FlagManual,
								FlagPendiente,
								ReferenciaNroDocumento,
								DocumentoReferencia,
								DocumentoReferenciaInterno,
								CodDependencia,
								Anio,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$NroInterno."',
								'".$CodTransaccion."',
								'".$FechaDocumento."',
								'".$Periodo."',
								'".$CodAlmacen."',
								'".$CodCentroCosto."',
								'".$CodDocumentoReferencia."',
								'".$NroDocumentoReferencia."',
								'".$IngresadoPor."',
								'".$RecibidoPor."',
								'".$Comentarios."',
								'".$FlagManual."',
								'".$FlagPendiente."',
								'".$ReferenciaNroDocumento."',
								'".$DocumentoReferencia."',
								'".$DocumentoReferenciaInterno."',
								'".$CodDependencia."',
								'".$Anio."',
								'CO',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CantidadPedida, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaNroInterno, $_ReferenciaSecuencia) = split(";char:td;", $linea);
			$_Descripcion = changeUrl($_Descripcion);
			$_PrecioCantidad = $_CantidadRecibida * $_PrecioUnit;
			
			//	si es dirigido a commoditys
			if ($FlagCommodity == "S") {
				##	inserto detalle
				$sql = "INSERT INTO lg_commoditytransacciondetalle (
									CodOrganismo,
									CodDocumento,
									NroDocumento,
									Secuencia,
									CommoditySub,
									Descripcion,
									CodUnidad,
									CantidadKardex,
									Cantidad,
									PrecioUnit,
									Total,
									ReferenciaCodDocumento,
									ReferenciaNroDocumento,
									ReferenciaNroInterno,
									ReferenciaSecuencia,
									CodAlmacen,
									CodCentroCosto,
									Anio,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodOrganismo."',
									'".$CodDocumento."',
									'".$NroDocumento."',
									'".$_Secuencia."',
									'".$_CommoditySub."',
									'".$_Descripcion."',
									'".$_CodUnidad."',
									'".$_CantidadRecibida."',
									'".$_CantidadRecibida."',
									'".$_PrecioUnit."',
									'".$_Total."',
									'".$_ReferenciaCodDocumento."',
									'".$_ReferenciaNroDocumento."',
									'".$_ReferenciaNroInterno."',
									'".$_ReferenciaSecuencia."',
									'".$CodAlmacen."',
									'".$_CodCentroCosto."',
									'".$Periodo."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				##	inserto detalle
				$sql = "INSERT INTO lg_transacciondetalle (
									CodOrganismo,
									CodDocumento,
									NroDocumento,
									Secuencia,
									CodItem,
									Descripcion,
									CodUnidad,
									CantidadPedida,
									CantidadRecibida,
									PrecioUnit,
									Total,
									ReferenciaCodDocumento,
									ReferenciaNroDocumento,
									ReferenciaNroInterno,
									ReferenciaSecuencia,
									CodCentroCosto,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodOrganismo."',
									'".$CodDocumento."',
									'".$NroDocumento."',
									'".$_Secuencia."',
									'".$_CodItem."',
									'".$_Descripcion."',
									'".$_CodUnidad."',
									'".$_CantidadPedida."',
									'".$_CantidadRecibida."',
									'".$_PrecioUnit."',
									'".$_Total."',
									'".$_ReferenciaCodDocumento."',
									'".$_ReferenciaNroDocumento."',
									'".$_ReferenciaNroInterno."',
									'".$_ReferenciaSecuencia."',
									'".$_CodCentroCosto."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	actualizo requerimientos (solo si es un requerimiento de autoreposicion)
			if ($field_rau['Clasificacion'] == $_PARAMETRO['REQRAU']) {
				##	si se scompleto el despacho
				if ($_CantidadPedida == $_CantidadRecibida) {
					##	completo detalle del requerimiento
					$sql = "UPDATE lg_requerimientosdet
							SET Estado = 'CO'
							WHERE
								CodRequerimiento = '".$CodRequerimiento."' AND
								Secuencia = '".$_ReferenciaSecuencia."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					
					##	si se completaron los detalles
					$sql = "SELECT *
							FROM lg_requerimientosdet
							WHERE
								CodRequerimiento = '".$CodRequerimiento."' AND
								Estado = 'PE'";
					$query_pendientes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					if (mysql_num_rows($query_pendientes) == 0) {
						##	completo requerimiento
						$sql = "UPDATE lg_requerimientos
								SET Estado = 'CO'
								WHERE CodRequerimiento = '".$CodRequerimiento."'";
						$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					}
				}
				##	actualizo cantidad recibida
				$sql = "UPDATE lg_requerimientosdet
						SET CantidadRecibida = (CantidadRecibida + ".floatval($_CantidadRecibida).")
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Secuencia = '".$_ReferenciaSecuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}			
			##	actualizo cantidad pendiente
			$sql = "UPDATE lg_requerimientosdet
					SET CantidadOrdenCompra = (CantidadOrdenCompra + ".floatval($_CantidadRecibida).")
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Secuencia = '".$_ReferenciaSecuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	si es dirigido a commoditys
			if ($FlagCommodity == "S") {
				//	consulto el stock
				$sql = "SELECT *
						FROM lg_commoditystock
						WHERE
							CommoditySub = '".$_CommoditySub."' AND
							CodAlmacen = '".$CodAlmacen."'";
				$query_stock = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_stock) == 0) {
					//	inserto
					$sql = "INSERT INTO lg_commoditystock (
										CodAlmacen,
										CommoditySub,
										Cantidad,
										PrecioUnitario,
										IngresadoPor,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$CodAlmacen."',
										'".$_CommoditySub."',
										'".$_CantidadRecibida."',
										'".$_PrecioUnit."',
										'".$IngresadoPor."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					//	actualizo
					$sql = "UPDATE lg_commoditystock
							SET
								Cantidad = Cantidad + ".floatval($_CantidadRecibida).",
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()
							WHERE
								CommoditySub = '".$_CommoditySub."' AND
								CodAlmacen = '".$CodAlmacen."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			} else {
				##	consulto el stock
				$sql = "SELECT *
						FROM lg_itemalmacen
						WHERE
							CodAlmacen = '".$CodAlmacen."' AND
							CodItem = '".$_CodItem."'";
				$query_almacen = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_almacen) == 0) {
					##	inserto item en almacen
					$sql = "INSERT INTO lg_itemalmacen (
										CodItem,
										CodAlmacen,
										Estado,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$_CodItem."',
										'".$CodAlmacen."',
										'A',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					
					##	inserto item en inventario
					$sql = "INSERT INTO lg_itemalmaceninv (
										CodAlmacen,
										CodItem,
										Proveedor,
										FechaIngreso,
										StockIngreso,
										StockActual,
										PrecioUnitario,
										DocReferencia,
										IngresadoPor,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$CodAlmacen."',
										'".$_CodItem."',
										'".$CodProveedor."',
										NOW(),
										'".$_CantidadRecibida."',
										'".$_CantidadRecibida."',
										'".$_PrecioUnit."',
										'".$_ReferenciaCodDocumento."-".$_ReferenciaNroDocumento."',
										'".$_SESSION["CODPERSONA_ACTUAL"]."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					##	actualizo item en inventario
					$sql = "UPDATE lg_itemalmaceninv
							SET
								StockActual = (StockActual + ".floatval($_CantidadRecibida)."),
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()
							WHERE
								CodAlmacen = '".$CodAlmacen."' AND
								CodItem = '".$_CodItem."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		//	si es dirigido a commoditys
		if ($FlagCommodity == "S") {
			//	si es una transaccion de activos fijoss
			if ($FlagActivoFijo == "S") {
				//	inserto activos
				$activo = split(";char:tr;", $activos);
				foreach ($activo as $linea) {
					list($_Secuencia, $_NroSecuencia, $_CommoditySub, $_Descripcion, $_CodClasificacion, $_Monto, $_NroSerie, $_FechaIngreso, $_Modelo, $_CodBarra, $_CodUbicacion, $_CodCentroCosto, $_NroPlaca, $_CodMarca, $_Color) = split(";char:td;", $linea);
					$_Descripcion = changeUrl($_Descripcion);
				
					##	inserto activo
					$sql = "INSERT INTO lg_activofijo (
										CodOrganismo,
										Anio,
										NroOrden,
										NroInterno,
										Secuencia,
										NroSecuencia,
										CommoditySub,
										Descripcion,
										CodCentroCosto,
										CodClasificacion,
										CodBarra,
										NroSerie,
										Modelo,
										CodProveedor,
										CodDocumento,
										NroDocumento,
										Monto,
										CodUbicacion,
										FechaIngreso,
										FlagFacturado,
										CodMarca,
										Color,
										NroPlaca,
										NumeroOrdenFecha,
										Estado,
										Clasificacion,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$CodOrganismo."',
										'".$Anio."',
										'".$ReferenciaNroDocumento."',
										'".$field_oc['NroInterno']."',
										'".$_Secuencia."',
										'".$_NroSecuencia."',
										'".$_CommoditySub."',
										'".$_Descripcion."',
										'".$_CodCentroCosto."',
										'".$_CodClasificacion."',
										'".$_CodBarra."',
										'".$_NroSerie."',
										'".$_Modelo."',
										'".$CodProveedor."',
										'".$CodDocumento."',
										'".$NroDocumento."',
										'".$_Monto."',
										'".$_CodUbicacion."',
										'".formatFechaAMD($_FechaIngreso)."',
										'N',
										'".$_CodMarca."',
										'".$_Color."',
										'".$_NroPlaca."',
										'".$field_oc['FechaOrden']."',
										'PR',
										'".$Clasificacion."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		mysql_query("COMMIT");
		die("|Se ha generado la Transacci&oacute;n <strong>Nro. $CodDocumento-$NroInterno</strong>");
	}
	
	//	despacho
	elseif ($accion == "despacho") {
		mysql_query("BEGIN");
		//	errores
		##	periodo
		$Anio = substr($FechaDocumento, 0, 4);
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	si es dirigido a commoditys
		if ($FlagCommodity == "S") {
			//	inserto transaccion
			##	genero el nuevo codigo
			$NroDocumento = getCodigo_3("lg_commoditytransaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
			$NroInterno = getCodigo("lg_commoditytransaccion", "NroInterno", 6, "Anio", $Anio, "CodOrganismo", $CodOrganismo, "CodDocumento", $CodDocumento);
			##	inserto
			$sql = "INSERT INTO lg_commoditytransaccion (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								NroInterno,
								CodTransaccion,
								FechaDocumento,
								Periodo,
								CodAlmacen,
								CodCentroCosto,
								CodDocumentoReferencia,
								NroDocumentoReferencia,
								IngresadoPor,
								RecibidoPor,
								Comentarios,
								ReferenciaNroDocumento,
								DocumentoReferencia,
								DocumentoReferenciaInterno,
								CodDependencia,
								Anio,
								FlagManual,
								FlagPendiente,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$NroInterno."',
								'".$CodTransaccion."',
								'".$FechaDocumento."',
								'".$Periodo."',
								'".$CodAlmacen."',
								'".$CodCentroCosto."',
								'".$CodDocumentoReferencia."',
								'".$NroDocumentoReferencia."',
								'".$IngresadoPor."',
								'".$RecibidoPor."',
								'".$Comentarios."',
								'".$ReferenciaNroDocumento."',
								'".$DocumentoReferencia."',
								'".$DocumentoReferenciaInterno."',
								'".$CodDependencia."',
								'".$Periodo."',
								'".$FlagManual."',
								'".$FlagPendiente."',
								'CO',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		} else {
			//	inserto transaccion
			##	genero el nuevo codigo
			$NroDocumento = getCodigo_3("lg_transaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
			
			$NroInterno = getCodigo("lg_transaccion", "NroInterno", 6, "Anio", $Anio, "CodOrganismo", $CodOrganismo, "CodDocumento", $CodDocumento);
			##	inserto
			$sql = "INSERT INTO lg_transaccion (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								NroInterno,
								CodTransaccion,
								FechaDocumento,
								Periodo,
								CodAlmacen,
								CodCentroCosto,
								CodDocumentoReferencia,
								NroDocumentoReferencia,
								IngresadoPor,
								RecibidoPor,
								Comentarios,
								FlagManual,
								FlagPendiente,
								ReferenciaNroDocumento,
								DocumentoReferencia,
								DocumentoReferenciaInterno,
								CodDependencia,
								Anio,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$NroInterno."',
								'".$CodTransaccion."',
								'".$FechaDocumento."',
								'".$Periodo."',
								'".$CodAlmacen."',
								'".$CodCentroCosto."',
								'".$CodDocumentoReferencia."',
								'".$NroDocumentoReferencia."',
								'".$IngresadoPor."',
								'".$RecibidoPor."',
								'".$Comentarios."',
								'".$FlagManual."',
								'".$FlagPendiente."',
								'".$ReferenciaNroDocumento."',
								'".$DocumentoReferencia."',
								'".$DocumentoReferenciaInterno."',
								'".$CodDependencia."',
								'".$Anio."',
								'CO',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CantidadPedida, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaNroInterno, $_ReferenciaSecuencia) = split(";char:td;", $linea);
			$_Descripcion = changeUrl($_Descripcion);
			$_PrecioCantidad = $_CantidadRecibida * $_PrecioUnit;
			
			//	si es dirigido a commoditys
			if ($FlagCommodity == "S") {
				##	inserto detalle
				$sql = "INSERT INTO lg_commoditytransacciondetalle (
									CodOrganismo,
									CodDocumento,
									NroDocumento,
									Secuencia,
									CommoditySub,
									Descripcion,
									CodUnidad,
									CantidadKardex,
									Cantidad,
									PrecioUnit,
									Total,
									ReferenciaCodDocumento,
									ReferenciaNroDocumento,
									ReferenciaNroInterno,
									ReferenciaSecuencia,
									CodAlmacen,
									CodCentroCosto,
									Anio,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodOrganismo."',
									'".$CodDocumento."',
									'".$NroDocumento."',
									'".$_Secuencia."',
									'".$_CommoditySub."',
									'".$_Descripcion."',
									'".$_CodUnidad."',
									'".$_CantidadRecibida."',
									'".$_CantidadRecibida."',
									'".$_PrecioUnit."',
									'".$_Total."',
									'".$_ReferenciaCodDocumento."',
									'".$_ReferenciaNroDocumento."',
									'".$_ReferenciaNroInterno."',
									'".$_ReferenciaSecuencia."',
									'".$CodAlmacen."',
									'".$_CodCentroCosto."',
									'".$Periodo."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				##	inserto detalle
				$sql = "INSERT INTO lg_transacciondetalle (
									CodOrganismo,
									CodDocumento,
									NroDocumento,
									Secuencia,
									CodItem,
									Descripcion,
									CodUnidad,
									CantidadPedida,
									CantidadRecibida,
									PrecioUnit,
									Total,
									ReferenciaCodDocumento,
									ReferenciaNroDocumento,
									ReferenciaNroInterno,
									ReferenciaSecuencia,
									CodCentroCosto,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodOrganismo."',
									'".$CodDocumento."',
									'".$NroDocumento."',
									'".$_Secuencia."',
									'".$_CodItem."',
									'".$_Descripcion."',
									'".$_CodUnidad."',
									'".$_CantidadPedida."',
									'".$_CantidadRecibida."',
									'".$_PrecioUnit."',
									'".$_Total."',
									'".$_ReferenciaCodDocumento."',
									'".$_ReferenciaNroDocumento."',
									'".$_ReferenciaNroInterno."',
									'".$_ReferenciaSecuencia."',
									'".$_CodCentroCosto."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	actualizo requerimientos
			##	si se scompleto el despacho
			if ($_CantidadPedida == $_CantidadRecibida) {
				##	completo detalle del requerimiento
				$sql = "UPDATE lg_requerimientosdet
						SET Estado = 'CO'
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Secuencia = '".$_ReferenciaSecuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				##	si se completaron los detalles
				$sql = "SELECT *
						FROM lg_requerimientosdet
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Estado = 'PE'";
				$query_pendientes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_pendientes) == 0) {
					##	completo requerimiento
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CO'
							WHERE CodRequerimiento = '".$CodRequerimiento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
			##	actualizo cantidad pendiente
			$sql = "UPDATE lg_requerimientosdet
					SET CantidadRecibida = (CantidadRecibida + ".floatval($_CantidadRecibida).")
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Secuencia = '".$_ReferenciaSecuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	
			
			//	si es dirigido a commoditys
			if ($FlagCommodity == "S") {
				//	consulto el stock
				$sql = "SELECT *
						FROM lg_commoditystock
						WHERE
							CommoditySub = '".$_CommoditySub."' AND
							CodAlmacen = '".$CodAlmacen."'";
				$query_stock = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_stock) == 0) {
					//	inserto
					$sql = "INSERT INTO lg_commoditystock (
										CodAlmacen,
										CommoditySub,
										Cantidad,
										PrecioUnitario,
										IngresadoPor,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$CodAlmacen."',
										'".$_CommoditySub."',
										'-".$_CantidadRecibida."',
										'".$_PrecioUnit."',
										'".$IngresadoPor."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					//	actualizo
					$sql = "UPDATE lg_commoditystock
							SET
								Cantidad = Cantidad - ".floatval($_CantidadRecibida).",
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()
							WHERE
								CommoditySub = '".$_CommoditySub."' AND
								CodAlmacen = '".$CodAlmacen."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			} else {
				##	consulto el stock
				$sql = "SELECT *
						FROM lg_itemalmacen
						WHERE
							CodAlmacen = '".$CodAlmacen."' AND
							CodItem = '".$_CodItem."'";
				$query_almacen = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_almacen) == 0) {
					##	inserto item en almacen
					$sql = "INSERT INTO lg_itemalmacen (
										CodItem,
										CodAlmacen,
										Estado,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$_CodItem."',
										'".$CodAlmacen."',
										'A',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					
					##	inserto item en inventario
					$sql = "INSERT INTO lg_itemalmaceninv (
										CodAlmacen,
										CodItem,
										Proveedor,
										FechaIngreso,
										StockIngreso,
										StockActual,
										PrecioUnitario,
										DocReferencia,
										IngresadoPor,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$CodAlmacen."',
										'".$_CodItem."',
										'".$CodProveedor."',
										NOW(),
										'-".$_CantidadRecibida."',
										'-".$_CantidadRecibida."',
										'".$_PrecioUnit."',
										'".$_ReferenciaCodDocumento."-".$_ReferenciaNroDocumento."',
										'".$_SESSION["CODPERSONA_ACTUAL"]."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					##	actualizo item en inventario
					$sql = "UPDATE lg_itemalmaceninv
							SET
								StockActual = (StockActual - ".floatval($_CantidadRecibida)."),
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()
							WHERE
								CodAlmacen = '".$CodAlmacen."' AND
								CodItem = '".$_CodItem."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		mysql_query("COMMIT");
		die("|Se ha generado la Transacci&oacute;n <strong>Nro. $CodDocumento-$NroInterno</strong>");
	}
}

//	facturacion de activos
elseif ($modulo == "facturacion_activos") {
	mysql_query("BEGIN");
	//	-----------------
	//	actualizar
	$sql = "UPDATE lg_activofijo
			SET
				FlagFacturado = 'S',
				ObligacionTipoDocumento = '".$CodTipoDocumento."',
				ObligacionNroDocumento = '".$NroDocumento."',
				ObligacionFechaDocumento = '".formatFechaAMD($FechaRegistro)."',
				UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
				UltimaFecha = NOW()
			WHERE
				CodOrganismo = '".$CodOrganismo."' AND
				Anio = '".$Anio."' AND
				NroOrden = '".$NroOrden."' AND
				Secuencia = '".$Secuencia."' AND
				NroSecuencia = '".$NroSecuencia."'";
	$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	//	-----------------
	mysql_query("COMMIT");
}

//	cotizaciones
elseif ($modulo == "cotizaciones") {
	//	invitar a cotizar
	if ($accion == "cotizaciones_items_invitar_proveedores") {
		mysql_query("BEGIN");
		//-------------------
		$Numero = intval(getCodigo("lg_cotizacion", "Numero", 10));
		$proveedores = split(";char:tr;", $detalles_proveedores);
		foreach ($proveedores as $proveedor) {
			list($_CodProveedor, $_NomProveedor, $_CodFormaPago) = split(";char:td;", $proveedor);
			##
			$NroCotizacionProv = getCodigo("lg_cotizacion", "NroCotizacionProv", 8);
			$NumeroInterno = getCodigo("lg_cotizacion", "NumeroInterno", 8, "Anio", date("Y"));
			$requerimientos = split(";", $detalles_requerimientos);
			foreach ($requerimientos as $requerimiento) {
				list($_CodRequerimiento, $_Secuencia, $_CodOrganismo, $_FlagExonerado) = split("_", $requerimiento);
				##
				$CotizacionNumero = getCodigo("lg_cotizacion", "CotizacionNumero", 8);
				$CantidadRequerimiento = getVar("lg_requerimientosdet", "CantidadPedida", "CodRequerimiento", $_CodRequerimiento, "Secuencia", $_Secuencia);
				$CodUnidad = getVar("lg_requerimientosdet", "CodUnidad", "CodRequerimiento", $_CodRequerimiento, "Secuencia", $_Secuencia);
				
				//	valido
				if (getNumRows("lg_cotizacion", "CodRequerimiento", $_CodRequerimiento, "Secuencia", $_Secuencia, "CodProveedor", $_CodProveedor) > 0) die("<strong>$_NomProveedor</strong> ya posee una invitaci&oacute;n para uno de los requerimientos.");
				
				//	inserto
				$sql = "INSERT INTO lg_cotizacion
						SET
							Numero = '".$Numero."',
							NroCotizacionProv = '".$NroCotizacionProv."',
							NumeroInvitacion = '".$NroCotizacionProv."',
							Anio = NOW(),
							NumeroInterno = '".$NumeroInterno."',
							CotizacionNumero = '".$CotizacionNumero."',
							Cantidad = '".$CantidadRequerimiento."',
							FechaApertura = NOW(),
							FechaDocumento = NOW(),
							FechaRecepcion = NOW(),
							FechaEntrega = NOW(),
							CodOrganismo = '".$_CodOrganismo."',
							CodRequerimiento = '".$_CodRequerimiento."',
							Secuencia = '".$_Secuencia."',
							CodProveedor = '".$_CodProveedor."',
							NomProveedor = '".changeUrl($_NomProveedor)."',
							FlagAsignado = 'N',
							FlagExonerado = '".$_FlagExonerado."',
							CodFormaPago = '".$_CodFormaPago."',
							FechaInvitacion = NOW(),
							FechaLimite = '".formatFechaAMD($FechaLimite)."',
							Condiciones = '".changeUrl($Condiciones)."',
							Observaciones = '".changeUrl($Observaciones)."',
							FlagUnidadCompra = 'N',
							CodUnidadCompra = '".$CodUnidad."',
							CantidadCompra = '".$CantidadRequerimiento."',
							Estado = 'PE',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	actualizo
				$sql = "UPDATE lg_requerimientosdet
						SET
							CotizacionSecuencia = '".mysql_insert_id()."',
							CotizacionCantidad = '".$CantidadRequerimiento."',
							CotizacionProveedor = '".$_CodProveedor."',
							CotizacionFormaPago = '".$_CodFormaPago."',
							CotizacionRegistros = (CotizacionRegistros + 1)
						WHERE
							CodRequerimiento = '".$_CodRequerimiento."' AND
							Secuencia = '".$_Secuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	
			}
		}
		echo "|".$Numero;
		//-------------------
		mysql_query("COMMIT");
	}
	
	//	cotizar x items
	elseif ($accion == "cotizaciones_items_invitar_cotizar") {
		mysql_query("BEGIN");
		//-------------------
		$filtro_delete = "";
		$Numero = intval(getCodigo("lg_cotizacion", "Numero", 10));
		$proveedores = split(";char:tr;", $detalles_proveedores);
		foreach ($proveedores as $proveedor) {
			list($_CotizacionSecuencia, $_CodProveedor, $_NomProveedor, $_FlagAsignado, $_CodUnidad, $_Cantidad, $_CodUnidadCompra, $_CantidadCompra, $_PrecioUnitInicio, $_FlagExonerado, $_PrecioUnitInicioIva, $_DescuentoPorcentaje, $_DescuentoFijo, $_PrecioUnitIva, $_Total, $_PrecioUnitFinal, $_FlagMejorPrecio, $_CodFormaPago, $_FechaInvitacion, $_FechaEntrega, $_FechaRecepcion, $_FechaLimite, $_Condiciones, $_Observaciones, $_DiasEntrega, $_ValidezOferta, $_NumeroCotizacion, $_FechaDocumento) = split(";char:td;", $proveedor);
			##
			$NroCotizacionProv = getCodigo("lg_cotizacion", "NroCotizacionProv", 8);
			$NumeroInterno = getCodigo("lg_cotizacion", "NumeroInterno", 8, "Anio", date("Y"));
			##
			$CotizacionNumero = getCodigo("lg_cotizacion", "CotizacionNumero", 8);
			$CantidadRequerimiento = getVar("lg_requerimientosdet", "CantidadPedida", "CodRequerimiento", $CodRequerimiento, "Secuencia", $Secuencia);
			if ($_FlagAsignado == "S") $_FechaAsignacion = substr($Ahora, 0, 10); else $_FechaAsignacion = "";
			if ($_CodUnidad != $_CodUnidadCompra) $_FlagUnidadCompra = "S"; else $_FlagUnidadCompra = "N";
			$_PrecioUnit = $_PrecioUnitInicio - $_DescuentoFijo - ($_PrecioUnitInicio * $_DescuentoPorcentaje / 100);
			//	valido
			if ($_CotizacionSecuencia != "") {
				//	actualizo
				$sql = "UPDATE lg_cotizacion
						SET
							FlagAsignado = '".$_FlagAsignado."',
							Cantidad = '".$_Cantidad."',
							PrecioUnitInicio = '".$_PrecioUnitInicio."',
							FlagExonerado = '".$_FlagExonerado."',
							PrecioUnitInicioIva = '".$_PrecioUnitInicioIva."',
							DescuentoPorcentaje = '".$_DescuentoPorcentaje."',
							DescuentoFijo = '".$_DescuentoFijo."',
							PrecioUnit = '".$_PrecioUnit."',
							PrecioUnitIva = '".$_PrecioUnitIva."',
							Total = '".$_Total."',
							PrecioCantidad = '".($_CantidadCompra*$_PrecioUnit)."',
							CodFormaPago = '".$_CodFormaPago."',
							FechaInvitacion = '".$_FechaInvitacion."',
							FechaEntrega = '".$_FechaEntrega."',
							FechaRecepcion = '".$_FechaRecepcion."',
							FechaLimite = '".$_FechaLimite."',
							FechaDocumento = '".$_FechaDocumento."',
							Condiciones = '".changeUrl($_Condiciones)."',
							Observaciones = '".changeUrl($_Observaciones).changeUrl($Observaciones)."',
							DiasEntrega = '".$_DiasEntrega."',
							ValidezOferta = '".$_ValidezOferta."',
							NumeroCotizacion = '".changeUrl($_NumeroCotizacion)."',
							FlagMejorPrecio = '".$_FlagMejorPrecio."',
							FlagUnidadCompra = '".$_FlagUnidadCompra."',
							CodUnidadCompra = '".$_CodUnidadCompra."',
							CantidadCompra = '".$_CantidadCompra."',
							Estado = 'PE',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE CotizacionSecuencia = '".$_CotizacionSecuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	actualizo
				$sql = "UPDATE lg_requerimientosdet
						SET
							CotizacionSecuencia = '".$_CotizacionSecuencia."',
							CotizacionCantidad = '".$CantidadRequerimiento."',
							CotizacionProveedor = '".$_CodProveedor."',
							CotizacionFormaPago = '".$_CodFormaPago."',
							CotizacionPrecioUnitInicio = '".$_PrecioUnitInicio."',
							CotizacionPrecioUnit = '".$_PrecioUnitInicio."',
							CotizacionPrecioUnitIva = '".$_PrecioUnitIva."',
							CotizacionFechaAsignacion = '".$_FechaAsignacion."'
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Secuencia = '".$Secuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				$_CotizacionSecuencia = mysql_insert_id();
				//	inserto
				$sql = "INSERT INTO lg_cotizacion
						SET
							Numero = '".$Numero."',
							NroCotizacionProv = '".$NroCotizacionProv."',
							NumeroInvitacion = '".$NroCotizacionProv."',
							Anio = NOW(),
							NumeroInterno = '".$NumeroInterno."',
							CotizacionNumero = '".$CotizacionNumero."',
							FechaApertura = NOW(),
							FechaDocumento = '".$_FechaDocumento."',
							FechaEntrega = '".$_FechaEntrega."',
							FechaRecepcion = '".$_FechaRecepcion."',
							CodOrganismo = '".$CodOrganismo."',
							CodRequerimiento = '".$CodRequerimiento."',
							Secuencia = '".$Secuencia."',
							CodProveedor = '".$_CodProveedor."',
							NomProveedor = '".changeUrl($_NomProveedor)."',
							FlagAsignado = '".$_FlagAsignado."',
							Cantidad = '".$_Cantidad."',
							PrecioUnitInicio = '".$_PrecioUnitInicio."',
							FlagExonerado = '".$_FlagExonerado."',
							PrecioUnitInicioIva = '".$_PrecioUnitInicioIva."',
							DescuentoPorcentaje = '".$_DescuentoPorcentaje."',
							DescuentoFijo = '".$_DescuentoFijo."',
							PrecioUnit = '".$_PrecioUnit."',
							PrecioUnitIva = '".$_PrecioUnitIva."',
							Total = '".$_Total."',
							PrecioCantidad = '".($_CantidadCompra*$_PrecioUnit)."',
							CodFormaPago = '".$_CodFormaPago."',
							FechaInvitacion = '".$_FechaInvitacion."',
							FechaLimite = '".$_FechaLimite."',
							Condiciones = '".changeUrl($_Condiciones)."',
							Observaciones = '".changeUrl($Observaciones)."',
							DiasEntrega = '".$_DiasEntrega."',
							ValidezOferta = '".$_ValidezOferta."',
							NumeroCotizacion = '".changeUrl($_NumeroCotizacion)."',
							FlagMejorPrecio = '".$_FlagMejorPrecio."',
							FlagUnidadCompra = '".$_FlagUnidadCompra."',
							CodUnidadCompra = '".$_CodUnidadCompra."',
							CantidadCompra = '".$_CantidadCompra."',
							Estado = 'PE',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	actualizo
				$sql = "UPDATE lg_requerimientosdet
						SET
							CotizacionSecuencia = '".mysql_insert_id()."',
							CotizacionCantidad = '".$CantidadRequerimiento."',
							CotizacionProveedor = '".$_CodProveedor."',
							CotizacionFormaPago = '".$_CodFormaPago."',
							CotizacionPrecioUnitInicio = '".$_PrecioUnitInicio."',
							CotizacionPrecioUnit = '".$_PrecioUnitInicio."',
							CotizacionPrecioUnitIva = '".$_PrecioUnitIva."',
							CotizacionFechaAsignacion = '".$_FechaAsignacion."',
							CotizacionRegistros = CotizacionRegistros + 1
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Secuencia = '".$Secuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	si borro alguna linea
		if ($borrar_proveedores != "") {
			$proveedoresx = split("[|]", $borrar_proveedores);
			foreach ($proveedoresx as $CodProveedor) {
				//	elimino
				$sql = "DELETE FROM lg_cotizacion
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Secuencia = '".$Secuencia."' AND
							CodProveedor = '".$CodProveedor."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//-------------------
		mysql_query("COMMIT");
	}
	
	//	cotizar x proveedors
	elseif ($accion == "cotizaciones_proveedores_invitar_cotizar") {
		mysql_query("BEGIN");
		//-------------------
		$filtro_delete = "";
		$Numero = intval(getCodigo("lg_cotizacion", "Numero", 10));
		$items = split(";char:tr;", $detalles_items);
		foreach ($items as $item) {
			list($_CotizacionSecuencia, $_CodUnidad, $_Cantidad, $_CodUnidadCompra, $_CantidadCompra, $_PrecioUnitInicio, $_FlagAsignado, $_FlagExonerado, $_PrecioUnitInicioIva, $_DescuentoPorcentaje, $_DescuentoFijo, $_PrecioUnit, $_PrecioUnitIva, $_Total, $_Observaciones) = split(";char:td;", $item);
			##
			$NroCotizacionProv = getCodigo("lg_cotizacion", "NroCotizacionProv", 8);
			$NumeroInterno = getCodigo("lg_cotizacion", "NumeroInterno", 8, "Anio", date("Y"));
			##
			$CotizacionNumero = getCodigo("lg_cotizacion", "CotizacionNumero", 8);
			$CantidadRequerimiento = getVar("lg_requerimientosdet", "CantidadPedida", "CodRequerimiento", $CodRequerimiento, "Secuencia", $Secuencia);
			if ($_FlagAsignado == "S") $_FechaAsignacion = substr($Ahora, 0, 10); else $_FechaAsignacion = "";
			if ($_CodUnidad != $_CodUnidadCompra) $_FlagUnidadCompra = "S"; else $_FlagUnidadCompra = "N";
			//	actualizo
			$sql = "UPDATE lg_cotizacion
					SET
						FlagAsignado = '".$_FlagAsignado."',
						Cantidad = '".$_Cantidad."',
						PrecioUnitInicio = '".$_PrecioUnitInicio."',
						FlagExonerado = '".$_FlagExonerado."',
						PrecioUnitInicioIva = '".$_PrecioUnitInicioIva."',
						DescuentoPorcentaje = '".$_DescuentoPorcentaje."',
						DescuentoFijo = '".$_DescuentoFijo."',
						PrecioUnit = '".$_PrecioUnit."',
						PrecioUnitIva = '".$_PrecioUnitIva."',
						Total = '".$_Total."',
						PrecioCantidad = '".($_CantidadCompra*$_PrecioUnit)."',
						CodFormaPago = '".$CodFormaPago."',
						FechaInvitacion = '".formatFechaAMD($FechaInvitacion)."',
						FechaLimite = '".formatFechaAMD($FechaLimite)."',
						FechaEntrega = '".formatFechaAMD($FechaEntrega)."',
						FechaRecepcion = '".formatFechaAMD($FechaRecepcion)."',
						FechaApertura = '".formatFechaAMD($FechaApertura)."',
						FechaDocumento = '".formatFechaAMD($FechaDocumento)."',
						Observaciones = '".changeUrl($_Observaciones)."',
						DiasEntrega = '".$DiasEntrega."',
						ValidezOferta = '".$ValidezOferta."',
						NumeroCotizacion = '".changeUrl($NumeroCotizacion)."',
						FlagUnidadCompra = '".$_FlagUnidadCompra."',
						CodUnidadCompra = '".$_CodUnidadCompra."',
						CantidadCompra = '".$_CantidadCompra."',
						Estado = 'PE',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE CotizacionSecuencia = '".$_CotizacionSecuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	actualizo
			$sql = "UPDATE lg_requerimientosdet
					SET
						CotizacionSecuencia = '".$_CotizacionSecuencia."',
						CotizacionCantidad = '".$_CantidadCompra."',
						CotizacionFormaPago = '".$CodFormaPago."',
						CotizacionPrecioUnitInicio = '".$_PrecioUnitInicio."',
						CotizacionPrecioUnit = '".$_PrecioUnitInicio."',
						CotizacionPrecioUnitIva = '".$_PrecioUnitIva."',
						CotizacionFechaAsignacion = '".$_FechaAsignacion."'
					WHERE CotizacionSecuencia = '".$_CotizacionSecuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		}
		//-------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar
	elseif ($accion == "eliminar") {
		$sql = "DELETE FROM lg_cotizacion WHERE NroCotizacionProv = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	cierre mensual
elseif ($modulo == "cierre_mensual") {
	mysql_query("BEGIN");
	//-------------------
	##	periodo anterior
	$PeriodoPartes = explode("-", $Periodo);
	$Anio = $PeriodoPartes[0];
	$Mes = $PeriodoPartes[1];
	$AnioAnterior = $Anio;
	$MesAnterior = intval($Mes) - 1;
	if ($MesAnterior == 0) { $MesAnterior = 12; --$AnioAnterior; }
	if ($MesAnterior < 10) $MesAnterior = "0".$MesAnterior;
	$PeriodoAnterior = $AnioAnterior."-".$MesAnterior;
	//	1.	verificar si existe un cierre mensual para el periodo inmediato anterior del organismo
	##	---------->
	//	2.	borrar los datos del periodo actual
	$sql = "DELETE FROM lg_cierremensual
			WHERE
				Periodo = '".$Periodo."' AND
				CodOrganismo = '".$CodOrganismo."'";	fwrite($__archivo, $sql.";\n\n");
	execute($sql);
	$sql = "DELETE FROM lg_cierremensualsustento
			WHERE
				Periodo = '".$Periodo."' AND
				CodOrganismo = '".$CodOrganismo."'";	fwrite($__archivo, $sql.";\n\n");
	execute($sql);
	$sql = "DELETE FROM lg_cierremensualx";	fwrite($__archivo, $sql.";\n\n");
	execute($sql);
	//	3.	inserta datos del periodo anterior en el periodo actual
	##	
	$sql = "INSERT INTO lg_cierremensual (
				Periodo,
				CodOrganismo,
				CodAlmacen,
				CodItem,
				StockNuevo,
				Precio,
				UltimoUsuario,
				UltimaFecha
			)
			SELECT
				'".$Periodo."' AS Periodo,
				CodOrganismo,
				CodAlmacen,
				CodItem,
				StockNuevo,
				Precio,
				'".$_SESSION['USUARIO_ACTUAL']."' AS UltimoUsuario,
				NOW() AS UltimaFecha
			FROM lg_cierremensual
			WHERE
				Periodo = '".$PeriodoAnterior."' AND
				CodOrganismo = '".$CodOrganismo."'";	fwrite($__archivo, $sql.";\n\n");
	execute($sql);
	##	
	$sql = "INSERT INTO lg_cierremensualx (
				CodItem,
				Precio
			)
			SELECT
				cm.CodItem,
				MAX(cm.Precio) AS Precio
			FROM
				lg_cierremensual cm
				INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
			WHERE
				cm.Periodo = '".$PeriodoAnterior."' AND
				cm.CodOrganismo = '".$CodOrganismo."' AND
				a.TipoAlmacen = 'P'
			GROUP BY CodItem";	fwrite($__archivo, $sql.";\n\n");
	execute($sql);
	//	4.	actualizo a N campos nullos
	##	---------->
	//	5.6.	consolido kardex
	$sql = "(SELECT
				t.CodOrganismo,
				k.CodAlmacen,
				k.CodItem,
				k.PeriodoContable AS Periodo,
				SUM(CASE WHEN tt.TipoMovimiento = 'I' THEN k.Cantidad ELSE 0 END) AS Ingresos,
				SUM(CASE WHEN tt.TipoMovimiento = 'T' AND k.Cantidad > 0 THEN k.Cantidad ELSE 0 END) AS IngresoTraslado,
				SUM(CASE WHEN k.CodTransaccion = 'ROC' OR 
							  k.CodTransaccion = 'ARO' OR 
							  k.CodTransaccion = 'DRO' 
						 THEN k.Cantidad 
						 ELSE 0 
					END) AS IngresoROC,
				SUM(CASE WHEN tt.TipoMovimiento = 'T' AND -k.Cantidad < 0 THEN k.Cantidad ELSE 0 END) AS SalidaTraslado,
				SUM(CASE WHEN tt.TipoMovimiento = 'E' THEN -k.Cantidad ELSE 0 END) AS Egresos
			 FROM
				lg_kardex k
				INNER JOIN lg_transaccion t ON (t.CodOrganismo = k.ReferenciaCodOrganismo AND
												t.CodDocumento = k.CodDocumento AND
												t.NroDocumento = k.NroDocumento)
				INNER JOIN lg_almacenmast a ON (k.CodAlmacen = a.CodAlmacen)
				INNER JOIN lg_tipotransaccion tt ON (tt.CodTransaccion = t.CodTransaccion)
			 WHERE
				k.PeriodoContable = '".$Periodo."' AND
				a.CodOrganismo = '".$CodOrganismo."'
			 GROUP BY CodOrganismo, CodAlmacen, CodItem
			)
			UNION
			(SELECT
				CodOrganismo,
				CodAlmacen,
				CodItem,
				Periodo,
				'0.00' As Ingresos,
				IngresoTraslado,
				IngresoROC,
				SalidaTraslado,
				SalidaREQ AS Egresos
			 FROM lg_cierremensual
			 WHERE
				Periodo = '".$Periodo."' AND
				CodOrganismo = '".$CodOrganismo."'
			)
			ORDER BY CodAlmacen, CodItem";	fwrite($__archivo, $sql.";\n\n");
	$field_consolidado = getRecords($sql);
	foreach($field_consolidado as $f) {
		//	7.	inserto/actualizo item
		$sql = "INSERT INTO lg_cierremensual
				SET
					Periodo = '".$f['Periodo']."',
					CodOrganismo = '".$f['CodOrganismo']."',
					CodAlmacen = '".$f['CodAlmacen']."',
					CodItem = '".$f['CodItem']."',
					IngresoROC = '".$f['IngresoROC']."',
					IngresoOtros = '".$f['IngresoROC']."',
					IngresoTraslado = '".$f['IngresoTraslado']."',
					SalidaREQ = '".$f['Egresos']."',
					SalidaOtros = '".$f['Egresos']."',
					SalidaTraslado = '".$f['SalidaTraslado']."',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '".$f['IngresoROC']."',
					IngresoOtros = '".$f['IngresoROC']."',
					IngresoTraslado = '".$f['IngresoTraslado']."',
					SalidaREQ = '".$f['Egresos']."',
					SalidaOtros = '".$f['Egresos']."',
					SalidaTraslado = '".$f['SalidaTraslado']."',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		execute($sql);
	}
	//	8.	actualizo stock nuevo
	$sql = "UPDATE lg_cierremensual
			SET StockNuevo = (StockAnterior + IngresoOtros + IngresoTraslado - SalidaOtros - SalidaTraslado)
			WHERE
				CodOrganismo = '".$CodOrganismo."' AND
				Periodo = '".$Periodo."'";	fwrite($__archivo, $sql.";\n\n");
	execute($sql);
	//	9.	precio promedio
	$sql = "SELECT
				td.CodOrganismo,
				td.CodDocumento,
				td.NroDocumento,
				td.Secuencia,
				td.CodItem,
				td.Descripcion,
				td.CodUnidad,
				td.CantidadRecibida,
				td.PrecioUnit,
				td.Total,
				td.ReferenciaCodDocumento,
				t.NroInterno,
				t.CodTransaccion,
				t.FechaDocumento,
				t.CodAlmacen,
				t.Periodo,
				t.DocumentoReferencia,
				tt.Descripcion AS NomTransaccion,
				oc.Anio,
				oc.NroOrden,
				oc.CodProveedor,
				oc.NroInterno AS NroInternoOrden
			FROM
				lg_transacciondetalle td
				INNER JOIN lg_transaccion t ON (t.CodOrganismo = td.CodOrganismo AND
												t.CodDocumento = td.CodDocumento AND
												t.NroDocumento = td.NroDocumento)
				INNER JOIN lg_ordencompradetalle ocd ON (ocd.Anio = t.Anio AND
														 ocd.CodOrganismo = t.CodOrganismo AND
														 ocd.NroOrden = td.ReferenciaNroDocumento AND
														 ocd.Secuencia = td.ReferenciaSecuencia)
				INNER JOIN lg_ordencompra oc ON (oc.Anio = ocd.Anio AND
												 oc.CodOrganismo = ocd.CodOrganismo AND
												 oc.NroOrden = ocd.NroOrden)
				INNER JOIN lg_tipotransaccion tt ON (tt.CodTransaccion = t.CodTransaccion)
			WHERE
				t.CodOrganismo = '".$CodOrganismo."' AND
				t.Periodo = '".$Periodo."' AND
				t.Estado = 'CO' AND
				(t.CodTransaccion = 'ROC' OR 
				 t.CodTransaccion = 'ARO' OR 
				 t.CodTransaccion = 'DRO' OR 
				 t.CodTransaccion = 'MIT' OR 
				 t.CodTransaccion = 'TRT' OR 
				 t.FlagManual = 'S')
			ORDER BY CodItem, FechaDocumento";	fwrite($__archivo, $sql.";\n\n");
	$field_promedio = getRecords($sql);
	foreach($field_promedio as $f) {
		//	10.	consulto cierre mensual
		$sql = "SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '".$CodOrganismo."' AND
					cm.Periodo = '".$Periodo."' AND
					cm.CodItem = '".$f['CodItem']."' AND
					a.TipoAlmacen <> 'T'";	fwrite($__archivo, $sql.";\n\n");
		$field_cierre = getRecord($sql);
		//	12.	verifico facturacion
		$sql = "SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '".$f['Anio']."' AND
					d.CodProveedor = '".$f['CodProveedor']."' AND
					d.DocumentoClasificacion = '".$f['CodTransaccion']."' AND
					d.DocumentoReferencia = '".$f['ReferenciaCodDocumento']."'";	fwrite($__archivo, $sql.";\n\n");
		$field_facturacion = getRecord($sql);
		//	13.	inserto iten en cierre
		$sql = "INSERT INTO lg_cierremensualx 
				SET
					CodItem = '".$f['CodItem']."',
					Precio = '".$f['PrecioUnit']."'
				ON DUPLICATE KEY UPDATE
					Precio = Precio";	fwrite($__archivo, $sql.";\n\n");
		execute($sql);
		//	14.	actualizo transaccion detalle
		$sql = "UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '".$f['PrecioUnit']."',
					Total = '".$f['Total']."'
				WHERE
					CodOrganismo = '".$f['CodOrganismo']."' AND
					CodDocumento = '".$f['CodDocumento']."' AND
					NroDocumento = '".$f['NroDocumento']."'";	fwrite($__archivo, $sql.";\n\n");
		execute($sql);		
		//	15.	inserto sustento
		##	anterior
		if ($Grupo == $f['CodItem']) {
			$Grupo = $f['CodItem'];
			$sql = "INSERT INTO lg_cierremensualsustento
					SET
						Periodo = '".$f['Periodo']."',
						CodOrganismo = '".$f['CodOrganismo']."',
						CodAlmacen = '".$f['CodAlmacen']."',
						CodItem = '".$f['CodItem']."',
						Secuencia = '".$f['Secuencia']."',
						DocumentoReferencia = 'SaldoAnterior',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
			execute($sql);
		}
		##	actual
		$sql = "INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '".$f['Periodo']."',
					CodOrganismo = '".$f['CodOrganismo']."',
					CodAlmacen = '".$f['CodAlmacen']."',
					CodItem = '".$f['CodItem']."',
					Secuencia = '".$f['Secuencia']."',
					Cantidad = '".$f['CantidadRecibida']."',
					CantidadAcumulada = '".$f['CantidadRecibida']."',
					Precio = '".$f['PrecioUnit']."',
					Monto = '".$f['Total']."',
					DocumentoReferencia = '".$f['DocumentoReferencia']."',
					FechaRecepcion = '".$f['FechaDocumento']."',
					TransaccionCodDocumento = '".$f['CodDocumento']."',
					TransaccionNroDocumento = '".$f['NroDocumento']."',
					TransaccionSecuencia = '".$f['Secuencia']."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		execute($sql);
	}
	die("<a href='lib/$modulo-$accion.sql' target='_blank'>$modulo-$accion.sql</a>");
	//-------------------
	mysql_query("COMMIT");
}
?>