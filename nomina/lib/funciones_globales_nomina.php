<?php
//	------------------------------------------
//	LISTA DE FUNCIONES (EDITOR DE FORMULA)
//	------------------------------------------
//	obtener numero de hijos
function NUMERO_DE_HIJOS($edad=NULL, $fecha=NULL) {
	global $_ARGS;
	global $_PARAMETRO;
	if ($edad) {
		if (!$fecha) $fecha = $_ARGS["_HASTA"];
		list($a, $m, $d) = split("[/.-]", $fecha); $anio = $a - ($edad);
		$mes = intval($m);
		$fecha = "$anio-$mes-$d";
		$filtro = "AND FechaNacimiento  >= '".$fecha."'";
	}
	//	consulto
	$sql = "SELECT *
			FROM rh_cargafamiliar
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				Parentesco = 'HI' $filtro";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	return intval(mysql_num_rows($query));
}

//	obtener numero de cursos
function NUMERO_DE_CURSOS() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT *
			FROM rh_empleado_cursos
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				FechaCulminacion <= '".$_ARGS['_PERIODO']."' AND
				FlagPago = 'S'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	return mysql_num_rows($query);
}

//	devuelve si es universitario
function UNIVERSITARIO() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT *
			FROM rh_empleado_instruccion
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				CodGradoInstruccion = 'UNI'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) return true;
	else return false;
}

//	devuelve si es tsu
function TSU() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT *
			FROM rh_empleado_instruccion
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				CodGradoInstruccion = 'TSU'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) return true;
	else return false;
}

//	obtener años de servicio
function ANIOS_DE_SERVICIO() {
	global $_ARGS;
	global $_PARAMETRO;
	list($anios, $meses, $dias) = getTiempo(formatFechaDMA($_ARGS['_FECHA_INGRESO']), formatFechaDMA($_ARGS['_HASTA']));
	return intval($anios);
}

//	obtener años de servicio
function ANIOS_DE_SERVICIO_FRACCION() {
	global $_ARGS;
	global $_PARAMETRO;
	list($anios, $meses, $dias) = getTiempo(formatFechaDMA($_ARGS['_FECHA_INGRESO']), formatFechaDMA($_ARGS['_HASTA']));
	if ($meses >= 6 && $dias > 0) ++$anios;
	return intval($anios);
}

//	devuelve si el empleado ocupa un cargo titular de jefatura
function JEFE_TITULAR() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT
				me.CodPersona,
				rp.Grado
			FROM
				mastempleado me
				INNER JOIN rh_puestos rp ON (me.CodCargo = rp.CodCargo)
			WHERE
				me.CodPersona = '".$_ARGS['_PERSONA']."' AND
				rp.Grado >= '90' AND rp.Grado <= '99'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) return true; else return false;
}

//	obtener los dias como titular del empleado en cargos de jefatura
function DIAS_JERARQUIA() {
	global $_ARGS;
	global $_PARAMETRO;
	$suma = 0;
	$sql = "SELECT
				  en.Fecha,
				  en.FechaHasta,
				  p.Grado,
				  ns.SueldoPromedio AS SueldoBasico
			FROM
				  rh_empleadonivelacion en
				  INNER JOIN rh_puestos p ON (en.CodCargo = p.CodCargo)
				  INNER JOIN rh_nivelsalarial ns ON (p.CategoriaCargo = ns.CategoriaCargo AND p.Grado = ns.Grado)
			WHERE
				  en.CodOrganismo = '".$_ARGS['_ORGANISMO']."' AND
				  en.CodPersona = '".$_ARGS['_PERSONA']."' AND
				  en.TipoAccion <> 'ET' AND
				  ((en.FechaHasta = '0000-00-00' AND en.Fecha <= '".$_ARGS['_HASTA']."') OR
				   ('".$_ARGS['_DESDE']."' <= en.FechaHasta))
			ORDER BY en.Fecha";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field = mysql_fetch_array($query)) {
		if ($field['Fecha'] < $_ARGS['_DESDE']) $desde = $_ARGS['_DESDE'];
		else $desde = $field['Fecha'];
		if ($field['FechaHasta'] == "0000-00-00" || $field['FechaHasta'] > $_ARGS['_HASTA']) $hasta = $_ARGS['_HASTA'];
		else $hasta = $field['FechaHasta'];
		if ($field['Grado'] == "90" || $field['Grado'] == "96" || $field['Grado'] == "97" || $field['Grado'] == "98" || $field['Grado'] == "99") {
			$dias = DIAS_FECHA($desde, $hasta);
			$suma += $dias;
		}
	}
	return intval($suma);
}

