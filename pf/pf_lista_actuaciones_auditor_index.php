<table class="table table-responsive table-condensed table-hover">
	<tr>
		<th colspan="10" class="alert  text-center fondo_vinotinto"><label  >Actuaciones asignadas</label></th>
	</tr>
	<tr>
		<th>Actuación</th>
		<th>Ente</th>
		<th>F.Inicio</th>
		<th>F.Termino</th>
		<th>Estado</th>
	</tr>
<ul class="list-group">
	<?php while ($actuaciones = mysql_fetch_array($query_actuaciones)):?>

	<tr>
		<td><?php echo $actuaciones['CodActuacion'];?></td>
		<td><?php echo $actuaciones['Organismo'];?></td>
		<td><?php echo $actuaciones['FechaInicio'];?></td>
		<td><?php echo $actuaciones['FechaTermino'];?></td>
		<td>
			<?php 
		
			if($actuaciones['Estado'] == 'PR');echo "APROBADO";
												
			?>
		
		</td>
	</tr>

	<?php endwhile; ?>
</ul>
</table>