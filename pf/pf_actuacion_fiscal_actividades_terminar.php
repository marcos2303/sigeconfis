<?php
$values = $_REQUEST;
list($CodActuacion, $CodActividad) = preg_split("/[.]/", $registro);
$sql = "SELECT
			vj.CodActuacion,
                        vj.Anio,
			vj.CodOrganismoExterno,
			vj.CodDependenciaExterna,
			vj.ObjetivoGeneral,
			vjd.Secuencia,
			vjd.FechaInicioReal,
			vjd.FechaTerminoReal,
			(vjd.Duracion + vjd.Prorroga) AS DiasReal,
			a.CodActividad,
			a.Descripcion AS NomActividad,
			a.FlagAutoArchivo,
			a.FlagNoAfectoPlan,
			oe.Organismo As NomOrganismoExterno,
			de.Dependencia As NomDependenciaExterna
		FROM
			pf_actuacionfiscal vj
			INNER JOIN pf_actuacionfiscaldetalle vjd ON (vj.CodActuacion = vjd.CodActuacion)
			INNER JOIN pf_actividades a ON (vjd.CodActividad = a.CodActividad)
			INNER JOIN pf_fases f ON (a.CodFase = f.CodFase)
			INNER JOIN pf_organismosexternos oe ON (vj.CodOrganismoExterno = oe.CodOrganismo)
			LEFT JOIN pf_dependenciasexternas de ON (vj.CodDependenciaExterna = de.CodDependencia)
		WHERE
			vj.CodActuacion = '".$CodActuacion."' AND
			a.CodActividad = '".$CodActividad."'";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));