//	obtener los dias de encargaduria del empleado en cargos de jefatura
function DIAS_JERARQUIA_DIFERENCIA() {
	global $_ARGS;
	global $_PARAMETRO;
	$suma = 0;
	$sql = "SELECT 
				  en.Fecha, 
				  en.FechaHasta, 
				  p.Grado, 
				  ns.SueldoPromedio AS SueldoBasico 
			FROM 
				  rh_empleadonivelacion en 
				  INNER JOIN rh_puestos p ON (en.CodCargo = p.CodCargo) 
				  INNER JOIN rh_nivelsalarial ns ON (p.CategoriaCargo = ns.CategoriaCargo AND p.Grado = ns.Grado) 
			WHERE
				  en.CodOrganismo = '".$_ARGS['_ORGANISMO']."' AND
				  en.CodPersona = '".$_ARGS['_PERSONA']."' AND
				  en.TipoAccion = 'ET' AND
				  ((en.FechaHasta = '0000-00-00' AND en.Fecha <= '".$_ARGS['_HASTA']."') OR
				   ('".$_ARGS['_DESDE']."' <= en.FechaHasta))
			ORDER BY en.Fecha";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field = mysql_fetch_array($query)) {
		if ($field['Fecha'] < $_ARGS['_DESDE']) $desde = $_ARGS['_DESDE'];
		else $desde = $field['Fecha'];
		if ($field['FechaHasta'] == "0000-00-00" || $field['FechaHasta'] > $_ARGS['_HASTA']) $hasta = $_ARGS['_HASTA'];
		else $hasta = $field['FechaHasta'];
		if ($field['Grado'] == "90" || $field['Grado'] == "96" || $field['Grado'] == "97" || $field['Grado'] == "98" || $field['Grado'] == "99") {
			$dias = DIAS_FECHA($desde, $hasta);
			$suma += $dias;
		}
	}
	return intval($suma);
}

//	devuelve si el empleado tiene una especializacion
function ESPECIALIZACION() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT *
			FROM rh_empleado_instruccion
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				CodGradoInstruccion = 'POS' AND
				Nivel = '01'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) return true;
	else return false;
}

//	devuelve si el empleado tiene un magister
function MAGISTER() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT *
			FROM rh_empleado_instruccion
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				CodGradoInstruccion = 'POS' AND
				Nivel = '02'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) return true;
	else return false;
}

//	devuelve si el empleado tiene un doctorado
function DOCTORADO() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT *
			FROM rh_empleado_instruccion
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				CodGradoInstruccion = 'POS' AND
				Nivel = '03'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) return true;
	else return false;
}

//	obtener diferencia de sueldo basico
function DIFERENCIA_SUELDO_BASICO() {
	global $_ARGS;
	global $_PARAMETRO;	
	$sum_diferencia = 0;
	//	Obtengo el sueldo basico mensual...
	$sql = "SELECT ns.SueldoPromedio AS SueldoBasico 
			FROM 
				  rh_empleadonivelacion en 
				  INNER JOIN rh_puestos p ON (en.CodCargo = p.CodCargo) 
				  INNER JOIN rh_nivelsalarial ns ON (p.CategoriaCargo = ns.CategoriaCargo AND p.Grado = ns.Grado) 
			WHERE 
				  en.CodOrganismo = '".$_ARGS['_ORGANISMO']."' AND  
				  en.CodPersona = '".$_ARGS['_PERSONA']."' AND 
				  en.TipoAccion <> 'ET' AND 
				  en.FechaHasta = '0000-00-00'
			ORDER BY en.Fecha";
	$query_sueldo = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_sueldo) != 0) $field_sueldo = mysql_fetch_array($query_sueldo);
	##
	$sql = "SELECT
				  en.Fecha, 
				  en.FechaHasta, 
				  ns.SueldoPromedio AS SueldoTemporal 
			FROM 
				  rh_empleadonivelacion en 
				  INNER JOIN rh_puestos p ON (en.CodCargo = p.CodCargo) 
				  INNER JOIN rh_nivelsalarial ns ON (p.CategoriaCargo = ns.CategoriaCargo AND p.Grado = ns.Grado) 
			WHERE 
				  en.CodOrganismo = '".$_ARGS['_ORGANISMO']."' AND  
				  en.CodPersona = '".$_ARGS['_PERSONA']."' AND 
				  en.TipoAccion = 'ET' AND 
				  ((en.FechaHasta = '0000-00-00' AND en.Fecha <= '".$_ARGS['_HASTA']."') OR 
				   ('".$_ARGS['_DESDE']."' >= en.Fecha AND 
				    '".$_ARGS['_DESDE']."' <= en.FechaHasta) OR 
				   (en.Fecha >= '".$_ARGS['_DESDE']."' AND 
				    en.Fecha <= '".$_ARGS['_HASTA']."')) 
			ORDER BY en.Fecha";
	$query_nivelaciones = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_nivelaciones = mysql_fetch_array($query_nivelaciones)) {
		if ($field_nivelaciones['Fecha'] < $_ARGS['_DESDE']) $desde = $_ARGS['_DESDE'];
		else $desde = $field_nivelaciones['Fecha'];
		##
		if ($field_nivelaciones['FechaHasta'] == "0000-00-00" || $field_nivelaciones['FechaHasta'] > $_ARGS['_HASTA']) $hasta = $_ARGS['_HASTA'];
		else $hasta = $field_nivelaciones['FechaHasta'];
		##
		$dias = DIAS_FECHA($desde, $hasta);
		##
		$Diferencia = $field_nivelaciones['SueldoTemporal'] - $field_sueldo['SueldoBasico'];
		$Diario = $Diferencia / $_PARAMETRO['MAXDIASMES'];
		$monto = $Diario * $dias;
		##
		$sum_diferencia += $monto;
	}
	return $sum_diferencia;
}

