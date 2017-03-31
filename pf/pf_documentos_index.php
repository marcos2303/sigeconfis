<?php while ($fields = mysql_fetch_array($actividad_detalle)):?>
<?php 
$anio = $fields['Anio'];
$CodActuacion = $fields['CodActuacion'];

?>



<table class="tblForm" width="800">
	<tbody><tr>
		<td class="tagForm" width="100">Actuación:</td>
		<td>
        	<input id="Secuencia" value="7" name="Secuencia" type="hidden">
			<input id="CodActuacion" value="<?php echo $fields['CodActuacion']?>" style="width:110px;" class="codigo" name="CodActuacion" readonly="" type="text"> - 
			<input id="CodActividad" value="<?php echo $fields['CodActividad']?>" style="width:90px;" class="codigo" name="CodActividad" readonly="" type="text">
		</td>
		<td class="tagForm" width="100">F.Inicio Real:</td>
		<td>
        	<input id="FechaInicioReal" name="FechaInicioReal" value="<?php echo $fields['FechaInicioReal']?>" style="width:78px;" class="disabled" disabled="disabled" type="text">
		</td>
	</tr>
	<tr>
		<td class="tagForm">Actividad:</td>
		<td>
        	<input id="NomActividad" name="NomActividad" value="<?php echo $fields['Descripcion']?>" style="width:95%;" class="disabled" disabled="disabled" type="text">
		</td>
		<td class="tagForm">F.Termino Real:</td>
		<td>
        	<input id="FechaTerminoReal" name="FechaTerminoReal" value="<?php echo $fields['FechaTerminoReal']?>" style="width:78px;" class="disabled" disabled="disabled" type="text">
		</td>
	</tr>
	<tr>
		<td align="right">Ente Externo:</td>
		<td>
            <input id="CodOrganismoExterno" name="CodOrganismoExterno" value="<?php echo $fields['CodOrganismo']?>" type="hidden">
			<input id="NomOrganismoExterno" value="<?php echo $fields['Organismo']?>" style="width:95%;" class="disabled" disabled="disabled" type="text">
		</td>
		<td class="tagForm">Dias:</td>
		<td>
        	<input id="Duracion" name="Duracion" value="<?php echo $fields['Duracion']?>" style="width:25px;" class="disabled" disabled="disabled" type="text">
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>
            <input id="CodDependenciaExterna" name="CodDependenciaExterna" value="0001" type="hidden">
			<input id="NomDependenciaExterna" name="<?php echo $fields['Dependencia']?>" value="" style="width:95%;" class="disabled" disabled="disabled" type="text">
		</td>
		<td class="tagForm">F.Registro Cierre:</td>
		<td>
        	<input id="FechaRegistroCierre" name="FechaRegistroCierre" value="<?php echo $fields['FechaRegistroCierre']?>" style="width:78px;" class="disabled" disabled="disabled" type="text">
		</td>
	</tr>
	<tr>
		<td class="tagForm">Objetivo General:</td>
		<td colspan="3">
        	<textarea id="ObjetivoGeneral" name="ObjetivoGeneral" style="width:80%; height:50px;" class="disabled" disabled="disabled"><?php echo $fields['ObjetivoGeneral']?></textarea>
		</td>
	</tr>
	<tr style=" display:none;">
		<td class="tagForm">&nbsp;</td>
		<td colspan="3">
        	<input id="FlagAutoArchivo" name="FlagAutoArchivo" value="S" type="checkbox"> Auto de Archivo
		</td>
	</tr>
    
	<tr>
		<td class="tagForm">* F.Terminado:</td>
		<td>
        	<input id="FechaTerminoCierre" name="FechaTerminoCierre" value="<?php echo $fields['FechaTerminoCierre']?>" style="width:78px;" class="datepicker hasDatepicker" onkeyup="setFechaDMA(this);" onchange="getDiasHabiles($('#FechaInicioReal').val(), this.value, document.getElementById('DiasCierre'));" type="text">
		</td>
		<td class="tagForm">Duración:</td>
		<td>
        	<input id="DiasCierre" name="DiasCierre" value="1" style="width:25px;" class="disabled" disabled="disabled" type="text"> Dias
		</td>
	</tr>
	<tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="3">
        	<textarea id="Observaciones" name="Observaciones" style="width:98%; height:50px;"><?php echo $fields['Observaciones']?></textarea>
		</td>
	</tr>
</tbody></table>
<?php endwhile;?>
<table class="tblForm" width="800">
    <tr>
        <th>Número de documento</th>
        <th>Acciones</th>
    </tr>
    

<?php while ($fields = mysql_fetch_array($actividad_documentos)):?>
    <?php $Documento = $fields['Documento'];?>
    <tr>
        <td><?php echo $fields['NroDocumento']?></td>
        <td><a target="_blank" href="<?php echo $GLOBALS['parametros']['PATHSIA']."archivos/pf/$anio/$CodActuacion/$Documento";?>"> Descargar/Ver</a></td>
    </tr>
<?php endwhile;?>
</table>

<script>
/*
	$(document).ready(function(){
		
		
		$.ajax({
			url: "pf_documentos_controller.php?action=getDocumentsByCedula&CodActuacion=<?php echo $values['CodActuacion']?>&CodActividad=<?php echo $values['CodActividad']?>",
			data: {},
			success: function(data){
				$.each(data, function(i,item){
                                    alert(1);
					$('#documentos').append("<tr><td>Descargar/Ver</td><td><a target='_blank' href='pf_documentos_controller.php?action=getDocumentByObjectId&ObjectId="+item._id.$id+"'>" + item.CodActuacion + "-" + item.CodActividad + '-' + item.Fase + '-' + item.filetype +"</a></label><br></td></tr>");
                                        //$('#documentos').append("<tr><td>Descargar/Ver</td><td><a target='_blank' href='pf_documentos_controller.php?action=getDocumentByObjectId&ObjectId="+item._id.$id+"'>" + item.CodActuacion + "-" + item.CodActividad + '-' + item.Fase + '-' + item.filetype +"</a></label><br></td></tr>");
				});
			},
			dataType: "json"
		});
		
	});*/
</script>