if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
if ($field['FlagAutoArchivo'] == "S") {
	$FlagAutoArchivo = "checked";
	$AutoArchivo = "S";
} else {
	$FlagAutoArchivo = "";
	$AutoArchivo = "N";
	$display_auto = "display:none;";
}
?>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Terminar Actividades</td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />
<?php if(isset($values['error'])):?>
<label class="msjError"><?php echo $error;?><br></label>
<?php endif;?>
<form name="frmentrada" id="frmentrada" action="lib/form_ajax.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="modulo" id="modulo" value="actuacion_fiscal_actividades_terminar" />
<input type="hidden" name="concepto" id="concepto" value="<?=@$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=@$lista?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=@$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=@$fbuscar?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=@$fordenar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=@$maxlimit?>" />
<input type="hidden" name="forganismo" id="forganismo" value="<?=@$forganismo?>" />
<input type="hidden" name="fdependencia" id="fdependencia" value="<?=@$fdependencia?>" />
<input type="hidden" name="fregistrod" id="fregistrod" value="<?=@$fregistrod?>" />
<input type="hidden" name="fregistroh" id="fregistroh" value="<?=@$fregistroh?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=@$fedoreg?>" />
<input type="hidden" name="forganismoext" id="forganismoext" value="<?=@$forganismoext?>" />
<input type="hidden" name="fnomorganismoext" id="fnomorganismoext" value="<?=@$fnomorganismoext?>" />
<input type="hidden" name="fdependenciaext" id="fdependenciaext" value="<?=@$fdependenciaext?>" />
<input type="hidden" name="fnomdependenciaext" id="fnomdependenciaext" value="<?=@$fnomdependenciaext?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=@$fordenar?>" />
<input type="hidden" name="fanio" id="fanio" value="<?=@$fanio?>" />
<input type="hidden" name="FlagNoAfectoPlan" id="FlagNoAfectoPlan" value="<?=@$field['FlagNoAfectoPlan']?>" />
<input type="hidden" name="AutoArchivo" id="AutoArchivo" value="<?=@$AutoArchivo?>" />
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm" width="100">Actuaci&oacute;n:</td>
		<td>
        	<input type="hidden" id="Secuencia" value="<?=@$field['Secuencia']?>" name = "Secuencia"/>
			<input type="text" id="CodActuacion" value="<?=@$field['CodActuacion']?>" style="width:110px;" class="codigo" name = "CodActuacion" readonly=""/> - 
			<input type="text" id="CodActividad" value="<?=@$field['CodActividad']?>" style="width:90px;" class="codigo" name = "CodActividad" readonly=""/>
                        <input type="hidden" id="Anio" value="<?=@$field['Anio']?>" style="width:90px;" class="codigo" name = "Anio" readonly=""/>
                        
                </td>
		<td class="tagForm" width="100">F.Inicio Real:</td>
		<td>
        	<input type="text" id="FechaInicioReal" name="FechaInicioReal" value="<?=formatFechaDMA(@$field['FechaInicioReal'])?>" style="width:78px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Actividad:</td>
		<td>
        	<input type="text" id="NomActividad" name="NomActividad" value="<?=$field['NomActividad']?>" style="width:95%;" class="disabled" disabled="disabled" />
		</td>
		<td class="tagForm">F.Termino Real:</td>
		<td>
        	<input type="text" id="FechaTerminoReal" name="FechaTerminoReal" value="<?=formatFechaDMA($field['FechaTerminoReal'])?>" style="width:78px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td align="right">Ente Externo:</td>
		<td>
            <input type="hidden" id="CodOrganismoExterno" name="CodOrganismoExterno" value="<?=$field['CodOrganismoExterno']?>" />
			<input type="text" id="NomOrganismoExterno" value="<?=$field['NomOrganismoExterno']?>" style="width:95%;" class="disabled" disabled="disabled" />
		</td>
		<td class="tagForm">Dias:</td>
		<td>
        	<input type="text" id="Duracion" name="Duracion" value="<?=$field['DiasReal']?>" style="width:25px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>
            <input type="hidden" id="CodDependenciaExterna" name="CodDependenciaExterna" value="<?=$field['CodDependenciaExterna']?>" />
			<input type="text" id="NomDependenciaExterna" name="NomDependenciaExterna" value="<?=$field['NomDependenciaExterna']?>" style="width:95%;" class="disabled" disabled="disabled" />
		</td>
		<td class="tagForm">F.Registro Cierre:</td>
		<td>
        	<input type="text" id="FechaRegistroCierre" name="FechaRegistroCierre" value="<?=date("d-m-Y")?>" style="width:78px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Objetivo General:</td>
		<td colspan="3">
        	<textarea id="ObjetivoGeneral" name="ObjetivoGeneral" style="width:98%; height:100px;" class="disabled" disabled="disabled"><?=$field['ObjetivoGeneral']?></textarea>
		</td>
	</tr>
	<tr style=" <?=$display_auto?>">
		<td class="tagForm">&nbsp;</td>
		<td colspan="3">
        	<input type="checkbox" id="FlagAutoArchivo" name="FlagAutoArchivo" value="S" <?=$FlagAutoArchivo?> /> Auto de Archivo
		</td>
	</tr>
    
	<tr>
		<td class="tagForm">* F.Terminado:</td>
		<td>
			<input readonly="readonly" type="text" id="FechaTerminoCierre"  name="FechaTerminoCierre" value="<?=formatFechaDMA($field['FechaTerminoReal'])?>" style="width:78px;" class="datepicker" onkeyup="setFechaDMA(this);" onchange="getDiasHabiles($('#FechaInicioReal').val(), this.value, document.getElementById('DiasCierre'));" />
		</td>
		<td class="tagForm">Duraci&oacute;n:</td>
		<td>
        	<input type="text" id="DiasCierre" name="DiasCierre" value="<?=@$field['DiasReal']?>" style="width:25px;" class="disabled" /> Dias
		</td>
	</tr>
	<tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="3">
        	<textarea id="Observaciones"  name="Observaciones" style="width:98%; height:50px;"></textarea>
		</td>
	</tr>
</table>

<br />
<div style="width:800px; <?=@$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>

<?php 

$array_actividades_sin_anexos = array(1);//significa que no tienen permitido anexarles documentos, porque generan uno por si mismas

?>

<input type="hidden" name="sel_documentos" id="sel_documentos" name="sel_documentos"/>
<input type = 'hidden' value='0' name='cuenta_documentos' id='cuenta_documentos'> 
<table width="80%" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" class="btLista" value="Insertar" onclick="insertarLinea2(this, 'actuacion_fiscal_actividades_terminar_documento', 'documentos', true)" />
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'documentos');" />
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:80%; height:150px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="">#</th>
		<th scope="col" width="">Adjunto</th>
        <th scope="col" width="">Nro. Documento</th>
        <th scope="col" width="">Fecha</th>
    </tr>
    </thead>
    
    <tbody id="lista_documentos">
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_documentos" value="<?=@$nrodocumentos?>" />
<input type="hidden" id="can_documentos" value="<?=@$nrodocumentos?>" />
<center>
<input type="submit" value="Terminar Actividad" style="width:100px; <?=@$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="window.location.href = 'gehen.php?anz=pf_actuacion_fiscal_actividades&lista=terminar&filtrar=default&concepto=01-0005'" name='cancelar'/>
</center>
</form>