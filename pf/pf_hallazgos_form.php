<?php include('pf_header.php');?>
<?php $_PARAMETRO = parametros();?>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$values['titulo']?></td>
		<td align="right"><a class="cerrar" href="framemain.php" onclick="window.close()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />
<?php if(isset($values['error']) and count($values['error'])>0):?>
	<?php //print_r($values['error']);?>
	<?php foreach($values['error'] as $error):?>
			<!--<label class="alert alert-danger"><?php echo $error;?></label>-->
	<?php endforeach;?>
<?php endif;?>

<form name="frmentrada" id="frmentrada" action="pf_hallazgos_controller.php" method="POST" onsubmit="return hallazgos(this, '<?=$accion?>');" enctype="multipart/form-data">
<div id="tabs_form">
	<ul>
		<li><a href="#tabs_form-1">Datos Generales</a></li>
		<li><a href="#tabs_form-2">Documentos</a></li>
	</ul>
	<div id="tabs_form-1">
		
						<?php if(isset($values['msg']) and count($values['msg'])>0):?>
							<?php echo $values['msg'];?>
						<?php endif;?>	
						<input type='text' name="registro" value="<?php if(isset($values['registro'])) echo $values['registro'];?>">
                                                <input type='text' name="CodActuacion" value="<?php if(isset($values['registro'])) echo $values['registro'];?>">
						<input type='text' name="action" value="<?php if(isset($values['action'])) echo $values['action'];?>">
						<input type='text' name="NumCedula" value="<?php if(isset($values['NumCedula'])) echo $values['NumCedula'];?>">
                                                <input type='text' name="Estado" value="<?php if(isset($values['Estado'])) echo $values['Estado'];?>">
						<input type='text' name="FechaCedula" value="<?php if(isset($values['FechaCedula'])) echo $values['FechaCedula'];?>">
                                                <table width="90%" class="tblForm">
                                                        <tr>
                                                                <th class="tagForm" width="125">* Código de la Actuación:</th>
                                                                <td colspan="2">
                                                                        <label class="tagForm"><?=$field_actuacion['CodActuacion']?></label>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <th class="tagForm" width="125">* Nombre de la Actuación:</th>
                                                                <td colspan="2">
                                                                        <?=$field_actuacion['CodActuacion']?>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <th class="tagForm" width="125">* Ente o Dependencia:</th>
                                                                <td colspan="2">
                                                                        <?=$field_actuacion['CodOrganismoExterno']?> - <?=$field_actuacion['CodDependenciaExterna']?>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <th class="tagForm" width="125">* Periodo Evaluado:</th>
                                                                <td colspan="2">
                                                                        <?=$field_actuacion['FechaInicio']?> - <?=$field_actuacion['FechaTermino']?>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <th class="tagForm" width="125">* Objetivo General:</th>
                                                                <td colspan="2">
                                                                        <p><?=$field_actuacion['ObjetivoGeneral']?></p>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <th class="tagForm" width="125">* Alcance:</th>
                                                                <td colspan="2">
                                                                        <p><?=$field_actuacion['Alcance']?></p>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <th class="tagForm" width="125">* Objetivo Específico:</th>
                                                                <td colspan="2">
                                                                <?php $detalle_objetivos_especificos = preg_split("/[;]+/", $field_actuacion['ObjetivosEspecificos']);?>
                                                                        <select name="ObjetivoEspecifico">
                                                                                <option value="">Seleccione...</option>
                                                                <?php if(count($detalle_objetivos_especificos )>0):?>
                                                                        <?php foreach($detalle_objetivos_especificos as $det):?>
                                                                                <?php if($det!=""):?>
                                                                                        <option value="<?php echo $det?>" <?php if(isset($values['ObjetivoEspecifico']) and $values['ObjetivoEspecifico'] == $det) echo "selected = 'selected'"?>><?php echo $det?></option>
                                                                                <?php endif;?>
                                                                        <?php endforeach;?>
                                                                <?php endif;?>
                                                                        </select>
                                                                        <?php if(isset($values['error']['ObjetivoEspecifico'])):?>
                                                                        <br>
                                                                                <label class="alert alert-danger"><?php echo $values['error']['ObjetivoEspecifico'];?></label>
                                                                        <?php endif;?>

                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <th>* CodCedula</th>
                                                                <td colspan="2">
                                                                        <?php if(!isset($values['CodCedula']) or $values['CodCedula']==''){?>
                                                                        <input name="CodCedula" type="hidden" maxlength="20" size="20" value="<?php if(isset($values['CodCedula'])) echo $values['CodCedula'];?>">
                                                                        <?php }else{;?>
                                                                        <input name="CodCedula" type='hidden' value="<?php if(isset($values['CodCedula'])) echo $values['CodCedula'];?>">
                                                                        <?php if(isset($values['CodCedula'])) echo $values['CodCedula'];?>
                                                                        <?php }?>

                                                                        <?php if(isset($values['error']['CodCedula'])) echo $values['error']['CodCedula'];?>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <th>* Condición</th>
                                                                <td colspan="2">
                                                                        <textarea name="Condicion" cols="80"><?php if(isset($values['Condicion'])) echo $values['Condicion'];?></textarea>
                                                                        <?php if(isset($values['error']['Condicion'])):?>
                                                                        <label class="alert alert-danger"><?php echo $values['error']['Condicion'];?></label>
                                                                        <?php endif;?>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <th>* Criterio</th>
                                                                <td colspan="2">
                                                                        <textarea name="Criterio" cols="80"><?php if(isset($values['Criterio'])) echo $values['Criterio'];?></textarea>
                                                                        <?php if(isset($values['error']['criterio'])):?>
                                                                        <label class="alert alert-danger"><?php echo $values['error']['criterio'];?></label>
                                                                        <?php endif;?>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <th>* Causas</th>
                                                                <td colspan="2">
                                                                        <textarea name="Causas" cols="80"><?php if(isset($values['Causas'])) echo $values['Causas'];?></textarea>
                                                                        <?php if(isset($values['error']['Causas'])):?>
                                                                        <label class="alert alert-danger"><?php echo $values['error']['Causas'];?></label>
                                                                        <?php endif;?>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <th>* Efectos</th>
                                                                <td colspan="2">
                                                                        <textarea name="Efectos" cols="80"><?php if(isset($values['Efectos'])) echo $values['Efectos'];?></textarea>
                                                                        <?php if(isset($values['error']['Efectos'])):?>
                                                                        <label class="alert alert-danger"><?php echo $values['error']['Efectos'];?></label>
                                                                        <?php endif;?>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <th>* Recomendaciones</th>
                                                                <td colspan="2">
                                                                        <textarea name="Recomendaciones" cols="80"><?php if(isset($values['Recomendaciones'])) echo $values['Recomendaciones'];?></textarea>
                                                                        <?php if(isset($values['error']['Recomendaciones'])):?>
                                                                        <label class="alert alert-danger"><?php echo $values['error']['Recomendaciones'];?></label>
                                                                        <?php endif;?>						

                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <th>* Responsable(s) de la condición</th>
                                                                <td>
                                                                        <a onclick="addResponsable()" style="cursor: pointer;">Agregar [+]</a>
                                                                        <table width="100%">
                                                                                <thead>
                                                                                        <tr>
                                                                                                <th>Cédula</th>
                                                                                                <th>Nombres</th>
                                                                                                <th>Apellidos</th>
                                                                                        </tr>										
                                                                                </thead>

                                                                                <tbody id="responsables">

                                                                                        <?php if(isset($values['Responsables']) and $values['Responsables']!=''):?>
                                                                                                <?php $detalle_responsables = preg_split("/[;]+/", $values['Responsables']);?>
                                                                                                <?php if(count($detalle_responsables)>0):?>
                                                                                                        <?php $i = 0;foreach($detalle_responsables as $det):?>
                                                                                                        <?php @list($nacion,$cedula,$nombres,$apellidos)= preg_split("/[|]+/", $det);?>

                                                                                                        <?php if($nacion!="" and $cedula !="" and $nombres != "" and $apellidos !=""):?>			
                                                                                                        <tr id="tr_responsables_<?php echo $i;?>">

                                                                                                                <td>
                                                                                                                        <select name="nacion[<?php echo $i;?>]"><option value="V">V</option><option value="E">E</option></select>
                                                                                                                        <input type="text" name="cedula[<?php echo $i;?>]" value="<?php echo $cedula?>"/>
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                        <input type="text" name="nombres[<?php echo $i;?>]" value="<?php echo $nombres?>"/>
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                        <input type="text" name="apellidos[<?php echo $i;?>]" value="<?php echo $apellidos?>" />
                                                                                                                        <a onclick="deleteResponsable(<?php echo $i;?>)">Eliminar [-]</a>
                                                                                                                </td>
                                                                                                        </tr>
                                                                                                        <?php endif;?>


                                                                                                        <?php $i++;endforeach;?>

                                                                                                <?php endif;?>
                                                                                        <?php endif;?>
                                                                                </tbody>

                                                                        </table>
                                                                        <?php if(isset($values['error']['Responsable'])) echo $values['error']['Responsable'];?>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <th>Soporte Documental</th>
                                                                <td>
                                                                        <a onclick="addSoporte()" style="cursor: pointer;">Agregar [+]</a>
                                                                        <table width="100%">
                                                                                <thead>
                                                                                        <tr>
                                                                                                <th>Número de documento</th>
                                                                                                <th>Observaciones</th>
                                                                                        </tr>										
                                                                                </thead>

                                                                                <tbody id="soportes">

                                                                                        <?php if(isset($values['SoporteDocumental']) and $values['SoporteDocumental']!=''):?>
                                                                                                <?php $detalle_soportes = preg_split("/[;]+/", $values['SoporteDocumental']);?>
                                                                                                <?php if(count($detalle_soportes)>0):?>
                                                                                                        <?php $i = 0;foreach($detalle_soportes as $det):?>
                                                                                                        <?php list($numero_documento,$observaciones)= preg_split("/[|]+/", $det);?>

                                                                                                        <?php if($numero_documento!="" and $observaciones !=""):?>			
                                                                                                        <tr id="tr_soportes_<?php echo $i;?>">

                                                                                                                <td>
                                                                                                                        <input type="text" name="numero_documento[<?php echo $i;?>]" value="<?php echo $numero_documento?>"/>
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                        <input type="text" name="observacion[<?php echo $i;?>]" value="<?php echo $observaciones?>"/>
                                                                                                                        <a onclick="deleteSoporte(<?php echo $i;?>)" style="cursor: pointer;">Eliminar [-]</a>
                                                                                                                </td>
                                                                                                        </tr>
                                                                                                        <?php endif;?>


                                                                                                        <?php $i++;endforeach;?>

                                                                                                <?php endif;?>
                                                                                        <?php endif;?>
                                                                                </tbody>

                                                                        </table>
                                                                        <?php if(isset($values['error']['SoporteDocumental'])) echo $values['error']['SoporteDocumental'];?>
                                                                </td>
                                                        </tr>
                                                </table>
					<center>
						<br />
					<div style="width:600px;" class="divMsj">(*) Campos Obligatorios</div>
					<input type="button" value="Guardar" style="width:80px;" onclick="hallazgos(this.form);"/>
					<input type="button" value="Cancelar" style="width:80px;" onclick="window.location.href = 'pf_hallazgos_controller.php?action=list&registro=<?php echo $values['registro']?>'" />
					</center>
					<br />

		
	</div>
	<div id="tabs_form-2">		
		<input type='hidden' value='0' id='contador'>
		<a href="#" onclick='addField()'>[Agregar + ]</a>
		<div id="div_documentos">
			<table width="100%" border="0" id="documentos" class="tblForm">
				<thead>
				<tr>
					<th>Id</th>
					<th>Archivo</th>
				</tr>
				</thead>
				
			</table>
			
		</div>
	</div>
