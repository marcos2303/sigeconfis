<? 
include("fphp.php"); 
$year_completa= date("Y-m-d H:i:s");
//// _____________________________________________________________
////               GUARDAR CATEGORIAS DEPRECIACION            ////
//// _____________________________________________________________
/// FUNCION PARA INSERTAR LINEAS EN CATEGORIAS NUEVA 
if ($accion == "insertarLineaCatNueva") {
connect();	
    $sa = "select * from ac_contabilidades";
    $qa = mysql_query($sa) or die ($sa.mysql_error());
    $ra = mysql_num_rows($qa);
    ?>
	<td>
		<select name="select1" style="width:100%;">
        <?
         if($ra!=0){
           for($i=0;$i<$ra;$i++){
            $fa = mysql_fetch_array($qa); 
        ?>
			<option value="<?=$fa['CodContabilidad']?>"><?=$fa['Descripcion']?></option>
        <? }}?>  
		</select>	
	</td>
	<td>
		<input type="text" name="descripcion" style="width:100%;"/>
	</td>
	<?
}
//// _____________________________________________________________
  //                  GUARDAR NUEVA CATEGORIA                   //
//// _____________________________________________________________
if($accion=="guardarNuevaCategoria"){
 connect();
 $sql="SELECT * FROM af_categoriadeprec WHERE CodCategoria='".$_POST['codcategoria']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $row=mysql_num_rows($qry);
 if($row!=0){
    echo"¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡";
 }else{
  $insert="INSERT INTO af_categoriadeprec (CodCategoria,
										  DescripcionLocal,
										  CuentaHistorica,
										  CuentaHistoricaVariacion,
										  CuentaHistoricaRevaluacion,
										  CuentaDepreciacion,
										  CuentaDepreciacionVariacion,
										  CuentaDepreciacionRevaluacion,
										  CuentaGastos,
										  CuentaGastosRevaluacion,
										  CuentaNeto,
										  CuentaREI,
										  CuentaResultado,
										  InventariableFlag,
										  GrupoCateg,
										  TipoDepreciacion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif) 
								 VALUES ('".$_POST['codcategoria']."',
										 '".$_POST['descp_local']."',
										 '".$_POST['v_historico']."',
										 '".$_POST['cc_adiciones']."',
										 '".$_POST['cc_ajinflacion']."',
										 '".$_POST['cd_pdepreciacion']."',
										 '".$_POST['cd_adiciones']."',
										 '".$_POST['cd_ajinflacion']."',
										 '".$_POST['cg_depreciacion']."',
										 '".$_POST['cg_ajinflacion']."',
										 '".$_POST['occ_valorneto']."',
										 '".$_POST['occ_rei']."',
										 '".$_POST['occ_ctaresultado']."',
										 '".$_POST['cat_invent']."',
										 '".$_POST['g_categoria']."',
										 '".$_POST['t_depreciacion']."',
								        '".$_POST['radioEstado']."',
										'".$_SESSION['USUARIO_ACTUAL']."',
										'".date("Y-m-d H:i:s")."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
   
   //// _______________________________________________
  ////       GUARDAR CATEGORIA CONTABILIDAD       ////
  //// _______________________________________________
  
  $linea = split(";", $detalles);
	  foreach ($linea as $registro) {
		list($codContabilidad, $depreciacion) = SPLIT( '[|]', $registro);
		
		$sqlin="INSERT INTO af_categoriacontabilidad(CodCategoria,
													CodContabilidad,
													DepreciacionPorcentaje) 
											 VALUES ('".$_POST['codcategoria']."',
													'$codContabilidad',
													'$depreciacion')";
       $qryin=mysql_query($sqlin) or die ($sqlin.mysql_error());
	  }
 }
 //echo "";
}
//// _____________________________________________________________
////               EDITAR CATEGORIAS DEPRECIACION             ////
//// _____________________________________________________________
if($accion=="editarCategoria"){
 connect();	
 $sql="UPDATE af_categoriadeprec SET DescripcionLocal='".$_POST['descp_local']."',
									  CuentaHistorica= '".$_POST['v_historico']."',
									  CuentaHistoricaVariacion='".$_POST['cc_adiciones']."',
									  CuentaHistoricaRevaluacion='".$_POST['cc_ajinflacion']."',
									  CuentaDepreciacion='".$_POST['cd_pdepreciacion']."',
									  CuentaDepreciacionVariacion='".$_POST['cd_adiciones']."',
									  CuentaDepreciacionRevaluacion='".$_POST['cd_ajinflacion']."',
									  CuentaGastos='".$_POST['cg_depreciacion']."',
									  CuentaGastosRevaluacion='".$_POST['cg_ajinflacion']."',
									  CuentaNeto='".$_POST['occ_valorneto']."',
									  CuentaREI= '".$_POST['occ_rei']."',
									  CuentaResultado='".$_POST['occ_ctaresultado']."',
									  InventariableFlag='".$_POST['cat_invent']."',
									  GrupoCateg='".$_POST['g_categoria']."',
									  TipoDepreciacion='".$_POST['t_depreciacion']."',
									  Estado='".$_POST['radioEstado']."',
									  UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
									  UltimaFechaModif='".date("Y-m-d H:i:s")."'
							   WHERE  
							          CodCategoria='".$_POST['codcategoria']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 
  //// ____________________________________________
  ////      EDITAR CATEGORIA CONTABILIDAD      ////
  //// ____________________________________________
 	  $s_delete = "delete from  af_categoriacontabilidad where CodCategoria='".$_POST['codcategoria']."'";
	  $q_delete = mysql_query($s_delete) or die ($s_delete.mysql_error());
	  
	  $linea = split(";", $detalles);
	  foreach ($linea as $registro) {
		list($codContabilidad, $depreciacion) = SPLIT( '[|]', $registro);
		
		$sqlin="INSERT INTO af_categoriacontabilidad(CodCategoria,
													CodContabilidad,
													DepreciacionPorcentaje) 
											 VALUES ('".$_POST['codcategoria']."',
													'$codContabilidad',
													'$depreciacion')";
       $qryin=mysql_query($sqlin) or die ($sqlin.mysql_error());
	  }
	  
}
//// _____________________________________________________________
////               GUARDAR LIBRO CONTABLE                     ////
//// _____________________________________________________________
if($accion==guardarLibroContable){
  connect();
  $sql="SELECT * FROM ac_librocontable 
                WHERE CodLibroCont='".$_POST['cod_librocontable']."' OR
				      Descripcion='".$_POST['descp_libro']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry);
  if($row==0){
   $insert="INSERT INTO ac_librocontable (CodLibroCont,
                                          Descripcion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif) 
								  VALUES ('".$_POST['cod_librocontable']."',
								         '".$_POST['descp_libro']."',
										 '".$_POST['radioEstado']."',
										 '".$_SESSION['USUARIO_ACTUAL']."',
										 '".date("Y-m-d H:i:s")."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
  }else{
   //echo"<script>";
   echo"¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡";
   /*echo"</script>";*/
  }
}
//// _____________________________________________________________
////               EDITAR LIBRO CONTABLE                      ////
//// _____________________________________________________________
if($accion==editarLibroContable){
 connect();
 $supdate="UPDATE ac_librocontable SET   Descripcion='".$_POST['descp_libro']."',
										 Estado='".$_POST['radioEstado']."',
										 UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
										 UltimaFechaModif='".date("Y-m-d H:i:s")."' 
								 WHERE  
								         CodLibroCont='".$_POST['registro']."'";
  $qupdate=mysql_query($supdate) or die ($supdate.mysql_error());
}
//// _____________________________________________________________
////               GUARDAR CONTABILIDADES                     ////
//// _____________________________________________________________
if($accion==guardarContabilidades){
  connect();	
  $sql="SELECT * FROM 
                     ac_contabilidades 
                WHERE 
				     CodContabilidad='".$_POST['cod_contabilidad']."' OR
				     Descripcion='".$_POST['descp_contabilidad']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry);
  if($row==0){
   $insert="INSERT INTO ac_contabilidades(CodContabilidad,
                                         Descripcion,
										 Estado,
										 UltimoUsuario,
										 UltimaFechaModif) 
								 VALUES ('".$_POST['cod_contabilidad']."',
								        '".$_POST['descp_contabilidad']."',
										'".$_POST['radioEstado']."',
										'".$_SESSION['USUARIO_ACTUAL']."',
										'".date("Y-m-d H:i:s")."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
  }else{
   echo"¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡";
   die;
  }
  
  $linea = split(";", $detalles);
  foreach($linea as $registro){
	  //$cont++; echo "cont=".$cont;
	  list($l_contable, $codigo) = SPLIT( '[|]', $registro);
      
	  $s_insert = "insert into ac_librocontabilidades (CodContabilidad,
	  												   CodLibroCont) 
												values('".$_POST['cod_contabilidad']."',
												       '$l_contable')";
	  $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
  }
}
//// _____________________________________________________________
////               EDITAR CONTABILIDADES                     ////
if($accion==editarContabilidades){
  connect();	
   /// Actualización de tabla AC_CONTABILIDADES
   $s_update = "update ac_contabilidades set Descripcion = '".$descp_contabilidad."',
		                                          Estado = '".$radioEstado."',
												  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												  UltimaFechaModif = '".date("Y-m-d H:i:s")."'
											 where
											      CodContabilidad = '".$cod_contabilidad."' ";
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error());

  /// Actualizo tabla AC_LIBROCONTABILIDADES
   $s_delete = "delete from  ac_librocontabilidades where CodContabilidad='".$cod_contabilidad."'";
   $q_delete = mysql_query($s_delete) or die ($s_delete.mysql_error());
  
  
  $linea = split(";", $detalles);
  foreach($linea as $registro){
	  //$cont++; echo "cont=".$cont;
	  list($l_contable, $codigo) = SPLIT( '[|]', $registro);
	  
	    $s_insert = "insert into ac_librocontabilidades (CodContabilidad,
	  												   CodLibroCont) 
												values('".$_POST['cod_contabilidad']."',
												       '$l_contable')";
 	    $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
  }
}
//// _____________________________________________________________
////               GUARDAR SITUACION DEL ACTIVO               ////
//// _____________________________________________________________
if($accion==guardarSituactivo){
  connect();  
  $sql="SELECT * 
          FROM 
               af_situacionactivo 
         WHERE 
               CodSituActivo='".$_POST['cod_situactivo']."' AND
               Descripcion='".$_POST['descp_situactivo']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry);
  if($row==0){
   $insert="INSERT INTO af_situacionactivo(CodSituActivo,
                                         Descripcion,
										 DepreciacionFlag,
										 RevaluacionFlag,
										 Estado,
                                         UltimoUsuario,
                                         UltimaFechaModif) 
								 VALUES ('".$_POST['cod_situactivo']."',
								        '".$_POST['descp_situactivo']."',
										'".$_POST['proceso_situactivo']."',
										'".$_POST['proceso_ajuste']."',
										'".$_POST['status_situactivo']."',
                                        '".$_SESSION['USUARIO_ACTUAL']."',
                                        '".date("Y-m-d H:i:s")."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
  }else{
   echo"<script>";
   echo"alert('¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡')";
   echo"</script>";
  }
}
//// _____________________________________________________________
////               EDITAR SITUACION DEL ACTIVO                ////
//// _____________________________________________________________
if($accion==editarSituactivo){
    connect(); 
   $supdate="UPDATE af_situacionactivo SET Descripcion='".$_POST['descp_situactivo']."',
											 DepreciacionFlag='".$_POST['proceso_situactivo']."',
											 RevaluacionFlag='".$_POST['proceso_ajuste']."',
											 Estado='".$_POST['status_situactivo']."',
                                             UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
                                             UltimaFechaModif='".date("Y-m-d H:i:s")."' 
									   WHERE 
                                             CodSituActivo='".$_POST['cod_situactivo']."' ";
   $qupdate=mysql_query($supdate) or die ($supdate.mysql_error());
}
//// _____________________________________________________________
////               GUARDAR TIPO SEGURO                        ////
//// _____________________________________________________________
if($accion==guardarTseguro){
  $sql="SELECT * FROM af_tiposeguro 
                WHERE CodTiposeguro='".$_POST['cod_tseguro']."' AND
				      Descripcion='".$_POST['descp_tseguro']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry);
  if($row==0){
   $insert="INSERT INTO af_tiposeguro(CodTipoSeguro,
									 Descripcion,
									 Estado) 
							  VALUES ('".$_POST['cod_tseguro']."',
									'".$_POST['descp_tseguro']."',
									'".$_POST['status_tseguro']."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
  }else{
   echo"<script>";
   echo"alert('¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡')";
   echo"</script>";
}
}
//// _____________________________________________________________
////               EDITAR TIPO SEGURO                        ////
//// _____________________________________________________________
if($accion==editarTseguro){
 $supdate="UPDATE af_tiposeguro SET  
                                   Descripcion='".$_POST['descp_tseguro']."',
								   Estado='".$_POST['status_tseguro']."',
								   UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
								   UltimaFechaModif='$year_completa' 
						     WHERE  
							       CodTipoSeguro='".$_POST['registro']."'";
 $qupdate=mysql_query($supdate) or die ($supdate.mysql_error());
}
//// _____________________________________________________________
////               GUARDAR TIPO VEHICULOS                     ////
//// _____________________________________________________________
if($accion==guardarTvehiculo){
  $sql="SELECT * FROM af_tipovehiculo 
                WHERE CodTipoVehiculo='".$_POST['cod_tvehiculo']."' AND
				      Descripcion='".$_POST['descp_tvehiculo']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry);
  if($row==0){
   $insert="INSERT INTO af_tipovehiculo(CodTipoVehiculo,
									   Descripcion,
									   Estado) 
							   VALUES ('".$_POST['cod_tvehiculo']."',
									  '".$_POST['descp_tvehiculo']."',
									  '".$_POST['status_tvehiculo']."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
  }else{
   echo"<script>";
   echo"alert('¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡')";
   echo"</script>";
}
}
//// _____________________________________________________________
////               EDITAR TIPO SEGURO                        ////
//// _____________________________________________________________
if($accion==editarTvehiculo){
 $supdate="UPDATE af_tipovehiculo SET  
                                   Descripcion='".$_POST['descp_tvehiculo']."',
								   Estado='".$_POST['status_tvehiculo']."',
								   UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
								   UltimaFechaModif='$year_completa' 
						     WHERE  
							       CodTipoVehiculo='".$_POST['registro']."'";
 $qupdate=mysql_query($supdate) or die ($supdate.mysql_error());
}
//// _____________________________________________________________
////               GUARDAR POLIZA DE SEGURO                   ////
//// _____________________________________________________________
if($accion==guardarPseguro){
  $sql="SELECT * FROM af_polizaseguro 
                WHERE CodPolizaSeguro='".$_POST['cod_pseguro']."' AND 
				      DescripcionLocal='".$_POST['descp_pseguro']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry); 
  if($row==0){
    $sin="INSERT INTO af_polizaseguro (CodPolizaSeguro,
	                                   DescripcionLocal,
									   EmpresaAseguradora,
									   MontoCobertura,
									   AgenteSeguros,
									   FechaVencimiento,
									   costoPoliza,
									   Estado) 
							   VALUES  ('".$_POST['cod_pseguro']."',
							            '".$_POST['descp_pseguro']."',
										'".$_POST['empa_pseguro']."',
										'".$_POST['ages_pseguro']."',
										'".$_POST['fvenc_pseguro']."',
										'".$_POST['mcober_pseguro']."',
										'".$_POST['cpoli_pseguro']."',
										'".$_POST['status_tvehiculo']."')";
	$qin=mysql_query($sin) or die ($sin.mysql_error());echo $sin;
  }
  
}
//// _____________________________________________________________________
////               GUARDAR CATASTRO                           ////
//// _____________________________________________________________________
//// _____________________________________________________________________
        /// MAESTRO DE CATASTRO INSERTANDO FILAS 
