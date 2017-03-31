-- 2013-05-06
ALTER TABLE `siaceda`.`ap_obligaciones` ADD COLUMN `TipoObligacion` VARCHAR(2) NULL  AFTER `FlagNomina` , ADD INDEX `TipoObligacion` (`TipoObligacion` ASC) ;
ALTER TABLE `siaceda`.`ap_obligaciones` CHANGE COLUMN `NroControl` `NroControl` VARCHAR(25) NOT NULL  ;
ALTER TABLE `siaceda`.`pr_obligaciones` CHANGE COLUMN `NroControl` `NroControl` VARCHAR(25) NOT NULL  ;
-- 2013-05-07
-- -----------------------------------
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
		MontoTransaccion = OLD.Monto,
		SaldoAnterior = SaldoActual,
		SaldoActual = @Sum
	WHERE NroCuenta = OLD.NroCuenta;
END$$
-- -----------------------------------
ALTER TABLE `siaceda`.`pr_obligaciones` ADD COLUMN `FlagDistribucionManual` VARCHAR(1) NOT NULL DEFAULT 'N'  AFTER `FechaVerificado` ;
-- 2013-05-08
ALTER TABLE `siaceda`.`pr_procesoperiodo` ADD COLUMN `EstadoPago` VARCHAR(2) NOT NULL COMMENT 'PE:PENDIENTE; PA:PAGADO' DEFAULT 'PE'  AFTER `FlagPagado` ;
ALTER TABLE `siaceda`.`pr_tiponominaempleado` ADD COLUMN `EstadoPago` VARCHAR(2) NOT NULL COMMENT 'PE:PENDIENTE; PA:PAGADO' DEFAULT 'PE'  AFTER `GeneradoPor` ;
ALTER TABLE `siaceda`.`pr_tiponominaempleado` ADD COLUMN `CodTipoDocumento` VARCHAR(2) NULL COMMENT 'pr_obligaciones->CodTipoDocumento'  AFTER `GeneradoPor` , ADD COLUMN `NroDocumento` VARCHAR(25) NULL COMMENT 'pr_obligaciones->NroDocumento'  AFTER `CodTipoDocumento` , ADD COLUMN `FechaPago` DATE NULL  AFTER `NroDocumento` ;
ALTER TABLE `siaceda`.`rh_vacacionpago` CHANGE COLUMN `Concepto` `CodConcepto` VARCHAR(4) NOT NULL  , ADD COLUMN `CodTipoDocumento` VARCHAR(2) NULL  AFTER `Pasados` , ADD COLUMN `NroDocumento` VARCHAR(25) NULL  AFTER `CodTipoDocumento` , ADD COLUMN `FechaPago` DATE NULL  AFTER `NroDocumento` ;
ALTER TABLE `siaceda`.`pr_tiponominaempleado` ADD INDEX `Obligacion` (`CodTipoDocumento` ASC, `NroDocumento` ASC) ;
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('CONCEPTOBVC', 'T', '0063', 'A', 'CODIGO DEL CONCEPTO DE PAGO DE VACACIONES', '0001', 'GE', 'EJBOLIVAR', '2013-05-08');
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('PROCESOBVC', 'T', 'BVC', 'A', 'CODIGO DEL TIPO DE PROCESO PARA EL PAGO DE BONO VACACIONALES', '0001', 'GE', 'EJBOLIVAR', '2013-05-08');
ALTER TABLE `siaceda`.`rh_vacacionpago` CHANGE COLUMN `DiasPago` `DiasPago` DECIMAL(11,2) NOT NULL  ;
-- 2013-05-13
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('DIAS_VACACIONES_PENDIENTES', 'DIAS DE PAGO DE VACACIONES PENDIENTES', 'A');
-- 2013-05-14-------------------
DROP TABLE `siaceda`.`pr_empleadoconceptoprocesos`;
TRUNCATE TABLE pr_empleadoconcepto;
ALTER TABLE `siaceda`.`pr_empleadoconcepto` CHANGE COLUMN `UltimaFecha` `UltimaFecha` DATETIME NOT NULL  ;
ALTER TABLE `siaceda`.`pr_empleadoconcepto` DROP COLUMN `Secuencia` , DROP PRIMARY KEY , ADD PRIMARY KEY (`CodConcepto`, `CodPersona`) ;
ALTER TABLE `siaceda`.`pr_empleadoconcepto` CHANGE COLUMN `Procesos` `Procesos` VARCHAR(255) NOT NULL DEFAULT ''  AFTER `FlagTipoProceso`;
ALTER TABLE `siaceda`.`pr_empleadoconcepto` ADD COLUMN `FlagManual` VARCHAR(1) NOT NULL DEFAULT 'N'  AFTER `PeriodoHasta` ;
-- 2013-05-15
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('MESES_FRACCION', 'FRACCION DE LOS MESES DEL TIEMPO DE SERVICIO PARA LOS EGRESADOS', 'A');
-- 2013-05-16
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('RH', '03', '03-0058', 'Tabla de Disfrutes Vacacionales', 'A', 'EJBOLIVAR', '2013-05-16');
CREATE TABLE `rh_vacaciontabla` (
  `NroAnio` int(3) NOT NULL,
  `DiasDisfrutes` int(3) NOT NULL,
  `DiasAdicionales` int(3) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`NroAnio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (1,15,0,'EJBOLIVAR','2013-05-16 09:55:34');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (2,16,0,'EJBOLIVAR','2013-05-16 09:55:47');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (3,17,0,'EJBOLIVAR','2013-05-16 09:55:54');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (4,18,0,'EJBOLIVAR','2013-05-16 09:56:04');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (5,19,0,'EJBOLIVAR','2013-05-16 09:56:12');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (6,20,1,'EJBOLIVAR','2013-05-16 10:12:09');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (7,21,1,'EJBOLIVAR','2013-05-16 10:28:06');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (8,22,1,'EJBOLIVAR','2013-05-16 10:28:17');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (9,23,1,'EJBOLIVAR','2013-05-16 10:28:30');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (10,24,1,'EJBOLIVAR','2013-05-16 10:28:38');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (11,25,2,'EJBOLIVAR','2013-05-16 10:28:55');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (12,26,2,'EJBOLIVAR','2013-05-16 10:31:53');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (13,27,2,'EJBOLIVAR','2013-05-16 10:32:01');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (14,28,2,'EJBOLIVAR','2013-05-16 10:32:10');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (15,29,2,'EJBOLIVAR','2013-05-16 10:32:17');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (16,30,3,'EJBOLIVAR','2013-05-16 10:32:26');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (17,30,3,'EJBOLIVAR','2013-05-16 10:32:34');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (18,30,3,'EJBOLIVAR','2013-05-16 10:32:43');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (19,30,3,'EJBOLIVAR','2013-05-16 10:32:52');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (20,31,3,'EJBOLIVAR','2013-05-16 10:33:00');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (21,31,4,'EJBOLIVAR','2013-05-16 10:33:16');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (22,32,4,'EJBOLIVAR','2013-05-16 10:33:25');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (23,32,4,'EJBOLIVAR','2013-05-16 10:33:34');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (24,33,4,'EJBOLIVAR','2013-05-16 10:33:44');
INSERT INTO `rh_vacaciontabla` (`NroAnio`,`DiasDisfrutes`,`DiasAdicionales`,`UltimoUsuario`,`UltimaFecha`) VALUES (25,33,4,'EJBOLIVAR','2013-05-16 10:33:54');
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('ACTVACA', 'T', 'S', 'A', 'INDICA SI SE ACTUALIZA EN FORMA MASIVA LOS PERIODOS VACACIONALES DE LOS EMPLEADOS', '0001', 'RH', 'EJBOLIVAR', '2013-05-16');
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('ACTVACAPER', 'T', '', 'A', 'PARAMETRO ACTUALIZADO AUTOMATICAMENTE PARA CONOCER SI SE ACTUALIZA EN FORMA MASIVA EL PERIODO ACTUAL', '0001', 'RH', 'EJBOLIVAR', '2013-05-16');
UPDATE `siaceda`.`mastparametros` SET `ValorParam`='10' WHERE `ParametroClave`='VACVENDIAS';
-- 2013-05-17
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('VACANTECEDENT', 'T', 'S', 'A', 'INIDICA SI SE TOMAN EN CUENTA LOS AÑOS DE ANTECEDENTES DE SERVICIO PARA OBTENER LOS DIAS DE DERECHO DE VACACIONES POR PERIODO', '0001', 'GE', 'EJBOLIVAR', '2013-05-17');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('DIAS_DISFRUTE_PENDIENTE', 'DIAS DE DISFRUTE DE VACACIONES PENDIENTES', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('DIAS_POR_DERECHO_PENDIENTE', 'DIAS X DERECHO FRACCIONADO DEL EMPLEADO', 'A');
-- 2013-05-20
ALTER TABLE `siaceda`.`pr_tiponominaempleado` ADD COLUMN `CodProveedor` VARCHAR(6) NULL  AFTER `GeneradoPor` ;
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('PROCESOS_PENDIENTES', 'MONTO DE LOS PROCESOS PENDIENTE DE PAGO (ARGS: PROCESO, PERIODO)', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`) VALUES ('PROCESOS_PENDIENTES_NRO', 'NRO. DE PROCESOS PENDIENTES DE PAGO (ARGS: PROCESO, PERIODO)');
UPDATE pr_tiponominaempleado SET EstadoPago = 'PA' WHERE Periodo < '2013-05';
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('ANTIGUEDAD_ACUMULADA', 'MONTO DE LA ANTIGUEDAD ACUMULADA', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('ANTIGUEDAD_ACUMULADA_FRACCION', 'MONTO FRACCIONADO POR ANTIGUEDAD', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('FIDEICOMISO_ACUMULADO', 'INTERES ACUMULADO', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('ANTIGUEDAD_ACUMULADA_DIAS', 'DIAS DE ANTIGUEDAD ACUMULADA', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('ANTIGUEDAD_ACUMULADA_FRACCION_DIAS', 'DIAS FRACCIONADO POR ANTIGUEDAD', 'A');
-- 2013-05-21
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('MESES_FRACCION_ACTUAL', 'FRACCION DE LOS MESES PARA EL AÑO ACTUAL', 'A');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('NOMINA', '05', '05-0016', 'Control de Prestaciones', 'A', 'EJBOLIVAR', '2013-05-21');
CREATE TABLE `pr_liquidacionempleado` (
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` int(2) NOT NULL,
  `CodTipoNom` varchar(2) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `CodTipoProceso` varchar(3) NOT NULL,
  `ProcesadoPor` varchar(6) NOT NULL,
  `FechaProceso` date NOT NULL,
  `SueldoBasico` decimal(11,2) NOT NULL DEFAULT '0.00',
  `TotalIngresos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `TotalEgresos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `TotalPatronales` decimal(11,2) NOT NULL DEFAULT '0.00',
  `TotalNeto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `TotalProvisiones` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Fingreso` date NOT NULL,
  `Fegreso` date DEFAULT NULL,
  `Fliquidacion` date NOT NULL,
  `CodCargo` varchar(4) NOT NULL,
  `CodProveedor` varchar(6) DEFAULT NULL,
  `CodTipoDocumento` varchar(2) DEFAULT NULL COMMENT 'pr_obligaciones->CodTipoDocumento',
  `NroDocumento` varchar(25) DEFAULT NULL COMMENT 'pr_obligaciones->NroDocumento',
  `FechaPago` date NOT NULL,
  `CodMotivoCes` varchar(2) NOT NULL,
  `ObsCese` tinytext,
  `PeriodoVoucher` varchar(7) NOT NULL,
  `Voucher` varchar(7) NOT NULL,
  `EstadoPago` varchar(2) NOT NULL DEFAULT 'PE' COMMENT 'PE:PENDIENTE; PA:PAGADO',
  `UltimoUsuario` varchar(30) NOT NULL DEFAULT '',
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPersona`,`Secuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('CARGO', 'Cargo del Empleado', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('ULTIMO_SUELDO_BASICO', 'ULTIMO SUELD BASICO DEL EMPLEADO', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('SUELDO_ACTUAL', 'Sueldo básico actual del empleado', 'A');
-- 2013-05-22
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('RH', '01', '01-0032', 'Empleados -> Vacaciones -> Actualización Masiva del Periodo', 'A', 'EJBOLIVAR', '2013-05-22');
--
UPDATE pr_procesoperiodo SET EstadoPago = 'PA', FlagPagado = 'S' WHERE Periodo <= '2013-05';
UPDATE pr_tiponominaempleado SET EstadoPago = 'PA' WHERE Periodo < '2013-05';
TRUNCATE TABLE rh_vacacionpago;
UPDATE rh_vacacionperiodo SET PendientePago = 0;
INSERT INTO rh_vacacionpago (
	CodPersona,
	NroPeriodo,
	Secuencia,
	CodTipoNom,
	DiasPago
)
SELECT
	vp.CodPersona,
	vp.NroPeriodo,
	'1',
	vp.CodTipoNom,
	vp.PendientePago
FROM rh_vacacionperiodo vp
WHERE
	vp.NroPeriodo = (SELECT MAX(vp1.NroPeriodo)
					 FROM rh_vacacionperiodo vp1
					 WHERE vp1.CodPersona = vp.CodPersona
					 GROUP BY vp1.CodPersona);
-- 2013-05-24
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('NOMINA', '05', '05-0017', 'Listar Ajustes (Empleados)', 'A', 'EJBOLIVAR', '2013-05-24');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('NOMINA', '05', '05-0018', 'Aprobar Ajustes (Empleados)', 'A', 'EJBOLIVAR', '2013-05-24');
UPDATE `siaceda`.`seguridad_concepto` SET `Descripcion`='Listar Ajustes (Grado Salarial)' WHERE `CodAplicacion`='NOMINA' and`Grupo`='05' and`Concepto`='05-0010';
UPDATE `siaceda`.`seguridad_concepto` SET `Descripcion`='Aprobar Ajustes (Grado Salarial)' WHERE `CodAplicacion`='NOMINA' and`Grupo`='05' and`Concepto`='05-0014';
ALTER TABLE `siaceda`.`pr_ajustesalarialajustes` DROP COLUMN `Descripcion` ;
CREATE TABLE `pr_ajustesalarialemp` (
  `CodOrganismo` varchar(4) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `Secuencia` int(2) NOT NULL,
  `Descripcion` text NOT NULL,
  `NroResolucion` varchar(15) NOT NULL,
  `NroGaceta` varchar(15) NOT NULL,
  `PreparadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaPreparacion` date NOT NULL,
  `FechaAprobado` date NOT NULL,
  `MotivoAnulacion` text NOT NULL,
  `CodTipoNom` varchar(2) NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, AP:Aprobado, AN:Anulado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Periodo`,`Secuencia`),
  KEY `FK_pr_ajustesalarialemp_1_idx` (`CodTipoNom`),
  CONSTRAINT `FK_pr_ajustesalarialemp_1` FOREIGN KEY (`CodTipoNom`) REFERENCES `tiponomina` (`CodTipoNom`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `pr_ajustesalarialajustesemp` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Periodo` varchar(7) NOT NULL,
  `Secuencia` int(2) NOT NULL,
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Porcentaje` decimal(11,2) DEFAULT '0.00',
  `Monto` decimal(11,2) DEFAULT '0.00',
  `SueldoPromedio` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, AP:Aprobado, AN:Anulado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Periodo`,`Secuencia`,`CodPersona`) USING BTREE,
  KEY `FK_pr_ajustesalarialajustesemp_1_idx` (`CodOrganismo`,`Periodo`,`Secuencia`),
  CONSTRAINT `FK_pr_ajustesalarialajustesemp_1` FOREIGN KEY (`CodOrganismo`, `Periodo`, `Secuencia`) REFERENCES `pr_ajustesalarialemp` (`CodOrganismo`, `Periodo`, `Secuencia`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `siaceda`.`rh_nivelsalarialajustes` DROP COLUMN `Descripcion` ;
ALTER TABLE `siaceda`.`rh_puestos` ADD INDEX `IK_rh_puestos_1` (`CategoriaCargo` ASC, `Grado` ASC) ;
-- 2013-05-27
UPDATE `siaceda`.`seguridad_concepto` SET `Descripcion`='Control de Procesos (Pre-Nómina)' WHERE `CodAplicacion`='NOMINA' and`Grupo`='05' and`Concepto`='05-0011';
UPDATE `siaceda`.`seguridad_concepto` SET `Descripcion`='Ejecución de Procesos (Pre-Nómina)' WHERE `CodAplicacion`='NOMINA' and`Grupo`='05' and`Concepto`='05-0012';
ALTER TABLE `siaceda`.`pr_procesoperiodoprenomina` DROP COLUMN `FlagAprobado` , DROP COLUMN `FechaAprobado` , DROP COLUMN `AprobadoPor` , DROP COLUMN `FechaPago` ;
ALTER TABLE `siaceda`.`pr_procesoperiodo` CHANGE COLUMN `FechaProceso` `FechaProceso` DATETIME NOT NULL  ;
ALTER TABLE `siaceda`.`pr_procesoperiodoprenomina` CHANGE COLUMN `UltimaFecha` `UltimaFecha` DATETIME NOT NULL  ;
ALTER TABLE `siaceda`.`pr_tiponominaempleadoprenomina` DROP COLUMN `CodTipoPago` , DROP COLUMN `Ncuenta` , DROP COLUMN `TipoCuenta` , DROP COLUMN `CodBanco` ;
ALTER TABLE `siaceda`.`pr_tiponominaempleadoprenomina` ADD COLUMN `TotalProvisiones` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  AFTER `TotalNeto` ;
-- 2013-05-30
UPDATE `siaceda`.`pr_funciones` SET `Funcion`='MESES_FRACCION_EGRESO', `Descripcion`='FRACCION DE LOS MESES PARA EL AÑO DE EGRESO DEL EMPLEADO' WHERE `CodFuncion`='48';
ALTER TABLE `siaceda`.`rh_sueldos` ADD COLUMN `SueldoIntegralParcial` DECIMAL(11,2) NOT NULL  AFTER `SueldoIntegral` ;
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('SUELDO_INTEGRAL_PARCIAL', 'Sueldo integral del trabajador (sueldo + alicuota vacacional)', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`) VALUES ('SUELDO_INTEGRAL_PARCIAL_DIARIO', 'Sueldo integral diario del trabajador (sueldo + alicuota vacacional)');
DELETE FROM `siaceda`.`pr_funciones` WHERE `CodFuncion`='22';
INSERT INTO pr_funciones VALUES ('49', 'ULTIMO_SUELDO_INTEGRAL', 'ULTIMO SUELDO INTEGRAL DEL EMPLEADO', 'A');
INSERT INTO pr_funciones VALUES ('50', 'ULTIMO_SUELDO_INTEGRAL_DIARIO', 'ULTIMO SUELDO INTEGRAL DIARIO DEL EMPLEADO', 'A');
INSERT INTO pr_funciones VALUES ('52', 'ULTIMO_SUELDO_INTEGRAL_PARCIAL_DIARIO', 'ULTIMO SUELDO INTEGRAL DIARIO (PARCIAL) DEL EMPLEADO', 'A');
INSERT INTO pr_funciones VALUES ('51', 'ULTIMO_SUELDO_INTEGRAL_PARCIAL', 'ULTIMO SUELDO INTEGRAL (PARCIAL) DEL EMPLEADO', 'A');
-- 2013-05-31
ALTER TABLE `siaceda`.`rh_vacacionsolicitud` ADD COLUMN `SolicitudRelacionada` VARCHAR(11) NULL  AFTER `Observaciones` ;