<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/estilo.css" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista Particulares</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$MAXLIMIT=30;
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if (@$_POST['filtro']!="") $sql="SELECT * FROM cp_particular WHERE (Cedula LIKE '%".$_POST['filtro']."%' OR Nombre LIKE '%".$_POST['filtro']."%' OR Cargo LIKE '%".$_POST['filtro']."%' OR Telefono LIKE '%".$_POST['filtro']."%' OR Direccion LIKE '%".$_POST['filtro']."%' )";
else $sql="SELECT * FROM cp_particular";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>
<form name="frmlista" id="frmlista" method="post" action="lista_particulares.php?limit=0&amp;campo=<?=@$campo?>">
<input type="hidden" name="tabla" id="tabla" value="<?=@$tabla?>" />
<table width="750" class="tblBotones">
<tr>
 <td><div id="rows"></div></td>
 <td width="250">
<?php 
echo "
<table align='center'>
<tr>
<td>
	<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros, ".$_GET['limit'].");' />
	<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$_GET['limit'].");' />
</td>
<td>Del</td><td><div id='desde'></div></td>
<td>Al</td><td><div id='hasta'></div></td>
<td>
	<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros, ".$_GET['limit'].");' />
	<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros, ".$_GET['limit'].");' />
</td>
</tr>
</table>";
?>
		</td>
		<td align="center">
			<input name="filtro" type="text" id="filtro" size="30" value="<?=@$_POST['filtro']?>" /><input type="submit" value="Buscar" />
		</td>
	</tr>
</table>
<?php if(!isset($ventana)) $ventana = ''?>
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="ventana" id="ventana" value="<?="$ventana";?>"/>
<table width="750" class="tblLista">
<thead>	
      <? if(@$ventana=='insertarDestinatarioParticularExt'){ ?>
        <tr class="trListaHead">
		   <th width="70">Cod. Particular</th>
		   <th width="150">Nombre</th>
		   <th width="180">Direcci&oacute;n</th>
           <th width="180">Cargo</th>
		   <!--<th width="200" scope="col">Cargo</th>-->
	   </tr>
      <? }else{ ?>
       <tr class="trListaHead">
		<th width="40" scope="col">Cod. Particular</th>
        <th width="35" scope="col">Nro. Cedula</th>
        <th width="130" scope="col">Nombre</th>
        <th width="130" scope="col">Direcci&oacute;n</th>
        <th width="50" scope="col">Tel&eacute;fono</th>
        <th width="120" scope="col">Cargo</th>
	</tr>
 </thead>
     <? } ?>
    
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		if (@$_POST['filtro']!="") $sql="SELECT 
		                                    *
										 FROM 
										    cp_particular
									    WHERE 
										   (Cedula LIKE '%".$_POST['filtro']."%' OR Nombre LIKE '%".$_POST['filtro']."%' OR Cargo LIKE '%".$_POST['filtro']."%' OR Telefono LIKE '%".$_POST['filtro']."%' OR Direccion LIKE '%".$_POST['filtro']."%' )
									ORDER BY 
									        CodParticular LIMIT ".$_GET['limit'].", $MAXLIMIT";
		else $sql="SELECT
						*
  					FROM
						cp_particular
				  ORDER BY
                       CodParticular LIMIT ".$_GET['limit'].", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			
			if(@$ventana=='insertarDestinatarioParticularExt'){
			 echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); insertarDestinatarioParticularExt(this.id,\"".$ventana."\");' id='b|".$field['CodParticular']."'>
				<td align='center'>".$field['CodParticular']."</td>
				<td align='left'>".htmlentities($field['Nombre'])."</td>
				<td align='left'>".htmlentities($field['Direccion'])."</td>
				<td align='left'>".htmlentities($field['Cargo'])."</td>
			</tr>";
			
			}else{
			
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); selEmpleado(\"".$field['Nombre']."\",\"".$_GET['campo']."\", \"".$field['Cargo']."\",\"".$field['Cedula']."\",\"".$field['Direccion']."\",\"".$field['Telefono']."\");' id='".$field['CodParticular']."'>
				<td align='center'>".$field['CodParticular']."</td>
				<td align='center'>".$field['Cedula']."</td>
				<td align='left'>".$field['Nombre']."</td>
				<td align='left'>".$field['Direccion']."</td>
				<td align='center'>".$field['Telefono']."</td>
				<td align='left'>".$field['Cargo']."</td>
			</tr>";
		   }
		}
	}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes($registros, $rows, ".$_GET['limit'].");
	</script>";				
	?>
</table>
</form>
</body>
</html>