</div>
					</form>	

<?php include('pf_footer.php');?>
<script>
	$(function() {
		$( "#tabs_form" ).tabs();
	});
</script>
<script>
	$(document).ready(function(){
		
		<?php if(isset($values['CodCedula']) and $values['CodCedula'] !=''):?>
		$.ajax({
			url: "pf_hallazgos_controller.php?action=getDocumentsByCedula&CodCedula=<?php echo $values['CodCedula']?>",
			data: {},
			success: function(data){
				$.each(data, function(i,item){
					$('#documentos').append("<tr><td>" + item._id + "</td><td><a target='_blank' href='<?=$_PARAMETRO["PATHSIA"]?>archivos/pf/"+ item.Anio +"/"+ item.CodActuacion +"/Cedulas/"+ item.filename +"'>" + item.filename +"</a></label><br></td></tr>");
				});
			},
			dataType: "json"
		});
		<?php endif;?>
	});
	
	function addField(){
		var cuenta_totales = parseInt($('#contador').val()) + 1;
		$('#div_documentos').prepend("<input type='file' name='files[]' accept='application/pdf'><br>");
		$('#contador').val(cuenta_totales);
	}
	function addResponsable()
	{
		var d = new Date();
		var clase = d.getTime(); 
		var content = '<tr id="tr_responsables_'+clase+'"><td><select name="nacion['+ clase +']"><option value="V">V</option><option value="E">E</option></select>';
		content+= '<input type="text" name="cedula['+ clase +']" /></td>';
		content+= '<td><input type="text" name="nombres['+ clase +']"/> </td>';
		content+= '<td><input type="text" name="apellidos['+ clase +']"/> <a onclick="deleteResponsable('+clase+')" style="cursor: pointer;">Eliminar [-]</a></td></tr>';
		$('#responsables').append(content);
	}
	function deleteResponsable(clase)
	{
		
		if(confirm("¿Está seguro(a) de eliminar el responsable?"))
		{
			$('#tr_responsables_' + clase).html('');
		}else{
			return false;
		}
		
	}
	function addSoporte()
	{
		var d = new Date();
		var clase = d.getTime(); 
		var content = '<tr id="tr_soportes_'+clase+'">';
		content+= '<td><input type="text" name="numero_documento['+ clase +']" /></td>';
		content+= '<td><input type="text" name="observacion['+ clase +']"/> </td>';
		content+= '<td><a onclick="deleteSoporte('+clase+')">Eliminar [-]</a></td></tr>';
		$('#soportes').append(content);
	}
	function deleteSoporte(clase)
	{
		
		if(confirm("¿Está seguro(a) de eliminar el soporte documental?"))
		{
			$('#tr_soportes_' + clase).html('');
		}else{
			return false;
		}
		
	}
</script>