-- 2013-02-08
ALTER TABLE `siaceda`.`lg_cotizacion` CHANGE COLUMN `Estado` `Estado` VARCHAR(2) NOT NULL DEFAULT 'PE' COMMENT 'PE:PENDIENTE, AD:ADJUDICADO; NA:NO ADJUDICADO;'  ;
-- 2013-02-13
CREATE TABLE `lg_cotizacionordenes` (
  `CotizacionSecuencia` int(10) NOT NULL,
  `CodRequerimiento` varchar(10) NOT NULL,
  `SecuenciaRequerimiento` int(4) unsigned NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `Anio` varchar(4) NOT NULL,
  `NroOrden` varchar(10) NOT NULL,
  `SecuenciaOrden` int(4) NOT NULL,
  `TipoOrden` varchar(2) NOT NULL COMMENT 'OC:ORDEN DE COMPRA; OS:ORDEN DE SERVICIO',
  `CantidadOrden` int(4) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CotizacionSecuencia`,`CodRequerimiento`,`SecuenciaRequerimiento`,`CodOrganismo`,`Anio`,`NroOrden`,`SecuenciaOrden`,`TipoOrden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- 2013-02-14
INSERT INTO `siaceda`.`seguridad_grupo` (`CodAplicacion`, `Grupo`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('LG', '08', 'Procesos', 'A', 'EJBOLIVAR', '2013-02-14');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('LG', '08', '08-0001', 'Facturación de Activos', 'A', 'EJBOLIVAR', '2013-02-14');
ALTER TABLE `siaceda`.`lg_activofijo` ADD COLUMN `NroInterno` VARCHAR(10) NOT NULL  AFTER `NroSecuencia` ;
UPDATE lg_activofijo SET NroInterno = NroOrden;

-- 2013-02-26
-- Trigger DDL Statements
DELIMITER $$

USE `siaceda`$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ac_voucherdet_triger1`
AFTER INSERT ON `siaceda`.`ac_voucherdet`
FOR EACH ROW
BEGIN
	SET @Sum = (SELECT SUM(MontoVoucher)
				FROM ac_voucherdet
				WHERE
					CodOrganismo = NEW.CodOrganismo AND
					Periodo = NEW.Periodo AND
					CodContabilidad = NEW.CodContabilidad AND
					CodCuenta = NEW.CodCuenta AND
					Estado = 'MA');
	SET @SaldoBalance = COALESCE(@Sum, 0);

	INSERT INTO ac_voucherbalance
	SET
		CodOrganismo = NEW.CodOrganismo,
		Periodo = NEW.Periodo,
		CodCuenta = NEW.CodCuenta,
		CodContabilidad = NEW.CodContabilidad,
		SaldoBalance = @SaldoBalance,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW()
	ON DUPLICATE KEY UPDATE
		SaldoBalance = @SaldoBalance,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW();
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ac_voucherdet_triger2`
AFTER UPDATE ON `siaceda`.`ac_voucherdet`
FOR EACH ROW
BEGIN
	SET @Sum = (SELECT SUM(MontoVoucher)
				FROM ac_voucherdet
				WHERE
					CodOrganismo = NEW.CodOrganismo AND
					Periodo = NEW.Periodo AND
					CodContabilidad = NEW.CodContabilidad AND
					CodCuenta = NEW.CodCuenta AND
					Estado = 'MA');
	SET @SaldoBalance = COALESCE(@Sum, 0);

	INSERT INTO ac_voucherbalance
	SET
		CodOrganismo = NEW.CodOrganismo,
		Periodo = NEW.Periodo,
		CodCuenta = NEW.CodCuenta,
		CodContabilidad = NEW.CodContabilidad,
		SaldoBalance = @SaldoBalance,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW()
	ON DUPLICATE KEY UPDATE
		SaldoBalance = @SaldoBalance,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW();

	IF (NEW.CodOrganismo <> OLD.CodOrganismo OR NEW.Periodo <> OLD.Periodo OR NEW.CodContabilidad <> OLD.CodContabilidad OR NEW.CodCuenta <> OLD.CodCuenta) THEN
		SET @SumOld = (SELECT SUM(MontoVoucher)
						FROM ac_voucherdet
						WHERE
							CodOrganismo = OLD.CodOrganismo AND
							Periodo = OLD.Periodo AND
							CodContabilidad = OLD.CodContabilidad AND
							CodCuenta = OLD.CodCuenta AND
							Estado = 'MA');
		SET @SaldoBalanceOld = COALESCE(@SumOld, 0);

		INSERT INTO ac_voucherbalance
		SET
			CodOrganismo = OLD.CodOrganismo,
			Periodo = OLD.Periodo,
			CodCuenta = OLD.CodCuenta,
			CodContabilidad = OLD.CodContabilidad,
			SaldoBalance = @SaldoBalanceOld,
			UltimoUsuario = NEW.UltimoUsuario,
			UltimaFecha = NOW()
		ON DUPLICATE KEY UPDATE
			SaldoBalance = @SaldoBalanceOld,
			UltimoUsuario = NEW.UltimoUsuario,
			UltimaFecha = NOW();
	END IF;
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ac_voucherdet_triger3`
AFTER DELETE ON `siaceda`.`ac_voucherdet`
FOR EACH ROW
BEGIN
	IF (OLD.Estado = 'MA') THEN
		UPDATE ac_voucherbalance
		SET
			SaldoBalance = SaldoBalance - OLD.MontoVoucher,
			UltimoUsuario = OLD.UltimoUsuario,
			UltimaFecha = NOW()
		WHERE
			CodOrganismo = OLD.CodOrganismo AND
			Periodo = OLD.Periodo AND
			CodCuenta = OLD.CodCuenta AND
			CodContabilidad = OLD.CodContabilidad;
	END IF;
END$$


-- Trigger DDL Statements
DELIMITER $$

USE `siaceda`$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`lg_transacciondetalle_triger1`
AFTER INSERT ON `siaceda`.`lg_transacciondetalle`
FOR EACH ROW
BEGIN
	SET @CodAlmacen = (SELECT CodAlmacen FROM lg_transaccion WHERE CodOrganismo = NEW.CodOrganismo AND CodDocumento = NEW.CodDocumento AND NroDocumento = NEW.NroDocumento);
	SET @FechaDocumento = (SELECT FechaDocumento FROM lg_transaccion WHERE CodOrganismo = NEW.CodOrganismo AND CodDocumento = NEW.CodDocumento AND NroDocumento = NEW.NroDocumento);
	SET @CodTransaccion = (SELECT CodTransaccion FROM lg_transaccion WHERE CodOrganismo = NEW.CodOrganismo AND CodDocumento = NEW.CodDocumento AND NroDocumento = NEW.NroDocumento);
	SET @IngresadoPor = (SELECT IngresadoPor FROM lg_transaccion WHERE CodOrganismo = NEW.CodOrganismo AND CodDocumento = NEW.CodDocumento AND NroDocumento = NEW.NroDocumento);
	SET @DocumentoReferencia = (SELECT DocumentoReferencia FROM lg_transaccion WHERE CodOrganismo = NEW.CodOrganismo AND CodDocumento = NEW.CodDocumento AND NroDocumento = NEW.NroDocumento);
	SET @TipoMovimiento = (SELECT TipoMovimiento FROM lg_tipotransaccion WHERE CodTransaccion = @CodTransaccion);
	IF (@TipoMovimiento = 'E') THEN
		SET @CantidadNew = NEW.CantidadRecibida * -1;
	ELSE
		SET @CantidadNew = NEW.CantidadRecibida * 1;
	END IF;
	SET @Ingresos = (SELECT SUM(td.CantidadRecibida)
					 FROM
						lg_transacciondetalle td
						INNER JOIN lg_transaccion t ON (t.CodOrganismo = td.CodOrganismo AND
														t.CodDocumento = td.CodDocumento AND
														t.NroDocumento = td.NroDocumento)
						INNER JOIN lg_tipotransaccion tt ON (t.CodTransaccion = tt.CodTransaccion)
					 WHERE
						t.CodAlmacen = @CodAlmacen AND
						td.CodItem = NEW.CodItem AND
						tt.TipoMovimiento = 'I');
	SET @Egresos = (SELECT SUM(td.CantidadRecibida)
					FROM
						lg_transacciondetalle td
						INNER JOIN lg_transaccion t ON (t.CodOrganismo = td.CodOrganismo AND
														t.CodDocumento = td.CodDocumento AND
														t.NroDocumento = td.NroDocumento)
						INNER JOIN lg_tipotransaccion tt ON (t.CodTransaccion = tt.CodTransaccion)
					WHERE
						t.CodAlmacen = @CodAlmacen AND
						td.CodItem = NEW.CodItem AND
						tt.TipoMovimiento = 'E');
	SET @StockActual = COALESCE(@Ingresos, 0) - COALESCE(@Egresos, 0);

	IF (NEW.Estado <> 'PR') THEN
		INSERT INTO lg_kardex
		SET
			CodItem = NEW.CodItem,
			CodAlmacen = @CodAlmacen,
			CodDocumento = NEW.CodDocumento,
			NroDocumento = NEW.NroDocumento,
			Secuencia = NEW.Secuencia,
			Fecha = @FechaDocumento,
			CodTransaccion = @CodTransaccion,
			ReferenciaAnio = NEW.ReferenciaAnio,
			ReferenciaCodOrganismo = NEW.CodOrganismo,
			ReferenciaCodDocumento = NEW.ReferenciaCodDocumento,
			ReferenciaNroDocumento = NEW.ReferenciaNroDocumento,
			ReferenciaSecuencia = NEW.ReferenciaSecuencia,
			Cantidad = @CantidadNew,
			PrecioUnitario = NEW.PrecioUnit,
			MontoTotal = NEW.Total,
			PeriodoContable = SUBSTRING(@FechaDocumento, 1, 7),
			UltimoUsuario = NEW.UltimoUsuario,
			UltimaFecha = NOW();
	END IF;

	INSERT INTO lg_itemalmacen
	SET
		CodItem = NEW.CodItem,
		CodAlmacen = @CodAlmacen,
		Estado = 'A',
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW()
	ON DUPLICATE KEY UPDATE
		Estado = 'A';

	INSERT INTO lg_itemalmaceninv
	SET
		CodAlmacen = @CodAlmacen,
		CodItem = NEW.CodItem,
		FechaIngreso = NOW(),
		StockIngreso = @StockActual,
		StockActual = @StockActual,
		PrecioUnitario = NEW.PrecioUnit,
		DocReferencia = @DocumentoReferencia,
		IngresadoPor = @IngresadoPor,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW()
	ON DUPLICATE KEY UPDATE
		StockActual = @StockActual,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW();
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`lg_transacciondetalle_triger2`
AFTER UPDATE ON `siaceda`.`lg_transacciondetalle`
FOR EACH ROW
BEGIN
	SET @CodAlmacen = (SELECT CodAlmacen FROM lg_transaccion WHERE CodOrganismo = NEW.CodOrganismo AND CodDocumento = NEW.CodDocumento AND NroDocumento = NEW.NroDocumento);
	SET @FechaDocumento = (SELECT FechaDocumento FROM lg_transaccion WHERE CodOrganismo = NEW.CodOrganismo AND CodDocumento = NEW.CodDocumento AND NroDocumento = NEW.NroDocumento);
	SET @CodTransaccion = (SELECT CodTransaccion FROM lg_transaccion WHERE CodOrganismo = NEW.CodOrganismo AND CodDocumento = NEW.CodDocumento AND NroDocumento = NEW.NroDocumento);
	SET @IngresadoPor = (SELECT IngresadoPor FROM lg_transaccion WHERE CodOrganismo = NEW.CodOrganismo AND CodDocumento = NEW.CodDocumento AND NroDocumento = NEW.NroDocumento);
	SET @DocumentoReferencia = (SELECT DocumentoReferencia FROM lg_transaccion WHERE CodOrganismo = NEW.CodOrganismo AND CodDocumento = NEW.CodDocumento AND NroDocumento = NEW.NroDocumento);
	SET @TipoMovimiento = (SELECT TipoMovimiento FROM lg_tipotransaccion WHERE CodTransaccion = @CodTransaccion);
	IF (@TipoMovimiento = 'E') THEN
		SET @CantidadNew = NEW.CantidadRecibida * -1;
		SET @CantidadOld = OLD.CantidadRecibida * -1;
	ELSE
		SET @CantidadNew = NEW.CantidadRecibida * 1;
		SET @CantidadOld = OLD.CantidadRecibida * 1;
	END IF;
	SET @Ingresos = (SELECT SUM(td.CantidadRecibida)
						 FROM
							lg_transacciondetalle td
							INNER JOIN lg_transaccion t ON (t.CodOrganismo = td.CodOrganismo AND
															t.CodDocumento = td.CodDocumento AND
															t.NroDocumento = td.NroDocumento)
							INNER JOIN lg_tipotransaccion tt ON (t.CodTransaccion = tt.CodTransaccion)
						 WHERE
							t.CodAlmacen = @CodAlmacen AND
							td.CodItem = NEW.CodItem AND
							tt.TipoMovimiento = 'I');
	SET @Egresos = (SELECT SUM(td.CantidadRecibida)
						FROM
							lg_transacciondetalle td
							INNER JOIN lg_transaccion t ON (t.CodOrganismo = td.CodOrganismo AND
															t.CodDocumento = td.CodDocumento AND
															t.NroDocumento = td.NroDocumento)
							INNER JOIN lg_tipotransaccion tt ON (t.CodTransaccion = tt.CodTransaccion)
						WHERE
							t.CodAlmacen = @CodAlmacen AND
							td.CodItem = NEW.CodItem AND
							tt.TipoMovimiento = 'E');
	SET @StockActual = COALESCE(@Ingresos, 0) - COALESCE(@Egresos, 0);
	
	IF (NEW.Estado <> OLD.Estado AND NEW.Estado <> 'PR') THEN
		INSERT INTO lg_kardex
		SET
			CodItem = NEW.CodItem,
			CodAlmacen = @CodAlmacen,
			CodDocumento = NEW.CodDocumento,
			NroDocumento = NEW.NroDocumento,
			Secuencia = NEW.Secuencia,
			Fecha = @FechaDocumento,
			CodTransaccion = @CodTransaccion,
			ReferenciaAnio = NEW.ReferenciaAnio,
			ReferenciaCodOrganismo = NEW.CodOrganismo,
			ReferenciaCodDocumento = NEW.ReferenciaCodDocumento,
			ReferenciaNroDocumento = NEW.ReferenciaNroDocumento,
			ReferenciaSecuencia = NEW.ReferenciaSecuencia,
			Cantidad = @CantidadNew,
			PrecioUnitario = NEW.PrecioUnit,
			MontoTotal = NEW.Total,
			PeriodoContable = SUBSTRING(@FechaDocumento, 1, 7),
			UltimoUsuario = NEW.UltimoUsuario,
			UltimaFecha = NOW()
		ON DUPLICATE KEY UPDATE
			Fecha = @FechaDocumento,
			CodTransaccion = @CodTransaccion,
			ReferenciaAnio = NEW.ReferenciaAnio,
			ReferenciaCodOrganismo = NEW.CodOrganismo,
			ReferenciaCodDocumento = NEW.ReferenciaCodDocumento,
			ReferenciaNroDocumento = NEW.ReferenciaNroDocumento,
			ReferenciaSecuencia = NEW.ReferenciaSecuencia,
			Cantidad = @CantidadNew,
			PrecioUnitario = NEW.PrecioUnit,
			MontoTotal = NEW.Total,
			PeriodoContable = SUBSTRING(@FechaDocumento, 1, 7),
			UltimoUsuario = NEW.UltimoUsuario,
			UltimaFecha = NOW();
	ELSE
		DELETE FROM lg_kardex 
		WHERE
			CodAlmacen = @CodAlmacen AND 
			CodDocumento = NEW.CodDocumento AND 
			NroDocumento = NEW.NroDocumento;
	END IF;

	INSERT INTO lg_itemalmacen
	SET
		CodItem = NEW.CodItem,
		CodAlmacen = @CodAlmacen,
		Estado = 'A',
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW()
	ON DUPLICATE KEY UPDATE
		Estado = 'A';

	INSERT INTO lg_itemalmaceninv
	SET
		CodAlmacen = @CodAlmacen,
		CodItem = NEW.CodItem,
		FechaIngreso = NOW(),
		StockIngreso = @StockActual,
		StockActual = @StockActual,
		PrecioUnitario = NEW.PrecioUnit,
		DocReferencia = @DocumentoReferencia,
		IngresadoPor = @IngresadoPor,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW()
	ON DUPLICATE KEY UPDATE
		StockActual = @StockActual,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW();
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`lg_transacciondetalle_triger3`
AFTER DELETE ON `siaceda`.`lg_transacciondetalle`
FOR EACH ROW
BEGIN
	SET @CodAlmacen = (SELECT CodAlmacen FROM lg_transaccion WHERE CodOrganismo = OLD.CodOrganismo AND CodDocumento = OLD.CodDocumento AND NroDocumento = OLD.NroDocumento);
	SET @CodTransaccion = (SELECT CodTransaccion FROM lg_transaccion WHERE CodOrganismo = OLD.CodOrganismo AND CodDocumento = OLD.CodDocumento AND NroDocumento = OLD.NroDocumento);
	SET @TipoMovimiento = (SELECT TipoMovimiento FROM lg_tipotransaccion WHERE CodTransaccion = @CodTransaccion);
	IF (@TipoMovimiento = 'E') THEN
		SET @CantidadOld = OLD.CantidadRecibida * -1;
	ELSE
		SET @CantidadOld = OLD.CantidadRecibida * 1;
	END IF;
	SET @Ingresos = (SELECT SUM(td.CantidadRecibida)
					 FROM
						lg_transacciondetalle td
						INNER JOIN lg_transaccion t ON (t.CodOrganismo = td.CodOrganismo AND
														t.CodDocumento = td.CodDocumento AND
														t.NroDocumento = td.NroDocumento)
						INNER JOIN lg_tipotransaccion tt ON (t.CodTransaccion = tt.CodTransaccion)
					 WHERE
						t.CodAlmacen = @CodAlmacen AND
						td.CodItem = OLD.CodItem AND
						tt.TipoMovimiento = 'I');
	SET @Egresos = (SELECT SUM(td.CantidadRecibida)
					FROM
						lg_transacciondetalle td
						INNER JOIN lg_transaccion t ON (t.CodOrganismo = td.CodOrganismo AND
														t.CodDocumento = td.CodDocumento AND
														t.NroDocumento = td.NroDocumento)
						INNER JOIN lg_tipotransaccion tt ON (t.CodTransaccion = tt.CodTransaccion)
					WHERE
						t.CodAlmacen = @CodAlmacen AND
						td.CodItem = OLD.CodItem AND
						tt.TipoMovimiento = 'E');
	SET @StockActual = COALESCE(@Ingresos, 0) - COALESCE(@Egresos, 0);

	IF (OLD.Estado <> 'PR') THEN
		DELETE FROM lg_kardex 
		WHERE 
			CodAlmacen = @CodAlmacen AND 
			CodDocumento = OLD.CodDocumento AND 
			NroDocumento = OLD.NroDocumento;
	END IF;

	INSERT INTO lg_itemalmaceninv
	SET
		CodAlmacen = @CodAlmacen,
		CodItem = OLD.CodItem,
		FechaIngreso = NOW(),
		StockIngreso = @StockActual,
		StockActual = @StockActual,
		PrecioUnitario = OLD.PrecioUnit,
		DocReferencia = @DocumentoReferencia,
		IngresadoPor = @IngresadoPor,
		UltimoUsuario = '',
		UltimaFecha = NOW()
	ON DUPLICATE KEY UPDATE
		StockActual = @StockActual,
		UltimoUsuario = '',
		UltimaFecha = NOW();
END$$


INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('NOMINA', '02', '02-0031', 'Pago de Vacaciones', 'A', 'EJBOLIVAR', '2013-02-26');
-- 2013-02-28
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `Explicacion`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('FIRMARHPR', 'T', '000080', 'A', 'CODIGO DE LA PERSONA QUE REVISA LOS PROCESOS DE NOMINA', 'CODIGO UTILIZADO PARA OBTENER LOS DATOS E IMPRIMIRLOS EN LOS REPORTES DE NOMINA COMO PERSONA QUE REVISA', '0001', 'GE', 'EJBOLIVAR', '2013-02-28');
UPDATE `siaceda`.`rh_nivelgradoinstruccion` SET `AbreviaturaM`='M.Sc.', `AbreviaturaF`='M.Sc.' WHERE `CodGradoInstruccion`='POS' and`Nivel`='02';
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('DEPMAXORG', 'T', '0001', 'A', 'CODIGO DE LA DEPENDENCIA DE LA MAXIMA AUTORIDAD DEL ORGANISMO', '0001', 'GE', 'EJBOLIVAR', '2013-02-28');
ALTER TABLE `siaceda`.`lg_cotizacion` ADD COLUMN `FlagMejorPrecio` VARCHAR(1) NOT NULL DEFAULT 'N'  AFTER `NumeroInterno` ;
