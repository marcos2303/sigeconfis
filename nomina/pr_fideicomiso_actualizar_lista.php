<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$AnioActual-$MesActual-$DiaActual";
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["ORGANISMO_ACTUAL"];
	$fCodTipoNom = $_SESSION["NOMINA_ACTUAL"];
	$fPeriodo = "$AnioActual-$MesActual";
	$fOrderBy = "LENGTH(Ndocumento),Ndocumento";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
$filtro = "";
if ($fEstado == "") $filtro.=" AND (e.Estado = 'A')"; else $cEstado = "checked";
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (tne.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodTipoNom != "") { $cCodTipoNom = "checked"; $filtro.=" AND (tne.CodTipoNom = '".$fCodTipoNom."')"; } else $dCodTipoNom = "disabled";
if ($fPeriodo != "") { $cPeriodo = "checked"; $filtro.=" AND (tne.Periodo = '".$fPeriodo."')"; } else $dPeriodo = "disabled";
//	------------------------------------
$_titulo = "Actualizar Dep&oacute;sito de Antiguedad";
$_width = 950;
$_sufijo = "fideicomiso_actualizar";
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$_titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=pr_<?=$_sufijo?>_lista" method="post" autocomplete="off">
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
			<input type="checkbox" checked onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:275px;" onChange="loadSelect($('#fCodTipoNom'), 'tabla=loadControlNominas2&CodOrganismo='+this.value, 1, destinos=['fCodTipoNom', 'fPeriodo']);">
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="125">N&oacute;mina:</td>
		<td>
			<input type="checkbox" checked onclick="this.checked=!this.checked" />
			<select name="fCodTipoNom" id="fCodTipoNom" style="width:250px;" onChange="loadSelect($('#fPeriodo'), 'tabla=loadControlPeriodos2&CodOrganismo='+$('#fCodOrganismo').val()+'&CodTipoNom='+this.value, 1, destinos=['fPeriodo']);">
				<?=loadControlNominas2($fCodOrganismo, $fCodTipoNom)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Periodo:</td>
		<td>
			<input type="checkbox" checked onclick="this.checked=!this.checked" />
            <select name="fPeriodo" id="fPeriodo" style="width:75px;">
				<?=loadControlPeriodos2($fCodOrganismo, $fCodTipoNom, $fPeriodo)?>
            </select>
		</td>
		<td align="right">&nbsp;</td>
		<td>
            <input type="checkbox" name="fEstado" id="fEstado" value="A" <?=$cEstado?> /> Mostrar cesados
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center>
</form>
<br />

<center>
<table width="<?=$_width?>" class="tblBotones">
    <tr>
        <td style="padding:5px;">
        	<a class="link" href="#" onclick="selTodos('personas');">Todos</a> |
            <a class="link" href="#" onclick="selNinguno('personas');">Ninguno</a>
        </td>
        <td align="right">
            <input type="button" id="btActualizar" value="Actualizar Acumulados" style="width:120px;" onClick="fideicomiso_actualizar()" /> |
            
            <input type="button" id="btTXT" value="Generar TXT" style="width:80px;" disabled="disabled" />
            <input type="button" id="btImprimir" value="Imprimir" style="width:80px;" onclick="abrirReporte(document.getElementById('frmentrada'), 'a_reporte', 'pr_fideicomiso_actualizar_pdf')" />
        </td>
    </tr>
</table>

