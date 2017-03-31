<?php
$field_mast = array();
$display = "block";

if(!isset($origen)) $origen = '';
if(!isset($disabled_ver)) $disabled_ver = '';
	$riesgos = "SELECT * FROM pf_riesgos "
                . "ORDER BY Orden";
$riesgos = mysql_query($riesgos) or die();

if ($opcion == "nuevo") {

	
	$accion = "nuevo";
	$titulo = "Análisis de riesgos";
	$cancelar = "window.close();";
	$CodOrganismo = $_SESSION["ORGANISMO_ACTUAL"];
	$NomOrganismo = $_SESSION["NOMBRE_ORGANISMO_ACTUAL"];
	if ($_SESSION["CONTROL_FISCAL"] == "S") {
		$CodDependencia = $_SESSION["DEPENDENCIA_ACTUAL"];
		$NomDependencia = $_SESSION["NOMBRE_DEPENDENCIA_ACTUAL"];
		$CodCentroCosto = $_SESSION["CCOSTO_ACTUAL"];
		$NomCentroCosto = $_SESSION["NOMBRE_CCOSTO_ACTUAL"];
	}
	$Estado = "PR";
	$FechaRegistro = date("Y-m-d");
	$FechaInicio = date("Y-m-d");
	$FechaInicioReal = date("Y-m-d");
	$PreparadoPor = $_SESSION["CODPERSONA_ACTUAL"];
	$NomPreparadoPor = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$CodProceso = "01";
	$label_submit = "Guardar";
	$sql = "SELECT
				af.*,
				o.Organismo AS NomOrganismo,
				d.Dependencia AS NomDependencia,
				cc.Abreviatura AS NomCentroCosto,
				de.Dependencia AS NomDependenciaExterna,
				oe.Organismo AS NomOrganismoExterno,
				p1.NomCompleto AS NomPreparadoPor,
				p2.NomCompleto AS NomRevisadoPor,
				p3.NomCompleto AS NomAprobadoPor,			
				(SELECT SUM(afd1.Duracion)
				 FROM
				 	pf_actuacionfiscaldetalle afd1
					INNER JOIN pf_actividades a1 ON (afd1.CodActividad = a1.CodActividad)
				 WHERE
				 	afd1.CodActuacion = af.CodActuacion AND
					a1.FlagNoAfectoPlan = 'S') AS DuracionNo
			FROM
				pf_actuacionfiscal af
				INNER JOIN mastorganismos o ON (af.CodOrganismo = o.CodOrganismo)
				INNER JOIN mastdependencias d ON (af.CodDependencia = d.CodDependencia)
				LEFT JOIN ac_mastcentrocosto cc ON (af.CodCentroCosto = cc.CodCentroCosto)
				INNER JOIN pf_organismosexternos oe ON (af.CodOrganismoExterno = oe.CodOrganismo)
				LEFT JOIN pf_dependenciasexternas de ON (af.CodDependenciaExterna = de.CodDependencia)
				INNER JOIN mastpersonas p1 ON (af.PreparadoPor = p1.CodPersona)
				LEFT JOIN mastpersonas p2 ON (af.RevisadoPor = p2.CodPersona)
				LEFT JOIN mastpersonas p3 ON (af.AprobadoPor = p3.CodPersona)
			WHERE af.CodActuacion = '".$registro."'";
	$query_mast = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_mast)) $field_mast = mysql_fetch_array($query_mast);
        
        
    
        
        
        
}
elseif ($opcion == "modificar" || $opcion == "ver" || $opcion == "revisar" || $opcion == "aprobar" || $opcion == "cerrar" || $opcion == "replanificar") {
	if(!isset($registro)) $registro = '';

       
        $sql = "SELECT * FROM pf_riesgos_detalle WHERE CodActuacion = '$registro'";
 	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field_riesgo = mysql_fetch_array($query);   
        
	//	consulto datos generales
	$sql = "SELECT
				af.*,
				o.Organismo AS NomOrganismo,
				d.Dependencia AS NomDependencia,
				cc.Abreviatura AS NomCentroCosto,
				de.Dependencia AS NomDependenciaExterna,
				oe.Organismo AS NomOrganismoExterno,
				p1.NomCompleto AS NomPreparadoPor,
				p2.NomCompleto AS NomRevisadoPor,
				p3.NomCompleto AS NomAprobadoPor,			
				(SELECT SUM(afd1.Duracion)
				 FROM
				 	pf_actuacionfiscaldetalle afd1
					INNER JOIN pf_actividades a1 ON (afd1.CodActividad = a1.CodActividad)
				 WHERE
				 	afd1.CodActuacion = af.CodActuacion AND
					a1.FlagNoAfectoPlan = 'S') AS DuracionNo
			FROM
				pf_actuacionfiscal af
				INNER JOIN mastorganismos o ON (af.CodOrganismo = o.CodOrganismo)
				INNER JOIN mastdependencias d ON (af.CodDependencia = d.CodDependencia)
				LEFT JOIN ac_mastcentrocosto cc ON (af.CodCentroCosto = cc.CodCentroCosto)
				INNER JOIN pf_organismosexternos oe ON (af.CodOrganismoExterno = oe.CodOrganismo)
				LEFT JOIN pf_dependenciasexternas de ON (af.CodDependenciaExterna = de.CodDependencia)
				INNER JOIN mastpersonas p1 ON (af.PreparadoPor = p1.CodPersona)
				LEFT JOIN mastpersonas p2 ON (af.RevisadoPor = p2.CodPersona)
				LEFT JOIN mastpersonas p3 ON (af.AprobadoPor = p3.CodPersona)
			WHERE af.CodActuacion = '".$registro."'";
	$query_mast = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_mast)) $field_mast = mysql_fetch_array($query_mast);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar análisis de riesgo";
		$cancelar = "window.close();";
		//$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
		$label_submit = "Modificar";
                $disabled_ver = "";
	}
	
	elseif ($opcion == "ver") {
		$disabled_ver = "disabled";
		$display = "none";
		$titulo = "Ver análisis de riesgo";
		$cancelar = "window.close();";
		$display_submit = "display:none;";
		$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
                $disabled_ver = "disabled";
                $display= "none;";
	}
	
	elseif ($opcion == "revisar") {
		$accion = "revisar";
		$disabled_ver = "disabled";
		$display = "none";
		$titulo = "Revisar análisis de riesgo";
		$cancelar = "window.close();";
		$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
		$label_submit = "Revisar";
	}
	
	elseif ($opcion == "aprobar") {
		$accion = "aprobar";
		$disabled_ver = "disabled";
		$display = "none";
		$titulo = "Aprobar análisis de riesgo";
		$cancelar = "window.close();";
		$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
		$label_submit = "Aprobar";
	}
	elseif ($opcion == "replanificar") {
		$accion = "replanificar";
		$disabled_ver = "";
		$titulo = "Replanificar análisis de riesgo";
		$cancelar = "window.close();";
		$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
		$label_submit = "Replanificar";
	}	
	elseif ($opcion == "cerrar") {
		$accion = "cerrar";
		$disabled_ver = "disabled";
		$display = "none";
		$titulo = "Cerrar análisis de riesgo";
		$cancelar = "window.close();";
		$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
		$label_submit = "Cerrar";
	}
	
	$CodProceso = @$field_mast['CodProceso'];
	$CodOrganismo = @$field_mast["CodOrganismo"];
	$CodDependencia = @$field_mast["CodDependencia"];
	$NomOrganismo = @$field_mast["NomOrganismo"];
	$NomDependencia = @$field_mast["NomDependencia"];
	$CodCentroCosto = @$field_mast["CodCentroCosto"];
	$NomCentroCosto = @$field_mast["NomCentroCosto"];
	$Estado = @$field_mast['Estado'];
	$FechaRegistro = @$field_mast['FechaRegistro'];
	$FechaInicio = @$field_mast['FechaInicio'];
	$FechaInicioReal = @$field_mast['FechaInicioReal'];
	$PreparadoPor = @$field_mast['PreparadoPor'];
	$NomPreparadoPor = @$field_mast['NomPreparadoPor'];
	$DuracionTotal = @$field_mast['Duracion'] + @$field_mast['Prorroga'] + @$field_mast['DuracionNo'] - @$field_mast['DiasAdelanto'];
}
//	------------------------------------
?>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	<?php
	if ($opcion == "nuevo") {
		?>
		
		setListaActividades();
		<?
	}
	?>
});
</script>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="lib/form_ajax.php" onsubmit="alert('Datos actualizados correctamente');window.close();" method="POST">
<input type="hidden" name="modulo" id="modulo" value="riesgos" />
<input type="hidden" name="CodActuacion" id="CodActuacion" value="<?php echo $registro;?>" />
<input type="hidden" name="accion" id="accion" value="<?=$action;?>" />
<table width="800" class="tblForm">
        <tr>
            <td colspan="3">DATOS DE LA ACTUACIÓN FISCAL</td>
        </tr>
        <tr>
            <td colspan="">Tipo:<br>            
                <select id="CodTipoActuacion" style="" disabled="disabled">
                <?=loadSelect("pf_tipoactuacionfiscal", "CodTipoActuacion", "Descripcion", @$field_mast['CodTipoActuacion'], 0);?>
                </select></td>
            <td colspan="">Código:<br><b><?=@$field_mast['CodActuacion']?></b></td>
            <td colspan="">Órgano o Ente:<br><?=@$field_mast['NomOrganismoExterno']?></td>
        </tr>    
        <tr>
            <td colspan="3">Objetivo:<br><?=@$field_mast['ObjetivoGeneral']?></td>
        </tr>  
        <tr>
            <td colspan="3">Alcance:<br><?=@$field_mast['Alcance']?></td>
        </tr>   
