<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>
<body>
<?php
include("fphp.php");
connect();
$registro=$_GET['registro'];
echo "
<form name='frmreporta' action='cargos_relaciones.php' method='POST' onSubmit='return verificarCargoRelaciones(this, \"".$registro."\");'>
<table width='700' class='tblForm'>
  <tr>
    <td class='tagForm'>Tipo:</td>
	  <td>
			<select name='tipo' id='tipo'>
				<option value=''>";
					getTRelacion('', 0);
					echo "
			</select>
		</td>
	</tr>
	<tr>
	  <td class='tagForm'>Relaci&oacute;n:</td>
	  <td><input type='text' name='ente' id='ente' size='75' maxlength='100' /></td>
	</tr>
</table>

<table width='700' class='tblBotones'>
	<tr>
		<td align='right'>
			<input name='btNuevo' type='submit' id='btNuevo' value='Agregar Relaci&oacute;n' />
			<input name='btBorrar' type='button' id='btBorrar' value='Eliminar Relaci&oacute;n' onClick='eliminarSubCargo(this.form, \"cargos_relaciones.php?accion=ELIMINAR&registro=".$_GET['registro']."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_cargorelaciones WHERE Secuencia='".$_POST['det']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT Secuencia, TipoRelacion, EnteRelacionado FROM rh_cargorelaciones WHERE (CodCargo='".$_GET['registro']."') ORDER BY TipoRelacion, Secuencia";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='700' class='tblLista'>
  <tr class='trListaHead'>
		<th width='625' scope='col'>Relaci&oacute;n</th>
		<th width='75' scope='col'>Tipo</th>
	</tr>";
$sw=0;
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	//	SOLAMENTE PARA OBTENER EL ROWSPAN QUE LE VOY A APLICAR A LA FILA
	$sql1="SELECT * FROM rh_cargorelaciones WHERE (TipoRelacion='E') AND CodCargo='".$_GET['registro']."'";
	$query1=mysql_query($sql1) or die ($sql1.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) { $rse=$rows1; $rsi=(int) ($rows-$rows1); }
	//
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
		<td>".$field[2]."</td>";
		if ($field[1]=="E" && $i==0) echo "<td align='center' rowspan='$rse'>Externa</td>";
		if ($field[1]=="I" && $sw==0) { echo "<td align='center' rowspan='$rsi'>Interna</td>"; $sw=1; }
	echo "
	</tr>";
}

echo "
</table>
<script type='text/javascript' language='javascript'>
	totalPuestos($rows);
</script>
</form>";


?>
</body>
</html>