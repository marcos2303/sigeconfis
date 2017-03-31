<?php
require('fpdf.php');
require('fphp_nomina.php');
connect();
//---------------------------------------------------

class PDF extends FPDF
{
var $ftiponom;
var $ftproceso;
var $fperiodo;

function SetTipoNom($tn)
{
	$this->ftiponom=$tn;
}

function SetProceso($tp)
{
	$this->ftproceso=$tp;
}

function SetPeriodo($p)
{
	$this->fperiodo=$p;
}

function SetAsignacion($a)
{
	$this->chkasignacion=$a;
}

function SetDeduccion($d)
{
	$this->chkdeduccion=$d;
}

function SetOrganismo($o)
{
	$this->forganismo=$o;
}

//	Cabecera de página
function Header()
{
	//	Tipo de Nomina
	$sql = "SELECT Nomina FROM tiponomina WHERE CodTipoNom = '".$this->ftiponom."'";
	$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);
	
	//	Tipo de Proceso
	$sql = "SELECT Descripcion FROM pr_tipoproceso WHERE CodTipoProceso = '".$this->ftproceso."'";
	$query_proceso = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_proceso) != 0) $field_proceso = mysql_fetch_array($query_proceso);
	
	//	Periodo
	list($fecha_desde, $fecha_hasta) = getPeriodoProceso($this->ftproceso, $this->fperiodo, $this->ftiponom);
	$periodo_fecha = "DESDE: ".formatFechaDMA($fecha_desde)." HASTA: ".formatFechaDMA($fecha_hasta);
	
	/*
	$periodo = getPeriodoLetras($this->fperiodo);
	list($anio, $mes)=SPLIT( '[/.-]', $this->fperiodo); $m = (int) $mes;
	$dias = getDiasMes($anio, $m);
	if ($this->ftproceso == "ADE") $dias = 15;
	if ($dias != 15) $dias = 30;
	$periodo_fecha = "DESDE: 01/$mes/$anio HASTA: $dias/$mes/$anio";
	*/
	
	$this->SetDrawColor(255, 255, 255); $this->SetFillColor(255, 255, 255); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', 'B', 8);
	
	$this->Cell(190, 5, ('CONTRALORIA DEL ESTADO'), 0, 1, 'L');
	$this->Cell(190, 5, ('DIRECCION DE RECURSOS HUMANOS'), 0, 1, 'L');
	$this->Cell(190, 5, ('TIPO DE NOMINA '.$field_nomina['Nomina']), 0, 1, 'L');
	$this->Cell(190, 5, ($field_proceso['Descripcion']), 0, 1, 'L');
	$this->Cell(190, 5, ($periodo_fecha), 0, 1, 'L');
	if ($this->chkasignacion == "I" && $this->chkdeduccion == "")
		$this->Cell(190, 5, ("LISTA DE ASIGNACIONES"), 0, 1, 'L');
	elseif ($this->chkasignacion == "" && $this->chkdeduccion == "D")	
		$this->Cell(190, 5, ("LISTA DE DEDUCCIONES"), 0, 1, 'L');
    $this->Cell(190, 5, ('PAGINA ').$this->PageNo().' DE {nb}', 0, 1, 'L');
	
	$this->SetFillColor(200, 200, 200);
	$this->SetFont('Arial', 'B', 6);
	$this->Cell(16, 6, ('CEDULA'), 1, 0, 'R', 1);
	$this->Cell(68, 6, ('NOMBRES Y APELLIDOS'), 1, 0, 'L', 1);
	$this->Cell(12, 6, ('CARGO'), 1, 0, 'C', 1);
	
	if ($this->chkasignacion == "I" && $this->chkdeduccion == "") {
		if ($this->chkasignacion == "I") $ftipo = "I";
		elseif ($this->chkdeduccion == "D") $ftipo = "D";
		
		$sql = "SELECT
					  tnec.CodConcepto,
					  c.Abreviatura AS NomConcepto
				FROM
					  pr_tiponominaempleadoconcepto tnec
					  INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND c.Tipo = '".$ftipo."')
				WHERE
					  tnec.CodTipoNom = '".$this->ftiponom."' AND
					  tnec.Periodo = '".$this->fperiodo."' AND
					  tnec.CodOrganismo = '".$this->forganismo."' AND
					  tnec.CodTipoProceso = '".$this->ftproceso."'
				GROUP BY CodConcepto
				ORDER BY CodConcepto";
		$query_conceptos = mysql_query($sql) or die ($sql.mysql_error());
		$rows_conceptos = mysql_num_rows($query_conceptos);
		while ($field_conceptos = mysql_fetch_array($query_conceptos)) {
			$this->Cell(16, 6, ($field_conceptos['NomConcepto']), 1, 0, 'R', 1);
		}
		$this->Cell(16, 6, ('T.ASIG.'), 1, 1, 'R', 1);
	}
	
	elseif ($this->chkasignacion == "" && $this->chkdeduccion == "D") {
		$this->Cell(16, 6, ('T.ASIG.'), 1, 0, 'C', 1);
		
		if ($this->chkasignacion == "I") $ftipo = "I";
		elseif ($this->chkdeduccion == "D") $ftipo = "D";
		
		$sql = "SELECT
					  tnec.CodConcepto,
					  c.Abreviatura AS NomConcepto
				FROM
					  pr_tiponominaempleadoconcepto tnec
					  INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND c.Tipo = '".$ftipo."')
				WHERE
					  tnec.CodTipoNom = '".$this->ftiponom."' AND
					  tnec.Periodo = '".$this->fperiodo."' AND
					  tnec.CodOrganismo = '".$this->forganismo."' AND
					  tnec.CodTipoProceso = '".$this->ftproceso."'
				GROUP BY CodConcepto
				ORDER BY CodConcepto";
		$query_conceptos = mysql_query($sql) or die ($sql.mysql_error());
		$rows_conceptos = mysql_num_rows($query_conceptos);
		while ($field_conceptos = mysql_fetch_array($query_conceptos)) {
			$this->Cell(16, 6, ($field_conceptos['NomConcepto']), 1, 0, 'R', 1);
		}
		$this->Cell(16, 6, ('T.DEDUC.'), 1, 0, 'R', 1);
		$this->Cell(16, 6, ('NETO'), 1, 1, 'R', 1);
	}
	
	
}

