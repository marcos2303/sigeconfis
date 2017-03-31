-- 2013-04-08
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
		SaldoActual = @Sum,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW()
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
		SaldoActual = @Sum,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW()
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
		MontoTransaccion = OLD.Monto,
		SaldoAnterior = SaldoActual,
		SaldoActual = @Sum,
		UltimoUsuario = '',
		UltimaFecha = NOW()
	WHERE NroCuenta = OLD.NroCuenta;
END$$
-- -----------------------------------
-- Trigger DDL Statements
DELIMITER $$

USE `siaceda`$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ap_ctabancaria_triger1`
AFTER INSERT ON `siaceda`.`ap_ctabancaria`
FOR EACH ROW
BEGIN
	INSERT INTO ap_ctabancariabalance
	SET
		NroCuenta = NEW.NroCuenta,
		FechaTransaccion = NEW.FechaApertura,
		MontoTransaccion = 0.00,
		SaldoAnterior = 0.00,
		SaldoActual = 0.00,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW();
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ap_ctabancaria_triger2`
AFTER UPDATE ON `siaceda`.`ap_ctabancaria`
FOR EACH ROW
BEGIN
	UPDATE ap_ctabancariabalance
	SET
		NroCuenta = NEW.NroCuenta,
		UltimoUsuario = NEW.UltimoUsuario,
		UltimaFecha = NOW()
	WHERE NroCuenta = OLD.NroCuenta;
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`ap_ctabancaria_triger3`
AFTER DELETE ON `siaceda`.`ap_ctabancaria`
FOR EACH ROW
BEGIN
	DELETE FROM ap_ctabancariabalance WHERE NroCuenta = OLD.NroCuenta;