//// ---------------------------------------------------------------------
if ($accion == "insertarLinea") {
	?>
	<td><input type="hidden" name="id" id="id">
        <input type="text" name="ano" id="ano" style="width:100%; text-align:center"></td>
    <td><input type="text" name="precio_Oficial" id="precio_Oficial" style="width:100%; text-align:right"></td>
    <td><input type="text" name="precio_Mercado" id="precio_Mercado" style="width:100%; text-align:right"></td>
    <td><input type="text" name="fecha_Referencial" id="fecha_Referencial" style="width:100%; text-align:center"></td>
	<?
}
//// ---------------------------------------------------------------------
//// GUARDAR REGISTRO DE CATASTRO
if ($accion==guardarCatastro) {
	connect();
	/// ------ Consulta para generar código de catastro
	$scon = "select max(CodCatastro) from af_catastro";
	$qcon = mysql_query($scon) or die ($scon.mysql_error());
	$rcon = mysql_num_rows($qcon); //echo $rcon;																						
	
	if($rcon!=0){
	   $fcon = mysql_fetch_array($qcon);
	   //$contador = $contador + 1; echo $contador;
	   $cod_catastro = (int) ($fcon[0]+1);
	   $cod_catastro = (string) str_repeat("0",8-strlen($cod_catastro)).$cod_catastro;
	   
	   $sql = "INSERT INTO af_catastro (CodCatastro, 
	                                    Descripcion, 
									    Estado,
										UltimoUsuario,
										UltimaFechaModif) 
							   VALUES ('$cod_catastro',
							           '$descp_catastro',
								 	   '$radioEstado',
									   '".$_SESSION['USUARIO_ACTUAL']."',
									   NOW())";
      $query_insert = mysql_query($sql) or die ($sql.mysql_error());		
	  
	  
	  //	detalles
	  $linea = split(";", $detalles);
	  foreach ($linea as $registro) {
		list($ano, $precio_Oficial, $precio_Mercado, $fecha_Referencial) = SPLIT( '[|]', $registro);
		
		$s_catanual = "select max(IdCatastroAnual) from af_catastroanual";
		$q_catanual = mysql_query($s_catanual) or die ($s_catanual.mysql_error());
		$f_catanual = mysql_fetch_array($q_catanual);
		
		$id_catastroanual = (int) ($f_catanual['0']+1);
		$id_catastroanual = (string) str_repeat("0",5-strlen($id_catastroanual)).$id_catastroanual;
		
		list($d, $m, $a)=SPLIT( '[-]', $fecha_Referencial); $f_referencial=$a.'-'.$m.'-'.$d;
				
		$s_insert = "INSERT INTO af_catastroanual (IdCatastroAnual,
												  CodCatastro, 
												  Ano, 
												  PrecioOficial,
												  PrecioMercado,
												  FechaReferencia,
												  UltimoUsuario,
												  UltimaFechaModif) 
										  VALUES ('$id_catastroanual',
												  '$cod_catastro',
												  '$ano', 
												  '$precio_Oficial', 
												  '$precio_Mercado',
												  '$f_referencial',
												  '".$_SESSION['USUARIO_ACTUAL']."',
												  NOW())";
	   $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
	}
  }
}
//// EDITAR CATASTRO
if ($accion==guardarCatastroEditar){
  connect();
  
  $supdate = "update af_catastro set Descripcion ='".$_POST['descp_catastro']."' ,
                                     Estado = '".$_POST['radioEstado']."',
									 UltimaFechaModif = '".date("Y-m-d H:i:s")."', 
									 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."'
								where 
								     CodCatastro = '".$_POST['cod_catastro']."'";
  $qupdate = mysql_query($supdate) or die ($supdate.mysql_error());
  
   $linea = split(";", $detalles);
	  foreach ($linea as $registro) {
		list($id, $ano, $precio_Oficial, $precio_Mercado, $fecha_Referencial) = SPLIT( '[|]', $registro);
		
		$s_conexiste = "select * from af_catastroanual where IdCatastroanual ='$id' and CodCatastro = '".$_POST['cod_catastro']."' ";
		$q_conexiste = mysql_query($s_conexiste) or die ($s_conexiste.mysql_error());
		$r_conexiste = mysql_fetch_array($q_conexiste);
		
		
		/// ------------------ 
		if($r_conexiste!=0){
		  $f_conexiste = mysql_fetch_array($q_conexiste);
		  if(($f_conexiste['Ano']!=$ano)or($f_conexiste['PrecioOficial']!=$precio_Oficial)or($f_conexiste['PrecioMercado']!=$precio_Mercado)or($f_conexiste['FechaReferencia']!=$fecha_Referencial)){
		   
		     list($d, $m, $a)=SPLIT( '[-]', $fecha_Referencial); $f_referencia=$a.'-'.$m.'-'.$d;
			 
		     $s_update = "update af_catastroanual set Ano ='$ano',
			                                         PrecioOficial = '$precio_Oficial',
													 PrecioMercado = '$precio_Mercado',
													 FechaReferencia = '$f_referencia',
													 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
													 UltimaFechaModif = '".date("Y-m-d H:i:s")."'
												 where
												     CodCatastro = '".$_POST['cod_catastro']."' and 
													 IdCatastroanual = '$id'";
			 $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
		  }
		}else{
		  		
		$s_catanual = "select max(IdCatastroAnual) from af_catastroanual";
		$q_catanual = mysql_query($s_catanual) or die ($s_catanual.mysql_error());
		$f_catanual = mysql_fetch_array($q_catanual);
		
		$id_catastroanual = (int) ($f_catanual['0']+1);
		$id_catastroanual = (string) str_repeat("0",5-strlen($id_catastroanual)).$id_catastroanual;
		
		list($d, $m, $a)=SPLIT( '[-]', $fecha_Referencial); $f_referencia=$a.'-'.$m.'-'.$d;
				
		$s_insert = "INSERT INTO af_catastroanual (IdCatastroAnual,
												  CodCatastro, 
												  Ano, 
												  PrecioOficial,
												  PrecioMercado,
												  FechaReferencia,
												  UltimoUsuario,
												  UltimaFechaModif) 
										  VALUES ('$id_catastroanual',
												  '$cod_catastro',
												  '$ano', 
												  '$precio_Oficial', 
												  '$precio_Mercado',
												  '$f_referencia',
												  '".$_SESSION['USUARIO_ACTUAL']."',
												  NOW())";
	   $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
	  }
	}
}
//// _____________________________________________________________________
//// CARGAR SELECT PARA DEPENDENCIAS EN TRANSFERIRDATOSGENERALES.PHP
//// _____________________________________________________________________
if($_POST['accion']=="obtenerDep") {
	if($_POST['tabla']=="dependencia") {
	  echo"
	   <select name='dependencia' id='dependencia' class='selectBig'>
			<option value=''>";
				getDependenciaTransferir("", $_POST['opcion']);
		echo "</select>";
	}
}
//// _____________________________________________________________________
//// 					CARGAR CAMPO CATEGORIA
//// _____________________________________________________________________
if($accion==cargarCampoCategoria){
  $s_categoria = "select * from af_categoriadeprec where CodCategoria = '".$valorEnviado."'";
  $q_categoria = mysql_query($s_categoria) or die ($s_categoria.mysql_error());
  $r_categoria = mysql_num_rows($q_categoria);
  
  if($r_categoria!=0){ $f_categoria = mysql_fetch_array($q_categoria); echo"<input type='text' id ";}
  
}
//// ---------------------------------------------------------------------
if($accion==EliminarCatastroEditado){
    connect();
   $s_delete = "delete from af_catastroanual where  IdCatastroanual  = '".$id_catanual."' and CodCatastro = '".$cod_catastro."'";
   $q_delete = mysql_query($s_delete) or die ($s_delete.mysql_error());
   
}
//// _____________________________________________________________________
//// 						GUARDAR TIPO SEGURO
//// _____________________________________________________________________
if($accion==guardarTipoSeguro){
   connect();	
   $sql = "insert into af_tiposeguro(CodTipoSeguro,
									 Descripcion,
									 Estado,
									 UltimoUsuario,
									 UltimaFechaModif)
								values('".$_POST['cod_tseguro']."',
									  '".$_POST['descp_tseguro']."',
									  '".$_POST['radioEstado']."',
									  '".$_SESSION['USUARIO_ACTUAL']."',
									  '".date("Y-m-d H:i:s")."')";
  $qry = mysql_query($sql) or die ($sql.mysql_error());
}
//// _____________________________________________________________________
//// 					ELIMINAR TIPO SEGURO
//// _____________________________________________________________________
if($accion==ELIMINARTIPOSEGUROS){
  connect();	
  $s_delete = "delete from af_tiposeguro where CodTipoSeguro='".$codigo."'";
  $q_delete = mysql_query($s_delete) or die ($s_delete.mysql_error());	
}
//// _____________________________________________________________________
////                    EDITAR TIPO SEGURO
//// _____________________________________________________________________
if($accion=='EditarTipoSeguro'){
  connect();
  $s_update = "update af_tiposeguro set Descripcion = '".$descp_tseguro."',
                                        Estado = '".$radioEstado."',
										UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										UltimaFechaModif = '".date("Y-m-d H:i:s")."'
								  where
								        CodTipoSeguro = '".$cod_tseguro."'";
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
}
//// _____________________________________________________________________
////                           GUARDAR TIPO VEHICULO
//// _____________________________________________________________________
if($accion=='guardarTipoVehiculo'){
  connect();
  $s_insert = "insert into af_tipovehiculo(CodTipoVehiculo,
                                               Descripcion,
											   Estado,
											   UltimoUsuario,
											   UltimaFechaModif) 
									     values('".$cod_tvehiculo."',
										        '".$descp_tvehiculo."',
												'".$radioEstado."',
												'".$_SESSION['USUARIO_ACTUAL']."',
												'".date("Y-m-d H:i:s")."')";
 $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
}
//// _____________________________________________________________________
////                          EDITAR TIPO VEHICULO
//// _____________________________________________________________________
if($accion=='EditarTipoVehiculo'){
  connect();
  $s_update = "update af_tipovehiculo set Descripcion = '".$descp_tvehiculo."',
                                        Estado = '".$radioEstado."',
										UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										UltimaFechaModif = '".date("Y-m-d H:i:s")."'
								  where 
								        CodTipoVehiculo = '".$cod_tvehiculo."'";
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error()); 
}
//// _____________________________________________________________________
////                           GUARDAR POLIZA SEGUROS
//// _____________________________________________________________________
if($accion=='guardarPolizaSeguro'){
  connect();
   list($d,$m,$a,$h,$i,$s) = SPLIT('[-: ]', $_POST['fvenc_pseguro']);
  $fecha_vencimiento = $a.'-'.$m.'-'.$d.' '.$h.':'.$i.':'.$s;
  
  
  $s_insert = "insert into af_polizaseguro(CodPolizaSeguro,
  								           DescripcionLocal,
										   EmpresaAseguradora,
										   MontoCobertura,
										   AgenteSeguros,
										   FechaVencimiento,
										   CostoPoliza,
										   Estado,
										   UltimoUsuario,
										   UltimaFechaModif)
									 values('".$cod_pseguro."',
									        '".$descp_pseguro."',
											'".$empa_pseguro."',
											'".$mcober_pseguro."',
											'".$ages_pseguro."',
											'".$fecha_vencimiento."',
											'".$cpoli_pseguro."',
											'".$radioEstado."',
											'".$_SESSION['USUARIO_ACTUAL']."',
											'".date("Y-m-d H:i:s")."')";	
  $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());

}
//// -------------------------------------------------------------------------------------------
////                                ELIMINAR POLIZA SEGUROS
//// -------------------------------------------------------------------------------------------
if($accion=='ELIMINARPOLIZASEGUROS'){
  connect();	
  $s_delete = "delete from af_polizaseguro where CodPolizaSeguro='".$codigo."'";
  $q_delete = mysql_query($s_delete) or die ($s_delete.mysql_query());	
}
//// _____________________________________________________________________
////                          EDITAR POLIZA SEGUROS
//// _____________________________________________________________________
if($accion=='EditarPolizaSeguros'){
  connect();	
  list($d,$m,$a,$h,$i,$s) = SPLIT('[-: ]', $_POST['fvenc_pseguro']);
  $fecha_vencimiento = $a.'-'.$m.'-'.$d.' '.$h.':'.$i.':'.$s;
  
  $s_update = "update af_polizaseguro set DescripcionLocal = '".$descp_pseguro."',
										  EmpresaAseguradora = '".$empa_pseguro."',
										  MontoCobertura = '".$mcober_pseguro."',
										  AgenteSeguros = '".$ages_pseguro."',
										  FechaVencimiento = '".$fecha_vencimiento."',
										  CostoPoliza = '".$cpoli_pseguro."',
										  Estado = '".$radioEstado."',
										  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										  UltimaFechaModif = '".date("Y-m-d H:i:s")."'
									 where
									      CodPolizaSeguro = '".$cod_pseguro."'";	
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
}
//// _____________________________________________________________________
////                      INSERTAR LINEAS EN CONTABILIDADES
//// _____________________________________________________________________
if ($accion=="insertarLineaContabilidad") {
	connect();
	?>
	<td>
		<input type="text" name="codigo" id="codigo_<?=$_POST['candetalle']?>"  style="width:100%;" readonly="readonly"/>
	</td>
	<td>
        <?
        $s_con = "select * from ac_librocontable";
		$q_con = mysql_query($s_con) or die ($s_con.mysql_error());
		$r_con = mysql_num_rows($q_con);
		?>
		<select name="l_contable" id="l_contable_<?=$_POST['candetalle']?>" style="width:100%;" onchange="cargarCodigo(this.id, <?=$_POST['candetalle']?>);" >
           <option value=""></option>
			<?
             if($r_con!=0){
				while ($f_con = mysql_fetch_array($q_con)){
			?>
			<option value="<?=$f_con['CodLibroCont'];?>"><?=$f_con['Descripcion'];?></option>
			<?
			 } }
			?>
		</select>	
	</td>
	<?
}
//// _____________________________________________________________________
////                             ELIMINAR  CONTABILIDADES
//// _____________________________________________________________________
if($accion =='EliminarEditarContabilidades'){
  echo "CODIGO=".$_POST['codigo'];	
  echo "ID=".$_POST['id_contabilidades'];
  list($A,$B,$C)= SPLIT('[_]',$_POST['id_contabilidades']);	
  echo $A,$B,$C;
  connect();	
  $s_delete = "delete from ac_librocontabilidades where CodContabilidad='$A' and CodLibroCont='$B'";
  $q_delete = mysql_query($s_delete) or die ($s_delete.mysql_query());	
   	
}
//// -------------------------------------------------------------------------------------------
////                   GUARDAR ACTIVO FIJO TRASNFERIDOS DESDE LOGISTICA
//// -------------------------------------------------------------------------------------------
if($accion == 'GuardarActivosTransferidos'){
connect();
   /// Consultar activo para hallar el máximo
   $scon = "select max(Activo) from af_activo where CodOrganismo = '".$CodOrganismo."'";
   $qcon = mysql_query($scon) or die ($scon.mysql_error());
   $fcon = mysql_fetch_array($qcon);
   
   $activo = (int) ($fcon[0]+1);
   $activo = (string) str_repeat("0",10-strlen($activo)).$activo;
   
   if($_POST['FechaIngreso']!='00-00-0000')$FechaIngreso = date("Y-m-d", strtotime($_POST['FechaIngreso']));
   if($_POST['NumeroOrdenFecha']!='00-00-0000')$NumeroOrdenFecha = date("Y-m-d", strtotime($_POST['NumeroOrdenFecha']));
   if($_POST['NumeroGuiaFecha']!='00-00-0000')$NumeroGuiaFecha = date("Y-m-d", strtotime($_POST['NumeroGuiaFecha'])); 
   if($_POST['DocAlmacenFecha']!='00-00-0000')$DocAlmacenFecha = date("Y-m-d", strtotime($_POST['DocAlmacenFecha'])); 
   if($_POST['InventarioFisicoFecha']!='00-00-0000')$InventarioFisicoFecha = date("Y-m-d", strtotime($_POST['InventarioFisicoFecha'])); 
   if($_POST['FacturaFecha']!='00-00-0000')$FacturaFecha = date("Y-m-d", strtotime($_POST['FacturaFecha']));
   
   
    
   $MontoCatastro = cambioFormato($_POST['MontoCatastro']);	
   $MontoLocal = cambioFormato($_POST['MontoLocal']);
   $MontoReferencia = cambioFormato($_POST['MontoReferencia']);
   $MontoMercado = cambioFormato($_POST['MontoMercado']);
    
   $s_insert ="insert into af_activo(Activo, CodOrganismo, CodDependencia, Descripcion, TipoActivo,
									 EstadoConserv, CodigoBarras, CodigoInterno, TipoSeguro, TipoVehiculo,
									 Categoria, Clasificacion,  ClasificacionPublic20, Ubicacion, TipoMejora,
									 ActivoConsolidado, EmpleadoUsuario, EmpleadoResponsable, CentroCosto, Marca,
									 Modelo, NumeroSerie, NumeroSerieMotor, NumeroPlaca, MarcaMotor, NumeroAsiento,
									 Material, Dimensiones, NumerodeParte, Color, FabricacionPais,  FabricacionAno,
									 PolizaSeguro, NumeroUnidades, CodigoCatastro,  AreaFisicaCatastro, MontoCatastro,
									 GenerarVoucherIngresoFlag, CodProveedor, FacturaTipoDocumento, FacturaNumeroDocumento,
									 FacturaFecha, NumeroOrden, NumeroOrdenFecha, NumeroGuia, NumeroGuiaFecha,
									 NumeroDocAlmacen, DocAlmacenFecha, InventarioFisicoFecha, InventarioFisicoComentario, FechaIngreso,
									 PeriodoIngreso, PeriodoInicioDepreciacion, PeriodoInicioRevaluacion, PeriodoBajaOnco, VoucherBajaOnco,
									 MontoLocal, MontoReferencia, MontoMercado, VoucherIngreso, Estado, UltimoUsuario,
									 UltimaFechaModif, SituacionActivo, FlagParaMantenimiento, FlagParaOperaciones, OrigenActivo,
									 UnidadMedida, DepreEspecificaFlag, PreparadoPor, FechaPreparacion, Naturaleza,
									 CodTipoMovimiento, EstadoRegistro, PeriodoVoucher, Anio, NroOrdenInterno)
                               values
                                    ('$activo', '".$CodOrganismo."', '".$CodDependencia."', '".$Descripcion."', '".$TipoActivo."',
									'".$EstadoConserv."', '".$CodigoBarras."', '".$CodigoInterno."', '".$TipoSeguro."', '".$TipoVehiculo."',
									'".$Categoria."', '".$Clasificacion."', '".$ClasificacionPublic20."', '".$Ubicacion."', '".$TipoMejora."',
									'".$ActivoConsolidado."', '".$EmpleadoUsuario."', '".$EmpleadoResponsable."', '".$CentroCosto."', '".$Marca."',
									'".$Modelo."', '".$NumeroSerie."', '".$NumeroSerieMotor."', '".$NumeroPlaca."', '".$MarcaMotor."','".$NumeroAsiento."', 
									'".$Material."', '".$Dimensiones."', '".$NumerodeParte."', '".$Color."', '".$FabricacionPais."', '".$FabricacionAno."', 
									'".$PolizaSeguro."', '".$NumeroUnidades."', '".$CodigoCatastro."', '".$AreaFisicaCatastro."', '$MontoCatastro',
									'".$GenerarVoucherIngresoFlag."', '".$CodProveedor."', '".$FacturaTipoDocumento."', '".$FacturaNumeroDocumento."',
									'$FacturaFecha', '".$NumeroOrden."', '$NumeroOrdenFecha', '".$NumeroGuia."', '$NumeroGuiaFecha',
									'".$NumeroDocAlmacen."', '$DocAlmacenFecha', '$InventarioFisicoFecha', '".$InventarioFisicoComentario."', '$FechaIngreso',
									'".$PeriodoIngreso."', '".$PeriodoInicioDepreciacion."', '".$PeriodoInicioRevaluacion."', '".$PeriodoBaja."', '".$VoucherBaja."',
									'$MontoLocal', '$MontoReferencia', '$MontoMercado', '".$VoucherIngreso."', 'PE', '".$_SESSION['USUARIO_ACTUAL']."',
									'".date("Y-m-d H:i:s")."', '".$SituacionActivo."', '".$FlagParaMantenimiento."', '".$FlagParaOperaciones."', '".$OrigenActivo."',
									'".$UnidadMedida."', '".$DepreEspecificaFlag."', '".$PreparadoPor."', '".date("Y-m-d")."', '$Naturaleza',
									'".$CodTipoMovimiento."', 'PE', '".$VoucherPeriodo."', '".$anio."', '".$nrointerorden."')";
 $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
   
   /// INSERT INFORMACION CONTABLE
  $s_insert2 = "insert into af_activohistoricontable(CodActivo, CodContabilidad, LocalInicio, LocalMesFinal,
													 LocalNeto, ALocalInicio, ALocalMesFinal, AlocalNeto,
												     Origen, UltimoUsuario, UltimaFechaModif, Periodo)
											  values('$activo', '".$Contabilidad."', '".$LocalInicio."', '".$LocalMesFinal."',
													 '".$LocalNeto."', '".$ALoclalInicio."', '".$ALocalMesFinal."', '".$ALocalNeto."',
													 '".$OrigenActivo."', '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."', '".date("Y-m")."')";
   $q_insert2 = mysql_query($s_insert2) or die ($s_insert2.mysql_error());
   
  // UPDATE QUE REALIZA CAMBIOS EN LA TABLA LG_ACTIVOFIJO
  // CAMBIO DE ESTADO Y NUMERO DE ACTIVO
  $s_insert3 = "update lg_activofijo set Estado = 'TR', 
                                         Activo = '$activo' 
									where 
									     CodOrganismo = '".$CodOrganismo."' and 
										 NroOrden = '".$NroOrden."' and 
										 Secuencia = '".$Secuencia."' and 
										 NroSecuencia = '".$NroSecuencia."' and 
										 Anio = '".$anio."' and 
										 NroInterno = '".$nrointerorden."'";  
  $q_insert3 = mysql_query($s_insert3) or die ($s_insert3.mysql_error()); 
  
  /// CONSULTA PARA OBTENER DATOS DE LA TRANSACCION
  if($_PARAMETRO['VOUINGP20']='S'){
  $scon4 = "select * from af_tipotranscuenta where TipoTransaccion = '".$TipoTransaccion."' order by Contabilidad";
  $qcon4 = mysql_query($scon4) or die ($scon4.mysql_error());
  $rcon4 = mysql_num_rows($qcon4);
  
  if($rcon4!=0){
    for($i=0; $i<$rcon4; $i++){
	   $fcon4 = mysql_fetch_array($qcon4);
	   if($fcon4['SignoFlag']=='-')$monto_distcontable = '-'.$MontoLocal;
	   else $monto_distcontable = $MontoLocal;
	   $s_insert4 = "insert into af_activodistribcontable ( Activo, TipoTransaccion, Contabilidad, 
	   												        Secuencia, CuentaContable, Monto, 
															UltimoUsuario, UltimaFechaModif) 
  											        values('$activo', '".$fcon4['TipoTransaccion']."', '".$fcon4['Contabilidad']."',
														   '".$fcon4['Secuencia']."', '".$fcon4['CuentaContable']."', '".$monto_distcontable."',
														   '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."')";
	   $q_insert4 = mysql_query($s_insert4) or die($s_insert4.mysql_error());
	}
  }
 }
}
//// -------------------------------------------------------------------------------------------
////                        GUARDAR ACTIVO FIJO "LISTOS DE ACTIVOS"
//// -------------------------------------------------------------------------------------------
if($accion == 'GuardarActivosListaActivos'){
connect();
   
   /// Consultar activo
   $scon = "select max(Activo) from af_activo where CodOrganismo = '".$CodOrganismo."'";
   $qcon = mysql_query($scon) or die ($scon.mysql_error());
   $fcon = mysql_fetch_array($qcon);
   
   $activo = (int) ($fcon[0]+1);
   $activo = (string) str_repeat("0",8-strlen($activo)).$activo; //echo $activo.'-';
   
   if($_POST['FechaIngreso']!='00-00-0000')$FechaIngreso = date("Y-m-d", strtotime($_POST['FechaIngreso']));
   if($_POST['NumeroOrdenFecha']!='00-00-0000')$NumeroOrdenFecha = date("Y-m-d", strtotime($_POST['NumeroOrdenFecha']));
   if($_POST['NumeroGuiaFecha']!='00-00-0000')$NumeroGuiaFecha = date("Y-m-d", strtotime($_POST['NumeroGuiaFecha'])); 
   if($_POST['DocAlmacenFecha']!='00-00-0000')$DocAlmacenFecha = date("Y-m-d", strtotime($_POST['DocAlmacenFecha'])); 
   if($_POST['InventarioFisicoFecha']!='00-00-0000')$InventarioFisicoFecha = date("Y-m-d", strtotime($_POST['InventarioFisicoFecha'])); 
   if($_POST['FacturaFecha']!='00-00-0000')$FacturaFecha = date("Y-m-d", strtotime($_POST['FacturaFecha']));
    
   $MontoCatastro = cambioFormato($_POST['MontoCatastro']);	
   $MontoLocal = cambioFormato($_POST['MontoLocal']);
   $MontoReferencia = cambioFormato($_POST['MontoReferencia']);
   $MontoMercado = cambioFormato($_POST['MontoMercado']);
    
   $s_insert ="insert into af_activo(Activo, CodOrganismo, CodDependencia, Descripcion, TipoActivo, EstadoConserv,
									 CodigoBarras, CodigoInterno, TipoSeguro, TipoVehiculo, Categoria, Clasificacion,
									 ClasificacionPublic20, Ubicacion, TipoMejora, ActivoConsolidado, EmpleadoUsuario,
									 EmpleadoResponsable, CentroCosto, Marca, Modelo,NumeroSerie,NumeroSerieMotor,
									 NumeroPlaca, MarcaMotor,NumeroAsiento, Material, Dimensiones,NumerodeParte,
									 Color, FabricacionPais,FabricacionAno, PolizaSeguro, NumeroUnidades, CodigoCatastro,
									 AreaFisicaCatastro,MontoCatastro, GenerarVoucherIngresoFlag,CodProveedor,
									 FacturaTipoDocumento,FacturaNumeroDocumento,FacturaFecha,NumeroOrden, NumeroOrdenFecha,NumeroGuia,
									 NumeroGuiaFecha,NumeroDocAlmacen,DocAlmacenFecha,InventarioFisicoFecha,InventarioFisicoComentario,FechaIngreso,
									 PeriodoIngreso, PeriodoInicioDepreciacion, PeriodoInicioRevaluacion,PeriodoBajaOnco,VoucherBajaOnco,MontoLocal,
									 MontoReferencia, MontoMercado,VoucherIngreso, Estado,UltimoUsuario, UltimaFechaModif,
									 SituacionActivo, FlagParaMantenimiento,FlagParaOperaciones,OrigenActivo,UnidadMedida,DepreEspecificaFlag,
									 PreparadoPor, FechaPreparado,Naturaleza,ConceptoMovimiento)
                               values
								('$activo', '".$CodOrganismo."', '".$CodDependencia."', '".$Descripcion."', '".$TipoActivo."', '".$EstadoConserv."',
								'".$CodigoBarras."', '".$CodigoInterno."', '".$TipoSeguro."', '".$TipoVehiculo."', '".$Categoria."', '".$Clasificacion."',
								'".$ClasificacionPublic20."','".$Ubicacion."','".$TipoMejora."','".$ActivoConsolidado."','".$EmpleadoUsuario."','".$EmpleadoResponsable."',
								'".$CentroCosto."',	'".$Marca."','".$Modelo."',	'".$NumeroSerie."',	'".$NumeroSerieMotor."','".$NumeroPlaca."',
								'".$MarcaMotor."','".$NumeroAsiento."',	'".$Material."','".$Dimensiones."',	'".$NumerodeParte."','".$Color."',
								'".$FabricacionPais."',	'".$FabricacionAno."','".$PolizaSeguro."','".$NumeroUnidades."','".$CodigoCatastro."','".$AreaFisicaCatastro."',
								'$MontoCatastro','".$GenerarVoucherIngresoFlag."','".$CodProveedor."','".$FacturaTipoDocumento."','".$FacturaNumeroDocumento."','FacturaFecha',
								'".$NumeroOrden."','$NumeroOrdenFecha','".$NumeroGuia."','$NumeroGuiaFecha','".$NumeroDocAlmacen."','$DocAlmacenFecha',
								'$InventarioFisicoFecha','".$InventarioFisicoComentario."','$FechaIngreso','".$PeriodoIngreso."','".$PeriodoInicioDepreciacion."',
								'".$PeriodoInicioRevaluacion."','".$PeriodoBaja."',	'".$VoucherBaja."',	'$MontoLocal','$MontoReferencia','$MontoMercado',
								'".$VoucherIngreso."','PE','".$_SESSION['USUARIO_ACTUAL']."',	'".date("Y-m-d H:i:s")."','".$SituacionActivo."','".$FlagParaMantenimiento."',
								'".$FlagParaOperaciones."',	'".$OrigenActivo."','".$UnidadMedida."','".$DepreEspecificaFlag."','".$PreparadoPor."','".date("Y-m-d")."',
								'".$Naturaleza."','".$ConceptoMovimiento."')";
 $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
   
   /// INSERT INFORMACION CONTABLE
  $s_insert2 = "insert into af_activohistoricontable(CodActivo, CodContabilidad, LocalInicio, LocalMesFinal, 
                                                       LocalNeto, ALocalInicio, ALocalMesFinal, AlocalNeto,
													   Origen, UltimoUsuario, UltimaFechaModif)
												values('$activo', '".$Contabilidad."', '".$LocalInicio."', '".$LocalMesFinal."', 
												      '".$LocalNeto."', '".$ALocalInicio."', '".$ALocalMesFinal."','".$ALocalNeto."',
													  'MA', '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."')";
   $q_insert2 = mysql_query($s_insert2) or die ($s_insert2.mysql_error());
   
  // UPDATE QUE REALIZA CAMBIOS EN LA TABLA LG_ACTIVOFIJO
  // CAMBIO DE ESTADO Y NUMERO DE ACTIVO
  $s_insert3 = "update lg_activofijo set Estado = 'TR', 
                                         Activo = '$activo' 
									where 
									     CodOrganismo = '".$CodOrganismo."' and 
										 NroOrden = '".$NroOrden."' and 
										 Secuencia = '".$Secuencia."' and 
										 NroSecuencia = '".$NroSecuencia."'";  
  $q_insert3 = mysql_query($s_insert3) or die ($s_insert3.mysql_error()); 
}
//// -------------------------------------------------------------------------------------------
////                 MODIFICACIONES EN LISTA DE ACTIVOS   "ACTIVO MAYOR"
//// -------------------------------------------------------------------------------------------
if($accion == 'GuardarModificacionesListaActivos'){
connect();
         
   $FechaIngreso = $_POST['FechaIngreso']; $FechaIngreso = date("Y-m-d", strtotime($FechaIngreso));
   $NumeroOrdenFecha = date("Y-m-d", strtotime($_POST['NumeroOrdenFecha']));
   $NumeroGuiaFecha = date("Y-m-d", strtotime($_POST['NumeroGuiaFecha'])); 
   $DocAlmacenFecha = date("Y-m-d", strtotime($_POST['DocAlmacenFecha'])); 
   $InventarioFisicoFecha = date("Y-m-d", strtotime($_POST['InventarioFisicoFecha'])); 
    
   $MontoCatastro = cambioFormato($_POST['MontoCatastro']);	
   $MontoLocal = cambioFormato($_POST['MontoLocal']);
   $MontoReferencia = cambioFormato($_POST['MontoReferencia']);
   $MontoMercado = cambioFormato($_POST['MontoMercado']);
  
   
   $s_update ="update af_activo set  
					 CodDependencia = '".$CodDependencia."', Descripcion = '".$Descripcion."', TipoActivo = '".$TipoActivo."',
					 EstadoConserv = '".$EstadoConserv."', CodigoBarras = '".$CodigoBarras."', CodigoInterno = '".$CodigoInterno."',
					 TipoSeguro = '".$TipoSeguro."', TipoVehiculo = '".$TipoVehiculo."', Categoria = '".$Categoria."',
					 Clasificacion = '".$Clasificacion."', ClasificacionPublic20 = '".$ClasificacionPublic20."', Ubicacion = '".$Ubicacion."',
					 TipoMejora = '".$TipoMejora."', ActivoConsolidado = '".$ActivoConsolidado."', EmpleadoUsuario = '".$EmpleadoUsuario."',
					 EmpleadoResponsable = '".$EmpleadoResponsable."', CentroCosto = '".$CentroCosto."', Marca = '".$Marca."',
					 Modelo = '".$Modelo."', NumeroSerie = '".$NumeroSerie."', NumeroSerieMotor = '".$NumeroSerieMotor."',
					 NumeroPlaca = '".$NumeroPlaca."', MarcaMotor = '".$MarcaMotor."', NumeroAsiento = '".$NumeroAsiento."',
					 Material = '".$Material."', Dimensiones = '".$Dimensiones."', NumerodeParte = '".$NumerodeParte."',
					 Color = '".$Color."', FabricacionPais = '".$FabricacionPais."', FabricacionAno = '".$FabricacionAno."',
					 PolizaSeguro = '".$PolizaSeguro."', NumeroUnidades = '".$NumeroUnidades."', CodigoCatastro = '".$CodigoCatastro."',
					 AreaFisicaCatastro='".$AreaFisicaCatastro."', MontoCatastro='$MontoCatastro', GenerarVoucherIngresoFlag='".$GenerarVoucherIngresoFlag."', 
					 CodProveedor = '".$CodProveedor."', FacturaTipoDocumento='".$FacturaTipoDocumento."',FacturaNumeroDocumento='".$FacturaNumeroDocumento."',
					 FacturaFecha='".$FacturaFecha."', NumeroOrden = '".$NumeroOrden."', NumeroOrdenFecha = '$NumeroOrdenFecha', 
					 NumeroGuia = '".$NumeroGuia."', NumeroGuiaFecha = '$NumeroGuiaFecha', NumeroDocAlmacen = '".$NumeroDocAlmacen."', 
					 DocAlmacenFecha = '$DocAlmacenFecha',InventarioFisicoFecha = '$InventarioFisicoFecha', InventarioFisicoComentario = '".$InventarioFisicoComentario."',
					 FechaIngreso = '$FechaIngreso', PeriodoIngreso = '".$PeriodoIngreso."', PeriodoInicioDepreciacion = '".$PeriodoInicioDepreciacion."',
					 PeriodoInicioRevaluacion = '".$PeriodoInicioRevaluacion."', PeriodoBajaOnco = '".$PeriodoBaja."', VoucherBajaOnco = '".$VoucherBaja."',
					 MontoLocal = '$MontoLocal', MontoReferencia = '$MontoReferencia', MontoMercado = '$MontoMercado',
					 VoucherIngreso = '".$VoucherIngreso."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFechaModif = '".date("Y-m-d H:i:s")."',
					 SituacionActivo = '".$SituacionActivo."', FlagParaMantenimiento = '".$FlagParaMantenimiento."', FlagParaOperaciones = '".$FlagParaOperaciones."',
					 DepreEspecificaFlag = '".$DepreEspecificaFlag."', CodTipoMovimiento = '".$CodTipoMovimiento."'
                where
				     Activo ='".$Activo."' and CodOrganismo = '".$CodOrganismo."'";

 $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
 

  /// UPDATE INFORMACION CONTABLE
  $s_update2 = "update af_activohistoricontable set  CodContabilidad = '".$Contabilidad."', LocalInicio = '".$LocalInicio."', 
  													   LocalMesFinal ='".$LocalMesFinal."', LocalNeto = '".$LocalNeto."', 
													   ALocalInicio = '".$ALocalInicio."',  ALocalMesFinal ='".$ALocalMesFinal."' , 
													   AlocalNeto = '".$ALocalNeto."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', 
													   UltimaFechaModif = '".date("Y-m-d H:i:s")."' 
												 where 
												       CodActivo='".$Activo."'";
													  
												
   $q_update2 = mysql_query($s_update2) or die ($s_update2.mysql_error());
 
/// CONSULTA PARA OBTENER DATOS DE LA TRANSACCION
if($_PARAMETRO['VOUINGP20']='S'){
	  
 $s = "select * from af_activodistribcontable where Activo='".$Activo."'"; 
 $q = mysql_query($s) or die ($s.mysql_error());
 $r = mysql_num_rows($q);	  
 
 if($r!=0) $f = mysql_fetch_array($q);
 if($f['TipoTransaccion']!= $_POST['TipoTransaccion']){
    $s_delete = "delete from af_activodistribcontable where Activo='".$Activo."'";
	$q_delete = mysql_query($s_delete) or die ($s_delete.mysql_error());
	
	$scon4 = "select * from af_tipotranscuenta where TipoTransaccion = '".$TipoTransaccion."' order by Contabilidad";
    $qcon4 = mysql_query($scon4) or die ($scon4.mysql_error());
    $rcon4 = mysql_num_rows($qcon4);
	
	if($rcon4!=0){
      for($i=0; $i<$rcon4; $i++){
	   $fcon4 = mysql_fetch_array($qcon4);
	   if($fcon4['SignoFlag']=='-')$monto_distcontable = '-'.$MontoLocal;
	   else $monto_distcontable = $MontoLocal;
	   $s_insert4 = "insert into af_activodistribcontable ( Activo, TipoTransaccion, Contabilidad, 
	   												        Secuencia, CuentaContable, Monto, 
															UltimoUsuario, UltimaFechaModif) 
  											        values('".$Activo."', '".$fcon4['TipoTransaccion']."', '".$fcon4['Contabilidad']."',
														   '".$fcon4['Secuencia']."', '".$fcon4['CuentaContable']."', '".$monto_distcontable."',
														   '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."')";
	   $q_insert4 = mysql_query($s_insert4) or die($s_insert4.mysql_error());
		
	 }
   }
 
 }
  
  }
}
//// -------------------------------------------------------------------------------------------
////                         INSERTA NUEVO MOVIMIENTO DE ACTIVO
//// -------------------------------------------------------------------------------------------
if($accion=='guardarNuevoMovimientoActivo'){
connect();
 
 /// Consulta para obtener el último número de movimiento por organismo
 $s_consulta = "select MAX(MovimientoNumero) from af_movimientos where Organismo ='".$Organismo."'"; 
 $q_consulta = mysql_query($s_consulta) or die ($s_consulta.mysql_error());
 $f_consulta = mysql_fetch_array($q_consulta);
 
 /// Crear el número de movimiento
 $Mov_Numero = (int) ($f_consulta['0'] + 1);
 $Mov_Numero = (string) str_repeat("0",10-strlen($Mov_Numero)).$Mov_Numero;
 
 $s_movimientos = "insert into af_movimientos(Organismo, MovimientoNumero, PreparadoPor,
											  FechaPreparacion, Estado, UltimoUsuario,
											  UltimaFechaModif, InternoExternoFlag, Comentario,
											  MotivoTraslado) 
									  values ('".$Organismo."', '$Mov_Numero', '".$PreparadoPor."',
											 '".date("Y-m-d")."', 'PR', '".$_SESSION['USUARIO_ACTUAL']."',
											 '".date("Y-m-d H:i:s")."', '".$InternoExternoFlag."', '".$Comentario."',
											 '".$MotivoTraslado."')";
 $q_movimientos = mysql_query($s_movimientos) or die ($s_movimientos.mysql_error());

 $s_mdetalle = "insert into af_movimientosdetalle(Organismo, Activo, MovimientoNumero, CentroCosto,
												 CentroCostoAnterior, Ubicacion, UbicacionAnterior, Dependencia,
												 DependenciaAnterior, EmpleadoUsuario,  EmpleadoUsuarioAnterior, EmpleadoResponsable,
												 EmpleadoResponsableAnterior, OrganismoActual, OrganismoAnterior,
												 CodTipoMoviDes, CodTipoMoviInc)
										  values ('".$Organismo."', '".$Activo."', '$Mov_Numero', '".$CentroCosto."',
												  '".$CentroCostoAnterior."', '".$Ubicacion."', '".$UbicacionAnterior."', '".$Dependencia."',
												  '".$DependenciaAnterior."', '".$EmpleadoUsuario."', '".$EmpleadoUsuarioAnterior."', '".$EmpleadoResponsable."',
												  '".$EmpleadoResponsableAnterior."', '".$OrganismoActual."', '".$OrganismoAnterior."',
												  '".$CodTipoMoviDes."', '".$CodTipoMoviInc."')";
 $q_mdetalle = mysql_query($s_mdetalle) or die ($s_mdetalle.mysql_error());
 