//	Pie de página
function Footer()
{
    
}
}

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf = new PDF('L', 'mm', 'Legal');
$pdf->AliasNbPages();
$pdf->SetMargins(1, 1, 1);
$pdf->SetAutoPageBreak(1, 1);
//---------------------------------------------------
$pdf->SetTipoNom($ftiponom);
$pdf->SetProceso($ftproceso);
$pdf->SetPeriodo($fperiodo);
$pdf->SetAsignacion($chkasignacion);
$pdf->SetDeduccion($chkdeduccion);
$pdf->SetOrganismo($forganismo);

if ($CodEmpleado != "") {
	$filtro1 = " AND tnec.CodPersona = '".$CodEmpleado."'";
	$filtro2 = " AND ptne.CodPersona = '".$CodEmpleado."'";
}
$pdf->AddPage();
//---------------------------------------------------
$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);


if ($chkasignacion == "I" && $chkdeduccion == "") {
	if ($chkasignacion == "I") $ftipo = "I";
	elseif ($chkdeduccion == "D") $ftipo = "D";
	
	$sql = "SELECT
				  tnec.CodConcepto,
				  c.Abreviatura AS NomConcepto
			FROM
				  pr_tiponominaempleadoconcepto tnec
				  INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND c.Tipo = '".$ftipo."')
			WHERE
				  tnec.CodTipoNom = '".$ftiponom."' AND
				  tnec.Periodo = '".$fperiodo."' AND
				  tnec.CodOrganismo = '".$forganismo."' AND
				  tnec.CodTipoProceso = '".$ftproceso."' $filtro1
			GROUP BY CodConcepto
			ORDER BY CodConcepto";
	$query_conceptos = mysql_query($sql) or die ($sql.mysql_error());
	$rows_conceptos = mysql_num_rows($query_conceptos);
	while ($field_conceptos = mysql_fetch_array($query_conceptos)) {
		$q .= ", (SELECT Monto 
					FROM pr_tiponominaempleadoconcepto 
					WHERE
						CodConcepto = '".$field_conceptos['CodConcepto']."' AND
						CodTipoNom = '".$ftiponom."' AND
						Periodo = '".$fperiodo."' AND
						CodOrganismo = '".$forganismo."' AND
						CodTipoProceso = '".$ftproceso."' AND
						CodPersona = p.CodPersona
					) AS '".$field_conceptos['CodConcepto']."'";
	}
	$sql = "SELECT
				  ptne.CodPersona,
				  p.Ndocumento,
				  p.NomCompleto,
				  c.CodDesc AS CodCargo
				  $q
			FROM
				  pr_tiponominaempleado ptne
				  INNER JOIN mastpersonas p ON (ptne.CodPersona = p.CodPersona)
				  INNER JOIN mastempleado e ON (p.CodPersona = e.CodPersona)
				  INNER JOIN rh_puestos c ON (e.CodCargo = c.CodCargo)
			WHERE
				  ptne.CodTipoNom = '".$ftiponom."' AND
				  ptne.Periodo = '".$fperiodo."' AND
				  ptne.CodOrganismo = '".$forganismo."' AND
				  ptne.CodTipoProceso = '".$ftproceso."' $filtro2
			ORDER BY length(p.Ndocumento), Ndocumento";
	$query_empleados = mysql_query($sql) or die ($sql.mysql_error());
	$rows_empleados = mysql_num_rows($query_empleados); $ln = 0;
	while ($field_empleados = mysql_fetch_array($query_empleados)) {
		$ln++;		
		if ($ln % 2 == 0)$pdf->SetFillColor(240, 240, 240); else $pdf->SetFillColor(255, 255, 255);		
		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(16, 6, number_format($field_empleados['Ndocumento'], 0, '', '.'), 1, 0, 'R', 1);
		$pdf->Cell(68, 6, $field_empleados['NomCompleto'], 1, 0, 'L', 1);
		$pdf->Cell(12, 6, $field_empleados['CodCargo'], 1, 0, 'C', 1);
		
		$total = 0;
		for ($i=4; $i<=$rows_conceptos+3; $i++) {
			$total += $field_empleados[$i];
			$sum_total[$i] += $field_empleados[$i];
			$monto = number_format($field_empleados[$i], 2, ',', '.');
			if ($monto != "0,00") $pdf->Cell(16, 6, $monto, 1, 0, 'R', 1); else $pdf->Cell(16, 6, '', 1, 0, 'R', 1);
		}
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(16, 6, number_format($total, 2, ',', '.'), 1, 0, 'R', 1);
		$pdf->Ln();
	}
	
	$pdf->SetFillColor(200, 200, 200);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(96, 6);
	
	for ($i=4; $i<=$rows_conceptos+3; $i++) {
		$sum_total_conceptos += $sum_total[$i];
		$total = $sum_total[$i];
		$pdf->Cell(16, 6, number_format($total, 2, ',', '.'), 1, 0, 'R', 1);
	}
	$pdf->Cell(16, 6, number_format($sum_total_conceptos, 2, ',', '.'), 1, 0, 'R', 1);
	$pdf->Ln(20);
	//---------------------------------------------------
	
	//	imprimo leyenda
	$sql = "SELECT
				  tnec.CodConcepto,
				  c.Abreviatura,
				  c.Descripcion
			FROM
				  pr_tiponominaempleadoconcepto tnec
				  INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND c.Tipo = '".$ftipo."')
			WHERE
				  tnec.CodTipoNom = '".$ftiponom."' AND
				  tnec.Periodo = '".$fperiodo."' AND
				  tnec.CodOrganismo = '".$forganismo."' AND
				  tnec.CodTipoProceso = '".$ftproceso."' $filtro1
			GROUP BY CodConcepto
			ORDER BY Tipo, CodConcepto";
	$query_leyenda = mysql_query($sql) or die ($sql.mysql_error());
	
	if (mysql_num_rows($query_leyenda) != 0) {
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200);
		
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(30, 5, 'LEYENDA', 1, 0, 'C', 1);
		$pdf->Cell(150, 5, 'DESCRIPCION', 1, 1, 'C', 1);
	}
	
	while ($field_leyenda = mysql_fetch_array($query_leyenda)) {
		$pdf->SetFillColor(200, 200, 200);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(30, 5, $field_leyenda['Abreviatura'], 1, 0, 'L', 1);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(150, 5, $field_leyenda['Descripcion'], 1, 1, 'L', 1);
	}
	
	//---------------------------------------------------
	list($nomelaborado, $carelaborado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "ProcesadoPor");
	list($nomaprobado, $caraprobado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "AprobadoPor");
	//---------------------------------------------------
	$pdf->Ln(15);
	
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(110, 4, ('ELABORADO POR:'), 0, 0, 'L');
	$pdf->Cell(80, 4, ('CONFORMADO POR:'), 0, 1, 'L');
	
	$pdf->Cell(110, 4, ($nomelaborado), 0, 0, 'L');
	$pdf->Cell(80, 4, ($nomaprobado), 0, 1, 'L');
	
	$pdf->Cell(110, 4, ($carelaborado), 0, 0, 'L');
	//$pdf->Cell(80, 4, ($caraprobado), 0, 1, 'L');
	$pdf->Cell(80, 4, ("DIRECTORA DE RECURSOS HUMANOS (E)"), 0, 1, 'L');
}