</table>
<table width="800" class="tblForm">
        <tr>
            <td colspan="5">MATRIZ DE EVALUACIÓN DE RIESGOS</td>
        </tr>
        <tr>
            <td colspan="" width="20%"><label>Riesgos</label></td>
            <td colspan="" width="20%"><label>Componentes</label></td>
            <td colspan="" width="20%"><label>Probabilidad</label></td>
            <td colspan="" width="20%"><label>Impacto</label></td>
            <td colspan="" width="20%"><label>Total</label></td>
        </tr> 
        <?php if(count($riesgos)>0):?>
            <?php $j=0;while ($field = mysql_fetch_array($riesgos)):?>
        <tr>
            <td colspan=""><label><?php echo $field['Tipo']?>: <?php echo $field['Descripcion']?></label></td>
                <td colspan="4">
                    <?php 
                    	$riesgos_componentes = "SELECT * FROM pf_riesgos_componentes where idRiesgo = ".$field['IdRiesgo']." and Estado = 'A' and PorDefecto = 'S' ORDER BY Orden";
                        $riesgos_componentes = mysql_query($riesgos_componentes) or die ($riesgos_componentes.mysql_error());
                    ?>
                    
                    
                    <table width="100%" id='tblRiesgosComponentes'>
                        <tr id="after_tr_<?php echo $field['IdRiesgo'];?>"><td colspan="5" align="center"><a style="display:<?php echo $display;?>;" onclick="buscaRiesgoComponente(<?php echo $field['IdRiesgo']?>)">Agregar componente[+]</a></td></tr>
                        <tbody>
                        <?php $i=0; while ($componentes= mysql_fetch_array($riesgos_componentes)):?>
                        <tr id="tr_<?php echo $field['IdRiesgo']?>_<?php echo $componentes['IdComponente']?>">
                            <td width="25%" class="td_<?php echo $field['IdRiesgo']?>_<?php echo $componentes['IdComponente']?>"><?php echo $componentes['Descripcion']?></td>
                            <td width="25%" class="td_<?php echo $field['IdRiesgo']?>_<?php echo $componentes['IdComponente']?>">
                                <?php $valor_probabilidad = getValorProbabilidad($registro, $field['IdRiesgo'], $componentes['IdComponente']);?>
                                <select <?=$disabled_ver?> class="selectSma2 tamanio_riesgo_<?php echo $field['IdRiesgo']?>" onchange="actualizaTotalLinea(<?php echo $field['IdRiesgo'];?>,<?php echo $componentes['IdComponente'];?>)" id="probabilidad_<?php echo $field['IdRiesgo']?>_<?php echo $componentes['IdComponente']?>" name="probabilidad[<?php echo $field['IdRiesgo']?>][<?php echo $componentes['IdComponente']?>]">
                                    <?php for($k=1;$k<=3;$k++):?>
                                    <option value="<?php echo $k;?>" <?php if($valor_probabilidad == $k) echo "selected='selected'";?>><?php echo $k;?></option>
                                    <?php endfor;?>
                                </select>
                            </td>
                            <td width="25%" class="td_<?php echo $field['IdRiesgo']?>_<?php echo $componentes['IdComponente']?>">
                                <?php $valor_impacto = getValorImpacto($registro, $field['IdRiesgo'], $componentes['IdComponente']);?>
                                <select <?=$disabled_ver?> class="selectSma2" onchange="actualizaTotalLinea(<?php echo $field['IdRiesgo'];?>,<?php echo $componentes['IdComponente'];?>)" id="impacto_<?php echo $field['IdRiesgo']?>_<?php echo $componentes['IdComponente']?>" name="impacto[<?php echo $field['IdRiesgo']?>][<?php echo $componentes['IdComponente']?>]">
                                    <?php for($k=1;$k<=3;$k++):?>
                                    <option value="<?php echo $k;?>" <?php if($valor_impacto == $k) echo "selected='selected'";?>><?php echo $k;?></option>
                                    <?php endfor;?>
                                </select>
                            </td>
                            <td width="25%" class="td_<?php echo $field['IdRiesgo']?>_<?php echo $componentes['IdComponente']?>">
                                <?php $valor_total = getValorTotal($registro, $field['IdRiesgo'], $componentes['IdComponente']);?>

                                <input <?=$disabled_ver?> type="text" size="5" onchange="" class="disabled total_linea_<?php echo $field['IdRiesgo']?>" name="total[<?php echo $field['IdRiesgo']?>][<?php echo $componentes['IdComponente']?>]" id="total_<?php echo $field['IdRiesgo']?>_<?php echo $componentes['IdComponente']?>" min="1" max="3" style="text-align: center;" readonly="" value="<?php echo $valor_total;?>">
                                <a style="display: <?php echo $display;?>" onclick="eliminaRiesgoComponente(<?php echo $field['IdRiesgo']?>,<?php echo $componentes['IdComponente']?>)">[-]</a>
                            </td>
                        </tr>
                        <?php $i++;endwhile;?>
                        </tbody>
                        <tr>
                            <td colspan="3">Total riesgo inherente (expresado en valor absoluto )</td>
                            <td colspan=""><input class="disabled" type="text" readonly="" size="5" value="<?php echo $i;?>" id="total_riesgo_absoluto_<?php echo $field['IdRiesgo']?>" name="total_riesgo_absoluto_<?php echo $field['IdRiesgo']?>" style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td colspan="3">Total riesgo inherente (expresado en valor porcentual)</td>
                            <td colspan=""><input type="text" readonly="" size="5" class="disabled total_riesgo_porcentual" value="<?php echo @round(($i/($i * 9))*100);?>" id="total_riesgo_porcentual_<?php echo $field['IdRiesgo']?>" name="total_riesgo_porcentual_<?php echo $field['IdRiesgo']?>" style="text-align: center;"> %</td>
                        </tr>
                    </table>
                    <input type="hidden" size="3" readonly="" value="<?php echo $i;?>" id="tamanio_riesgo_<?php echo $field['IdRiesgo']?>" name="tamanio_riesgo_<?php echo $field['IdRiesgo'];?>">
                    <input type="hidden" size="3" readonly="" value="<?php echo $i * 9;?>" id="completo_riesgo_<?php echo $field['IdRiesgo']?>" name="completo_riesgo_<?php echo $field['IdRiesgo'];?>">

                </td>
        </tr>
        
            <?php $j++;endwhile;?>
        <?php endif;?>
        <tr>
                <td colspan="4"><b>TOTAL GENERAL</b> (promedio expresado en valor porcentual): Riesgos inherentes+ Riesgo de control + Riesgos de detección</td>
                <td colspan=""><input type="text" readonly="" size="5" class="disabled promedio_total" value="11" id="promedio_total" name="promedio_total"> %</td>
                 
        </tr>
        <tr>
            <td colspan="5"><b>OBSERVACIONES</b></td>                 
        </tr>
        <tr>
            <td colspan="5"><textarea cols="127" rows="10" <?=$disabled_ver?> name="Observaciones"><?php if(isset($field_riesgo['Observaciones'])) echo $field_riesgo['Observaciones'];?></textarea></td>                 
        </tr>
