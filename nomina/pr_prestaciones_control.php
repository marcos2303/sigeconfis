<?php
$FechaActual = "$AnioActual-$MesActual-$DiaActual";
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["ORGANISMO_ACTUAL"];
	$fOrderBy = "CodOrganismo";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (le.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodTipoNom != "") { $cCodTipoNom = "checked"; $filtro.=" AND (le.CodTipoNom = '".$fCodTipoNom."')"; } else $dCodTipoNom = "disabled";
if ($fCodDependencia != "") { $cCodDependencia = "checked"; $filtro.=" AND (le.CodDependencia = '".$fCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fFliquidacionD != "" || $fFliquidacionH != "") {
	$cFliquidacion = "checked";
	if ($fFliquidacionD != "") $filtro.=" AND (le.Fliquidacion >= '".formatFechaAMD($fFliquidacionD)."')";
	if ($fFliquidacionH != "") $filtro.=" AND (le.Fliquidacion <= '".formatFechaAMD($fFliquidacionH)."')";
} else $dFliquidacion = "disabled";
if ($fCodPersona != "") { $cCodPersona = "checked"; $filtro.=" AND (le.CodPersona = '".$fCodPersona."')"; } else $dCodPersona = "visibility:hidden;";
if ($fCodMotivoCes != "") { $cCodMotivoCes = "checked"; $filtro.=" AND (le.CodMotivoCes = '".$fCodMotivoCes."')"; } else $dCodMotivoCes = "disabled";
//	------------------------------------
$_titulo = "Control de Prestaciones";
$_width = 900;
$_sufijo = "prestaciones_control";
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$_titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=pr_<?=$_sufijo?>" method="post" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="sel_registros" id="sel_registros" />