<form name="frm_personas" id="frm_personas">
<div style="overflow:scroll; width:<?=$_width?>px; height:350px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15"></th>
		<th scope="col" width="20">Tr.</th>
		<th scope="col" width="50">C&oacute;digo</th>
		<th scope="col" align="left">Nombre</th>
		<th scope="col" width="70">Nro. Documento</th>
		<th scope="col" width="125">Cuenta</th>
		<th scope="col" width="60">Monto</th>
		<th scope="col" width="35">Dias</th>
		<th scope="col" width="60">Compl. Adic.</th>
		<th scope="col" width="35">Dias Adic.</th>
    </tr>
    </thead>
    
    <tbody id="lista_personas">
    <?php
	if ($fCodOrganismo != "" && $fCodTipoNom != "" && $fPeriodo != "") {
		//	consulto lista
		$sql = "SELECT
					p.CodPersona,
					p.NomCompleto,
					p.Ndocumento,
					e.CodEmpleado,
					e.Fingreso,
					e.Estado,
					e.Fegreso,
					SUBSTRING(e.Fegreso, 1, 7) AS PeriodoEgreso,
					pp.FechaDesde,
					pp.FechaHasta,
					bp.Ncuenta,
					tne.TotalIngresos,
					tnec.Cantidad,
					tnec.Monto,
					(SELECT COUNT(*)
					 FROM pr_acumuladofideicomisodetalle afd
					 WHERE
						afd.CodOrganismo = '".$fCodOrganismo."' AND
						afd.Periodo = '".$fPeriodo."' AND
						afd.CodPersona = tnec.CodPersona) AS FlagTransferido
				FROM
					pr_tiponominaempleado tne
					INNER JOIN mastpersonas p ON (p.CodPersona = tne.CodPersona)
					INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
					INNER JOIN pr_procesoperiodo pp ON (tne.CodTipoNom = pp.CodTipoNom AND
														tne.Periodo = pp.Periodo AND
														tne.CodOrganismo = pp.CodOrganismo AND
														tne.CodTipoProceso = pp.CodTipoProceso)
					LEFT JOIN pr_tiponominaempleadoconcepto tnec ON (tnec.CodTipoNom = tne.CodTipoNom AND
																	 tnec.Periodo = tne.Periodo AND
																	 tnec.CodPersona = tne.CodPersona AND
																	 tnec.CodOrganismo = tne.CodOrganismo AND
																	 tnec.CodTipoProceso = tne.CodTipoProceso AND
																	 tnec.CodConcepto = '".$_PARAMETRO['PROVISION']."')
					LEFT JOIN bancopersona bp ON (bp.CodPersona = p.CodPersona AND bp.Aportes = 'FI')
				WHERE
					((tne.CodTipoProceso = 'FIN') OR
					 (tne.CodTipoProceso = 'ADE' AND e.Estado = 'I' AND tnec.Cantidad > 0))
					$filtro
				ORDER BY $fOrderBy";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field = mysql_fetch_array($query)) {
			$id = "$field[CodPersona]";
			if ($field['TotalIngresos'] > 0) $DiasAdicional = getDiasAdicionalesTrimestral($field['Fingreso'], $field['FechaDesde'], $field['FechaHasta'], $field['Fegreso']); else $DiasAdicional = 0;
			if ($DiasAdicional) {
				$Complemento = calculo_antiguedad_complemento_trimestral($field['CodPersona'], $field['Fingreso'], $field['FechaDesde'], $field['FechaHasta']);
				$style = "font-weight:bold;";
			} else {
				$Complemento = 0;
				$style = "";
			}
			if ($field['PeriodoEgreso'] == $fPeriodo && $field['Fegreso'] < $field['FechaHasta']) {
				$FlagFraccionado = "S";
				$color = "#900";
			} else {
				$FlagFraccionado = "N";
				$color = "#000";
			}
			?>
			<tr class="trListaBody" onclick="clkMulti($(this), '<?=$id?>');" style="color:<?=$color?>;">
				<th>
                	<input type="checkbox" name="personas" id="<?=$id?>" value="<?=$id?>" style="display:none" />
                    <input type="hidden" name="Transaccion" value="<?=$field['Monto']?>" />
                    <input type="hidden" name="Dias" value="<?=$field['Cantidad']?>" />
                    <input type="hidden" name="Complemento" value="<?=$Complemento?>" />
                    <input type="hidden" name="DiasAdicional" value="<?=$DiasAdicional?>" />
                    <input type="hidden" name="FlagFraccionado" value="<?=$FlagFraccionado?>" />
					<?=++$i?>
                </th>
				<td align="center"><?=printFlag($field['FlagTransferido'])?></td>
				<td align="center"><?=$field['CodEmpleado']?></td>
				<td><?=htmlentities($field['NomCompleto'])?></td>
				<td align="right"><?=number_format($field['Ndocumento'], 0, '', '.')?></td>
				<td align="center"><?=$field['Ncuenta']?></td>
				<td align="right"><?=number_format($field['Monto'], 2, ',', '.')?></td>
				<td align="right"><?=number_format($field['Cantidad'], 2, ',', '.')?></td>
				<td align="right" style=" <?=$style?>"><?=number_format($Complemento, 2, ',', '.')?></td>
				<td align="right" style=" <?=$style?>"><?=number_format($DiasAdicional, 2, ',', '.')?></td>
			</tr>
			<?
		}
	}
    ?>
    </tbody>
</table>
</div>
</form>
</center>

<div class="gallery clearfix">
    <a href="pagina.php?iframe=true" rel="prettyPhoto[iframe1]" style="display:none;" id="a_reporte"></a>
</div>