//// -------------------  SE CARGAN DATOS EN TABLA HISTORICA DE MOVIMIENTOS DEL ACTIVO
/*$sact = "select	
			a.MovimientoNumero,
			b.Activo
		from  
			af_movimientos a 
			inner join af_movimientosdetalle b on (a.Organismo = b.Organismo) and 
												  (a.MovimientoNumero = b.MovimientoNumero)
		where  
			a.MovimientoNumero=(SELECT MAX(MovimientoNumero) FROM af_movimientos)";
$qact = mysql_query($sact) or die ($sact.mysql_error());
$fact = mysql_fetch_array($qact);

$sth = "select 
              *, Secuencia 
		 from 
		      af_historicotransaccion 
	     where 
		      Secuencia=(SELECT MAX(Secuencia) FROM af_historicotransaccion and Activo='".$Activo."' and CodOrganismo = '".$Organismo."')";
$qth = mysql_query($sth) or die ($sth.mysql_error());
	$secuencia = (int) (0 + 1);
	$secuencia = (string) str_repeat("0",4-strlen($secuencia)).$secuencia;


$sin = "insert into af_historicotransaccion(CodOrganismo, Activo, Secuencia, CodDependencia,
											CentroCosto, CodigoInterno, SituacionActivo, CodTipoMovimiento,
											Ubicacion, InternoExternoFlag, MotivoTraslado, FechaIngreso,
											FechaTransaccion, FechaBaja, PeriodoIngreso, PeriodoTransaccion,
											NroOrden, MontoActivo, UltimoUsuario,UltimaFechaModif)
									  value ('".$Organismo."', '".$Activo."', '$secuencia', '".$fact['CodDependencia']."',
											'".$fact['CentroCosto']."',	'".$fact['CodigoInterno']."', '".$fact['SituacionActivo']."', '".$fact['CodTipoMovimiento']."',
											'".$fact['Ubicacion']."', 'I', '01', '".$fact['FechaIngreso']."',
											'".date("Y-m-d")."', '".date("Y-m-d")."', '".$fact['PeriodoIngreso']."', '".date("Y-m")."',
											'".$fact['NumeroOrden']."', '".$fact['MontoLocal']."', '".$_SESSION['USUARIO_ACTUAL']."','".date("Y-m-d H:i:s")."')";
$qin = mysql_query($sin) or die ($sin.mysql_error());*/
 
  
 echo $Mov_Numero;
 
 }
 //// ------------------------------------------------------------------------------------------
