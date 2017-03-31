<?php
include("../lib/fphp.php");
include("lib/fphp.php");
include('pf_hallazgos_model.php');
include('pf_hallazgos_validator.php');
include('../lib/Validate.php');
include('../lib/messages.php');
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
		case 'nuevo':
			nuevaCedula($values);
			break;
		case 'add':
			addCedula($values);
			break;
		case 'list':
			ListaCedula($values);
			break;
		case 'edit':
			EditarCedula($values);
			break;
		case 'update':
			ActualizarCedula($values);
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
function nuevaCedula($values)
{
	$CodActuacion = $values['registro'];
	$values['CodActuacion'] = $CodActuacion;
	$field_actuacion = getActuacionById($CodActuacion);
	$values['action'] = 'add';
	require('pf_hallazgos_form.php');
}
function addCedula($values)
{	
	
	$valido = true;
	$values['error'] = validate($values);
	$values['Responsables'] = "";
	$values['SoporteDocumental'] = "";
	if(isset($values['nacion'])){
		foreach($values['nacion'] as $key => $value)
		{
			$nacion = $values['nacion'][$key];
			$cedula = $values['cedula'][$key];
			$nombres = $values['nombres'][$key];
			$apellidos= $values['apellidos'][$key];
			if($values['Responsables']==''){
				$values['Responsables'].= $nacion."|".$cedula."|".$nombres."|".$apellidos;
			}else{
				$values['Responsables'].= ";".$nacion."|".$cedula."|".$nombres."|".$apellidos."";
			}
	
		}
	}
	if(isset($values['numero_documento'])){	
		foreach($values['numero_documento'] as $key => $value)
		{
			$numero_documento= $values['numero_documento'][$key];
			$observacion = $values['observacion'][$key];
			if($values['SoporteDocumental']==''){
				$values['SoporteDocumental'].= $numero_documento."|".$observacion;
			}else{
				$values['SoporteDocumental'].= ";".$numero_documento."|".$observacion;
			}
			
		}
	}		
		
		//echo $values['Responsables'];die;
	
	
	if(count($values['error'])>0)
	{
		$valido = false;
	} 
	if($valido == true)
	{
		

		//echo $values['Responsables'];die;
		
		//almaceno en bd
		$values['CodCedula'] = generarCorrelativoCedulaHallazgos($values);//Generar correlativo
		$values['NumCedula'] = saveCedulaHallazgos($values);	
                $CodActuacion = $values['CodActuacion'];
                $Anio = date('Y');
                $carpeta = "../archivos/pf/".$Anio."/$CodActuacion/Cedulas/";
                //echo $carpeta;die;
                if (!file_exists($carpeta)) {
                    mkdir("$carpeta", 0777);
                    //echo "The directory $carpeta was successfully created.";
                } 
                if(isset($values['files']))
		{
                    saveDocumentoCedula($values);
			//saveDocumentMongoDb($values['files'],'pf_hallazgos',$values['CodActuacion'],1,array('CodCedula' => $values['CodCedula']));
                    $i = 0;
                    $_DocName = 'prueba';
                    
                    //echo count($_FILES['files']);die;
                    foreach($values['files'] as $documento)
                    {
                        //echo $i."<br>";
                        
                                    if(isset($_FILES['files']['tmp_name'][$i]))
                                    {
                                        if (!move_uploaded_file($_FILES['files']['tmp_name'][$i], $carpeta.$_FILES['files']['name'][$i]))
                                        {

                                            echo "Se ha presentado un inconveniente subiendo los archivos ";
                                            die;
                                        } 
                                    }

                           $i++;
                    }
                    //die;
                        
                }
		$values['msg'] = SAVE_MESSAGE;
		ListaCedula($values);die;
	}
	else
	{
		//redirecciono al form para mostrar los errores
		nuevaCedula($values);die;
	}
	$CodActuacion = $values['registro'];
	$field_actuacion = getActuacionById($CodActuacion);
	require('pf_hallazgos_form.php');
}
function consultaCedula($values)
{
	$CodActuacion = $values['registro'];
	$field_actuacion = getActuacionById($CodActuacion);
	require('pf_hallazgos_form.php');	
}
function ListaCedula($values)
{
	$CodActuacion = $values['registro'];
	$cedulas_list = getCedulaByCodActuacion($CodActuacion);
	require('pf_hallazgos_index.php');	
}
function EditarCedula($values)
{
	$field_actuacion  = getActuacionById($values['CodActuacion']);
	$cedula =  getCedulaByCodCedula($values['CodCedula']);
	$values = array_merge($values,$cedula);
	$values['action'] = 'update';
	require('pf_hallazgos_form.php');	
}
function ActualizarCedula($values)
{
	$valido = true;
	$values['error'] = validate($values);
	$values['Responsables'] = "";
	$values['SoporteDocumental'] = "";
		
	if(isset($values['nacion'])){
		foreach($values['nacion'] as $key => $value)
		{
			$nacion = $values['nacion'][$key];
			$cedula = $values['cedula'][$key];
			$nombres = $values['nombres'][$key];
			$apellidos= $values['apellidos'][$key];
			if($values['Responsables']==''){
				$values['Responsables'].= $nacion."|".$cedula."|".$nombres."|".$apellidos;
			}else{
				$values['Responsables'].= ";".$nacion."|".$cedula."|".$nombres."|".$apellidos."";
			}
			
		}
	}
	if(isset($values['numero_documento'])){
		foreach($values['numero_documento'] as $key => $value)
		{
			$numero_documento= $values['numero_documento'][$key];
			$observacion = $values['observacion'][$key];
			if($values['SoporteDocumental']==''){
				$values['SoporteDocumental'].= $numero_documento."|".$observacion;
			}else{
				$values['SoporteDocumental'].= ";".$numero_documento."|".$observacion;
			}
			
		}
	}
	if(count($values['error'])>0) $valido = false;
	if($valido == true)
	{
		//almaceno en bd
		updateCedulaHallazgos($values);
		$values['msg'] = UPDATE_MESSAGE;
		ListaCedula($values);die;
	}
	else
	{
		//redirecciono al form para mostrar los errores
		nuevaCedula($values);die;
	}
	$CodActuacion = $values['registro'];
	$field_actuacion = getActuacionById($CodActuacion);
	require('pf_hallazgos_form.php');
}
function getDocumentByObjectId($values){
	//$values['ObjectId'] = "56cc76534aafe065778b4581";
	$ObjectId = $values['ObjectId'];
	getDocumentMongoDB($ObjectId);
}
function getDocumentsByCedula($values){
	//$values['ObjectId'] = "56cc76534aafe065778b4581";
	$CodCedula = $values['CodCedula'];
	echo getDocumentInfoByCodCedulaFile($CodCedula);
}