END$$
-- -----------------------------------
-- 2013-04-10
ALTER TABLE `siaceda`.`ap_obligaciones` ADD COLUMN `FlagNomina` VARCHAR(1) NOT NULL DEFAULT 'N'  AFTER `CodPresupuesto` ;
ALTER TABLE `siaceda`.`lg_commoditytransacciondetalle` CHANGE COLUMN `ReferenciaNroDocumento` `ReferenciaNroDocumento` VARCHAR(20) NOT NULL  ;
-- 2013-04-11
ALTER TABLE `siaceda`.`lg_transacciondetalle` ADD COLUMN `ReferenciaNroInterno` VARCHAR(20) NOT NULL  AFTER `ReferenciaSecuencia` ;
ALTER TABLE `siaceda`.`lg_commoditytransacciondetalle` CHANGE COLUMN `ReferenciaNroDocumento` `ReferenciaNroDocumento` VARCHAR(10) NOT NULL  , ADD COLUMN `ReferenciaNroInterno` VARCHAR(20) NOT NULL  AFTER `ReferenciaNroDocumento` ;
ALTER TABLE `siaceda`.`lg_commoditytransacciondetalle` ADD COLUMN `CodUnidadCompra` VARCHAR(3) NULL  AFTER `Total` , ADD COLUMN `CantidadCompra` DECIMAL(11,2) NULL  AFTER `CodUnidadCompra` , ADD COLUMN `PrecioUnitCompra` DECIMAL(11,2) NULL  AFTER `CantidadCompra` ;
-- 2013-04-16
ALTER TABLE `siaceda`.`mastempleado` ADD COLUMN `FechaIngresoAnt` DATE NULL  AFTER `CodHorario` ;
CREATE TABLE `rh_procesocesereing` (
  `Tipo` varchar(1) NOT NULL DEFAULT 'C' COMMENT 'C:CESE; R:REINGRESO',
  `CodProceso` varchar(6) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Periodo` varchar(7) NOT NULL,
  `Anio` year(4) NOT NULL,
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
-- 2013-04-17
ALTER TABLE `siaceda`.`pr_procesoperiodo` CHANGE COLUMN `UltimaFecha` `UltimaFecha` DATETIME NOT NULL  ;
-- 2013-04-22
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('ORGANISMO', 'Organismo del empleado', 'A');
UPDATE `siaceda`.`pr_variables` SET `Variable`='PERIODO' WHERE `CodVariable`='11';
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('PROCESO', 'Tipo de proceso', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('DESDE', 'Fecha Desde del proceso', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('HASTA', 'Fecha hasta del proceso', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('DIAS_PROCESO', 'Dias total del proceso', 'A');
UPDATE `siaceda`.`pr_variables` SET `Variable`='DIAS' WHERE `CodVariable`='18';
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('PERSONA', 'Código de la persona', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('EMPLEADO', 'Código del empleado', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('TIPO_CONCEPTO', 'Tipo de concepto (I:INGRESO; P:PROVISION; D:DESCUENTO; A:APORTE;)', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('CTA_BANCARIA', 'Nro. de cuenta bancaria', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('BANCO', 'Código del banco', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('TIPO_CUENTA', 'Tipo de cuenta bancaria', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('ESTADO', 'Estado del empleado (A:ACTIVO; I:INACTIVO;)', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('FECHA_EGRESO', 'Fecha de egreso del empleado', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('ANO_EGRESO', 'Año de egreso del empleado', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('MES_EGRESO', 'Mes de egreso del empleado', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('DIA_EGRESO', 'Dia de egreso del empleado', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('ESTADO', 'Estado del empleado', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('TIPO_PAGO', 'Tipo de pago del empleado', 'A');
-- -----------------------------------
-- 2013-04-10
-- Trigger DDL Statements
DELIMITER $$

USE `siaceda`$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`pr_acumuladofideicomisodetalle_triger1`
AFTER INSERT ON `siaceda`.`pr_acumuladofideicomisodetalle`
FOR EACH ROW
BEGIN
	SET @Transaccion = (SELECT SUM(Transaccion) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona);
	SET @TransaccionFide = (SELECT SUM(TransaccionFide) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona);
	SET @Complemento = (SELECT SUM(Complemento) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona);
	SET @Dias = (SELECT SUM(Dias) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona);
	SET @DiasAdicional = (SELECT SUM(DiasAdicional) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona);

	UPDATE pr_acumuladofideicomiso
	SET
		AcumuladoProv = @Transaccion + @Complemento,
		AcumuladoFide = @TransaccionFide,
		AcumuladoProvDias = @Dias,
		AcumuladoDiasAdicional = @DiasAdicional
	WHERE CodPersona = NEW.CodPersona;
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`pr_acumuladofideicomisodetalle_triger2`
AFTER UPDATE ON `siaceda`.`pr_acumuladofideicomisodetalle`
FOR EACH ROW
BEGIN
	SET @Transaccion = (SELECT SUM(Transaccion) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona);
	SET @TransaccionFide = (SELECT SUM(TransaccionFide) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona);
	SET @Complemento = (SELECT SUM(Complemento) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona);
	SET @Dias = (SELECT SUM(Dias) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona);
	SET @DiasAdicional = (SELECT SUM(DiasAdicional) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona);

	UPDATE pr_acumuladofideicomiso
	SET
		AcumuladoProv = @Transaccion + @Complemento,
		AcumuladoFide = @TransaccionFide,
		AcumuladoProvDias = @Dias,
		AcumuladoDiasAdicional = @DiasAdicional
	WHERE CodPersona = NEW.CodPersona;
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`pr_acumuladofideicomisodetalle_triger3`
AFTER DELETE ON `siaceda`.`pr_acumuladofideicomisodetalle`
FOR EACH ROW
BEGIN
	SET @Transaccion = (SELECT SUM(Transaccion) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = OLD.CodPersona);
	SET @TransaccionFide = (SELECT SUM(TransaccionFide) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = OLD.CodPersona);
	SET @Complemento = (SELECT SUM(Complemento) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = OLD.CodPersona);
	SET @Dias = (SELECT SUM(Dias) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = OLD.CodPersona);
	SET @DiasAdicional = (SELECT SUM(DiasAdicional) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = OLD.CodPersona);

	UPDATE pr_acumuladofideicomiso
	SET
		AcumuladoProv = @Transaccion + @Complemento,
		AcumuladoFide = @TransaccionFide,
		AcumuladoProvDias = @Dias,
		AcumuladoDiasAdicional = @DiasAdicional
	WHERE CodPersona = OLD.CodPersona;
END$$
-- -----------------------------------
-- 2013-04-25
ALTER TABLE `siaceda`.`rh_sueldos` DROP COLUMN `Secuencia` , DROP PRIMARY KEY , ADD PRIMARY KEY (`CodPersona`, `Periodo`) ;
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('SUELDO_INTEGRAL', 'Sueldo integral del trabajador', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('SUELDO_INTEGRAL_DIARIO', 'Sueldo integral diario del trabajador', 'A');
DELETE FROM `siaceda`.`pr_funciones` WHERE `CodFuncion`='19';
UPDATE `siaceda`.`pr_funciones` SET `Funcion`='DIAS_ANTIGUEDAD_TRIMESTRAL', `Descripcion`='DIAS A PAGAR EN BASE AL TRIMESTRE PARA EL DEPOSITO DE ANTIGUEDAD' WHERE `CodFuncion`='30';
-- 2013-04-26
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('DIAS_ANTIGUEDAD_TRIMESTRAL', 'NUMERO DE DIAS DE ANTIGUEDAD', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('ULTIMA_REMUNERACION_DIARIA', 'REMUNERACION DIARIA DEL TRABAJADOR (SUELDO + BONOS) DEL ULTIMO PERIODO ANTERIOR', 'A');
-- 2013-04-29
ALTER TABLE `siaceda`.`pr_funciones` CHANGE COLUMN `Funcion` `Funcion` VARCHAR(50) NOT NULL  ;
ALTER TABLE `siaceda`.`pr_variables` CHANGE COLUMN `Variable` `Variable` VARCHAR(50) NOT NULL  ;
UPDATE `siaceda`.`pr_funciones` SET `Funcion`='ULTIMA_ALICUOTA_VACACIONAL' WHERE `CodFuncion`='22';
UPDATE `siaceda`.`pr_funciones` SET `Funcion`='ULTIMO_SUELDO_NORMAL_DIARIO' WHERE `CodFuncion`='21';
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('SUELDO_NORMAL', 'SUELDO NORMAL', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('SUELDO_NORMAL_DIARIO', 'SUELDO NORMAL DIARIO', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('CONCEPTO', 'MONTO DEL CONCEPTO (ARGS: CODIGO DEL CONCEPTO)', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('ULTIMO_CONCEPTO', 'ULTIMO MONTO DEL CONCEPTO (ARGS: CODIGO DEL CONCEPTO)', 'A');
ALTER TABLE `siaceda`.`pr_acumuladofideicomisodetalle` ADD COLUMN `FlagFraccionado` VARCHAR(1) NOT NULL DEFAULT 'N'  AFTER `Complemento` ;
-- -----------------------------------
DROP TRIGGER pr_acumuladofideicomisodetalle_triger1;
DROP TRIGGER pr_acumuladofideicomisodetalle_triger2;
DROP TRIGGER pr_acumuladofideicomisodetalle_triger3;
-- Trigger DDL Statements
DELIMITER $$

USE `siaceda`$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`pr_acumuladofideicomisodetalle_triger1`
AFTER INSERT ON `siaceda`.`pr_acumuladofideicomisodetalle`
FOR EACH ROW
BEGIN
	SET @Transaccion = (SELECT SUM(Transaccion) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona AND FlagFraccionado = 'N');
	SET @TransaccionFide = (SELECT SUM(TransaccionFide) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona AND FlagFraccionado = 'N');
	SET @Complemento = (SELECT SUM(Complemento) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona AND FlagFraccionado = 'N');
	SET @Dias = (SELECT SUM(Dias) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona AND FlagFraccionado = 'N');
	SET @DiasAdicional = (SELECT SUM(DiasAdicional) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona AND FlagFraccionado = 'N');

	UPDATE pr_acumuladofideicomiso
	SET
		AcumuladoProv = @Transaccion + @Complemento,
		AcumuladoFide = @TransaccionFide,
		AcumuladoProvDias = @Dias,
		AcumuladoDiasAdicional = @DiasAdicional
	WHERE CodPersona = NEW.CodPersona;
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`pr_acumuladofideicomisodetalle_triger2`
AFTER UPDATE ON `siaceda`.`pr_acumuladofideicomisodetalle`
FOR EACH ROW
BEGIN
	SET @Transaccion = (SELECT SUM(Transaccion) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona AND FlagFraccionado = 'N');
	SET @TransaccionFide = (SELECT SUM(TransaccionFide) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona AND FlagFraccionado = 'N');
	SET @Complemento = (SELECT SUM(Complemento) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona AND FlagFraccionado = 'N');
	SET @Dias = (SELECT SUM(Dias) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona AND FlagFraccionado = 'N');
	SET @DiasAdicional = (SELECT SUM(DiasAdicional) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = NEW.CodPersona AND FlagFraccionado = 'N');

	UPDATE pr_acumuladofideicomiso
	SET
		AcumuladoProv = @Transaccion + @Complemento,
		AcumuladoFide = @TransaccionFide,
		AcumuladoProvDias = @Dias,
		AcumuladoDiasAdicional = @DiasAdicional
	WHERE CodPersona = NEW.CodPersona;
END$$

CREATE
DEFINER=`root`@`localhost`
TRIGGER `siaceda`.`pr_acumuladofideicomisodetalle_triger3`
AFTER DELETE ON `siaceda`.`pr_acumuladofideicomisodetalle`
FOR EACH ROW
BEGIN
	SET @Transaccion = (SELECT SUM(Transaccion) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = OLD.CodPersona AND FlagFraccionado = 'N');
	SET @TransaccionFide = (SELECT SUM(TransaccionFide) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = OLD.CodPersona AND FlagFraccionado = 'N');
	SET @Complemento = (SELECT SUM(Complemento) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = OLD.CodPersona AND FlagFraccionado = 'N');
	SET @Dias = (SELECT SUM(Dias) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = OLD.CodPersona AND FlagFraccionado = 'N');
	SET @DiasAdicional = (SELECT SUM(DiasAdicional) FROM pr_acumuladofideicomisodetalle WHERE CodPersona = OLD.CodPersona AND FlagFraccionado = 'N');

	UPDATE pr_acumuladofideicomiso
	SET
		AcumuladoProv = @Transaccion + @Complemento,
		AcumuladoFide = @TransaccionFide,
		AcumuladoProvDias = @Dias,
		AcumuladoDiasAdicional = @DiasAdicional
	WHERE CodPersona = OLD.CodPersona;
END$$
-- -----------------------------------