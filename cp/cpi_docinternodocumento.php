<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', @$concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento</title>
<style>
<!--
@page { size: 21.59cm 27.94cm }

}
-->
</style>
<script language="JavaScript"> 
function printPage() {  
if(document.all) {  
document.all.divButtons.style.visibility = 'hidden';  
window.print();  
document.all.divButtons.style.visibility = 'visible';  
} else {  
document.getElementById('divButtons').style.visibility = 'hidden';  
window.print();  
document.getElementById('divButtons').style.visibility = 'visible';  
}  
}  
</script>
</head>

<body>
<?php
 list($cod_documento, $codpersona)=preg_split( '/[|]/', @$_GET['registro']);
 //echo $codpersona;

$sa = "select * from cp_documentointerno where Cod_DocumentoCompleto = '$cod_documento'";
 $qa = mysql_query($sa) or die ($sa.mysql_error());
 $fa = mysql_fetch_array($qa);
 
 //// consulta para verificar datos
 $scon="select *  from cp_documentodistribucion where Cod_Documento = '$cod_documento'";
 $qcon = mysql_query($scon) or die ($scon.mysql_error());
 $rcon = mysql_num_rows($qcon);
 

//if ($fcon['Procedencia']=='INT'){ 
 //// CONSULTA PARA OBTNER DATOS A MOSTRAR
 
 if($rcon!=0){
 
   for($i=0; $i<$rcon; $i++){
     $fcon = mysql_fetch_array($qcon);
	 
	 $sb = "select 
				   md.Dependencia,
				   mp.NomCompleto,
				   rp.DescripCargo,
				   md.CodPersona 
			  from 
				   mastdependencias md
				   inner join mastpersonas mp on (md.CodPersona=mp.CodPersona) 
				   inner join mastempleado me on (mp.CodPersona = me.Codpersona)
				   inner join rh_puestos rp on (rp.CodCargo = me.CodCargo)
			 where 
				   md.CodInterno = '".$fa['CodInterno']."'";
	 $qb = mysql_query($sb) or die ($sb.mysql_error());
	 $fb = mysql_fetch_array($qb);
	 
	 //// CONSULTA PARA OBTENER LOS DATOS A QUIEN VA DIRIGIDO EL DOCUMENTO
	 $sc = "select
	               mp.NomCompleto,
				   rp.DescripCargo,
				   md.Dependencia
			  from
			      mastpersonas mp,
				  rh_puestos rp,
				  mastdependencias md
			 where
			      mp.CodPersona = '".$fcon['CodPersona']."' and
				  rp.CodCargo = '".$fcon['CodCargo']."' and 
				  md.CodDependencia = '".$fcon['CodDependencia']."'";
	 $qc = mysql_query($sc) or die ($sc.mysql_error());
	 $fc = mysql_fetch_array( $qc);
	 
?>
<table id="Padre" name="Padre" align="center" cellpadding="0" cellspacing="0">
<tr>
<td>

<table id="principal"  align="center">
<tr><td width="707">
  <!-- *********************** -->
  <table align="center">
  <tr><td>
  <!-- CABECERA DEL DOCUMENTO -->
  <table width="679" align="right" id="cabecera" cellpadding="0" cellspacing="0">
  <tr>
   <td width="3"></td>
   <td width="124" align="center"><img src="imagenes/logo-CMC.png" style="width:120px" /></td>
   <td width="10"></td>
   <td width="420">
   <!-- *********************** -->
   <table cellpadding="0" cellspacing="0">
   <tr>
      <td align="center" width="414"><font size="3" face="Arial">REPUBLICA BOLIVARIANA DE VENEZUELA</font></td>
   </tr>
   <tr>
      <td align="center"><font size="3" face="Arial">CONTRALORIA DEL ESTADO DELTA AMACURO</font></td>
   </tr>
   <tr>
      <td align="center"><font size="3" face="Arial"><?=$fb['Dependencia']?></font></td>
    </tr>
   </table>
   <!-- *********************** -->
   </td>
   <td width="120" align="center"><img src="imagenes/logoContraloria.jpg" style="height:80px; width:80px"/></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  </table>
  <!-- FIN CABECERA DEL DOCUMENTO -->
  </td></tr>
  </table>
  <!-- *********************** -->
  
</td></tr>
<tr><td>
   <!-- *********************** -->
   <table width="688" height="27" id="numero_doc"> 
   <tr>
    <td width="26" height="21"></td>
    <td width="26"></td>
    <td width="26"></td>
    <td width="436"></td>
    <td width="150"><font face="Arial"><b>N°:<?=$cod_documento?></b></font></td>
   </tr>
   </table>
   <!-- *********************** -->
</td></tr>

<tr><td>
   <!-- *********************** -->
   <table id="titulo">
   <tr>
    <td width="0"></td>
    <td width="0"></td>
    <td width="268"></td>
    <td width="148" align="center"><font size="3" face="Arial"><b>MEMORANDUM</b></font></td>
    <td width="0"></td>
  </tr>
  <tr><td height="5"></td></tr>
  </table>
  <!-- *********************** -->
</td></tr>

<tr><td>
  <!-- CUERPO 1 DEL DOCUMENTO -->
  <table id="cuerpo1" cellpadding="0" cellspacing="0">
  <tr>
    <td width="34"></td>
    <td width="79"><font face="Arial" size="3"><b>PARA:</b></font></td>
    <td width="563"><font face="Arial" size="3"><?=htmlentities($fc['NomCompleto']);?></font></td>
  </tr>
  <tr>
    <td width="34"></td>
    <td width="79"></td>
    <td width="563"><font face="Arial" size="3"><?=$fc['DescripCargo']?></font></td>
  </tr>
   <tr><td height="5"></td></tr>
  <tr>
    <td></td>
    <td><font face="Arial" size="3"><b>DE:</b></font></td>
    <td><font face="Arial" size="3"><?=$fb['Dependencia']?></font></td>
  </tr>
   <tr><td height="5"></td></tr>
  <tr>
    <td></td>
    <td><font face="Arial" size="3"><b>FECHA:</b></font></td>
    <?php 
	 @list($a, $m, $d)=preg_split( '/[.-]/', $fa['FechaDocumento']); $f_documento=$d.'-'.$m.'-'.$a
	?>
    <td><font face="Arial" size="3"><?=$f_documento?></font></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td></td>
    <td><font face="Arial" size="3"><b>ASUNTO:</b></font></td>
    <td><font face="Arial" size="3"><?=$fa['Asunto']?></font></td>
  </tr>
  </table>
  <!-- *********************** -->
</td></tr>

<tr><td>
  <!-- CONTENIDO DEL DOCUMENTO -->
  <table width="701">
  <tr>
    <td width="28"></td>
    <td width="646"><div style="width:650px;"><font face="Arial" size="3"><?=$fa['Contenido']?></font></div></td>
    <td width="11"></td>
  </tr>
  </table>
  <!-- *********************** -->
<tr><td>
  <!-- *********************** -->
  <table align="center" id="atentamente" width="500">
  <tr>
    <td width="71"></td>
    <td align="center"><font face="Arial" size="3">Atentamente,</font></td>
    <td width="81"></td>
  </tr>
  <tr>
    <td width="71"></td>
    <td height="25"></td>
    <td width="81"></td>
  </tr>
  <tr>
    <td width="71"></td>
    <td align="center"></td>
    <td width="81"></td>
  </tr>
  <tr>
  <?
   $sd = "select * from rh_puestos where CodCargo = '".$fa['Cod_CargoRemitente']."'";
   $qd = mysql_query($sd) or die ($sd.mysql_error()); //echo $sa;
   $fd = mysql_fetch_array($qd); 
  ?>
    <td width="71"></td>
    <td align="center" width="365"><font face="Arial" size="3"><?=$fb['NomCompleto']?></font></td>
    <td width="81"></td>
  </tr>
  <tr>
    <td width="71"></td>
    <td align="center" width="365"><font face="Arial" size="3"><?=$fd['DescripCargo']?></font></td>
    <td width="81"></td>
  </tr>
  </table>
  <!-- *********************** -->
</td></tr>

<tr><td>
  <!-- *********************** -->
  <table width="686" id="pie_pagina">
  <tr>
     <td width="26"></td>
     <td width="70"><font face="Arial" size="2"><?=$fa['MediaFirma']?></font></td>
     <td width="421"></td>
     <td width="149" align="right"></td>
  </tr>
  </table>
  <!-- *********************** -->
</td></tr>
<!--<center><input type="button" name="imprimir" value="Imprimir" onclick="window.print();"></center>-->
</table>
<? 
}
} else{
  if ($fcon['Procedencia']=='EXT'){   
   
?>
<table align="center">
<tr>
  <td><font size="4"><b>Documento de Procedencia Externa</b></font></td>
</tr>
</table>
<? 
}}
?>
</td></tr>
<div id="divButtons" name="divButtons">  
<input type="button" id="imprimir" name="imprimir" value = "Imprimir" onclick="printPage()"/> 
</div> 
</table>
</body>
</html>