</table>
<input type="hidden" size="3" value="<?php echo $j;?>" id="riesgos" name="riesgos">

<center>
<input type="submit" value="<?=$label_submit?>" style="width:80px; <?=@$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="<?=@$cancelar?>" />
</center>
<br />
<div style="width:800px; <?=@$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
</form>
<div id="dialog"></div>
<script>

actualizaTotalLinea(1, 1);
actualizaTotalLinea(2, 9);
actualizaTotalLinea(3, 16);

function actualizaTotalLinea(idRiesgo, idComponente){

    var probabilidad = $("#probabilidad_"+ idRiesgo +"_"+idComponente+"").val();
    var impacto = $("#impacto_"+ idRiesgo +"_"+idComponente+"").val();
    var total = (parseInt(probabilidad) * parseInt(impacto));
    $("#total_"+ idRiesgo +"_" + idComponente+"").val(total);
 
    var tamanio_riesgo = $('#tamanio_riesgo_'+idRiesgo).val();
    //alert(tamanio_riesgo);
    var completo_riesgo =  $('#completo_riesgo_'+idRiesgo).val();
    //efectuo la sumatoria de los totales por riesgo
    var total_riesgo_seleccionado = 0;
    $(".total_linea_" + idRiesgo).each(function() {
        total_riesgo_seleccionado+= parseInt($(this).val());
    });
    
    var valor_absoluto_riesgo = total_riesgo_seleccionado;
    if(completo_riesgo<=0){
        var valor_porcentual_riesgo = 0;
    }else{
        var valor_porcentual_riesgo = ((parseInt(total_riesgo_seleccionado)/completo_riesgo) * 100);
    }
    
    $("#total_riesgo_absoluto_" + idRiesgo).val(valor_absoluto_riesgo);
    $("#total_riesgo_porcentual_" + idRiesgo).val(parseFloat(valor_porcentual_riesgo).toFixed(0));
    //parseFloat(valor_porcentual_riesgo).toFixed(0);
    //alert(valor_porcentual_riesgo);
    
    var total_promedio = 0;
    $(".total_riesgo_porcentual").each(function() {
        total_promedio+= parseInt($(this).val());
    });
    var riesgos = $('#riesgos').val();
    var promedio = total_promedio / parseInt(riesgos);
    $('#promedio_total').val(parseFloat(promedio).toFixed(0));
   
    
}
function eliminaRiesgoComponente(idRiesgo,idComponente){

    $("#tr_"+idRiesgo+"_"+idComponente).remove();
    var tamanio_riesgo = parseInt($('#tamanio_riesgo_'+ idRiesgo).val()) -1;
    $('#tamanio_riesgo_' + idRiesgo).val(tamanio_riesgo);
    
    var completo_riesgo =  parseInt($('#completo_riesgo_'+idRiesgo).val()) - 9;
    $('#completo_riesgo_'+idRiesgo).val(completo_riesgo);
    actualizaTotalLinea(idRiesgo, idComponente);    
}
function buscaRiesgoComponente(idRiesgo){
    $.ajax({
        url: 'http://192.168.5.6/sigeconfis/lib/listas/listado_componentes.php?filtrar=default&ventana=actuacion_fiscal_auditores_insertar&iframe=true&width=950&height=525&idRiesgo='+idRiesgo,
        //data: data,
        success: function(html){
            //alert('success');
            $('#dialog').html(html);
            
            $( "#dialog" ).dialog({
                width: '90%',
                height: '500'
            });
        }
    });
       

}
function agregaRiesgoComponente(idRiesgo,idComponente,Descripcion){
        //alert('#tr_'+idRiesgo +'_' + idComponente);
        if($('#tr_'+idRiesgo +'_' + idComponente).length){
                alert('Ya se encuentra seleccionado el componente: ' + Descripcion);
                return false;
        }
        
        $('#dialog').dialog('close');
        
        $('#after_tr_'+idRiesgo).closest("tr").after('<tr id="tr_'+idRiesgo+'_'+idComponente+'">'
                +'<td class="td_'+idRiesgo+'_'+idComponente+'">' + Descripcion + '</td>'
                + '<td class="td_'+idRiesgo+'_'+idComponente+'"><select class="selectSma2 tamanio_riesgo_'+idRiesgo+'" onchange="actualizaTotalLinea('+idRiesgo+','+idComponente+')" id="probabilidad_'+idRiesgo+'_'+idComponente+'" name="probabilidad['+idRiesgo+']['+idComponente+']"><option value="1">1</option><option value="2">2</option><option value="3">3</option></select>'
                + '<td class="td_'+idRiesgo+'_'+idComponente+'"><select class="selectSma2" onchange="actualizaTotalLinea('+idRiesgo+','+idComponente+')" id="impacto_'+idRiesgo+'_'+idComponente+'" name="impacto['+idRiesgo+']['+idComponente+']"><option value="1">1</option><option value="2">2</option><option value="3">3</option></select>'
                + '<td class="td_'+idRiesgo+'_'+idComponente+'"><input size="5" onchange="" class="disabled total_linea_'+idRiesgo+'" name="total['+idRiesgo+']['+idComponente+']" id="total_'+idRiesgo+'_'+idComponente+'" min="1" max="3" style="text-align: center;" readonly="" value="1" type="text"> <a  onclick="eliminaRiesgoComponente('+idRiesgo+','+idComponente+')"> [-]</a></td>'
                + '</tr>');
        
        
    var tamanio_riesgo = parseInt($('#tamanio_riesgo_'+ idRiesgo).val()) + 1;
    $('#tamanio_riesgo_' + idRiesgo).val(tamanio_riesgo);        
    var completo_riesgo =  parseInt($('#completo_riesgo_'+idRiesgo).val()) + 9;
    $('#completo_riesgo_'+idRiesgo).val(completo_riesgo);
    actualizaTotalLinea(idRiesgo, idComponente);    
    
    
    
    
    
}
</script>
