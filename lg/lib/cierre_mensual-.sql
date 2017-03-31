DELETE FROM lg_cierremensual
			WHERE
				Periodo = '2013-08' AND
				CodOrganismo = '0001';

DELETE FROM lg_cierremensualsustento
			WHERE
				Periodo = '2013-08' AND
				CodOrganismo = '0001';

DELETE FROM lg_cierremensualx;

INSERT INTO lg_cierremensual (
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
				'2013-08' AS Periodo,
				CodOrganismo,
				CodAlmacen,
				CodItem,
				StockNuevo,
				Precio,
				'EVELASQUEZ' AS UltimoUsuario,
				NOW() AS UltimaFecha
			FROM lg_cierremensual
			WHERE
				Periodo = '2013-07' AND
				CodOrganismo = '0001';

INSERT INTO lg_cierremensualx (
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
				cm.Periodo = '2013-07' AND
				cm.CodOrganismo = '0001' AND
				a.TipoAlmacen = 'P'
			GROUP BY CodItem;

(SELECT
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
				k.PeriodoContable = '2013-08' AND
				a.CodOrganismo = '0001'
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
				Periodo = '2013-08' AND
				CodOrganismo = '0001'
			)
			ORDER BY CodAlmacen, CodItem;

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000001',
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '1.000000',
					SalidaOtros = '1.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '1.000000',
					SalidaOtros = '1.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000002',
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '1.000000',
					SalidaOtros = '1.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '1.000000',
					SalidaOtros = '1.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000008',
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '10.000000',
					SalidaOtros = '10.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '10.000000',
					SalidaOtros = '10.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000012',
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '4.000000',
					SalidaOtros = '4.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '4.000000',
					SalidaOtros = '4.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000014',
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '2.000000',
					SalidaOtros = '2.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '2.000000',
					SalidaOtros = '2.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000028',
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '1.000000',
					SalidaOtros = '1.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '1.000000',
					SalidaOtros = '1.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000034',
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '2.000000',
					SalidaOtros = '2.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '2.000000',
					SalidaOtros = '2.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000048',
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '6.000000',
					SalidaOtros = '6.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '6.000000',
					SalidaOtros = '6.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000053',
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '1.000000',
					SalidaOtros = '1.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '1.000000',
					SalidaOtros = '1.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000085',
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '10.000000',
					SalidaOtros = '10.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '0.000000',
					IngresoOtros = '0.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '10.000000',
					SalidaOtros = '10.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000089',
					IngresoROC = '12.000000',
					IngresoOtros = '12.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '12.000000',
					IngresoOtros = '12.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000090',
					IngresoROC = '36.000000',
					IngresoOtros = '36.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '36.000000',
					IngresoOtros = '36.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000091',
					IngresoROC = '40.000000',
					IngresoOtros = '40.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '40.000000',
					IngresoOtros = '40.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000093',
					IngresoROC = '36.000000',
					IngresoOtros = '36.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '36.000000',
					IngresoOtros = '36.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000097',
					IngresoROC = '36.000000',
					IngresoOtros = '36.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '36.000000',
					IngresoOtros = '36.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000103',
					IngresoROC = '12.000000',
					IngresoOtros = '12.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '12.000000',
					IngresoOtros = '12.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000104',
					IngresoROC = '36.000000',
					IngresoOtros = '36.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '36.000000',
					IngresoOtros = '36.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000107',
					IngresoROC = '12.000000',
					IngresoOtros = '12.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '12.000000',
					IngresoOtros = '12.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000111',
					IngresoROC = '48.000000',
					IngresoOtros = '48.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '48.000000',
					IngresoOtros = '48.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000115',
					IngresoROC = '100.000000',
					IngresoOtros = '100.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '100.000000',
					IngresoOtros = '100.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000117',
					IngresoROC = '100.000000',
					IngresoOtros = '100.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '100.000000',
					IngresoOtros = '100.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000118',
					IngresoROC = '100.000000',
					IngresoOtros = '100.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '100.000000',
					IngresoOtros = '100.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000119',
					IngresoROC = '100.000000',
					IngresoOtros = '100.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '100.000000',
					IngresoOtros = '100.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000122',
					IngresoROC = '24.000000',
					IngresoOtros = '24.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '24.000000',
					IngresoOtros = '24.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000123',
					IngresoROC = '24.000000',
					IngresoOtros = '24.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '24.000000',
					IngresoOtros = '24.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000124',
					IngresoROC = '24.000000',
					IngresoOtros = '24.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '24.000000',
					IngresoOtros = '24.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000127',
					IngresoROC = '6.000000',
					IngresoOtros = '6.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '6.000000',
					IngresoOtros = '6.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

INSERT INTO lg_cierremensual
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000138',
					IngresoROC = '12.000000',
					IngresoOtros = '12.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW()
				ON DUPLICATE KEY UPDATE
					IngresoROC = '12.000000',
					IngresoOtros = '12.000000',
					IngresoTraslado = '0.000000',
					SalidaREQ = '0.000000',
					SalidaOtros = '0.000000',
					SalidaTraslado = '0.000000',
					StockAnterior = '0.00',
					StockNuevo = '0.00',
					Precio = '0.00',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

UPDATE lg_cierremensual
			SET StockNuevo = (StockAnterior + IngresoOtros + IngresoTraslado - SalidaOtros - SalidaTraslado)
			WHERE
				CodOrganismo = '0001' AND
				Periodo = '2013-08';

SELECT
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
				t.CodOrganismo = '0001' AND
				t.Periodo = '2013-08' AND
				t.Estado = 'CO' AND
				(t.CodTransaccion = 'ROC' OR 
				 t.CodTransaccion = 'ARO' OR 
				 t.CodTransaccion = 'DRO' OR 
				 t.CodTransaccion = 'MIT' OR 
				 t.CodTransaccion = 'TRT' OR 
				 t.FlagManual = 'S')
			ORDER BY CodItem, FechaDocumento;

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000089' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000089',
					Precio = '65.00'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '65.00',
					Total = '780.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000089',
					Secuencia = '3',
					Cantidad = '12.00',
					CantidadAcumulada = '12.00',
					Precio = '65.00',
					Monto = '780.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '3',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000090' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000090',
					Precio = '45.00'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '45.00',
					Total = '1620.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000090',
					Secuencia = '1',
					Cantidad = '36.00',
					CantidadAcumulada = '36.00',
					Precio = '45.00',
					Monto = '1620.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '1',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000091' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000091',
					Precio = '19.50'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '19.50',
					Total = '780.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000091',
					Secuencia = '2',
					Cantidad = '40.00',
					CantidadAcumulada = '40.00',
					Precio = '19.50',
					Monto = '780.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '2',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000093' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000093',
					Precio = '48.00'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '48.00',
					Total = '1728.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000093',
					Secuencia = '4',
					Cantidad = '36.00',
					CantidadAcumulada = '36.00',
					Precio = '48.00',
					Monto = '1728.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '4',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000097' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000097',
					Precio = '17.75'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '17.75',
					Total = '639.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000097',
					Secuencia = '5',
					Cantidad = '36.00',
					CantidadAcumulada = '36.00',
					Precio = '17.75',
					Monto = '639.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '5',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000103' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000103',
					Precio = '45.00'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '45.00',
					Total = '540.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000103',
					Secuencia = '18',
					Cantidad = '12.00',
					CantidadAcumulada = '12.00',
					Precio = '45.00',
					Monto = '540.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '18',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000104' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000104',
					Precio = '15.00'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '15.00',
					Total = '540.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000104',
					Secuencia = '6',
					Cantidad = '36.00',
					CantidadAcumulada = '36.00',
					Precio = '15.00',
					Monto = '540.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '6',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000107' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000107',
					Precio = '50.00'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '50.00',
					Total = '600.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000107',
					Secuencia = '7',
					Cantidad = '12.00',
					CantidadAcumulada = '12.00',
					Precio = '50.00',
					Monto = '600.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '7',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000111' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000111',
					Precio = '15.00'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '15.00',
					Total = '720.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000111',
					Secuencia = '8',
					Cantidad = '48.00',
					CantidadAcumulada = '48.00',
					Precio = '15.00',
					Monto = '720.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '8',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000115' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000115',
					Precio = '6.00'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '6.00',
					Total = '600.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000115',
					Secuencia = '9',
					Cantidad = '100.00',
					CantidadAcumulada = '100.00',
					Precio = '6.00',
					Monto = '600.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '9',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000117' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000117',
					Precio = '7.00'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '7.00',
					Total = '700.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000117',
					Secuencia = '10',
					Cantidad = '100.00',
					CantidadAcumulada = '100.00',
					Precio = '7.00',
					Monto = '700.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '10',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000118' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000118',
					Precio = '6.30'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '6.30',
					Total = '630.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000118',
					Secuencia = '11',
					Cantidad = '100.00',
					CantidadAcumulada = '100.00',
					Precio = '6.30',
					Monto = '630.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '11',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000119' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000119',
					Precio = '6.00'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '6.00',
					Total = '600.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000119',
					Secuencia = '12',
					Cantidad = '100.00',
					CantidadAcumulada = '100.00',
					Precio = '6.00',
					Monto = '600.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '12',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000122' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000122',
					Precio = '26.00'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '26.00',
					Total = '624.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000122',
					Secuencia = '15',
					Cantidad = '24.00',
					CantidadAcumulada = '24.00',
					Precio = '26.00',
					Monto = '624.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '15',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000123' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000123',
					Precio = '29.00'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '29.00',
					Total = '696.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000123',
					Secuencia = '13',
					Cantidad = '24.00',
					CantidadAcumulada = '24.00',
					Precio = '29.00',
					Monto = '696.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '13',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000124' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000124',
					Precio = '15.00'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '15.00',
					Total = '360.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000124',
					Secuencia = '14',
					Cantidad = '24.00',
					CantidadAcumulada = '24.00',
					Precio = '15.00',
					Monto = '360.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '14',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000127' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000127',
					Precio = '48.00'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '48.00',
					Total = '288.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000127',
					Secuencia = '16',
					Cantidad = '6.00',
					CantidadAcumulada = '6.00',
					Precio = '48.00',
					Monto = '288.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '16',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

SELECT cm.*
				FROM
					lg_cierremensual cm
					INNER JOIN lg_almacenmast a ON (a.CodAlmacen = cm.CodAlmacen)
				WHERE
					cm.CodOrganismo = '0001' AND
					cm.Periodo = '2013-08' AND
					cm.CodItem = '0000000138' AND
					a.TipoAlmacen <> 'T';

SELECT Fecha
				FROM
					ap_documentos d
					INNER JOIN ap_obligaciones o ON (o.CodProveedor = d.CodProveedor AND
													 o.CodTipoDocumento = d.ReferenciaTipoDocumento AND
													 o.NroDocumento = d.ReferenciaNroDocumento)
				WHERE
					d.Anio = '2013' AND
					d.CodProveedor = '000230' AND
					d.DocumentoClasificacion = 'ROC' AND
					d.DocumentoReferencia = 'OC';

INSERT INTO lg_cierremensualx 
				SET
					CodItem = '0000000138',
					Precio = '45.00'
				ON DUPLICATE KEY UPDATE
					Precio = Precio;

UPDATE lg_transacciondetalle
				SET
					PrecioUnit = '45.00',
					Total = '540.00'
				WHERE
					CodOrganismo = '0001' AND
					CodDocumento = 'NI' AND
					NroDocumento = '000062';

INSERT INTO lg_cierremensualsustento
				SET
					Periodo = '2013-08',
					CodOrganismo = '0001',
					CodAlmacen = 'ALCEDA',
					CodItem = '0000000138',
					Secuencia = '17',
					Cantidad = '12.00',
					CantidadAcumulada = '12.00',
					Precio = '45.00',
					Monto = '540.00',
					DocumentoReferencia = 'OC-0000000034-2013-2',
					FechaRecepcion = '2013-08-01',
					TransaccionCodDocumento = 'NI',
					TransaccionNroDocumento = '000062',
					TransaccionSecuencia = '17',
					UltimoUsuario = 'EVELASQUEZ',
					UltimaFecha = NOW();