////                         INSERTA ESTADO / REVISAR MOVIMIENTO DE ACTIVO
//// -------------------------------------------------------------------------------------------
if($accion=='guardarRevisarMovimientoActivo'){
connect();
  $sql_a = "update 
  				  af_movimientos 
			    set 
				  Estado='RV',
				  RevisadoPor = '".$RevisadoPor."',
				  FechaRevision = '".date("Y-m-d")."',
				  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
				  UltimaFechaModif = '".date("Y-m-d H:i:s")."'
		     where 
			      Organismo = '".$Organismo."' and 
				  MovimientoNumero = '".$NumeroMovimiento."'";  
  $qry_a = mysql_query($sql_a) or die ($sql_a.mysql_error());
}
//// -------------------------------------------------------------------------------------------
////                   GUARDAR MODIFICACIONES DE MOVIMIENTO DE ACTIVO
//// -------------------------------------------------------------------------------------------
if($accion=='guardarEditarMovimientoActivo'){
connect();
$s_movimientos = "update af_movimientos set
                                            UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
											UltimaFechaModif = '".date("Y-m-d H:i:s")."',
											InternoExternoFlag = '".$InternoExternoFlag."',
											Comentario = '".$Comentario."',
											MotivoTraslado = '".$MotivoTraslado."'
									   where 
									        Organismo = '".$Organismo."' and 
											MovimientoNumero = '".$MovimientoNumero."'"; //echo $s_movimientos;
 $q_movimientos = mysql_query($s_movimientos) or die ($s_movimientos.mysql_error());

 $s_mdetalle = "update af_movimientosdetalle set 
                                  				 CentroCosto = '".$CentroCosto."',
												 CentroCostoAnterior = '".$CentroCostoAnterior."',
												 Ubicacion = '".$Ubicacion."',
												 UbicacionAnterior = '".$UbicacionAnterior."',
												 Dependencia = '".$Dependencia."',
												 DependenciaAnterior = '".$DependenciaAnterior."',
												 EmpleadoUsuario = '".$EmpleadoUsuario."',
												 EmpleadoUsuarioAnterior = '".$EmpleadoUsuarioAnterior."',
												 EmpleadoResponsable = '".$EmpleadoResponsable."',
												 EmpleadoResponsableAnterior = '".$EmpleadoResponsableAnterior."',
												 OrganismoActual = '".$OrganismoActual."',
												 OrganismoAnterior = '".$OrganismoAnterior."'
										   where 
										         Organismo = '".$Organismo."' and 
											     Activo = '".$Activo."' and 
											     MovimientoNumero = '".$MovimientoNumero."'";
 $q_mdetalle = mysql_query($s_mdetalle) or die ($s_mdetalle.mysql_error());
}
//// -------------------------------------------------------------------------------------------
////                     APROBAR MOVIMIENTO DE ACTIVO
//// -------------------------------------------------------------------------------------------
if($accion=="guardarAprobarMovimientoActivo"){
 connect();	
 
 //// Consulto para saber si el usuario actual es el mismo que preparo el movimiento
 //// si lo es no puede realizarce el update en af_movimientos
 $scon = "select CodPersona  from usuarios where Usuario='".$_SESSION['USUARIO_ACTUAL']."'";
 $qcon = mysql_query($scon) or die ($scon.mysql_error()); 
 $rcon = mysql_num_rows($qcon); if($rcon!=0)$fcon = mysql_fetch_array($qcon); 
 
 /// obtengo el máximo de secuencia rh_empleadonivelacion
  $s_c = "select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$fcon['CodPersona']."'";
  $q_c = mysql_query($s_c) or die ($s_c.mysql_error());
  $r_c = mysql_num_rows($q_c); if($r_c!=0)$f_c = mysql_fetch_array($q_c);
 
 //// Obtengo datos del Aprobador  
 $s_b = "select 
				a.CodCargo,
				a.CodPersona,
				b.DescripCargo,
				c.NomCompleto
			from 
				rh_empleadonivelacion a 
				inner join rh_puestos b on (b.CodCargo=a.CodCargo)
				inner join mastpersonas c on (c.CodPersona = a.CodPersona)
		  where 
				a.Secuencia='".$f_c[0]."' and  
				a.CodPersona='".$fcon['CodPersona']."'";
  $q_b = mysql_query($s_b) or die ($s_b.mysql_error());
  $r_b = mysql_num_rows($q_b); if($r_b!=0)$f_b = mysql_fetch_array($q_b);
  
 //// Obtengo datos de la encargada de la Dirección General, utilizamos el parametro
  $parametroAprobadoPor= $_PARAMETRO['APROBACTIVOPOR'] ; /// Conformado Dirección General 0003
  	   $s_dg = "select 
					  mp.ValorParam,
					  b.CodPersona,
					  d.DescripCargo
				from 
					  mastparametros mp 
					  inner join mastdependencias a on (a.CodDependencia=mp.ValorParam) 
					  inner join mastpersonas b on (b.CodPersona=a.CodPersona) 
					  inner join mastempleado c on (c.CodPersona=a.CodPersona)
					  inner join rh_puestos d on (d.CodCargo=c.CodCargo)
				where 
					  mp.ValorParam='$parametroAprobadoPor'";
	  $q_dg = mysql_query($s_dg) or die ($s_dg.mysql_error());
	  $r_dg = mysql_num_rows($q_dg); if($r_dg!=0) $f_dg = mysql_fetch_array($q_dg);

	  $s_dg2 = "select 
					a.CodCargo,
					a.CodPersona,
					b.DescripCargo,
					c.NomCompleto	
				from 
					rh_empleadonivelacion a 
					inner join rh_puestos b on (b.CodCargo = a.CodCargo)
					inner join mastpersonas c on (c.CodPersona = a.CodPersona) 
				where
					a.CodPersona='".$f_dg['CodPersona']."' and  
					a.Secuencia = (select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$f_dg['CodPersona']."')"; 
	   $q_dg2 = mysql_query($s_dg2) or die ($s_dg2.mysql_error());
	   $r_dg2 = mysql_num_rows($q_dg2); if($r_dg2!=0) $f_dg2 = mysql_fetch_array($q_dg2);
 
 //// Consulto para saber si quien preparó es igual a quien intenta aprobar
 if($_POST['PreparadoPor']==$fcon['CodPersona']){ 
   //echo "EL USUARIO QUE PREPARO EL MOVIMIENTO DEBE SER DISTINTO AL MOMENTO DE APROBAR";
   $cont = 1; echo $cont; 
 }else{
    
	/// Obteniendo nro de acta de Entrega
    $sentrega = "select max(NroActa) from af_actaentregaactivo where CodOrganismo='$CodOrganismo'"; 
	$qentrega = mysql_query($sentrega) or die ($sentrega.mysql_error());
	$fentrega = mysql_fetch_array($qentrega);
   
    $nro_acta_antrega = (int) ($fentrega[0]+1);
	$nro_acta_antrega = (string) str_repeat("0",4-strlen($nro_acta_antrega)).$nro_acta_antrega;
	
	$sql01 = "update 
	                af_movimientos 
			     set 
				    AprobadoPor = '".$fcon['CodPersona']."',
					FechaAprobacion = '".date("Y-m-d")."',
					Estado = 'AP'
			   where 
			        MovimientoNumero = '".$MovimientoNumero."' and 
					Organismo = '".$Organismo."'"; //echo $sql01;//echo"<option>".$sql01."</option>";
    $qry01 = mysql_query($sql01) or die ($sql01.mysql_error());
	
	$sql = "update af_activo set 
                              CentroCosto = '".$CentroCosto."',
							  Ubicacion = '".$Ubicacion."',
							  EmpleadoUsuario = '".$EmpleadoUsuario."',
							  EmpleadoResponsable = '".$EmpleadoResponsable."',
							  CodDependencia = '".$CodDependencia."',
							  CodOrganismo = '".$CodOrganismo."',
							  NroActaEntrega = '$nro_acta_antrega' 
						where 
						      Activo='".$Activo."' and 
							  CodOrganismo='".$Organismo."'"; //echo $sql;
   $qry = mysql_query($sql) or die ($sql.mysql_error());

	$sa = "select 
				 CodigoInterno, SituacionActivo, FechaIngreso, 
				 PeriodoIngreso, MontoLocal
			 from 
				 af_activo 
			where 
				 Activo='".$Activo."' and CodOrganismo='".$Organismo."'"; 
	$qa = mysql_query($sa) or die ($sa.mysql_error());
	$fa = mysql_fetch_array($qa);

	$sact = "select 
				a.*,
				a.MovimientoNumero,
				b.*
			from 
				 af_movimientosdetalle a
				 inner join af_movimientos b on (a.MovimientoNumero = b.MovimientoNumero) and 
												(a.Organismo = b.Organismo)
			where 
				a.MovimientoNumero=(select max(MovimientoNumero) from af_movimientosdetalle where Activo='".$Activo."' and Organismo='".$Organismo."') and 
				a.Organismo = '".$Organismo."'";
	$qact = mysql_query($sact) or die ($sact.mysql_error());
	$fact = mysql_fetch_array($qact);

   //// Consulta en "AF_ACTIVO" para obtener nuevos datos relacionados al EmpleadoResponsable
   //// a ingresar en "AF_ACTAENTREGAACTIVO"
   $s_activo = "select 
   					  a.*,
					  b.NomCompleto as NombreEmpleadoResponsable
				 from 
				     af_activo a 
					 inner join mastpersonas b on (b.CodPersona=a.EmpleadoResponsable)
				where 
					 a.Activo='".$Activo."' and 
					 a.CodOrganismo = '".$CodOrganismo."'";
   $q_activo = mysql_query($s_activo) or die ($s_activo.mysql_error());
   $r_activo = mysql_num_rows($q_activo); if($r_activo!=0) $f_activo = mysql_fetch_array($q_activo) ;
   
   $s_activosdat = "select 
						a.CodCargo,
						a.CodPersona,
						b.DescripCargo	
					from 
						rh_empleadonivelacion a 
						inner join rh_puestos b on (b.CodCargo=a.CodCargo) 
					where
						a.CodPersona='".$f_activo['EmpleadoResponsable']."' and  
						a.Secuencia = (select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$f_activo['EmpleadoResponsable']."')";
	$q_activosdat = mysql_query($s_activosdat) or die ($s_activosdat.mysql_error());
	$r_activosdat = mysql_num_rows($q_activosdat); if($r_activosdat!=0) $f_activosdat = mysql_fetch_array($q_activosdat);
	
   
   
   
   //// Insertando Datos en "AF_ACTAENTREGAACTIVO"
   $s_actaentrega = "insert into af_actaentregaactivo(Activo, Anio, NroActa, FechaActa,
													   AprobadoPor, CargoAprobador, DescripCargoAprob, EmpleadoAprob, 
													   ConformadoPor, EmpleadoConform, CargoConform, DescripCargoConform,
													   ResponsablePrimario, EmpleadoRespon, CargoResponsable, 
													   DescripCargoRespon, UltimoUsuario, UltimoFechaModif, CodOrganismo) 
												 value('".$Activo."', '".date("Y")."', '$nro_acta_antrega', '".date("Y-m-d")."',
												  	   '".$fcon['CodPersona']."', '".$f_b['CodCargo']."', '".$f_b['DescripCargo']."', '".$f_b['NomCompleto']."',
													   '".$f_dg2['CodPersona']."','".$f_dg2['NomCompleto']."','".$f_dg2['CodCargo']."','".$f_dg2['DescripCargo']."',
													   '".$f_activo['EmpleadoResponsable']."','".$f_activo['NombreEmpleadoResponsable']."','".$f_activosdat['CodCargo']."',
													   '".$f_activosdat['DescripCargo']."','".$_SESSION['USUARIO_ACTUAL']."','".date("Y-m-d H:i:s")."', '".$CodOrganismo."')";
													   
	$q_actaentrega = mysql_query($s_actaentrega) or die ($s_actaentrega.mysql_error());
	
	//// insertando lineas de registro en "AF_HISTORICOTRANSACCION"
	$veces = 2;
	for($a=0;$a<$veces;$a++){
		$cont_1 +=1;	
		/// Se obtiene la secuencia y otros datos que esten relacionados con el activo al cual se
		/// le esta realizando el movimiento en la tabla "AF_HISTORICOTRANSACCION"
		$sth = "select 
					  * 
				 from 
					  af_historicotransaccion 
				 where 
					  Secuencia=(SELECT MAX(Secuencia) FROM af_historicotransaccion where Activo='".$Activo."' and CodOrganismo='".$Organismo."') and 
					  CodOrganismo = '".$Organismo."'";
		$qth = mysql_query($sth) or die ($sth.mysql_error());
		$fth = mysql_fetch_array($qth);
	
		/// Se crea la secuencia 
		$secuencia = (int) ($fth['Secuencia'] + 1);
		//$secuencia = (string) str_repeat("0",4-strlen($secuencia)).$secuencia;
		
		/// Se realiza la inserción en la tabla "AF_HISTORICOTRANSACCION" 	
		if($cont_1=='2'){
		  $sin = "insert into af_historicotransaccion(CodOrganismo, Activo, Secuencia, CodDependencia,
													CentroCosto, CodigoInterno, SituacionActivo,
													Ubicacion, InternoExternoFlag, MotivoTraslado, FechaIngreso,
													FechaTransaccion, PeriodoIngreso, PeriodoTransaccion, NumeroOrden, 
													OrdenSecuencia, MontoActivo, UltimoUsuario, UltimaFechaModif, 
													CodTipoMovimiento, NumeroMovimiento)
											  value ('".$Organismo."', '".$Activo."', '$secuencia', '".$fact['Dependencia']."',
													'".$fact['CentroCosto']."',	'".$fa['CodigoInterno']."', '".$fa['SituacionActivo']."',
													'".$fact['Ubicacion']."', '".$fact['InternoExternoFlag']."', '".$fact['MotivoTraslado']."', '".$fa['FechaIngreso']."',
													'".date("Y-m-d")."', '".$fa['PeriodoIngreso']."', '".date("Y-m")."', '".$fth['NumeroOrden']."', 
													'".$fth['OrdenSecuencia']."', '".$fa['MontoLocal']."', '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."',
													'".$fact['CodTipoMoviInc']."', '".$fact['MovimientoNumero']."')"; 
		}else{
		  $sin = "insert into af_historicotransaccion(CodOrganismo, Activo, Secuencia, CodDependencia,
													CentroCosto, CodigoInterno, SituacionActivo,
													Ubicacion, InternoExternoFlag, MotivoTraslado, FechaIngreso,
													FechaTransaccion, PeriodoIngreso, PeriodoTransaccion, NumeroOrden, 
													OrdenSecuencia, MontoActivo, UltimoUsuario, UltimaFechaModif, 
													CodTipoMovimiento, NumeroMovimiento)
											  value ('".$CodOrganismoAnterior."', '".$Activo."', '$secuencia', '".$CodDependenciaAnterior."',
													'".$CentroAnterior."',	'".$fa['CodigoInterno']."', '".$fa['SituacionActivo']."',
													'".$UbicacionAnterior."', '".$fact['InternoExternoFlag']."', '".$fact['MotivoTraslado']."', '".$fa['FechaIngreso']."',
													'".date("Y-m-d")."', '".$fa['PeriodoIngreso']."', '".date("Y-m")."', '".$fth['NumeroOrden']."', 
													'".$fth['OrdenSecuencia']."', '".$fa['MontoLocal']."', '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."', 
													'".$fact['CodTipoMoviDes']."', '".$fact['MovimientoNumero']."')"; 
		}
		$qin = mysql_query($sin) or die ($sin.mysql_error());  
	}
 
$cont = 2; echo $cont;
}}
//// -------------------------------------------------------------------------------------------
///                GUARDAR MAESTRO NUEVA CARACATERISTICA TECNICA DE ACTIVO
//// -------------------------------------------------------------------------------------------
if($accion=="guardarNuevaCaracteristicaTecnica"){
connect();
 $sql = "select * from af_caracteristicatecnica where CodCaractTecnica = '".$CodCaractTecnica."'";
 $qry = mysql_query($sql)  or die ($sql.mysql_error());
 $row = mysql_num_rows($qry);
 if($row!=0){
	echo"¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡";
 }else{
    $s_insert = "insert into af_caracteristicatecnica(CodCaractTecnica,
													  DescripcionLocal,
													  Estado,
													  UltimoUsuario,
													  UltimaFechaModif)
											   values 
											         ('".$CodCaractTecnica."',
													  '".$DescripcionLocal."',
													  '".$Estado."',
													  '".$_SESSION['USUARIO_ACTUAL']."',
													  '".date("Y-m-d H:i:s")."')";
	$q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
 }
}
//// -------------------------------------------------------------------------------------------
///                     EDITAR MAESTRO CARACATERISTICA TECNICA DE ACTIVO
//// -------------------------------------------------------------------------------------------
if($accion=="editarCaracteristicaTecnica"){
connect();
 $s_update = "update 
                    af_caracteristicatecnica 
				 set 
				    DescripcionLocal = '".$DescripcionLocal."', 
					Estado = '".$Estado."', 
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFechaModif = '".date("Y-m-d H:i:s")."'
			   where 
			        CodCaractTecnica = '".$CodCaractTecnica."'";
 $q_update = mysql_query($s_update)  or die ($s_update.mysql_error());
}
//// -------------------------------------------------------------------------------------------
///                    ELIMINAR MAESTRO CARACTERISTICA TECNICA DE ACTIVO
//// -------------------------------------------------------------------------------------------
if($accion=="eliminarCaractTecnica"){
connect();
 //echo "CODIGO=".$_POST['codigo'];	
$sql = "delete from af_caracteristicatecnica where CodCaractTecnica = '".$codigo."'"; 
$qry = mysql_query($sql) or die ($sql.mysql_error()); 
}
/// ----------------------------------------------------------------------
///            GUARDAR MAESTRO COMPONENTES DE UN EQUIPO
//// ---------------------------------------------------------------------
if($accion=="guardarNuevoComponenteEquipo"){
connect();
 $sql = "select * from af_tipocomponente where CodTipoComp = '".$CodTipoComp."'";
 $qry = mysql_query($sql)  or die ($sql.mysql_error());
 $row = mysql_num_rows($qry);
 if($row!=0){
	echo"¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡";
 }else{
    $s_insert = "insert into af_tipocomponente(CodTipoComp,
											  DescripcionLocal,
											  Estado,
											  UltimoUsuario,
											  UltimaFechaModif)
									   values 
											 ('".$CodTipoComp."',
											  '".$DescripcionLocal."',
											  '".$Estado."',
											  '".$_SESSION['USUARIO_ACTUAL']."',
											  '".date("Y-m-d H:i:s")."')";
	$q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
 }
}
/// ----------------------------------------------------------------------
///               EDITAR MAESTRO COMPONENTES DE UN EQUIPO
//// ---------------------------------------------------------------------
if($accion=="editarComponenteEquipo"){
connect();
$s_update = "update af_tipocomponente set 
                                         DescripcionLocal = '".$DescripcionLocal."',
										 Estado = '".$Estado."',
										 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', 
										 UltimaFechaModif = '".date("Y-m-d H:i:s")."' 
								   where 
								         CodTipoComp = '".$CodTipoComp."'";
$q_update = mysql_query($s_update) or die ($s_update.mysql_error()) ;
}
/// ---------------------------------------------------------------------
///          ELIMINAR REGISTRO DE MAESTRO COMPONENTES DE UN EQUIPO
//// ---------------------------------------------------------------------
if($accion == "eliminarComponentesEquipo"){
connect();
 $s_delete = "delete from af_tipocomponente where CodTipoComp = '".$codigo."'";
 $q_delete = mysql_query($s_delete) or die ($s_delete.mysql_error());
}
//// ---------------------------------------------------------------------
/// 	INSERTAR LINEAS EN CARACTERISTICAS TECNICAS DEL ACTIVO
//// ---------------------------------------------------------------------
if ($accion == "insertarLineaCaracTecnicasActivo"){
connect();	
    $sa = "select * from af_caracteristicatecnica where Estado='A'";
    $qa = mysql_query($sa) or die ($sa.mysql_error());
    $ra = mysql_num_rows($qa);
	$cont = $_POST['cont'];
    ?>
    <td align="center">
      <input type="text" id="cont" name="cont" value="<?=$cont;?>" size="1" style="text-align:right" readonly/>
    </td>
	<td>
		<select name="select1" style="width:100%;">
        <?
         if($ra!=0){
           for($i=0;$i<$ra;$i++){
            $fa = mysql_fetch_array($qa); 
        ?>
			<option value="<?=$fa['CodCaractTecnica']?>"><?=$fa['DescripcionLocal']?></option>
        <? }}?>  
		</select>	
	</td>
	<td align="center">
		<input type="text" name="cantidad" style="width:100%; text-align:right"/>
	</td>
    <td>
		<input type="text" name="comentario" style="width:100%;"/>
	</td>
    <td>
		<input type="text" name="observaciones" style="width:100%;"/>
	</td>
	<?
}
//// ---------------------------------------------------------------------
/// 			INSERTAR LINEAS EN PARTES DEL ACTIVO
//// ---------------------------------------------------------------------
if ($accion == "insertarLineaCaracTecnicasActivo2"){
connect();	
    $sa = "select * from af_tipocomponente where Estado='A'";
    $qa = mysql_query($sa) or die ($sa.mysql_error());
    $ra = mysql_num_rows($qa);
	$cont2 = $_POST['cont2'];
    ?>
    <td>
      <input type="text" name="cont2" value="<?=$cont2;?>" size="1" style="text-align:right" readonly />
    </td>
	<td>
		<select name="select1" style="width:100%;">
        <?
         if($ra!=0){
           for($i=0;$i<$ra;$i++){
            $fa = mysql_fetch_array($qa); 
        ?>
			<option value="<?=$fa['CodTipoComp']?>"><?=$fa['DescripcionLocal']?></option>
        <? }}?>  
		</select>	
	</td>
    <td>
		<input type="text" name="descripcion" style="width:100%;"/>
	</td>
	<td>
		<input type="text" name="marca" style="width:100%;"/>
	</td>
    <td>
		<input type="text" name="num_serie" style="width:100%; text-align:right"/>
	</td>
    <td>
		<input type="text" name="fecha_asignacion" style="width:100%;"/>
	</td>
	<?
}
//// ---------------------------------------------------------------------
//// 						GUARDAR ACTIVO MENORES
//// ---------------------------------------------------------------------
if($accion == "GuardarActivosMenores"){
connect();
   /// Consultar activo
   $scon = "select max(Activo) from af_activo where CodOrganismo = '".$CodOrganismo."'";
   $qcon = mysql_query($scon) or die ($scon.mysql_error());
   $fcon = mysql_fetch_array($qcon);
   
   $activo = (int) ($fcon[0]+1);
   $activo = (string) str_repeat("0",10-strlen($activo)).$activo; //echo $activo.'-';
   
   if($_POST['FechaIngreso']!='00-00-0000')$FechaIngreso = date("Y-m-d", strtotime($_POST['FechaIngreso']));
   if($_POST['NumeroOrdenFecha']!='00-00-0000')$NumeroOrdenFecha = date("Y-m-d", strtotime($_POST['NumeroOrdenFecha']));
   if($_POST['NumeroGuiaFecha']!='00-00-0000')$NumeroGuiaFecha = date("Y-m-d", strtotime($_POST['NumeroGuiaFecha'])); 
   if($_POST['DocAlmacenFecha']!='00-00-0000')$DocAlmacenFecha = date("Y-m-d", strtotime($_POST['DocAlmacenFecha'])); 
   if($_POST['InventarioFisicoFecha']!='00-00-0000')$InventarioFisicoFecha = date("Y-m-d", strtotime($_POST['InventarioFisicoFecha'])); 
   if($_POST['FacturaFecha']!='00-00-0000')$FacturaFecha = date("Y-m-d", strtotime($_POST['FacturaFecha']));
    
   $MontoCatastro = cambioFormato($_POST['MontoCatastro']);	
   $MontoLocal = cambioFormato($_POST['MontoLocal']);
   $MontoReferencia = cambioFormato($_POST['MontoReferencia']);
   $MontoMercado = cambioFormato($_POST['MontoMercado']);
   
   $s_prepor = "select CodPersona from usuarios where Usuario='".$_SESSION['USUARIO_ACTUAL']."'";
   $q_prepor = mysql_query($s_prepor) or die ($s_prepor.mysql_error());
   $r_prepor = mysql_num_rows($q_prepor);
   if($r_prepor!=0) $f_prepor = mysql_fetch_array($q_prepor);
   
   
    
   $s_insert ="insert into af_activo(Activo, CodOrganismo, CodDependencia, Descripcion, DescpCorta,
									 CodigoBarras, CodigoInterno, Clasificacion,  Ubicacion,
									 ActivoConsolidado, EmpleadoResponsable, CentroCosto, Marca,
									 Modelo, NumeroSerie, Dimensiones, Color, FabricacionPais,
									 FabricacionAno, CodProveedor, FacturaTipoDocumento, FacturaNumeroDocumento,
									 FacturaFecha, NumeroOrden, NumeroOrdenFecha, NumeroGuia, NumeroGuiaFecha,
									 FechaIngreso, PeriodoIngreso, MontoLocal, UltimoUsuario,
									 UltimaFechaModif, SituacionActivo, FlagParaOperaciones, Naturaleza,
									 PreparadoPor, EstadoRegistro, ClasificacionPublic20, CodTipoMovimiento, OrigenActivo, Estado,
									 FechaPreparacion, Categoria, EmpleadoUsuario)
                               values
                                    ('$activo', '".$CodOrganismo."', '".$CodDependencia."', '".$Descripcion."', '".$DescpCorta."',
									'".$CodigoBarras."', '".$CodigoInterno."', '".$Clasificacion."', '".$Ubicacion."',
									'".$ActivoConsolidado."', '".$EmpleadoResponsable."', '".$CentroCosto."', '".$Marca."',
									'".$Modelo."', '".$NumeroSerie."', '".$Dimensiones."', '".$Color."', '".$FabricacionPais."',
									'".$FabricacionAno."', '".$CodProveedor."', '".$FacturaTipoDocumento."', '".$FacturaNumeroDocumento."',
									'$FacturaFecha', '".$NumeroOrden."', '$NumeroOrdenFecha', '".$NumeroGuia."', '$NumeroGuiaFecha',
									'$FechaIngreso', '".$PeriodoIngreso."', '$MontoLocal', '".$_SESSION['USUARIO_ACTUAL']."',
									'".date("Y-m-d H:i:s")."',	'".$SituacionActivo."', '".$FlagParaOperaciones."', '".$Naturaleza."',
									'".$f_prepor['CodPersona']."', 'PE', '".$ClasificacionPublic20."', '".$CodTipoMovimiento."', 'MA', 'PE',
									'".date("Y-m-d")."', '".$Categoria."', '".$EmpleadoUsuario."')";
 $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
}
//// ---------------------------------------------------------------------
//// 						MODIFICAR ACTIVO MENORES
//// ---------------------------------------------------------------------
if($accion == 'GuardarModificacionesActivosMenores'){
connect();
   
   if($_POST['FechaIngreso']!='00-00-0000')$FechaIngreso = date("Y-m-d", strtotime($_POST['FechaIngreso']));
   if($_POST['NumeroOrdenFecha']!='00-00-0000')$NumeroOrdenFecha = date("Y-m-d", strtotime($_POST['NumeroOrdenFecha']));
   if($_POST['NumeroGuiaFecha']!='00-00-0000')$NumeroGuiaFecha = date("Y-m-d", strtotime($_POST['NumeroGuiaFecha'])); 
   if($_POST['DocAlmacenFecha']!='00-00-0000')$DocAlmacenFecha = date("Y-m-d", strtotime($_POST['DocAlmacenFecha'])); 
   if($_POST['InventarioFisicoFecha']!='00-00-0000')$InventarioFisicoFecha = date("Y-m-d", strtotime($_POST['InventarioFisicoFecha'])); 
   if($_POST['FacturaFecha']!='00-00-0000')$FacturaFecha = date("Y-m-d", strtotime($_POST['FacturaFecha']));
    
   $MontoCatastro = cambioFormato($_POST['MontoCatastro']);	
   $MontoLocal = cambioFormato($_POST['MontoLocal']);
   $MontoReferencia = cambioFormato($_POST['MontoReferencia']);
   $MontoMercado = cambioFormato($_POST['MontoMercado']);
    
   $s_update = "update 
                       af_activo 
				   set
						CodDependencia='".$CodDependencia."', Descripcion='".$Descripcion."', DescpCorta='".$DescpCorta."', 
						CodigoBarras='".$CodigoBarras."', CodigoInterno='".$CodigoInterno."', Clasificacion='".$Clasificacion."',
						Ubicacion='".$Ubicacion."', ActivoConsolidado='".$ActivoConsolidado."', EmpleadoResponsable='".$EmpleadoResponsable."',
						CentroCosto='".$CentroCosto."', Marca='".$Marca."', Modelo='".$Modelo."', NumeroSerie='".$NumeroSerie."', Dimensiones='".$Dimensiones."',
						Color='".$Color."',	FabricacionPais='".$FabricacionPais."', FabricacionAno='".$FabricacionAno."', CodProveedor='".$CodProveedor."',
						FacturaTipoDocumento='".$FacturaTipoDocumento."', FacturaNumeroDocumento='".$FacturaNumeroDocumento."', FacturaFecha='$FacturaFecha',
						NumeroOrden='".$NumeroOrden."', NumeroOrdenFecha='".$NumeroOrdenFecha."', NumeroGuia='".$NumeroGuia."', NumeroGuiaFecha='$NumeroGuiaFecha',
						MontoLocal='$MontoLocal', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFechaModif='".date("Y-m-d H:i:s")."',
						SituacionActivo='".$SituacionActivo."', FlagParaOperaciones='".$FlagParaOperaciones."', EmpleadoUsuario='".$EmpleadoUsuario."' 
				where  
				         Activo='".$Activo."' and 
						 CodOrganismo = '".$CodOrganismo."'";
 $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
}
//// ---------------------------------------------------------------------
//// 			PARA INSERTAR LINEAS EN AGRUPAR/CONSOLIDAR
//// ---------------------------------------------------------------------
if ($accion == "insertarLineaAgroCons") {
connect();	
     $sql = "select 
	 			   a.CodigoInterno,
				   a.Activo,
				   a.Descripcion,
				   b.Descripcion as DescpUbicacion
			  from 
			       af_activo a 
				   inner join af_ubicaciones b on (b.CodUbicacion=a.Ubicacion)
			 where 
			 	   a.Activo='".$codigo."'";
	 $qry = mysql_query($sql) or die ($sql.mysql_error());
	 $field = mysql_fetch_array($qry);
    ?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_detalle');$('#registro').val('');" id="det_<?=$field['Activo'];?>">
	 <td><img src="imagenes/asignar2.jpg" style="width:20px;height:20px;visibility:visible"/>Interno: <input type="text" id="c_Interno" name="c_Interno" size="23" value="<?=$field['CodigoInterno'];?>" disabled/> 
     # Activo: <input type="text" id="numero_activo" name="numero_activo" size="23" value="<?=$field['Activo'];?>" disabled/> 
     Descripci&oacute;n: <input type="text" id="DescripcionActivo" name="DescripcionActivo" size="67" value="<?=$field['Descripcion'];?>" disabled/>
       Ubicaci&oacute;n: <input type="text" id="ubicacionActivo" name="ubicacionActivo" size="69" value="<?=$field['DescpUbicacion'];?>" disabled/> 
       Sitacui&oacute;n de Alquiler: <input type="text" id="situacionAlquiler" name="situacionAlquiler" size="54" value="" disabled/></td>
     </tr>
     <!--<td># Activo: <input type="text" id="numero_activo" name="numero_activo"/></td>-->
	<? echo "|".$field['Activo'];
}
//// ---------------------------------------------------------------------
//// 			PARA MOSTAR LINEAS EN AGRUPAR/CONSOLIDAR
//// ---------------------------------------------------------------------
if ($accion == "mostrarLineaAgroCons") {
connect();	
     $sql = "select 
	 			   a.CodigoInterno,
				   a.Activo,
				   a.Descripcion,
				   b.Descripcion as DescpUbicacion
			  from 
			       af_activo a 
				   inner join af_ubicaciones b on (b.CodUbicacion=a.Ubicacion)
			 where 
			 	   a.Activo='".$codigo."'";
	 $qry = mysql_query($sql) or die ($sql.mysql_error());
	 $field = mysql_fetch_array($qry);
    ?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_detalle');$('#registro').val('');" id="det_<?=$field['Activo'];?>">
	 <td><img src="imagenes/asignar2.jpg" style="width:20px;height:20px;visibility:visible"/>Interno: <input type="text" id="c_Interno" name="c_Interno" size="23" value="<?=$field['CodigoInterno'];?>" disabled/> 
     # Activo: <input type="text" id="numero_activo" name="numero_activo" size="23" value="<?=$field['Activo'];?>" disabled/> 
     Descripci&oacute;n: <input type="text" id="DescripcionActivo" name="DescripcionActivo" size="67" value="<?=$field['Descripcion'];?>" disabled/>
       Ubicaci&oacute;n: <input type="text" id="ubicacionActivo" name="ubicacionActivo" size="69" value="<?=$field['DescpUbicacion'];?>" disabled/> 
       Sitacui&oacute;n de Alquiler: <input type="text" id="situacionAlquiler" name="situacionAlquiler" size="54" value="" disabled/></td>
     </tr>
     <!--<td># Activo: <input type="text" id="numero_activo" name="numero_activo"/></td>-->
	<? echo "|".$field['Activo'];
}
//// ---------------------------------------------------------------------
//// 			FUNCION PARA INSERTAR LINEAS EN CATEGORIAS NUEVA 
//// ---------------------------------------------------------------------
if ($accion == "insertarLineaTipoTransaccion") {
connect();?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_sub');" id="sub_<?=$nrodetalle?>">
    <? /// CATEGORIA DEPRECIACION
	$sb="select * from af_categoriadeprec";
	$qb=mysql_query($sb) or die ($sb.mysql_error());
	$rb=mysql_num_rows($qb);
    ?>
    <td>
      <select id="select2" name="select2" class="selectSma">
      <option value=''></option>
       <?
        if($rb!=0){
		  for($j=0;$j<$rb;$j++){
		    $fb=mysql_fetch_array($qb); 
		?>
          <option value="<?=$fb['CodCategoria'];?>"><?=$fb['CodCategoria'].' - '.$fb['DescripcionLocal'] ;?></option>
		<? }}  ?>
      </select>
    </td>
	<?
    $sa = "select * from ac_contabilidades";
    $qa = mysql_query($sa) or die ($sa.mysql_error());
    $ra = mysql_num_rows($qa);
    ?>
	<td>
		<select name="select1" id="select1" class="selectSma">
        <option value=''></option>
        <?
         if($ra!=0){
           for($i=0;$i<$ra;$i++){
            $fa = mysql_fetch_array($qa); 
        ?>
			<option value="<?=$fa['CodContabilidad']?>"><?=$fa['Descripcion']?></option>
        <? }}?>  
		</select>	
	</td>
    <td><input type="text" id="secuencia" name="secuencia" size="3" style="text-align:center" value="<?=$_POST['contador'];?>"/></td>
	<td><input type="text" name="descripcion" id="descripcion" size="100"/></td>
    <td><input type="text" name="cuenta" id="cuenta_<?=$contador;?>" size="50" onclick="asumoInsert(this.id);"/></td>
    <td><select name="select3" id="select3">
          <option value=''></option>
          <option value='+'>+</option>
          <option value='-'>-</option>
		</select></td>
    <?
     $sc = "select * from mastmiscelaneosdet where CodMaestro='CML'";
	 $qc = mysql_query($sc) or die ($sc.mysql_error());
	 $rc = mysql_num_rows($qc);
	?>
    <!--<td><select id="select3" name="select3" class="selectSma">
        <option value=''></option>
        <?
         if($rc!=0){
		   for($k=0;$k<$rc;$k++){
			   $fc = mysql_fetch_array($qc);
		     echo" <option value='".$fc['CodDetalle']."'>".$fc['Descripcion']."</option>"; 
		   }
		 }
		?>
        </select></td>-->
        </tr>
	<?
}
//// ----------------------------------------------------------------------
//// 				GUARDAR REGISTRO - MAESTROS DE TIPO TRANSACCIONES
//// ----------------------------------------------------------------------
if($accion=="guardarTipoTransacciones"){
  connect();
  $sql="SELECT * FROM 
                      af_tipotransaccion 
                WHERE 
				      TipoTransaccion='".$_POST['TipoTransaccion']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $row=mysql_num_rows($qry);
 if($row==0){
  $insert="INSERT INTO af_tipotransaccion(TipoTransaccion,
                                          FlagAltaBaja,
										  Descripcion,
										  TipoVoucher,
										  Estado,
										  TransaccionesdelSistemaFlag,
										  UltimoUsuario,
										  UltimaFechaModif) 
								 VALUES ('".$TipoTransaccion."',
										 '".$FlagAltaBaja."',
										 '".$Descripcion."',
										 '".$TipoVoucher."',
										 '".$Estado."',
										 '".$flagTranSistema."',
										 '".$_SESSION['USUARIO_ACTUAL']."',
										 '".date("Y-m-d H:i:s")."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
   
   $linea = split(";", $detalles);
	  foreach ($linea as $registro) {
		//list($Categoria, $Contabilidad, $Secuencia, $Descripcion, $CuentaContable, $SignoFlag, $CampoLocal) = SPLIT( '[|]', $registro);
		list($Categoria, $Contabilidad, $Secuencia, $Descripcion, $CuentaContable, $SignoFlag) = SPLIT( '[|]', $registro);
		//// Consulta para determinar la secuencia del registro
		/*$sc = "select max(Secuencia) from af_tipotranscuenta where TipoTransaccion='".$TipoTransaccion."'";
		$qc = mysql_query($sc) or die ($sc.mysql_error());
		$fc = mysql_fetch_array($qc);
		$Secuencia = (int) ($fc[0]+1);
        $Secuencia = (string) str_repeat("0",3-strlen($Secuencia)).$Secuencia; */
		
		$sqlin="INSERT INTO af_tipotranscuenta(TipoTransaccion,
		                                       Categoria,
											   Contabilidad,
											   Secuencia,
											   Descripcion,
											   CuentaContable,
											   SignoFlag,
											   UltimoUsuario,
											   UltimaFechaModif) 
									   VALUES ('".$TipoTransaccion."',
									           '$Categoria',
									           '$Contabilidad',
											   '$Secuencia',
											   '$Descripcion',
											   '$CuentaContable',
											   '$SignoFlag',
											   '".$_SESSION['USUARIO_ACTUAL']."',
											   '".date("Y-m-d H:i:s")."')";
       $qryin=mysql_query($sqlin) or die ($sqlin.mysql_error());
	  }
 }else{
   echo"¡CODIGO TRANSACCION YA EXISTE¡";
 }
}
//// ----------------------------------------------------------------------
//// ----------------- EDITAR REGISTRO - MAESTROS DE TIPO TRANSACCIONES
if($accion=="editarTipoTransacciones"){
connect();	
$sup = "update af_tipotransaccion set FlagAltaBaja = '".$FlagAltaBaja."',
									  Descripcion = '".$Descripcion."',
									  TipoVoucher ='".$TipoVoucher."',
									  Estado = '".$Estado."',
									  TransaccionesdelSistemaFlag= '".$flagTranSistema."',
									  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
									  UltimaFechaModif = '".date("Y-m-d H:i:s")."' 
								 where 
								      TipoTransaccion = '".$TipoTransaccion."'";
$qup = mysql_query($sup) or die ($sup.mysql_error());

$linea = split(";", $detalles);
	  foreach ($linea as $registro) {
	  list($Categoria, $Contabilidad, $Secuencia, $Descripcion, $CuentaContable, $SignoFlag) = split( '[|]', $registro);
	  //echo $registro;
	  
	  $scon = "select * from af_tipotranscuenta where TipoTransaccion='".$TipoTransaccion."' and Secuencia='$Secuencia'";
	  $qcon = mysql_query($scon) or die ($scon.mysql_error());
	  $rcon = mysql_num_rows($qcon);
	  
	  if($rcon!=0){
	    $sup02="update af_tipotranscuenta set Categoria='$Categoria',
									   		  Contabilidad='$Contabilidad',
									          Descripcion='$Descripcion',
											  CuentaContable='$CuentaContable',
											  SignoFlag='$SignoFlag',
											  UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
											  UltimaFechaModif='".date("Y-m-d H:i:s")."' 
							            where 
										      TipoTransaccion='$TipoTransaccion' and 
											  Secuencia = '$Secuencia'"; 
	     $qup02=mysql_query($sup02) or die ($sup02.mysql_error());
	  }else{
         $sqlin="INSERT INTO af_tipotranscuenta(TipoTransaccion,
		                                       Categoria,
											   Contabilidad,
											   Secuencia,
											   Descripcion,
											   CuentaContable,
											   SignoFlag,
											   UltimoUsuario,
											   UltimaFechaModif) 
									   VALUES ('".$TipoTransaccion."',
									           '$Categoria',
									           '$Contabilidad',
											   '$Secuencia',
											   '$Descripcion',
											   '$CuentaContable',
											   '$SignoFlag',
											   '".$_SESSION['USUARIO_ACTUAL']."',
											   '".date("Y-m-d H:i:s")."')"; 
       $qryin=mysql_query($sqlin) or die ($sqlin.mysql_error());
       
	  }
	  }
}
//// ----------------------------------------------------------------------
//// ----------------- GUARDAR NUEVO REGISTRO PUBLICACION 20
if($accion=='guardarNuevoPublicacion20'){ 
  connect();
  $cod = 0;
	  list($codClasificacion, $descripcion01) = split('[-]', $_POST['codigo2']);
	  if($nivel=='1'){
	    $sql = "select max(CodClasificacion) from af_clasificacionactivo20 where Nivel = '".$nivel."'"; 
	    $qry = mysql_query($sql)  or die ($sql.mysql_error());
	    $field = mysql_fetch_array($qry);
		$cod_clasficiacion20 = (int) ($field[0] + 1);
	    $cod_clasficiacion20 = (string) str_repeat("0",2-strlen($cod_clasficiacion20)).$cod_clasficiacion20;
	  }else
	    if($nivel=='2'){
			 $sql = "select 
			                CodClasificacion,
							Descripcion 
					    from 
						    af_clasificacionactivo20
						where 
						    CodClasificacion =(select max(CodClasificacion) from af_clasificacionactivo20 where CodClasificacion like '$codClasificacion%' and Nivel='2')"; 
	         $qry = mysql_query($sql)  or die ($sql.mysql_error());
			 $row = mysql_num_rows($qry);
			 if($row!=0){ 
			    $field = mysql_fetch_array($qry);
			    $cod = substr($field[0], -2); //echo 'cod=  '.$cod;    /// Cola 
			    $cod2 = substr($field[0], 0, -2); //echo 'cod2=  '.$cod2; /// Punta  
			    if($cod<'99'){
			      $cod_clasficiacion20 = (int)($cod+1);
			      $cod_clasficiacion20 = (string) str_repeat('0', 2-strlen($cod_clasficiacion20)).$cod_clasficiacion20;
				  $cod_clasficiacion20 = $cod2.''.$cod_clasficiacion20;
			    }else echo"!NO PUEDE SER INGRESADO POR SUPERAR EL LIMITE DE REGISTRO POR NIVEL!";
			 }else{
			    $cod_clasficiacion20 = (int)($cod+1);
			    $cod_clasficiacion20 = (string) str_repeat('0', 2-strlen($cod_clasficiacion20)).$cod_clasficiacion20;
				$cod_clasficiacion20 = $codClasificacion.''.$cod_clasficiacion20;
			 }
	  }else
	    if($nivel=='3'){ 
			 $sql = "select 
			                CodClasificacion,
							descripcion
					   from 
					        af_clasificacionactivo20 
					   where 
					        CodClasificacion = (select max(CodClasificacion) from af_clasificacionactivo20 where CodClasificacion like '$codClasificacion%' and Nivel='3')";
	         $qry = mysql_query($sql)  or die ($sql.mysql_error());
			 $row = mysql_num_rows($qry); 
			  if($row!=0){
				 $field = mysql_fetch_array($qry);
				 $cod = substr($field[0], -3);
			     $cod2 = substr($field[0], 0, -3);
				 if($cod<'999'){
				   $cod_clasficiacion20 = (int)($cod+1);
			       $cod_clasficiacion20 = (string) str_repeat('0', 3-strlen($cod_clasficiacion20)).$cod_clasficiacion20;
				   $cod_clasficiacion20 = $cod2.''.$cod_clasficiacion20;
			     }else echo"!NO PUEDE SER INGRESADO POR SUPERAR EL LIMITE DE REGISTRO POR NIVEL!";
			   }else{
			     $cod_clasficiacion20 = (int)($cod+1);
			     $cod_clasficiacion20 = (string) str_repeat('0', 3-strlen($cod_clasficiacion20)).$cod_clasficiacion20;
				 $cod_clasficiacion20 = $codClasificacion.''.$cod_clasficiacion20;
			   }
	    }else
		 if($nivel=='4'){ 
			 $sql = "select 
			                CodClasificacion,
							Descripcion
					   from 
					        af_clasificacionactivo20 
					   where 
					        CodClasificacion = (select max(CodClasificacion) from af_clasificacionactivo20 where CodClasificacion like '$codClasificacion%' and Nivel='4')";
	         $qry = mysql_query($sql)  or die ($sql.mysql_error());
			 $row = mysql_num_rows($qry);
			 if($row!=0){
				 $field = mysql_fetch_array($qry);
				 $cod = substr($field[0], -3);     /// Cola
			     $cod2 = substr($field[0], 0, -3); /// Punta 
				 if($cod<'999'){
				   $cod_clasficiacion20 = (int)($cod+1);
			       $cod_clasficiacion20 = (string) str_repeat('0', 3-strlen($cod_clasficiacion20)).$cod_clasficiacion20;
				   $cod_clasficiacion20 = $cod2.''.$cod_clasficiacion20;
			     }else echo"!NO PUEDE SER INGRESADO POR SUPERAR EL LIMITE DE REGISTRO POR NIVEL!";
			  }else{
			     $cod_clasficiacion20 = (int)($cod+1);
			     $cod_clasficiacion20 = (string) str_repeat('0', 3-strlen($cod_clasficiacion20)).$cod_clasficiacion20;
				 $cod_clasficiacion20 = $codClasificacion.''.$cod_clasficiacion20;
			  }
		 }
	     if ($cod_clasficiacion20!=""){
			 $s_insert = "INSERT INTO af_clasificacionactivo20 (CodClasificacion,
															 Descripcion,
															 Nivel,
															 Estado,
															 UltimoUsuario,
															 UltimaFecha)
													VALUES ('$cod_clasficiacion20',
															'".utf8_decode($descripcion)."',
															'$nivel',
															'".$status."',
															'".$_SESSION['USUARIO_ACTUAL']."',
															'".date("Y-m-d H:i:s")."')"; 
			 $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
		 }
}
//// ----------------------------------------------------------------------
//// ----------------- EDITAR REGISTRO PUBLICACION 20
if($accion == "EditarPublicacion20"){
 connect();	
		$sql = "UPDATE af_clasificacionactivo20 SET   Descripcion = '".utf8_decode($descripcion)."',
													  Estado = '".$status."',
													  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
													  UltimaFecha = '".date("Y-m-d H:i:s")."'
												WHERE 
													  CodClasificacion = '".$codigo."' AND Nivel = '".$nivel."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
}
//// ----------------------------------------------------------------------
//// ----------------- ELIMINAR REGISTRO PUBLICACION 20
//// ----------------------------------------------------------------------
if($accion=='eliminarClasificacion20'){
 connect();
 	
 $scon = "select * from af_clasificacionactivo20 where CodClasificacion='".$codigo."' ";
 $qcon = mysql_query($scon) or die ($scon.mysql_error());
 $fcon = mysql_fetch_array($qcon);
 
 if($fcon['Nivel']=='1') $cola = '01';
 else if($fcon['Nivel']=='2') $cola = '001';
 else if($fcon['Nivel']=='3') $cola = '001';
 else if($fcon['Nivel']=='4') $cola = '001';
 
 $codigo = $fcon['CodClasificacion'].''.$cola;
 
 /// Consultando si posee nivel siguiente
 $snivel = "select * from af_clasificacionactivo20 where CodClasificacion='$codigo'";
 $qnivel = mysql_query($snivel) or die ($snivel.mysql_error());
 $rnivel = mysql_num_rows($qnivel);
 
 if($rnivel!=0){
	$fnivel=mysql_fetch_array($qnivel);
	echo"!NO PUEDE SER ELIMINADO,REVISE CLASIFICACION: ".$fnivel['CodClasificacion'];
 }else{
   $sd = "delete from af_clasificacionactivo20 where CodClasificacion='".$fcon['CodClasificacion']."'";
   $qd= mysql_query($sd) or die ($sd.mysql_error()); 
 }
}
//// ----------------------------------------------------------------------
//// ----------------- GUARDAR PROCESO NUEVA TRANSACCION BAJA
//// ----------------------------------------------------------------------
if($accion=='guardarTransaccionBaja'){
connect();
	
	if($Modulo=='Modificar'){
	  $sql_a = "update 
	  				  af_transaccionbaja 
				   set 
				      Activo='".$Activo."', Organismo = '".$Organismo."', Dependencia = '".$Dependencia."', TipoTransaccion = '".$TipoTransaccion."',
					  CentroCosto='".$CentroCosto."', ContabilizadoFlag='".$ContabilizadoFlag."', Responsable='".$Responsable."',
					  ConceptoMovimiento='".$ConceptoMovimiento."', CodigoInterno='".$CodigoInterno."', Categoria='".$Categoria."', Ubicacion='".$Ubicacion."',
					  Comentario='".$Comentario."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFechaModif='".date("Y-m-d H:i:s")."',
					  Resolucion='".$Resolucion."', FacturaNumero='".$FacturaNumero."', LocalIngreso='".$MontoLocal."', MotivoTraslado='".$MotivoTraslado."'  
				 where 
				      CodTransaccionBaja = '".$CodTransaccionBaja."' and 
					  Organismo = '".$Organismo."' and 
					  Activo = '".$Activo."'"; 
	  $qry_a = mysql_query($sql_a) or die ($sql_a.mysql_error());
	  
	  $sql_del = "delete from af_transaccionbajacuenta where CodTransaccionBaja='".$CodTransaccionBaja."' and Activo='".$Activo."'"; //echo $sql_del;
	  $qry_del = mysql_query($sql_del) or die ($sql_del.mysql_error());
	  
	  /// Consulto para verificar el tipo de transacción seleccionado
	  $s_tiptrans = "select 
							 a.TipoTransaccion,
							 b.Contabilidad, 
							 b.Secuencia,
							 b.Descripcion,
							 b.CuentaContable,
							 b.SignoFlag
					  from   
							 af_tipotransaccion a 
							 inner join af_tipotranscuenta b on (b.TipoTransaccion = a.TipoTransaccion) 
					  where  
							a.TipoTransaccion = '".$TipoTransaccion."' 
					order by 
							Contabilidad"; //echo $s_tiptrans; 
	   $q_tiptrans = mysql_query($s_tiptrans) or die ($s_tiptrans.mysql_error());
	   $r_tiptrans = mysql_num_rows($q_tiptrans);
	  
	  if($r_tiptrans!=0){
	    for($i=0; $i<$r_tiptrans; $i++){ 
		   $f_tiptrans = mysql_fetch_array($q_tiptrans);
		   if($f_tiptrans['SignoFlag']=='-')$signo='-';else $signo="";
		   $monto = $signo.''.$MontoLocal; 
		   list($d, $m, $Y) = split('[-]', $Fecha); $fechaCreado = $Y.'-'.$m.'-'.$d;
		   $sin02 = "insert into af_transaccionbajacuenta(Activo, Contabilidad, Secuencia, CuentaContable,
														  Descripcion, MontoLocal, MontoALocal, Fecha, 
														  UltimoUsuario, UltimaFechaModif, CodTransaccionBaja) 
												   values('".$Activo."','".$f_tiptrans['Contabilidad']."','".$f_tiptrans['Secuencia']."','".$f_tiptrans['CuentaContable']."',
														  '".$f_tiptrans['Descripcion']."','$monto', '$monto', '$fechaCreado', 
														  '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."', '".$CodTransaccionBaja."')"; //echo $sin02;
			$qin02 = mysql_query($sin02) or die ($sin02.mysql_error());
	   }
     }
	  				  
	
	}
	elseif($Modulo=='Nuevo'){
		
	$sql_a = "select max(CodTransaccionBaja) from af_transaccionbaja where Organismo='".$Organismo."'";
	$qry_a = mysql_query($sql_a) or die ($sql_a.mysql_error());	
	$row_a = mysql_num_rows($qry_a);
	if($row_a!=0) $field_a=mysql_fetch_array($qry_a);
	
	$codtransaccionbaja = (int) ($field_a[0]+1);
    $codtransaccionbaja = (string) str_repeat("0",6-strlen($codtransaccionbaja)).$codtransaccionbaja;
	
	$sin = "insert into af_transaccionbaja(Activo, Organismo, TipoTransaccion, Dependencia,
									  Fecha, CentroCosto, ContabilizadoFlag, Responsable,
									  ConceptoMovimiento, CodigoInterno, Categoria, Estado, 
									  Ubicacion, Comentario, Periodo, UltimoUsuario, UltimaFechaModif, 
									  Resolucion, FacturaNumero, LocalIngreso, CodTransaccionBaja, 
									  FlagExterno, MotivoTraslado, PreparadoPor, FechaBaja) 
							   values('".$Activo."','".$Organismo."','".$TipoTransaccion."','".$Dependencia."',
									  '".date("Y-m-d")."','".$CentroCosto."','".$ContabilizadoFlag."','".$Responsable."',
									  '".$ConceptoMovimiento."','".$CodigoInterno."','".$Categoria."','PR',
									  '".$Ubicacion."','".$Comentario."', '".date("Y-m")."', '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."',
									  '".$Resolucion."', '".$FacturaNumero."', '".$MontoLocal."', '$codtransaccionbaja', 
									  '".$FlagExterno."', '".$MotivoTraslado."', '".$PreparadoPor."', '".$FechaBaja."')"; 
	$qin = mysql_query($sin) or die ($sin.mysql_error());
  
   /// Consulto para verificar el tipo de transacción seleccionado
   $s_tiptrans = "select 
     					 a.TipoTransaccion,
						 b.Contabilidad, 
						 b.Secuencia,
						 b.Descripcion,
						 b.CuentaContable,
						 b.SignoFlag
				  from   
				  	     af_tipotransaccion a 
						 inner join af_tipotranscuenta b on (b.TipoTransaccion = a.TipoTransaccion) 
				  where  
				  		a.TipoTransaccion = '".$TipoTransaccion."' 
				order by 
				        Contabilidad"; //echo $s_tiptrans; 
   $q_tiptrans = mysql_query($s_tiptrans) or die ($s_tiptrans.mysql_error());
   $r_tiptrans = mysql_num_rows($q_tiptrans);
   
   if($r_tiptrans!=0){
	  for($i=0; $i<$r_tiptrans; $i++){ 
	   $f_tiptrans = mysql_fetch_array($q_tiptrans);
	   if($f_tiptrans['SignoFlag']=='-')$signo='-';else $signo="";
	   $monto = $signo.''.$MontoLocal; 
   	   $sin02 = "insert into af_transaccionbajacuenta(Activo, Contabilidad, Secuencia, CuentaContable,
   												      Descripcion, MontoLocal, MontoALocal, Fecha, 
													  UltimoUsuario, UltimaFechaModif, CodTransaccionBaja) 
   										   	   values('".$Activo."','".$f_tiptrans['Contabilidad']."','".$f_tiptrans['Secuencia']."','".$f_tiptrans['CuentaContable']."',
										              '".$f_tiptrans['Descripcion']."','$monto', '$monto', '".date("Y-m-d")."', 
													  '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."', '$codtransaccionbaja')"; //echo $sin02;
   		$qin02 = mysql_query($sin02) or die ($sin02.mysql_error());
	 }
   }}
    elseif($Modulo=='Revisar'){
	  $sql_a = "update 
	  				  af_transaccionbaja 
				   set 
				      Estado='RV',
					  RevisadoPor='".$Usuario."',
					  FechaRevision='".date("Y-m-d")."',
					  UltimoUsuario='".$Usuario."',
					  UltimaFechaModif='".date("Y-m-d H:i:s")."'  
				 where 
				      CodTransaccionBaja = '".$CodTransaccionBaja."' and 
					  Organismo = '".$Organismo."' and 
					  Activo = '".$Activo."'"; 
	  $qry_a = mysql_query($sql_a) or die ($sql_a.mysql_error());
	}
	elseif($Modulo=='Aprobar'){
	  /// actualizar transaccion baja
	  $sql_a = "update 
	  				  af_transaccionbaja 
				   set 
				      Estado='AP',
					  AprobadoPor='".$Usuario."',
					  FechaAprobacion='".date("Y-m-d")."',
					  UltimoUsuario='".$Usuario."',
					  UltimaFechaModif='".date("Y-m-d H:i:s")."'  
				 where 
				      CodTransaccionBaja = '".$CodTransaccionBaja."' and 
					  Organismo = '".$Organismo."' and 
					  Activo = '".$Activo."'"; //echo $sql_a; 
	  $qry_a = mysql_query($sql_a) or die ($sql_a.mysql_error());
	  
	  // insertar transaccion baja en historico
	  $s_secuencia = "select max(secuencia) from af_historicotransaccion where Activo='".$Activo."' and CodOrganismo='".$Organismo."'";
	  $q_secuencia = mysql_query($s_secuencia) or die ($s_secuencia.mysql_error());
	  $r_secuencia = mysql_num_rows($q_secuencia);
	  if($r_secuencia!=0)$f_secuencia = mysql_fetch_array($q_secuencia);
	  
	  $nro_secuencia = (int) ($f_secuencia[0]+1);
      $nro_secuencia = (string) str_repeat("0",6-strlen($nro_secuencia)).$nro_secuencia;
	  // generando nro secuencia
	  list($a, $b, $c) = split('[-]',$FechaIngreso);
	  $f_ingreso = $a.'-'.$b.'-'.$c;
	  $p_ingreso = $a.'-'.$b;
	  list($a, $b, $c) = split('[-]',$FechaBaja);
	  $PeriodoBaja = $a.'-'.$b;
	  list($a, $b, $c) = split('[-]',$Fecha);
	  $PeriodoTransaccion = $a.'-'.$b;
	  
	  $s_b = "insert into af_historicotransaccion (CodOrganismo, Activo, Secuencia, CodDependencia, 
												  CentroCosto, CodigoInterno, SituacionActivo, CodTipoMovimiento,
												  Ubicacion, InternoExternoFlag, MotivoTraslado, FechaIngreso,
												  FechaBaja, FechaTransaccion, PeriodoIngreso, PeriodoTransaccion,
												  PeriodoBaja, NumeroOrden, OrdenSecuencia, MontoActivo, UltimoUsuario
												  UltimaFechaModif, NumeroMovimiento)
											value('".$Organismo."', '".$Activo."', '".$nro_secuencia."', '".$Dependencia."',
												  '".$CentroCosto."', '".$CodigoInterno."', 'DE', '".$ConceptoMovimiento."',
												  '".$Ubicacion."', '".$FlagExterno."', '".$MotivoTraslado."', '".$f_ingreso."',
												  '".$FechaBaja."', '".$Fecha."', '".$p_ingreso."', '".$PeriodoTransaccion."',
												  '".$PeriodoBaja."', '".$NumeroOrden."', '".$OrdenSecuencia."', '".$_SESSION['USUARIO_ACTUAL']."',
												  '".date("Y-m-d H:i:s")."', '".$CodTransaccionBaja."')";
	  $q_b = mysql_query($s_b) or die ($s_b.mysql_error());
	  
	  /// Actualizo campos en af_activo
	  $s_c = "update af_activo set SituacionActivo='DE' where  Activo = '".$Activo."' and CodOrganismo='".$Organismo."' ";
	  $q_c = mysql_query($s_c) or die ($s_c.mysql_error());
	  
	}
	elseif($Modulo=='Anular'){
	  if($estado=='RV') $new_est ='PR';
	  elseif($estado=='PR')$new_est ='AN';
	  $sql_a = "update 
	  				  af_transaccionbaja 
				   set 
				      Estado='$new_est',
					  AnuladoPor='".$Usuario."',
					  FechaAnulado='".date("Y-m-d")."',
					  MotivoAnulado='".$motivo_anular."',
					  UltimoUsuario='".$Usuario."',
					  UltimaFechaModif='".date("Y-m-d H:i:s")."'  
				 where 
				      CodTransaccionBaja = '".$CodTransaccionBaja."' and 
					  Organismo = '".$Organismo."' and 
					  Activo = '".$Activo."'"; ///echo $sql_a; 
	  $qry_a = mysql_query($sql_a) or die ($sql_a.mysql_error());
	}
}
//// ----------------------------------------------------------------------
//// ----------------- ANULAR TRANSACCION BAJA
//// ----------------------------------------------------------------------
if($accion=="anularRegistroTransaccionActivo"){
 $sql = "select Estado from af_transaccionbaja where Activo='".$codigo."'";
 $qry = mysql_query($sql) or die ($sql.mysql_error());
 $row = mysql_num_rows($qry);
 
 if($row!=0) $field = mysql_fetch_array($qry);
 if($field['Estado']=="AP"){
    $sup = "update af_transaccionbaja set Estado='PR' where Activo='".$codigo."'";
	$qup = mysql_query($sup) or die ($sup.mysql_error());
	
	$sth = "delete from af_historicotransaccion where Activo='".$codigo."' and Secuencia=(select max(Secuencia) from af_historicotransaccion where Activo='".$codigo."')";
	$qth = mysql_query($sth) or die ($sth.mysql_error());
	
 }else{
     $sup = "update af_transaccionbaja set Estado='PR' where Activo='".$codigo."'";
	 $qup = mysql_query($sup) or die ($sup.mysql_error());
 }
}
//// ----------------------------------------------------------------------
//// FUNCION PARA MOSTRAR INFORMACION TRANSACCIONES EN  LISTA ACTIVOS - AGREGAR
//// ----------------------------------------------------------------------
if ($accion == "insertarDatos_1") {
connect();
echo"<table width='890' border='0' align='center'>";
$sql = "select 
			  a.* ,
			  b.Descripcion as DescpContabilidad,
			  c.Descripcion as Descrip
		  from 
		      af_tipotranscuenta a 
			  inner join ac_contabilidades b on (b.CodContabilidad=a.Contabilidad) 
			  inner join ac_mastplancuenta20 c on (c.CodCuenta=a.CuentaContable)
		 where 
		      a.TipoTransaccion='".$tipobaja."' 
		order by a.Contabilidad, a.Secuencia";
$qry = mysql_query($sql) or die ($sql.mysql_error());
$row = mysql_num_rows($qry);

if($row!=0){
 for($i=0; $i<$row; $i++){
  $field = mysql_fetch_array($qry);
  if($field['SignoFlag']=='')$SignoFlag='+';else $SignoFlag='-';
  if($field['Contabilidad']=='F')$tabla='ac_mastplancuenta20'; else $tabla='ac_mastplancuenta'; 
  
  $sql_b = "select * from $tabla where CodCuenta='".$field['CuentaContable']."'";
  $qry_b = mysql_query($sql_b) or die ($sql_b.mysql_error());
  $row_b = mysql_num_rows($qry_b);
  if($row_b!=0)$field_b=mysql_fetch_array($qry_b);
  
  if($contabilidad!=$field['Contabilidad']){
  echo"<tr >
         <td align='center' width='20' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;background-color:#C0C0C0;border-style:outset;'><b>".$field['Contabilidad']."</b></td>
		 <td align='center' width='211' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;background-color:#C0C0C0;border-style:outset;'><b>".$field['DescpContabilidad']."</b></td>
      </tr>";
  $contabilidad =$field['Contabilidad'];
  }
  echo"<tr>
    <td width='20' align='right' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;'>".$field['Secuencia']."</td>
	<td width='211' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;'>".$field['CuentaContable']." <b>- ".$field_b['Descripcion']."</b></td>
	<td width='300' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;'>".$field['Descripcion']."</td>
	<td width='40' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;text-align:right;'>".$monto."</td>
	<td width='35' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;' align='center'>".$SignoFlag."</td>
  </tr>";
 }
}

echo"</table>";
}
//// ----------------------------------------------------------------------
//// FUNCION PARA MOSTRAR INFORMACION TRANSACCIONES EN  BAJA ACTIVOS NUEVA TRANSACCION
//// ----------------------------------------------------------------------
if ($accion == "insertarDatos_2") {
connect();
echo"<table width='820' border='0'>";
$sql = "select 
			  a.* ,
			  b.Descripcion as DescpContabilidad
		  from 
		      af_tipotranscuenta a 
			  inner join ac_contabilidades b on (b.CodContabilidad=a.Contabilidad)
		 where 
		      a.TipoTransaccion='".$tipobaja."' 
		order by a.Contabilidad, a.Secuencia"; //echo $sql;
$qry = mysql_query($sql) or die ($sql.mysql_error());
$row = mysql_num_rows($qry);

if($row!=0){
 for($i=0; $i<$row; $i++){
  $field = mysql_fetch_array($qry);
  if($field['SignoFlag']=='')$SignoFlag='+';else $SignoFlag='-';
  if($field['Contabilidad']=='F')$tabla='ac_mastplancuenta20'; else $tabla='ac_mastplancuenta'; 
  
  $sql_b = "select * from $tabla where CodCuenta='".$field['CuentaContable']."'";
  $qry_b = mysql_query($sql_b) or die ($sql_b.mysql_error());
  $row_b = mysql_num_rows($qry_b);
  if($row_b!=0)$field_b=mysql_fetch_array($qry_b);
  
  if($contabilidad!=$field['Contabilidad']){
  echo"<tr >
         <td align='center' width='36' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;background-color:#C0C0C0;border-style:outset;'><b>".$field['Contabilidad']."</b></td>
		 <td align='center' width='150' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;background-color:#C0C0C0;border-style:outset;'><b>".$field['DescpContabilidad']."</b></td>
		 </tr>";
  $contabilidad =$field['Contabilidad'];
  }
  echo"<tr>
    <td align='right' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;'>".$field['Secuencia']."</td>
	<td style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;'>".$field['CuentaContable']." <b>- ".$field_b['Descripcion']."</b></td>
	<td width='370' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;'>".$field['Descripcion']."</td>
	<td width='70' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;text-align:right;'>$SignoFlag$monto</td>
  </tr>";
 }
}

echo"</table>";
}
//// ----------------------------------------------------------------------
////  GUARDAR REGISTRO - AGRUPAR/CONSOLIDAR ACTIVOS - MENÚ OTROS
//// ----------------------------------------------------------------------
if($accion == "grabarAgrupacionConsolidacion"){
  connect();
     
   $linea = split(";", $detalles);
	  foreach ($linea as $registro) {
		 $supdate = " update af_activo set ActivoConsolidado ='$activo' where Activo='$registro' ";
         $qupdate = mysql_query($supdate) or die ($supdate.mysql_error());
 }
}
//// ----------------------------------------------------------------------
////            ANULAR REGISTRO DE MOVIMIENTO DE ACTIVO
//// ----------------------------------------------------------------------
if($accion=="AnularMovimientoActivo"){ 
   list($Organismo, $MovimientoNumero, $Estado) = split('[|]', $codigo);
   $sql_a = "select * from af_movimientos where Organismo='$Organismo' and MovimientoNumero='$MovimientoNumero' and Estado='$Estado'";/// echo $sql_a;
   $qry_a = mysql_query($sql_a) or die ($sql_a.mysql_error());
   $row_a = mysql_num_rows($qry_a);
   
   /// Anmteriormente
   /*if($row_a!=0){
     $field_a = mysql_fetch_array($qry_a);
	 if($field_a['Estado']=='AP') echo "¡Este Registro no puede ser anulado, se encuentra Aprobado¡";
	 else{
		 if($field_a['Estado']=='RV') $valor= 'PR';
		 elseif($field_a['Estado']=='PR') $valor= 'AN';
		 $sql_b = "update  af_movimientos set Estado='$valor' where Organismo='$Organismo' and MovimientoNumero='$MovimientoNumero'";
		 $qry_b = mysql_query($sql_b) or die ($sql_b.mysql_error());
		 } 
   } */
   if($row_a!=0){
     $field_a = mysql_fetch_array($qry_a);
	 $sql_b = "update  af_movimientos set Estado='PR' where Organismo='$Organismo' and MovimientoNumero='$MovimientoNumero'";
	 $qry_b = mysql_query($sql_b) or die ($sql_b.mysql_error());
	 
	 // Actualizacion de datos en af_activo
	 //$sactivo = "update af_activo set ";
	 
	 
	 
	 if($field_a['Estado']=='AP') echo "¡Este Registro no puede ser anulado, se encuentra Aprobado¡";
	 else{
		 if($field_a['Estado']=='RV') $valor= 'PR';
		 elseif($field_a['Estado']=='PR') $valor= 'AN';
		 $sql_b = "update  af_movimientos set Estado='$valor' where Organismo='$Organismo' and MovimientoNumero='$MovimientoNumero'";
		 $qry_b = mysql_query($sql_b) or die ($sql_b.mysql_error());
		 } 
   }
}
//// ----------------------------------------------------------------------
////            ANULAR REGISTRO DE MOVIMIENTO DE ACTIVO
//// ----------------------------------------------------------------------
if($accion=="tab2"){ 
list($organismo, $activo, $codtransaccionbaja)=split('[|]', $valor);
//echo $organismo, $activo, $codtransaccionbaja;
?>
<thead>
	<tr class="trListaHead">
		<th width="40">Cuenta</th>
        <th width="40">Contabilidad</th>
        <th width="40">Secuencia</th>
		<th width="250">Descripcion</th>
        <th width="40">MontoLocal</th>
        <th width="40">Fecha</th>
	</tr>
</thead>
<?
$s_a = "select 
			  a.*,
			  b.Descripcion as DescpContabilidad 
		 from 
		     af_transaccionbajacuenta a 
			 inner join ac_contabilidades b on (b.Codcontabilidad = a.Contabilidad) 
		where 
		     a.Activo='$activo' and 
			 a.CodTransaccionBaja='$codtransaccionbaja' and 
			 a.Contabilidad='".$cont."'";
$q_a = mysql_query($s_a) or die ($s_a.mysql_error());
$r_a = mysql_num_rows($q_a);
if($r_a!=0)
  for($i=0; $i<$r_a; $i++){
	 $f_a=mysql_fetch_array($q_a);
	 $fecha = cambiarfecha($f_a["Fecha"]);
	 
    echo "
	 <tr class='trListaBody' onclick='mClk(this,\"registro\");'  id='$id'>
	    <td align='center'>".$f_a['CuentaContable']."</td>
		<td align='center'>".$f_a['DescpContabilidad']."</td>
		<td align='center'>".$f_a['Secuencia']."</td>
		<td align='left'>".$f_a['Descripcion']."</td>
		<td align='right'>".number_format($f_a['MontoLocal'],2,',','.')."</td>
		<td align='center'>$fecha</td>
	 </tr>";	
	}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalLista($ra);
		totalLotes($registro, $rows, ".$_GET['limit'].");
	</script>";				
}
function cambiarfecha($fecha){
 list($year,$mes,$dia)=explode("-",$fecha);
 $fecha="$dia-$mes-$year";
 return $fecha;
 }
 //// ----------------------------------------------------------------------
