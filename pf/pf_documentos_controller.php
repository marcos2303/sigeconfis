<?php

include("lib/fphp.php");
include("../lib/fphp.php");
include('pf_documentos_model.php');
include('pf_hallazgos_validator.php');
include('../lib/Validate.php');
include('../lib/messages.php');
$values = $_REQUEST;
$values = array_merge($values,$_FILES);
$values['titulo'] = 'Cédula de hallazgos';
$GLOBALS['parametros'] = $_PARAMETRO;


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

		case 'list':
			ListDocumentos($values);
			break;
		case 'getDocumentByObjectId':
			getDocumentByObjectId($values);
			break;
		case 'getDocumentsByCedula':
			getDocumentsByCedula($values);
			break;
		default:
			break;
	}
}

function ListDocumentos($values)
{
	$CodActuacion = $values['CodActuacion'];
	$CodActividad = $values['CodActividad'];
	$documentos_list = getDocumentosByCodActuacionActividad($CodActuacion,$CodActividad);
	$actividad_detalle = getActuacionFiscalDetalle($CodActuacion,$CodActividad);
        $actividad_documentos = getDocumentosActuacionFiscalActividad($CodActuacion,$CodActividad);
	require('pf_documentos_index.php');	
}
function getDocumentByObjectId($values){
	//$values['ObjectId'] = "56cc76534aafe065778b4581";
	$ObjectId = $values['ObjectId'];
	getDocumentMongoDB($ObjectId);
}
function getDocumentsByCedula($values){
	//$values['ObjectId'] = "56cc76534aafe065778b4581";
	$CodActuacion = $values['CodActuacion'];
	$CodActividad = $values['CodActividad'];
	
	echo getDocumentByCodCedulaCodActuacion($CodActuacion,$CodActividad);
}
