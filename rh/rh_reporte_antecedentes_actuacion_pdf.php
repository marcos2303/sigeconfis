<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
$LF = 0x0A;
$CR = 0x0D;
$nl = sprintf("%c%c",$CR,$LF);
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $Ahora;
		global $field;
		global $_POST;
		global $_GET;
		extract($_POST);
		extract($_GET);
		##	
		//	membrete
		##	obtengo los valores
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $field['CodOrganismo']);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPRHPR"]);		
		##	colores
		$this->SetDrawColor(0, 0, 0);
		$this->SetTextColor(0, 0, 0);
		##	cuadros y lineas
		$this->Rect(10, 10, 195, 36, 'D');
		$this->Rect(45, 10, 0.1, 36, 'D');
		$this->Rect(170, 10, 0.1, 36, 'D');
		$this->Rect(45, 35, 160, 0.1, 'D');
		$this->Rect(170, 20, 35, 0.1, 'D');
		$this->Rect(170, 25, 35, 0.1, 'D');
		$this->Rect(170, 30, 35, 0.1, 'D');
		$this->Rect(187.5, 25, 0.1, 10, 'D');
		##	imprimo membrete
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 18, 12, 18, 18);
		$this->SetFont('Arial', 'B', 8);
		$this->SetXY(10, 32); $this->MultiCell(35, 4, utf8_decode($field['Organismo']), 0, 'C');
		##
		$this->SetFont('Arial', 'B', 11);
		$this->SetXY(40, 18); $this->MultiCell(125, 5, utf8_decode($NomDependencia), 0, 'C');
		##
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(40, 36);
		$this->Cell(125, 5, 'FORMATO', 0, 2, 'C');
		$this->Cell(125, 5, utf8_decode('ANTECEDENTES DE SERVICIOS'), 0, 0, 'C');
		##
		$this->SetFont('Arial', 'B', 8);
		$this->SetXY(170, 10); $this->Cell(35, 5, utf8_decode('CODIGO:'), 0, 1, 'C');
		$this->SetXY(170, 15); $this->Cell(35, 5, utf8_decode('FOR-DRRHH-016'), 0, 1, 'C');
		$this->SetXY(170, 20); $this->Cell(35, 5, utf8_decode('REVISIÓN:'), 0, 1, 'C');
		$this->SetXY(170, 25); $this->Cell(17.5, 5, utf8_decode('N°:'), 0, 1, 'C');
		$this->SetXY(187.5, 25); $this->Cell(17.5, 5, utf8_decode('FECHA'), 0, 1, 'C');
		$this->SetXY(170, 30); $this->Cell(17.5, 5, utf8_decode('1 DE 1'), 0, 1, 'C');
		$this->SetXY(187.5, 30); $this->Cell(17.5, 5, utf8_decode('03/05/2013'), 0, 1, 'C');
		$this->SetXY(170, 37); $this->Cell(35, 5, utf8_decode('PAGINA'), 0, 1, 'C');
		$this->SetFont('Arial', '', 8);
		$this->SetXY(170, 41); $this->Cell(35, 5, $this->PageNo().' DE {nb}', 0, 1, 'C');
	}
	
	//	Pie de página.
	function Footer() {
		
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(5, 5);
//---------------------------------------------------
$registros = explode("|", $sel_registros);
foreach($registros as $CodPersona) {
	$sql = "SELECT
				p.Ndocumento,
				p.Nacionalidad,
				p.Sexo,
				CONCAT(p.Apellido1, ' ', p.Apellido2, ' ', p.Nombres) AS NomCompleto,
				e.CodOrganismo,
				e.Fingreso,
				e.Fegreso,
				SUBSTRING(e.Fingreso, 1, 4) AS AnioIngreso,
				SUBSTRING(e.Fingreso, 6, 2) AS MesIngreso,
				SUBSTRING(e.Fingreso, 9, 4) AS DiaIngreso,
				SUBSTRING(e.Fegreso, 1, 4) AS AnioEgreso,
				SUBSTRING(e.Fegreso, 6, 2) AS MesEgreso,
				SUBSTRING(e.Fegreso, 9, 4) AS DiaEgreso,
				mc.MotivoCese,
				(SELECT Cargo 
				 FROM rh_empleadonivelacionhistorial
				 WHERE CodPersona = p.CodPersona
				 ORDER BY Secuencia
				 LIMIT 0, 1) AS CargoIngreso,
				(SELECT Cargo
				 FROM rh_empleadonivelacionhistorial
				 WHERE CodPersona = p.CodPersona
				 ORDER BY Secuencia DESC
				 LIMIT 0, 1) AS CargoEgreso,
				(SELECT pt2.CodDesc
				 FROM
					rh_empleadonivelacion en2
					INNER JOIN rh_puestos pt2 ON (pt2.CodCargo = en2.CodCargo)
				 WHERE en2.CodPersona = p.CodPersona
				 ORDER BY en2.Secuencia
				 LIMIT 0, 1) AS CodigoIngreso,
				(SELECT pt2.CodDesc
				 FROM
					rh_empleadonivelacion en2
					INNER JOIN rh_puestos pt2 ON (pt2.CodCargo = en2.CodCargo)
				 WHERE en2.CodPersona = p.CodPersona
				 ORDER BY en2.Secuencia DESC
				 LIMIT 0, 1) AS CodigoEgreso,
				(SELECT pt2.Grado
				 FROM
					rh_empleadonivelacion en2
					INNER JOIN rh_puestos pt2 ON (pt2.CodCargo = en2.CodCargo)
				 WHERE en2.CodPersona = p.CodPersona
				 ORDER BY en2.Secuencia
				 LIMIT 0, 1) AS GradoIngreso,
				(SELECT pt2.Grado
				 FROM
					rh_empleadonivelacion en2
					INNER JOIN rh_puestos pt2 ON (pt2.CodCargo = en2.CodCargo)
				 WHERE en2.CodPersona = p.CodPersona
				 ORDER BY en2.Secuencia DESC
				 LIMIT 0, 1) AS GradoEgreso,
				(SELECT SueldoBasico
				 FROM pr_tiponominaempleado
				 WHERE
					CodTipoProceso = 'FIN' AND
					CodPersona = p.CodPersona
				 ORDER BY Periodo
				 LIMIT 0, 1) AS SueldoBasicoIngreso,
				(SELECT TotalIngresos
				 FROM pr_tiponominaempleado
				 WHERE
					CodTipoProceso = 'FIN' AND
					CodPersona = p.CodPersona
				 ORDER BY Periodo
				 LIMIT 0, 1) AS TotalIngresosIngreso,
				(SELECT SueldoBasico
				 FROM pr_tiponominaempleado
				 WHERE
					CodTipoProceso = 'FIN' AND
					CodPersona = p.CodPersona AND
					Periodo < e.Fegreso
				 ORDER BY Periodo DESC
				 LIMIT 0, 1) AS SueldoBasicoEgreso,
				(SELECT TotalIngresos
				 FROM pr_tiponominaempleado
				 WHERE
					CodTipoProceso = 'FIN' AND
					CodPersona = p.CodPersona AND
					Periodo < e.Fegreso
				 ORDER BY Periodo DESC
				 LIMIT 0, 1) AS TotalIngresosEgreso,
				 (SELECT Entrada1
				  FROM rh_horariolaboraldet
				  WHERE
					CodHorario = e.CodHorario AND
					FlagLaborable = 'S'
				  ORDER BY Dia
				  LIMIT 0, 1) AS Entrada1,
				 (SELECT Salida1
				  FROM rh_horariolaboraldet
				  WHERE
					CodHorario = e.CodHorario AND
					FlagLaborable = 'S'
				  ORDER BY Dia
				  LIMIT 0, 1) AS Salida1,
				 (SELECT Entrada2
				  FROM rh_horariolaboraldet
				  WHERE
					CodHorario = e.CodHorario AND
					FlagLaborable = 'S'
				  ORDER BY Dia
				  LIMIT 0, 1) AS Entrada2,
				 (SELECT Salida2
				  FROM rh_horariolaboraldet
				  WHERE
					CodHorario = e.CodHorario AND
					FlagLaborable = 'S'
				  ORDER BY Dia
				  LIMIT 0, 1) AS Salida2
			FROM
				mastpersonas p
				INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
				LEFT JOIN rh_motivocese mc ON (mc.CodMotivoCes = e.CodMotivoCes)
			WHERE e.CodPersona = '".$CodPersona."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	//---------------------------------------------------
	$pdf->AddPage();
	//---------------------------------------------------
	##	colores
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(0, 0, 0);
	##	cuadro
	$pdf->Rect(10, 55, 195, 208, 'D');
	##
	$pdf->Ln();
	$pdf->SetFillColor(200, 200, 200);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(195, 5, utf8_decode('DATOS PERSONALES'), 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetWidths(array(150, 45));
	$pdf->SetAligns(array('L', 'C'));
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Row(array('APELLIDOS Y NOMBRES',
					'CEDULA DE IDENTIDAD'));
	$pdf->SetFont('Arial', '', 10);
	$pdf->Row(array(utf8_decode($field['NomCompleto']),
					number_format($field['Ndocumento'], 0, '', '.')));
	##
	$pdf->SetFillColor(200, 200, 200);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(195, 5, utf8_decode('ACTUACIÓN EN EL ORGANISMO'), 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetWidths(array(97.5, 97.5));
	$pdf->SetAligns(array('C', 'C'));
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Row(array('INGRESO',
					'EGRESO'));
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetWidths(array(31.5, 66, 31.5, 66));
	$pdf->SetAligns(array('C', 'L', 'C', 'L'));
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Row(array('FECHA',
					utf8_decode('TÍTULO DEL CARGO'),
					'FECHA',
					utf8_decode('TÍTULO DEL CARGO')));
					
	$pdf->SetWidths(array(10, 10, 11.5));
	$pdf->SetAligns(array('C', 'C', 'C'));
	$pdf->SetFont('Arial', 'B', 10);
	$y = $pdf->GetY();
	$pdf->SetXY(10, $y);
	$pdf->Row(array('DIA',
					'MES',
					utf8_decode('AÑO')));
	$pdf->SetXY(107.5, $y);
	$pdf->Row(array('DIA',
					'MES',
					utf8_decode('AÑO')));
	$pdf->SetFont('Arial', '', 10);
	$y = $pdf->GetY();
	$pdf->SetXY(10, $y);
	$pdf->Row(array($field['DiaIngreso'],
					$field['MesIngreso'],
					$field['AnioIngreso']));
	$pdf->SetXY(41.5, $y-5);
	$pdf->MultiCell(66, 5, utf8_decode($field['CargoIngreso']), 0, 'L');
	$pdf->SetXY(107.5, $y);
	$pdf->Row(array($field['DiaEgreso'],
					$field['MesEgreso'],
					$field['AnioEgreso']));
	$pdf->SetXY(139, $y-5);
	$pdf->MultiCell(66, 5, utf8_decode($field['CargoEgreso']), 0, 'L');
	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(20, 5, utf8_decode('Código: '), 1, 0, 'L');
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(11.5, 5, $field['CodigoIngreso'], 1, 0, 'C');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(40, 5, utf8_decode('Grado: '), 1, 0, 'C');
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(26, 5, $field['GradoIngreso'], 1, 0, 'C');
	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(20, 5, utf8_decode('Código: '), 1, 0, 'L');
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(11.5, 5, $field['CodigoEgreso'], 1, 0, 'C');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(40, 5, utf8_decode('Grado: '), 1, 0, 'C');
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(26, 5, $field['GradoEgreso'], 1, 0, 'C');
	##
	$pdf->Ln();
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetWidths(array(31.5, 40, 26, 31.5, 40, 26));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Row(array('Tipo de Nombramiento',
					'Horario de Trabajo',
					'Horas Semanales',
					'Tipo de Nombramiento',
					'Horario de Trabajo',
					'Horas Semanales'));
	$pdf->SetFont('Arial', '', 10);
	$y = $pdf->GetY();
	$pdf->SetXY(10, $y);
	$RAC = "Nro. de R.A.C.".$nl;
	$Entrada1 = formatHora12($field['Entrada1']);
	$Salida1 = formatHora12($field['Salida1']);
	$Entrada2 = formatHora12($field['Entrada2']);
	$Salida2 = formatHora12($field['Salida2']);
	$Horario = "$Entrada1 a $Salida1"."$nl"."$Entrada2 a $Salida2";
	$pdf->Row(array($RAC,
					$Horario,
					35,
					$RAC,
					$Horario,
					35));
	##
	$pdf->SetFont('Arial', '', 10);
	$y = $pdf->GetY() + 2;
	
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->Line(107.50, $y-2, 107.50, $y+20);
	
	$PrimasIngreso = $field['TotalIngresosIngreso'] - $field['SueldoBasicoIngreso'];
	$PrimasEgreso = $field['TotalIngresosEgreso'] - $field['SueldoBasicoEgreso'];
	$pdf->SetXY(15, $y);
	$pdf->Cell(35, 5, utf8_decode('SUELDO BÁSICO'), 0, 0, 'L');
	$pdf->Cell(52.5, 5, number_format($field['SueldoBasicoIngreso'], 2, ',', '.'), 0, 0, 'R');
	$pdf->SetXY(112.5, $y);
	$pdf->Cell(35, 5, utf8_decode('SUELDO BÁSICO'), 0, 0, 'L');
	$pdf->Cell(52.5, 5, number_format($field['SueldoBasicoEgreso'], 2, ',', '.'), 0, 0, 'R');
	$pdf->SetXY(15, $y+6);
	$pdf->Cell(35, 5, utf8_decode('PRIMAS'), 0, 0, 'L');
	$pdf->Cell(52.5, 5, number_format($PrimasIngreso, 2, ',', '.'), 0, 0, 'R');
	$pdf->SetXY(112.5, $y+6);
	$pdf->Cell(35, 5, utf8_decode('PRIMAS'), 0, 0, 'L');
	$pdf->Cell(52.5, 5, number_format($PrimasEgreso, 2, ',', '.'), 0, 0, 'R');
	$pdf->SetXY(15, $y+12);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(35, 5, utf8_decode('TOTAL'), 0, 0, 'L');
	$pdf->Cell(52.5, 5, number_format($field['TotalIngresosIngreso'], 2, ',', '.'), 0, 0, 'R');
	$pdf->SetXY(112.5, $y+12);
	$pdf->Cell(35, 5, utf8_decode('TOTAL'), 0, 0, 'L');
	$pdf->Cell(52.5, 5, number_format($field['TotalIngresosEgreso'], 2, ',', '.'), 0, 0, 'R');
	##
	$pdf->Ln(8);
	$pdf->SetFillColor(200, 200, 200);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(97.5, 5, utf8_decode('TIPO DE EGRESO'), 1, 0, 'C', 1);
	$pdf->Cell(97.5, 5, utf8_decode('PAGO DE PRESTACIONES SOCIALES'), 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetWidths(array(97.5, 97.5));
	$pdf->SetAligns(array('C', 'L'));
	$pdf->SetFont('Arial', '', 10);
	$pdf->Row(array(utf8_decode($field['MotivoCese']),
					'               Si                                    No'));
	if ($FlagPrestaciones == "A") {
		$si = "imagenes/check.jpg";
		$no = "imagenes/nocheck.jpg";
	} else {
		$si = "imagenes/nocheck.jpg";
		$no = "imagenes/check.jpg";
	}	
	$y = $pdf->GetY() - 4;
	$pdf->Image($si, 130, $y, 3, 3);
	$pdf->Image($no, 170, $y, 3, 3);
	##
	$pdf->SetFillColor(200, 200, 200);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(97.5, 5, utf8_decode('FUNDAMENTO LEGAL'), 1, 0, 'C', 1);
	$pdf->Cell(97.5, 5, utf8_decode('TIEMPO DE SERVICIO'), 1, 0, 'C', 1);
	$pdf->Ln();
	list($Anios, $Meses, $Dias) = getTiempo(formatFechaDMA($field['Fingreso']), formatFechaDMA($field['Fegreso']));
	$TiempoServicio = " $nl AÑOS: $Anios                    MESES: $Meses                    DIAS: $Dias";
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetWidths(array(97.5, 97.5));
	$pdf->SetAligns(array('J', 'C'));
	$pdf->SetFont('Arial', '', 10);
	$pdf->Row(array(utf8_decode($_PARAMETRO['ANTLEGAL']),
					utf8_decode($TiempoServicio)));
	##
	$pdf->SetFillColor(200, 200, 200);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(195, 5, utf8_decode('OBSERVACIONES'), 1, 0, 'C', 1);
	$pdf->Ln();
	
	$Nombre = $field['NomCompleto'];
	$Nacionalidad = $field['Nacionalidad'];
	$Cedula = number_format($field['Ndocumento'], 0, '', '.');
	$FechaIngreso = formatFechaDMA($field['Fingreso']);
	$FechaEgreso = formatFechaDMA($field['Fegreso']);
	$CargoIngreso = $field['CargoIngreso'];
	$CargoEgreso = $field['CargoEgreso'];
	if ($field['Sexo'] == "M") $Ciudadano = "EL CIUDADANO"; else $Ciudadano = "LA CIUDADANA";
	eval($_PARAMETRO['ANTOBS']);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetWidths(array(195));
	$pdf->SetAligns(array('J'));
	$pdf->SetFont('Arial', '', 10);
	$pdf->Row(array(utf8_decode($ANTOBS)));
	
	//	obtengo las firmas
	$CodAprueba = getVar("mastdependencias", "CodPersona", "CodDependencia", $_PARAMETRO['DEPMAXORG']);
	list($_ELABORADO['Nombre'], $_ELABORADO['Cargo'], $_ELABORADO['Nivel']) = getFirma($_PARAMETRO['FIRMARHPR2']);
	list($_REVISADO['Nombre'], $_REVISADO['Cargo'], $_REVISADO['Nivel']) = getFirma($_PARAMETRO['FIRMARHPR1']);
	list($_CONFORMADO['Nombre'], $_CONFORMADO['Cargo'], $_CONFORMADO['Nivel']) = getFirmaxDependencia($_PARAMETRO['DEPRHPR']);
	list($_APROBADO['Nombre'], $_APROBADO['Cargo'], $_APROBADO['Nivel']) = getFirma($CodAprueba);
	##
	$pdf->SetFillColor(200, 200, 200);
	$pdf->Cell(195, 5, utf8_decode('CONFORMACION Y APROBACION'), 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetXY(10, 203); 
	$pdf->Cell(97.5, 5, utf8_decode('ELABORADO POR'), 1, 0, 'C');
	$pdf->Cell(97.5, 5, utf8_decode('REVISADO POR'), 1, 0, 'C');
	$pdf->SetXY(10, 233);
	$pdf->Cell(97.5, 5, utf8_decode('CONFORMADO POR'), 1, 0, 'C');
	$pdf->Cell(97.5, 5, utf8_decode('APROBADO POR'), 1, 0, 'C');
	$pdf->Rect(107.5, 203, 0.1, 60, 'D');
	##
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 9);
	$pdf->SetWidths(array(95.5));
	$pdf->SetAligns(array('C'));
	$pdf->SetXY(11, 209);
	$pdf->Row(array(utf8_decode($_ELABORADO['Nivel'].' '.$_ELABORADO['Nombre'])));
	$pdf->SetX(11);
	$pdf->Row(array(utf8_decode($_ELABORADO['Cargo'])));
	$pdf->SetXY(108.5, 209);
	$pdf->Row(array(utf8_decode($_REVISADO['Nivel'].' '.$_REVISADO['Nombre'])));
	$pdf->SetX(108.5);
	$pdf->Row(array(utf8_decode($_REVISADO['Cargo'])));
	$pdf->SetXY(11, 239);
	$pdf->Row(array(utf8_decode($_CONFORMADO['Nivel'].' '.$_CONFORMADO['Nombre'])));
	$pdf->SetX(11);
	$pdf->Row(array(utf8_decode("DIRECTORA DE RECURSOS HUMANOS (E)")));
	$pdf->SetXY(108.5, 239);
	$pdf->Row(array(utf8_decode($_APROBADO['Nivel'].' '.$_APROBADO['Nombre'])));
	$pdf->SetX(108.5);
	$pdf->Row(array(utf8_decode($_APROBADO['Cargo'])));
	##
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(11, 228);
	$pdf->Cell(68, 5, utf8_decode('Firma: '), 0, 0, 'L');
	$pdf->SetXY(108.5, 228);
	$pdf->Cell(68, 5, utf8_decode('Firma: '), 0, 0, 'L');
	$pdf->SetXY(11, 258);
	$pdf->Cell(68, 5, utf8_decode('Firma: '), 0, 0, 'L');
	$pdf->SetXY(108.5, 258);
	$pdf->Cell(68, 5, utf8_decode('Firma: '), 0, 0, 'L');
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  