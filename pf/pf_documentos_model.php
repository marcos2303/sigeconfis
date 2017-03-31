<?php

function getDocumentosByCodActuacionActividad($CodActuacion,$CodActividad)
{
	$q = array();
	$sql = "SELECT * FROM  pf_actuacionfiscaldocumentos d WHERE d.codActuacion = '$CodActuacion' and CodActividad = '$CodActividad'";
        $query_mast = mysql_query($sql) or die($sql.mysql_error());
	//if (mysql_num_rows($query_mast)) $q = mysql_fetch_array($query_mast);
	return $query_mast;
}
function getDocumentByCodCedulaCodActuacion($CodActuacion,$CodActividad){
$m = new MongoClient();
$db = $m->selectDB('documentos');
$collection = new MongoCollection($db, 'fs.files');
$query = array("CodActividad"=>"$CodActividad","CodActuacion"=>"$CodActuacion");
$cursor = $collection->find($query);

$array = array();
	foreach ($cursor as $doc) 
	{
		$array[] = $doc;
	}
	return json_encode($array);
}
function getDocumentMongoDB($ObjectId)
{
	$mongo = new MongoClient();
	$gridFS = $mongo->documentos->getGridFS();

	$object = $gridFS->findOne(array('_id' => new MongoId($ObjectId)));
	header("Content-Transfer-Encoding: binary");
	header('Content-type: '.$object->file['filetype']);
	header('Expires: 0');
	header("Content-disposition: attachment; filename=".$object->file['CodActuacion']."-".$object->file['CodActividad']."-".$object->file['Fase'].".".$object->file['filetype']);
	//header('Content-name: '.$object->file['filename']);
	header('Content-Type:application-x/force-download'); 
	echo $object->getBytes();

}
function getActuacionFiscalDetalle($CodActuacion,$CodActividad)
{
	$q = array();
	$sql = "SELECT *, (SELECT Dependencia FROM pf_dependenciasexternas dep WHERE codOrganismoExterno = ext.CodOrganismo AND CodDependencia = a.CodDependenciaExterna ) AS Dependencia "
                . "FROM  pf_actuacionfiscaldetalle d, pf_actuacionfiscal a, pf_organismosexternos ext "
                . "WHERE d.codActuacion = '$CodActuacion' "
                . " and a.codActuacion = d.codActuacion"
                . " and CodActividad = '$CodActividad'"
                . " AND ext.CodOrganismo = a.CodOrganismoExterno";
        //echo $sql;die;
	$query_mast = mysql_query($sql) or die($sql.mysql_error());
	//if (mysql_num_rows($query_mast)) $q = mysql_fetch_array($query_mast);
	return $query_mast;
}
function getDocumentosActuacionFiscalActividad($CodActuacion,$CodActividad)
{
	$q = array();
	$sql = "SELECT * FROM  pf_actuacionfiscaldocumentos d WHERE d.codActuacion = '$CodActuacion' and CodActividad = '$CodActividad' order by linea";
        $query_mast = mysql_query($sql) or die($sql.mysql_error());	
	return $query_mast;
}