//	obtener adelanto de quincena
function ADELANTO_QUINCENA() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT TotalIngresos
			FROM pr_tiponominaempleado
			WHERE
				Periodo = '".$_ARGS['_PERIODO']."' AND
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				CodOrganismo = '".$_ARGS['_ORGANISMO']."' AND
				CodTipoProceso = 'ADE'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	obtener numero de lunes
function NUMERO_LUNES_FECHA() {
	global $_ARGS;
	global $_PARAMETRO;
	if ($_ARGS['_ESTADO'] == "A") {
		if ($_ARGS['_FECHA_INGRESO'] <= $_ARGS['_DESDE']) {
			if ($_ARGS['_FECHA_INGRESO'] < $_ARGS['_HASTA']) list($ae, $me, $de) = split('[/.-]', $_ARGS['_HASTA']);
			else list($ae, $me, $de) = split('[/.-]', $_ARGS['_FECHA_INGRESO']);
			list($ap, $mp) = split('[/.-]', $_ARGS['_PERIODO']); $periodo_inicio = "01-$mp-$ap";
			$primer_dia_semana = getWeekDay($periodo_inicio);
			$dia_semana = $primer_dia_semana;
			$dia_inicio = 1;
			$dia_fin = getDiasMes("$ae-$me");
		} else {
			list($ae, $me, $de) = split('[/.-]', $_ARGS['_HASTA']);
			list($ai, $mi, $di) = split('[/.-]', $_ARGS['_FECHA_INGRESO']); $periodo_inicio = "$di-$mi-$ai";
			$diai = (int) $di;
			$primer_dia_semana = getWeekDay($periodo_inicio);
			$dia_semana = $primer_dia_semana;
			if ($dia_semana == 1) $dia_inicio = (int) $di;
			else {
				if ($dia_semana == 0) $restar_dia_semana = $diai - 7;
				else $restar_dia_semana = $diai - $dia_semana;
				if ($restar_dia_semana < 1) $dia_inicio = (int) $di;
				else {
					$diferencia_dias = $dia_semana - 1;
					$dia_inicio = (int) $di;
					$dia_inicio -= $diferencia_dias;
				}
			}
			$dia_fin = getDiasMes("$ae-$me");
		}
	}
	else {
		list($ae, $me, $de) = split('[/.-]', $_ARGS['_FECHA_EGRESO']);
		if ($_ARGS['_FECHA_INGRESO'] <= $_ARGS['_DESDE']) {
			list($ap, $mp) = split('[/.-]', $_ARGS['_PERIODO']); $periodo_inicio = "01-$mp-$ap";
			$primer_dia_semana = getWeekDay($periodo_inicio);
			$dia_semana = $primer_dia_semana;
			$dia_inicio = 1;
			$dia_fin = (int) $de;
		} else {	
			list($ai, $mi, $di) = split('[/.-]', $_ARGS['_FECHA_INGRESO']); $periodo_inicio = "$di-$mp-$ap";
			$diai = (int) $di;
			$primer_dia_semana = getWeekDay($periodo_inicio);
			$dia_semana = $primer_dia_semana;
			if ($dia_semana == 1) $dia_inicio = (int) $di;
			else {
				if ($dia_semana == 0) $restar_dia_semana = $diai - 7;
				else $restar_dia_semana = $diai - $dia_semana;
				if ($restar_dia_semana < 1) $dia_inicio = (int) $di;
				else {
					$diferencia_dias = $dia_semana - 1;
					$dia_inicio = (int) $di;
					$dia_inicio -= $diferencia_dias;
				}
			}
			$dia_fin = (int) $de;
		}
	}
	$lunes = 0;
	for ($dia=$dia_inicio; $dia<=$dia_fin; $dia++) {
		if ($dia_semana == 7) $dia_semana = 0;
		if ($dia_semana == 1) $lunes++;
		$dia_semana++;
	}
	return intval($lunes);
}

