<?php	
function getActuacionById($CodActuacion)
{
	$q = array();
	$sql = "SELECT * FROM  pf_actuacionfiscal a WHERE a.codActuacion = '$CodActuacion'";
	$query_mast = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_mast)) $q = mysql_fetch_array($query_mast);
	
	return $q;
}
function getCedulaByCodActuacion($CodActuacion)
{
	$q = array();
	$sql = "SELECT * FROM  pf_cedulahallazgos c WHERE c.codActuacion = '$CodActuacion'";
	$query_mast = mysql_query($sql) or die($sql.mysql_error());
	//if (mysql_num_rows($query_mast)) $q = mysql_fetch_array($query_mast);
	return $query_mast;
}
function saveCedulaHallazgos($values){
	
	$sql = "INSERT INTO pf_cedulahallazgos 
	(
	CodCedula,
	CodActuacion, 
	Condicion, 
	Criterio, 
	Causas, 
	Efectos, 
	Recomendaciones, 
	Estado, 
	UltimoUsuario, 
	UltimaFecha, 
	FechaCedula,
	Responsables,
	ObjetivoEspecifico,
	SoporteDocumental
	)
	VALUES
	(
	'".$values['CodCedula']."', 
	'".$values['CodActuacion']."', 
	'".$values['Condicion']."', 
	'".$values['Criterio']."', 
	'".$values['Causas']."', 
	'".$values['Efectos']."', 
	'".$values['Recomendaciones']."', 
	'PR', 
	'".$_SESSION['USUARIO_ACTUAL']."', 
	'".date('Y-m-d h:i:s')."', 
	'".date('Y-m-d h:i:s')."',
	'".$values['Responsables']."',
	'".$values['ObjetivoEspecifico']."',
	'".$values['SoporteDocumental']."'
	)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		return mysql_insert_id();
	
}
function updateCedulaHallazgos($values){
	$sql = "
		UPDATE siaceda.pf_cedulahallazgos
		SET
		Condicion = '".$values['Condicion']."' , 
		Criterio = '".$values['Criterio']."' , 
		Causas = '".$values['Causas']."' , 
		Efectos = '".$values['Efectos']."' ,  
		Recomendaciones = '".$values['Recomendaciones']."' , 
		Estado = '".$values['Estado']."' , 
		UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."' , 
		UltimaFecha = '".date('Y-m-d h:i:s')."' , 
		FechaCedula = '".$values['FechaCedula']."' , 
		Responsables = '".$values['Responsables']."' ,
		SoporteDocumental = '".$values['SoporteDocumental']."' 
		WHERE
		NumCedula = '".$values['NumCedula']."' AND CodCedula = '".$values['CodCedula']."' ";	
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
}
function getCedulaByCodCedula($CodCedula)
{
	$q = array();
	$sql = "SELECT * FROM  pf_cedulahallazgos a WHERE a.codCedula = '$CodCedula'";
	$query_mast = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_mast)) $q = mysql_fetch_array($query_mast);
	
	return $q;
}
function saveDocumentoCedula($values){
	
	$files = $values['files'];
	if(count($files)>0)
	{	
		$cuenta = count($files['name']);
		//print_r($files);die;
		for($i = 0; $i < $cuenta; $i++)
		{
			$sql = "
				INSERT INTO `siaceda`.`pf_cedulahallazgos_documentos` 
				(CodCedula, 
				NumCedula, 
				NomArchivo, 
                                Anio,
				Extension, 
				FechaNuevo, 
				UsuarioNuevo, 
				Estado, 
				FechaActualizacion, 
				UsuarioActualizacion
				)
				VALUES
				('".$values['CodCedula']."', 
				'".$values['NumCedula']."', 
				'".$files['name'][$i]."', "
                                ."'".date('Y')."',
				'".$files['type'][0]."', 
				'".date('Y-m-d h:i:s')."', 
				'".$_SESSION['USUARIO_ACTUAL']."', 
				'PR', 
				'".date('Y-m-d h:i:s')."', 
				'".$_SESSION['USUARIO_ACTUAL']."'
				);";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
	

}
function generarCorrelativoCedulaHallazgos($values){
	
	$CodActuacion = $values['CodActuacion'];
	$q = array();
	$sql = "SELECT COUNT(*)as cuenta FROM pf_cedulahallazgos WHERE CodActuacion = '$CodActuacion'";
	$query_mast = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_mast)) $q = mysql_fetch_array($query_mast);
	$cuenta = $q['cuenta'] + 1;
	$correlativo = $CodActuacion."-".str_pad($cuenta, 4, 0, STR_PAD_LEFT);
	return $correlativo;	
	
}

