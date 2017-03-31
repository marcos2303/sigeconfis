<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["ORGANISMO_ACTUAL"];
	$fCodTipoNom = $_SESSION["NOMINA_ACTUAL"];
	//	proceso por defecto
	$sql = "SELECT Periodo, CodTipoProceso
			FROM pr_procesoperiodo
			WHERE
				CodTipoNom = '".$fCodTipoNom."' AND
				CodOrganismo = '".$fCodOrganismo."' AND
				Estado = 'A' AND
				FlagAprobado = 'S'
			GROUP BY Periodo
			ORDER BY Periodo DESC
			LIMIT 0, 1";
	$query_def = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_def) != 0) $field_def = mysql_fetch_array($query_def);
	$fPeriodo = $field_def['Periodo'];
	$fCodTipoProceso = $field_def['CodTipoProceso'];
	$id_tab = 0;
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (o.CodOrganismo = '".$fCodOrganismo."')"; }
if ($fCodTipoNom != "") { $cCodTipoNom = "checked"; $filtro.=" AND (o.CodTipoNom = '".$fCodTipoNom."')"; }
if ($fCodTipoProceso != "") { $cCodTipoProceso = "checked"; $filtro.=" AND (o.CodTipoProceso = '".$fCodTipoProceso."')"; }
if ($fPeriodo != "") { $cPeriodo = "checked"; $filtro.=" AND (o.PeriodoNomina = '".$fPeriodo."')"; }
//	------------------------------------
$i=0;
$display_tab[0] = "display:none;";
$display_tab[1] = "display:none;";
$display_tab[2] = "display:none;";
$display_tab[3] = "display:none;";
foreach($display_tab as $_tab) {
	if ($id_tab == $i) { $display_tab[$i] = "display:block;"; $current_tab[$i] = "current"; }
	else { $display_tab[$i] = "display:none;"; $current_tab[$i] = ""; }
	++$i;
}
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Interfase Cuentas x Pagar</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=pr_interfase_cuentas_por_pagar" method="post" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="id_tab" id="id_tab" value="<?=$id_tab?>" />
<input type="hidden" name="sel_registros" id="sel_registros" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="100">Organismo:</td>
		<td>
			<input type="checkbox" checked="checked" onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:270px;" onChange="loadSelect($('#fCodTipoNom'), 'tabla=loadControlNominas2&CodOrganismo='+this.value, 1, destinos=['fCodTipoNom', 'fPeriodo']);">
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="100">N&oacute;mina:</td>
		<td>
			<input type="checkbox" checked="checked" onclick="this.checked=!this.checked" />
			<select name="fCodTipoNom" id="fCodTipoNom" style="width:270px;" onChange="loadSelect($('#fPeriodo'), 'tabla=loadControlPeriodos2&CodOrganismo='+$('#fCodOrganismo').val()+'&CodTipoNom='+this.value, 1, destinos=['fPeriodo']);">
				<?=loadControlNominas2($fCodOrganismo, $fCodTipoNom)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Periodo:</td>
		<td>
			<input type="checkbox" checked="checked" onclick="this.checked=!this.checked" />
			<select name="fPeriodo" id="fPeriodo" style="width:75px;" onChange="loadSelect($('#fCodTipoProceso'), 'tabla=loadControlProcesos&CodOrganismo='+$('#fCodOrganismo').val()+'&CodTipoNom='+$('#fCodTipoNom').val()+'&Periodo='+this.value, 1, destinos=['fCodTipoProceso']);">
            	<option value="">&nbsp;</option>
            	<?=loadControlPeriodos2($fCodOrganismo, $fCodTipoNom, $fPeriodo)?>
			</select>
		</td>
		<td align="right">Proceso:</td>
		<td>
			<input type="checkbox" checked="checked" onclick="this.checked=!this.checked" />
			<select name="fCodTipoProceso" id="fCodTipoProceso" style="width:270px;" <?=$dCodTipoProceso?>>
            	<option value="">&nbsp;</option>
            	<?=loadControlProcesos($fCodOrganismo, $fCodTipoNom, $fPeriodo, $fCodTipoProceso)?>
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center>
</form>
<br />

