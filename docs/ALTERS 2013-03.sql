-- 2013-03-01
ALTER TABLE `siaceda`.`lg_cotizacion` ADD COLUMN `FlagUnidadCompra` VARCHAR(1) NOT NULL  AFTER `FlagMejorPrecio` , ADD COLUMN `CodUnidadCompra` varchar(3) NOT NULL  AFTER `FlagUnidadCompra` , ADD COLUMN `CantidadCompra` DECIMAL(11,2) NOT NULL  AFTER `CodUnidadCompra` ;
-- 2013-03-05
-- Trigger DDL Statements
DELIMITER $$

USE `siaceda`$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`lg_distribucioncompromisos_triger1`
AFTER INSERT ON `siaceda`.`lg_distribucioncompromisos`
FOR EACH ROW
BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM lg_distribucioncompromisos
				  WHERE
						CodOrganismo = NEW.CodOrganismo AND
						CodPresupuesto = NEW.CodPresupuesto AND
						cod_partida = NEW.cod_partida AND
						Estado = 'CO');

	UPDATE pv_presupuestodet
	SET MontoCompromiso = @Monto
	WHERE
		Organismo = NEW.CodOrganismo AND
		CodPresupuesto = NEW.CodPresupuesto AND
		cod_partida = NEW.cod_partida;
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`lg_distribucioncompromisos_triger2`
AFTER UPDATE ON `siaceda`.`lg_distribucioncompromisos`
FOR EACH ROW
BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM lg_distribucioncompromisos
				  WHERE
						CodOrganismo = NEW.CodOrganismo AND
						CodPresupuesto = NEW.CodPresupuesto AND
						cod_partida = NEW.cod_partida AND
						Estado = 'CO');

	UPDATE pv_presupuestodet
	SET MontoCompromiso = @Monto
	WHERE
		Organismo = NEW.CodOrganismo AND
		CodPresupuesto = NEW.CodPresupuesto AND
		cod_partida = NEW.cod_partida;
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`lg_distribucioncompromisos_triger3`
AFTER DELETE ON `siaceda`.`lg_distribucioncompromisos`
FOR EACH ROW
BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM lg_distribucioncompromisos
				  WHERE
						CodOrganismo = OLD.CodOrganismo AND
						CodPresupuesto = OLD.CodPresupuesto AND
						cod_partida = OLD.cod_partida AND
						Estado = 'CO');

	UPDATE pv_presupuestodet
	SET MontoCompromiso = @Monto
	WHERE
		Organismo = OLD.CodOrganismo AND
		CodPresupuesto = OLD.CodPresupuesto AND
		cod_partida = OLD.cod_partida;
END$$
-- ----------------------------------------------
-- Trigger DDL Statements
DELIMITER $$