elseif ($chkasignacion == "" && $chkdeduccion == "D") {
	if ($chkasignacion == "I") $ftipo = "I";
	elseif ($chkdeduccion == "D") $ftipo = "D";
	
	$sql = "SELECT
				  tnec.CodConcepto,
				  c.Abreviatura AS NomConcepto
			FROM
				  pr_tiponominaempleadoconcepto tnec
				  INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND c.Tipo = '".$ftipo."')
			WHERE
				  tnec.CodTipoNom = '".$ftiponom."' AND
				  tnec.Periodo = '".$fperiodo."' AND
				  tnec.CodOrganismo = '".$forganismo."' AND
				  tnec.CodTipoProceso = '".$ftproceso."' $filtro1
			GROUP BY CodConcepto
			ORDER BY CodConcepto";
	$query_conceptos = mysql_query($sql) or die ($sql.mysql_error());
	$rows_conceptos = mysql_num_rows($query_conceptos);
	while ($field_conceptos = mysql_fetch_array($query_conceptos)) {
		$q .= ", (SELECT 
						Monto 
					FROM 
						pr_tiponominaempleadoconcepto 
					WHERE
						CodConcepto = '".$field_conceptos['CodConcepto']."' AND
						CodTipoNom = '".$ftiponom."' AND
						Periodo = '".$fperiodo."' AND
						CodOrganismo = '".$forganismo."' AND
						CodTipoProceso = '".$ftproceso."' AND
						CodPersona = p.CodPersona
					) AS '".$field_conceptos['CodConcepto']."'";
	}
	
	$sql = "SELECT
				  ptne.CodPersona,
				  p.Ndocumento,
				  p.NomCompleto,
				  c.CodDesc AS CodCargo,
				  (SELECT 
						SUM(pr_tnec.Monto)
					FROM 
						pr_tiponominaempleadoconcepto pr_tnec
				  		INNER JOIN pr_concepto pr_c ON (pr_tnec.CodConcepto = pr_c.CodConcepto AND pr_c.Tipo = 'I')
					WHERE
						pr_tnec.CodTipoNom = '".$ftiponom."' AND
						pr_tnec.Periodo = '".$fperiodo."' AND
						pr_tnec.CodOrganismo = '".$forganismo."' AND
						pr_tnec.CodTipoProceso = '".$ftproceso."' AND
						pr_tnec.CodPersona = p.CodPersona
					) AS TotalAsignaciones
				  $q
			FROM
				  pr_tiponominaempleado ptne
				  INNER JOIN mastpersonas p ON (ptne.CodPersona = p.CodPersona)
				  INNER JOIN mastempleado e ON (p.CodPersona = e.CodPersona)
				  INNER JOIN rh_puestos c ON (e.CodCargo = c.CodCargo)
			WHERE
				  ptne.CodTipoNom = '".$ftiponom."' AND
				  ptne.Periodo = '".$fperiodo."' AND
				  ptne.CodOrganismo = '".$forganismo."' AND
				  ptne.CodTipoProceso = '".$ftproceso."' $filtro2
			ORDER BY length(p.Ndocumento), Ndocumento";
	$query_empleados = mysql_query($sql) or die ($sql.mysql_error());
	$rows_empleados = mysql_num_rows($query_empleados); $ln = 0;
	while ($field_empleados = mysql_fetch_array($query_empleados)) {
		$ln++;		
		if ($ln % 2 == 0)$pdf->SetFillColor(240, 240, 240); else $pdf->SetFillColor(255, 255, 255);		
		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(16, 6, number_format($field_empleados['Ndocumento'], 0, '', '.'), 1, 0, 'R', 1);
		$pdf->Cell(68, 6, $field_empleados['NomCompleto'], 1, 0, 'L', 1);
		$pdf->Cell(12, 6, $field_empleados['CodCargo'], 1, 0, 'C', 1);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(16, 6, number_format($field_empleados['TotalAsignaciones'], 2, ',', '.'), 1, 0, 'R', 1);
		$sum_total_asignaciones += $field_empleados['TotalAsignaciones'];
		$pdf->SetFont('Arial', '', 8);
		
		$total = 0;
		for ($i=5; $i<=$rows_conceptos+4; $i++) {
			$total += $field_empleados[$i];
			$sum_total[$i] += $field_empleados[$i];
			$monto = number_format($field_empleados[$i], 2, ',', '.');
			if ($monto != "0,00") $pdf->Cell(16, 6, $monto, 1, 0, 'R', 1); else $pdf->Cell(16, 6, '', 1, 0, 'R', 1);
		}
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(16, 6, number_format($total, 2, ',', '.'), 1, 0, 'R', 1);
		$neto = $field_empleados['TotalAsignaciones'] - $total;
		$pdf->Cell(16, 6, number_format($neto, 2, ',', '.'), 1, 0, 'R', 1);
		$pdf->Ln();
	}
	
	$pdf->SetFillColor(200, 200, 200);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(96, 6);
	$pdf->Cell(16, 6, number_format($sum_total_asignaciones, 2, ',', '.'), 1, 0, 'R', 1);
	
	for ($i=5; $i<=$rows_conceptos+4; $i++) {
		$sum_total_conceptos += $sum_total[$i];
		$total = $sum_total[$i];
		$pdf->Cell(16, 6, number_format($total, 2, ',', '.'), 1, 0, 'R', 1);
	}
	$pdf->Cell(16, 6, number_format($sum_total_conceptos, 2, ',', '.'), 1, 0, 'R', 1);
	$sum_total_neto = $sum_total_asignaciones - $sum_total_conceptos;
	$pdf->Cell(16, 6, number_format($sum_total_neto, 2, ',', '.'), 1, 0, 'R', 1);
	$pdf->Ln(20);
	//---------------------------------------------------
	
	//	imprimo leyenda
	$sql = "SELECT
				  tnec.CodConcepto,
				  c.Abreviatura,
				  c.Descripcion
			FROM
				  pr_tiponominaempleadoconcepto tnec
				  INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND c.Tipo = '".$ftipo."')
			WHERE
				  tnec.CodTipoNom = '".$ftiponom."' AND
				  tnec.Periodo = '".$fperiodo."' AND
				  tnec.CodOrganismo = '".$forganismo."' AND
				  tnec.CodTipoProceso = '".$ftproceso."' $filtro1
			GROUP BY CodConcepto
			ORDER BY Tipo, CodConcepto";
	$query_leyenda = mysql_query($sql) or die ($sql.mysql_error());
	
	if (mysql_num_rows($query_leyenda) != 0) {
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200);		
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(30, 5, 'LEYENDA', 1, 0, 'C', 1);
		$pdf->Cell(150, 5, 'DESCRIPCION', 1, 1, 'C', 1);
	}
	
	while ($field_leyenda = mysql_fetch_array($query_leyenda)) {
		$pdf->SetFillColor(200, 200, 200);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(30, 5, $field_leyenda['Abreviatura'], 1, 0, 'L', 1);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(150, 5, $field_leyenda['Descripcion'], 1, 1, 'L', 1);
	}
	
	//---------------------------------------------------
	list($nomelaborado, $carelaborado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "ProcesadoPor");
	list($nomaprobado, $caraprobado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "AprobadoPor");
	//---------------------------------------------------
	$pdf->Ln(15);
	
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(110, 4, ('ELABORADO POR:'), 0, 0, 'L');
	$pdf->Cell(80, 4, ('CONFORMADO POR:'), 0, 1, 'L');
	
	$pdf->Cell(110, 4, ($nomelaborado), 0, 0, 'L');
	$pdf->Cell(80, 4, ($nomaprobado), 0, 1, 'L');
	
	$pdf->Cell(110, 4, ($carelaborado), 0, 0, 'L');
	//$pdf->Cell(80, 4, ($caraprobado), 0, 1, 'L');
	$pdf->Cell(80, 4, ("DIRECTORA DE RECURSOS HUMANOS (E)"), 0, 1, 'L');
}

//---------------------------------------------------
$pdf->Output();
?>  
