<?php
include("../../lib/fphp.php");
include("fphp.php");
extract($_POST);
extract($_GET);
//	--------------------------

//	--------------------------
if ($accion == "setFechaActividades") {
	if (getDiaSemana($FechaInicio) == 0 || getDiaSemana($FechaInicio) == 6) {
		$FechaInicio = getFechaFinHabiles($FechaInicio, 2);
		echo $FechaInicio;
	}
	
	echo "||";
	$total_duracion = 0;
	$total_prorroga = 0;
	$fase_duracion = 0;
	$fase_prorroga = 0;
	$lineas = preg_split("/[;]+/", $detalles);	$i=0;
	foreach ($lineas as $linea) {	$i++;
		@list($CodFase, $NomFase, $CodActividad, $Descripcion, $FlagAutoArchivo, $FlagNoAfectoPlan, $Duracion) = preg_split("/[|]+/", $linea);
		$FechaTermino = getFechaFinHabiles($FechaInicio, $Duracion);
		if (@$grupo != $CodFase) {
			if ($i>1)  {
				?>
                <tr>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th><strong><span><?=$fase_duracion?></span></strong></th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th><strong><span><?=$fase_prorroga?></span></strong></th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
                <?php
				$fase_duracion = 0;
				$fase_prorroga = 0;
			}
			$grupo = $CodFase;
			?>
            <tr class="trListaBody2">
                <td colspan="13"><?=$CodFase?> <?=$NomFase?></td>
            </tr>
            <?php
		}
		
								/*$sql = "SELECT Estado FROM pf_actuacionfiscaldetalle WHERE CodActuacion = '".$CodActuacion."'  AND CodActividad = '$CodActividad'";
								$query_select = mysql_query($sql) or die($sql.mysql_error());
								$field = mysql_fetch_array($query_select);	*/	
		?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_actividades');" id="<?=$CodActividad?>">
        	<td align="center"><?=printEstadoActuacion2("PR")?></td>
            <td>
	            <input type="hidden" name="CodFase" value="<?=$CodFase?>" />
	            <input type="hidden" name="NomFase" value="<?=$NomFase?>" />
	            <input type="hidden" name="CodActividad" value="<?=$CodActividad?>" />
	            <input type="hidden" name="Descripcion" value="<?=$Descripcion?>" />
                <input type="hidden" name="FlagAutoArchivo" value="<?=$FlagAutoArchivo?>" />
                <input type="hidden" name="FlagNoAfectoPlan" value="<?=$FlagNoAfectoPlan?>" />
				<?=$Descripcion?>
			</td>
            <td align="center"><input type="text" autocomplete="off" name="Duracion" style="width:97%; text-align:center;" value="<?=$Duracion?>" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" onchange="setFechaActividades();" /></td>
            <td align="center">
                <?=$FechaInicio?>
                <input type="hidden" name="FechaInicio" value="<?=$FechaInicio?>" />
            </td>
            <td align="center">
                <?=$FechaTermino?>
                <input type="hidden" name="FechaTermino" value="<?=$FechaTermino?>" />
            </td>
            <td align="center">
                0
                <input type="hidden" name="Prorroga" value="0" />
            </td>
            <td align="center">
                <?=$FechaInicio?>
                <input type="hidden" name="FechaInicioReal" value="<?=$FechaInicio?>" />
            </td>
            <td align="center">
                <?=$FechaTermino?>
                <input type="hidden" name="FechaTerminoReal" value="<?=$FechaTermino?>" />
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center"><?=printFlag2($FlagAutoArchivo)?></td>
            <td align="center"><?=printFlag2($FlagNoAfectoPlan)?></td>
        </tr>
        <?php
		$FechaInicio = getFechaFinHabiles($FechaTermino, 2);
		if ($FlagNoAfectoPlan == "N") {
			$total_duracion += $Duracion;
			$fase_duracion += $Duracion;
			$total_prorroga += 0;
			$fase_prorroga += 0;
			$fecha_termino_afecto = $FechaTermino;
		} else $noafecto += $Duracion;
	}
	?>
    <tr>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th><strong><span><?=$fase_duracion?></span></strong></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th><strong><span><?=$fase_prorroga?></span></strong></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
    </tr>
    <?php
	echo "||$fecha_termino_afecto||".intval($total_duracion)."||".intval($total_prorroga)."||".intval($noafecto);
}