USE `siaceda`$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ap_distribucionobligacion_triger1`
AFTER INSERT ON `siaceda`.`ap_distribucionobligacion`
FOR EACH ROW
BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM ap_distribucionobligacion
				  WHERE
						CodOrganismo = NEW.CodOrganismo AND
						CodPresupuesto = NEW.CodPresupuesto AND
						cod_partida = NEW.cod_partida AND
						Estado = 'CA');

	UPDATE pv_presupuestodet
	SET MontoCausado = @Monto
	WHERE
		Organismo = NEW.CodOrganismo AND
		CodPresupuesto = NEW.CodPresupuesto AND
		cod_partida = NEW.cod_partida;
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ap_distribucionobligacion_triger2`
AFTER UPDATE ON `siaceda`.`ap_distribucionobligacion`
FOR EACH ROW
BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM ap_distribucionobligacion
				  WHERE
						CodOrganismo = NEW.CodOrganismo AND
						CodPresupuesto = NEW.CodPresupuesto AND
						cod_partida = NEW.cod_partida AND
						Estado = 'CA');

	UPDATE pv_presupuestodet
	SET MontoCausado = @Monto
	WHERE
		Organismo = NEW.CodOrganismo AND
		CodPresupuesto = NEW.CodPresupuesto AND
		cod_partida = NEW.cod_partida;
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ap_distribucionobligacion_triger3`
AFTER DELETE ON `siaceda`.`ap_distribucionobligacion`
FOR EACH ROW
BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM ap_distribucionobligacion
				  WHERE
						CodOrganismo = OLD.CodOrganismo AND
						CodPresupuesto = OLD.CodPresupuesto AND
						cod_partida = OLD.cod_partida AND
						Estado = 'CA');

	UPDATE pv_presupuestodet
	SET MontoCausado = @Monto
	WHERE
		Organismo = OLD.CodOrganismo AND
		CodPresupuesto = OLD.CodPresupuesto AND
		cod_partida = OLD.cod_partida;
END$$
-- ----------------------------------------------
-- Trigger DDL Statements
DELIMITER $$

USE `siaceda`$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ap_ordenpagodistribucion_triger1`
AFTER INSERT ON `siaceda`.`ap_ordenpagodistribucion`
FOR EACH ROW
BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM ap_ordenpagodistribucion
				  WHERE
						CodOrganismo = NEW.CodOrganismo AND
						CodPresupuesto = NEW.CodPresupuesto AND
						cod_partida = NEW.cod_partida AND
						Estado = 'PA');

	UPDATE pv_presupuestodet
	SET MontoPagado = @Monto
	WHERE
		Organismo = NEW.CodOrganismo AND
		CodPresupuesto = NEW.CodPresupuesto AND
		cod_partida = NEW.cod_partida;
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ap_ordenpagodistribucion_triger2`
AFTER UPDATE ON `siaceda`.`ap_ordenpagodistribucion`
FOR EACH ROW
BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM ap_ordenpagodistribucion
				  WHERE
						CodOrganismo = NEW.CodOrganismo AND
						CodPresupuesto = NEW.CodPresupuesto AND
						cod_partida = NEW.cod_partida AND
						Estado = 'PA');

	UPDATE pv_presupuestodet
	SET MontoPagado = @Monto
	WHERE
		Organismo = NEW.CodOrganismo AND
		CodPresupuesto = NEW.CodPresupuesto AND
		cod_partida = NEW.cod_partida;
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ap_ordenpagodistribucion_triger3`
AFTER DELETE ON `siaceda`.`ap_ordenpagodistribucion`
FOR EACH ROW
BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM ap_ordenpagodistribucion
				  WHERE
						CodOrganismo = OLD.CodOrganismo AND
						CodPresupuesto = OLD.CodPresupuesto AND
						cod_partida = OLD.cod_partida AND
						Estado = 'PA');

	UPDATE pv_presupuestodet
	SET MontoPagado = @Monto
	WHERE
		Organismo = OLD.CodOrganismo AND
		CodPresupuesto = OLD.CodPresupuesto AND
		cod_partida = OLD.cod_partida;