<center>
<table width="1000" align="center" cellpadding="0" cellspacing="0">
	<tr>
    	<td>
            <div class="header" style="width:1000px;">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" class="<?=$current_tab[0]?>" onclick="currentTab('tab', this); $('#id_tab').val('0');">
            	<a href="#" onclick="mostrarTab('tab', 1, 4);">Interfase Bancaria</a>
            </li>
            <li id="li2" class="<?=$current_tab[1]?>" onclick="currentTab('tab', this); $('#id_tab').val('1');">
            	<a href="#" onclick="mostrarTab('tab', 2, 4);">Cheques</a>
            </li>
            <li id="li3" class="<?=$current_tab[2]?>" onclick="currentTab('tab', this); $('#id_tab').val('2');">
            	<a href="#" onclick="mostrarTab('tab', 3, 4);">Pago a Terceros</a>
            </li>
            <li id="li4" class="<?=$current_tab[3]?>" onclick="currentTab('tab', this); $('#id_tab').val('3');">
            	<a href="#" onclick="mostrarTab('tab', 4, 4);">Retenciones Judiciales</a>
            </li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<div id="tab1" style=" <?=$display_tab[0]?>;">
<table width="1000" class="tblBotones">
    <tr>
        <td>
            <input type="button" value="Calcular Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_calcular('01');" />
            
        	<input type="button" value="Consolidar Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_consolidar('01');" />
            
        	<input type="button" value="Verificar Presupuesto" style="width:130px;" onclick="interfase_cuentas_por_pagar_abrir_check('01');" />
        </td>
        <td align="right">
        	<input type="button" value="Generar Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_generar_abrir('01')" />
        </td>
    </tr>