//	--------------------------
elseif ($accion == "actuacion_fiscal_actividades_terminar_documento") {
	?>
    <tr>
    	<td align="center">
        	<strong><?=$nrodetalle?></strong>
        </td>
        <td>
            <input type="file" name="files[]" class="cell" accept="application/pdf"/>
        </td>
        <td>
            <input type="text" name="NroDocumento[]" class="cell" autocomplete="off" autocomplete="off"/>
        </td>
        <td>
			<input type="text" name="Fecha[]" autocomplete="off" class="cell datepicker" id='datepicker<?=$nrodetalle?>' onkeyup="setFechaDMA(this);" value="<?php echo date('d-m-Y')?>" onclick="$('#datepicker<?=$nrodetalle?>').datepicker();$('#datepicker<?=$nrodetalle?>').datepicker('show');"/>
        </td>
    </tr>
	<?php
}

//	--------------------------
elseif ($accion == "setFechaActividadesProrroga") {
	$lineas = preg_split("/[;]/", $detalles);	$i=0;	$j=0;
	foreach ($lineas as $linea) {	$i++;
		list($CodFase, $NomFase, $CodActividad, $Descripcion, $FlagAutoArchivo, $FlagNoAfectoPlan, $Estado, $Duracion, $FechaInicio, $FechaTermino, $ProrrogaAcu, $Prorroga, $FechaInicioR, $FechaTerminoR, $DiasCierre, $fechaTerminoCierre,$FechaRegistroCierre, $DiasAdelanto) = preg_split("/[|]/", $linea);
		if (($Estado == "EJ") || @$cambiar) {
			$cambiar = true;
			$j++;
			if ($j == 1) $FechaInicioReal = $FechaInicioR;
			$tiempo = $ProrrogaAcu + $Prorroga + $Duracion;
			$FechaTerminoReal = getFechaFinHabiles($FechaInicioReal, $tiempo);
		} else {
			$cambiar = false;
			$FechaInicioReal = $FechaInicioR;
			$FechaTerminoReal = $FechaTerminoR;
		}
		
		if (@$grupo != $CodFase) {
			if ($i > 1)  {
				?>
                <tr>
                    <th colspan="2">&nbsp;</th>
                    <th align="center">
                        <span style="font-weight:bold;"><?=$fase_duracion?></span>
                    </th>
                    <th colspan="2">&nbsp;</th>
                    <th align="center">
                        <span style="font-weight:bold;"><?=$fase_prorroga?></span>
                    </th>
                </tr>
                <?php
				$fase_duracion = 0;
				$fase_prorroga = 0;
			}
			$grupo = $CodFase;
			?>
            <tr class="trListaBody2">
                <td colspan="2"><?=$CodFase?> <?=$NomFase?></td>
            </tr>
            <?
		}
		?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_actividades');" id="<?=$CodActividad?>">
        	<td align="center"><?=printEstadoActuacion2($Estado)?></td>
            <td>
	            <input type="hidden" name="CodFase" value="<?=$CodFase?>" />
	            <input type="hidden" name="NomFase" value="<?=$NomFase?>" />
	            <input type="hidden" name="CodActividad" value="<?=$CodActividad?>" />
	            <input type="hidden" name="Descripcion" value="<?=$Descripcion?>" />
                <input type="hidden" name="FlagAutoArchivo" value="<?=$FlagAutoArchivo?>" />
                <input type="hidden" name="FlagNoAfectoPlan" value="<?=$FlagNoAfectoPlan?>" />
                <input type="hidden" name="Estado" value="<?=$Estado?>" />
				<?=$Descripcion?>
			</td>
            <td align="center">
                <?=$Duracion?>
            	<input type="hidden" name="Duracion" value="<?=$Duracion?>" />
            </td>
            <td align="center">
                <?=$FechaInicio?>
                <input type="hidden" name="FechaInicio" value="<?=$FechaInicio?>" />
            </td>
            <td align="center">
                <?=$FechaTermino?>
                <input type="hidden" name="FechaTermino" value="<?=$FechaTermino?>" />
            </td>
            <td align="center">
                <?=intval($ProrrogaAcu)?>
                <input type="hidden" name="ProrrogaAcu" value="<?=($ProrrogaAcu)?>" />
            </td>
            <td align="center">
				<?php
                if ($Estado == "EJ") {
                    ?><input type="text" name="Prorroga" value="<?=$Prorroga?>" class="cell" style="text-align:center;" onchange="setFechaActividadesProrroga(this.value, '<?=$CodActividad?>');" /><?
                } else {
                    ?><input type="hidden" name="Prorroga" value="<?=$Prorroga?>" /><?
                    echo $Prorroga;
                }
                ?>
            </td>
            <td align="center">
                <?=$FechaInicioReal?>
                <input type="hidden" name="FechaInicioReal" value="<?=$FechaInicioReal?>" />
            </td>
            <td align="center">
                <?=$FechaTerminoReal?>
                <input type="hidden" name="FechaTerminoReal" value="<?=$FechaTerminoReal?>" />
            </td>
            <td align="center">
                <?=$DiasCierre?>
                <input type="hidden" name="DiasCierre" value="<?=$DiasCierre?>" />
            </td>
            <td align="center">
                <?=@$FechaTerminoCierre?>
                <input type="hidden" name="FechaTerminoCierre" value="<?=@$FechaTerminoCierre?>" />
            </td>
            <td align="center">
                <?=$FechaRegistroCierre?>
                <input type="hidden" name="FechaRegistroCierre" value="<?=$FechaRegistroCierre?>" />
                <input type="hidden" name="DiasAdelanto" value="<?=$DiasAdelanto?>" />
            </td>
            <td align="center"><?=printFlag2($FlagAutoArchivo)?></td>
            <td align="center"><?=printFlag2($FlagNoAfectoPlan)?></td>
        </tr>
        <?php
		if(!isset($total_duracion)) $total_duracion='';
		if(!isset($fase_duracion)) $fase_duracion='';
		if(!isset($total_prorroga)) $total_prorroga='';
		if(!isset($fase_prorroga)) $fase_prorroga='';
		if (($Estado == "EJ") || $cambiar) {
			$FechaInicioReal = getFechaFinHabiles($FechaTerminoReal, 2);
		}
		if ($FlagNoAfectoPlan == "N") {
			$total_duracion += $Duracion;
			$fase_duracion += $Duracion;
			$total_prorroga += $ProrrogaAcu;
			$fase_prorroga += $ProrrogaAcu;
		}
	}
	?>
    <tr>
        <th colspan="2">&nbsp;</th>
        <th align="center">
            <span style="font-weight:bold;"><?=$fase_duracion?></span>
        </th>
        <th colspan="2">&nbsp;</th>
        <th align="center">
            <span style="font-weight:bold;"><?=$fase_prorroga?></span>
        </th>
    </tr>
    <?php
	echo "||$finicio||$ffin||".intval($total_duracion)."||".intval($total_prorroga);
}