////             GENERAR VOUCHER DE INGRESO ACTIVO
//// ----------------------------------------------------------------------
if($accion == "generarVoucherIngActivo"){
  connect(); 
   
   $Periodo="2013-06";  
   $NroVoucher = getCodigo("ac_vouchermast","NroVoucher",4,"CodOrganismo",$CodOrganismo,"Periodo",$Periodo,"CodVoucher",$CodVoucher,"CodContabilidad",$CodContabilidad);
   
   $voucherIngPub20 = $CodVoucher.'-'.$NroVoucher;
  
  //// Actualizando  AF_ACTIVO
  $sql_a = "update af_activo set VoucherIngPub20 = '$voucherIngPub20',
  								 PeriodoVoucherIng =  '".$PeriodoIngreso."'
						    where 
							     Activo='".$Activo."' and 
								 CodOrganismo='".$CodOrganismo."'";
  $qry_a = mysql_query($sql_a) or die ($sql_a.mysql_error());
  
  //// consulta tabla 
  $sql_d = "select PrefVoucherPD from mastaplicaciones where CodAplicacion='AF'" ;
  $qry_d = mysql_query($sql_d) or die ($sql_d.mysql_error());
  $row_d = mysql_num_rows($qry_d);
  if($row_d!=0) $field_d = mysql_fetch_array();
  
  //// 
  $sql_e = "select 
                   a.*,
				   b.SignoFlag
			  from 
			       af_activodistribcontable a 
				   inner join af_tipotranscuenta b on (b.TipoTransaccion=a.TipoTransaccion)and(b.CuentaContable=a.CuentaContable)
			 where 
			       a.Activo='".$Activo."'";
  $qry_e = mysql_query($sql_e) or die ($sql_e.mysql_error());
  $row_e = mysql_num_rows($qry_e);
  
  if($row_e!=0){
	  
    for($i; $i<$row_e; $i++){
       $field_e = mysql_fetch_array($qry_e);
	   $montoVActivo = $field_e['Monto'];
	  
	   $lineas+= 1;
	  
	   if($field_e['Signo']=="-")$montoVoucherActivo = '-'.$field_e['Monto'];
	   else $montoVoucherActivo = $field_e['Monto'];
	  
	   $sql_f = "select Descripcion from ac_mastplancuenta20 where CodCuenta = '".$field_e['CuentaContable']."'";
	  $qry_f = mysql_query($sql_f) or die ($sql_f.mysql_error());
	  $row_f = mysql_num_rows($qry_f);
	  if($row_f!=0) $field_f = mysql_fetch_array($qry_f);

	   // Insertando en AC_VOUCHERDET
	   $sql_c = "insert into ac_voucherdet(CodOrganismo, Periodo, Voucher, Linea,
										  CodContabilidad, CodCuenta, MontoVoucher, MontoPost,
										  CodPersona, FechaVoucher, CodCentroCosto, ReferenciaTipoDocumento,
										  ReferenciaNroDocumento, Descripcion, Estado, UltimoUsuario, UltimaFecha) 
								  values('".$CodOrganismo."', '".$Periodo."', '$voucherIngPub20', '".$field_e['Secuencia']."',
										'".$field_e['Contabilidad']."',	'".$field_e['CuentaContable']."', '$montoVoucherActivo', '$montoVoucherActivo',
										'".$CodPersona."', '".$FacturaFecha."', '".$CentroCosto."', '".$ObligacionTipoDocumento."',
										'".$ObligacionNroDocumento."', '".$field_f['Descripcion']."', 'MA', '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."')";
     $qry_c = mysql_query($sql_c) or die ($sql_c.mysql_error());
    } 
	
	if($montoVActivo<0)$montoVActivo = (-1) * $montoVActivo;
	
	// Se toma para campos Aprobado por el encagado de la dependencia "Dirección de Administración y Servicios"
	$s_a = "select 
				   a.CodOrganismo,
				   b.CodPersona 
			  from  
			       mastorganismos a 
				   inner join mastdependencias b on (a.CodOrganismo=b.CodOrganismo) 
			  where 
			       a.CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' ";
	$q_a = mysql_query($s_a) or die ($s_a.mysql_error());
	$f_a = mysql_fetch_array($q_a);
	
	$sb = "select * from usuarios where Usuario='".$_SESSION['USUARIO_ACTUAL']."'";
	$qb = mysql_query($sb) or die ($sb.mysql_error());
	$rb = mysql_num_rows($qb);
	if($rb!=0)$fb = mysql_fetch_array($qb);
	
	// Busco Numero Interno desde logistica
	$se = "select * from lg_activofijo where Activo='".$Activo."' and  CodOrganismo='".$CodOrganismo."'";
	$qe = mysql_query($se) or die ($se.mysql_error());
	$fe = mysql_fetch_array($qe);
	
	$sdep = "select * from mastdependencias where CodDependencia='0009'";
	$qdep = mysql_query($sdep) or die ($sdep.mysql_error());
	$rdep = mysql_num_rows($qdep);
	if($rdep!=0) $fdep = mysql_fetch_array($qdep);
	
	
	// Insertando en AC_VOUCHERMAST
	$sql_g = "insert into ac_vouchermast(CodOrganismo, Periodo,  Voucher,  CodContabilidad,
										  Prefijo,  NroVoucher,  CodVoucher,  CodDependencia,
										  Creditos, Debitos,  Lineas,  PreparadoPor,  
										  FechaPreparacion, AprobadoPor, FechaAprobacion, TituloVoucher, 
										  ComentariosVoucher,  FechaVoucher,  NroInterno,  FlagTransferencia, 
										  Estado, CodLibroCont,  UltimoUsuario,  UltimaFecha)
									values('".$CodOrganismo."',  '".$Periodo."',  '$voucherIngPub20',  '".$CodContabilidad."',
										   '".$CodVoucher."',  '$NroVoucher', '".$CodVoucher."', '".$DependenciaUsuario."', 
										   '$montoVActivo', '-$montoVActivo', '$lineas', '".$fb['CodPersona']."',
										   '".date("Y-m-d")."', '".$fdep['CodPersona']."', '".date("Y-m-d")."', '".$ComentariosVoucher."',
										   '".$ComentariosVoucher."', '".$FechaVoucher."', '".$fe['NroInterno']."','N',
										   'MA', '".$CodLibroCont."', '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."' )";
	  $qry_g = mysql_query($sql_g) or die ($sql_g.mysql_error());
    
  }
}
//// ----------------------------------------------------------------------
////             GENERAR VOUCHER DE BAJA DE ACTIVOS PUB 20
//// ----------------------------------------------------------------------
if($accion == "generarVoucherBajaPub20"){
  connect(); 
   
  $NroVoucher = getCodigo("ac_vouchermast","NroVoucher",4,"CodOrganismo",$CodOrganismo,"Periodo",$Periodo,"CodVoucher",$CodVoucher,"CodContabilidad",$CodContabilidad);
  
  $VoucherBajaPub20 = $CodVoucher.'-'.$NroVoucher; //echo $voucherIngPub20;
   
  //// ACTUALIZACION DE TABLAS 
  $sql_a = "update af_activo set VoucherBajaPub20 = '$VoucherBajaPub20',
  								 PeriodoBajaPub20 =  '".$Periodo."'
						    where 
							     Activo='".$Activo."' and 
								 CodOrganismo='".$CodOrganismo."'"; //echo $sql_a;
  $qry_a = mysql_query($sql_a) or die ($sql_a.mysql_error());
  
  $s_upd = "update af_transaccionbaja set ContabilizadoFlagPub20='S',
  										  PeriodoVoucherPub20 = '".date("Y-m")."',
										  VoucherPub20 = '".$VoucherBajaPub20."' 
									where 
									      CodTransaccionBaja = '".$CodTransaccionBaja."' and 
										  Activo = '".$Activo."' and 
										  Organismo = '".$CodOrganismo."'";
  $q_upd = mysql_query($s_upd) or die ($s_upd.mysql_error());
  
    
  $s_transbajacuenta = "select 
							   * 
						  from 
								af_transaccionbajacuenta 
						  where 
								CodTransaccionBaja='".$CodTransaccionBaja."' and 
								Activo='".$Activo."' and 
								Contabilidad='".$CodContabilidad."'"; 
  $q_transbajacuenta = mysql_query($s_transbajacuenta) or die ($s_transbajacuenta.mysql_error());
  $r_transbajacuenta = mysql_num_rows($q_transbajacuenta);
  if($r_transbajacuenta != 0){
   for($i=0; $i<$r_transbajacuenta; $i++){
	 $f_transbajacuenta = mysql_fetch_array($q_transbajacuenta);
	 $sql_c = "insert into 
					ac_voucherdet(CodOrganismo, Periodo, Voucher, Linea,
									  CodContabilidad, CodCuenta,  MontoVoucher, MontoPost,
									  CodPersona, FechaVoucher, CodCentroCosto,  ReferenciaTipoDocumento,
									  ReferenciaNroDocumento, Descripcion, Estado, UltimoUsuario, UltimaFecha) 
					values('".$CodOrganismo."','".$Periodo."','$VoucherBajaPub20','".$f_transbajacuenta['Secuencia']."',
						'".$CodContabilidad."','".$f_transbajacuenta['CuentaContable']."','".$f_transbajacuenta['MontoLocal']."','".$f_transbajacuenta['MontoLocal']."',
						'".$CodPersona."', '".date("Y-m-d")."', '".$CentroCosto."',	'".$ObligacionTipoDocumento."',
						'".$ObligacionNroDocumento."', '".$field_f['Descripcion']."', 'MA', '".$_SESSION['USUARIO_ACTUAL']."','".date("Y-m-d H:i:s")."')";
	 $qry_c = mysql_query($sql_c) or die ($sql_c.mysql_error());
	 
	 $nro_lineas++; 
	 if($f_transbajacuenta['MontoLocal']>0)$Creditos+=$f_transbajacuenta['MontoLocal'];
	 else $Debitos+=$f_transbajacuenta['MontoLocal'];
	 
   }
  }
	  
	  $s_trans = "select * from af_transaccionbaja where CodTransaccionBaja='".$CodTransaccionBaja."' and Activo='".$Activo."' and Organismo='".$CodOrganismo."'";
	  $q_trans = mysql_query($s_trans) or die ($s_trans.mysql_error());
	  $r_trans = mysql_num_rows($q_trans);
	  if($r_trans!=0)$f_trans = mysql_fetch_array($q_trans);
	  
	  $s_vmast = "select max(NroInterno) from ac_vouchermast where CodOrganismo='".$CodOrganismo."'";
	  $q_vmast = mysql_query($s_vmast) or die ($s_vmast.mysql_error());
	  $r_vmast = mysql_num_rows($q_vmast);
	  if($r_vmast!=0)$f_vmast= mysql_fetch_array($q_vmast);
	  $nrointerno=(int) ($f_vmast[0]+1);
	  $nrointerno=(string) str_repeat("0", 10-strlen($nrointerno)).$nrointerno;
	  
	  
	$sql_g = "insert into ac_vouchermast(CodOrganismo, Periodo,  Voucher,  CodContabilidad,
										  Prefijo,  NroVoucher,  CodVoucher,  CodDependencia,
										  Creditos, Debitos,  Lineas,  PreparadoPor,  
										  FechaPreparacion, AprobadoPor, FechaAprobacion, TituloVoucher, 
										  ComentariosVoucher,  FechaVoucher,  NroInterno,  FlagTransferencia, 
										  Estado, CodLibroCont,  UltimoUsuario,  UltimaFecha, ComentariosVoucher)
									values('".$CodOrganismo."',  '".$Periodo."',  '$VoucherBajaPub20',  '".$CodContabilidad."',
										   'AF',  '$NroVoucher', 'AF', '".$dependencia."', 
										   '$Creditos', '$Debitos', '$nro_lineas','".$f_trans['PreparadoPor']."',
										   '".$f_trans['Fecha']."', '".$f_trans['AprobadoPor']."', '".$f_trans['FechaAprobacion']."', '".$f_trans['Comentario']."',
										   '".$f_trans['Comentario']."', '".date("Y-m-d")."', '".$nrointerno."', 'N', 
										   'MA', '".$CodLibroCont."', '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."','".$f_trans['Comentario']."')"; //echo $sql_g;
	 $qry_g = mysql_query($sql_g) or die ($sql_g.mysql_error());
    
  }
