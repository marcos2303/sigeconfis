<?php 
extract($_POST);
extract($_GET);
//echo "<option>$codigo - $accion</option>";
include("funciones.php");
///////////////////////////////////////////////////////////////////////////////
//	SCRIPTS PARA AJAX
///////////////////////////////////////////////////////////////////////////////
if ($_POST['modulo']=="ELIMINAR") {
	connect();
if($_POST['accion']=="ELIMINARAJUSTE"){
 $SQL="SELECT * FROM pv_ajustepresupuestariodet WHERE CodAjuste='".$_POST['CodAjuste']."'";
 $QRY=mysql_query($SQL) or die ($SQL.mysql_error());
 $ROW=mysql_num_rows($QRY);
 if($ROW!=0){
   $SQL="DELETE FROM pv_ajustepresupuestariodet WHERE CodAjuste='".$_POST['CodAjuste']."'";
   $QRY=mysql_query($SQL) or die ($SQL.mysql_error());
 }
}
//// ------------------------------------------------------
//// ------------------------------------------------------
}
//// ----------------------------------------------------------------------------------------------	////
////			 ----------------------     MAESTRO SECTOR  -------------------------
//// ----------------------------------------------------------------------------------------------	////
if ($_POST['modulo']=="APLICACIONES") {
	connect();
	$error=0;
	//$ahora=date("Y-m-d H:i:s");
	$codigo=strtoupper($_POST['codigo']);
	$id_programa=strtoupper($_POST['id_programa']);
	$codigo1=strtoupper($_POST['cod_programa']);
	$descripcion=strtoupper(utf8_decode($_POST['descripcion']));
	//$periodo=strtr($_POST['periodo'], "/", "-");
	//$voucher=strtoupper($_POST['voucher']);
	///////////////////////////////////////////////////////////////////////////////////////////
	////// ************** *****  ELIMINAR REGISTRO MAESTRO SECTOR ***** **************** //////
 	if($_POST['accion']=="ELIMINAR"){//	CONSULTO SI EL REGISTRO A ELIMINAR
	   $sql="SELECT * FROM pv_sector WHERE Cod_sector='".$_POST['codigo']."'";
	   $query=mysql_query($sql) or die ($sql.mysql_error());
	   $rows=mysql_num_rows($query);
	   if($rows!=0){
	      $sql2="SELECT cod_sector FROM pv_programa1 WHERE cod_sector='".$_POST['codigo']."'";
		  $query2=mysql_query($sql2) or die ($sql2.mysql_error());
		  $rows2=mysql_num_rows($query2);
	      if($rows2!=0){
		    echo "<script>";
            echo "alert('EL REGISTRO ESTA ENLAZADO A OTRAS TABLAS')";
            echo "</script>"; 
		  }else{
		    $sql="DELETE FROM pv_sector WHERE Cod_sector='".$_POST['codigo']."'";
	        $query= mysql_query($sql) or die ($sql.mysql_error());
	      } 
	      //$error="REGISTRO EXISTENTE";
	   }
	 }
	 /////////////////////////////////////////////////////////////////////////////////////////
	 //// **** ELIMINAR ANTEPROYECTO
	 if($_POST['accion']=="ELIMINARANTEPROYECTO"){
	  	    $sql="DELETE FROM pv_antepresupuesto WHERE CodAnteproyecto='".$_POST['codigo']."'";
		    $query=mysql_query($sql) or die ($sql.mysql_error());
			
			$sql2="DELETE FROM pv_antepresupuestodet WHERE CodAnteproyecto='".$_POST['codigo']."'";
		    $query2=mysql_query($sql2) or die ($sql2.mysql_error());
	 }
	 //// **** ELIMINAR AJUSTE
	  if($_POST['accion']=="ELIMINARAJUSTE"){
	  	    $sql="DELETE FROM pv_ajustepresupuestario WHERE CodAjuste='".$_POST['codigo']."'";
		    $query=mysql_query($sql) or die ($sql.mysql_error());
			
			$sql2="DELETE FROM pv_ajustepresupuestariodet WHERE CodAjuste='".$_POST['codigo']."'";
		    $query2=mysql_query($sql2) or die ($sql2.mysql_error());
	 }
	 //// **** ELIMINAR REFORMULACION
	 if($_POST['accion']=="ELIMINARREFORMULACION"){
	  	    $sql="DELETE FROM pv_reformulacionppto WHERE CodRef='".$_POST['codigo']."' AND Organismo='".$_SESSION['ORGANISMO_ACTUAL']."'";
		    $query=mysql_query($sql) or die ($sql.mysql_error());
			
			$sql2="DELETE FROM pv_reformulacionpptodet WHERE CodRef='".$_POST['codigo']."' AND Organismo='".$_SESSION['ORGANISMO_ACTUAL']."'";
		    $query2=mysql_query($sql2) or die ($sql2.mysql_error());
	 }
	///// ************** *****  ELIMINAR REGISTRO MAESTRO PROGRAMA ***** **************** /////
	if($_POST['accion']=="ELIMINARPROG"){
	   $sql="SELECT * FROM pv_programa1 WHERE id_programa='".$_POST['codigo']."'";
	   $query=mysql_query($sql) or die ($sql.mysql_error());
	   $rows=mysql_num_rows($query);
	   if($rows!=0){
	     $sql2="SELECT id_programa FROM pv_subprog1 WHERE id_programa='".$_POST['codigo']."'";  
		 $query2=mysql_query($sql2) or die ($sql2.mysql_error());
		 $rows2=mysql_num_rows($query2);
		 if($rows2!=0){
		    echo "<script>";
            echo "alert('EL REGISTRO ESTA ENLAZADO A OTRAS TABLAS')";
            echo "</script>"; 
		 }else{
		    $sql="DELETE FROM pv_programa1 WHERE id_programa='".$_POST['codigo']."'";
		    $query=mysql_query($sql) or die ($sql.mysql_error());
	     }
	      //$error="REGISTRO EXISTENTE";
	   }
	}else{///////////////////////////////////////////////////////////////////////////////////////////
	     ////// ************** *****  ELIMINAR REGISTRO MAESTRO SUB-PROGRAMA ***** ********** ///////
	   if($_POST['accion']=="ELIMINARSP"){
	     $sql="SELECT * FROM pv_subprog1 WHERE id_sub='".$_POST['codigo']."'";
		 $query= mysql_query($sql) or die ($sql.mysql_error());
		 $rows=mysql_num_rows($query);
		 if($rows!=0){
		    $sql2="SELECT * FROM pv_proyecto1 WHERE id_sub='".$_POST['codigo']."'";  
		    $query2=mysql_query($sql2) or die ($sql2.mysql_error());
		    $rows2=mysql_num_rows($query2);
		    if($rows2!=0){
			   echo "<script>";
               echo "alert('EL REGISTRO ESTA ENLAZADO A OTRAS TABLAS')";
               echo "</script>"; 
			}else{
			   $sql="DELETE FROM pv_subprog1 WHERE id_sub='".$_POST['codigo']."'";
			   $query=mysql_query($sql) or die ($sql.mysql_error());
		    }
		 }
		   //$error="REGISTRO EXISTENTE";
	   }else{///////////////////////////////////////////////////////////////////////////////////////////
	         ////// ************** *****  ELIMINAR REGISTRO MAESTRO PROYECTO ***** ********** //////////
	        if($_POST['accion']=="ELIMINARPROY"){
		      $sql="SELECT * FROM pv_proyecto1 WHERE id_proyecto='".$_POST['codigo']."'";
		      $query= mysql_query($sql) or die ($sql.mysql_error());
		      $rows= mysql_num_rows($query);
		      if($rows!=0){
			     $sql2="SELECT * FROM pv_actividad1 WHERE id_proyecto='".$_POST['codigo']."'";
				 $query2=mysql_query($sql2) or die ($sql2.mysql_error());
				 $rows2=mysql_num_rows($query2);
				 if($rows2!=0){
				   echo"<script>";
				   echo"alert('EL REGISTRO ESTA ENLAZADO A OTRAS TABLAS')";
				   echo"</script>";
				 }else{
				   $sql="DELETE FROM pv_proyecto1 WHERE id_proyecto='".$_POST['codigo']."'";
			       $query=mysql_query($sql) or die ($sql.mysql_error());
		         }
		        //$error="REGISTRO EXISTENTE";
		     }
	      }else{//////////////////////////////////////////////////////////////////////////////////////
	           ////// ************** *****  ELIMINAR REGISTRO MAESTRO ACTIVIDAD ***** ********** //////
		      if($_POST['accion']=="ELIMINARACT"){
		      $sql="SELECT * FROM pv_actividad1 WHERE id_actividad='".$_POST['codigo']."'";
		      $query= mysql_query($sql) or die ($sql.mysql_error());
		      $rows= mysql_num_rows($query);
		      if($rows!=0){
		        $sql="DELETE FROM pv_actividad1 WHERE id_actividad='".$_POST['codigo']."'";
			    $query=mysql_query($sql) or die ($sql.mysql_error());
		      }else{
		        $error="REGISTRO EXISTENTE";
		      }
		    }else{///////////////////////////////////////////////////////////////////////////////
			      //////////////// ***********  ELIMINAR PARTIDA ************   /////////////////
		      if($_POST['accion']=="ELIMINARPART"){
			    $sql="SELECT * FROM pv_partida WHERE cod_partida='".$_POST['codigo']."'";
				$query= mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if($rows!=0){
				  $sql="DELETE FROM pv_partida WHERE cod_partida='".$_POST['codigo']."'";
				  $query=mysql_query($sql) or die ($sql.mysql_error());
				}else{
				  $error="REGISTRO EXISTENTE";
			    }
			  }else{////////////// ***** ELIMINAR REGISTRO UNIDAD EJECUTORA ***** //////////////////////
			    if($_POST['accion']=="ELIMINARUNIDAD"){
				 $sql="SELECT * FROM pv_unidadejecutora WHERE id_unidadejecutora='".$_POST['codigo']."'";
				 $query=mysql_query($sql) or die ($sql.mysql_error());
				 $rows=mysql_num_rows($query);
				 if($rows!=0){
				   $sql="DELETE FROM pv_unidadejecutora WHERE id_unidadejecutora='".$_POST['codigo']."'";
				 }else{
				    $error="REGISTRO EXISTENTE";
				 }				
				}
			   }	
			}
	      }
	} 
}
}
//// ----------------------------------------------------------------------------------------------	////
//// 			------------------ PROCESO DEL DOCTOR PRESUPUESTARIO ----------------------			  
//// ----------------------------------------------------------------------------------------------	////			  
if($accion=="DOCTORPRESUPUESTARIO"){
connect();
  if($valor=='tab2'){
     list($cod_partida, $cont, $MontoAjusteMov, $MontoAjustadoActual, $MontoPorAjustar)= split('[_]',$codigo);
	 
	 if($MontoPorAjustar<'0'){
	    $MontoModif = $MontoAjustadoActual + $MontoPorAjustar; 	 
	 }else{ 
	    $MontoModif = $MontoAjustadoActual + $MontoPorAjustar; 	 
	 }
	    
	 $sql_a = "update 
	 				pv_ajustepresupuestariodet 
				  set 
				    MontoAjuste='$MontoModif' 
				where 
				    CodPresupuesto='".$CodPresupuesto."' and 
					Organismo='".$Organismo."' and 
					cod_partida='$cod_partida' ";	
	$qry_a = mysql_query($sql_a) or die ($sql_a.mysql_error());
  }elseif($valor=='tab1'){
    list($cod_partida, $MTCompromiso, $MontoCompromiso, $MTCausado, $MontoCausado, $MTPagado, $MontoPagado)= split('[|]',$codigo); 
    //echo " <option>$cod_partida, $MTCompromiso, $MontoCompromiso, $MTCausado, $MontoCausado, $MTPagado, $MontoPagado</option>";
	
	// PARA COMPROMISOS
	$monto_ajustar_compromiso = $MTCompromiso - $MontoCompromiso; /// Verificando Compromisos 
	if($monto_ajustar_compromiso>'0') $monto_compromiso_modif = $MontoCompromiso + $monto_ajustar_compromiso;
	else  $monto_compromiso_modif = $MontoCompromiso + $monto_ajustar_compromiso;
	
	if($monto_ajustar_compromiso!=0){
			$s_compromiso = "update 
								   pv_presupuestodet 
								 set 
								   MontoCompromiso = '$monto_compromiso_modif' 
							   where 
								   CodPresupuesto='".$CodPresupuesto."' and 
								   Organismo='".$Organismo."' and 
								   cod_partida='$cod_partida'";
			$q_compromiso = mysql_query($s_compromiso) or die ($s_compromiso.mysql_error());
	}
	
	// PARA CAUSADOS
	$monto_ajustar_causado = $MTCausado - $MontoCausado;  /// Verificanco Causados
	if($monto_ajustar_causado>'0') $monto_causado_modif = $MontoCausado + $monto_ajustar_causado;
	else $monto_causado_modif = $MontoCausado + $monto_ajustar_causado;
	
	if($monto_ajustar_causado!=0){
			$s_causado = "update 
								   pv_presupuestodet 
								 set 
								   MontoCausado = '$monto_causado_modif' 
							   where 
								   CodPresupuesto='".$CodPresupuesto."' and 
								   Organismo='".$Organismo."' and 
								   cod_partida='$cod_partida'";
			$q_causado = mysql_query($s_causado) or die ($s_causado.mysql_error());
	}
	
	// PARA PAGADOS
	$monto_ajustar_pagado = $MTPagado - $MontoPagado;  /// Verificanco Pagados
	if($monto_ajustar_pagado>'0') $monto_pagado_modif = $MontoPagado + $monto_ajustar_pagado;
	else $monto_pagado_modif = $MontoPagado + $monto_ajustar_pagado;
	
	if($monto_ajustar_pagados!=0){
		$s_pagados = "update 
							   pv_presupuestodet 
							 set 
							   MontoPagado = '$monto_pagado_modif' 
						   where 
							   CodPresupuesto='".$CodPresupuesto."' and 
							   Organismo='".$Organismo."' and 
							   cod_partida='$cod_partida'";
		$q_pagados = mysql_query($s_pagados) or die ($s_pagados.mysql_error());
	}
	
  }
}
?>