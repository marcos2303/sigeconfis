<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
include ("..//fphp.php");
//include ("fphp.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript01.js"></script>
<script type="text/javascript" language="javascript" src="fscript02.js"></script>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>

<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover{
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(imagenes/left_on.gif)
}
#header #current A {
        BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>
</head>
<body>
<table width="100%" height="19" cellpadding="0" cellspacing="0">
<tr>
  <td class="titulo">Proceso | Doctor Presupuestario</td>
  <td align="right"><a class="cerrar"; onclick="window.close();">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />
<div style="width:800px" class="divFormCaption"></div>
<?
if(!$_POST){
  $ano = date("Y");
  $sql_a = "select * from pv_presupuesto where EjercicioPpto='$ano'";
  $qry_a = mysql_query($sql_a) or die ($sql_a.mysql_error());
  $row_a = mysql_num_rows($qry_a);
  if($row_a!=0)$field_a = mysql_fetch_array($qry_a);
  $cod_presupuesto= $field_a['CodPresupuesto'];
  $periodo=date("Y-m"); 
}

if($fOrganismo != "")$filtro.=" AND (Organismo = '".$fOrganismo."')";
if($cod_presupuesto != "")$filtro.=" AND (CodPresupuesto = '".$cod_presupuesto."')";
//if($periodo!="") $filtro1.=" AND (Periodo='".$periodo."')";

echo"<form name='frmentrada' id='frmentrada' action='proceso_doctorpresupuestario.php?limit=0' method='POST'>
<input type='hidden' name='limit' value='".$limit."'>
<table class='tblForm' width='800' height='50'>
<tr>
   <td>
   <table>
   <tr>
       <td align='right' width='85'>Organismo:</td>
       <td align='left' width='200'>
           <select name='fOrganismo' id='fOrganismo' class='selectBig'>";
           getOrganismos($_SESSION['ORGANISMO_ACTUAL'],3);
           echo"
           </select>
       </td>
	   <td class='tagForm' width='120'>Cod. Presupuesto:</td>
	   <td><input type='text' id='cod_presupuesto' name='cod_presupuesto' size='7' maxlength='4' style='text-align:center' value='$cod_presupuesto'/></td>
	   <td class='tagForm' width='120'>Periodo:</td>
	   <td><input type='text' id='periodo' name='periodo' size='7' maxlength='7' style='text-align:center' value='$periodo'/></td>
   </tr>
  </table>
  </td>
 </tr>
 </table>
<center><input type='submit' name='btBuscar' value='Buscar'/></center>
</form>";

$sql_b="select
       		 a.cod_partida,
       		 e.Denominacion,
			 a.MontoCompromiso as MTCompromiso,
			 a.MontoCausado as MTCausado,
			 a.MontoPagado as MTPagado,
			 (select
				   sum(d.Monto)
				from
				  lg_distribucioncompromisos d
				where
				  d.CodOrganismo=a.Organismo and
				  d.cod_partida=a.cod_partida and
				  d.CodPresupuesto=a.CodPresupuesto and
				  d.Estado='CO') as MontoCompromiso,
			  (select
				   sum(b.Monto)
				from
				  ap_distribucionobligacion b
				where
				  b.CodOrganismo=a.Organismo and
				  b.cod_partida=a.cod_partida and
				  b.CodPresupuesto=a.CodPresupuesto and
				  b.Estado='CA') as MontoCausado,
			   (select
				   sum(c.Monto)
				from
				  ap_ordenpagodistribucion c
				where
				  c.CodOrganismo=a.Organismo and
				  c.cod_partida=a.cod_partida and
				  c.CodPresupuesto=a.CodPresupuesto and
				  c.Estado='PA') as MontoPagado
		from
 			 pv_presupuestodet a 
			 inner join pv_partida e on (e.cod_partida=a.cod_partida)
		where
		     a.Estado='AP' $filtro 
	 order by 
	         a.cod_partida"; //echo $sql_b;
$qry_b=mysql_query($sql_b) or die ($sql_b.mysql_error()); 
$row_b = mysql_num_rows($qry_b); 
?>
<form id="tabs" name="tabs">
<table width="800" align="center">
<tr>
<td>
<div id="header">
<ul>
<!-- CSS Tabs PESTAÑAS OPCIONES DE PRESUPUESTO -->
<li><a onClick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Ejecucion Presupuestaria</a></li>
<li><a onClick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Ajuste Presupuestario</a></li> 
</ul>
</div>
</td>
</tr>
</table>
<? /// ---------------------- TAB 1 EJECUCION PRESUPUESTARIA ?>
<div name="tab1" id="tab1" style="display:block;">
<div style="width:800px" class="divFormCaption"></div>
<? 
echo"<input type='hidden' id='registro' name='registro'/> 
	 <input type='hidden' id='registro_1' name='registro_1'/>"; 
