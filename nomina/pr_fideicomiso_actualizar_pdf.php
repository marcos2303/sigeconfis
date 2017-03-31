<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
$filtro = "";
if ($fEstado == "") $filtro.=" AND (e.Estado = 'A')"; else $cEstado = "checked";
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (tne.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodTipoNom != "") { $cCodTipoNom = "checked"; $filtro.=" AND (tne.CodTipoNom = '".$fCodTipoNom."')"; } else $dCodTipoNom = "disabled";
if ($fPeriodo != "") { $cPeriodo = "checked"; $filtro.=" AND (tne.Periodo = '".$fPeriodo."')"; } else $dPeriodo = "disabled";
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $Ahora;
		global $field_resumen;
		global $_POST;
		global $_GET;
		extract($_POST);
		extract($_GET);
		##
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $fCodOrganismo);
		$NomOrganismo = getValorCampo("mastorganismos", "CodOrganismo", "Organismo", $fCodOrganismo);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPRHPR"]);
		##
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 10, 5, 10, 10);
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->SetXY(235, 5); $this->Cell(10, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(235, 10); $this->Cell(10, 5, utf8_decode('Página: '), 0, 0, 'L');
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(260, 5, utf8_decode('ACUMULADO DE ANTIGUEDAD'), 0, 1, 'C', 0);
		$this->SetFont('Arial', 'BI', 8);
		$this->SetXY(10, 24); $this->Cell(260, 5, utf8_decode('Periodo '.$fPeriodo), 0, 1, 'C', 0);
		$this->Ln(5);
		##
		$this->SetFont('Arial', 'B', 8);
		$this->SetWidths(array(18, 85, 20, 15, 13, 13, 13, 13, 13, 10, 17, 17, 17));
		$this->SetAligns(array('R', 'L', 'C', 'R', 'R', 'R', 'R', 'R', 'R', 'C', 'R', 'R', 'R'));
		$this->Row(array('Documento',
						 'Nombre Completo',
						 'F.Ingreso',
						 'Sueldo Mensual',
						 'Bonos',
						 'Diario',
						 'Ali. Vac.',
						 'Ali. Fin.',
						 'Sueldo + Alic.',
						 'Dias',
						 'Prest. Antig.',
						 'Prest. Compl.',
						 'Total'));
		$this->Ln(2);
	}
	
	//	Pie de página.
	function Footer() {
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(0, 0, 0);
		
		$this->SetFont('Arial', 'B', 8);
		$this->SetXY(10, 193); $this->MultiCell(65, 4, 'PREPARADO POR', 0, 'C');
		$this->SetXY(110, 193); $this->MultiCell(65, 4, 'REVISADO POR', 0, 'C');
		$this->SetXY(210, 193); $this->MultiCell(65, 4, 'CONFORMADO POR', 0, 'C');
		$this->SetXY(10, 197); $this->MultiCell(65, 4, 'LICDA. ANDREINA ZAPATA', 0, 'C');
		$this->SetXY(110, 197); $this->MultiCell(65, 4, 'LICDA. CARMEN ALFONZO', 0, 'C');
		$this->SetXY(210, 197); $this->MultiCell(65, 4, 'LICDA. ROSIS REQUENA', 0, 'C');
		$this->SetXY(10, 201); $this->MultiCell(65, 4, 'ANALISTA DE RECURSOS HUMANOS I', 0, 'C');
		$this->SetXY(110, 201); $this->MultiCell(65, 4, 'ANALISTA DE RECURSOS HUMANOS II', 0, 'C');
		$this->SetXY(210, 201); $this->MultiCell(65, 4, 'DIRECTORA DE RECURSOS HUMANOS (E)', 0, 'C');
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('L', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(5, 30);
$pdf->AddPage();
//---------------------------------------------------
//	consulto
//	consulto lista
$sql = "SELECT
			p.CodPersona,
			p.NomCompleto,
			p.Ndocumento,
			e.CodEmpleado,
			e.Fingreso,
			pp.FechaDesde,
			pp.FechaHasta,
			bp.Ncuenta,
			tne.CodTipoProceso,
			tne.TotalIngresos,
			tnec.Cantidad,
			tnec.Monto
		FROM
			pr_tiponominaempleado tne
			INNER JOIN mastpersonas p ON (p.CodPersona = tne.CodPersona)
			INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
			INNER JOIN pr_procesoperiodo pp ON (tne.CodTipoNom = pp.CodTipoNom AND
												tne.Periodo = pp.Periodo AND
												tne.CodOrganismo = pp.CodOrganismo AND
												tne.CodTipoProceso = pp.CodTipoProceso)
			LEFT JOIN pr_tiponominaempleadoconcepto tnec ON (tnec.CodTipoNom = tne.CodTipoNom AND
															 tnec.Periodo = tne.Periodo AND
															 tnec.CodPersona = tne.CodPersona AND
															 tnec.CodOrganismo = tne.CodOrganismo AND
															 tnec.CodTipoProceso = tne.CodTipoProceso AND
															 tnec.CodConcepto = '".$_PARAMETRO['PROVISION']."')
			LEFT JOIN bancopersona bp ON (bp.CodPersona = p.CodPersona AND bp.Aportes = 'FI')
		WHERE
			(tne.CodTipoProceso = 'FIN' OR tne.CodTipoProceso = 'PPA') AND			
			((e.Estado = 'I' AND SUBSTRING(e.Fegreso, 1, 7) <> '$fPeriodo' AND e.Fegreso > pp.FechaHasta) OR (e.Estado = 'A'))			
			$filtro
		ORDER BY $fOrderBy";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {
	$SueldoMensual = getVar2("rh_sueldos", "SueldoNormal", array("Periodo","CodPersona"), array($fPeriodo,$field['CodPersona']));
	$Bono = getBono($field['CodPersona'], $fPeriodo);
	$RemuneracionMensual = $SueldoMensual + $Bono;
	$SueldoDiario = round(($SueldoMensual / 30), 2);
	$RemuneracionDiaria = round(($RemuneracionMensual / 30), 2);
	$AliVac = getVar2("pr_tiponominaempleadoconcepto", "Monto", array("CodTipoNom","Periodo","CodPersona","CodOrganismo","CodTipoProceso","CodConcepto"), array($fCodTipoNom,$fPeriodo,$field['CodPersona'],$fCodOrganismo,$field['CodTipoProceso'],$_PARAMETRO['ALIVAC']));
	$AliFin = getVar2("pr_tiponominaempleadoconcepto", "Monto", array("CodTipoNom","Periodo","CodPersona","CodOrganismo","CodTipoProceso","CodConcepto"), array($fCodTipoNom,$fPeriodo,$field['CodPersona'],$fCodOrganismo,$field['CodTipoProceso'],$_PARAMETRO['ALIFIN']));
	$SueldoAlicuotas = $RemuneracionDiaria + $AliVac + $AliFin;
	if ($field['TotalIngresos'] > 0) $DiasAdicional = getDiasAdicionalesTrimestral($field['Fingreso'], $field['FechaDesde'], $field['FechaHasta'], $field['Fegreso']); else $DiasAdicional = 0;
	if ($DiasAdicional) $Complemento = calculo_antiguedad_complemento_trimestral($field['CodPersona'], $field['Fingreso'], $field['FechaDesde'], $field['FechaHasta']);
	else $Complemento = 0;
	$Complemento = round($Complemento, 2);
	$Total = $field['Monto'] + $Complemento;
	##
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	if (++$i % 2 == 0) $pdf->SetFillColor(240, 240, 240); else $pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Row(array(number_format($field['Ndocumento'], 0, '', '.'),
					utf8_decode($field['NomCompleto']),
					formatFechaDMA($field['Fingreso']),
					number_format($SueldoMensual, 2, ',', '.'),
					number_format($Bono, 2, ',', '.'),
					number_format($SueldoDiario, 2, ',', '.'),
					number_format($AliVac, 2, ',', '.'),
					number_format($AliFin, 2, ',', '.'),
					number_format($SueldoAlicuotas, 2, ',', '.'),
					number_format($field['Cantidad'], 2, ',', '.'),
					number_format($field['Monto'], 2, ',', '.'),
					number_format($Complemento, 2, ',', '.'),
					number_format($Total, 2, ',', '.')));
	$SumMonto += $field['Monto'];
	$SumComplemento += $Complemento;
	$SumTotal += $Total;
}
##
$pdf->Cell(213, 5);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(200, 200, 200);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetWidths(array(17, 17, 17));
$pdf->SetAligns(array('R', 'R', 'R'));
$pdf->Row(array(number_format($SumMonto, 2, ',', '.'),
				number_format($SumComplemento, 2, ',', '.'),
				number_format($SumTotal, 2, ',', '.')));
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>