END$$
-- ----------------------------------------------
-- 2013-03-11
ALTER TABLE `siaceda`.`ap_bancotransaccion` ADD COLUMN `VoucherPeriodoPub20` VARCHAR(7) NULL  AFTER `Voucher` , ADD COLUMN `VoucherPub20` VARCHAR(45) NULL  AFTER `VoucherPeriodoPub20` ;
ALTER TABLE `siaceda`.`lg_ordencompradetalle` ADD COLUMN `CodUnidadRec` VARCHAR(3) NOT NULL  AFTER `ClienteNroPedido` , ADD COLUMN `CantidadRec` DECIMAL(11,2) NOT NULL  AFTER `CodUnidadRec` ;
ALTER TABLE `siaceda`.`lg_ordenserviciodetalle` ADD COLUMN `CodUnidadRec` VARCHAR(3) NOT NULL  AFTER `Comentarios` , ADD COLUMN `CantidadRec` DECIMAL(11,2) NOT NULL  AFTER `CodUnidadRec` ;
-- 2013-03-12
ALTER TABLE `siaceda`.`lg_transacciondetalle` ADD COLUMN `CodUnidadCompra` VARCHAR(3) NOT NULL  AFTER `Total` , ADD COLUMN `CantidadCompra` DECIMAL(11,2) NOT NULL  AFTER `CodUnidadCompra` , ADD COLUMN `PrecioUnitCompra` DECIMAL(11,2) NOT NULL  AFTER `CantidadCompra` ;
-- 2013-03-13
INSERT INTO `seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES
('RH', '05', '05-0014', 'Nuevo Cese/Reingreso', 'A', 'EJBOLIVAR', '2013-03-13'),
('RH', '05', '05-0015', 'Listar Cese/Reingreso', 'A', 'EJBOLIVAR', '2013-03-13'),
('RH', '05', '05-0016', 'Conformar Cese/Reingreso', 'A', 'EJBOLIVAR', '2013-03-13'),
('RH', '05', '05-0017', 'Aprobar Cese/Reingreso', 'A', 'EJBOLIVAR', '2013-03-13');
-- ----------------------------------------------
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
	SET @SaldoBalActual = COALESCE((SELECT SaldoBalActual
								    FROM ac_voucherbalance
								    WHERE
										CodOrganismo = NEW.CodOrganismo AND
										Periodo < NEW.Periodo AND
										CodCuenta = NEW.CodCuenta AND
										CodContabilidad = NEW.CodContabilidad
								    ORDER BY Periodo DESC
								    LIMIT 0, 1)) + @SaldoBalance;

	INSERT INTO ac_voucherbalance
	SET
		CodOrganismo = NEW.CodOrganismo,
		Periodo = NEW.Periodo,
		CodCuenta = NEW.CodCuenta,
		CodContabilidad = NEW.CodContabilidad,
		SaldoBalance = @SaldoBalance,
		SaldoBalActual = @SaldoBalActual,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW()
	ON DUPLICATE KEY UPDATE
		SaldoBalance = @SaldoBalance,
		SaldoBalActual = @SaldoBalActual,
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
	SET @SaldoBalActual = COALESCE((SELECT SaldoBalActual
								    FROM ac_voucherbalance
								    WHERE
										CodOrganismo = NEW.CodOrganismo AND
										Periodo < NEW.Periodo AND
										CodCuenta = NEW.CodCuenta AND
										CodContabilidad = NEW.CodContabilidad
								    ORDER BY Periodo DESC
								    LIMIT 0, 1)) + @SaldoBalance;

	INSERT INTO ac_voucherbalance
	SET
		CodOrganismo = NEW.CodOrganismo,
		Periodo = NEW.Periodo,
		CodCuenta = NEW.CodCuenta,
		CodContabilidad = NEW.CodContabilidad,
		SaldoBalance = @SaldoBalance,
		SaldoBalActual = @SaldoBalActual,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW()
	ON DUPLICATE KEY UPDATE
		SaldoBalance = @SaldoBalance,
		SaldoBalActual = @SaldoBalActual,
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
		SET @Sum = (SELECT SUM(MontoVoucher)
					FROM ac_voucherdet
					WHERE
						CodOrganismo = OLD.CodOrganismo AND
						Periodo = OLD.Periodo AND
						CodContabilidad = OLD.CodContabilidad AND
						CodCuenta = OLD.CodCuenta AND
						Estado = 'MA');
		SET @SaldoBalance = COALESCE(@Sum, 0);
		SET @SaldoBalActual = COALESCE((SELECT SaldoBalActual
										FROM ac_voucherbalance
										WHERE
											CodOrganismo = OLD.CodOrganismo AND
											Periodo < OLD.Periodo AND
											CodCuenta = OLD.CodCuenta AND
											CodContabilidad = OLD.CodContabilidad
										ORDER BY Periodo DESC
										LIMIT 0, 1)) + @SaldoBalance;

		INSERT INTO ac_voucherbalance
		SET
			CodOrganismo = OLD.CodOrganismo,
			Periodo = OLD.Periodo,
			CodCuenta = OLD.CodCuenta,
			CodContabilidad = OLD.CodContabilidad,
			SaldoBalance = @SaldoBalance,
			SaldoBalActual = @SaldoBalActual,
			UltimaFecha = NOW()
		ON DUPLICATE KEY UPDATE
			SaldoBalance = @SaldoBalance,
			SaldoBalActual = @SaldoBalActual,
			UltimaFecha = NOW();
	END IF;
END$$
-- ----------------------------------------------
-- 2013-03-14
-- Trigger DDL Statements
DELIMITER $$

USE `siaceda`$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ap_bancotransaccion_triger1`
AFTER INSERT ON `siaceda`.`ap_bancotransaccion`
FOR EACH ROW
BEGIN
	SET @Sum = (SELECT SUM(Monto)
				FROM ap_bancotransaccion
				WHERE
					NroCuenta = NEW.NroCuenta AND
					(Estado = 'AP' OR Estado = 'CO'));
	
	UPDATE ap_ctabancariabalance
	SET
		MontoTransaccion = NEW.Monto,
		SaldoAnterior = SaldoActual,
		SaldoActual = @Sum
	WHERE NroCuenta = NEW.NroCuenta;
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ap_bancotransaccion_triger2`
AFTER UPDATE ON `siaceda`.`ap_bancotransaccion`
FOR EACH ROW
BEGIN
	SET @Sum = (SELECT SUM(Monto)
				FROM ap_bancotransaccion
				WHERE
					NroCuenta = NEW.NroCuenta AND
					(Estado = 'AP' OR Estado = 'CO'));
	
	UPDATE ap_ctabancariabalance
	SET
		MontoTransaccion = NEW.Monto,
		SaldoAnterior = SaldoActual,
		SaldoActual = @Sum
	WHERE NroCuenta = NEW.NroCuenta;
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ap_bancotransaccion_triger3`
AFTER DELETE ON `siaceda`.`ap_bancotransaccion`
FOR EACH ROW
BEGIN
	SET @Sum = (SELECT SUM(Monto)
				FROM ap_bancotransaccion
				WHERE
					NroCuenta = OLD.NroCuenta AND
					(Estado = 'AP' OR Estado = 'CO'));
	
	UPDATE ap_ctabancariabalance
	SET
		MontoTransaccion = OL.Monto,
		SaldoAnterior = SaldoActual,
		SaldoActual = @Sum
	WHERE NroCuenta = OLD.NroCuenta;
