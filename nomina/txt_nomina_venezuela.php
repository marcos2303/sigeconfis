<?php
session_start();
header('Content-Type: text/html; charset=iso-8859-1');
set_time_limit(-1);
include("fphp_nomina.php");
connect();
$texto="";
$archivo=fopen($nombre_archivo.".txt", "w+");
//---------------
$LF = 0x0A;
$CR = 0x0D;
$nl = sprintf("%c%c",$CR,$LF);
$fecha=date("d/m/y");
//---------------

//---------------
$sql = "SELECT
			mp.Ndocumento,
			CONCAT(mp.Apellido1, ' ', mp.Nombres) AS Busqueda,
			ptne.TotalNeto,
			bp.Ncuenta,
			bp.TipoCuenta,
			rbp.CodBeneficiario,
			rbp.NroDocumento,
			rbp.NombreCompleto
		FROM
			pr_tiponominaempleado ptne
			INNER JOIN mastpersonas mp ON (ptne.CodPersona = mp.CodPersona)
			INNER JOIN bancopersona bp ON (ptne.CodPersona = bp.CodPersona)
			LEFT JOIN rh_beneficiariopension rbp ON (mp.CodPersona = rbp.CodPersona)
		WHERE
			ptne.CodTipoProceso = '".$codproceso."' AND
			ptne.Periodo = '".$periodo."' AND
			ptne.CodOrganismo = '".$organismo."' AND
			ptne.TotalNeto > 0
		ORDER BY ptne.CodTipoNom, length(mp.Ndocumento), mp.Ndocumento";
$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	if ($field['CodBeneficiario'] != "") {
		$nombre = $field['NombreCompleto'];
		$cedula = $field['NroDocumento'];
	} else {
		$nombre = $field['Busqueda'];
		$cedula = $field['Ndocumento'];
	}

	$sum += $field['TotalNeto'];
 	//--
	if ($field['TipoCuenta'] == "CO") $tipo_cuenta = "0"; else $tipo_cuenta = "1";
 	//--
	$nrocuenta = (string) str_repeat("0", 20-strlen($field['Ncuenta'])).$field['Ncuenta'];
	//--
	list($int, $dec)=SPLIT( '[.]', $field['TotalNeto']); $field_monto = "$int$dec";
	$monto = (string) str_repeat("0", 11-strlen($field_monto)).$field_monto;
	//--
	$relleno_1 = "0770";
	//--
	$nombre = (string) $nombre.str_repeat(" ", 40-strlen($nombre));
	//--
	$cedula = (string) str_repeat("0", 10-strlen($cedula)).$cedula;
	//--
	$relleno_2 = "003291  ";
	//--
	$texto.=$tipo_cuenta.$nrocuenta.$monto.$relleno_1.$nombre.$cedula.$relleno_2."$nl";
}

list($int, $dec)=SPLIT( '[.]', $sum); $total = "$int$dec";
$total_neto = (string) str_repeat("0", 13-strlen($total)).$total;
	
$titulo = "HContraloria del Estado                  0102062860000001209801".$fecha.$total_neto."03291 ".$nl;

fwrite($archivo, $titulo.$texto);
fclose($archivo);

?>