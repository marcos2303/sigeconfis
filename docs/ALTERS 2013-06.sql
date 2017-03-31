-- 2013-06-03
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('NOMINA', '02', '02-0032', 'Constancia de Trabajo', 'A', 'EJBOLIVAR', '2013-06-03');
-- 2013-06-05
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('ULTIMO_SUELDO_MINIMO', 'ULTIMO SUELDO MINIMO', 'A');
-- 2013-06-07
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('RH', '02', '02-0012', 'Antecedentes de Servicio', 'A', 'EJBOLIVAR', '2013-06-07');
-- 2013-06-11
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('ANTLEGAL', 'T', 'ARTICULO 78  LEY DEL ESTATUTO DE LA FUNCIÓN PÚBLICA PUBLICADA  EN GACETA OFICIAL DE LA REPÚBLICA BOLIVARIANA DE VENEZUELA N° 37.522 DE FECHA 06-09-2002.', 'A', 'FUNDAMENTO LEGAL REPORTE DE ANTECEDENTES DE SERVICIO', '0001', 'RH', 'EJBOLIVAR', '2013-06-11');
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('ANTOBS', 'T', '$ANTOBS = \"$Ciudadano $Nombre,  TITULAR  DE LA  CÉDULA DE  IDENTIDAD  $Nacionalidad-$Cedula,  PRESTÓ SUS SERVICIOS EN ESTE ÓRGANO  DE  CONTROL  FISCAL  DESDE  EL DIA $FechaIngreso, EN EL CARGO INICIAL DE $CargoIngreso, FINALIZANDO  EL  DIA $FechaEgreso,  COMO $CargoEgreso.\";', 'A', 'OBSERVACIONES EN REPORTE DE ANTECEDENTES DE SERVICIOS', '0001', 'GE', 'EJBOLIVAR', '2013-06-11');
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('FIRMARHPR1', 'T', '000080', 'A', 'FIRMA REPORTE RECURSOS HUMANOS', '0001', 'RH', 'EJBOLIVAR', '2013-06-11');
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('FIRMARHPR2', 'T', '000098', 'A', 'FIRMA REPORTE RECURSOS HUMANOS', '0002', 'RH', 'EJBOLIVAR', '2013-06-11');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('ULTIMO_TOTAL_INGRESOS', 'ULTIMO TOTAL DE INGRESOS PARA UN PROCESO (ARGS: PROCESO)', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('TOTAL_INGRESOS', 'TOTAL DE INGRESOS PARA UN PROCESO Y PERIODO (ARGS: PROCESO; PERIODO)', 'A');
-- 2013-06-13
ALTER TABLE `siaceda`.`pr_ajustesalarialajustes` CHANGE COLUMN `Porcentaje` `Porcentaje` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  , CHANGE COLUMN `Monto` `Monto` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  , ADD COLUMN `SueldoBasico` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  AFTER `CodNivel` , DROP PRIMARY KEY , ADD PRIMARY KEY USING BTREE (`CodOrganismo`, `Periodo`, `Secuencia`, `CodNivel`) ;
ALTER TABLE `siaceda`.`pr_ajustesalarialajustesemp` CHANGE COLUMN `Porcentaje` `Porcentaje` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  , CHANGE COLUMN `Monto` `Monto` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  , ADD COLUMN `SueldoBasico` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  AFTER `CodPersona` ;
UPDATE pr_ajustesalarialajustes SET SueldoBasico = SueldoPromedio - Monto WHERE Monto > 0;
UPDATE pr_ajustesalarialajustesemp SET SueldoBasico = SueldoPromedio - Monto WHERE Monto > 0;
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('RH', '02', '02-0013', 'Listado de Ceses/Reingresos', 'A', 'EJBOLIVAR', '2013-06-13');
ALTER TABLE `siaceda`.`rh_vacacionsolicitud` ADD COLUMN `NroSolicitud` VARCHAR(6) NOT NULL  AFTER `CodSolicitud` ;
ALTER TABLE `siaceda`.`rh_vacacionpago` ADD COLUMN `CodProveedor` VARCHAR(6) NULL  AFTER `Pasados` ;
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('RH', '02', '02-0014', 'Pago de Vacaciones', 'A', 'EJBOLIVAR', '2013-06-13');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('ANIOS_DE_SERVICIO_FRACCION', 'AÑOS DE SERVICIO DEL TRABAJADOR (TOMA EN CUENTA LOS PERIODOS MAYOR A SEIS MESES)', 'A');
DELETE FROM `siaceda`.`pr_funciones` WHERE `CodFuncion`='22';
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('SUELDO_INTEGRAL', 'SUELDO INTEGRAL (SUELDO NORMAL + ALICUOTAS)', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('ULTIMO_SUELDO_INTEGRAL', 'ULTIMO SUELDO INTEGRAL', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('ULTIMO_SUELDO_INTEGRAL_DIARIO', 'ULTIMO SUELDO INTEGRAL DIARIO', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('SUELDO_INTEGRAL_PARCIAL', 'SUELDO INTEGRAL (SUELDO NORMAL + ALICUOTA VACACIONAL)', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('ULTIMO_SUELDO_INTEGRAL_PARCIAL', 'ULTIMO SUELDO INTEGRAL PARCIAL', 'A');
INSERT INTO `siaceda`.`pr_funciones` (`Funcion`, `Descripcion`, `Estado`) VALUES ('ULTIMO_SUELDO_INTEGRAL_PARCIAL_DIARIO', 'ULTIMO SUELDO INTEGRAL PARCIAL DIARIO', 'A');
-- 2013-06-26
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('NOMINA', '02', '02-0032', 'Antecedentes de Servicio', 'A', 'EJBOLIVAR', '2013-06-26');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('NOMINA', '02', '02-0033', 'Listado de Ceses/Reingresos', 'A', 'EJBOLIVAR', '2013-06-26');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('NOMINA', '02', '02-0034', 'Listado de Ceses/Reingresos', 'A', 'EJBOLIVAR', '2013-06-26');
UPDATE `siaceda`.`seguridad_concepto` SET `Descripcion`='Antecedentes de Servicio' WHERE `CodAplicacion`='NOMINA' and`Grupo`='02' and`Concepto`='02-0033';
UPDATE `siaceda`.`seguridad_concepto` SET `Descripcion`='Constancias de Trabajo' WHERE `CodAplicacion`='NOMINA' and`Grupo`='02' and`Concepto`='02-0032';