</table>
<form name="frm_bancos" id="frm_bancos">
<div style="overflow:scroll; width:1000px; height:350px;">
<table width="1150" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="50">Proveedor</th>
        <th scope="col" align="left">Nombre del Proveedor</th>
        <th scope="col" width="25">Ver.</th>
        <th scope="col" width="25">Trf.</th>
        <th scope="col" width="90">Total Obligaci&oacute;n</th>
        <th scope="col" width="90">Monto</th>
        <th scope="col" width="90">Retenciones</th>
        <th scope="col" width="15">Doc.</th>
        <th scope="col" width="135">Nro. Documento</th>
        <th scope="col" width="75">Fecha Registro</th>
        <th scope="col" width="50">Nro. Registro</th>
    </tr>
    </thead>
    
    <tbody id="lista_bancos">
    <?php
    //	consulto lista
    $sql = "SELECT
				o.CodProveedor,
				o.NroDocumento,
				o.NroRegistro,
				o.FechaRegistro,
				o.FlagTransferido,
				o.MontoObligacion,
				o.MontoAfecto,
				o.MontoNoAfecto,
				o.MontoImpuestoOtros,
				o.TipoObligacion,
				o.FlagVerificado,
				o.TipoObligacion,
				mp.NomCompleto AS NomProveedor,
				mp.Ndocumento,
				td.CodTipoDocumento,
				td.Descripcion AS NomTipoDocumento
			FROM
				pr_obligaciones o
				INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
				INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			WHERE o.TipoObligacion = '01' $filtro
			ORDER BY LENGTH(Ndocumento), Ndocumento";
    $query_bancos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
    while ($field_bancos = mysql_fetch_array($query_bancos)) {
        $id = "$field_bancos[CodProveedor]_$field_bancos[CodTipoDocumento]_$field_bancos[NroDocumento]_$field_bancos[TipoObligacion]";
        ?>
        <tr class="trListaBody" onclick="clkMulti($(this), 'bancos_<?=$id?>');">
            <td align="center">
            	<input type="checkbox" name="bancos" id="bancos_<?=$id?>" value="<?=$id?>" style="display:none;" />
                <input type="hidden" name="FlagVerificado" value="<?=$field_bancos['FlagVerificado']?>" />
                <input type="hidden" name="FlagTransferido" value="<?=$field_bancos['FlagTransferido']?>" />
				<?=$field_bancos['CodProveedor']?>
            </td>
            <td><?=htmlentities($field_bancos['NomProveedor'])?></td>
            <td align="center"><?=printFlag($field_bancos['FlagVerificado'])?></td>
            <td align="center"><?=printFlag($field_bancos['FlagTransferido'])?></td>
            <td align="right"><strong><?=number_format($field_bancos['MontoObligacion'], 2, ',', '.')?></strong></td>
            <td align="right"><?=number_format($field_bancos['MontoNoAfecto'], 2, ',', '.')?></td>
            <td align="right"><?=number_format($field_bancos['MontoImpuestoOtros'], 2, ',', '.')?></td>
            <td align="center"><?=$field_bancos['CodTipoDocumento']?></td>
            <td><?=$field_bancos['NroDocumento']?></td>
            <td align="center"><?=formatFechaDMA($field_bancos['FechaRegistro'])?></td>
            <td align="center"><?=$field_bancos['NroRegistro']?></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</form>
<table align="center" width="1000">
	<tr>
    	<td>
        	<a class="link" href="#" onclick="selTodos('bancos');">Todos</a> |
            <a class="link" href="#" onclick="selNinguno('bancos');">Ninguno</a>
        </td>
    </tr>
</table>
</div>

<div id="tab2" style=" <?=$display_tab[1]?>;">
<table width="1000" class="tblBotones">
    <tr>
        <td>
            <input type="button" value="Calcular Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_calcular('02');" />
            
        	<input type="button" value="Consolidar Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_consolidar('02');" />
            
        	<input type="button" value="Verificar Presupuesto" style="width:130px;" onclick="interfase_cuentas_por_pagar_abrir_check('02');" />
        </td>
        <td align="right">
        	<input type="button" value="Generar Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_generar_abrir('02')" />
        </td>
    </tr>
</table>
<form name="frm_cheques" id="frm_cheques">
<div style="overflow:scroll; width:1000px; height:350px;">
<table width="1150" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="50">Proveedor</th>
        <th scope="col" align="left">Nombre del Proveedor</th>
        <th scope="col" width="25">Ver.</th>
        <th scope="col" width="25">Trf.</th>
        <th scope="col" width="90">Total Obligaci&oacute;n</th>
        <th scope="col" width="90">Monto</th>
        <th scope="col" width="90">Retenciones</th>
        <th scope="col" width="15">Doc.</th>
        <th scope="col" width="135">Nro. Documento</th>
        <th scope="col" width="75">Fecha Registro</th>
        <th scope="col" width="50">Nro. Registro</th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto lista
    $sql = "SELECT
				o.CodProveedor,
				o.NroDocumento,
				o.NroRegistro,
				o.FechaRegistro,
				o.FlagTransferido,
				o.MontoObligacion,
				o.MontoAfecto,
				o.MontoNoAfecto,
				o.MontoImpuestoOtros,
				o.FlagVerificado,
				o.TipoObligacion,
				mp.NomCompleto AS NomProveedor,
				mp.Ndocumento,
				td.Descripcion AS NomTipoDocumento,
				td.CodTipoDocumento
			FROM
				pr_obligaciones o
				INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
				INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			WHERE o.TipoObligacion = '02' $filtro
			ORDER BY LENGTH(Ndocumento), Ndocumento";
    $query_cheques = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
    while ($field_cheques = mysql_fetch_array($query_cheques)) {
        $id = "$field_cheques[CodProveedor]_$field_cheques[CodTipoDocumento]_$field_cheques[NroDocumento]_$field_cheques[TipoObligacion]";
        ?>
        <tr class="trListaBody" onclick="clkMulti($(this), 'cheques_<?=$id?>');">
            <td align="center">
            	<input type="checkbox" name="cheques" id="cheques_<?=$id?>" value="<?=$id?>" style="display:none;" />
                <input type="hidden" name="FlagVerificado" value="<?=$field_cheques['FlagVerificado']?>" />
                <input type="hidden" name="FlagTransferido" value="<?=$field_cheques['FlagTransferido']?>" />
				<?=$field_cheques['CodProveedor']?>
            </td>
            <td><?=htmlentities($field_cheques['NomProveedor'])?></td>
            <td align="center"><?=printFlag($field_cheques['FlagVerificado'])?></td>
            <td align="center"><?=printFlag($field_cheques['FlagTransferido'])?></td>
            <td align="right"><strong><?=number_format($field_cheques['MontoObligacion'], 2, ',', '.')?></strong></td>
            <td align="right"><?=number_format($field_cheques['MontoNoAfecto'], 2, ',', '.')?></td>
            <td align="right"><?=number_format($field_cheques['MontoImpuestoOtros'], 2, ',', '.')?></td>
            <td align="center"><?=$field_cheques['CodTipoDocumento']?></td>
            <td><?=$field_cheques['NroDocumento']?></td>
            <td align="center"><?=formatFechaDMA($field_cheques['FechaRegistro'])?></td>
            <td align="center"><?=$field_cheques['NroRegistro']?></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</form>
<table align="center" width="1000">
	<tr>
    	<td>
        	<a class="link" href="#" onclick="selTodos('cheques');">Todos</a> |
            <a class="link" href="#" onclick="selNinguno('cheques');">Ninguno</a>
        </td>
    </tr>
</table>
</div>

<div id="tab3" style=" <?=$display_tab[2]?>;">
<table width="1000" class="tblBotones">
    <tr>
        <td>
            <input type="button" value="Calcular Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_calcular('03');" />
            
        	<input type="button" value="Verificar Presupuesto" style="width:130px;" onclick="interfase_cuentas_por_pagar_abrir_check('03');" />
        </td>
        <td align="right">
        	<input type="button" value="Generar Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_generar_abrir('03')" />
        </td>
    </tr>
</table>
<form name="frm_terceros" id="frm_terceros">
<div style="overflow:scroll; width:1000px; height:350px;">
<table width="1150" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="50">Proveedor</th>
        <th scope="col" align="left">Nombre del Proveedor</th>
        <th scope="col" width="25">Ver.</th>
        <th scope="col" width="25">Trf.</th>
        <th scope="col" width="90">Total Obligaci&oacute;n</th>
        <th scope="col" width="90">Monto</th>
        <th scope="col" width="90">Retenciones</th>
        <th scope="col" width="15">Doc.</th>
        <th scope="col" width="135">Nro. Documento</th>
        <th scope="col" width="75">Fecha Registro</th>
        <th scope="col" width="50">Nro. Registro</th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto lista
    $sql = "SELECT
				o.CodProveedor,
				o.NroDocumento,
				o.NroRegistro,
				o.FechaRegistro,
				o.FlagTransferido,
				o.MontoObligacion,
				o.MontoAfecto,
				o.MontoNoAfecto,
				o.MontoImpuestoOtros,
				o.FlagVerificado,
				o.TipoObligacion,
				mp.NomCompleto AS NomProveedor,
				mp.Ndocumento,
				td.Descripcion AS NomTipoDocumento,
				td.CodTipoDocumento
			FROM
				pr_obligaciones o
				INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
				INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			WHERE o.TipoObligacion = '03' $filtro
			ORDER BY LENGTH(Ndocumento), Ndocumento";
    $query_terceros = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
    while ($field_terceros = mysql_fetch_array($query_terceros)) {
        $id = "$field_terceros[CodProveedor]_$field_terceros[CodTipoDocumento]_$field_terceros[NroDocumento]_$field_terceros[TipoObligacion]";
        ?>
        <tr class="trListaBody" onclick="clkMulti($(this), 'terceros_<?=$id?>');">
            <td align="center">
            	<input type="checkbox" name="terceros" id="terceros_<?=$id?>" value="<?=$id?>" style="display:none;" />
                <input type="hidden" name="FlagVerificado" value="<?=$field_terceros['FlagVerificado']?>" />
                <input type="hidden" name="FlagTransferido" value="<?=$field_terceros['FlagTransferido']?>" />
				<?=$field_terceros['CodProveedor']?>
            </td>
            <td><?=htmlentities($field_terceros['NomProveedor'])?></td>
            <td align="center"><?=printFlag($field_terceros['FlagVerificado'])?></td>
            <td align="center"><?=printFlag($field_terceros['FlagTransferido'])?></td>
            <td align="right"><strong><?=number_format($field_terceros['MontoObligacion'], 2, ',', '.')?></strong></td>
            <td align="right"><?=number_format($field_terceros['MontoNoAfecto'], 2, ',', '.')?></td>
            <td align="right"><?=number_format($field_terceros['MontoImpuestoOtros'], 2, ',', '.')?></td>
            <td align="center"><?=$field_terceros['CodTipoDocumento']?></td>
            <td><?=$field_terceros['NroDocumento']?></td>
            <td align="center"><?=formatFechaDMA($field_terceros['FechaRegistro'])?></td>
            <td align="center"><?=$field_terceros['NroRegistro']?></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</form>
<table align="center" width="1000">
	<tr>
    	<td>
        	<a class="link" href="#" onclick="selTodos('terceros');">Todos</a> |
            <a class="link" href="#" onclick="selNinguno('terceros');">Ninguno</a>
        </td>
    </tr>
</table>
</div>

<div id="tab4" style=" <?=$display_tab[3]?>;">
<table width="1000" class="tblBotones">
    <tr>
        <td>
            <input type="button" value="Calcular Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_calcular('04');" />
        </td>
        <td align="right">
        	<input type="button" value="Generar Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_generar_abrir('04')" />
        </td>
    </tr>
</table>
<form name="frm_judiciales" id="frm_judiciales">
<div style="overflow:scroll; width:1000px; height:350px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="50">Proveedor</th>
        <th scope="col" align="left">Nombre del Proveedor</th>
        <th scope="col" width="25">Trf.</th>
        <th scope="col" width="90">Total Obligaci&oacute;n</th>
        <th scope="col" width="15">Doc.</th>
        <th scope="col" width="135">Nro. Documento</th>
        <th scope="col" width="75">Fecha Registro</th>
        <th scope="col" width="50">Nro. Registro</th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto lista
    $sql = "SELECT
				o.CodProveedor,
				o.NroDocumento,
				o.NroRegistro,
				o.FechaRegistro,
				o.FlagTransferido,
				o.MontoObligacion,
				o.MontoAfecto,
				o.MontoNoAfecto,
				o.MontoImpuestoOtros,
				o.FlagVerificado,
				o.TipoObligacion,
				mp.NomCompleto AS NomProveedor,
				mp.Ndocumento,
				td.Descripcion AS NomTipoDocumento,
				td.CodTipoDocumento
			FROM
				pr_obligaciones o
				INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
				INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			WHERE o.TipoObligacion = '04' $filtro
			ORDER BY LENGTH(Ndocumento), Ndocumento";
    $query_judiciales = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
    while ($field_judiciales = mysql_fetch_array($query_judiciales)) {
        $id = "$field_judiciales[CodProveedor]_$field_judiciales[CodTipoDocumento]_$field_judiciales[NroDocumento]_$field_judiciales[TipoObligacion]";
        ?>
        <tr class="trListaBody" onclick="clkMulti($(this), 'judiciales_<?=$id?>');">
            <td align="center">
            	<input type="checkbox" name="judiciales" id="judiciales_<?=$id?>" value="<?=$id?>" style="display:none;" />
                <input type="hidden" name="FlagVerificado" value="<?=$field_judiciales['FlagVerificado']?>" />
				<?=$field_judiciales['CodProveedor']?>
            </td>
            <td><?=htmlentities($field_judiciales['NomProveedor'])?></td>
            <td align="center"><?=printFlag($field_judiciales['FlagTransferido'])?></td>
            <td align="right"><strong><?=number_format($field_judiciales['MontoObligacion'], 2, ',', '.')?></strong></td>
            <td align="center"><?=$field_judiciales['CodTipoDocumento']?></td>
            <td><?=$field_judiciales['NroDocumento']?></td>
            <td align="center"><?=formatFechaDMA($field_judiciales['FechaRegistro'])?></td>
            <td align="center"><?=$field_judiciales['NroRegistro']?></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</form>
<table align="center" width="1000">
	<tr>
    	<td>
        	<a class="link" href="#" onclick="selTodos('judiciales');">Todos</a> |
            <a class="link" href="#" onclick="selNinguno('judiciales');">Ninguno</a>
        </td>
    </tr>
</table>
</div>

</center>

<span class="gallery clearfix">
<a id="a_check" href="pagina.php?iframe=true&width=500&height=500" rel="prettyPhoto[iframe1]" style="display:none;"></a>
</span>