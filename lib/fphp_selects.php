<?php
include("fphp.php");
extract($_POST);
extract($_GET);

//	----------------
if ($tabla == "dependencia") {
	?><option value="">&nbsp;</option><?php	loadSelectDependiente("mastdependencias", "CodDependencia", "Dependencia", "CodOrganismo", "", $opcion, 0);
}

elseif ($tabla == "dependencia_filtro") { ?><option value="">&nbsp;</option><?php getDependencias("", $opcion, 3);
}

elseif ($tabla == "dependencia_fiscal") { ?><option value="">&nbsp;</option><?php loadDependenciaFiscal("", $opcion, 0);
}

elseif ($tabla == "periodo") {
	?><option value="">&nbsp;</option><?php loadSelectNominaPeriodos($opcion1, $opcion2, 0);
}

elseif ($tabla == "estado") {
	?><option value="">&nbsp;</option><?php	loadSelectDependienteEstado("", $opcion, 0);
}

elseif ($tabla == "municipio") {
	?><option value="">&nbsp;</option><?php loadSelectDependiente("mastmunicipios", "CodMunicipio", "Municipio", "CodEstado", "", $opcion, 0);
}

elseif ($tabla == "ciudad") {
	?><option value="">&nbsp;</option><?php loadSelectDependiente("mastciudades", "CodCiudad", "Ciudad", "CodMunicipio", "", $opcion, 0);
}

elseif ($tabla == "centro_costo") {
	?><option value="">&nbsp;</option><?php	loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", "", $opcion, 0);
}

elseif ($tabla == "fases") {
	?><option value="">&nbsp;</option><?php loadSelectDependiente("pf_fases", "CodFase", "Descripcion", "CodProceso", "", $opcion, 0);
}

elseif ($tabla == "subgrupocc") {
	?><option value="">&nbsp;</option><?php loadSelectDependiente("ac_subgrupocentrocosto", "CodSubGrupoCentroCosto", "Descripcion", "CodGrupoCentroCosto", "", $opcion, 0);
}

elseif ($tabla == "periodo_evaluacion") {
	?><option value="">&nbsp;</option><?php loadSelectDependiente("rh_evaluacionperiodo", "Periodo", "Periodo", "CodOrganismo", "", $opcion, 0);
}

elseif ($tabla == "subgrupocentrocosto") {
	?><option value="">&nbsp;</option><?php loadSelectDependiente("ac_subgrupocentrocosto", "CodSubGrupoCentroCosto", "Descripcion", "CodGrupoCentroCosto", "", $opcion, 0);
}

elseif ($tabla == "tipo_servicio_documento") {
	loadSelectTipoServicioDocumento($opcion, 0);
}

elseif ($tabla == "cuentas_bancarias") {
	?><option value="">&nbsp;</option><?php	loadSelectDependiente("ap_ctabancaria", "NroCuenta", "NroCuenta", "CodBanco", "", $opcion, 0);
}

elseif ($tabla == "centro_costo") {
	?><option value="">&nbsp;</option><?php	loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", "", $opcion, 0);
}

elseif ($tabla == "familia") {
	?><option value="">&nbsp;</option><?php loadSelectDependiente("lg_clasefamilia", "CodFamilia", "Descripcion", "CodLinea", "", $opcion, 0);
}

elseif ($tabla == "profesiones") {
	?><option value="">&nbsp;</option><?php loadSelectDependiente2("rh_profesiones", "CodProfesion", "Descripcion", "CodGradoInstruccion", "Area", "", $opcion1, $opcion2, 0);
}

elseif ($tabla == "profesion") {
	?><option value="">&nbsp;</option><?php loadSelectProfesiones("", $CodGradoInstruccion, $Area, 0);
}

elseif ($tabla == "nivel-instruccion") {
	?><option value="">&nbsp;</option><?php loadSelectDependiente("rh_nivelgradoinstruccion", "Nivel", "Descripcion", "CodGradoInstruccion", "", $opcion, 0);
}

elseif ($tabla == "serie-ocupacional") {
	?><option value="">&nbsp;</option><?php loadSelectDependiente("rh_serieocupacional", "CodSerieOcup", "SerieOcup", "CodGrupOcup", "", $opcion, 0);
}

elseif ($tabla == "cargo") {
	?><option value="">&nbsp;</option><?php loadSelectDependiente("rh_puestos", "CodCargo", "DescripCargo", "CodSerieOcup", "", $opcion, 0);
}

elseif ($tabla == "periodo-bono") {
	?><option value="">&nbsp;</option><?php loadSelectPeriodosBono("", $CodOrganismo, $CodTipoNom, 0);
}

elseif ($tabla == "semana-bono") {
	loadSelectSemanasBono($Semana, $Periodo, 0);
}

elseif ($tabla == "loadSelectPeriodosNomina") {
	?><option value="">&nbsp;</option><?php loadSelectPeriodosNomina("", $CodOrganismo, $CodTipoNom, $opt);
}

elseif ($tabla == "loadSelectPeriodosNominaProcesos") {
	loadSelectPeriodosNominaProcesos("", $Periodo, $CodOrganismo, $CodTipoNom, $opt);
}

elseif ($tabla == "tipo-servicio") {
	?><option value="">&nbsp;</option><?php loadSelectDependiente("masttiposervicio", "CodTipoServicio", "Descripcion", "CodRegimenFiscal", "", $opcion, 0);
}

elseif ($tabla == "loadNominaPeriodos") {
	?><option value="">&nbsp;</option><?php loadNominaPeriodos($opcion, "", 0);
}

elseif ($tabla == "loadControlNominas") {
	?><option value="">&nbsp;</option><?php loadControlNominas($CodOrganismo, "");
}

elseif ($tabla == "loadControlNominasPrenomina") {
	?><option value="">&nbsp;</option><?php loadControlNominasPrenomina($CodOrganismo, "");
}

elseif ($tabla == "loadControlPeriodos") {
	?><option value="">&nbsp;</option><?php loadControlPeriodos($CodOrganismo, $CodTipoNom, "");
}

elseif ($tabla == "loadControlPeriodosPrenomina") {
	?><option value="">&nbsp;</option><?php loadControlPeriodosPrenomina($CodOrganismo, $CodTipoNom, "");
}

elseif ($tabla == "loadControlProcesos") {
	?><option value="">&nbsp;</option><?php loadControlProcesos($CodOrganismo, $CodTipoNom, $Periodo, "");
}

elseif ($tabla == "loadControlProcesos2") {
	?><option value="">&nbsp;</option><?php loadControlProcesos2($CodOrganismo, $CodTipoNom, $Periodo, "");
}

elseif ($tabla == "loadControlProcesosPrenomina") {
	?><option value="">&nbsp;</option><?php loadControlProcesosPrenomina($CodOrganismo, $CodTipoNom, $Periodo, "");
}

elseif ($tabla == "loadControlNominas2") {
	?><option value="">&nbsp;</option><?php loadControlNominas2($CodOrganismo, "");
}

elseif ($tabla == "loadControlPeriodos2") {
	?><option value="">&nbsp;</option><?php loadControlPeriodos2($CodOrganismo, $CodTipoNom, "");
}

elseif ($tabla == "lg_commoditymast") {
	?><option value="">&nbsp;</option><?php loadSelect2("lg_commoditymast", "CommodityMast", "Descripcion", "", 0, array('Clasificacion'), array($fClasificacion));
}
?>