//	--------------------------
elseif ($accion == "actuacion_fiscal_modificar") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_actuacionfiscal
			WHERE
				CodActuacion = '".$codigo."' AND
				Estado <> 'PR'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede modificar este registro");
}
elseif ($accion == "riesgo_nuevo") {
	//	valido
	$sql = "SELECT *
			FROM pf_riesgos_detalle
			WHERE
				CodActuacion = '".$codigo."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("Ya existe un análisis para esta actuación");
}
elseif ($accion == "riesgo_modificar") {
	//	valido
	$sql = "SELECT *
			FROM pf_riesgos_detalle
			WHERE
				CodActuacion = '".$codigo."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) == 0) die("Aún no ha creado el análisis. Debe seleccionar agregar.");
}
elseif ($accion == "riesgo_ver") {
	//	valido
	$sql = "SELECT *
			FROM pf_riesgos_detalle
			WHERE
				CodActuacion = '".$codigo."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) == 0) die("Aún no ha creado el análisis. Debe seleccionar agregar.");
}
//	--------------------------
elseif ($accion == "actuacion_fiscal_cerrar") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_actuacionfiscal
			WHERE
				CodActuacion = '".$codigo."' AND
				(Estado = 'CE' OR Estado = 'CO')";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede cerrar este registro");
}