//	obtener el sueldo minimo
function SUELDO_MINIMO() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT Monto
			FROM mastsueldosmin
			WHERE Periodo = (SELECT MAX(Periodo) FROM mastsueldosmin WHERE Periodo <= '".$_ARGS['_PERIODO']."')";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	obtener el sueldo minimo
function ULTIMO_SUELDO_MINIMO() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT Monto
			FROM mastsueldosmin
			WHERE Periodo < (SELECT MAX(Periodo) FROM mastsueldosmin WHERE Periodo <= '".$_ARGS['_PERIODO']."')
			ORDER BY Periodo DESC
			LIMIT 0, 1";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	obtener el tipo de retencion del empleado (M:MONTO; P:PORCENTAJE)
function TIPO_RETENCION() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT rjc.TipoDescuento 
			FROM 
				rh_retencionjudicial rj
				INNER JOIN rh_retencionjudicialconceptos rjc ON (rj.CodOrganismo = rjc.CodOrganismo AND rj.CodRetencion = rjc.CodRetencion)
			WHERE 
				rj.CodPersona = '".$_ARGS['_PERSONA']."' AND 
				rj.FechaResolucion <= '".$_ARGS['_HASTA']."' AND 
				rjc.CodConcepto = '".$_ARGS['_CONCEPTO']."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	if ($field['TipoDescuento'] == "P") $_TIPO = "PORCENTAJE";
	elseif ($field['TipoDescuento'] == "M") $_TIPO = "MONTO";
	return $_TIPO;
}

//	obtener el monto de la retencion judicial del empleado
function RETENCION_JUDICIAL() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT rjc.Descuento 
			FROM 
				rh_retencionjudicial rj
				INNER JOIN rh_retencionjudicialconceptos rjc ON (rj.CodOrganismo = rjc.CodOrganismo AND rj.CodRetencion = rjc.CodRetencion)
			WHERE 
				rj.CodPersona = '".$_ARGS['_PERSONA']."' AND 
				rj.FechaResolucion <= '".$_ARGS['_HASTA']."' AND 
				rjc.CodConcepto = '".$_ARGS['_CONCEPTO']."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	obtener el porcentaje a descontar por impuesto soble la renta
function PORCENTAJE_ISLR() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT Porcentaje
			FROM pr_impuestorenta
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				'".$_ARGS['_PERIODO']."' >= Desde AND '".$_ARGS['_PERIODO']."' <= Hasta";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	obtener meses de antiguedad del empleado
function MESES_ANTIGUEDAD() {
	global $_ARGS;
	global $_PARAMETRO;
	list($anios, $meses, $dias) = getTiempo(formatFechaDMA($_ARGS['_FECHA_INGRESO']), formatFechaDMA($_ARGS['_HASTA']));
	$cantidad = $meses + ($anios * 12);
	return intval($cantidad);
}

//	obtener el sueldo normal
function SUELDO_NORMAL() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT SueldoNormal
			FROM rh_sueldos
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				Periodo = '".$_ARGS['_PERIODO']."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	obtener el sueldo normal
function SUELDO_NORMAL_DIARIO() {
	global $_ARGS;
	global $_PARAMETRO;
	$SueldoNormal = SUELDO_NORMAL();
	return floatval(round(($SueldoNormal / 30), 2));
}

//	devuelve el ultimo sueldo basico del empleado (ultimo periodo anterior)
function ULTIMO_SUELDO_BASICO() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT SueldoBasico
			FROM rh_sueldos
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				Periodo < '".$_ARGS['_PERIODO']."'
			ORDER BY Periodo DESC
			LIMIT 0, 1";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	devuelve el ultimo sueldo normal del empleado (ultimo periodo anterior)
function ULTIMO_SUELDO_NORMAL() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT SueldoNormal
			FROM rh_sueldos
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				Periodo < '".$_ARGS['_PERIODO']."'
			ORDER BY Periodo DESC
			LIMIT 0, 1";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field['SueldoNormal']);
	} else return 0;
}

//	devuelve el ultimo sueldo normal diario del empleado (ultimo periodo anterior)
function ULTIMO_SUELDO_NORMAL_DIARIO() {
	global $_ARGS;
	global $_PARAMETRO;
	$UltimoSueldoNormal = ULTIMO_SUELDO_NORMAL();
	return floatval(round(($UltimoSueldoNormal / 30), 2));
}

//	obtener el sueldo integral
function SUELDO_INTEGRAL() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT SueldoIntegral
			FROM rh_sueldos
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				Periodo = '".$_ARGS['_PERIODO']."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	devuelve el ultimo sueldo integral del empleado (ultimo periodo anterior)
function ULTIMO_SUELDO_INTEGRAL() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT SueldoIntegral
			FROM rh_sueldos
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				Periodo < '".$_ARGS['_PERIODO']."'
			ORDER BY Periodo DESC
			LIMIT 0, 1";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field['SueldoIntegral']);
	} else return 0;
}