END$$
-- ----------------------------------------------
DROP procedure IF EXISTS `SaldoBalActual`;
-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE DEFINER=`admin`@`%` PROCEDURE `SaldoBalActual`(IN iCodOrganismo VARCHAR(4), IN iPeriodo VARCHAR(7), IN iCodContabilidad VARCHAR(1), IN iCodCuenta VARCHAR(13))
BEGIN
	SET @Sum = (SELECT SUM(MontoVoucher)
				FROM ac_voucherdet
				WHERE
					CodOrganismo = iCodOrganismo AND
					Periodo = iPeriodo AND
					CodContabilidad = iCodContabilidad AND
					CodCuenta = iCodCuenta AND
					Estado = 'MA');
	SET @SaldoBalance = COALESCE(@Sum, 0);
	SET @SaldoBalActual = COALESCE((SELECT SaldoBalActual
								    FROM ac_voucherbalance
								    WHERE
										CodOrganismo = iCodOrganismo AND
										Periodo < iPeriodo AND
										CodContabilidad = iCodContabilidad AND
										CodCuenta = iCodCuenta
								    ORDER BY Periodo DESC
								    LIMIT 0, 1), 0) + @SaldoBalance;

	UPDATE ac_voucherbalance
	SET SaldoBalActual = @SaldoBalActual
	WHERE
		CodOrganismo = iCodOrganismo AND
		Periodo = iPeriodo AND
		CodContabilidad = iCodContabilidad AND
		CodCuenta = iCodCuenta;
