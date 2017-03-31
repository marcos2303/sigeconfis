<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$AnioActual-$MesActual-$DiaActual";
//	------------------------------------
if ($opcion == "nuevo") {
	$field['Estado'] = "PR";
	$field['CodOrganismo'] = $_SESSION["ORGANISMO_ACTUAL"];
	$field['CodDependencia'] = $_SESSION["DEPENDENCIA_ACTUAL"];
	$field['CodCentroCosto'] = $_SESSION["CCOSTO_ACTUAL"];
	$field['CodBeneficiario'] = $_SESSION["CODPERSONA_ACTUAL"];
	$field['NomBeneficiario'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$field['CodPersonaPagar'] = $_SESSION["CODPERSONA_ACTUAL"];
	$field['NomPersonaPagar'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$field['PreparadoPor'] = $_SESSION["CODPERSONA_ACTUAL"];
	$field['NomPreparadoPor'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$field['FlagCajaChica'] = "C";
	$field['CodClasificacion'] = "CC";
	$field['CodTipoPago'] = "02";
	$field['Periodo'] = $AnioActual;
	$field['FechaPreparacion'] = $FechaActual;
	//	presupuesto
	$sql = "SELECT CodPresupuesto 
			FROM pv_presupuesto 
			WHERE EjercicioPpto = '".$AnioActual."' AND Organismo = '".$field['CodOrganismo']."'";
	$query_presupuesto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_presupuesto)) $field_presupuesto = mysql_fetch_array($query_presupuesto);
	$field['CodPresupuesto'] = $field_presupuesto['CodPresupuesto'];
	##
	$accion = "nuevo";
	$titulo = "Agregar Caja Chica";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
	$disabled_anular = "disabled";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
	$mostrarTabDistribucion = "mostrarTabDistribucionCajaChica();";
}
elseif ($opcion == "modificar" || $opcion == "ver" || $opcion == "aprobar" || $opcion == "anular") {
	list($FlagCajaChica, $Periodo, $NroCajaChica) = split("[_]", $sel_registros);
	//	consulto datos generales
	$sql = "SELECT
				cc.*,
				p1.NomCompleto AS NomBeneficiario,
				p2.NomCompleto AS NomPreparadoPor,
				p3.NomCompleto AS NomAprobadoPor
			FROM
				ap_cajachica cc
				INNER JOIN mastpersonas p1 ON (p1.CodPersona = cc.CodBeneficiario)
				LEFT JOIN mastpersonas p2 ON (p2.CodPersona = cc.PreparadoPor)
				LEFT JOIN mastpersonas p3 ON (p3.CodPersona = cc.AprobadoPor)
			WHERE
				cc.FlagCajaChica = '".$FlagCajaChica."' AND
				cc.Periodo = '".$Periodo."' AND
				cc.NroCajaChica = '".$NroCajaChica."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Actualizar Caja Chica";
		$disabled_modificar = "disabled";
		$disabled_anular = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Modificar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
		$mostrarTabDistribucion = "mostrarTabDistribucionCajaChica();";
	}
	
	elseif ($opcion == "aprobar") {
		$field['AprobadoPor'] = $_SESSION["CODPERSONA_ACTUAL"];
		$field['NomAprobadoPor'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
		$field['FechaAprobacion'] = $FechaActual;
		##
		$accion = "aprobar";
		$titulo = "Aprobar Caja Chica";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_anular = "disabled";
		$disabled_conceptos = "disabled";
		$display_modificar = "display:none;";
		$display_ver = "display:none;";
		$label_submit = "Aprobar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	elseif ($opcion == "anular") {
		$accion = "anular";
		$titulo = "Anular Caja Chica";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_anular = "disabled";
		$disabled_conceptos = "disabled";
		$display_modificar = "display:none;";
		$display_ver = "display:none;";
		$label_submit = "Aprobar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_anular = "disabled";
		$disabled_conceptos = "disabled";
		$display_modificar = "display:none;";
		$display_submit = "display:none;";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
}
//	
$MontoAutorizado = getVar("ap_cajachicaautorizacion", "Monto", "CodOrganismo", $field['CodOrganismo'], "CodEmpleado", $field['CodBeneficiario']);
//	------------------------------------
$_width = 900;
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$clkCancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<center>
<div class="ui-widget" id="nocumple" style="display:none;">
    <div class="ui-state-error ui-corner-all" style="width:<?=$_width?>px; text-align:left;">
        <p>
        <span class="ui-icon ui-icon-alert" style="float:left;"></span>
        <strong>El empleado NO tiene Monto autorizado para generar Gastos de Caja Chica</strong>
        </p>
    </div>
    <br />
</div>
<div class="ui-widget" id="excede" style="display:none;">
    <div class="ui-state-highlight ui-corner-all" style="width:708px; text-align:left;">
        <p>
        <span class="ui-icon ui-icon-info" style="float: left;"></span>
        <strong>Los Gastos de Caja Chica exceden el <?=$_PARAMETRO['REPCC']?>% del Monto Autorizado</strong>
        </p>
    </div>
</div>
</center>

<table width="<?=$_width?>" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 3);">Informaci&oacute;n General</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 3);">Conceptos del Gasto</a></li>
            <li id="li3" onclick="currentTab('tab', this);"><a href="#" onclick="<?=$mostrarTabDistribucion?>mostrarTab('tab', 3, 3);">Dist. Presupuestaria</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<div id="tab1" style="display:block;">
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_caja_chica_lista" method="POST" enctype="multipart/form-data" onsubmit="return caja_chica(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fCodDependencia" id="fCodDependencia" value="<?=$fCodDependencia?>" />
<input type="hidden" name="fCodCentroCosto" id="fCodCentroCosto" value="<?=$fCodCentroCosto?>" />
<input type="hidden" name="fFechaPreparacion" id="fFechaPreparacion" value="<?=$fFechaPreparacion?>" />
<input type="hidden" id="FlagCajaChica" value="<?=$field['FlagCajaChica']?>" />
<input type="hidden" id="Anio" value="<?=$field['Periodo']?>" />
<input type="hidden" id="CodPresupuesto" value="<?=$field['CodPresupuesto']?>" />

<table width="<?=$_width?>" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos de la Caja Chica</td>
    </tr>
    <tr>
		<td class="tagForm" width="125">Beneficiario:</td>
		<td class="gallery clearfix">
            <input type="hidden" id="CodBeneficiario" value="<?=$field['CodBeneficiario']?>" />
            <input type="text" id="NomBeneficiario" value="<?=$field['NomBeneficiario']?>" style="width:295px;" disabled />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&ventana=caja_chica_beneficiario&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_modificar?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm" width="125">Caja Chica:</td>
		<td>
        	<input type="text" id="NroCajaChica" value="<?=$field['NroCajaChica']?>" style="width:40px;" class="codigo" disabled /> -
            <input type="text" id="Periodo" value="<?=$field['Periodo']?>" style="width:35px; text-align:center;" class="codigo" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Cheque a Nombre de:</td>
		<td class="gallery clearfix">
            <input type="hidden" id="CodPersonaPagar" value="<?=$field['CodPersonaPagar']?>" />
            <input type="text" id="NomPersonaPagar" value="<?=$field['NomPersonaPagar']?>" style="width:295px;" disabled />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&cod=CodPersonaPagar&nom=NomPersonaPagar&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" style=" <?=$display_ver?>" class="aEditable">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm">Estado:</td>
		<td>
        	<input type="hidden" id="Estado" value="<?=$field['Estado']?>" />
        	<input type="text" value="<?=strtoupper(printValores("ESTADO-CAJACHICA", $field['Estado']))?>" style="width:93px;" class="codigo" disabled />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Organismo:</td>
		<td>
            <select id="CodOrganismo" style="width:300px;" class="iEditable" <?=$disabled_ver?>>
                <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field['CodOrganismo'], 1)?>
            </select>
		</td>
        <td colspan="2" class="divFormCaption">Montos Totales</td>
	</tr>
    <tr>
		<td class="tagForm">Dependencia:</td>
		<td>
            <select id="CodDependencia" style="width:300px;" class="iEditable" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastdependencias", "CodDependencia", "Dependencia", "CodOrganismo", $field['CodDependencia'], $field['CodOrganismo'], 0)?>
            </select>
		</td>
		<td class="tagForm">Monto Afecto:</td>
		<td>
        	<input type="text" id="MontoAfecto" value="<?=number_format($field['MontoAfecto'], 2, ',', '.')?>" style="width:100px; text-align:right;" class="currency" disabled />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Centro de Costo:</td>
		<td>
            <select id="CodCentroCosto" style="width:300px;" class="iEditable" <?=$disabled_ver?>>
                <?=loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", $field['CodCentroCosto'], $field['CodDependencia'], 0)?>
            </select>
		</td>
		<td class="tagForm">Monto No Afecto:</td>
		<td>
        	<input type="text" id="MontoNoAfecto" value="<?=number_format($field['MontoNoAfecto'], 2, ',', '.')?>" style="width:100px; text-align:right;" class="currency" disabled />
		</td>
	</tr>
    <tr>
        <td class="tagForm">Preparado Por:</td>
        <td>
            <input type="hidden" id="PreparadoPor" value="<?=$field['PreparadoPor']?>" />
            <input type="text" id="NomPreparadoPor" value="<?=htmlentities($field['NomPreparadoPor'])?>" style="width:225px;" disabled="disabled" />
            <input type="text" id="FechaPreparacion" value="<?=formatFechaDMA($field['FechaPreparacion'])?>" style="width:60px;" disabled="disabled" />
        </td>
		<td class="tagForm">Monto Bruto:</td>
		<td>
        	<input type="text" id="MontoBruto" value="<?=number_format($field['MontoBruto'], 2, ',', '.')?>" style="width:100px; text-align:right;" class="currency" disabled />
		</td>
    </tr>
    <tr>
        <td class="tagForm">Aprobado Por:</td>
        <td>
            <input type="hidden" id="AprobadoPor" value="<?=$field['AprobadoPor']?>" />
            <input type="text" id="NomAprobadoPor" value="<?=htmlentities($field['NomAprobadoPor'])?>" style="width:225px;" disabled="disabled" />
            <input type="text" id="FechaAprobacion" value="<?=formatFechaDMA($field['FechaAprobacion'])?>" style="width:60px;" disabled="disabled" />
        </td>
		<td class="tagForm">Monto Impuesto:</td>
		<td>
        	<input type="text" id="MontoImpuesto" value="<?=number_format($field['MontoImpuesto'], 2, ',', '.')?>" style="width:100px; text-align:right;" class="currency" disabled />
		</td>
	</tr>
    <tr>
        <td class="tagForm">Obligaci&oacute;n:</td>
		<td>
        	<input type="text" id="CodTipoDocumento" value="<?=$field['CodTipoDocumento']?>" style="width:20px;" disabled />
        	<input type="text" id="NroDocumento" value="<?=$field['NroDocumento']?>" style="width:95px;" disabled />
		</td>
		<td class="tagForm">Monto Retenci&oacute;n:</td>
		<td>
        	<input type="text" id="MontoRetencion" value="<?=number_format($field['MontoRetencion'], 2, ',', '.')?>" style="width:100px; text-align:right;" class="currency" disabled />
		</td>
	</tr>
    <tr>
        <td class="tagForm">Nro. Doc. Interno:</td>
		<td>
        	<input type="text" id="NroDocumentoInterno" value="<?=$field['NroDocumentoInterno']?>" style="width:125px;" disabled />
		</td>
		<td class="tagForm">Monto Total:</td>
		<td>
        	<input type="text" id="MontoTotal" value="<?=number_format($field['MontoTotal'], 2, ',', '.')?>" style="width:100px; text-align:right;" class="currency codigo" disabled />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Tipo de Pago:</td>
		<td>
            <select id="CodTipoPago" style="width:130px;" disabled>
                <?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", $field['CodTipoPago'], 1)?>
            </select>
		</td>
		<td class="tagForm"><strong>Monto a Reembolsar</strong>:</td>
		<td>
        	<input type="text" id="MontoNeto" value="<?=number_format($field['MontoNeto'], 2, ',', '.')?>" style="width:100px; text-align:right;" class="currency codigo" disabled />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Clasificaci&oacute;n:</td>
		<td>
            <select id="CodClasificacion" style="width:130px;" disabled>
                <?=loadSelect("ap_clasificaciongastos", "CodClasificacion", "Descripcion", $field['CodClasificacion'], 1)?>
            </select>
		</td>
		<td class="tagForm"><strong>Monto Autorizado</strong>:</td>
		<td>
        	<input type="text" id="MontoAutorizado" value="<?=number_format($MontoAutorizado, 2, ',', '.')?>" style="width:100px; text-align:right;" class="currency codigo" disabled />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td colspan="3">
        	<textarea id="Descripcion" style="width:95%; height:50px;" class="iEditable" <?=$disabled_ver?>><?=htmlentities($field['Descripcion'])?></textarea>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Motivo Rechazo:</td>
		<td colspan="3">
        	<textarea id="RazonRechazo" style="width:95%; height:50px;" <?=$disabled_anular?>><?=htmlentities($field['RazonRechazo'])?></textarea>
		</td>
	</tr>
    <tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>

<center>
<input type="submit" style="display:none;" />
</center>

</form>
</div>

<div id="tab2" style="display:none;">
<center>
<form name="frm_conceptos" id="frm_conceptos" autocomplete="off">
<input type="hidden" id="sel_conceptos" />
<table width="<?=$_width?>" class="tblBotones">
	<thead>
    	<th class="divFormCaption" colspan="2">Conceptos del Gasto</th>
    </thead>
    <tbody>
    <tr>
        <td class="gallery clearfix">
        	<a id="aSelPersona" href="../lib/listas/listado_personas.php?filtrar=default&ventana=caja_chica_persona&cod=CodProveedor&nom=NomProveedor&campo3=DocFiscal&seldetalle=sel_conceptos&iframe=true&width=1000&height=475" rel="prettyPhoto[iframe4]" style="display:none;"></a>           
            <input type="button" class="btLista bEditable" id="btSelPersona" value="Sel. Persona" onclick="validarAbrirLista('sel_conceptos', 'aSelPersona');" <?=$disabled_conceptos?> />
        </td>
        <td align="right" class="gallery clearfix">
            <a id="a_conceptos" href="../lib/listas/listado_concepto_gastos.php?filtrar=default&ventana=caja_chica_conceptos_insertar&detalle=conceptos&iframe=true&width=950&height=600" rel="prettyPhoto[iframe3]" style="display:none;"></a>
            
            <a id="a_distribucion" href="pagina.php?iframe=true" rel="prettyPhoto[iframe5]" style="display:none;"></a>
            <input type="button" class="btLista bEditable" value="Insertar" <?=$disabled_conceptos?> onclick="$('#a_conceptos').click();" />
            <input type="button" class="btLista bEditable" value="Borrar" onclick="quitar(this, 'conceptos');" <?=$disabled_conceptos?> /> |
            <input type="button" class="btLista bEditable" value="Distribución" onclick="caja_chica_distribucion_abrir();" <?=$disabled_conceptos?> />
        </td>
    </tr>
    </tbody>
</table>
<div style="overflow:scroll; width:<?=$_width?>px; height:310px;">
<table width="2100" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">&nbsp;</th>
        <th scope="col" width="60">Fecha</th>
        <th scope="col" width="250" align="left">Concepto</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
        <th scope="col" width="75">Monto Pagado</th>
        <th scope="col" width="100">Tipo Impuesto</th>
        <th scope="col" width="100">Tipo Servicio</th>
        <th scope="col" width="75">Monto Afecto</th>
        <th scope="col" width="75">Monto No Afecto</th>
        <th scope="col" width="75">Monto Impuesto</th>
        <th scope="col" width="75">Monto Retenci&oacute;n</th>
        <th scope="col" colspan="2">Documento</th>
        <th scope="col" width="75">Factura</th>
        <th scope="col" width="75">Doc. Fiscal</th>
        <th scope="col" width="250">Persona</th>
    </tr>
    </thead>
    
    <tbody id="lista_conceptos">
    <?php
	$nro_conceptos = 0;
	$sql = "SELECT
				ccd.*,
				cg.Descripcion AS NomConceptoGasto,
				cg.CodPartida,
				cg.CodCuenta,
				cg.CodCuentaPub20
			FROM
				ap_cajachicadetalle ccd
				INNER JOIN ap_conceptogastos cg ON (ccd.CodConceptoGasto = cg.CodConceptoGasto)
			WHERE
				ccd.FlagCajaChica = '".$FlagCajaChica."' AND
				ccd.Periodo = '".$Periodo."' AND
				ccd.NroCajaChica = '".$NroCajaChica."'
			ORDER BY FechaDocumento, Secuencia";
	$query_conceptos = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_conceptos = mysql_fetch_array($query_conceptos)) {	$nro_conceptos++;
		$id = $nro_conceptos;
		
		//	
		$_Distribucion = "";
		$sql = "SELECT *
				FROM ap_cajachicadistribucion
				WHERE
					FlagCajaChica = '".$FlagCajaChica."' AND
					Periodo = '".$Periodo."' AND
					NroCajaChica = '".$NroCajaChica."' AND
					Secuencia = '".$field_conceptos['Secuencia']."'";
		$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_distribucion = mysql_fetch_array($query_distribucion)) {
			if ($_Distribucion != "") $_Distribucion .= ";";
			$_Distribucion .= "$field_distribucion[CodConceptoGasto]|$field_distribucion[CodCentroCosto]|$field_distribucion[CodPartida]|$field_distribucion[CodCuenta]|$field_distribucion[CodCuentaPub20]|$field_distribucion[Monto]";
		}
		
		?>
        <tr class="trListaBody" onclick="clk($(this), 'conceptos', 'conceptos_<?=$id?>');" id="conceptos_<?=$id?>">
			<th>
				<?=$nro_conceptos?>
			</th>
            <td>
            	<input type="text" name="Fecha" style="text-align:center;" class="cell datepicker iEditable" value="<?=formatFechaDMA($field_conceptos['Fecha'])?>" maxlength="10" <?=$disabled_conceptos?> />
            </td>
			<td>
                <input type="hidden" name="CodConceptoGasto" id="CodConceptoGasto_<?=$id?>" value="<?=$field_conceptos['CodConceptoGasto']?>" />
                <textarea name="NomConceptoGasto" style="height:25px;" class="cell2" readonly="readonly"><?=htmlentities($field_conceptos['NomConceptoGasto'])?></textarea>
			</td>
			<td>
                <textarea name="Descripcion" style="height:25px;" class="cell iEditable" <?=$disabled_conceptos?>><?=htmlentities($field_conceptos['Descripcion'])?></textarea>
			</td>
			<td>
                <input type="text" name="MontoPagado" id="MontoPagado_<?=$id?>" value="<?=number_format($field_conceptos['MontoPagado'], 2, ',', '.')?>" style="text-align:right;" class="cell currency iEditable" onchange="cajaChicaMontoPagado('<?=$id?>');" <?=$disabled_conceptos?> />
			</td>
            <td>
                <select name="CodRegimenFiscal" style="width:130px;" class="cell iEditable" onChange="getOptionsSelect(this.value, 'tipo-servicio', 'CodTipoServicio_<?=$id?>', 1);">
                    <?=loadSelect("ap_regimenfiscal", "CodRegimenFiscal", "Descripcion", $field_conceptos['CodRegimenFiscal'], 0)?>
                </select>
            </td>
            <td>
                <select name="CodTipoServicio" id="CodTipoServicio_<?=$id?>" style="width:130px;" class="cell iEditable">
                	<?=loadSelectDependiente("masttiposervicio", "CodTipoServicio", "Descripcion", "CodRegimenFiscal", $field_conceptos['CodTipoServicio'], $field_conceptos['CodRegimenFiscal'], 0)?>
                </select>
            </td>
			<td>
                <input type="text" name="MontoAfecto" id="MontoAfecto_<?=$id?>" value="<?=number_format($field_conceptos['MontoAfecto'], 2, ',', '.')?>" style="text-align:right;" class="cell currency iEditable" onchange="cajaChicaMontoAfecto('<?=$id?>');" <?=$disabled_conceptos?> />
			</td>
			<td>
                <input type="text" name="MontoNoAfecto" id="MontoNoAfecto_<?=$id?>" value="<?=number_format($field_conceptos['MontoNoAfecto'], 2, ',', '.')?>" style="text-align:right;" class="cell currency iEditable" onchange="cajaChicaMontoNoAfecto('<?=$id?>');" <?=$disabled_conceptos?> />
			</td>
			<td>
                <input type="text" name="MontoImpuesto" id="MontoImpuesto_<?=$id?>" value="<?=number_format($field_conceptos['MontoImpuesto'], 2, ',', '.')?>" style="text-align:right;" class="cell currency" <?=$disabled_conceptos?> />
			</td>
			<td>
                <input type="text" name="MontoRetencion" id="MontoRetencion_<?=$id?>" value="<?=number_format($field_conceptos['MontoRetencion'], 2, ',', '.')?>" style="text-align:right;" class="cell currency iEditable" <?=$disabled_conceptos?> />
			</td>
            <td width="45">
                <select name="CodTipoDocumento" class="cell iEditable">
                    <?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", $field_conceptos['CodTipoDocumento'], 10)?>
                </select>
            </td>
			<td width="125">
                <input type="text" name="NroDocumento" class="cell iEditable" value="<?=$field_conceptos['NroDocumento']?>" maxlength="20" <?=$disabled_conceptos?> />
			</td>
			<td>
                <input type="text" name="NroRecibo" class="cell iEditable" value="<?=$field_conceptos['NroRecibo']?>" maxlength="20" <?=$disabled_conceptos?> />
			</td>
			<td>
                <input type="text" name="DocFiscal" id="DocFiscal_<?=$id?>" class="cell2" value="<?=$field_conceptos['DocFiscal']?>" maxlength="20" readonly="readonly" />
			</td>
			<td>
                <input type="hidden" name="CodProveedor" id="CodProveedor_<?=$id?>" value="<?=$field_conceptos['CodProveedor']?>" />
                <input type="text" name="NomProveedor" id="NomProveedor_<?=$id?>" class="cell2 iEditable" value="<?=$field_conceptos['NomProveedor']?>" readonly="readonly" onfocus="caja_chica_habilitar_proveedor('<?=$id?>', '<?=$opcion?>');" />
                
                <input type="hidden" name="CodPartida" id="CodPartida_<?=$id?>" value="<?=$field_conceptos['CodPartida']?>" />
                <input type="hidden" name="CodCuenta" id="CodCuenta_<?=$id?>" value="<?=$field_conceptos['CodCuenta']?>" />
                <input type="hidden" name="CodCuentaPub20" id="CodCuentaPub20_<?=$id?>" value="<?=$field_conceptos['CodCuentaPub20']?>" />
                <input type="hidden" name="Distribucion" id="Distribucion_<?=$id?>" value="<?=$_Distribucion?>" />
			</td>
		</tr>
		<?
	}
    ?>
    </tbody>