<div class="divBorder" style="width:<?=$_width?>px;">
<table width="<?=$_width?>" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:275px;" onChange="getOptionsSelect(this.value, 'dependencia_filtro', 'fCodDependencia', true);" <?=$dCodOrganismo?>>
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="125">N&oacute;mina:</td>
		<td>
			<input type="checkbox" <?=$cCodTipoNom?> onclick="chkFiltro(this.checked, 'fCodTipoNom');" />
			<select name="fCodTipoNom" id="fCodTipoNom" style="width:143px;" <?=$dCodTipoNom?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("tiponomina", "CodTipoNom", "Nomina", $fCodTipoNom, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" <?=$cCodDependencia?> onclick="chkFiltro(this.checked, 'fCodDependencia');" />
			<select name="fCodDependencia" id="fCodDependencia" style="width:275px;" <?=$dCodDependencia?>>
            	<option value="">&nbsp;</option>
				<?=getDependencias($fCodDependencia, $fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right">F. Liquidaci&oacute;n: </td>
		<td>
			<input type="checkbox" <?=$cFliquidacion?> onclick="chkFiltro_2(this.checked, 'fFliquidacionD', 'fFliquidacionH');" />
			<input type="text" name="fFliquidacionD" id="fFliquidacionD" value="<?=$fFliquidacionD?>" <?=$dFliquidacion?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" /> -
            <input type="text" name="fFliquidacionH" id="fFliquidacionH" value="<?=$fFliquidacionH?>" <?=$dFliquidacion?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" />
        </td>
	</tr>
	<tr>
		<td align="right">Empleado: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cCodPersona?> onclick="chkFiltroLista_3(this.checked, 'fCodEmpleado', 'fNomEmpleado', 'fCodPersona', 'btEmpleado');" />
            <input type="hidden" name="fCodPersona" id="fCodPersona" value="<?=$fCodPersona?>" />
            <input type="hidden" name="fCodEmpleado" id="fCodEmpleado" value="<?=$fCodEmpleado?>" />
			<input type="text" name="fNomEmpleado" id="fNomEmpleado" style="width:270px;" class="disabled" value="<?=$fNomEmpleado?>" readonly />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&cod=fCodEmpleado&nom=fNomEmpleado&campo3=fCodPersona&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btEmpleado" style=" <?=$dCodPersona?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td align="right">Motivo Cese: </td>
		<td>
            <input type="checkbox" <?=$cCodMotivoCes?> onclick="chkFiltro(this.checked, 'fCodMotivoCes');" />
            <select name="fCodMotivoCes" id="fCodMotivoCes" style="width:143px;" <?=$dCodMotivoCes?>>
                <option value="">&nbsp;</option>
                <?=loadSelect("rh_motivocese", "CodMotivoCes", "MotivoCese", $fCodMotivoCes, 0)?>
            </select>
        </td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="<?=$_width?>" class="tblBotones">
    <tr>
        <td><div id="rows"></div></td>
        <td align="right">
            <input type="button" id="btImprimir" value="Imprimir" style="width:80px;" onclick="abrirReporteVal('a_reporte', 'pr_prestaciones_pdf', '', '', $('#sel_registros'))" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:<?=$_width?>px; height:350px;">
<table width="1600" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15"></th>
        <th scope="col" width="50" onclick="order('CodEmpleado')">Cod.</th>
        <th scope="col" align="left" onclick="order('NomCompleto')">Nombre Completo</th>
        <th scope="col" width="75" onclick="order('Fliquidacion')">F.Liquidaci&oacute;n</th>
        <th scope="col" width="100" align="right" onclick="order('TotalNeto')">Monto</th>
        <th scope="col" width="300" align="left" onclick="order('NomProcesadoPor')">Procesado Por</th>
        <th scope="col" width="60" onclick="order('PeriodoVoucher')">Periodo</th>
        <th scope="col" width="60" onclick="order('Voucher')">Voucher</th>
        <th scope="col" width="200" align="left" onclick="order('MotivoCese')">Motivo</th>
        <th scope="col" width="100" align="right" onclick="order('TotalIngresos')">Ingresos</th>
        <th scope="col" width="100" align="right" onclick="order('TotalDescuentos')">Descuentos</th>
    </tr>
    </thead>
    
    <tbody id="lista_registros">
    <?php
    //	consulto todos
    $sql = "SELECT
				le.CodPersona,
				le.Secuencia
            FROM
				pr_liquidacionempleado le
				INNER JOIN mastempleado e ON (e.CodPersona = le.CodPersona)
				INNER JOIN mastpersonas p1 ON (p1.CodPersona = le.CodPersona)
				INNER JOIN mastpersonas p2 ON (p2.CodPersona = le.ProcesadoPor)
				LEFT JOIN rh_motivocese cs ON (cs.CodMotivoCes = le.CodMotivoCes)
            WHERE 1 $filtro";
    $query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
    $rows_total = mysql_num_rows($query);
    
    //	consulto lista
    $sql = "SELECT
				le.*,
				e.CodEmpleado,
				p1.NomCompleto,
				p2.NomCompleto AS NomProcesadoPor
            FROM
				pr_liquidacionempleado le
				INNER JOIN mastempleado e ON (e.CodPersona = le.CodPersona)
				INNER JOIN mastpersonas p1 ON (p1.CodPersona = le.CodPersona)
				INNER JOIN mastpersonas p2 ON (p2.CodPersona = le.ProcesadoPor)
				LEFT JOIN rh_motivocese cs ON (cs.CodMotivoCes = le.CodMotivoCes)
            WHERE 1 $filtro
            ORDER BY $fOrderBy
            LIMIT ".intval($limit).", ".intval($maxlimit);
    $query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
    $rows_lista = mysql_num_rows($query);	$i=0;
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodPersona]_$field[Secuencia]";
        ?>
        <tr class="trListaBody" onclick="clk($(this), 'registros', '<?=$id?>');">
            <th><?=++$i?></th>
            <td align="center"><?=$field['CodEmpleado']?></td>
            <td><?=htmlentities($field['NomCompleto'])?></td>
            <td align="center"><?=formatFechaDMA($field['Fliquidacion'])?></td>
            <td align="right"><strong><?=number_format($field['TotalNeto'], 2, ',', '.')?></strong></td>
            <td><?=htmlentities($field['NomProcesadoPor'])?></td>
            <td align="center"><?=$field['PeriodoVoucher']?></td>
            <td align="center"><?=$field['Voucher']?></td>
            <td><?=htmlentities($field['MotivoCese'])?></td>
            <td align="right"><?=number_format($field['TotalIngresos'], 2, ',', '.')?></td>
            <td align="right"><?=number_format($field['TotalDescuentos'], 2, ',', '.')?></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
<table width="<?=$_width?>">
    <tr>
        <td>
            Mostrar: 
            <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td align="right">
            <?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>

<div class="gallery clearfix">
    <a href="pagina.php?iframe=true" rel="prettyPhoto[iframe2]" style="display:none;" id="a_reporte"></a>
</div>