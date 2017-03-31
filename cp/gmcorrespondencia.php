<?php 
include ("fphp.php");
$fechaCompleta=date("Y-m-d H:i:s");
$year=date("Y");
//// ___________________________________________________
////      INSERTAR DESTINATARIOS

//echo($accion);die;
if ($accion == "insertarDestinatarioEmp") {
	connect();
	if ($ventana == "insertarDestinatarioEmp") $ddesc = "disabled"; else $ddesc = "";
	$detalle = preg_split("/[;]+/", $detalles);
	foreach ($detalle as $registro) {
		if ($codigo == $registro) die("No se puede insertar dos veces el mismo $tabla!");
	}
	
	//	si no se encontraron errores inserta en la tabla los datos
	echo "||";
	
        //// Consulta para obtener el maximo secuencia que posee el empleado al momento	
	    $sa="select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$codigo."'";
		$qa=mysql_query($sa) or die ($sa.mysql_error());
		$fa=mysql_fetch_array($qa);
		
		//// Consulta para obtener el cargo
		$sb="select * from rh_empleadonivelacion where CodPersona='".$codigo."' and Secuencia='".$fa['0']."' ";
		$qb=mysql_query($sb) or die ($sb.mysql_error());
		$fb=mysql_fetch_array($qb);
		
		//// Consulta para obtener el resto de la informaci�n	
		$sql = "SELECT 
		               mp.NomCompleto as NomCompleto,
					   rp.DescripCargo as DescripCargo,
					   md.Dependencia as  nombre_dependencia,
					   md.CodDependencia as CodDependencia,
					   rp.CodCargo as CodCargo
		          FROM 
				      mastdependencias md,
					  rh_puestos rp,
					  mastpersonas mp
					  inner join mastempleado me on (me.CodPersona = mp.CodPersona)
				WHERE 
				      mp.CodPersona = '".$codigo."' "
                        
                        . " AND rp.CodCargo = me.CodCargo 
                            AND md.CodDependencia = me.CodDependencia;"; //echo $sql;
		$query = mysql_query($sql) or die ($sql.mysql_error());
                //echo $sql;die;
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);

	?>
		<td align="center" width="20">
        	<input type="hidden" name="codpersona" value="<?=$codigo?>" />
            <input type="hidden" name="cod_dependencia" value="<?=$field['CodDependencia']?>" />
            <input type="hidden" name="dependencia" value="<?=$field['nombre_dependencia']?>" />
            <input type="hidden" name="cargo" value="<?=$field['CodCargo']?>" />
            <input type="hidden" name="descp_cargo" value="<?=$field['DescripCargo']?>" />
			<?=$field['nombre_dependencia']?>
		</td>
        <td align="left" width="70"><?=$field['NomCompleto'];?></td>
        <td align="left" width="70">
        	
			<?=$field['DescripCargo'];?>
        </td>
          
        
        
        
	<?php
}else{
  if ($accion == "insertarDestinatarioDep") {
	connect();
	
	if ($ventana == "insertarDestinatarioDep") $ddesc = "disabled"; else $ddesc = "";
	
	$detalle = preg_split("/[;]+/", $detalles);
	foreach ($detalle as $registro) {
		if ($codigo == $registro) die("No se puede insertar dos veces el mismo $tabla!");
	}
	
	//	si no se encontraron errores inserta en la tabla los datos
	echo "||";
		
		list($cod_dependencia, $codPersona) = preg_split("/[|]+/", $codigo);
		
		/// Consulta para obenter la maxima secuencia en la tabla que tenga el empleado
		$sa = "select max(secuencia) from rh_empleadonivelacion where CodPersona = '".$codPersona."'";
		$qa = mysql_query($sa) or die ($sa.mysql_error());
		$fa = mysql_fetch_array($qa);
		
		/// Consulto para obtener los datos segun la secuencia obtenida anteriormente
		$sb = "select * from rh_empleadonivelacion where CodPersona = '".$codPersona."' and Secuencia = '".$fa['0']."'";
		$qb = mysql_query($sb) or die ($sb.mysql_error());
		$fb = mysql_fetch_array($qb);
				
		/// Consulta para obtener mas datos relacionados al empleado
		$sql = "SELECT 
		               mp.NomCompleto as NomCompleto,
					   rp.DescripCargo as DescripCargo,
					   md.Dependencia as  nombre_dependencia,
					   md.CodDependencia as CodDependencia,
					   rp.CodCargo as CodCargo
		          FROM 
				      rh_puestos rp,
					  mastdependencias md,
				      mastpersonas mp
					  inner join mastempleado me on (me.CodPersona = mp.CodPersona)
				WHERE 
				       mp.CodPersona = md.CodPersona AND rp.CodCargo = me.CodCargo and md.CodDependencia = '$cod_dependencia'";
                //echo $sql;die;
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
        
	?> 
       	<td align="center" width="20">
        	<input type="hidden" name="codpersona" value="<?=$codPersona?>" />
            <input type="hidden" name="cod_dependencia" value="<?=$field['CodDependencia']?>" />
            <input type="text" size="100" name="dependencia" id="dependencia" value="<?=@$field['nombre_dependencia'];?>" readonly/>
		</td>
        <td align="left" width="70">
        <input type="text" name="nombcompleto" id="nombcompleto" value="<?=@$field['NomCompleto'];?>" size="100" readonly/>
        </td>
        <td align="left" width="70"><input type="hidden" id="contador" value="<?=@$contador;?>"/>
        	<input type="hidden" name="cargo" id="cargo_<?=@$contador;?>" value="<?=@$field['CodCargo']?>" />
            <input type="text" name="descp_cargo" id="descp_<?=@$contador;?>" value="<?=@$field['DescripCargo'];?>" size="100" readonly  onclick="asumoInsert(this.id)|prueba(this.id);"/>
        </td>
	<?php
}
}
////   ____________________________________________________________
////   INSERTAR DESTINATARIO PARA SALIDA DE DOCUMENTOS EXTERNOS
////   ____________________________________________________________
if ($accion == "insertarDestinatarioDepExt") {
	connect();
	
	if ($ventana == "insertarDestinatarioDepExt") $ddesc = "disabled"; else $ddesc = "";
	
	$detalle = preg_split( "/[;]+/", $detalles);
	foreach ($detalle as $registro) {
		if ($cod2 == $registro) die("No se puede insertar dos veces el mismo $tabla!");
	}
	
	//	si no se encontraron errores inserta en la tabla los datos
	echo "||";
		$sql = "SELECT * FROM pf_dependenciasexternas WHERE CodDependencia='".$cod2."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);

	?>
		<td width="250">
           <input type="hidden" name="codigo_organismo" value="<?=$field['CodOrganismo']?>" />
           <input type="hidden" name="codigo_dependencia" value="<?=$cod2?>" />
		   <input type="text" name="ente" id="ente" size="70" value="<?=utf8_encode($field['Dependencia'])?>" readonly/>
           <input type="hidden" name="EsParticular"  value="N"/>
        </td>
        <td>
           <input type="text" name="representante" id="representante" size="70" value="<?=utf8_encode($field['Representante'])?>"/>
        </td>
        <td align="left" width="70">
            <input type="text" name="cargorepresentante"  size="60" id="cargorepresentante" value="<?=utf8_encode($field['Cargo'])?>"/>
        	<input type="hidden" name="cargo" value="<?=$field['CodCargo']?>"/>
        </td>
	<?php
}else{
 if ($accion == "insertarDestinatarioOrgExt") {
	connect();
	
	if ($ventana == "insertarDestinatarioOrgExt") $ddesc = "disabled"; else $ddesc = "";
	
	$detalle = preg_split( "/[;]+/", $detalles);
	foreach ($detalle as $registro) {
		if ($codigo == $registro) die("No se puede insertar dos veces el mismo $tabla!");
	}
	
	//	si no se encontraron errores inserta en la tabla los datos
	echo "||";
		$sql = "SELECT * FROM pf_organismosexternos WHERE CodOrganismo='".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);

	?>
		<td align="center" width="20">
            <input type="text" name="ente" id="ente" size="70" value="<?=utf8_encode($field['Organismo'])?>" readonly/>
        	<input type="hidden" name="codigo_organismo" value="<?=$codigo?>" />
            <input type="hidden" name="codigo_dependencia" value="" />
            <input type="hidden" name="EsParticular" value="N"/>
		</td>
        <td align="left" width="70">
            <input type="text" name="representante" id="representante" size="70" value="<?=utf8_encode($field['RepresentLegal'])?>"/>
        </td>
        <td align="left" width="70">
            <input type="text" name="cargorepresentante"  size="60" id="cargorepresentante" value="<?=utf8_encode($field['Cargo'])?>"/>
        	<input type="hidden" name="cargo" id="cargo" value="<?=utf8_encode($field['Cargo'])?>"/>
        </td>
	<?php
  }else{
    ////  -------------------------------------------------------------------------------
    ////  INSERTAR DESTINATARIO PARA SALIDA DE DOCUMENTOS EXTERNOS "PARTICULAR"
    ////  -------------------------------------------------------------------------------
    if($accion=='insertarDestinatarioParticularExt'){
      connect();
	
	if ($ventana == "insertarDestinatarioParticularExt") $ddesc = "disabled"; else $ddesc = "";
	
	$detalle = preg_split( "/[;]+/", $detalles);
	foreach ($detalle as $registro) {
		if ($codigo == $registro) die("�No se puede insertar dos veces el mismo $tabla!");
	}
	
	//	si no se encontraron errores inserta en la tabla los datos
	echo "||";
		$sql = "SELECT * FROM cp_particular WHERE CodParticular='".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);

	?>
		<td align="center" width="20">
            <input type="text" name="ente" id="ente" size="70" value="PARTICULAR" readonly/>
        	<input type="hidden" name="codigo_organismo" value="<?=$codigo?>" />
            <input type="hidden" name="codigo_dependencia" value="" />
            <input type="hidden" name="EsParticular" value="S"/>
		</td>
        <td align="left" width="70">
            <input type="text" name="representante" id="representante" size="70" value="<?=utf8_encode($field['Nombre'])?>"/>
        </td>
        <td align="left" width="70">
            <input type="text" name="cargorepresentante"  size="60" id="cargorepresentante" value="<?=utf8_encode($field['Cargo'])?>"/>
        	<input type="hidden" name="cargo" id="cargo" value="<?=utf8_encode($field['Cargo'])?>"/>
        </td>
	<?php
	
	}
  }
}
//// -------------------------------------------------------------------------------------
////  GUARDAR NUEVO DOCUMENTO DE ENTRADA EXTERNA
//// -------------------------------------------------------------------------------------
if($accion=='guardarDestinatarios'){
connect();
   if($_POST['organismo'] != '') $org = $_POST['organismo'];
   else $org = $_POST['codparticular'];
   
	$sql = "UPDATE cp_documentoextentrada
			SET
				FlagInformeEscrito = '".$infor_escrito."',
				FlagHablarConmigo = '".$hablar_alrespecto."',
				FlagCoordinarcon = '".$coord_con."',
				CoordinarCon = '".$coord_con2."',
				FlagPrepararMemo = '".$pre_memo."',
				PrepararMemo = '".$pre_memo2."',
				FlagInvestigarInformar = '".$inv_inforver."',
				FlagTramitarConclusion = '".$tram_conclusion."',
				FlagDistribuir = '".$distribuir."',
				FlagConocimiento = '".$pconocimiento_fp."',
				FlagPrepararConstentacion = '".$pre_contfirm."',
				FlagArchivar = '".$archivar."',
				FlagRegistrode = '".$registro_de."',
				RegistroDe = '".$registro_de2."',
				FlagPrepararOficio = '".$prep_oficio."',
				PrepararOficio = '".$prep_oficio2."',
				FlagConocerOpinion = '".$conocer_opinion."',
				FlagTramitarloCaso = '".$tram_casoproceden."',
				FlagAcusarRecibo = '".$acusar_recibo."',
				FlagTramitarEn = '".$tram_dias."',
				TramitarEn = '".$tram_dias2."',
				Estado='RE',
				UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
				UltimaFechaModif='".date("Y-m-d H:i:s")."'
			WHERE
				Cod_Organismos = '$org' AND
				NumeroDocumentoExt = '".$n_documento."' AND
				Cod_TipoDocumento = '".$t_documento."' AND
				NumeroRegistroInt = '".$cod_documento."'";
	$qry=mysql_query($sql) or die ($sql.mysql_error());
	
 	 
	$lineas = preg_split("/[;]/", $detalles);
	foreach ($lineas as $linea) {
		@list($codpersona, $cargo)=preg_split( '/[|]/', $linea);
		/// CALCULAR SECUENCIA *********************************
		$sql = "INSERT INTO cp_documentodistribucion(
							Cod_Organismo,
							CodDependencia,
							CodPersona,
							Cod_Documento,
							Cod_TipoDocumento,
							CodCargo,
							FechaDistribucion,
							FechaEnvio,
							Estado,
							UltimoUsuario,
							UltimaFechaModif,
							Periodo,
							PlazoAtencion,
							Procedencia)
					 VALUES (
							'".$_SESSION['ORGANISMO_ACTUAL']."',
							'".$depenOrigen."',
							'".$codpersona."',
							'".$reg_interno."',
							'".$t_documento."',
							'".$cargo."',
							'".date("Y-m-d")."',
							'".date("Y-m-d")."',
							'EV',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."',
							'".date("Y")."',
							'".$tram_dias2."',
							'EXT')";
		$qrydocent=mysql_query($sql) or die ($sql.mysql_error());

 }
}
////  ------------------------------------------------------------------------------------
////   GUARDAR DATOS CORRESPONDENCIA DE SALIDA EXT
////  ------------------------------------------------------------------------------------
if($accion=='guardarSalidaNueva'){
connect();
    $scon= "select max(Cod_Documento) from 
	                                     cp_documentoextsalida 
								    where 
									     CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
										 Cod_TipoDocumento='".$_POST['t_documento']."' and
										 Periodo = '".date("Y")."'";
	$qcon= mysql_query($scon) or die ($scon.mysql_error());
	$fcon=mysql_fetch_array($qcon);
	$cod_documento=(int) ($fcon[0]+1); /// CODIGO DEL DOCUMENTO
    $cod_documento=(string) str_repeat("0",4-strlen($cod_documento)).$cod_documento;
	$cod_int = $codigo_interno;
	//$codigoCompleto = CEDA."-".$cod_int."-".$cod_documento."-".$year; echo $codigoCompleto;
	$codigoCompleto = $cod_int."-".$cod_documento."-".$year; //echo $codigoCompleto;
	$sql = "INSERT cp_documentoextsalida( CodOrganismo,
										  Cod_Documento,
										  Cod_TipoDocumento,
										  Cod_DocumentoCompleto,
										  Cod_Dependencia,
										  CodInterno,
										  FechaRegistro,
										  Remitente,
										  Cargo,
										  Asunto,
										  Descripcion,
										  Periodo,
										  PlazoAtencion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif,
										  FechaDocumento)
								    VALUES('".$_SESSION['ORGANISMO_ACTUAL']."',								         
										  '$cod_documento',
										  '".$t_documento."',
										  '$codigoCompleto',
										  '".$dep_interna."',
										  '".$codigo_interno."',
										  '".date("Y-m-d")."',
										  '".$codigo_persona."',
										  '".$codigo_cargo."',
										  '".$asunto."',
										  '".$descrip."',
										  '".date("Y")."',
										  '".$plazo."',
										  'PR',
										  '".$_SESSION['USUARIO_ACTUAL']."',
										  '".date("Y-m-d H:i:s")."',
										  '".date("Y-m-d")."')";
	$qry=mysql_query($sql) or die ($sql.mysql_error());
 	 
	$lineas = preg_split( "/[;]+/", $detalles);
	foreach ($lineas as $linea) {
		list($codigo_organismo, $codigo_dependencia, $EsParticular, $representante, $cargorepresentante)=SPLIT( '[|]', $linea);
		
		$scon= "select max(Secuencia) from 
	                                     cp_documentodistribucionext 
								    where 
									     CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
										 Cod_TipoDocumento='".$_POST['t_documento']."' and
										 Cod_Documento = '$cod_documento'";
	   $qcon= mysql_query($scon) or die ($scon.mysql_error());
	   $fcon=mysql_fetch_array($qcon);
	   $secuencia=(int) ($fcon[0]+1); /// CODIGO DEL DOCUMENTO
       $secuencia=(string) str_repeat("0",4-strlen($secuencia)).$secuencia;
	   
	   if($EsParticular=='S'){$valorPart = 'S';}else{$valorPart = 'N'; }
		
		$sql = "INSERT INTO cp_documentodistribucionext ( CodOrganismo ,
														Cod_Documento,
														Cod_TipoDocumento,
														Periodo,
														Secuencia,
														Cod_Organismos,
														Cod_Dependencia,
														Representante,
														Cargo,
														FechaDistribucion,
														PlazoAtencion,
														Estado,
														FlagEsParticular,
														UltimoUsuario,
														UltimaFechaModif)
												VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
												        '$cod_documento',
														'".$t_documento."',
														'".date("Y-m-d")."',
														'$secuencia',
														'".$codigo_organismo."',
														'".$codigo_dependencia."',
														'".$representante."',
														'".$cargorepresentante."',
														'".date("Y-m-d")."',
														'".$plazo."',
												        'PR',
														'$valorPart',
														'".$_SESSION["USUARIO_ACTUAL"]."',
														'".date("Y-m-d H:i:s")."')";
		$qry=mysql_query($sql) or die ($sql.mysql_error());
   }	
}
////  ------------------------------------------------------------------------------------
////  PROCEDIMIENTO PARA GUARDAR NUEVO DOCUMENTO INTERNO
////  ------------------------------------------------------------------------------------ 
if($accion=='guardarDocumentoInterno'){
connect();
    ////  Consulta para verificar el Periodo
	$s_periodo = "select 
	                     max(Periodo) 
					from 
					     cp_documentointerno 
				   where 
				         CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and 
						 Cod_Dependencia='".$_POST['dep_interna']."' and 
						 Cod_TipoDocumento='".$_POST['t_documento']."'";
	$q_periodo = mysql_query($s_periodo) or die ($s_periodo.mysql_error());
	$f_periodo = mysql_fetch_array($q_periodo);
	
	//// en caso de Periodo ser igual al consultado
	if($f_periodo[0]==date("Y")){
	
	   $scon= "select 
			   max(Cod_Documento) 
		from 
			   cp_documentointerno 
		where 
			   CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
			   Cod_TipoDocumento='".$_POST['t_documento']."' and 
			   Cod_Dependencia='".$_POST['dep_interna']."' and
			   Periodo = '".date("Y")."'";
	   $qcon= mysql_query($scon) or die ($scon.mysql_error());
	   $fcon=mysql_fetch_array($qcon);
	   
	   $cod_documento=(int) ($fcon[0]+1); /// CODIGO DEL DOCUMENTO
       $cod_documento=(string) str_repeat("0",4-strlen($cod_documento)).$cod_documento;
	   $cod_int = $codigo_interno;
	   $codigoCompleto = $cod_int."-".$cod_documento."-".$year; //echo $codigoCompleto;
	   
	   
	   $s_dep = "select * from mastdependencias where CodDependencia='".$dep_interna."'";
	   $q_dep = mysql_query($s_dep) or die ($s_dep.mysql_error());
	   $f_dep = mysql_fetch_array($q_dep);
	   
	   /// PREGUNTO SI ANEXSI1 VIENE VACIO O NO
	   if($_POST['anexsi1']=='S'){
	     $sql = "INSERT cp_documentointerno( CodOrganismo,
										  Cod_Documento,
										  Cod_TipoDocumento ,
										  Cod_DocumentoCompleto,
										  Cod_Dependencia,
										  CodInterno,
										  FechaRegistro,
										  Cod_Remitente,
										  Cod_CargoRemitente,
										  Asunto,
										  Descripcion,
										  Periodo,
										  PlazoAtencion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif,
										  FlagsAnexo,
										  FechaDocumento,
										  DescripcionAnexo,
										  Descp_CargoRemitente,
										  Descp_DependenciaRemitente,
										  FlagEncargaduriaEspecial)
								    VALUES('".$_SESSION['ORGANISMO_ACTUAL']."',								         
										  '$cod_documento',
										  '".$t_documento."',
										  '$codigoCompleto',
										  '".$dep_interna."',
										  '".$codigo_interno."',
										  '".date("Y-m-d")."',
										  '".$codigo_persona."',
										  '".$codigo_cargo."',
										  '".$asunto."',
										  '".$descrip."',
										  '".date("Y")."',
										  '".$plazo."',
										  'PR',
										  '".$_SESSION['USUARIO_ACTUAL']."',
										  '".date("Y-m-d H:i:s")."',
										  '".$_POST['anexsi1']."',
										  '".date("Y-m-d")."',
										  '".$_POST['anexDescp']."',
										  '".$cargodestinatario_int."',
										  '".$f_dep['Dependencia']."',
										  '".$FlagEncargaduriaEspecial."')";
	   $qry=mysql_query($sql) or die ($sql.mysql_error());
	   }else{
		 /// -----------------------------------------------
		 if($_POST['anexsi2']=='N'){  
		   $sql = "INSERT cp_documentointerno( CodOrganismo,
										  Cod_Documento ,
										  Cod_TipoDocumento ,
										  Cod_DocumentoCompleto,
										  Cod_Dependencia,
										  CodInterno,
										  FechaRegistro,
										  Cod_Remitente,
										  Cod_CargoRemitente,
										  Asunto,
										  Descripcion,
										  Periodo,
										  PlazoAtencion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif,
										  FlagsAnexo,
										  FechaDocumento,
										  DescripcionAnexo,
										  Descp_CargoRemitente,
										  Descp_DependenciaRemitente,
										  FlagEncargaduriaEspecial)
								    VALUES('".$_SESSION['ORGANISMO_ACTUAL']."',								         
										  '$cod_documento',
										  '".$t_documento."',
										  '$codigoCompleto',
										  '".$dep_interna."',
										  '".$codigo_interno."',
										  '".date("Y-m-d")."',
										  '".$codigo_persona."',
										  '".$codigo_cargo."',
										  '".$asunto."',
										  '".$descrip."',
										  '".date("Y")."',
										  '".$plazo."',
										  'PR',
										  '".$_SESSION['USUARIO_ACTUAL']."',
										  '".date("Y-m-d H:i:s")."',
										  '".$_POST['anexsi2']."',
										  '".date("Y-m-d")."',
										  '".$_POST['anexDescp']."',
										  '".$cargodestinatario_int."',
										  '".$f_dep['Dependencia']."',
										  '".$FlagEncargaduriaEspecial."')";
	    $qry=mysql_query($sql) or die ($sql.mysql_error());
		 }else{
		   $sql = "INSERT cp_documentointerno( CodOrganismo,
										  Cod_Documento ,
										  Cod_TipoDocumento ,
										  Cod_DocumentoCompleto,
										  Cod_Dependencia,
										  CodInterno,
										  FechaRegistro,
										  Cod_Remitente,
										  Cod_CargoRemitente,
										  Asunto,
										  Descripcion,
										  Periodo,
										  PlazoAtencion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif,
										  FechaDocumento,
										  Descp_CargoRemitente,
										  Descp_DependenciaRemitente,
										  FlagEncargaduriaEspecial)
								    VALUES('".$_SESSION['ORGANISMO_ACTUAL']."',								         
										  '$cod_documento',
										  '".$t_documento."',
										  '$codigoCompleto',
										  '".$dep_interna."',
										  '".$codigo_interno."',
										  '".date("Y-m-d")."',
										  '".$codigo_persona."',
										  '".$codigo_cargo."',
										  '".$asunto."',
										  '".$descrip."',
										  '".date("Y")."',
										  '".$plazo."',
										  'PR',
										  '".$_SESSION['USUARIO_ACTUAL']."',
										  '".date("Y-m-d H:i:s")."',
										  '".date("Y-m-d")."',
										  '".$cargodestinatario_int."',
										  '".$f_dep['Dependencia']."',
										  '".$FlagEncargaduriaEspecial."')";
	      $qry=mysql_query($sql) or die ($sql.mysql_error());
		}
	   }	
	$lineas = preg_split( "/[;]+/", $detalles);
	foreach ($lineas as $linea) {
		list($codpersona, $cod_dependencia, $dependencia, $cargo, $descp_cargo)= preg_split( "/[|]+/", $linea); 	
                //echo $codpersona." ".$cod_dependencia." ".$dependencia." ".$cargo."".$descp_cargo;die;
                $sql = "INSERT INTO cp_documentodistribucion (
							Cod_Organismo,
							CodPersona,
							CodDependencia,
							CodCargo,
							Descp_DependenciaDestinatario,
							Descp_CargoDestinatario,
							Cod_Documento,
							Cod_TipoDocumento,
							FechaDistribucion,
							Estado,
							UltimoUsuario,
							UltimaFechaModif,
							Periodo,
							PlazoAtencion,
							Procedencia)
					 VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
							'".$codpersona."',
							'".$cod_dependencia."',
							'".$cargo."',
							'".$dependencia."',
							'".$descp_cargo."',
							'$codigoCompleto',
							'".$t_documento."',
							'".date("Y-m-d")."',
							'PR',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."',
							'".date("Y")."',
							'".$plazo."',
							'INT')"; //echo $sql;
		$qrydocent=mysql_query($sql) or die ($sql.mysql_error());
	}
   }
   
   /// en caso de Periodo ser distinto al consultado
   if($f_periodo[0]!=date("Y")){
	   
	    $scon= "select 
			          max(Cod_Documento) 
		         from 
			          cp_documentointerno 
		         where 
					   CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
					   Cod_TipoDocumento='".$_POST['t_documento']."' and 
					   Cod_Dependencia='".$_POST['dep_interna']."' and
					   Periodo = '".date("Y")."'";
	   $qcon= mysql_query($scon) or die ($scon.mysql_error());
	   $fcon=mysql_fetch_array($qcon);
	   $cod_documento=(int) ($fcon[0]+1); /// CODIGO DEL DOCUMENTO
       $cod_documento=(string) str_repeat("0",4-strlen($cod_documento)).$cod_documento;
	   $cod_int = $codigo_interno;
	   $codigoCompleto = $cod_int."-".$cod_documento."-".$year; //echo $codigoCompleto;
	
	   if($_POST['anexsi1']=='S'){
	     $sql = "INSERT cp_documentointerno( CodOrganismo,
										  Cod_Documento ,
										  Cod_TipoDocumento ,
										  Cod_DocumentoCompleto,
										  Cod_Dependencia,
										  CodInterno,
										  FechaRegistro,
										  Cod_Remitente,
										  Cod_CargoRemitente,
										  Asunto,
										  Descripcion,
										  Periodo,
										  PlazoAtencion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif,
										  FlagsAnexo,
										  FechaDocumento,
										  DescripcionAnexo)
								    VALUES('".$_SESSION['ORGANISMO_ACTUAL']."',								         
										  '$cod_documento',
										  '".$t_documento."',
										  '$codigoCompleto',
										  '".$dep_interna."',
										  '".$codigo_interno."',
										  '".date("Y-m-d")."',
										  '".$codigo_persona."',
										  '".$codigo_cargo."',
										  '".$asunto."',
										  '".$descrip."',
										  '".date("Y")."',
										  '".$plazo."',
										  'PR',
										  '".$_SESSION['USUARIO_ACTUAL']."',
										  '".date("Y-m-d H:i:s")."',
										  '".$_POST['anexsi1']."',
										  '".date("Y-m-d")."',
										  '".$_POST['anexDescp']."')";
	     $qry=mysql_query($sql) or die ($sql.mysql_error());
	   }else{
		 /// ------------------------------------------------
		 if($_POST['anexsi2']=='S'){  
	       $sql = "INSERT cp_documentointerno( CodOrganismo,
										  Cod_Documento ,
										  Cod_TipoDocumento ,
										  Cod_DocumentoCompleto,
										  Cod_Dependencia,
										  CodInterno,
										  FechaRegistro,
										  Cod_Remitente,
										  Cod_CargoRemitente,
										  Asunto,
										  Descripcion,
										  Periodo,
										  PlazoAtencion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif,
										  FlagsAnexo,
										  FechaDocumento,
										  DescripcionAnexo)
								    VALUES('".$_SESSION['ORGANISMO_ACTUAL']."',								         
										  '$cod_documento',
										  '".$t_documento."',
										  '$codigoCompleto',
										  '".$dep_interna."',
										  '".$codigo_interno."',
										  '".date("Y-m-d")."',
										  '".$codigo_persona."',
										  '".$codigo_cargo."',
										  '".$asunto."',
										  '".$descrip."',
										  '".date("Y")."',
										  '".$plazo."',
										  'PR',
										  '".$_SESSION['USUARIO_ACTUAL']."',
										  '".date("Y-m-d H:i:s")."',
										  '".$_POST['anexsi2']."',
										  '".date("Y-m-d")."',
										  '".$_POST['anexDescp']."')";
	       $qry=mysql_query($sql) or die ($sql.mysql_error());
		 }else{
		   $sql = "INSERT cp_documentointerno( CodOrganismo,
										  Cod_Documento,
										  Cod_TipoDocumento,
										  Cod_DocumentoCompleto,
										  Cod_Dependencia,
										  CodInterno,
										  FechaRegistro,
										  Cod_Remitente,
										  Cod_CargoRemitente,
										  Asunto,
										  Descripcion,
										  Periodo,
										  PlazoAtencion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif,
										  FechaDocumento)
								    VALUES('".$_SESSION['ORGANISMO_ACTUAL']."',								         
										  '$cod_documento',
										  '".$t_documento."',
										  '$codigoCompleto',
										  '".$dep_interna."',
										  '".$codigo_interno."',
										  '".date("Y-m-d")."',
										  '".$codigo_persona."',
										  '".$codigo_cargo."',
										  '".$asunto."',
										  '".$descrip."',
										  '".date("Y")."',
										  '".$plazo."',
										  'PR',
										  '".$_SESSION['USUARIO_ACTUAL']."',
										  '".date("Y-m-d H:i:s")."',
										  '".date("Y-m-d")."')";
	       $qry=mysql_query($sql) or die ($sql.mysql_error());
		 }
	   }
 	 
	$lineas = preg_split("/[;]+/", $detalles);
	foreach ($lineas as $linea) {
		@list($codpersona, $cod_dependencia, $dependencia, $cargo, $descp_cargo)=preg_split( "/[|]+/", @$linea); 
				
		$sql = "INSERT INTO cp_documentodistribucion (
							Cod_Organismo,
							CodPersona,
							CodDependencia,
							CodCargo,
							Descp_DependenciaDestinatario,
							Descp_CargoDestinatario,
							Cod_Documento,
							Cod_TipoDocumento,
							FechaDistribucion,
							Estado,
							UltimoUsuario,
							UltimaFechaModif,
							Periodo,
							PlazoAtencion,
							Procedencia)
					 VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
							'".$codpersona."',
							'".$cod_dependencia."',
							'".$cargo."',
							'".$dependencia."',
							'".$descp_cargo."',
							'$codigoCompleto',
							'".$t_documento."',
							'".date("Y-m-d")."',
							'PR',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."',
							'".date("Y")."',
							'".$plazo."',
							'INT')";
		$qrydocent=mysql_query($sql) or die ($sql.mysql_error());
	}
   }
}
////  -----------------------------------------------------------------------------------
////  PROCEDIMIENTO PARA EDITAR DOCUMENTO INTERNO
////  ----------------------------------------------------------------------------------- 
if($accion=='guardarDocumentoInternoEditar'){
connect();

$s_dep = "select * from mastdependencias where CodDependencia='".$dep_interna."'";
$q_dep = mysql_query($s_dep) or die ($s_dep.mysql_error());
$f_dep = mysql_fetch_array($q_dep);

/// Actualizo los campos en la tabla cp_documento internos que resulten ser modificados   
$sa="update cp_documentointerno set 
									Asunto = '".$_POST['asunto']."',
									Descripcion = '".$_POST['descrip']."',
									PlazoAtencion = '".$_POST['plazo']."',
									DescripcionAnexo = '".$_POST['anexDescp']."',
									FlagsAnexo = '".$_POST['Anexos']."',
									Descp_CargoRemitente = '".$_POST['cargodestinatario_int']."',
									Descp_DependenciaRemitente = '".$f_dep['Dependencia']."'
                              where 
							        CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
								    Periodo = '".date("Y")."' and 
									Cod_DocumentoCompleto ='".$_POST['n_documento']."' and 
									Cod_TipoDocumento = '".$_POST['t_documento']."' "; 
//echo $sa;
$qa=mysql_query($sa) or die ($sa.mysql_error());

if($_POST['Estado']=='PP') $estado = 'PE'; elseif($_POST['Estado']=='PR') $estado = 'PR';
//// Verifico  y actualizo cambios en cp_distribucion, se eliminan o a�aden destinatarios seg�n
//// se requiera.
$c="delete from cp_documentodistribucion where Cod_Documento = '".$_POST['n_documento']."'";
$qc=mysql_query($c) or die ($c.mysql_error());

$lineas = preg_split( "/[;]+/", $detalles);
	foreach ($lineas as $linea) {                                                                          
		list($codpersona, $cod_dependencia, $Descp_DependenciaDestinatario, $cargo, $Descp_CargoDestinatario )=preg_split( '/[|]+/', $linea);
		//echo $codpersona, $cod_dependencia, $Descp_DependenciaDestinatario, $cargo, $Descp_CargoDestinatario;
		if(($codpersona!='') and ($cod_dependencia!='') and ($cargo!='')){		
		$sql = "INSERT INTO cp_documentodistribucion (
							Cod_Organismo,
							CodDependencia,
							CodPersona,
							Cod_Documento,
							Cod_TipoDocumento,
							CodCargo,
							FechaDistribucion,
							UltimoUsuario,
							UltimaFechaModif,
							Periodo,
							PlazoAtencion,
							Procedencia,
							Estado,
							Descp_DependenciaDestinatario,
							Descp_CargoDestinatario)
					 VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
							'".$cod_dependencia."',
							'".$codpersona."',
							'".$_POST['n_documento']."',
							'".$t_documento."',
							'".$cargo."',
							'".date("Y-m-d")."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."',
							'".date("Y")."',
							'".$plazo."',
							'INT',
							'$estado',
							'$Descp_DependenciaDestinatario',
							'$Descp_CargoDestinatario')";
	$qrydocent=mysql_query($sql) or die ($sql.mysql_error());
		}
   }
}
////  -----------------------------------------------------------------------------------
////  PROCEDIMIENTO PARA EDITAR DOCUMENTO EXTERNO DE SALIDA
////  ----------------------------------------------------------------------------------- 
if($accion=="guardarSalidaExtEditar"){
connect();

/// Actualizo los campos en la tabla cp_documentoextsalida que resulten ser modificados    
$sa="update cp_documentoextsalida set Cod_tipoDocumento = '".$_POST['t_documento']."',
									Asunto = '".$_POST['asunto']."',
									Descripcion = '".$_POST['descrip']."',
									PlazoAtencion = '".$_POST['plazo']."'
                              where 
							        CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
								    Periodo = '".date("Y")."' and 
									Cod_DocumentoCompleto ='".$_POST['n_documento']."' ";
$qa=mysql_query($sa) or die ($sa.mysql_error());

/// Verifico  y actualizo cambios en cp_distribucion, se eliminan o a�aden destinatarios seg�n
/// se requiera.
$c="delete from cp_documentodistribucionext where Cod_Documento = '".$_POST['cod_documento']."'";
$qc=mysql_query($c) or die ($c.mysql_error());

// Inserto las destinatario editados y agregados
$lineas = split(";", $detalles);
	foreach ($lineas as $linea) {
		list($codigo_organismo, $codigo_dependencia, $EsParticular, $representante, $cargorepresentante)=SPLIT( '[|]', $linea);
		
		$scon= "select max(Secuencia) from 
	                                     cp_documentodistribucionext 
								    where 
									     CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
										 Cod_TipoDocumento='".$_POST['t_documento']."' and
										 Cod_Documento = '$cod_documento' and
										 Periodo = '".date("Y")."'"; //echo $scon;
	   $qcon= mysql_query($scon) or die ($scon.mysql_error());
	   $fcon=mysql_fetch_array($qcon); //echo "Secuencia=".$fcon[Seciencia];
	   $secuencia=(int) ($fcon[0]+1); /// CODIGO DEL DOCUMENTO
       $secuencia=(string) str_repeat("0",4-strlen($secuencia)).$secuencia; //echo "NroSecuencia=".$secuencia;
	   
	   if($EsParticular=='S'){$valorPart = 'S';}else{$valorPart = 'N'; }
		
		$sql = "INSERT INTO cp_documentodistribucionext ( CodOrganismo,
														Cod_Documento,
														Cod_TipoDocumento,
														Periodo,
														Secuencia,
														Cod_Organismos,
														Cod_Dependencia,
														Representante,
														Cargo,
														FechaDistribucion,
														PlazoAtencion,
														Estado,
														FlagEsParticular,
														UltimoUsuario,
														UltimaFechaModif)
												VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
												        '$cod_documento',
														'".$t_documento."',
														'".date("Y-m-d")."',
														'$secuencia',
														'".$codigo_organismo."',
														'".$codigo_dependencia."',
														'".$representante."',
														'".$cargorepresentante."',
														'".date("Y-m-d")."',
														'".$plazo."',
												        'PR',
														'$valorPart',
														'".$_SESSION["USUARIO_ACTUAL"]."',
														'".date("Y-m-d H:i:s")."')";
		$qry=mysql_query($sql) or die ($sql.mysql_error()); //echo $sql;
   }	
}
///// ----------------------------------------------------------------------------------- 
/////  GUARDAR ENVIO DOCUMENTO SALIDA  
///// -----------------------------------------------------------------------------------
if($accion=="guardarEnvio"){
connect();
   
   $lineas = split(";", $detalles);
	foreach ($lineas as $linea) {
		list($cod_documento, $periodos, $secuencia)=SPLIT( '[|]', $linea);
		//echo"$cod_documento, $periodos, $secuencia";		      
        $CONT++; echo "CONTADOR= ".$CONT; echo"Cod_Documento=".$cod_documento; echo"Periodos=".$periodos; echo"Secuencia=".$secuencia;
        $s="update cp_documentodistribucionext set Cod_PersonaResp = '".$codempleado."' ,
													    Cod_CargoResp = '".$cod_cargoremit."',
													    FechaEnvio = '".date("Y-m-d")."',
													    Estado = 'EV',
													    UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
													    UltimaFechaModif = '".date("Y-m-d H:i:s")."'
											        where
													    CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and
													    Periodo = '".$periodos."' and
														Secuencia = '".$secuencia."' and
														Cod_Documento = '".$cod_documento."'";
        $q=mysql_query($s) or die ($s.mysql_error());
   
        //// SELECT PARA OBTENER DATOS QUE SERAN INTRODUCIDOS EN CP_HISTORICODOCUMENTOEXTSALIDA
	    $scon="select 
	   				   cpdist.Cod_TipoDocumento as Cod_TipoDocumento,
					   cpdext.Cod_Dependencia as CodDependenciaInterna,
					   cpdist.Cod_Dependencia as Cod_DependenciaExterna,
					   cpdext.FechaRegistro as FechaRegistro,
					   cpdist.Cod_Organismos as Cod_Organismos,
					   cpdist.Representante as Destinatario,
					   cpdist.Cargo as CargoDestinatario,
					   cpdext.Remitente as Remitente,
					   cpdext.Cargo as CargoRemitente,
					   cpdext.Asunto as Asunto,
					   cpdext.Descripcion as Descripcion,
					   cpdext.FechaDocumento as FechaDocumento,
					   cpdext.Contenido as Contenido,
					   cpdist.Cod_PersonaResp as Cod_PersonaResp,
					   cpdist.Cod_CargoResp as Cod_CargoResp,
					   cpdist.FechaEnvio as FechaEnvio,
					   cpdist.Estado as Estado,
					   cpdist.FlagEsParticular as flagparticular
				  from 
					   cp_documentodistribucionext cpdist
					   inner join cp_documentoextsalida cpdext on (cpdext.Cod_Documento = cpdist.Cod_Documento)
				 where 
					   cpdist.CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and
					   cpdist.Periodo = '".$periodos."' and
					   cpdist.Secuencia = '".$secuencia."' and
					   cpdist.Cod_Documento = '".$cod_documento."'";
       $qcon=mysql_query($scon) or die ($scon.mysql_error());
	   $fcon=mysql_fetch_array($qcon);
	  
	   //// SELECT PARA OBTENER SECUENCIA MAX
	   $smax= "select max(Secuencia) from 
	                                    cp_historicodocumentoextsalida 
								   where 
								        CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and 
										Cod_Documento = '".$cod_documento."' and
										Periodo = '".$periodos."' and
										Cod_Historico = '".$secuencia."'";
	  $qmax= mysql_query($smax) or die ($smax.mysql_error());
	  
	  if(($fcon['flagparticular']=='N')or($fcon['flagparticular']=='N')){
	    $fmax= mysql_fetch_array($qmax);
	  
	    $valorSecuencia=(int) ($fmax[0]+1);
	    $valorSecuencia=(string) str_repeat("0",4-strlen($valorSecuencia)).$valorSecuencia;
	    //// INSERT PARA INGRESAR DATOS OBTENIDOS DE LA CONSULTA ANTERIOR 
        $shist="insert into cp_historicodocumentoextsalida (CodOrganismo,
														  Cod_Documento,
														  Cod_TipoDocumento,
														  Cod_Historico,
														  Periodo,
														  Secuencia,
														  CodDependencia,
														  Cod_Dependencia,
														  FechaRegistro,
														  Cod_Organismos,
														  Destinatario,
														  CargoDestinatario,
														  Remitente,
														  Cargo,
														  Asunto,
														  Descripcion,
														  FechaDocumento,
														  Contenido,
														  Cod_PersonaResp,
														  Cod_CargoResp,
														  FechaEnvio,
														  Estado,
														  UltimoUsuario,
														  UltimaFechaModif)
												  values ('".$_SESSION['ORGANISMO_ACTUAL']."',
												  		 '".$cod_documento."',
														 '".$fcon['Cod_TipoDocumento']."',
														 '".$secuencia."',
														 '".$periodos."',
														 '".$valorSecuencia."',
														 '".$fcon['CodDependenciaInterna']."',
   														 '".$fcon['Cod_DependenciaExterna']."',
														 '".$fcon['FechaRegistro']."',
														 '".$fcon['Cod_Organismos']."',
														 '".$fcon['Destinatario']."',
														 '".$fcon['CargoDestinatario']."',
														 '".$fcon['Remitente']."',
														 '".$fcon['CargoRemitente']."',
														 '".$fcon['Asunto']."',
														 '".$fcon['Descripcion']."',
														 '".$fcon['FechaDocumento']."',
														 '".$fcon['Contenido']."',
														 '".$fcon['Cod_PersonaResp']."',
														 '".$fcon['Cod_CargoResp']."',
														 '".$fcon['FechaEnvio']."',
														 '".$fcon['Estado']."',
														 '".$_SESSION['USUARIO_ACTUAL']."',
														 '".date("Y-m-d H:i:s")."')";
	  $qhist= mysql_query($shist) or die ($shist.mysql_error());
   
    }
	}
   $sa = "update cp_documentoextsalida set Estado = 'EV',
   										   FechaEnvio = '".date("Y-m-d")."',
										   UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										   UltimaFechaModif = '".date("Y-m-d H:i:s")."'
									  where 
										   CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and
										   Periodo = '".$periodo."' and
										   Cod_DocumentoCompleto = '".$_POST['ndoc_completo']."'";
  $qa=mysql_query($sa) or die ($sa.mysql_error());											
}
///// ----------------------------------------------------------------------------------- 
/////  GUARDAR ENVIO DOCUMENTO INTERNO
///// -----------------------------------------------------------------------------------
if($accion=="guardarEnvioInterno"){
connect();
   
   $lineas = preg_split("/[;]/", $detalles);
	foreach ($lineas as $linea) {
		@list($cod_documento, $periodos, $secuencia)=preg_split( '/[|]/', $linea);
		//echo"$cod_documento, $periodos, $secuencia";		      
   
       $s="update cp_documentodistribucion set Cod_PersonaResp = '".$codempleado."' ,
													    Cod_CargoResp = '".$cod_cargoremit."',
													    FechaEnvio = '".date("Y-m-d")."',
													    Estado = 'EV',
													    UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
													    UltimaFechaModif = '".date("Y-m-d H:i:s")."'
											        where
													    Cod_Organismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and
													    Periodo = '".$periodos."' and
														CodPersona = '".$secuencia."' and
														Cod_Documento = '".$cod_documento."' and 
														Cod_TipoDocumento = '".$cod_tipodocumento."'";
       $q=mysql_query($s) or die ($s.mysql_error());
	   
	   //// --------------------------------------------------------------------------------------------------
	   $sa="update cp_documentointerno set
										 Estado = 'EV',
										 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										 UltimaFechaModif = '".date("Y-m-d H:i:s")."'
									where
										 CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and
										 Periodo = '".$periodos."' and
										 Cod_DocumentoCompleto = '".$cod_documento."' and 
										 Cod_TipoDocumento = '".$cod_tipodocumento."'";
       $qa=mysql_query($sa) or die ($sa.mysql_error());
	   
   }										
}
//// ------------------------------------------------------------------------------------
//// OPERACION IMPRIMIR GMCORRESPONDENCIA.PHP
//// ------------------------------------------------------------------------------------
if ($accion == "consultarPersonasDocInterno") {
	connect();
	
	list($cod_tipodocumento, $cod_documentocompleto)=SPLIT( '[|]', $_POST['codigo']);
	
	$sql = "SELECT *
			FROM cp_documentodistribucion
			WHERE Cod_Documento = '$cod_documentocompleto' AND 
			      Cod_TipoDocumento = '$cod_tipodocumento'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	echo mysql_num_rows($query);
}
//// -----------------------------------------------------------------------------------
//// PERMITE REALIZAR CAMBIOS VIA MODIFICACION CODIGO
if ($accion == "activarCambio") {
	connect();
	
	$sql = "SELECT *
			FROM cp_documentoacuserecibo
			WHERE CodOrganismo = '0001' AND 
			      Periodo = '2011' and 
				  FechaAcuse = '0000-00-00'";
	$qry = mysql_query($sql) or die ($sql.mysql_error());
	$row = mysql_num_rows($qry);
	if($row!=0){
	  for($i=0;$i<$row;$i++){
	     $field = mysql_fetch_array($qry);
	     list($a,$b) = SPLIT('[ ]',$field['UltimaFechaModif']); 
		 //echo $a.'*'.$b;
		 $s_update = "update 
		                    cp_documentoacuserecibo 
						set 
						    FechaAcuse='$a' 
					  where 
					        CodOrganismo='0001' and 
							Periodo='2011' and 
							Cod_Documento='".$field['Cod_Documento']."' and 
							Cod_TipoDocumento='".$field['Cod_TipoDocumento']."'";
	    
		$q_update = mysql_query($s_update) or die ($s_update.mysql_error());
	  }	
	}
}

?>