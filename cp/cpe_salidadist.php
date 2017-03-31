<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
include("ControlCorrespondencia.php");
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Salida de Documentos Externos | Distribuci&oacute;n</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php

if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
if(!$_POST){$fEstado="EV"; $cEstado="checked";} else $cEstado="checked";

$filtro = "";

if (@$forganismo != "") { $filtro .= " AND (cpds.CodOrganismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled"; // listo
if (@$fDestinatario !=""){ $filtro .="AND (cpdext.Cod_Organismos= '".$fDestinatario."') AND (cpdext.FlagEsParticular='N')"; $cDestinatario = "checked";}else $dDestinatario = "disabled"; 
if (@$fPartDest !=""){ $filtro .="AND (cpdext.Cod_Organismos= '".$fPartDest."') AND (cpdext.FlagEsParticular='S')"; $cPartDest="checked";} else $dPartDest="disabled";
if (@$fElaboradoPor !=""){ $filtro .="AND (CodDependencia= '".$fElaboradoPor."')"; $cElaborado= "checked";}else $dElaborado = "disabled"; // listo
if (@$fEstado !=""){ $filtro .="AND (cpdext.Estado='".$fEstado."')"; $cEstado="checked";} else $dEstado="disabled";
if (@$fTdocumento !=""){ $filtro .="AND (cpdext.Cod_TipoDocumento='".$fTdocumento."')"; $cTDocumento="checked";} else $dTDocumento="disabled";

if (@$fdesde != "" and @$fhasta != "") { // FECHA DE REGISTRO DEL DOCUMENTO

  @list($d, $m, $a)=preg_split('/[.-]/', $_POST['fdesde']); $fechadesde=$a.'-'.$m.'-'.$d;
  @list($d, $m, $a)=preg_split('/[.-]/', $_POST['fhasta']); $fechahasta=$a.'-'.$m.'-'.$d;
  
	if ($fdesde != "") $filtro .= " AND (FechaDistribucion >= '$fechadesde')";
	if ($fhasta != "") $filtro .= " AND (FechaDistribucion <= '$fechahasta')"; 
	$cFechaRecibido = "checked"; 
	
	list($a, $m, $d)=SPLIT('[/.-]', $fechadesde); $fechadesde=$d.'-'.$m.'-'.$a;
    list($a, $m, $d)=SPLIT('[/.-]', $fechahasta); $fechahasta=$d.'-'.$m.'-'.$a;
	
} else $dFechaRecibido = "disabled";


$MAXLIMIT=30;
//	ELIMINO EL REGISTRO
if (@$_GET['accion']=="ELIMINAR") {
	//
	$sql="DELETE FROM mastpersonas WHERE CodPersona='".$_POST['registro']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$_GET['limit']=0;
}
if(!isset($dorganismo) or $dorganismo == '') $dorganismo = '';
if(!isset($cFechaRecibido) or $cFechaRecibido == '') $cFechaRecibido = '';
if(!isset($dFechaRecibido) or $dFechaRecibido == '') $dFechaRecibido = '';
if(!isset($fechadesde) or $fechadesde == '') $fechadesde = '';
if(!isset($fechahasta) or $fechahasta == '') $fechahasta = '';
if(!isset($cDestinatario) or $cDestinatario == '') $cDestinatario = '';
if(!isset($dDestinatario) or $dDestinatario == '') $dDestinatario = '';
if(!isset($fDestinatario) or $fDestinatario == '') $fDestinatario = '';
if(!isset($cElaborado) or $cElaborado == '') $cElaborado = '';
if(!isset($dElaborado) or $dElaborado == '') $dElaborado = '';
if(!isset($fElaboradoPor) or $fElaboradoPor == '') $fElaboradoPor = '';
if(!isset($cPartDest) or $cPartDest == '') $cPartDest = '';
if(!isset($dPartDest) or $dPartDest == '') $dPartDest = '';
if(!isset($fPartDest) or $fPartDest == '') $fPartDest = '';
if(!isset($cEstado) or $cEstado == '') $cEstado = '';
if(!isset($dEstado) or $dEstado == '') $dEstado = '';
if(!isset($cTDocumento) or $cTDocumento == '') $cTDocumento = '';
if(!isset($fTdocumento) or $fTdocumento == '') $fTdocumento = '';
if(!isset($corganismo) or $corganismo == '') $corganismo = '';
echo "
<form name='frmentrada' action='cpe_salidadist.php?limit=0' method='POST'>
<input type='hidden' name='limit' value='".$limit."'>
<div class='divBorder' style='width:1000px;'>
<table width='1000' class='tblFiltro'>
<tr>
   <td width='125' align='right'>Organismo:</td>
   <td> 
      <input type='checkbox' id='checkorganismos' name='checkorganismos' value='1' $corganismo onclick='this.checked=true'>
	     <select name='forganismo' id='forganismo' class='selectBig' $dorganismo>";
		   getOrganismos(3,$_SESSION['ORGANISMO_ACTUAL']);
		 echo"
		 </select>
   </td>
   <td width='125' align='right' >Fecha Enviado:</td>
   <td> 
     <input type='checkbox' id='checkFechaRecibido' name='checkFechaRecibido' value='1' $cFechaRecibido onclick='enabledFechaRecibido(this.form);'/>
	 desde:<input type='text' name='fdesde' id='fdesde' size='8' maxlength='10' $dFechaRecibido value='$fechadesde'/>
	 hasta:<input type='text' name='fhasta' id='fhasta' size='8' maxlength='10' $dFechaRecibido value='$fechahasta'/>
   </td>
</tr>
<tr>
 <td width='125' align='right'>Org. Destinatario:</td>
 <td>
    <input type='checkbox' id='checkDestinatario' name='checkDestinatario' value='1' $cDestinatario onclick='enabledDestinatario(this.form);'/>
	 <select id='fDestinatario' name='fDestinatario' class='selectBig' $dDestinatario>
	 <option value=''></option>";
	  getDestinatario(1, $fDestinatario);
	echo"
	</select>
 </td>
 <td width='125' align='right'>Elaborado por:</td>
 <td><input type='checkbox' id='checkElaborado' name='checkElaborado' value='1' $cElaborado onclick='enabledElaboradoPor(this.form);'/>
     <select id='fElaboradoPor' name='fElaboradoPor' class='selectBig' $dElaborado>
	  <option value=''></option>";
	   getElaboradoPor(0,$fElaboradoPor);
     echo"
	 </select>
  </td>
</tr>

<tr>
<td width='125' align='right'>Part. Destinatario:</td>
<td>
  <input type='checkbox' id='checkPartDest' name='checkPartDest' value='1' $cPartDest onclick='enabledParticularDestinatario(this.form);'/>
	 <select id='fPartDest' name='fPartDest' class='selectBig' $dPartDest>
	 <option value=''></option>";
	  getParticularDestinatario(0, $fPartDest);
	echo"
	</select>
</td>
<td width='125' align='right'>Estado:</td>
<td>
	<input type='checkbox' id='checkEstado' name='checkEstado' value='1' $cEstado onclick='this.checked=true';/>
	<select name='fEstado' id='fEstado' class='selectMed' $dEstado>
		 <option value='EV'>Enviado</option>";
		echo "
	</select>
</td>
</tr>

<tr>
<td width='125' align='right'>Tipo Documento:</td>
<td>
  <input type='checkbox' id='checkTdocumento' name='checkTdocumento' value='1' $cTDocumento onclick='enabledTdocumento(this.form);'/>
	 <select id='fTdocumento' name='fTdocumento' class='selectMed' $dTDocumento>
	 <option value=''></option>";
	  getTdocumento(0, $fTdocumento);
	echo"
	</select>
</td>
<td width='125' align='right'></td>
<td>
</td>
</tr>

</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Documentos</div><br />
<form/>";
///_________________________________________________________________________________________
$year = date("Y");
$sql="SELECT 
            cpds.Cod_DocumentoCompleto ,
			cpdext.Periodo,
			cpds.Cod_TipoDocumento,
			md.Dependencia,
			cpds.Asunto,
			cpdext.FlagEsParticular,
			cpdext.Cod_Organismos,
			cpdext.Cod_Dependencia,
			cpds.Descripcion,
			cpdext.Secuencia,
			cpds.FechaDocumento,
			cpds.PlazoAtencion,
			cpdext.Estado,
			cpdext.Cod_Documento,
			cpdext.FechaEnvio
        FROM 
		     cp_documentoextsalida cpds
			 inner join cp_documentodistribucionext cpdext on ((cpdext.Cod_Documento=cpds.Cod_Documento) and 
			                                                   (cpdext.Cod_TipoDocumento=cpds.Cod_TipoDocumento) and 
															   (cpdext.Periodo=cpds.Periodo))
			 inner join mastdependencias md on (md.CodDependencia = cpds.Cod_Dependencia) 
	   WHERE 
	        cpdext.Estado='EV' $filtro
	ORDER BY
	        cpdext.Cod_TipoDocumento,cpdext.Cod_Documento,cpdext.Periodo";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
$rows=$registros;  //echo $rows;
?>
<table width="1000" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td width="400">
		</td>
		<td align="right">
<input type="button" id="btNuevo" name="btNuevo" class="btLista" value="Acuse" onclick="cargarOpcion(this.form,'cpe_acusereciboext.php','SELF');"/>
<input type="button" id="btEditar" name="btEditar" class="btLista" value="Devolucion"  onclick="cargarOpcion(this.form, 'cpe_salidadevolucion.php','SELF');"/>
		</td>
    <td width="20"></td>    
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table align="center">
<tr>
  <td align="center">
  <div style="overflow:scroll; width:1000px; height:300px;">
<table width="1300" class="tblLista">
	<tr class="trListaHead">
      <th width="40"></th>
      <th>Nro.Documento</th>
      <th>A&ntilde;o</th>
      <th>Tipo Documento</th>
      <th>Remitente</th>
      <th>Destinatario</th>
      <th>Asunto</th>
      <th>Comentario</th>
      <th>Fecha Documento</th>
      <th>Fecha Enviado</th>
      <th>Plazo Atenci&oacute;n</th>
      <th>Estado</th>
   </tr>
<?php 
if ($registros!=0) {
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		//// _________ CONSULTO PARA OBTENER LA DESCRIPCION DE TIPO DE DOCUMENTO A MOSTRAR
		$sqltipodoc="SELECT 
		                    Descripcion 
		               FROM 
					        cp_tipocorrespondencia 
					  WHERE 
					        Cod_TipoDocumento='".$field['Cod_TipoDocumento']."'";
		$qrytipodoc=mysql_query($sqltipodoc) or die ($sqltipodoc.mysql_error());
		$fieldtipodoc=mysql_fetch_array($qrytipodoc);
		//// ________________________________________________________________________________________ 
		//// CONSULTO PARA OBTENER INFORMACION DEL DESTINATARIO ORGANISMO - DEPENDENCIA
		if($field[5]=='N'){
		   if($field[7]!=''){	
			 $sdocsalida="select 
								pforg.Organismo as Organismo, 
								pforg.RepresentLegal as r_legalorg, 
								pfdep.Representante as r_legaldep, 
								pfdep.Dependencia as dependencia  
						   from 
								pf_organismosexternos pforg, 
								pf_dependenciasexternas pfdep
						  where
								pforg.CodOrganismo= pfdep.CodOrganismo AND 
								pfdep.CodDependencia='".$field[7]."'";
		 }else{
		   $sdocsalida="select 
							    Organismo 
						   from 
								pf_organismosexternos
						  where
								CodOrganismo= '".$field[6]."'";
		 }
		
		$qdocsalida=mysql_query($sdocsalida) or die ($sdocsalida.mysql_error());
		$fdocsalida=mysql_fetch_array($qdocsalida);
		}
		
		//// _________ CONSULTO PARA OBTENER INFORMACION DEL REMITENTE ORGANISMO - DEPENDENCIA
		if($field['12']=='EV')$estado='Enviado';
	
		//// _____ CAMBIO DE FORMATO DE FECHA PARA MOSTRAR
		@list($a, $m, $d)=preg_split( '/[.-]/', $field['10']); $f_documento=$d.'-'.$m.'-'.$a;
		@list($a, $m, $d)=preg_split( '/[.-]/', $field['14']); $f_envio=$d.'-'.$m.'-'.$a;
		//if($field['FlagConfidencial']==1){$conf='checked onclick="this.checked=!this.checked"';}else{$conf='disabled="disabled"';}
		//// ___________________________________________________________________________________________
		$id = $field[13]."|".$field[1]."|".$field[9];
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='$id'>
		    <td align='center'><img src='imagenes/activo2.png' style='width:20px;height=10px;' />"; 
			echo" <input type='checkbox' name='documento' id='$id' value='$id' style='display:none'/></td>
			<td align='center'>".$field[0]."</td>
			<td align='center'>".$field[1]."</td>
			<td align='center'>".$fieldtipodoc['Descripcion']."</td>
			<td align='left'>".$field[3]."</td>";
			if($field[5]=='S'){
				echo"<td align='left'>PARTICULAR</td>";
			}else{
				echo"<td align='left'>".htmlentities($fdocsalida['Organismo'])."</td>";
			}
			echo"<td align='left'>".@$field[4]."</td>
			<td align='center'>".@$field['8']."</td>
			<td align='center'>$f_documento</td>
			<td align='center'>$f_envio</td>
			<td align='center'>".@$field['11']." dia(s)</td>
			<td align='center'>$estado</td>
		</tr>";
	}
	}
$rows=(int)$rows;
if(!isset($_ADMIN) or $_ADMIN == '') $_ADMIN = '';
if(!isset($_INSERT) or $_INSERT == '') $_INSERT = '';
if(!isset($_UPDATE) or $_ADMIN == '') $_UPDATE = '';
if(!isset($_DELETE) or $_DELETE == '') $_DELETE = '';
echo "
<script type='text/javascript' language='javascript'>
	totalRegistros($registros, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	totalLotes($registros, $rows, ".$_GET['limit'].");
</script>";				
?>
</table>
</div>
</td></tr></table>
</body>
</html>