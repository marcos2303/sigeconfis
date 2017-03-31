-- 2013-07-10
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('FIRMALG1', 'T', '000077', 'A', 'FIRMA PREPARADO POR REPORTES DE ALMACEN', '0001', 'LG', 'EJBOLIVAR', '2013-07-10');
UPDATE `siaceda`.`mastparametros` SET `CodOrganismo`='0001' WHERE `ParametroClave`='FIRMARHPR2';
-- 2013-07-23
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('FIRMALG2', 'T', '000026', 'A', 'FIRMA CONFORMADO POR REPORTES DE ALMACEN', '0001', 'LG', 'EJBOLIVAR', '2013-07-23');
-- 2013-07-25
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('LG', '07', '07-0012', 'Listado de Invitaciones', 'A', 'EJBOLIVAR', '2013-07-25');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('LG', '07', '07-0013', 'Cuadros Comparativos de Ofertas', 'A', 'EJBOLIVAR', '2013-07-25');
-- 2013-07-26
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('LG', '07', '07-0014', 'Items sin Movimiento', 'A', 'EJBOLIVAR', '2013-07-26');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('LG', '07', '07-0015', 'Stock por Punto de Reposición', 'A', 'EJBOLIVAR', '2013-07-26');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('LG', '07', '07-0016', 'Ordenes Comprometidas', 'A', 'EJBOLIVAR', '2013-07-26');
-- 2013-07-29
ALTER TABLE `siaceda`.`pr_tipoproceso` ADD COLUMN `FlagRetroactivo` VARCHAR(1) NOT NULL DEFAULT 'N'  AFTER `FlagAdelanto` ;
ALTER TABLE `siaceda`.`pr_procesoperiodo` ADD COLUMN `PeriodoNomina` VARCHAR(7) NOT NULL  AFTER `CodTipoProceso` ;
UPDATE siaceda.pr_procesoperiodo SET PeriodoNomina = Periodo;
ALTER TABLE `siaceda`.`rh_sueldos` ADD COLUMN `AliVac` DECIMAL(11,2) NOT NULL  AFTER `SueldoIntegralParcial` , ADD COLUMN `AliFin` DECIMAL(11,2) NOT NULL  AFTER `AliVac` ;
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('PERIODONOMINA', 'Periodo que afecta el cálculo de la nómina', 'A');
INSERT INTO `siaceda`.`pr_variables` (`Variable`, `Descripcion`, `Estado`) VALUES ('RETROACTIVO', 'Indica si el proceso es para pago de retroactivo', 'A');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('LG', '07', '07-0017', 'Ordenes Comprometidas', 'A', 'EJBOLIVAR', '2013-07-30');