//	devuelve el ultimo sueldo integral diario del empleado (ultimo periodo anterior)
function ULTIMO_SUELDO_INTEGRAL_DIARIO() {
	global $_ARGS;
	global $_PARAMETRO;
	$UltimoSueldoNormal = ULTIMO_SUELDO_INTEGRAL();
	return floatval(round(($UltimoSueldoNormal / 30), 2));
}

//	obtener el sueldo integral
function SUELDO_INTEGRAL_PARCIAL() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT SueldoIntegralParcial
			FROM rh_sueldos
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				Periodo = '".$_ARGS['_PERIODO']."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	devuelve el ultimo sueldo integral del empleado (ultimo periodo anterior)
function ULTIMO_SUELDO_INTEGRAL_PARCIAL() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT SueldoIntegralParcial
			FROM rh_sueldos
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				Periodo < '".$_ARGS['_PERIODO']."'
			ORDER BY Periodo DESC
			LIMIT 0, 1";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field['SueldoIntegralParcial']);
	} else return 0;
}

//	devuelve el ultimo sueldo integral diario del empleado (ultimo periodo anterior)
function ULTIMO_SUELDO_INTEGRAL_PARCIAL_DIARIO() {
	global $_ARGS;
	global $_PARAMETRO;
	$UltimoSueldoNormal = ULTIMO_SUELDO_INTEGRAL_PARCIAL();
	return floatval(round(($UltimoSueldoNormal / 30), 2));
}

//	obtener la suma de las bonificaciones
function BONOS() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT SUM(tnec.Monto) AS Bonos
			FROM
				pr_concepto c
				INNER JOIN pr_tiponominaempleadoconcepto tnec ON (tnec.CodConcepto = c.CodConcepto)
			WHERE
				c.FlagBonoRemuneracion = 'S' AND
				tnec.Periodo = '".$_ARGS['_PERIODO']."' AND
				tnec.CodPersona = '".$_ARGS['_PERSONA']."'
			GROUP BY tnec.CodPersona";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field['Bonos']);
	} else return 0;
}

//	obtener la remuneracion diaria
function REMUNERACION_DIARIA() {
	global $_ARGS;
	global $_PARAMETRO;
	$Sueldo = $_ARGS['_SUELDO_NORMAL_DIARIO'];
	$Bonos = round(BONOS() / 30, 2);
	$Diario = $Sueldo + $Bonos;
	return floatval($Diario);
}

//	obtener la remuneracion diaria
function ULTIMA_REMUNERACION_DIARIA() {
	global $_ARGS;
	global $_PARAMETRO;
	$Sueldo = ULTIMO_SUELDO_NORMAL_DIARIO();
	$Bonos = round(BONOS() / 30, 2);
	$Diario = $Sueldo + $Bonos;
	return floatval($Diario);
}

//	devuelve el total de ingresos para un proceso
function TOTAL_INGRESOS($CodTipoProceso=NULL, $Periodo=NULL) {
	global $_ARGS;
	global $_PARAMETRO;
	##
	if (!$CodTipoProceso) $CodTipoProceso = $_ARGS['_PROCESO'];
	if (!$Periodo) $Periodo = $_ARGS['_PERIODO'];
	##
	$sql = "SELECT TotalIngresos
			FROM pr_tiponominaempleado
			WHERE
				CodTipoNom = '".$_ARGS['_NOMINA']."' AND
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				CodOrganismo = '".$_ARGS['_ORGANISMO']."' AND
				CodTipoProceso = '".$CodTipoProceso."' AND
				Periodo = '".$Periodo."'";
	$TotalIngresos = getVar3($sql);
	return floatval($TotalIngresos);
}

//	devuelve el total de ingresos para un proceso
function ULTIMO_TOTAL_INGRESOS($CodTipoProceso=NULL) {
	global $_ARGS;
	global $_PARAMETRO;
	##
	if (!$CodTipoProceso) $CodTipoProceso = $_ARGS['_PROCESO'];
	##
	$sql = "SELECT TotalIngresos
			FROM pr_tiponominaempleado
			WHERE
				CodTipoNom = '".$_ARGS['_NOMINA']."' AND
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				CodOrganismo = '".$_ARGS['_ORGANISMO']."' AND
				CodTipoProceso = '".$CodTipoProceso."' AND
				Periodo < '".$_ARGS['_PERIODO']."'
			ORDER BY Periodo DESC
			LIMIT 0, 1";
	$TotalIngresos = getVar3($sql);
	return floatval($TotalIngresos);
}

//	devuelve si el periodo cumple el trimestre
function TRIMESTRE() {
	global $_ARGS;
	global $_PARAMETRO;
	list($Anio, $Mes) = split("[./-]", $_ARGS['_PERIODO']);
	if ($Mes == "03" || $Mes == "06" || $Mes == "09" || $Mes == "12") return true;
	else return false;
}