?>
<table width="800" class="tblLista">
 <tr> 
  <td width="121"><div id="rows"># de Lineas:<?=$row_b;?></div></td>
  <td width="667" colspan="2" align="right">
     <input type="button" name="btProcesar" id="btProcesar" class="btLista" value="Procesar" onclick="ProcesarCambios(this.form, 'tab1' );"/>
  </td>
  </tr>
</table>
<center>
<div style="overflow:scroll; width:800px; height:300px;">
<table width="1500" class="tblLista">
<thead>
<tr class="trListaHead">
   <th colspan="2">PARTIDA PRESUPUESTARIA</td>
   <th colspan="3">COMPROMISOS</td>
   <th colspan="3">CAUSADO</td>
   <th colspan="3">PAGADO</td>
</tr>
 <tr class="trListaHead">
		<th width="5">Partida</th>
        <th width="150">Descripci&oacute;n</th>
		<th width="50">Compromisos seg&uacute;n Movimientos</th>
		<th width="50">Compromiso Actual</th>
		<th width="50">Monto por Ajustar</th>
    	<th width="50">Causados seg&uacute;n Movimientos</th>
 	    <th width="50">Causado Actual</th>
        <th width="50">Monto por Ajustar</th>
        <th width="50">Pagados seg&uacute;n Movimientos</th>
        <th width="50">Pagado Actual</th>
        <th width="50">Monto por Ajustar</th>
  </tr>
  </thead>
<?
  
  if($row_b!=0){
   for($i=0;$i<$row_b;$i++){
	   $field_b = mysql_fetch_array($qry_b);
	   $MontoCompromisoActual = number_format($field_b['MTCompromiso'],2,',','.');
	   $MontoCausadoActual = number_format($field_b['MTCausado'],2,',','.');
	   $MontoPagadoActual = number_format($field_b['MTPagado'],2,',','.');
	   //---------------------------------------------------------------------------------------
	   $MontoMovCompromiso = number_format($field_b['MontoCompromiso'],2,',','.');
	   $MontoMovCausado = number_format($field_b['MontoCausado'],2,',','.');
	   $MontoMovPagado = number_format($field_b['MontoPagado'],2,',','.');
	   //---------------------------------------------------------------------------------------
	   	   
	   $MontoCompAjustar = number_format(($field_b['MTCompromiso'] - $field_b['MontoCompromiso']),2,',','.'); /// Monto a mostar en la columna Compromisos monto por ajustar
	   $MontoCausAjustar = number_format(($field_b['MTCausado'] - $field_b['MontoCausado']),2,',','.'); /// Monto a mostar en la columna Compromisos monto por ajustar
	   $MontoPagaAjustar = number_format(($field_b['MTPagado'] - $field_b['MontoPagado']),2,',','.'); /// Monto a mostar en la columna Compromisos monto por ajustar
	   
	   $id_01=$field_b['cod_partida'].'|'.$field_b['MTCompromiso'].'|'.$field_b['MontoCompromiso'].'|'.$field_b['MTCausado'].'|'.$field_b['MontoCausado'].'|'.$field_b['MTPagado'].'|'.$field_b['MontoPagado'];
	   
    echo"<tr class='trListaBody' onclick='mClk(this,\"registro\");' id='$id_01'>
		<td align='center'>".$field_b['cod_partida']."</td>
		<td align='left'>".$field_b['Denominacion']."</td>
		<td align='right'>$MontoMovCompromiso</td>
        <td align='right'>$MontoCompromisoActual</td>
        <td align='right'>$MontoCompAjustar</td>
		
		<td align='right'>$MontoMovCausado</td>
        <td align='right'>$MontoCausadoActual</td>
        <td align='right'>$MontoCausAjustar</td>
		
		<td align='right'>$MontoMovPagado</td>
        <td align='right'>$MontoPagadoActual</td>
        <td align='right'>$MontoPagaAjustar</td>
		
	</tr>";
    }
 }
  ?>
</table>
</div>
</center>
</div>