END;
-- ----------------------------------------------
CREATE TABLE `ac_balancecuenta` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Anio` year(4) NOT NULL,
  `CodContabilidad` varchar(1) NOT NULL COMMENT 'ac_contabilidades->CodContabilidad',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `SaldoInicial` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SaldoFinal` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SaldoBalance01` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SaldoBalance02` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SaldoBalance03` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SaldoBalance04` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SaldoBalance05` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SaldoBalance06` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SaldoBalance07` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SaldoBalance08` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SaldoBalance09` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SaldoBalance10` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SaldoBalance11` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SaldoBalance12` decimal(11,2) NOT NULL DEFAULT '0.00',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Anio`,`CodContabilidad`,`CodCuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- 2013-03-15
ALTER TABLE `siaceda`.`mastparametros` CHANGE COLUMN `ValorParam` `ValorParam` LONGTEXT NOT NULL DEFAULT ''  ;
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('COMENTVACGEN', 'T', 'CANCELACIÓN 105 DE DÍAS DE BONO VACACIONAL DE CONFORMIDAD CON LO ESTABLECIDO EN EL RESUELVE ÚNICO DE LA RESOLUCIÓN CEDA-017-A-2006, DE FECHA 23-02-2006 (CON CARÁCTER RETROACTIVO A PARTIR DE FECHA 01-01-2006).', 'A', 'COMENTARIOS FORMATO DE REPORTE DE PAGO DE VACACIONES', '0001', 'NOMINA', 'EJBOLIVAR', '2013-03-15');
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('COMENTVACCON', 'T', 'CANCELACIÓN 40 DE DÍAS DE BONO VACACIONAL DE CONFORMIDAD CON LO ESTABLECIDO EN EL ARTICULO 14 DE LA LEY ORGANICA DE EMOLUMENTOS PENSIONES Y JUBILACIONES DE LOS ALTOS FUNCIONARIOS Y ALTAS FUNCIONARIAS DEL PODER PUBLICO GACETA NRO. 39.592.', 'A', 'COMENTARIOS FORMATO DE REPORTE DE PAGO DE VACACIONES (CONTRALOR)', '0001', 'NOMINA', 'EJBOLIVAR', '2013-03-15');
--
INSERT INTO ac_balancecuenta (
	CodOrganismo,
	Anio,
	CodContabilidad,
	CodCuenta,
	SaldoInicial,
	SaldoBalance01,
	SaldoBalance02,
	SaldoBalance03,
	UltimoUsuario
)
SELECT
	vb.CodOrganismo,
	vb.Periodo,
	vb.CodContabilidad,
	vb.CodCuenta,
	vb.SaldoInicial,
	(SELECT SUM(SaldoBalance) FROM ac_voucherbalance WHERE CodOrganismo = vb.CodOrganismo AND Periodo = '2013-01' AND CodContabilidad = vb.CodContabilidad AND CodCuenta = vb.CodCuenta),
	(SELECT SUM(SaldoBalance) FROM ac_voucherbalance WHERE CodOrganismo = vb.CodOrganismo AND Periodo = '2013-02' AND CodContabilidad = vb.CodContabilidad AND CodCuenta = vb.CodCuenta),
	(SELECT SUM(SaldoBalance) FROM ac_voucherbalance WHERE CodOrganismo = vb.CodOrganismo AND Periodo = '2013-03' AND CodContabilidad = vb.CodContabilidad AND CodCuenta = vb.CodCuenta),
	'EJBOLIVAR'
FROM ac_voucherbalance vb
WHERE
	vb.CodOrganismo = '0001' AND
	vb.Periodo LIKE '2013-%' AND
	vb.CodContabilidad = 'F'