//	devuelve los dias para el deposito de antiguedad
function DIAS_ANTIGUEDAD_TRIMESTRAL() {
	global $_ARGS;
	global $_PARAMETRO;
	if ($_ARGS['_MES_PROCESO'] <= "03") $InicioTri = $_ARGS['_ANO_PROCESO']."-01-01";
	elseif ($_ARGS['_MES_PROCESO'] <= "06") $InicioTri = $_ARGS['_ANO_PROCESO']."-04-01";
	elseif ($_ARGS['_MES_PROCESO'] <= "09") $InicioTri = $_ARGS['_ANO_PROCESO']."-07-01";
	elseif ($_ARGS['_MES_PROCESO'] <= "12") $InicioTri = $_ARGS['_ANO_PROCESO']."-10-01";
	if ($_ARGS['_FECHA_INGRESO'] <= $InicioTri) $Desde = $InicioTri; else $Desde = $_ARGS['_FECHA_INGRESO'];
	if ($_ARGS['_ESTADO'] == "A") $Hasta = $_ARGS['_HASTA']; else $Hasta = $_ARGS['_FECHA_EGRESO'];
	if (($_ARGS['_ESTADO'] == "A" && TRIMESTRE()) || $_ARGS['_ESTADO'] == "I") {
		list($Anios, $Meses, $Dias) = getTiempo(formatFechaDMA($Desde), formatFechaDMA($Hasta));
		$Dias++;
	}
	if ($Meses >= 3) $DiasAntiguedad = $_PARAMETRO['DIASANTIG'] * 3;
	else $DiasAntiguedad = ($_PARAMETRO['DIASANTIG'] * $Meses) + ($_PARAMETRO['DIASANTIG'] / 30 * $Dias);
	return floatval(round($DiasAntiguedad, 2));
}

