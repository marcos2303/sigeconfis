 -- 2013-08-01
 INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('NOMINA', '02', '02-0035', 'Payroll de Pago x Periodo', 'A', 'EJBOLIVAR', '2013-08-01');
UPDATE `siaceda`.`seguridad_concepto` SET `Descripcion`='Payroll de Pago x Periodo' WHERE `CodAplicacion`='NOMINA' and`Grupo`='02' and`Concepto`='02-0013';
UPDATE `siaceda`.`seguridad_concepto` SET `Descripcion`='Payroll de Pago' WHERE `CodAplicacion`='NOMINA' and`Grupo`='02' and`Concepto`='02-0035';
-- 2013-08-05
ALTER TABLE `siaceda`.`ap_ordenpago` CHANGE COLUMN `FechaTransferencia` `FechaTransferencia` DATE NOT NULL COMMENT 'Fecha de Pago'  ;
-- 2013-08-07
ALTER TABLE `siaceda`.`lg_cierremensualsustento` ADD COLUMN `Monto` DECIMAL(11,2) NULL  AFTER `Precio` , ADD COLUMN `DocumentoReferencia` VARCHAR(20) NULL  AFTER `Monto` ;
CREATE TABLE `lg_cierremensualx` (
  `CodItem` varchar(10) NOT NULL COMMENT 'lg_itemmast->CodItem',
  `Precio` decimal(11,6) NOT NULL,
  PRIMARY KEY (`CodItem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
