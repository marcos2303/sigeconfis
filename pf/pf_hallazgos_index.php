<?php include('pf_header.php');?>
	<?php if(isset($values['msg']) and count($values['msg'])>0):?>
		<?php echo $values['msg'];?>
	<?php endif;?>

<table cellpadding="0" cellspacing="0" width="100%">
<tbody>
	<tr>
		<td class="titulo">Cédula de hallazgos</td>
		<td align="right">
			<a class="cerrar" href="#">[cerrar]</a>
		</td>
	</tr>
</tbody>
</table>
<table width="100%" class="tblForm">
	<tr>
		<td>Código Cédula</td>
		<td>Condición</td>
		<td>Criterio</td>
		<td>Causas</td>
		<td>Efectos</td>
		<td>Estado</td>
		<td>Acciones</td>
	</tr>
	<?php while($cedula = mysql_fetch_assoc($cedulas_list)):?>
	<tr>
		<td><?php echo $cedula['CodCedula'];?></td>
		<td><?php echo $cedula['Condicion'];?></td>
		<td><?php echo $cedula['Criterio'];?></td>
		<td><?php echo $cedula['Causas'];?></td>
		<td><?php echo $cedula['Efectos'];?></td>
		<td><?php echo $cedula['Estado'];?></td>
		<td><a href="pf_hallazgos_controller.php?action=edit&registro=<?php echo $cedula['CodActuacion'];?>&CodActuacion=<?php echo $cedula['CodActuacion'];?>&CodCedula=<?php echo $cedula['CodCedula'];?>">Editar/Ver</a></td>
	</tr>
	<?php endwhile;?>
</table>
<form action="pf_hallazgos_controller.php" method="POST">
	<input type="hidden" name='action' value="nuevo">
	<input type="hidden" name='registro' value="<?php echo $values['registro']?>">
	<input type="submit" value="Agregar">	
</form>

<?php include('pf_footer.php');?>