// JavaScript Document

// 	interfase de cuentas por pagar (abrir check)
function interfase_cuentas_por_pagar_abrir_check(TipoObligacion) {
	//	valores
	var CodOrganismo = $("#fCodOrganismo").val();
	var CodTipoNom = $("#fCodTipoNom").val();
	var Periodo = $("#fPeriodo").val();
	var CodTipoProceso = $("#fCodTipoProceso").val();
	var registro = "";
	
	//	valido
	var error = "";
	if (CodOrganismo == "") error = "Debe seleccionar el Organismo";
	else if (CodTipoNom == "") error = "Debe seleccionar la N&oacute;mina";
	else if (Periodo == "") error = "Debe seleccionar el Periodo";
	else if (CodTipoProceso == "") error = "Debe seleccionar el Proceso";
	
	if (error == "") {
		var nro_bancos = 0;
		var nro_cheques = 0;
		var nro_terceros = 0;
		
		//	listado (interfase bancaria)
		if (TipoObligacion == "01") {
			var chk = false;
			var frm_bancos = document.getElementById("frm_bancos");
			for(var i=0; n=frm_bancos.elements[i]; i++) {
				if (n.name == "bancos" && n.checked) { registro = n.value; ++nro_bancos; chk = true; }
				else if (n.name == "FlagVerificado" && chk) {
					if (n.value == "S") { error = "La Obligaci&oacute;n ya se encuentra Verificada "; break; }
					else chk = false;
				}
			}
		}
		//	listado (cheques)
		else if (TipoObligacion == "02") {
			var chk = false;
			var frm_cheques = document.getElementById("frm_cheques");
			for(var i=0; n=frm_cheques.elements[i]; i++) {
				if (n.name == "cheques" && n.checked) { registro = n.value; ++nro_cheques; chk = true; }
				else if (n.name == "FlagVerificado" && chk) {
					if (n.value == "S") { error = "La Obligaci&oacute;n ya se encuentra Verificada "; break; }
					else chk = false;
				}
			}
		}
		//	listado (terceros)
		else if (TipoObligacion == "03") {
			var chk = false;
			var frm_terceros = document.getElementById("frm_terceros");
			for(var i=0; n=frm_terceros.elements[i]; i++) {
				if (n.name == "terceros" && n.checked) { registro = n.value; ++nro_terceros; chk = true; }
				else if (n.name == "FlagVerificado" && chk) {
					if (n.value == "S") { error = "La Obligaci&oacute;n ya se encuentra Verificada "; break; }
					else chk = false;
				}
			}
		}
		
		//	valido que selecciono algun registro
		if (nro_bancos > 1 || nro_cheques > 1 || nro_terceros > 1) error = "Debe seleccionar solo una Obligaci&oacute;n";
		else if (nro_bancos < 1 && nro_cheques < 1 && nro_terceros < 1) error = "Debe seleccionar una Obligaci&oacute;n";
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 450);
	} else {
		var href = "gehen.php?anz=pr_interfase_cuentas_por_pagar_verificar&filtrar=default&CodOrganismo="+CodOrganismo+"&CodTipoNom="+CodTipoNom+"&Periodo="+Periodo+"&CodTipoProceso="+CodTipoProceso+"&registro="+registro+"&TipoObligacion="+TipoObligacion+"&iframe=true&width=800&height=450";
		$("#a_check").attr("href", href);
		document.getElementById('a_check').click();
	}
}

