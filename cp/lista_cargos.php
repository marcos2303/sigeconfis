<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="css1.css" rel="stylesheet" type="text/css"/>-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/estilo.css" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="cp_script.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Cargos</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$MAXLIMIT=30;
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if (@$_POST['filtro']!="") $sql="SELECT * FROM rh_puestos WHERE (CodCargo LIKE '%".$_POST['filtro']."%' OR DescripCargo LIKE '%".$_POST['filtro']."%')";
else $sql="SELECT * FROM rh_puestos";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>
<form name="frmlista" id="frmlista" method="post" action="lista_cargos.php?limit=0&ventana=insertarCargos&tabla=item">
<input type="hidden" name="tabla" id="tabla" value="<?=@$tabla?>"/>
<input type="hidden" id="valor_campos" name="valor_campos" value="<?=@$valor_campos;?>"/>

<input type="hidden" id="id_descp_cargo" name="id_descp_cargo" value="<?=@$id_descp_cargo;?>"/>
<input type="hidden" id="id_cargo" name="id_cargo" value="<?=@$id_cargo;?>"/>

<input type="text" name="Activar" id="Activar" value="<?=@$Activar;?>"/>
<input type="hidden" name="codigo_cargo" id="codigo_cargo" value="<?=@$codigo_cargo;?>"/>

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
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="ventana" id="ventana" value="<?=@$_GET['ventana']?>"/>
<table width="750" class="tblLista">
<thead>
	<tr class="trListaHead">
		<th width="80" scope="col">Cod. Cargo</th>
		<th scope="200">Descripci&oacute;n</th>
		<th width="100" scope="col">Estado</th>
	</tr>
   </thead>
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		if (@$_POST['filtro']!="") $sql="SELECT 
		                                     *
										 FROM 
										     rh_puestos 
									    WHERE 
										   (CodCargo LIKE '%".$_POST['filtro']."%' OR
										    DescripCargo LIKE '%".$_POST['filtro']."%') AND 
											estado='A'
									ORDER BY 
									        CodCargo LIMIT ".$_GET['limit'].", $MAXLIMIT";
		else $sql="SELECT
						*
  					FROM
						rh_puestos
				   WHERE 
				       Estado='A'
			   ORDER BY
                       CodCargo LIMIT ".$_GET['limit'].", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			$id = $field['CodCargo'].'|'.$field['DescripCargo'];
			if($field['Estado']=='A') $estado = "Activo";
			if($ventana=="insertarCargos"){			
			 echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); insertarCargos(this.id,\"".$ventana."\");' id='$id'>
				<td align='center'>".$field['CodCargo']."</td>
				<td align='left'>".$field['DescripCargo']."</td>
				<td align='center'>$estado</td>
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