function saveDocumentMongoDb($files,$proceedOf,$DocumentId,$Status,$Description){
	$id = array();
	
		$m = new MongoClient();
		
		$gridfs = $m->selectDB('documentos')->getGridFS();
		$data = $gridfs->storeUpload('files', array('proceedOf'=> $proceedOf,"DocumentId"=>$DocumentId,"status" => $Status,'username' => $_SESSION['USUARIO_ACTUAL'],'filetype' => 'pdf',"Description" => $Description));	
		$id[] = $data[0]->{'$id'};
	
	return $id;
}
function saveFileMongoDb($file,$proceedOf,$DocumentId,$Status,$Description){
	$id = array();
		$m = new MongoClient();
		$gridfs = $m->selectDB('documentos')->getGridFS();
		$data = $gridfs->storeFile($file, array('proceedOf'=> $proceedOf,"DocumentId"=>$DocumentId,"CodActuacion"=>$Description["CodActuacion"],"CodActividad"=>$Description["CodActividad"],"Fase"=>$Description["Fase"],"Linea"=>$Description["Linea"],"NroDocumento"=>$Description["NroDocumento"],"Documento"=>$Description["Documento"],"DocName"=>$Description["DocName"],"status" => $Status,'username' => $_SESSION['USUARIO_ACTUAL'],'filetype' => 'pdf',"Description" => $Description));	
		//$id = $data[0]->{'$id'};
	return true;
	//return $id;
}
function getDocumentMongoDB($ObjectId)
{
	$mongo = new MongoClient();
	$gridFS = $mongo->documentos->getGridFS();

	$object = $gridFS->findOne(array('_id' => new MongoId($ObjectId)));
	header("Content-Transfer-Encoding: binary");
	header('Content-type: '.$object->file['filetype']);
	header('Expires: 0');
	header("Content-disposition: attachment; filename=".$object->file['filename']);
	//header('Content-name: '.$object->file['filename']);
	header('Content-Type:application-x/force-download'); 
	echo $object->getBytes();

}
function getDocumentInfoByCodCedula($CodCedula){
$m = new MongoClient();
$db = $m->selectDB('documentos');
$collection = new MongoCollection($db, 'fs.files');
// buscar frutas
$query = array('Description' => array('CodCedula'=> $CodCedula) );
$cursor = $collection->find($query);
$array = array();
	foreach ($cursor as $doc) 
	{
		$array[] = $doc;
	}
	
	return json_encode($array);
}
function getDocumentInfoByCodCedulaFile($CodCedula){

    $array = array();
    

        $sql = "SELECT * FROM pf_cedulahallazgos_documentos ca,pf_cedulahallazgos cb "
                . "where ca.CodCedula = '$CodCedula'  "
                . "AND ca.CodCedula COLLATE utf8_spanish_ci = cb.CodCedula COLLATE utf8_spanish_ci";
        $query_actividad = mysql_query($sql) or die($sql.mysql_error());
        while($field_actividad = mysql_fetch_array($query_actividad)) {
            $array[] = array(
                '_id'=> $field_actividad['id'],
                'filename'=> $field_actividad['NomArchivo'],
                'Anio'=> $field_actividad['Anio'],
                'CodActuacion'=> $field_actividad['CodActuacion'],
                'CodCedula'=> $field_actividad['CodCedula'],
                );
        }
	return json_encode($array);
}