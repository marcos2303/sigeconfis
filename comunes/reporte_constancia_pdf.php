<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
$LF = 0x0A;
$CR = 0x0D;
$nl = sprintf("%c%c",$CR,$LF);
//---------------------------------------------------
//	consulto datos
$sql = "SELECT
			p.NomCompleto,
			p.Nacionalidad,
			p.Ndocumento,
			e.CodOrganismo,
			e.Fingreso,
			e.SueldoActual AS SueldoBasico,
			pt.DescripCargo
		FROM
			mastpersonas p
			INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
			INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
		WHERE p.CodPersona = '".$sel_registros."'";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
##
if ($field['Sexo'] == "F" && $field['CodTipoNom'] == "02") $funcionario = "la trabajadora";
elseif ($field['Sexo'] == "F") $funcionario = "la funcionaria"; 
elseif ($field['Sexo'] == "M" && $field['CodTipoNom'] == "02") $funcionario = "el trabajador";
elseif ($field['Sexo'] == "M") $funcionario = "el funcionario";
$NomCompleto = trim(strtoupper($field['NomCompleto']));
$Ndocumento = number_format($field['Ndocumento'], 0, '', '.');
$Fingreso = formatFechaDMA($field['Fingreso']);
$DescripCargo = trim(strtoupper($field['DescripCargo']));
##
$sql = "SELECT * FROM rh_sueldos WHERE CodPersona = '".$sel_registros."' ORDER BY Periodo DESC LIMIT 0, 1";
$field_sueldo = getRecord($sql);
$SueldoBasico = strtoupper(convertir_a_letras($field_sueldo['Sueldo'], "moneda")." (Bs. ".number_format($field_sueldo['Sueldo'], 2, ',', '.').")");
$Diferencia = number_format(($field_sueldo['SueldoNormal']-$field_sueldo['Sueldo']), 2, '.', '');
$Primas = strtoupper(convertir_a_letras($Diferencia, "moneda")." (Bs. ".number_format($Diferencia, 2, ',', '.').")");
$SueldoNormal = strtoupper(convertir_a_letras($field_sueldo['SueldoNormal'], "moneda")." (Bs. ".number_format($field_sueldo['SueldoNormal'], 2, ',', '.').")");
$ActualDia = convertir_a_letras($DiaActual, "entero")." ($DiaActual)";
$ActualMes = getNombreMes("$AnioActual-$MesActual");
$Ciudad = ucwords(strtolower(getVar3("SELECT Ciudad FROM mastciudades WHERE CodCiudad = '".$_PARAMETRO['CIUDADDEFAULT']."'")));
$Estado = ucwords(strtolower(getVar3("SELECT Estado FROM mastestados WHERE CodEstado = '".$_PARAMETRO['ESTADODEFAULT']."'")));
##
list($FirmaNombre, $FirmaCargo, $FirmaNivel) = getFirmaxDependencia($_PARAMETRO["DEPMAXORG"], 1, 1);
$Firma = getRecord("SELECT
						p.Nacionalidad,
						p.Ndocumento
					FROM
						mastdependencias d
						INNER JOIN mastpersonas p ON (p.CodPersona = d.CodPersona)
					WHERE d.CodDependencia = '".$_PARAMETRO["DEPMAXORG"]."'");
$FirmaNdocumento = number_format($Firma['Ndocumento'], 0, '', '.');
$FirmaNombre = strtoupper($FirmaNombre);
$FirmaCargo = strtoupper($FirmaCargo);
$FirmaNivel = strtoupper($FirmaNivel);
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
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $field['CodOrganismo']);
		$NomOrganismo = getValorCampo("mastorganismos", "CodOrganismo", "Organismo", $field['CodOrganismo']);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPMAXORG"]);
		##
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->SetTextColor(50, 50, 50);
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 20, 11, 20, 18);
		$this->SetFont('Arial', 'B', 12);
		$this->SetXY(50, 12); $this->Cell(118, 5, utf8_decode('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C', 1);
		$this->SetXY(50, 17); $this->Cell(118, 5, utf8_decode($NomOrganismo), 0, 0, 'C', 1);
		$this->SetXY(50, 22); $this->Cell(118, 5, utf8_decode($NomDependencia), 0, 0, 'C', 1);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 16);
		$this->SetY(40); $this->Cell(178, 5, utf8_decode('CONSTANCIA'), 0, 1, 'C');
		$this->Ln(10);
	}
	
	//	Pie de página.
	function Footer() {
		global $_PARAMETRO;
		global $Ahora;
		global $field;
		global $FirmaNombre;
		global $FirmaCargo;
		global $FirmaNivel;
		global $FirmaEstado;
		global $_POST;
		global $_GET;
		extract($_POST);
		extract($_GET);
		##
		$pie1 = "Según Resolución Nº. 01-00-129  de fecha 12-06-2012,";
		$pie2 = "Emanada del Despacho del  la Contralora General de la República,";
		$pie3 = "Publicada en G. O. Nº  39.943  de fecha 13-06-2012";
		$pie4 = "Hacia la Consolidación y Fortalecimiento del Sistema Nacional de Control Fiscal. ";
		$pie5 = "Calle Centurión - Quinta Paola  Nro. 36 / Teléfono (0287) 7211344 – Fax (0287) 7211655";
		$pie6 = "Tucupita Edo Delta Amacuro.";
		##
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 12);
		$this->SetY(170); $this->Cell(178, 5, utf8_decode($FirmaNivel.' '.$FirmaNombre), 0, 1, 'C');
		$this->SetY(175); $this->Cell(178, 5, utf8_decode($FirmaCargo), 0, 1, 'C');
		$this->SetFont('Arial', '', 8);
		$this->SetY(180); $this->Cell(178, 5, utf8_decode($pie1), 0, 1, 'C');
		$this->SetY(184); $this->Cell(178, 5, utf8_decode($pie2), 0, 1, 'C');
		$this->SetY(188); $this->Cell(178, 5, utf8_decode($pie3), 0, 1, 'C');
		$this->SetFont('Arial', 'B', 10);
		$this->SetY(190); $this->Cell(178, 5, utf8_decode($_PARAMETRO['INICONSTANCIA']), 0, 1, 'L');
		$this->SetTextColor(100, 100, 100);
		$this->SetFont('Arial', 'I', 6);
		$this->SetY(252); $this->Cell(178, 5, utf8_decode($pie4), 0, 1, 'C');
		$this->SetY(256); $this->Cell(178, 5, utf8_decode($pie5), 0, 1, 'C');
		$this->SetY(260); $this->Cell(178, 5, utf8_decode($pie6), 0, 1, 'C');
		$this->Image($_PARAMETRO["PATHLOGO"].'LOGOSNCF.jpg', 175, 240, 20, 18);
		$this->SetTextColor(0, 0, 0);
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(20, 10, 30);
$pdf->SetAutoPageBreak(1, 2);
$pdf->AddPage();
//---------------------------------------------------
if ($FlagSueldo == "S")
	$parrafo1 = "Quien suscribe, $FirmaNivel $FirmaNombre, titular de la cédula de identidad $Firma[Nacionalidad]-$FirmaNdocumento, en mi condición de $FirmaCargo, hago constar que $funcionario $NomCompleto, titular de la cédula de identidad número: $field[Nacionalidad]-$Ndocumento, labora en este Órgano de Control Fiscal desde la fecha $Fingreso, y actualmente ocupa el cargo de $DescripCargo, devengando una remuneración salarial básica mensual de $SueldoBasico, más primas por la cantidad de $Primas; totalizando una remuneración normal de $SueldoNormal.";
else
	$parrafo1 = "Quien suscribe, $FirmaNivel $FirmaNombre, titular de la cédula de identidad $Firma[Nacionalidad]-$FirmaNdocumento, en mi condición de $FirmaCargo, hago constar que $funcionario $NomCompleto, titular de la cédula de identidad número: $field[Nacionalidad]-$Ndocumento, labora en este Órgano de Control Fiscal desde la fecha $Fingreso, y actualmente ocupa el cargo de $DescripCargo.";

$parrafo2 = "Constancia que se expide a petición de la parte interesada. En la Ciudad de $Ciudad, Estado $Estado, a los $ActualDia día(s) del mes de $ActualMes de $AnioActual.";
$parrafo3 = "Válida por tres meses.";
##
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(178, 6, utf8_decode($parrafo1), 0, 'J');
$pdf->Ln(5);
$pdf->MultiCell(178, 6, utf8_decode($parrafo2), 0, 'J');
$pdf->Ln(5);
$pdf->MultiCell(178, 6, utf8_decode($parrafo3), 0, 'J');


//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  