</table>
</div>
<input type="hidden" id="nro_conceptos" value="<?=$nro_conceptos?>" />
<input type="hidden" id="can_conceptos" value="<?=$nro_conceptos?>" />
</form>
</center>
</div>

<div id="tab3" style="display:none;">
<center>
<div style="width:<?=$_width?>px;" class="divFormCaption">Distribuci&oacute;n Contable</div>
<div style="overflow:scroll; width:<?=$_width?>px; height:150px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="125">Cuenta</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="100">Monto</th>
    </tr>
    </thead>
    
    <tbody id="lista_cuentas">
    <?
	list($_cod_partida_igv, $_CodCuenta_igv, $_CodCuentaPub20_igv) = getPartidaCuentaFromIGV($_PARAMETRO["IGVCODIGO"]);
	$nrocuentas = 0;
	$sql = "(SELECT
				ccd.CodCuenta,
				pc.Descripcion,
				SUM(ccd.Monto) AS Monto
			FROM
				ap_cajachicadistribucion ccd
				INNER JOIN ac_mastplancuenta pc ON (ccd.CodCuenta = pc.CodCuenta)
			WHERE
				ccd.FlagCajaChica = '".$FlagCajaChica."' AND
				ccd.Periodo = '".$Periodo."' AND
				ccd.NroCajaChica = '".$NroCajaChica."'
			GROUP BY CodCuenta)
			UNION
			(SELECT
				'".$_CodCuenta_igv."' AS CodCuenta,
				'".getVar("ac_mastplancuenta", "Descripcion", "CodCuenta", $_CodCuenta_igv)."' AS Descripcion,
				'".$field['MontoImpuesto']."' AS Monto)
			ORDER BY CodCuenta";
	$query_cuentas = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_cuentas = mysql_fetch_array($query_cuentas)) {
		$nrocuentas++;
		?>
		<tr class="trListaBody">
			<td align="center">
				<?=$field_cuentas['CodCuenta']?>
            </td>
			<td>
				<?=$field_cuentas['Descripcion']?>
            </td>
			<td align="right">
				<?=number_format($field_cuentas['Monto'], 2, ',', '.')?>
            </td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>

<div style="width:<?=$_width?>px;" class="divFormCaption">Distribuci&oacute;n Contable (Pub. 20)</div>
<div style="overflow:scroll; width:<?=$_width?>px; height:150px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="125">Cuenta</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="100">Monto</th>
    </tr>
    </thead>
    
    <tbody id="lista_cuentas20">
    <?
	$nrocuentas = 0;
	$sql = "(SELECT
				ccd.CodCuentaPub20,
				pc.Descripcion,
				SUM(ccd.Monto) AS Monto
			FROM
				ap_cajachicadistribucion ccd
				INNER JOIN ac_mastplancuenta20 pc ON (ccd.CodCuentaPub20 = pc.CodCuenta)
			WHERE
				ccd.FlagCajaChica = '".$FlagCajaChica."' AND
				ccd.Periodo = '".$Periodo."' AND
				ccd.NroCajaChica = '".$NroCajaChica."'
			GROUP BY CodCuentaPub20)
			UNION
			(SELECT
				'".$_CodCuentaPub20_igv."' AS CodCuentaPub20,
				'".getVar("ac_mastplancuenta20", "Descripcion", "CodCuenta", $_CodCuentaPub20_igv)."' AS Descripcion,
				'".$field['MontoImpuesto']."' AS Monto)
			ORDER BY CodCuentaPub20";
	$query_cuentas = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_cuentas = mysql_fetch_array($query_cuentas)) {
		$nrocuentas++;
		?>
		<tr class="trListaBody">
			<td align="center">
				<?=$field_cuentas['CodCuentaPub20']?>
            </td>
			<td>
				<?=$field_cuentas['Descripcion']?>
            </td>
			<td align="right">
				<?=number_format($field_cuentas['Monto'], 2, ',', '.')?>
            </td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>

<div style="width:<?=$_width?>px;" class="divFormCaption">Distribuci&oacute;n Presupuestaria</div>
<div style="overflow:scroll; width:<?=$_width?>px; height:150px;">
<form name="frm_partidas" id="frm_partidas">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="125">Partida</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="100">Monto</th>
    </tr>
    </thead>
    
    <tbody id="lista_partidas">
    <?
	$nropartidas = 0;
	$sql = "(SELECT
				ccd.CodPartida,
				pc.denominacion,
				SUM(ccd.Monto) AS Monto
			FROM
				ap_cajachicadistribucion ccd
				INNER JOIN pv_partida pc ON (ccd.CodPartida = pc.cod_partida)
			WHERE
				ccd.FlagCajaChica = '".$FlagCajaChica."' AND
				ccd.Periodo = '".$Periodo."' AND
				ccd.NroCajaChica = '".$NroCajaChica."'
			GROUP BY CodPartida)
			UNION
			(SELECT
				'".$_cod_partida_igv."' AS CodPartida,
				'".getVar("pv_partida", "denominacion", "cod_partida", $_cod_partida_igv)."' AS denominacion,
				'".$field['MontoImpuesto']."' AS Monto)
			ORDER BY CodPartida";
	$query_partidas = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_partidas = mysql_fetch_array($query_partidas)) {
		$nropartidas++;
		list($MontoAjustado, $MontoCompromiso, $MontoPendiente) = disponibilidadPartida($field['Periodo'], $field['CodOrganismo'], $field_partidas['CodPartida'], $field['CodPresupuesto']);
		$MontoDisponible = $MontoAjustado - $MontoCompromiso;
		if ($field['Estado'] == "PR" && $field['NroCajaChica'] != "") $MontoPendiente -= $field_partidas['Monto'];
		//	valido
		if ($field['Estado'] == "PR" && $MontoDisponible < $field_partidas['Monto']) $style = "style='font-weight:bold; background-color:#F8637D;'";
		elseif($field['Estado'] == "PR" && $MontoDisponible < ($field_partidas['Monto'] + $MontoPendiente)) $style = "style='font-weight:bold; background-color:#FFC;'";
		else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
		?>
		<tr class="trListaBody" <?=$style?>>
			<td align="center">
                <input type="hidden" name="cod_partida" value="<?=$field_partidas['CodPartida']?>" />
                <input type="hidden" name="Monto" value="<?=$field_partidas['Monto']?>" />
                <input type="hidden" name="MontoDisponible" value="<?=$MontoDisponible?>" />
                <input type="hidden" name="MontoPendiente" value="<?=$MontoPendiente?>" />
				<?=$field_partidas['CodPartida']?>
            </td>
			<td>
				<?=htmlentities($field_partidas['denominacion'])?>
            </td>
			<td align="right">
				<?=number_format($field_partidas['Monto'], 2, ',', '.')?>
            </td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</form>
</div>
</center>
</div>

<center>
<input type="button" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" class="bEditable" onclick="caja_chica(document.getElementById('frmentrada'), '<?=$accion?>');" />
<input type="button" value="Cancelar" style="width:75px;" onclick="<?=$clkCancelar?>" />
</center>
<div style="width:<?=$_width?>px" class="divMsj">Campos Obligatorios *</div>

<script type="text/javascript" language="javascript">
$(document).ready(function() {
	<?
	if ($MontoAutorizado > 0) $boo = true; else $boo = false;
	if ($opcion == "nuevo" || $opcion == "modificar") { ?>caja_chica_habilitar(<?=$boo?>);<? }
	?>
	
});
</script>