GROUP BY CodCuenta;
-- ----------------------------------------------
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
	SET @SaldoBalActual = COALESCE((SELECT SaldoBalActual
								    FROM ac_voucherbalance
								    WHERE
										CodOrganismo = NEW.CodOrganismo AND
										Periodo < NEW.Periodo AND
										CodCuenta = NEW.CodCuenta AND
										CodContabilidad = NEW.CodContabilidad
								    ORDER BY Periodo DESC
								    LIMIT 0, 1), 0) + @SaldoBalance;
	SET @Anio = SUBSTRING(NEW.Periodo, 1, 4);
	SET @Mes = SUBSTRING(NEW.Periodo, 6, 2);

	INSERT INTO ac_voucherbalance
	SET
		CodOrganismo = NEW.CodOrganismo,
		Periodo = NEW.Periodo,
		CodCuenta = NEW.CodCuenta,
		CodContabilidad = NEW.CodContabilidad,
		SaldoBalance = @SaldoBalance,
		SaldoBalActual = @SaldoBalActual,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW()
	ON DUPLICATE KEY UPDATE
		SaldoBalance = @SaldoBalance,
		SaldoBalActual = @SaldoBalActual,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW();

	INSERT INTO ac_balancecuenta
	SET
		CodOrganismo = NEW.CodOrganismo,
		Anio = NEW.Periodo,
		CodCuenta = NEW.CodCuenta,
		CodContabilidad = NEW.CodContabilidad,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW()
	ON DUPLICATE KEY UPDATE
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW();

	IF (@Mes = '01') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance01 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '02') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance02 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = NEW.Periodo AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '03') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance03 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '04') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance04 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '05') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance05 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '06') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance06 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '07') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance07 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '08') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance08 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '09') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance09 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '10') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance10 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '11') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance11 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '12') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance12 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;
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
	SET @SaldoBalActual = COALESCE((SELECT SaldoBalActual
								    FROM ac_voucherbalance
								    WHERE
										CodOrganismo = NEW.CodOrganismo AND
										Periodo < NEW.Periodo AND
										CodCuenta = NEW.CodCuenta AND
										CodContabilidad = NEW.CodContabilidad
								    ORDER BY Periodo DESC
								    LIMIT 0, 1), 0) + @SaldoBalance;
	SET @Anio = SUBSTRING(NEW.Periodo, 1, 4);
	SET @Mes = SUBSTRING(NEW.Periodo, 6, 2);

	INSERT INTO ac_voucherbalance
	SET
		CodOrganismo = NEW.CodOrganismo,
		Periodo = NEW.Periodo,
		CodCuenta = NEW.CodCuenta,
		CodContabilidad = NEW.CodContabilidad,
		SaldoBalance = @SaldoBalance,
		SaldoBalActual = @SaldoBalActual,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW()
	ON DUPLICATE KEY UPDATE
		SaldoBalance = @SaldoBalance,
		SaldoBalActual = @SaldoBalActual,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW();

	INSERT INTO ac_balancecuenta
	SET
		CodOrganismo = NEW.CodOrganismo,
		Anio = NEW.Periodo,
		CodCuenta = NEW.CodCuenta,
		CodContabilidad = NEW.CodContabilidad,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW()
	ON DUPLICATE KEY UPDATE
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW();

	IF (@Mes = '01') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance01 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '02') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance02 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = NEW.Periodo AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '03') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance03 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '04') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance04 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '05') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance05 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '06') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance06 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '07') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance07 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '08') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance08 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '09') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance09 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '10') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance10 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '11') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance11 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;

	IF (@Mes = '12') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance12 = @SaldoBalance
		WHERE
			CodOrganismo = NEW.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = NEW.CodCuenta AND
			CodContabilidad = NEW.CodContabilidad;
	END IF;
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ac_voucherdet_triger3`
AFTER DELETE ON `siaceda`.`ac_voucherdet`
FOR EACH ROW
BEGIN
	
	SET @Sum = (SELECT SUM(MontoVoucher)
				FROM ac_voucherdet
				WHERE
					CodOrganismo = OLD.CodOrganismo AND
					Periodo = OLD.Periodo AND
					CodContabilidad = OLD.CodContabilidad AND
					CodCuenta = OLD.CodCuenta AND
					Estado = 'MA');
	SET @SaldoBalance = COALESCE(@Sum, 0);
	SET @SaldoBalActual = COALESCE((SELECT SaldoBalActual
								    FROM ac_voucherbalance
								    WHERE
										CodOrganismo = OLD.CodOrganismo AND
										Periodo < OLD.Periodo AND
										CodCuenta = OLD.CodCuenta AND
										CodContabilidad = OLD.CodContabilidad
								    ORDER BY Periodo DESC
								    LIMIT 0, 1), 0) + @SaldoBalance;
	SET @Anio = SUBSTRING(OLD.Periodo, 1, 4);
	SET @Mes = SUBSTRING(OLD.Periodo, 6, 2);

	INSERT INTO ac_voucherbalance
	SET
		CodOrganismo = OLD.CodOrganismo,
		Periodo = OLD.Periodo,
		CodCuenta = OLD.CodCuenta,
		CodContabilidad = OLD.CodContabilidad,
		SaldoBalance = @SaldoBalance,
		SaldoBalActual = @SaldoBalActual,
		UltimoUsuario = OLD.UltimoUsuario,
		UltimaFecha = NOW()
	ON DUPLICATE KEY UPDATE
		SaldoBalance = @SaldoBalance,
		SaldoBalActual = @SaldoBalActual,
		UltimoUsuario = OLD.UltimoUsuario,
		UltimaFecha = NOW();

	INSERT INTO ac_balancecuenta
	SET
		CodOrganismo = OLD.CodOrganismo,
		Anio = OLD.Periodo,
		CodCuenta = OLD.CodCuenta,
		CodContabilidad = OLD.CodContabilidad,
		UltimoUsuario = OLD.UltimoUsuario,
		UltimaFecha = NOW()
	ON DUPLICATE KEY UPDATE
		UltimoUsuario = OLD.UltimoUsuario,
		UltimaFecha = NOW();

	IF (@Mes = '01') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance01 = @SaldoBalance
		WHERE
			CodOrganismo = OLD.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = OLD.CodCuenta AND
			CodContabilidad = OLD.CodContabilidad;
	END IF;

	IF (@Mes = '02') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance02 = @SaldoBalance
		WHERE
			CodOrganismo = OLD.CodOrganismo AND
			Anio = OLD.Periodo AND
			CodCuenta = OLD.CodCuenta AND
			CodContabilidad = OLD.CodContabilidad;
	END IF;

	IF (@Mes = '03') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance03 = @SaldoBalance
		WHERE
			CodOrganismo = OLD.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = OLD.CodCuenta AND
			CodContabilidad = OLD.CodContabilidad;
	END IF;

	IF (@Mes = '04') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance04 = @SaldoBalance
		WHERE
			CodOrganismo = OLD.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = OLD.CodCuenta AND
			CodContabilidad = OLD.CodContabilidad;
	END IF;

	IF (@Mes = '05') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance05 = @SaldoBalance
		WHERE
			CodOrganismo = OLD.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = OLD.CodCuenta AND
			CodContabilidad = OLD.CodContabilidad;
	END IF;

	IF (@Mes = '06') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance06 = @SaldoBalance
		WHERE
			CodOrganismo = OLD.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = OLD.CodCuenta AND
			CodContabilidad = OLD.CodContabilidad;
	END IF;

	IF (@Mes = '07') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance07 = @SaldoBalance
		WHERE
			CodOrganismo = OLD.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = OLD.CodCuenta AND
			CodContabilidad = OLD.CodContabilidad;
	END IF;

	IF (@Mes = '08') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance08 = @SaldoBalance
		WHERE
			CodOrganismo = OLD.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = OLD.CodCuenta AND
			CodContabilidad = OLD.CodContabilidad;
	END IF;

	IF (@Mes = '09') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance09 = @SaldoBalance
		WHERE
			CodOrganismo = OLD.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = OLD.CodCuenta AND
			CodContabilidad = OLD.CodContabilidad;
	END IF;

	IF (@Mes = '10') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance10 = @SaldoBalance
		WHERE
			CodOrganismo = OLD.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = OLD.CodCuenta AND
			CodContabilidad = OLD.CodContabilidad;
	END IF;

	IF (@Mes = '11') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance11 = @SaldoBalance
		WHERE
			CodOrganismo = OLD.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = OLD.CodCuenta AND
			CodContabilidad = OLD.CodContabilidad;
	END IF;

	IF (@Mes = '12') THEN
		UPDATE ac_balancecuenta
		SET SaldoBalance12 = @SaldoBalance
		WHERE
			CodOrganismo = OLD.CodOrganismo AND
			Anio = @Anio AND
			CodCuenta = OLD.CodCuenta AND
			CodContabilidad = OLD.CodContabilidad;
	END IF;
END$$
-- ----------------------------------------------
-- 18-03-2013
CREATE TABLE `rh_procesocesereing` (
  `Tipo` varchar(1) NOT NULL DEFAULT 'C' COMMENT 'C:CESE; R:REINGRESO',
  `CodProceso` varchar(6) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Periodo` varchar(7) NOT NULL,
  `Fecha` date NOT NULL,
  `AnioServicio` int(3) NOT NULL,
  `Edad` int(3) NOT NULL,
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `Dependencia` varchar(100) NOT NULL,
  `CodCargo` varchar(4) NOT NULL COMMENT 'rh_puestos->CodCargo',
  `DescripCargo` varchar(100) NOT NULL,
  `SueldoActual` decimal(11,2) NOT NULL,
  `FechaIngreso` date NOT NULL,
  `CodTipoNom` varchar(2) NOT NULL COMMENT 'tiponomina->CodTipoNom',
  `CodTipoTrabajador` varchar(2) NOT NULL COMMENT 'masttipotrabajador->CodTipoTrabajador',
  `CodMotivoCes` varchar(2) NOT NULL COMMENT 'rh_motivocese->CodMotivoCes',
  `FechaEgreso` date NOT NULL,
  `ObsCese` longtext NOT NULL,
  `SitTra` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `CreadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaCreado` datetime NOT NULL,
  `ObsCreado` longtext NOT NULL,
  `ConformadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaConformado` datetime NOT NULL,
  `ObsConformado` longtext NOT NULL,
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaAprobado` datetime NOT NULL,
  `ObsAprobado` longtext NOT NULL,
  `Estado` varchar(2) NOT NULL COMMENT 'PE:PENDIENTE; CN:CONFORMADO; AP:APROBADO; AN:ANULADO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Tipo`,`CodProceso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- 25-03-2013
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('PERIODO_PROCESO', 'Periodo del proceso', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('ANO_PROCESO', 'Año del proceso', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('MES_PROCESO', 'Mes del proceso', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`) VALUES ('REMUNERACION_DIARIA', 'REMUNERACION DIARIA DEL TRABAJADOR (SUELDO + BONOS)');
-- 26-03-2013
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`) VALUES ('DIAS_JERARQUIA_DIFERENCIA', 'NUMERO DE DIAS AL QUE SE LE APLICARA EL PORCENTAJE CORRESPONDIENTE DE LA JERARQUIA');
ALTER TABLE `siaceda`.`pr_funciones` CHANGE COLUMN `Funcion` `Funcion` VARCHAR(30) NOT NULL  ;
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`) VALUES ('TRIMESTRE', 'INDICA SI EL PERIODO DEL PROCESO CORRESPONDE A UN TRIMESTRE DEL AÑO (MARZO, JUNIO, SEPTIEMBRE, DICIEMBRE)');