//// ______________________________________________________________________
////             GENERAR VOUCHER DE BAJA DE ACTIVOS ONCO
//// ______________________________________________________________________
if($accion == "generarVoucherBajaOnco"){
  connect();
   
  $NroVoucher = getCodigo("ac_vouchermast","NroVoucher",4,"CodOrganismo",$CodOrganismo,"Periodo",$Periodo,"CodVoucher",$CodVoucher,"CodContabilidad",$CodContabilidad);
  
  $VoucherBaja = $CodVoucher.'-'.$NroVoucher; //echo $voucherIngPub20;
   
  //// ACTUALIZACION DE TABLAS 
  $sql_a = "update af_activo set VoucherBajaOnco = '$VoucherBaja',
  								 PeriodoBajaOnco =  '".$Periodo."'
						    where 
							     Activo='".$Activo."' and 
								 CodOrganismo='".$CodOrganismo."'"; //echo $sql_a;
  $qry_a = mysql_query($sql_a) or die ($sql_a.mysql_error());
  
  $s_upd = "update af_transaccionbaja set ContabilizadoFlag='S',
  										  PeriodoVoucher = '".date("Y-m")."',
										  VoucherNo = '$VoucherBaja' 
									where 
									      CodTransaccionBaja = '".$CodTransaccionBaja."' and 
										  Activo = '".$Activo."' and 
										  Organismo = '".$CodOrganismo."'";
  $q_upd = mysql_query($s_upd) or die ($s_upd.mysql_error());
  
    
  $s_transbajacuenta = "select 
							   * 
						  from 
								af_transaccionbajacuenta 
						  where 
								CodTransaccionBaja='".$CodTransaccionBaja."' and 
								Activo='".$Activo."' and 
								Contabilidad='".$CodContabilidad."'"; 
  $q_transbajacuenta = mysql_query($s_transbajacuenta) or die ($s_transbajacuenta.mysql_error());
  $r_transbajacuenta = mysql_num_rows($q_transbajacuenta);
  if($r_transbajacuenta != 0){
   for($i=0; $i<$r_transbajacuenta; $i++){
	 $f_transbajacuenta = mysql_fetch_array($q_transbajacuenta);
	 $sql_c = "insert into 
					ac_voucherdet(CodOrganismo, Periodo, Voucher, Linea,
								  CodContabilidad, CodCuenta,  MontoVoucher, MontoPost,
								  CodPersona, FechaVoucher, CodCentroCosto,  ReferenciaTipoDocumento,
								  ReferenciaNroDocumento, Descripcion, Estado, UltimoUsuario, UltimaFecha) 
					values('".$CodOrganismo."','".$Periodo."','$VoucherBajaPub20','".$f_transbajacuenta['Secuencia']."',
						'".$CodContabilidad."','".$f_transbajacuenta['CuentaContable']."','".$f_transbajacuenta['MontoLocal']."','".$f_transbajacuenta['MontoLocal']."',
						'".$CodPersona."', '".$FechaIngreso."', '".$CentroCosto."',	'".$ObligacionTipoDocumento."',
						'".$ObligacionNroDocumento."', '".$field_f['Descripcion']."', 'MA', '".$_SESSION['USUARIO_ACTUAL']."','".date("Y-m-d H:i:s")."')";
	 $qry_c = mysql_query($sql_c) or die ($sql_c.mysql_error());
	 
	 $nro_lineas++; 
	 if($f_transbajacuenta['MontoLocal']>0)$Creditos+=$f_transbajacuenta['MontoLocal'];
	 else $Debitos+=$f_transbajacuenta['MontoLocal'];
	 
   }
  }
	  
	  $s_trans = "select * from af_transaccionbaja where CodTransaccionBaja='".$CodTransaccionBaja."' and Activo='".$Activo."' and Organismo='".$CodOrganismo."'";
	  $q_trans = mysql_query($s_trans) or die ($s_trans.mysql_error());
	  $r_trans = mysql_num_rows($q_trans);
	  if($r_trans!=0)$f_trans = mysql_fetch_array($q_trans);
	  
	  $s_vmast = "select max(NroInterno) from ac_vouchermast where CodOrganismo='".$CodOrganismo."'";
	  $q_vmast = mysql_query($s_vmast) or die ($s_vmast.mysql_error());
	  $r_vmast = mysql_num_rows($q_vmast);
	  if($r_vmast!=0)$f_vmast= mysql_fetch_array($q_vmast);
	  $nrointerno=(int) ($f_vmast[0]+1);
	  $nrointerno=(string) str_repeat("0", 10-strlen($nrointerno)).$nrointerno;
	  
	  
	$sql_g = "insert into ac_vouchermast(CodOrganismo, Periodo,  Voucher,  CodContabilidad,
										  Prefijo,  NroVoucher,  CodVoucher,  CodDependencia,
										  Creditos, Debitos,  Lineas,  PreparadoPor,  
										  FechaPreparacion, AprobadoPor, FechaAprobacion, TituloVoucher, 
										  ComentariosVoucher,  FechaVoucher,  NroInterno,  FlagTransferencia, 
										  Estado, CodLibroCont,  UltimoUsuario,  UltimaFecha, ComentariosVoucher)
									values('".$CodOrganismo."',  '".$Periodo."',  '$VoucherBaja',  '".$CodContabilidad."',
										   'AF',  '$NroVoucher', 'AF', '".$dependencia."', 
										   '$Creditos', '$Debitos', '$nro_lineas','".$f_trans['PreparadoPor']."',
										   '".$f_trans['Fecha']."', '".$f_trans['AprobadoPor']."', '".$f_trans['FechaAprobacion']."', '".$f_trans['Comentario']."',
										   '".$f_trans['Comentario']."', '".date("Y-m-d")."', '".$nrointerno."', 'N', 
										   'MA', '".$CodLibroCont."', '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."','".$f_trans['Comentario']."')"; //echo $sql_g;
	 $qry_g = mysql_query($sql_g) or die ($sql_g.mysql_error());
    
  }
