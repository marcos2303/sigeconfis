<?php
include("../lib/fphp.php");
include("lib/fphp.php");
$values = $_REQUEST;
$values = array_merge($values,$_FILES);
$values['titulo'] = 'Cédula de hallazgos';
//getDocumentByObjectId(1);die;
dispatch($values);

function dispatch($values)
{	
	if(isset($values['action']))
	{
		$action = $values['action'];
	}else
	{
		$action = '';
	}
	
	
	switch ($action) {

		case 'lista_actuaciones_auditor':
			ListActuacionesAuditor($values);
			break;
		default:
			break;
	}
}

function ListActuacionesAuditor($values)
{

	
	$sql = ""
		. " SELECT *,pf.Estado FROM pf_actuacionfiscalauditores aa"
		. " INNER JOIN pf_actuacionfiscal pf ON pf.CodActuacion = aa.CodActuacion "
		. " INNER JOIN mastorganismos m ON m.CodOrganismo = pf.CodOrganismo"
		. " WHERE aa.CodPersona = '".$_SESSION["CODPERSONA_ACTUAL"]."' and pf.Estado = 'PR'";
	$query_actuaciones = mysql_query($sql) or die ($sql.mysql_error());
	require('pf_lista_actuaciones_auditor_index.php');	
}