// 	interfase de cuentas por pagar (abrir generar)
function interfase_cuentas_por_pagar_generar_abrir(TipoObligacion) {
	//	valores
	var CodOrganismo = $("#fCodOrganismo").val();
	var CodTipoNom = $("#fCodTipoNom").val();
	var Periodo = $("#fPeriodo").val();
	var CodTipoProceso = $("#fCodTipoProceso").val();
	var registro = "";
	
	//	valido
	var error = "";
	if (CodOrganismo == "") error = "Debe seleccionar el Organismo";
	else if (CodTipoNom == "") error = "Debe seleccionar la N&oacute;mina";
	else if (Periodo == "") error = "Debe seleccionar el Periodo";
	else if (CodTipoProceso == "") error = "Debe seleccionar el Proceso";
	
	if (error == "") {
		var nro_bancos = 0;
		var nro_cheques = 0;
		var nro_terceros = 0;
		var nro_judiciales = 0;
		
		//	listado (interfase bancaria)
		if (TipoObligacion == "01") {
			var chk = false;
			var frm_bancos = document.getElementById("frm_bancos");
			for(var i=0; n=frm_bancos.elements[i]; i++) {
				if (n.name == "bancos" && n.checked) { registro = n.value; ++nro_bancos; chk = true; }
				else if (n.name == "FlagVerificado" && chk) { 
					if (n.value == "N") { error = "Debe verificar la Obligaci&oacute;n seleccionada"; break; }
				}
				else if (n.name == "FlagTransferido" && chk) {
					if (n.value == "S") { error = "La Obligaci&oacute;n ya se encuentra Transferida"; break; }
					else chk = false;
				}
			}
		}
		//	listado (cheques)
		else if (TipoObligacion == "02") {
			var chk = false;
			var frm_cheques = document.getElementById("frm_cheques");
			for(var i=0; n=frm_cheques.elements[i]; i++) {
				if (n.name == "cheques" && n.checked) { registro = n.value; ++nro_cheques; chk = true; }
				else if (n.name == "FlagVerificado" && chk) { 
					if (n.value == "N") { error = "Debe verificar la Obligaci&oacute;n seleccionada"; break; }
				}
				else if (n.name == "FlagTransferido" && chk) {
					if (n.value == "S") { error = "La Obligaci&oacute;n ya se encuentra Transferida"; break; }
					else chk = false;
				}
			}
		}
		//	listado (terceros)
		else if (TipoObligacion == "03") {
			var chk = false;
			var frm_terceros = document.getElementById("frm_terceros");
			for(var i=0; n=frm_terceros.elements[i]; i++) {
				if (n.name == "terceros" && n.checked) { registro = n.value; ++nro_terceros; chk = true; }
				else if (n.name == "FlagVerificado" && chk) { 
					if (n.value == "N") { error = "Debe verificar la Obligaci&oacute;n seleccionada"; break; }
				}
				else if (n.name == "FlagTransferido" && chk) {
					if (n.value == "S") { error = "La Obligaci&oacute;n ya se encuentra Transferida"; break; }
					else chk = false;
				}
			}
		}
		//	listado (judiciales)
		else if (TipoObligacion == "04") {
			var chk = false;
			var frm_judiciales = document.getElementById("frm_judiciales");
			for(var i=0; n=frm_judiciales.elements[i]; i++) {
				if (n.name == "judiciales" && n.checked) { registro = n.value; ++nro_judiciales; chk = true; }
				else if (n.name == "FlagTransferido" && chk) {
					if (n.value == "S") { error = "La Obligaci&oacute;n ya se encuentra Transferida"; break; }
					else chk = false;
				}
			}
		}
		
		//	valido que selecciono algun registro
		if (nro_bancos > 1 || nro_cheques > 1 || nro_terceros > 1 || nro_judiciales > 1) error = "Debe seleccionar solo una Obligaci&oacute;n";
		else if (nro_bancos < 1 && nro_cheques < 1 && nro_terceros < 1 && nro_judiciales < 1) error = "Debe seleccionar una Obligaci&oacute;n";
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 450);
	} else {
		$("#sel_registros").val(registro);
		cargarPagina(document.getElementById('frmentrada'), "gehen.php?anz=pr_interfase_cuentas_por_pagar_obligacion");
	}
}

function setFormula(valor, tipo, descripcion) {
	$(".last").removeClass("last");
	if (tipo == "numero") { 
		var span = "<span class='span-selected last' onclick='selSpan($(this));' style='color:#F60;'>"+valor+"</span>";
	}
	else if (tipo == "char") { 
		var span = "<span class='span-selected last' onclick='selSpan($(this));' style='color:#000;'>"+valor+"</span>";
	}
	else if (tipo == "signo") {
		var span = "<span class='span-selected last' onclick='selSpan($(this));' style='color:#000;'>"+valor+"</span>";
	}
	else if (tipo == "signo2") {
		var span = "<span class='span-selected last' onclick='selSpan($(this));' style='color:#000;'>"+valor+"</span>";
	}
	else if (tipo == "salto") {
		var span = "<span onclick='selSpan($(this));' style='color:#000;'>"+valor+"</span> <br class='span-selected last' />";
	}
	else if (tipo == "var") {
		var span = "<span class='span-selected last' onclick='selSpan($(this));' style='color:#333;'>"+valor+"</span>";
	}
	else if (tipo == "var-total") {
		var span = "<span class='span-selected last' onclick='selSpan($(this));' style='color:#333; font-weight:bold;'>"+valor+"</span>";
	}
	else if (tipo == "espacio") {
		var span = "<span class='span-selected last' onclick='selSpan($(this));'> </span>";
	}
	else if (tipo == "enter") {
		var span = "<br class='span-selected last' />";
	}
	else if (tipo == "variable") {
		var span = "<span class='span-selected last' onclick='selSpan($(this));' style='color:#090;' title='"+descripcion+"'>$_"+valor+"</span>";
	}
	else if (tipo == "parametro") {
		var span = "<span class='span-selected last' onclick='selSpan($(this));' style='color:#330;' title='"+descripcion+"'>$_"+valor+"</span>";
	}
	else if (tipo == "concepto") {
		var span = "<span class='span-selected last' onclick='selSpan($(this));' style='color:#900;' title='"+descripcion+"'>$_"+valor+"</span>";
	}
	else if (tipo == "funcion") {
		var span = "<span class='span-selected last' onclick='selSpan($(this));' style='color:#06F;' title='"+descripcion+"'>"+valor+"()</span>";
	}
	
	if ($("#Formula").text().trim() == "") $("#Formula").append(span);	
	else $(span).insertAfter(".span-selected");
	$(".span-selected").removeClass("span-selected").addClass("span");
	$(".last").addClass("span-selected");
}