//	devuelve el monto de un concepto calculado
function CONCEPTO($CONCEPTO) {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT Monto
			FROM pr_tiponominaempleadoconcepto
			WHERE
				Periodo = '".$_ARGS['_PERIODO']."' AND
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				CodConcepto = '".$CONCEPTO."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	devuelve el monto de un concepto calculado
function ULTIMO_CONCEPTO($CONCEPTO) {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT Monto
			FROM pr_tiponominaempleadoconcepto
			WHERE
				Periodo < '".$_ARGS['_PERIODO']."' AND
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				CodConcepto = '".$CONCEPTO."'
			ORDER BY Periodo DESC
			LIMIT 0, 1";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	devuelve el nro de dias pendientes de pago de vacaciones
function DIAS_VACACIONES_PENDIENTES() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT PendientePago
			FROM rh_vacacionperiodo
			WHERE CodPersona = '".$_ARGS['_PERSONA']."'
			ORDER BY NroPeriodo DESC
			LIMIT 0, 1";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	obtener meses de antiguedad del empleado
function MESES_FRACCION() {
	global $_ARGS;
	global $_PARAMETRO;
	list($anios, $meses, $dias) = getTiempo(formatFechaDMA($_ARGS['_FECHA_INGRESO']), formatFechaDMA($_ARGS['_FECHA_EGRESO']));
	return intval($meses);
}

//	
function MESES_FRACCION_ACTUAL() {
	global $_ARGS;
	global $_PARAMETRO;
	list($anios, $meses) = split("[./-]", $_ARGS['_PERIODO']);
	return intval($meses);
}

//	
function MESES_FRACCION_EGRESO() {
	global $_ARGS;
	global $_PARAMETRO;
	list($Anio, $Mes, $Dia) = split("[./-]", $_ARGS['_FECHA_EGRESO']);
	if ($Dia < getDiasMes("$Anio-$Mes")) return (intval($Mes) - 1);
	else return intval($Mes);
}

//	devuelve el nro de dias pendientes de disfrute de vacaciones
function DIAS_DISFRUTE_PENDIENTE() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT Pendientes
			FROM rh_vacacionperiodo
			WHERE CodPersona = '".$_ARGS['_PERSONA']."'
			ORDER BY NroPeriodo DESC
			LIMIT 0, 1";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	devuelve el nro de dias por derecho pediente del empleado
function DIAS_POR_DERECHO_PENDIENTE() {
	global $_ARGS;
	global $_PARAMETRO;
	##	tabla de disrute
	$sql = "SELECT * FROM rh_vacaciontabla";
	$query_periodos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_periodos = mysql_fetch_array($query_periodos)) {
		$id = $field_periodos['NroAnio'];
		$_DISFRUTES[$id] = $field_periodos['DiasDisfrutes'];
		$_ADICIONAL[$id] = $field_periodos['DiasAdicionales'];
	}
	##	obtengo los dias de derecho
	list($AniosAntecedente, $MesesAntecedente, $DiasAntecedente) = getTiempoAntecedente($_ARGS['_PERSONA']);
	list($AniosOrganismo, $MesesOrganismo, $DiasOrganismo) = getEdad(formatFechaDMA($_ARGS['_FECHA_INGRESO']), formatFechaDMA($_ARGS['_HASTA']));
	if ($_PARAMETRO['VACANTECEDENT'] == "S") {
		$_DiasDisfrutes = $_DISFRUTES[$AniosOrganismo+1+$AniosAntecedente];
		$_DiasAdicionales = $_ADICIONAL[$AniosOrganismo+1];
		$Derecho = $_DiasDisfrutes + $_DiasAdicionales;
	} else $Derecho = $_DISFRUTES[$AniosOrganismo+1] + $_ADICIONAL[$AniosOrganismo+1];
	return intval($Derecho);
}

//	devuelve el monto pediente del empleado
function PROCESOS_PENDIENTES($CodTipoProceso=NULL, $Periodo=NULL) {
	global $_ARGS;
	global $_PARAMETRO;
	##
	$filtro = "";
	if ($CodTipoProceso != "") $filtro .= " AND CodTipoProceso = '".$CodTipoProceso."'";
	if ($Periodo != "") $filtro .= " AND Periodo = '".$CodTipoProceso."'";
	##	consulto la suma de los procesos pendientes
	$sql = "SELECT SUM(TotalNeto) AS Total
			FROM pr_tiponominaempleado
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				EstadoPago = 'PE'
				$filtro
			GROUP BY CodPersona";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	devuelve el nro de dias pediente del empleado
function PROCESOS_PENDIENTES_NRO($CodTipoProceso=NULL, $Periodo=NULL) {
	global $_ARGS;
	global $_PARAMETRO;
	##
	$filtro = "";
	if ($CodTipoProceso != "") $filtro .= " AND CodTipoProceso = '".$CodTipoProceso."'";
	if ($Periodo != "") $filtro .= " AND Periodo = '".$CodTipoProceso."'";
	##	consulto la suma de los procesos pendientes
	$sql = "SELECT COUNT(*) AS Nro
			FROM pr_tiponominaempleado
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				EstadoPago = 'PE'
				$filtro
			GROUP BY CodPersona";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	devuelve el acumulado de antiguedad
function ANTIGUEDAD_ACUMULADA() {
	global $_ARGS;
	global $_PARAMETRO;
	##
	$sql = "SELECT (AcumuladoInicialProv + AcumuladoProv) AS Acumulado
			FROM pr_acumuladofideicomiso
			WHERE CodPersona = '".$_ARGS['_PERSONA']."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	devuelve el acumulado de antiguedad
function ANTIGUEDAD_ACUMULADA_DIAS() {
	global $_ARGS;
	global $_PARAMETRO;
	##
	$sql = "SELECT (AcumuladoInicialDias + AcumuladoProvDias) AS AcumuladoDias
			FROM pr_acumuladofideicomiso
			WHERE CodPersona = '".$_ARGS['_PERSONA']."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	devuelve el acumulado de antiguedad
function ANTIGUEDAD_ACUMULADA_FRACCION() {
	global $_ARGS;
	global $_PARAMETRO;
	##
	$sql = "SELECT (SUM(Transaccion) + SUM(Complemento)) AS Fraccion
			FROM pr_acumuladofideicomisodetalle
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				FlagFraccionado = 'S'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	devuelve el acumulado de antiguedad
function ANTIGUEDAD_ACUMULADA_FRACCION_DIAS() {
	global $_ARGS;
	global $_PARAMETRO;
	##
	$sql = "SELECT (SUM(Dias) + SUM(DiasAdicional)) AS FraccionDias
			FROM pr_acumuladofideicomisodetalle
			WHERE
				CodPersona = '".$_ARGS['_PERSONA']."' AND
				FlagFraccionado = 'S'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	devuelve el acumulado de antiguedad
function FIDEICOMISO_ACUMULADO() {
	global $_ARGS;
	global $_PARAMETRO;
	##
	$sql = "SELECT (AcumuladoInicialFide + AcumuladoFide) AS Acumulado
			FROM pr_acumuladofideicomiso
			WHERE CodPersona = '".$_ARGS['_PERSONA']."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	devuelve el salario por jubilacion
function SALARIO_JUBILACION() {
	global $_ARGS;
	global $_PARAMETRO;
	##
	$sql = "SELECT MontoJubilacion FROM mastempleado WHERE CodPersona = '".$_ARGS['_PERSONA']."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return floatval($field[0]);
	} else return 0;
}

//	------------------------------------------
//	LISTA DE FUNCIONES ADICIONALES
//	------------------------------------------
//	obtener los valores de los parametros
function PARAMETROS_FORMULA() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT * FROM mastparametros";
	$query_parametro = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_parametro = mysql_fetch_array($query_parametro)) {
		$id = "P_".$field_parametro['ParametroClave'];
		$_PARAMETRO[$id] = $field_parametro['ValorParam'];
	}
	return $_PARAMETRO;
}

//	obtener sueldo basico
function SUELDO_BASICO() {
	global $_ARGS;
	global $_PARAMETRO;
	$sum_sueldo = 0;
	$sql = "SELECT
				  en.Fecha,
				  en.FechaHasta,
				  ns.SueldoPromedio AS SueldoBasico,
				  e.Estado,
				  e.Fegreso
			FROM 
				  rh_empleadonivelacion en
				  INNER JOIN mastempleado e ON (en.CodPersona = e.CodPersona)
				  INNER JOIN rh_puestos p ON (en.CodCargo = p.CodCargo)
				  INNER JOIN rh_nivelsalarial ns ON (p.CategoriaCargo = ns.CategoriaCargo AND p.Grado = ns.Grado)
			WHERE
				  en.CodOrganismo = '".$_ARGS['_ORGANISMO']."' AND
				  en.CodPersona = '".$_ARGS['_PERSONA']."' AND
				  en.TipoAccion <> 'ET' AND
				  ((en.FechaHasta = '0000-00-00' AND en.Fecha <= '".$_ARGS['_HASTA']."') OR
				   ('".$_ARGS['_DESDE']."' >= en.Fecha AND
				    '".$_ARGS['_DESDE']."' <= en.FechaHasta))
			ORDER BY en.Fecha";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field = mysql_fetch_array($query)) {
		if ($field['Fecha'] < $_ARGS['_DESDE']) $desde = $_ARGS['_DESDE'];
		else $desde = $field['Fecha'];
		if ($field['FechaHasta'] == "0000-00-00" || $field['FechaHasta'] > $_ARGS['_HASTA']) $hasta = $_ARGS['_HASTA'];
		else $hasta = $field['FechaHasta'];
		if ($field['Estado'] == "A") { $dias = DIAS_FECHA($desde, $hasta); }
		else {
			if ($field['Fegreso'] < $_ARGS['_DESDE']) $dias = 0;
			else $dias = DIAS_FECHA($_ARGS['_DESDE'], $field['Fegreso']);
		}
		$monto = round(($field['SueldoBasico'] / $_PARAMETRO['MAXDIASMES'] * $dias), 2);
		$sum_sueldo += $monto;
	}
	return $sum_sueldo;
}

//	obtener los dias de sueldo basico
function DIAS_SUELDO_BASICO() {
	global $_ARGS;
	global $_PARAMETRO;
	if ($_ARGS['_ESTADO'] == "A") {
		if ($_ARGS['_FECHA_INGRESO'] < $_ARGS['_DESDE']) return DIAS_FECHA($_ARGS['_DESDE'], $_ARGS['_HASTA']);
		else return DIAS_FECHA($_ARGS['_FECHA_INGRESO'], $_ARGS['_HASTA']);
	} else {
		if ($_ARGS['_FECHA_EGRESO'] < $_ARGS['_DESDE']) return 0;
		else return DIAS_FECHA($_ARGS['_DESDE'], $_ARGS['_FECHA_EGRESO']);
	}
}

//	obtener la fecha desde y hasta del proceso
function FECHA_PROCESO() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT
				FechaDesde,
				FechaHasta
			FROM pr_procesoperiodo
			WHERE
				CodOrganismo = '".$_ARGS['_ORGANISMO']."' AND
				CodTipoNom = '".$_ARGS['_NOMINA']."' AND
				Periodo = '".$_ARGS['_PERIODO']."' AND
				CodTipoProceso = '".$_ARGS['_PROCESO']."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return array($field['FechaDesde'], $field['FechaHasta']);
}

//	obtener total de dias del proceso
function DIAS_PROCESO() {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT
				FechaDesde,
				FechaHasta
			FROM pr_procesoperiodo
			WHERE
				CodOrganismo = '".$_ARGS['_ORGANISMO']."' AND
				CodTipoNom = '".$_ARGS['_NOMINA']."' AND
				Periodo = '".$_ARGS['_PERIODO']."' AND
				CodTipoProceso = '".$_ARGS['_PROCESO']."' AND
				FlagProcesado = 'N'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return DIAS_FECHA($field['FechaDesde'], $field['FechaHasta']);
}

//	obtener dias entre dos fechas
function DIAS_FECHA($_DESDE, $_HASTA) {
	global $_ARGS;
	global $_PARAMETRO;
	$sql = "SELECT DATEDIFF('$_HASTA', '$_DESDE');";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$field = mysql_fetch_array($query);
	$dias = ++$field[0];
	if (substr($_HASTA, 5, 5) == "02-28") $dias+=2;
	elseif (substr($_HASTA, 5, 5) == "02-29") $dias+=1;
	return intval($dias);
}
?>