//	--------------------------
elseif ($accion == "actuacion_fiscal_prorrogas_modificar") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_prorroga
			WHERE
				CodProrroga = '".$codigo."' AND
				Estado <> 'PR'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede modificar este registro");
}

//	--------------------------
elseif ($accion == "actuacion_fiscal_prorrogas_anular") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_prorroga
			WHERE
				CodProrroga = '".$codigo."' AND
				Estado = 'AP'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede anular este registro");
}

//	--------------------------
elseif ($accion == "valoracion_juridica_modificar") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_valoracionjuridica
			WHERE
				CodValJur = '".$codigo."' AND
				Estado <> 'PR'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede modificar este registro");
}

//	--------------------------
elseif ($accion == "valoracion_juridica_cerrar") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_valoracionjuridica
			WHERE
				CodValJur = '".$codigo."' AND
				(Estado = 'CE' OR Estado = 'CO')";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede cerrar este registro");
}

//	--------------------------
elseif ($accion == "valoracion_juridica_prorrogas_modificar") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_valoracionjuridicaprorroga
			WHERE
				CodProrroga = '".$codigo."' AND
				Estado <> 'PR'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede modificar este registro");
}

//	--------------------------
elseif ($accion == "valoracion_juridica_prorrogas_anular") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_valoracionjuridicaprorroga
			WHERE
				CodProrroga = '".$codigo."' AND
				Estado = 'AP'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede anular este registro");
}

//	--------------------------
elseif ($accion == "potestad_investigativa_modificar") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_potestad
			WHERE
				CodPotestad = '".$codigo."' AND
				Estado <> 'PR'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede modificar este registro");
}

//	--------------------------
elseif ($accion == "potestad_investigativa_cerrar") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_potestad
			WHERE
				CodPotestad = '".$codigo."' AND
				(Estado = 'CE' OR Estado = 'CO')";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede cerrar este registro");
}

//	--------------------------
elseif ($accion == "potestad_investigativa_prorrogas_modificar") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_potestadprorroga
			WHERE
				CodProrroga = '".$codigo."' AND
				Estado <> 'PR'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede modificar este registro");
}

//	--------------------------
elseif ($accion == "potestad_investigativa_prorrogas_anular") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_potestadprorroga
			WHERE
				CodProrroga = '".$codigo."' AND
				Estado = 'AP'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede anular este registro");
}

//	--------------------------
elseif ($accion == "determinacion_responsabilidad_modificar") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_determinacion
			WHERE
				CodDeterminacion = '".$codigo."' AND
				Estado <> 'PR'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede modificar este registro");
}

//	--------------------------
elseif ($accion == "determinacion_responsabilidad_cerrar") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_determinacion
			WHERE
				CodDeterminacion = '".$codigo."' AND
				(Estado = 'CE' OR Estado = 'CO')";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede cerrar este registro");
}

//	--------------------------
elseif ($accion == "determinacion_responsabilidad_prorrogas_modificar") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_determinacionprorroga
			WHERE
				CodProrroga = '".$codigo."' AND
				Estado <> 'PR'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede modificar este registro");
}

//	--------------------------
elseif ($accion == "determinacion_responsabilidad_prorrogas_anular") {
	//	valido
	$sql = "SELECT Estado
			FROM pf_determinacionprorroga
			WHERE
				CodProrroga = '".$codigo."' AND
				Estado = 'AP'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) die("No puede anular este registro");
}
?>