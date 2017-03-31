<?php
include("fphp.php");
include('Validate.php');
include('messages.php');
$values = $_REQUEST;
$values = array_merge($values,$_FILES);
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
		case 'personas':
			listPersonas($values);
			break;
		default:
			break;
	}
}
function listPersonas($values){
	$values['cedula'] = $values['term'];
	$m = new MongoClient();
	$db = $m->selectDB('documentos');
	$collection = new MongoCollection($db, 'personas');
	$query = array('cedula' => $values['cedula']);
	$cursor = $collection->find($query);
	$array = array();
	$i = 0;
		foreach ($cursor as $doc) 
		{
			$array[$i]['value'] = $doc['nombre1']." ".$doc['nombre2']." ".$doc['apellido1']." ".$doc['apellido2'];
			$array[$i]['id'] = $doc['cedula'];
			$array[$i]['nacion'] = $doc['nacion'];
			$array[$i]['cedula'] = $doc['cedula'];
			$i++;
		}

	echo json_encode($array);
}