<? /// ---------------------- TAB 2 AJUSTES PRESUPUESTARIOS ?>
<div id="tab2" style="display:none;">
<div style="width:800px" class="divFormCaption"></div><!--////////////////// **************** MOSTRAR LA TABLA DE PARTIDAS  ************ //////////////////// -->
<? 
$sql_c = "select
			  a.cod_partida,
			  d.Denominacion,
			  a.MontoAprobado,
			  a.MontoAjustado,
			  (select
					 sum(Montoajuste)
				 from
					 pv_ajustepresupuestariodet b
					 inner join pv_ajustepresupuestario c on (c.CodPresupuesto=b.CodPresupuesto and c.Organismo=b.Organismo and
															  c.CodAjuste=b.CodAjuste and c.TipoAjuste='IN')
				where
					 b.CodPresupuesto=a.CodPresupuesto and
					 b.Organismo=a.Organismo and
					 b.cod_partida=a.cod_partida) as MontoAjusteInc,
			  (select
					 sum(Montoajuste)
				from
					pv_ajustepresupuestariodet f
					inner join pv_ajustepresupuestario g on (g.CodPresupuesto=f.CodPresupuesto)and(g.Organismo=f.Organismo)and
															(g.CodAjuste=f.CodAjuste)and(g.TipoAjuste='DI')
			   where
					f.CodPresupuesto=a.CodPresupuesto and
					f.Organismo=a.Organismo and
					f.cod_partida=a.cod_partida) as MontoAjusteDis
		
		from
			  pv_presupuestodet a
			  inner join pv_partida d on (d.cod_partida=a.cod_partida)
		where
			  a.CodPresupuesto = '0003'
		 order by
			  a.cod_partida;";

$qry_c = mysql_query($sql_c) or die ($sql_c.mysql_error());
$row_c = mysql_num_rows($qry_c);
?>
<table width="800" class="tblLista">
 <tr> 
  <td width="121"><div id="rows"># de Lineas:<?=$row_b;?></div></td>
  <td width="667" colspan="2" align="right">
     <input type="button" name="btProcesar" id="btProcesar" class="btLista" value="Procesar" onclick="ProcesarCambios(this.form, 'tab2');"/>
  </td>
  </tr>
</table>
<center>
<div style="overflow:scroll; width:800px; height:300px;">
<table width="800" class="tblLista" border="0">
<thead>
  <tr class="trListaHead">
		<th colspan="2">PARTIDA PRESUPUESTARIA</th>
		<th colspan="3">Ajustes Presupuestarios</th>
  </tr>
 </thead>
<thead>
  <tr class="trListaHead">
		<th width="30">Partida</th>
		<th width="120">Descripci&oacute;n</th>
		<th width="50">Ajustes Seg&uacute;n Movimientos</th>
		<th width="60">Monto Ajustado Actual</th>
		<th width="50">Monto por Ajustar</th>
  </tr>
 </thead>
<?php
$rows=(int)$rows;
if($row_c!=0){ 
  for($a=0;$a<$row_c;$a++ ){
	  $field_c =mysql_fetch_array($qry_c); 
  /// -------------------------------------
  /// CALCULOS 
  $MAM = ($field_c['MontoAprobado']+($field_c['MontoAjusteInc'] - $field_c['MontoAjusteDis']));
  $MontoAjusteMov = number_format($MAM,2,',','.');
  
  $MAA = $field_c['MontoAjustado']; 	
  $MontoAjustadoActual = number_format($MAA,2,',','.'); 	
  
  $MPA = (($field_c['MontoAprobado']+($field_c['MontoAjusteInc'] - $field_c['MontoAjusteDis']))-$field_c['MontoAjustado']);
  $MontoPorAjustar = number_format($MPA,2,',','.');
  
  $cont++;
  $id2=$field_c['cod_partida'].'_'.$cont.'_'.$MAM.'_'.$MAA.'_'.$MPA;
  
  if($MontoPorAjustar>0){
	 $color_i="<font color='#FF0000'>"; $color_f="</font>";
  }elseif($MontoPorAjustar>0){ 
     $color_i="<font color='#0000FF'>"; $color_f="</font>";
  }
  
  
  
	echo" <tr class='trListaBody' onclick='mClk(this,\"registro\");' id='$id2'>
	 <td align='center'>".$field_c['cod_partida']."</td>
	 <td>".$field_c['Denominacion']."</td>
	 <td align='right'>$MontoAjusteMov</td>
	 <td align='right'>$MontoAjustadoActual</td>
	 <td align='right'>$color_i $MontoPorAjustar $color_f</td>
	 </tr> ";
  }
}



/*echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes($row_b, $row_b, ".$limit.");
	</script>";*/
?>
</table>
<script type="text/javascript" language="javascript">
	totalPuestos(<?=$rows?>);
</script>
</div>
</center>

<center>
<input type="hidden" name="filas" id="filas" value="<?=$rows?>" />
</center></div>
</form>
</body>
</html>