//// ----------------------------------------------------------------------
////   APROBAR ACTIVO (ESTADO = AP), PROCESOS => APROBACION ALTA DE ACTIVOS
//// ----------------------------------------------------------------------
if($accion=='AprobarActivo'){
 connect();

list($a, $m, $d) = split('[-]', $FechaIngreso); 
$Anio = $a;




/// Obtengo el codpersona del usuario que esta aprobando la alta del activo
$s_a = "select * from usuarios where Usuario='".$_SESSION['USUARIO_ACTUAL']."'";
$q_a = mysql_query($s_a) or die ($s_a.mysql_error());
$r_a = mysql_num_rows($q_a);

if($r_a!=0)$f_a = mysql_fetch_array($q_a);
  $AprobadoPor = $f_a['CodPersona'];
  
  /// obtengo el máximo de secuencia rh_empleadonivelacion
  $s_c = "select max(Secuencia) from rh_empleadonivelacion where CodPersona='$AprobadoPor'";
  $q_c = mysql_query($s_c) or die ($s_c.mysql_error());
  $f_c = mysql_fetch_array($q_c);
  
  /// Obtengo datos del Aprobador  
  $s_b = "select 
				a.CodCargo,
				a.CodPersona,
				b.DescripCargo,
				c.NomCompleto
			from 
				rh_empleadonivelacion a 
				inner join rh_puestos b on (b.CodCargo=a.CodCargo)
				inner join mastpersonas c on (c.CodPersona = a.CodPersona)
		  where 
				a.Secuencia='".$f_c[0]."' and  
				a.CodPersona='$AprobadoPor'";
  $q_b = mysql_query($s_b) or die ($s_b.mysql_error());
  $r_b = mysql_num_rows($q_b); if($r_b!=0)$f_b = mysql_fetch_array($q_b);

  /// Obtengo datos de la encargada de la Dirección General, utilizamos el parametro
  $parametroAprobadoPor= $_PARAMETRO['APROBACTIVOPOR'] ; /// Conformado Dirección General 0003
  $s_dg = "select 
                  mp.ValorParam,
				  b.CodPersona,
				  d.DescripCargo
			from 
			      mastparametros mp 
			      inner join mastdependencias a on (a.CodDependencia=mp.ValorParam) 
				  inner join mastpersonas b on (b.CodPersona=a.CodPersona) 
				  inner join mastempleado c on (c.CodPersona=a.CodPersona)
				  inner join rh_puestos d on (d.CodCargo=c.CodCargo)
			where 
			      mp.ValorParam='$parametroAprobadoPor'";
	$q_dg = mysql_query($s_dg) or die ($s_dg.mysql_error());
	$r_dg = mysql_num_rows($q_dg); if($r_dg!=0) $f_dg = mysql_fetch_array($q_dg);

  $s_dg2 = "select 
				a.CodCargo,
				a.CodPersona,
				b.DescripCargo,
				c.NomCompleto	
			from 
				rh_empleadonivelacion a 
				inner join rh_puestos b on (b.CodCargo = a.CodCargo)
				inner join mastpersonas c on (c.CodPersona = a.CodPersona) 
			where
				a.CodPersona='".$f_dg['CodPersona']."' and  
				a.Secuencia = (select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$f_dg['CodPersona']."')"; 
   $q_dg2 = mysql_query($s_dg2) or die ($s_dg2.mysql_error());
   $r_dg2 = mysql_num_rows($q_dg2); if($r_dg2!=0) $f_dg2 = mysql_fetch_array($q_dg2);

  $det = explode(";", $detalles);
  $i=0;

/// Obteniendo nro de acta de incorporación
    $sincorp = "select max(NroActa) from af_actaincorpactivo where CodOrganismo='$CodOrganismo'"; 
	$qincorp = mysql_query($sincorp) or die ($sincorp.mysql_error());
	$fincorp = mysql_fetch_array($qincorp);
   
    $nro_acta_incorp = (int) ($fincorp[0]+1);
	$nro_acta_incorp = (string) str_repeat("0",4-strlen($nro_acta_incorp)).$nro_acta_incorp;
	

foreach ($det as $detalle) {	
  if($paso!=1){ list($CodOrganismo, $Activo) = split('[|]', $detalle); $i++;}
   
   $s_activo = "select 
   					  a.*,
					  b.NomCompleto as NombreEmpleadoResponsable
				 from 
				     af_activo a 
					 inner join mastpersonas b on (b.CodPersona=a.EmpleadoResponsable)
				where 
					 a.Activo='$Activo' and 
					 a.CodOrganismo = '$CodOrganismo'"; 
   $q_activo = mysql_query($s_activo) or die ($s_activo.mysql_error());
   $r_activo = mysql_num_rows($q_activo); if($r_activo!=0) $f_activo = mysql_fetch_array($q_activo) ;
   
   $s_activosdat = "select 
						a.CodCargo,
						a.CodPersona,
						b.DescripCargo	
					from 
						rh_empleadonivelacion a 
						inner join rh_puestos b on (b.CodCargo=a.CodCargo) 
					where
						a.CodPersona='".$f_activo['EmpleadoResponsable']."' and  
						a.Secuencia = (select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$f_activo['EmpleadoResponsable']."')";
	$q_activosdat = mysql_query($s_activosdat) or die ($s_activosdat.mysql_error());
	$r_activosdat = mysql_num_rows($q_activosdat); if($r_activosdat!=0) $f_activosdat = mysql_fetch_array($q_activosdat);
    
	/// Insertando Datos en "af_actaincorpactivo"
	$s_actincorp = "insert into af_actaincorpactivo(CodOrganismo, Activo, Anio, NroActa, 
													FechaActa, AprobadoPor, EmpleadoAprob, ConformadoPor, 
													EmpleadoConform, CargoConform, DescripCargoConform, CargoAprobadoPor, 
													DescripCargoAprob, UltimoUsuario, UltimaFechaModif )
											  values('".$CodOrganismo."', '".$Activo."', '".$Anio."', '".$nro_acta_incorp."',
											         '".$FechaIngreso."', '".$f_b['CodPersona']."', '".$f_b['NomCompleto']."', '".$f_dg2['CodPersona']."',
													 '".$f_dg2['NomCompleto']."','".$f_dg2['CodCargo']."','".$f_dg2['DescripCargo']."','".$f_b['CodCargo']."', 
													 '".$f_b['DescripCargo']."', '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."')"; 
	$q_actincorp = mysql_query($s_actincorp) or die ($s_actincorp.mysql_error());
	
	/// Obteniendo nro de acta de entrega
	$sentrega = "select max(NroActa) from af_actaentregaactivo where CodOrganismo='$CodOrganismo'";
	$qentrega = mysql_query($sentrega) or die ($sentrega.mysql_error());
	$rentrega = mysql_num_rows($qentrega); if($rentrega!=0) $fentrega = mysql_fetch_array($qentrega);
	
	$nro_acta_antrega = (int) ($fentrega[0]+1);
	$nro_acta_antrega = (string) str_repeat("0",4-strlen($nro_acta_antrega)).$nro_acta_antrega;
   
    /// Insertando Datos en "af_actaentregaactivo"
   	$s_actaentrega = "insert into af_actaentregaactivo(Activo, Anio, NroActa, FechaActa,
													   AprobadoPor, CargoAprobador, DescripCargoAprob, EmpleadoAprob, 
													   ConformadoPor, EmpleadoConform, CargoConform, DescripCargoConform,
													   ResponsablePrimario, EmpleadoRespon, CargoResponsable, 
													   DescripCargoRespon, UltimoUsuario, UltimoFechaModif, CodOrganismo) 
												 value('".$Activo."', '".date("Y")."', '$nro_acta_antrega', '".date("Y-m-d")."',
												  	   '".$f_b['CodPersona']."', '".$f_b['CodCargo']."', '".$f_b['DescripCargo']."', '".$f_b['NomCompleto']."',
													   '".$f_dg2['CodPersona']."', '".$f_dg2['NomCompleto']."','".$f_dg2['CodCargo']."','".$f_dg2['DescripCargo']."',
													   '".$f_activo['EmpleadoResponsable']."','".$f_activo['NombreEmpleadoResponsable']."','".$f_activosdat['CodCargo']."',
													   '".$f_activosdat['DescripCargo']."','".$_SESSION['USUARIO_ACTUAL']."','".date("Y-m-d H:i:s")."', '".$CodOrganismo."')";
	$q_actaentrega = mysql_query($s_actaentrega) or die ($s_actaentrega.mysql_error());

   
   //// ------------------ ACTUALIZANDO TABLA AF_ACTIVO 
   $s_update = "update 
  					af_activo 
				set 
					Estado='AP',
					EstadoRegistro='AP',
					AprobadoPor='".$f_b['CodPersona']."',
					CargoAprobadoPor='".$f_b['CodCargo']."',
					FechaRevisadoPor='".date("Y-m-d")."',
					RevisadoPor = '".$f_b['CodPersona']."',
					CargoRevisadoPor = '".$f_b['CodCargo']."',
					ConformadoPor = '".$f_dg2['CodPersona']."',
					CargoConformadoPor = '".$f_dg2['CodCargo']."',
					NroIncorporacion = '$nro_acta_incorp',
					NroActaEntrega = '$nro_acta_antrega'
			  where 
			   		Activo='".$Activo."' and 
					CodOrganismo='".$CodOrganismo."'"; 
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error());

//// ------------------ SE CARGAN DATOS EN TABLA HISTORICA DE MOVIMIENTOS DEL ACTIVO
$sact = "select 
      			a.*,
				b.Secuencia as OrdenSecuencia
			from 
      			af_activo a 
				inner join lg_activofijo b on (a.Activo  = b.Activo) 
			where 
      			a.Activo= '".$Activo."'  and 
				a.CodOrganismo= '".$CodOrganismo."' and 
				a.Estado= 'AP'";
$qact = mysql_query($sact) or die ($sact.mysql_error());
$fact = mysql_fetch_array($qact);

	$secuencia = (int) (0 + 1);
	$secuencia = (string) str_repeat("0",1-strlen($secuencia)).$secuencia;


$sin = "insert into af_historicotransaccion(CodOrganismo, Activo, Secuencia, CodDependencia,
											CentroCosto, CodigoInterno, SituacionActivo, CodTipoMovimiento,
											Ubicacion, InternoExternoFlag, MotivoTraslado, FechaIngreso,
											FechaTransaccion,PeriodoIngreso, PeriodoTransaccion,
											NumeroOrden, OrdenSecuencia, MontoActivo, UltimoUsuario,UltimaFechaModif)
									  value('".$fact['CodOrganismo']."', '".$fact['Activo']."', '$secuencia', '".$fact['CodDependencia']."',
										   '".$fact['CentroCosto']."','".$fact['CodigoInterno']."','".$fact['SituacionActivo']."', '".$fact['CodTipoMovimiento']."',
										   '".$fact['Ubicacion']."', 'I', '01', '".$fact['FechaIngreso']."',
										   '".date("Y-m-d")."', '".$fact['PeriodoIngreso']."', '".date("Y-m")."',
										   '".$fact['NumeroOrden']."','".$fact['OrdenSecuencia']."','".$fact['MontoLocal']."','".$_SESSION['USUARIO_ACTUAL']."','".date("Y-m-d H:i:s")."')";