//	
function selSpan(span) {
	$(".span-selected").removeClass("span-selected").addClass("span");
	span.addClass("span-selected");
}

//	agregar
function control_agregar(boton) {
	if (boton == ">") {
		if ($("#lista_disponibles .trListaBodySel").length > 0) {
			$("#lista_disponibles .trListaBodySel").clone(true).appendTo("#lista_aprobados");
			$("#lista_disponibles .trListaBodySel").remove();
		} else cajaModal("Debe seleccionar un Empleado", "error", 400);
	}
	else if (boton == ">>") {
		if ($("#lista_disponibles tr").length > 0) {
			$("#lista_disponibles tr").clone(true).appendTo("#lista_aprobados");
			$("#lista_disponibles tr").remove();
		} else cajaModal("Lista vacia", "error", 400);
	}
	$("#rows_disponibles").html($("#lista_disponibles tr").length);
	$("#rows_aprobados").html($("#lista_aprobados tr").length);
}

//	abrir reporte
function procesos_control_payroll(reporte) {
	//	personas
	var detalles_personas = "";
	var frm_personas = document.getElementById("frm_aprobados");
	for(var i=0; n=frm_personas.elements[i]; i++) {
		if (n.name == "personas" && n.checked) detalles_personas += n.value + "|";
	}
	var len = detalles_personas.length; len-=1;
	detalles_personas = detalles_personas.substr(0, len);
	
	//	valido
	if (detalles_personas == "") cajaModal("Debe seleccionar por lo menos un empleado", "error", 400);
	else {
		//	formulario
		var get = getForm(document.getElementById('frmentrada'));
		var NomProceso = $("#fCodTipoProceso option:selected").text();
		get = get + "&NomProceso="+NomProceso;
		var url = "pr_"+reporte+"_pdf.php?empleados=" + detalles_personas + "&" + get + "&iframe=true&width=100%&height=100%";
		$("#a_reporte").attr("href", url);
		document.getElementById("a_reporte").click();
	}
}

//	
function ajuste_salarial_check(id) {
	var boo = $("#CodNivel"+id).prop("checked");
	$("#Porcentaje"+id).val("0,00").prop("readonly", !boo);
	$("#Monto"+id).val("0,00").prop("readonly", !boo);
	$("#SueldoNuevo"+id).val("0,00");
}

//	
function ajuste_salarial_montos(id) {
	var SueldoBasico = setNumero($("#SueldoBasico"+id).val());
	var Porcentaje = setNumero($("#Porcentaje"+id).val());
	var Monto = setNumero($("#Monto"+id).val());
	if (Porcentaje > 0 || Monto > 0) var SueldoNuevo = (SueldoBasico * Porcentaje / 100) + Monto + SueldoBasico;
	else SueldoNuevo = 0;
	$("#SueldoNuevo"+id).val(SueldoNuevo).formatCurrency();
}

//	
function ajuste_salarial_emp_check(id) {
	var boo = $("#CodPersona"+id).prop("checked");
	$("#Porcentaje"+id).val("0,00").prop("readonly", !boo);
	$("#Monto"+id).val("0,00").prop("readonly", !boo);
	$("#SueldoNuevo"+id).val("0,00");
}

//	
function ajuste_salarial_emp_nomina_mostrar(CodTipoNom) {
	$("input[name=CodPersona]").prop("checked", false);	
	$("input[name=Porcentaje]").prop("readonly", true).val("0,00");
	$("input[name=Monto]").prop("readonly", true).val("0,00");
	$("input[name=SueldoNuevo]").val("0,00");
	$(".lista").css("display", "none");
	$(".lista."+CodTipoNom).css("display", "block");
}

//	
function ajuste_salarial_emp_nomina_mostrar_ver(CodTipoNom) {
	$(".lista").css("display", "none");
	$(".lista."+CodTipoNom).css("display", "block");
}