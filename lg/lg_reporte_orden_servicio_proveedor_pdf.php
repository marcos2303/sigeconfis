<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
$LF = 0x0A;
$CR = 0x0D;
$nl = sprintf("%c%c",$CR,$LF);
//---------------------------------------------------
$filtro = "";
$filtro_detalle = "";
if ($fCodOrganismo != "") $filtro .= " AND (oc.CodOrganismo = '".$fCodOrganismo."')";
if ($fCodProveedor != "") $filtro .= " AND (oc.CodProveedor = '".$fCodProveedor."')";
if ($fEstadoMast != "") $filtro.=" AND (oc.Estado = '".$fEstadoMast."')";
if ($fFechaPreparacionD != "") $filtro .= " AND (oc.FechaPreparacion >= '".formatFechaAMD($fFechaPreparacionD)."')";
if ($fFechaPreparacionH != "") $filtro .= " AND (oc.FechaPreparacion <= '".formatFechaAMD($fFechaPreparacionH)."')";
if ($fFechaAprobacionD != "") $filtro .= " AND (oc.FechaAprobacion >= '".formatFechaAMD($fFechaAprobacionD)."')";
if ($fFechaAprobacionH != "") $filtro .= " AND (oc.FechaAprobacion <= '".formatFechaAMD($fFechaAprobacionH)."')";
if ($fMontoTotalD != "") $filtro .= " AND (oc.TotalMontoIva >= ".floatval(setNumero($fMontoTotalD)).")";
if ($fMontoTotalH != "") $filtro .= " AND (oc.TotalMontoIva <= ".floatval(setNumero($fMontoTotalH)).")";
##
if ($fCommoditySub != "") $filtro_detalle.=" AND (ocd.CommoditySub = '".$fCommoditySub."')";
if ($fFlagTerminado != "") $filtro_detalle.=" AND (ocd.FlagTerminado = '".$fFlagTerminado."')";
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $Ahora;
		global $field;
		global $_POST;
		extract($_POST);
		##
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $_SESSION['ORGANISMO_ACTUAL']);
		$NomOrganismo = getValorCampo("mastorganismos", "CodOrganismo", "Organismo", $_SESSION['ORGANISMO_ACTUAL']);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPLOGCXP"]);
		##
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 3, 3, 10, 10);
		$this->SetFont('Arial', '', 8);
		$this->SetXY(12, 3); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(12, 8); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->SetXY(245, 3); $this->Cell(10, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(245, 8); $this->Cell(10, 5, utf8_decode('Página: '), 0, 0, 'L');
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->Ln(5);
		$this->Cell(272, 5, utf8_decode('Ordenes de Servicios por Proveedor'), 0, 1, 'C', 0);
		$this->Ln(5);
		##
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(200, 200, 200);
		$this->SetWidths(array(10,15,175,15,16,16,13,12));
		$this->SetAligns(array('R','C','L','R','C','C','C','C'));
		$this->SetFont('Arial', 'B', 8);
		$this->Row(array('#',
						 'Item',
						 utf8_decode('Descripción'),
						 'Cant.',
						 'Fecha Esperada',
						 'Dias Atrasados',
						 'C.C.',
						 'Conf.'));
		$this->Ln(1);
	}
	
	//	Pie de página.
	function Footer() {}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('L', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(3, 3, 3);
$pdf->SetAutoPageBreak(1, 3);
$pdf->AddPage();
//---------------------------------------------------
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetDrawColor(255, 255, 255);
//	consulto
$i = 0;
$NroOrden = 0;
$sql = "SELECT
			oc.CodOrganismo,
			oc.Anio,
			oc.NroOrden,
			oc.NroInterno,
			oc.CodProveedor,
			oc.NomProveedor,
			oc.FechaAprobacion,
			oc.TotalMontoIva AS TotalOrden,
			oc.Estado AS EstadoMast,
			ocd.CommoditySub,
			ocd.Descripcion,
			ocd.CantidadPedida,
			ocd.FechaEsperadaTermino,
			ocd.FlagTerminado,
			ocd.CodCentroCosto,
			DATEDIFF(NOW(),ocd.FechaEsperadaTermino) AS DiasAtrasados
		FROM
			lg_ordenservicio oc
			INNER JOIN lg_ordenserviciodetalle ocd ON (ocd.CodOrganismo = oc.CodOrganismo AND
													   ocd.Anio = oc.Anio AND
													   ocd.NroOrden = oc.NroOrden)
		WHERE 1 $filtro $filtro_detalle
		ORDER BY CodOrganismo, CodProveedor, Anio, NroOrden";
$field = getRecords($sql);
foreach($field as $f) {	++$i;
	if ($Grupo != $f['CodProveedor']) {
		$Grupo = $f['CodProveedor'];
		$Grupo2 = "";
		$NroOrden = 0;
		##	total x proveedor
		$sql = "SELECT SUM(oc.TotalMontoIva)
				FROM lg_ordenservicio oc
				WHERE oc.CodProveedor = '".$f['CodProveedor']."' $filtro
				GROUP BY CodProveedor";
		$TotalProveedor = getVar3($sql);
		##
		$pdf->SetFillColor(230, 230, 230);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(181, 8, utf8_decode($f['NomProveedor']), 0, 0, 'L', 1);
		$pdf->Cell(66, 8, 'Total Proveedor: ', 0, 0, 'R', 1);
		$pdf->SetFont('Arial', 'BU', 8);
		$pdf->Cell(25, 8, number_format($TotalProveedor, 2, ',', '.'), 0, 1, 'R', 1);
	}
	if ($Grupo2 != $f['NroOrden']) {
		$Grupo2 = $f['NroOrden'];
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(10, 8, ++$NroOrden, 0, 0, 'R');
		$pdf->Cell(17, 8, 'O/S: ', 0, 0, 'R');
		$pdf->Cell(15, 8, $f['NroInterno'], 0, 0, 'L');
		$pdf->Cell(30, 8, formatFechaDMA($f['FechaAprobacion']), 0, 0, 'C');
		$pdf->Cell(109, 8, printValoresGeneral("ESTADO-SERVICIO", $f['EstadoMast']), 0, 0, 'L');
		$pdf->Cell(66, 8, 'Total Orden: ', 0, 0, 'R');
		$pdf->SetFont('Arial', 'BU', 8);
		$pdf->Cell(25, 8, number_format($f['TotalOrden'], 2, ',', '.'), 0, 1, 'R');
	}
	if ($fVerDetalle == "S") {
		if ($f['CodItem'] != "") $Codigo = $f['CodItem']; else $Codigo = $f['CommoditySub'];
		if ($f['DiasAtrasados'] < 0 || $f['FlagTerminado'] != "N") $DiasAtrasados = "-"; 
		else $DiasAtrasados = $f['DiasAtrasados'];
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetFont('Arial', '', 7);	
		$pdf->Row(array('-',
						$f['CommoditySub'],
						utf8_decode($f['Descripcion']),
						number_format($f['CantidadPedida'], 2, ',', '.'),
						formatFechaDMA($f['FechaEsperadaTermino']),
						$DiasAtrasados,
						$f['CodCentroCosto'],
						printValoresGeneral("FLAG", $f['FlagTerminado'])));
		$pdf->Ln(1);
	}
	$Total += $f['Total'];
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