$qin = mysql_query($sin) or die ($sin.mysql_error());  

}

echo $nro_acta_incorp; 
}  
//// ______________________________________________________________________
////               PROCESO DE APROBACION MASIVO DE ACTIVOS             ////
//// ______________________________________________________________________
if($accion=="cargarMasivoAltaActivo"){ 
connect();

/// Obtengo el codpersona del usuario que esta aprobando la alta del activo
$s_a = "select * from usuarios where Usuario='".$_SESSION['USUARIO_ACTUAL']."'";
$q_a = mysql_query($s_a) or die ($s_a.mysql_error());
$r_a = mysql_num_rows($q_a);

if($r_a!=0)$f_a = mysql_fetch_array($q_a);
  $AprobadoPor = $f_a['CodPersona'];
  
  /// obtengo el máximo de secuencia
  $s_c = "select max(Secuencia) from rh_empleadonivelacion where CodPersona='$AprobadoPor'";
  $q_c = mysql_query($s_c) or die ($s_c.mysql_error());
  $f_c = mysql_fetch_array($q_c);
  
  /// Obtengo datos del Aprobador  
  $s_b = "select 
				a.CodCargo,
				a.CodPersona,
				b.DescripCargo,
				c.NomCompleto
			from 
				rh_empleadonivelacion a 
				inner join rh_puestos b on (b.CodCargo=a.CodCargo)
				inner join mastpersonas c on (c.CodPersona = a.CodPersona)
		  where 
				a.Secuencia='".$f_c[0]."' and  
				a.CodPersona='$AprobadoPor'";
  $q_b = mysql_query($s_b) or die ($s_b.mysql_error());
  $r_b = mysql_num_rows($q_b); if($r_b!=0)$f_b = mysql_fetch_array($q_b);

  /// Obtengo datos de la encargada de la Dirección General, utilizamos el parametro
  $parametroAprobadoPor= $_PARAMETRO['APROBACTIVOPOR'] ; /// Conformado Dirección General 0003
  	$s_dg = "select 
                  mp.ValorParam,
				  b.CodPersona,
				  d.DescripCargo
			from 
			      mastparametros mp 
			      inner join mastdependencias a on (a.CodDependencia=mp.ValorParam) 
				  inner join mastpersonas b on (b.CodPersona=a.CodPersona) 
				  inner join mastempleado c on (c.CodPersona=a.CodPersona)
				  inner join rh_puestos d on (d.CodCargo=c.CodCargo)
			where 
			      mp.ValorParam='$parametroAprobadoPor'";
	$q_dg = mysql_query($s_dg) or die ($s_dg.mysql_error());
	$r_dg = mysql_num_rows($q_dg); if($r_dg!=0) $f_dg = mysql_fetch_array($q_dg);

    $s_dg2 = "select 
				a.CodCargo,
				a.CodPersona,
				b.DescripCargo,
				c.NomCompleto	
			from 
				rh_empleadonivelacion a 
				inner join rh_puestos b on (b.CodCargo = a.CodCargo)
				inner join mastpersonas c on (c.CodPersona = a.CodPersona) 
			where
				a.CodPersona='".$f_dg['CodPersona']."' and  
				a.Secuencia = (select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$f_dg['CodPersona']."')"; 
   $q_dg2 = mysql_query($s_dg2) or die ($s_dg2.mysql_error());
   $r_dg2 = mysql_num_rows($q_dg2); if($r_dg2!=0) $f_dg2 = mysql_fetch_array($q_dg2);

  	$det = explode(";", $detalles);
  	$i=0;

/// Obteniendo nro de acta de incorporación
    $sincorp = "select max(NroActa) from af_actaincorpactivo where CodOrganismo='$CodOrganismo'"; echo $sincorp;
	$qincorp = mysql_query($sincorp) or die ($sincorp.mysql_error());
	$fincorp = mysql_fetch_array($qincorp);
   
    $nro_acta_incorp = (int) ($fincorp[0]+1);
	$nro_acta_incorp = (string) str_repeat("0",4-strlen($nro_acta_incorp)).$nro_acta_incorp;

/// Obteniendo nro de acta de entrega
	$sentrega = "select max(NroActa) from af_actaentregaactivo where CodOrganismo='$CodOrganismo'"; //echo $sentrega;
	$qentrega = mysql_query($sentrega) or die ($sentrega.mysql_error());
	$rentrega = mysql_num_rows($qentrega); if($rentrega!=0) $fentrega = mysql_fetch_array($qentrega);
	
	$nro_acta_antrega = (int) ($fentrega[0]+1);
	$nro_acta_antrega = (string) str_repeat("0",4-strlen($nro_acta_antrega)).$nro_acta_antrega;
	

foreach ($det as $detalle) {	
   list($CodOrganismo, $Activo, $Dependencia)=split( '[|]', $detalle); $i++;
   
   $s_activo = "select 
   					  a.*,
					  b.NomCompleto as NombreEmpleadoResponsable
				 from 
				     af_activo a 
					 inner join mastpersonas b on (b.CodPersona=a.EmpleadoResponsable)
				where 
					 a.Activo='$Activo' and 
					 a.CodOrganismo = '$CodOrganismo'";
   $q_activo = mysql_query($s_activo) or die ($s_activo.mysql_error());
   $r_activo = mysql_num_rows($q_activo); if($r_activo!=0) $f_activo = mysql_fetch_array($q_activo) ;
   
   $s_activosdat = "select 
						a.CodCargo,
						a.CodPersona,
						b.DescripCargo	
					from 
						rh_empleadonivelacion a 
						inner join rh_puestos b on (b.CodCargo=a.CodCargo) 
					where
						a.CodPersona='".$f_activo['EmpleadoResponsable']."' and  
						a.Secuencia = (select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$f_activo['EmpleadoResponsable']."')";
	$q_activosdat = mysql_query($s_activosdat) or die ($s_activosdat.mysql_error());
	$r_activosdat = mysql_num_rows($q_activosdat); if($r_activosdat!=0) $f_activosdat = mysql_fetch_array($q_activosdat);
    
   /// Insertando Datos en "af_actaincorpactivo"
	$s_actincorp = "insert into af_actaincorpactivo(CodOrganismo, Activo, Anio, NroActa, 
													FechaActa, AprobadoPor, EmpleadoAprob, ConformadoPor, 
													EmpleadoConform, CargoConform, DescripCargoConform, CargoAprobadoPor, 
													DescripCargoAprob, UltimoUsuario, UltimaFechaModif )
											  values('".$CodOrganismo."', '".$Activo."', '".date("Y")."', '".$nro_acta_incorp."',
											         '".date("Y-m-d")."', '".$f_b['CodPersona']."', '".$f_b['NomCompleto']."', '".$f_dg2['CodPersona']."',
													 '".$f_dg2['NomCompleto']."','".$f_dg2['CodCargo']."','".$f_dg2['DescripCargo']."','".$f_b['CodCargo']."', 
													 '".$f_b['DescripCargo']."', '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."')"; 
	$q_actincorp = mysql_query($s_actincorp) or die ($s_actincorp.mysql_error());
	
	  
   /// Insertando Datos en "af_actaentregaactivo"
   	$s_actaentrega = "insert into af_actaentregaactivo(Activo, Anio, NroActa, FechaActa,
													   AprobadoPor, CargoAprobador, DescripCargoAprob, EmpleadoAprob, 
													   ConformadoPor, EmpleadoConform, CargoConform, DescripCargoConform,
													   ResponsablePrimario, EmpleadoRespon, CargoResponsable, 
													   DescripCargoRespon, UltimoUsuario, UltimoFechaModif, CodOrganismo) 
												 value('".$Activo."', '".date("Y")."', '$nro_acta_antrega', '".date("Y-m-d")."',
												  	   '".$f_b['CodPersona']."', '".$f_b['CodCargo']."', '".$f_b['DescripCargo']."', '".$f_b['NomCompleto']."',
													   '".$f_dg2['CodPersona']."','".$f_dg2['NomCompleto']."','".$f_dg2['CodCargo']."','".$f_dg2['DescripCargo']."',
													   '".$f_activo['EmpleadoResponsable']."','".$f_activo['NombreEmpleadoResponsable']."','".$f_activosdat['CodCargo']."',
													   '".$f_activosdat['DescripCargo']."','".$_SESSION['USUARIO_ACTUAL']."','".date("Y-m-d H:i:s")."', '".$CodOrganismo."')"; 
	$q_actaentrega = mysql_query($s_actaentrega) or die ($s_actaentrega.mysql_error());

   
   //// ------------------ ACTUALIZANDO TABLA AF_ACTIVO 
    $s_update = "update 
  					af_activo 
				set 
					Estado='AP',
					EstadoRegistro='AP',
					AprobadoPor='".$f_b['CodPersona']."',
					CargoAprobadoPor='".$f_b['CodCargo']."',
					FechaRevisadoPor='".date("Y-m-d")."',
					RevisadoPor = '".$f_b['CodPersona']."',
					CargoRevisadoPor = '".$f_b['CodCargo']."',
					ConformadoPor = '".$f_dg2['CodPersona']."',
					CargoConformadoPor = '".$f_dg2['CodCargo']."',
					NroIncorporacion = '$nro_acta_incorp',
					NroActaEntrega = '$nro_acta_antrega'
			  where 
			   		Activo='".$Activo."' and 
					CodOrganismo='".$CodOrganismo."'"; 
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error());

   //// ------------------ SE CARGAN DATOS EN TABLA HISTORICA DE MOVIMIENTOS DEL ACTIVO
    $sact = "select 
      			a.*,
				b.Secuencia as OrdenSecuencia
			from 
      			af_activo a 
				inner join lg_activofijo b on (a.Activo  = b.Activo) 
			where 
      			a.Activo= '".$Activo."'  and 
				a.CodOrganismo= '".$CodOrganismo."' and 
				a.Estado= 'AP'";
$qact = mysql_query($sact) or die ($sact.mysql_error());
$fact = mysql_fetch_array($qact);

	$secuencia = (int) (0 + 1);
	$secuencia = (string) str_repeat("0",1-strlen($secuencia)).$secuencia;

    $sin = "insert into af_historicotransaccion(CodOrganismo, Activo, Secuencia, CodDependencia,
											CentroCosto, CodigoInterno, SituacionActivo, CodTipoMovimiento,
											Ubicacion, InternoExternoFlag, MotivoTraslado, FechaIngreso,
											FechaTransaccion,PeriodoIngreso, PeriodoTransaccion,
											NumeroOrden, OrdenSecuencia, MontoActivo, UltimoUsuario,UltimaFechaModif)
									  value('".$fact['CodOrganismo']."', '".$fact['Activo']."', '$secuencia', '".$fact['CodDependencia']."',
										   '".$fact['CentroCosto']."',	'".$fact['CodigoInterno']."', '".$fact['SituacionActivo']."', '".$fact['CodTipoMovimiento']."',
										   '".$fact['Ubicacion']."', 'I', '01', '".$fact['FechaIngreso']."',
										   '".date("Y-m-d")."', '".$fact['PeriodoIngreso']."', '".date("Y-m")."',
										   '".$fact['NumeroOrden']."','".$fact['OrdenSecuencia']."','".$fact['MontoLocal']."','".$_SESSION['USUARIO_ACTUAL']."','".date("Y-m-d H:i:s")."')";
$qin = mysql_query($sin) or die ($sin.mysql_error());  

}

$s_veces = "select 
				   *
			  from 
			       af_actaentregaactivo 
			 where 
			       NroActa = (select max(NroActa) from af_actaentregaactivo where CodOrganismo='".$CodOrganismo."') 
			order by 
			      Activo";
$q_veces = mysql_query($s_veces) or die ($s_veces.mysql_error());
$r_veces = mysql_num_rows($q_veces);
if($r_veces!=0)
 for($x=0; $x<$r_veces; $x++){ 
   $cuentaVeces++;
   $f_veces = mysql_fetch_array($q_veces);
   $activos_veces .= $f_veces['Activo'].'|';  
   $nro_acta_antrega = $f_veces['NroActa'];
   $codorganismo =  $f_veces['CodOrganismo'];
 }

 echo $i.';'.$nro_acta_antrega.';'.$codorganismo.';'.$activos_veces;
  
}
//// ______________________________________________________________________
////                 PROCESO CARGAR ALTA DE ACTIVOS                    ////
//// ______________________________________________________________________
if($accion=="cargarAltaActivo"){
  	$det = explode(";", $detalles);
	$i=0;

foreach ($det as $detalle) {	
   list($CodOrganismo, $Activo)=split( '[|]', $detalle); $i++;
   $valor = $CodOrganismo.'|'.$Activo;
   echo $valor;

}}
?>


