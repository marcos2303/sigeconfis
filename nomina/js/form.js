// JavaScript Document

// 	interfase de cuentas por pagar (calcular)
function interfase_cuentas_por_pagar_calcular(TipoObligacion) {
	bloqueo(true);
	
	//	valores
	var CodOrganismo = $("#fCodOrganismo").val();
	var CodTipoNom = $("#fCodTipoNom").val();
	var Periodo = $("#fPeriodo").val();
	var CodTipoProceso = $("#fCodTipoProceso").val();
	
	//	valido
	var error = "";
	if (CodOrganismo == "") error = "Debe seleccionar el Organismo";
	else if (CodTipoNom == "") error = "Debe seleccionar la N&oacute;mina";
	else if (Periodo == "") error = "Debe seleccionar el Periodo";
	else if (CodTipoProceso == "") error = "Debe seleccionar el Proceso";
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 450);
	} else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=interfase_cuentas_por_pagar&accion=calcular&CodOrganismo="+CodOrganismo+"&CodTipoNom="+CodTipoNom+"&Periodo="+Periodo+"&CodTipoProceso="+CodTipoProceso+"&CodTipoProceso="+CodTipoProceso+"&TipoObligacion="+TipoObligacion,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 450);
				else {
					var funct = "document.getElementById('frmentrada').submit();";
					cajaModal("Se calcularon las obligaciones exitosamente", "exito", 400, funct);
				}
			}
		});
	}
}

// 	interfase de cuentas por pagar (consolidar)
function interfase_cuentas_por_pagar_consolidar(TipoObligacion) {
	bloqueo(true);
	
	//	valores
	var CodOrganismo = $("#fCodOrganismo").val();
	var CodTipoNom = $("#fCodTipoNom").val();
	var Periodo = $("#fPeriodo").val();
	var CodTipoProceso = $("#fCodTipoProceso").val();
	var detalles_bancos = "";
	var detalles_cheques = "";
	var detalles_terceros = "";
	var detalles_judiciales = "";
	
	//	valido
	var error = "";
	if (CodOrganismo == "") error = "Debe seleccionar el Organismo";
	else if (CodTipoNom == "") error = "Debe seleccionar la N&oacute;mina";
	else if (Periodo == "") error = "Debe seleccionar el Periodo";
	else if (CodTipoProceso == "") error = "Debe seleccionar el Proceso";
	
	if (error == "") {
		if (TipoObligacion == "01") {
			//	listado (interfase bancaria)
			var nro_bancos = 0;
			var frm_bancos = document.getElementById("frm_bancos");
			for(var i=0; n=frm_bancos.elements[i]; i++) {
				if (n.name == "bancos" && n.checked) { detalles_bancos += n.value + ";"; ++nro_bancos; }
			}
			var len = detalles_bancos.length; len-=1;
			detalles_bancos = detalles_bancos.substr(0, len);
		}
		else if (TipoObligacion == "02") {
			//	listado (cheques)
			var nro_cheques = 0;
			var frm_cheques = document.getElementById("frm_cheques");
			for(var i=0; n=frm_cheques.elements[i]; i++) {
				if (n.name == "cheques" && n.checked) { detalles_cheques += n.value + ";"; ++nro_cheques; }
			}
			var len = detalles_cheques.length; len-=1;
			detalles_cheques = detalles_cheques.substr(0, len);
		}
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 450);
	} else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=interfase_cuentas_por_pagar&accion=consolidar&CodOrganismo="+CodOrganismo+"&CodTipoNom="+CodTipoNom+"&Periodo="+Periodo+"&CodTipoProceso="+CodTipoProceso+"&detalles_bancos="+detalles_bancos+"&detalles_cheques="+detalles_cheques+"&TipoObligacion="+TipoObligacion,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 450);
				else {
					var funct = "document.getElementById('frmentrada').submit();";
					cajaModal("Se consolidaron las obligaciones exitosamente", "exito", 400, funct);
				}
			}
		});
	}
}

// 	interfase de cuentas por pagar (generar)
function interfase_cuentas_por_pagar_generar(form) {
	bloqueo(true);
	
	//	formulario
	if (document.getElementById("FlagCompromiso").checked) var FlagCompromiso = "S"; else var FlagCompromiso = "N";
	var MontoAfecto = new Number(setNumero($("#MontoAfecto").val()));
	var MontoNoAfecto = new Number(setNumero($("#MontoNoAfecto").val()));
	var MontoImpuesto = new Number(setNumero($("#MontoImpuesto").val()));
	var MontoImpuestoOtros = new Number(setNumero($("#MontoImpuestoOtros").val()));
	var MontoObligacion = new Number(setNumero($("#MontoObligacion").val()));
	var MontoAdelanto = new Number(setNumero($("#MontoAdelanto").val()));
	var MontoPagar = new Number(setNumero($("#MontoPagar").val()));
	var MontoPagoParcial = new Number(setNumero($("#MontoPagoParcial").val()));
	var MontoPendiente = new Number(setNumero($("#MontoPendiente").val()));
	var documento_afecto = new Number(setNumero($("#documento_afecto").val()));
	var documento_impuesto = new Number(setNumero($("#documento_impuesto").val()));
	var documento_noafecto = new Number(setNumero($("#documento_noafecto").val()));
	var distribucion_total = new Number(setNumero($("#distribucion_total").val()));
	var impuesto_total = new Number(setNumero($("#impuesto_total").val()));
	var Estado = $('#Estado').val();
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodProveedor" || n.id == "CodProveedorPagar" || n.id == "CodOrganismo" || n.id == "CodCentroCosto" || n.id == "CodTipoDocumento" || n.id == "NroDocumento" || n.id == "NroControl" || n.id == "CodTipoServicio" || n.id == "CodTipoPago" || n.id == "NroCuenta") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valNumericoEntero(n.value) && n.id == "DiasPago") { error = "Formato de fecha de factura es incorrecta"; break; }
		else if (!valFecha(n.value) && n.id == "FechaFactura") { error = "Formato de fecha de factura es incorrecta"; break; }
		else if (!valFecha(n.value) && n.id == "FechaRegistro") { error = "Formato de fecha de registro es incorrecta"; break; }
		else if (!valFecha(n.value) && n.id == "FechaDocumento") { error = "Formato de fecha de documento es incorrecta"; break; }
		else if (!valFecha(n.value) && n.id == "FechaRecepcion") { error = "Formato de fecha de recepcion es incorrecta"; break; }
		else if (!valFecha(n.value) && n.id == "FechaVencimiento") { error = "Formato de fecha de vencimiento es incorrecta"; break; }
		else if (!valFecha(n.value) && n.id == "FechaProgramada") { error = "Formato de fecha programada es incorrecta"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoAfecto") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoNoAfecto") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoImpuesto") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoImpuestoOtros") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoObligacion") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoAdelanto") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoPagar") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoPagoParcial") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoPendiente") { error = "Formato de montos incorrectos"; break; }
	}
	
	//	detalles impuesto
	var detalles_impuesto = "";
	var frm_impuesto = document.getElementById("frm_impuesto");
	for(var i=0; n=frm_impuesto.elements[i]; i++) {
		if (n.name == "CodImpuesto") detalles_impuesto += n.value + ";char:td;";
		else if (n.name == "CodConcepto") detalles_impuesto += n.value + ";char:td;";
		else if (n.name == "Signo") detalles_impuesto += n.value + ";char:td;";
		else if (n.name == "FlagImponible") detalles_impuesto += n.value + ";char:td;";
		else if (n.name == "FlagProvision") detalles_impuesto += n.value + ";char:td;";
		else if (n.name == "CodCuenta") {
			if (n.value.trim() == "" && $("#CONTONCO").val() == "S") { error = "Se encontraron lineas en los Impuestos sin Cuentas Contables.<br>Revise el Perfil de Conceptos para este Proceso."; break; }
			else detalles_impuesto += n.value + ";char:td;";
		}
		else if (n.name == "CodCuentaPub20") {
			if (n.value.trim() == "" && $("#CONTPUB20").val() == "S") { error = "Se encontraron lineas en los Impuestos sin Cuentas Contables.<br>Revise el Perfil de Conceptos para este Proceso."; break; }
			else detalles_impuesto += n.value + ";char:td;";
		}
		else if (n.name == "MontoAfecto") {
			var _MontoAfecto = new Number(setNumero(n.value));
			//if (isNaN(_MontoAfecto) || _MontoAfecto == 0) error = "Se encontraron lineas en las retenciones con montos incorrectos";
			detalles_impuesto += _MontoAfecto + ";char:td;";
		}
		else if (n.name == "FactorPorcentaje") {
			var _FactorPorcentaje = new Number(setNumero(n.value));
			//if (isNaN(_FactorPorcentaje) || _FactorPorcentaje <= 0) error = "Se encontraron lineas en las retenciones con montos incorrectos";
			detalles_impuesto += _FactorPorcentaje + ";char:td;";
		}
		else if (n.name == "MontoImpuesto") {
			var _MontoImpuesto = new Number(setNumero(n.value));
			if (isNaN(_MontoImpuesto) || _MontoImpuesto == 0) error = "Se encontraron lineas en las retenciones con montos incorrectos";
			detalles_impuesto += _MontoImpuesto + ";char:tr;";
		}
	}
	var len = detalles_impuesto.length; len-=9;
	detalles_impuesto = detalles_impuesto.substr(0, len);
	
	//	detalles documento
	var detalles_documento = "";
	var frm_documento = document.getElementById("frm_documento");
	for(var i=0; n=frm_documento.elements[i]; i++) {
		if (n.name == "Porcentaje") detalles_documento += n.value + ";char:td;";
		else if (n.name == "ReferenciaTipoDocumento") detalles_documento += n.value + ";char:td;";
		else if (n.name == "DocumentoClasificacion") detalles_documento += n.value + ";char:td;";
		else if (n.name == "DocumentoReferencia") detalles_documento += n.value + ";char:td;";
		else if (n.name == "Fecha") detalles_documento += n.value + ";char:td;";
		else if (n.name == "ReferenciaNroDocumento") detalles_documento += n.value + ";char:td;";
		else if (n.name == "MontoTotal") {
			var _MontoTotal = new Number(setNumero(n.value));
			if (isNaN(_MontoTotal) || _MontoTotal < 0) { error = "Se encontraron lineas en los documentos con montos incorrectos"; break; }
			detalles_documento += _MontoTotal + ";char:td;";
		}
		else if (n.name == "MontoAfecto") {
			var _MontoAfecto = new Number(setNumero(n.value));
			if (isNaN(_MontoAfecto) || _MontoAfecto < 0) { error = "Se encontraron lineas en los documentos con montos incorrectos"; break; }
			detalles_documento += _MontoAfecto + ";char:td;";
		}
		else if (n.name == "MontoImpuestos") {
			var _MontoImpuestos = new Number(setNumero(n.value));
			if (isNaN(_MontoImpuestos) || _MontoImpuestos < 0) { error = "Se encontraron lineas en los documentos con montos incorrectos"; break; }
			detalles_documento += _MontoImpuestos + ";char:td;";
		}
		else if (n.name == "MontoNoAfecto") {
			var _MontoNoAfecto = new Number(setNumero(n.value));
			if (isNaN(_MontoNoAfecto) || _MontoNoAfecto < 0) { error = "Se encontraron lineas en los documentos con montos incorrectos"; break; }
			detalles_documento += _MontoNoAfecto + ";char:td;";
		}
		else if (n.name == "Comentarios") detalles_documento += changeUrl(n.value.trim()) + ";char:tr;";
	}
	var len = detalles_documento.length; len-=9;
	detalles_documento = detalles_documento.substr(0, len);
	
	//	detalles distribucion
	var _MontoAfecto = new Number(0);
	var _MontoNoAfecto = new Number(0);
	var detalles_distribucion = "";
	var frm_distribucion = document.getElementById("frm_distribucion");
	for(var i=0; n=frm_distribucion.elements[i]; i++) {
		if (n.name == "cod_partida") {
			if (document.getElementById("FlagPresupuesto").checked && n.value == "") error = "Se encontraron lineas en la distribucion sin partidas presupuestarias";
			detalles_distribucion += n.value + ";char:td;";
		}
		else if (n.name == "CodCuenta") {
			if (n.value.trim() == "" && $("#CONTONCO").val() == "S") error = "Se encontraron lineas en la distribucion sin cuentas contables";
			detalles_distribucion += n.value + ";char:td;";
		}
		else if (n.name == "CodCuentaPub20") {
			if (n.value.trim() == "" && $("#CONTPUB20").val() == "S") error = "Se encontraron lineas en la distribucion sin cuentas contables (Pub. 20)";
			detalles_distribucion += n.value + ";char:td;";
		}
		else if (n.name == "CodCentroCosto") detalles_distribucion += n.value + ";char:td;";
		else if (n.name == "FlagNoAfectoIGV") {
			if (n.checked) { detalles_distribucion += "S" + ";char:td;"; var _FlagNoAfectoIGV = "S"; }
			else { detalles_distribucion += "N" + ";char:td;"; var _FlagNoAfectoIGV = "N"; }
		}
		else if (n.name == "Monto") {
			var Monto = new Number(setNumero(n.value));
			if (isNaN(Monto) || Monto <= 0) error = "Se encontraron lineas en la distribucion con montos incorrectos";
			detalles_distribucion += Monto + ";char:td;";
			if (_FlagNoAfectoIGV == "N") _MontoAfecto += Monto; else _MontoNoAfecto += Monto;
		}
		else if (n.name == "TipoOrden") detalles_distribucion += n.value + ";char:td;";
		else if (n.name == "NroOrden") detalles_distribucion += n.value + ";char:td;";
		else if (n.name == "Referencia") detalles_distribucion += n.value + ";char:td;";
		else if (n.name == "Descripcion") detalles_distribucion += changeUrl(n.value.trim()) + ";char:td;";
		else if (n.name == "CodPersona") detalles_distribucion += n.value + ";char:td;";
		else if (n.name == "NroActivo") detalles_distribucion += n.value + ";char:td;";
		else if (n.name == "FlagDiferido") {
			if (n.checked) detalles_distribucion += "S" + ";char:tr;";
			else detalles_distribucion += "N" + ";char:tr;";
		}
	}
	var len = detalles_distribucion.length; len-=9;
	detalles_distribucion = detalles_distribucion.substr(0, len);
	
	//	detalles partidas
	var detalles_partidas = "";
	var frm_partidas = document.getElementById("frm_partidas");
	for(var i=0; n=frm_partidas.elements[i]; i++) {
		if (n.name == "cod_partida") {
			var cod_partida = n.value;
			detalles_partidas += n.value + ";char:td;";
		}
		else if (n.name == "Monto") {
			var Monto = new Number(n.value);
			detalles_partidas += n.value + ";char:td;";
		}
		else if (n.name == "MontoDisponible") {
			var MontoDisponible = new Number(n.value);
			detalles_partidas += n.value + ";char:td;";
		}
		else if (n.name == "MontoPendiente") {
			var MontoPendiente = new Number(n.value);
			if (MontoDisponible < Monto && FlagCompromiso == "S" && Estado == "PR") { error = "Sin disponibilidad presupuestaria la partida <strong>" + cod_partida + "</strong>"; break; }
			else detalles_partidas += n.value + ";char:tr;";
		}
	}
	var len = detalles_partidas.length; len-=9;
	detalles_partidas = detalles_partidas.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		var url = "modulo=interfase_cuentas_por_pagar&accion=generar&"+post+"&detalles_impuesto="+detalles_impuesto+"&detalles_documento="+detalles_documento+"&detalles_distribucion="+detalles_distribucion;
		obligacion_ajax(form, url);
	}
	return false;
}
//	obligacion (ajax)
function obligacion_ajax(form, url) {
	$.ajax({
		type: "POST",
		url: "lib/form_ajax.php",
		data: url,
		async: false,
		success: function(resp) {
			var datos = resp.split("|");
			if (datos[0].trim() != "") cajaModal(datos[0], "error", 400);
			else form.submit();
		}
	});
}

// 	interfase de cuentas por pagar (verificar)
function interfase_cuentas_por_pagar_verificar(form) {
	$(".div-progressbar").css("display", "block");
	
	var error = "";
	//	partidas
	var frm_partidas = document.getElementById("frm_partidas");
	for(var i=0; n=frm_partidas.elements[i]; i++) {
		if (n.name == "Diferencia") {
			var Diferencia = parseFloat(n.value);
			if (Diferencia < 0) { error = "Se encontraron partidas sin Disponibilidad"; break; }
		}
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=interfase_cuentas_por_pagar&accion=verificar&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else {
					var funct = "parent.$.prettyPhoto.close();";
					funct += "parent.document.getElementById('frmentrada').submit();";
					cajaModal("Presupuesto verificado exitosamente", "exito", 400, funct);
				}
			}
		});
	}
	return false;
}

//	conceptos
function conceptos(form, accion) {
	$(".div-progressbar").css("display", "block");
	
	//	valido
	var error = "";
	if ($("#Descripcion").val().trim() == "" || $("#TextoImpresion").val().trim() == "" || $("#Tipo").val() == "") error = "Debe llenar los campos obligatorios";
	if (isNaN(setNumero($("#PlanillaOrden").val()))) error = "Orden de Boleta incorrecto";
	
	//	tipos de nomina
	var detalles_nominas = "";
	var frm_nominas = document.getElementById("frm_nominas");
	for(var i=0; n=frm_nominas.elements[i]; i++) {
		if (n.name == "CodTipoNom") detalles_nominas += n.value + ";char:tr;";
	}
	var len = detalles_nominas.length; len-=9;
	detalles_nominas = detalles_nominas.substr(0, len);
	
	//	tipos de proceso
	var detalles_procesos = "";
	var frm_procesos = document.getElementById("frm_procesos");
	for(var i=0; n=frm_procesos.elements[i]; i++) {
		if (n.name == "CodTipoProceso") detalles_procesos += n.value + ";char:tr;";
	}
	var len = detalles_procesos.length; len-=9;
	detalles_procesos = detalles_procesos.substr(0, len);
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		//	formulario
		var post = getForm(form);
		if ($("#FlagObligacion").prop("checked")) var FlagObligacion = "S"; else var FlagObligacion = "N";
		var CodPersona = $("#CodPersona").val();
		//var FormulaEditor = changeUrl($("#Formula").html());
		var Formula = changeUrl($("#Formula").val().trim());
		if ($('#Formula').val().trim() != "") var FlagFormula = "S"; else var FlagFormula = "N";
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=conceptos&accion="+accion+"&"+post+"&FlagObligacion="+FlagObligacion+"&CodPersona="+CodPersona+"&Formula="+Formula+"&detalles_nominas="+detalles_nominas+"&detalles_procesos="+detalles_procesos+"&FlagFormula="+FlagFormula,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	perfil de conceptos
function conceptos_perfil(form, accion) {
	$(".div-progressbar").css("display", "block");
	
	//	valido
	var error = "";
	if ($("#Descripcion").val().trim() == "") error = "Debe llenar los campos obligatorios";
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=conceptos_perfil&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	perfil de conceptos (detalle)
function conceptos_perfil_detalle(form) {
	$(".div-progressbar").css("display", "block");
	
	//	valido
	var error = "";
	
	//	conceptos
	var detalles_conceptos = "";
	var frm_conceptos = document.getElementById("frm_conceptos");
	for(var i=0; n=frm_conceptos.elements[i]; i++) {
		if (n.name == "CodTipoProceso") detalles_conceptos += n.value + ";char:td;";
		else if (n.name == "CodConcepto") detalles_conceptos += n.value + ";char:td;";
		else if (n.name == "cod_partida") detalles_conceptos += n.value + ";char:td;";
		else if (n.name == "CuentaDebe") detalles_conceptos += n.value + ";char:td;";
		else if (n.name == "CuentaDebePub20") detalles_conceptos += n.value + ";char:td;";
		else if (n.name == "FlagDebeCC") {
			if (n.checked) detalles_conceptos += "S;char:td;";
			else detalles_conceptos += "N;char:td;";
		}
		else if (n.name == "CuentaHaber") detalles_conceptos += n.value + ";char:td;";
		else if (n.name == "CuentaHaberPub20") detalles_conceptos += n.value + ";char:td;";
		else if (n.name == "FlagHaberCC") {
			if (n.checked) detalles_conceptos += "S;char:tr;";
			else detalles_conceptos += "N;char:tr;";
		}
	}
	var len = detalles_conceptos.length; len-=9;
	detalles_conceptos = detalles_conceptos.substr(0, len);
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=conceptos_perfil&accion=conceptos&"+post+"&detalles_conceptos="+detalles_conceptos,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	control de procesos
function procesos_control(form, accion) {
	bloqueo(true);
	
	//	valido
	var error = "";
	if ($("#CodOrganismo").val() == "" || $("#CodTipoNom").val() == "" || $("#Periodo").val() == "" || $("#CodTipoProceso").val() == "" || $("#FechaDesde").val().trim() == "" || $("#FechaHasta").val().trim() == "") error = "Debe llenar los campos obligatorios";
	else if (!valFecha($("#FechaDesde").val())) error = "Formato de Fecha Desde incorrecta";
	else if (!valFecha($("#FechaHasta").val())) error = "Formato de Fecha Hasta incorrecta";
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=procesos_control&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	ejecucion de procesos
function procesos_control_ejecucion() {
	bloqueo(true);
	
	//	personas
	var error = "";
	var detalles_personas = "";
	var chk = false;
	var frm_personas = document.getElementById("frm_aprobados");
	for(var i=0; n=frm_personas.elements[i]; i++) {
		if (n.name == "personas" && n.checked) {
			chk = true;
			detalles_personas += n.value + ";char:tr;";
		}
		else if (n.name == "EstadoPago" && chk) {
			chk = false;
			if (n.value == "TR") { error = "No puede Generar Empleados que ya fueron Transferidos a Obligaciones x Pagar"; break; }
			else if (n.value == "PA") { error = "No puede Generar Empleados que ya se le generaron Pagos"; break; }
		}
	}
	var len = detalles_personas.length; len-=9;
	detalles_personas = detalles_personas.substr(0, len);
	if (detalles_personas == "") error = "Debe seleccionar los empleados a Procesar";
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		//	formulario
		var post = getForm(document.getElementById('frmentrada'));
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=procesos_control_ejecucion&accion=ejecutar&"+post+"&detalles_personas="+detalles_personas,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else document.getElementById('frmentrada').submit();
			}
		});
	}
	return false;
}

//	actualizar acumulados
function fideicomiso_actualizar() {
	bloqueo(true);
	
	//	personas
	var error = "";
	var detalles_personas = "";
	var frm_personas = document.getElementById("frm_personas");
	for(var i=0; n=frm_personas.elements[i]; i++) {
		if (n.name == "personas") {
			var sel = n.checked;
			if (sel) detalles_personas += n.value + ";char:td;";
		}
		else if (n.name == "Transaccion" && sel) detalles_personas += n.value + ";char:td;";
		else if (n.name == "Dias" && sel) detalles_personas += n.value + ";char:td;";
		else if (n.name == "Complemento" && sel) detalles_personas += n.value + ";char:td;";
		else if (n.name == "DiasAdicional" && sel) detalles_personas += n.value + ";char:td;";
		else if (n.name == "FlagFraccionado" && sel) detalles_personas += n.value + ";char:tr;";
	}
	var len = detalles_personas.length; len-=9;
	detalles_personas = detalles_personas.substr(0, len);
	if (detalles_personas == "") error = "Debe seleccionar los empleados a Actualizar";
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		//	formulario
		var post = getForm(document.getElementById('frmentrada'));
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=fideicomiso&accion=actualizar&"+post+"&detalles_personas="+detalles_personas,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else document.getElementById('frmentrada').submit();
			}
		});
	}
	return false;
}

// 	calculo de fideicomiso
function fideicomiso_procesar_calculo() {
	bloqueo(true);
	
	//	formulario
	var post = getForm(document.getElementById("frmentrada"));
	
	//	lista de calculo
	var detalles_periodos = "";
	var frm_periodos = document.getElementById("frm_periodos");
	for(var i=0; n=frm_periodos.elements[i]; i++) {
		if (n.name == "Periodo") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "SueldoMensual") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "Bonificaciones") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "AliVac") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "AliFin") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "SueldoDiario") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "SueldoDiarioAli") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "Dias") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "PrestAntiguedad") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "DiasComplemento") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "PrestComplemento") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "PrestAcumulada") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "Tasa") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "DiasMes") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "InteresMensual") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "InteresAcumulado") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "Anticipo") detalles_periodos += n.value + ";char:tr;";
	}
	var len = detalles_periodos.length; len-=9;
	detalles_periodos = detalles_periodos.substr(0, len);
	
	//	ajax
	$.ajax({
		type: "POST",
		url: "lib/form_ajax.php",
		data: "modulo=fideicomiso&accion=procesar&"+post+"&detalles_periodos="+detalles_periodos,
		async: false,
		success: function(resp) {
			if (resp.trim() != "") cajaModal(resp, "error", 400);
			else cajaModal("Se procesaron los datos exitosamente", "exito", 400);
		}
	});
}

//	acumulado de fideicomiso
function fideicomiso_acumulado(form) {
	bloqueo(true);
	
	//	valido
	var error = "";
	if ($("#PeriodoInicial").val().trim() == "") error = "Debe llenar los campos obligatorios";
	else if (!valPeriodo($("#PeriodoInicial").val())) error = "Formato de Periodo Inicial incorrecto";
	else if (isNaN(setNumero($("#AcumuladoInicialDias").val())) && $("#AcumuladoInicialDias").val() != "") error = "Formato de Dias Inicial incorrecto";
	else if (isNaN(setNumero($("#AcumuladoDiasAdicionalInicial").val())) && $("#AcumuladoDiasAdicionalInicial").val() != "") error = "Formato de Dias Adicional Inicial incorrecto";
	else if (isNaN(setNumero($("#AcumuladoInicialProv").val())) && $("#AcumuladoInicialProv").val() != "") error = "Formato de Antiguedad Inicial incorrecto";
	else if (isNaN(setNumero($("#AcumuladoInicialFide").val())) && $("#AcumuladoInicialFide").val() != "") error = "Formato de Fideicomiso Inicial incorrecto";
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=fideicomiso&accion=acumulado&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	tipos de proceso
function tipo_proceso(form, accion) {
	bloqueo(true);
	
	//	valido
	var error = "";
	if ($("#CodTipoProceso").val().trim() == "" || $("#Descripcion").val().trim() == "") error = "Debe llenar los campos obligatorios";
	else if (!valCodigo($("#CodTipoProceso").val())) error = "Formato para el C&oacute;digo Incorrecto";
	else if ($("#PeriodoNomina").val().trim() == "" || !valPeriodo($("#PeriodoNomina").val())) error = "Periodo N&oacute;mina Incorrecto";
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=tipo_proceso&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	conceptos asignacion
function conceptos_asignacion() {
	bloqueo(true);
	
	//	valido
	var TipoAplicacion = $("#TipoAplicacion").val();
	var error = "";
	if ($("#CodConcepto").val()) error = "Debe seleccionar el Concepto";
	
	//	empleados
	var error = "";
	var detalles_empleados = "";
	var chk = false;
	var frm_empleados = document.getElementById("frm_empleados");
	for(var i=0; n=frm_empleados.elements[i]; i++) {
		if (n.name == "empleados" && n.checked) {
			detalles_empleados += n.value + ";char:td;";
			chk = true;
		}
		else if (n.name == "PeriodoDesde" && chk) {
			var PeriodoDesde = n.value;
			detalles_empleados += PeriodoDesde + ";char:td;";
		}
		else if (n.name == "PeriodoHasta" && chk) {
			var PeriodoHasta = n.value;
			if (PeriodoDesde > PeriodoHasta && TipoAplicacion == "T") { error = "El Periodo Inicio no puede ser mayor al Periodo Fin"; break; }
			else detalles_empleados += PeriodoHasta + ";char:td;";
		}
		else if (n.name == "FlagManual" && chk) {
			if (n.checked) detalles_empleados += "S;char:td;";
			else detalles_empleados += "N;char:td;";
		}
		else if (n.name == "Monto" && chk) detalles_empleados += n.value + ";char:td;";
		else if (n.name == "Cantidad" && chk) detalles_empleados += n.value + ";char:td;";
		else if (n.name == "Procesos" && chk) detalles_empleados += n.value + ";char:td;";
		else if (n.name == "Estado" && chk) {
			detalles_empleados += n.value + ";char:tr;";
			chk = false;
		}
	}
	var len = detalles_empleados.length; len-=9;
	detalles_empleados = detalles_empleados.substr(0, len);
	if (detalles_empleados == "") error = "Debe seleccionar los empleados a Procesar";
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		//	formulario
		var post = getForm(document.getElementById('frmentrada'));
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=conceptos_asignacion&"+post+"&detalles_empleados="+detalles_empleados,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else cajaModal("Concepto asignado exitosamente", "exito", 400, "document.getElementById('frmentrada').submit();");
			}
		});
	}
	return false;
}

//	ajuste salarial (grado salarial)
function ajuste_salarial(form, accion) {
	bloqueo(true);
	
	//	valido
	var error = "";
	if ($("#CodOrganismo").val() == "" || $("#NroResolucion").val().trim() == "" || $("#Descripcion").val().trim() == "") error = "Debe llenar los campos obligatorios";
	
	//	ajustes
	var detalles_ajustes = "";
	var frm_ajustes = document.getElementById("frm_ajustes");
	for(var i=0; n=frm_ajustes.elements[i]; i++) {
		if (n.name == "CodNivel") {
			var chk = n.checked;
			if (chk) detalles_ajustes += n.value + ";char:td;";
		}
		else if (n.name == "SueldoBasico" && chk) detalles_ajustes += setNumero(n.value) + ";char:td;";
		else if (n.name == "Porcentaje" && chk) {
			var Porcentaje = setNumero(n.value);
			if (isNaN(Porcentaje)) { error = "Se encontraron Porcentajes incorrectos"; break; }
			else detalles_ajustes += Porcentaje + ";char:td;";
		}
		else if (n.name == "Monto" && chk) {
			var Monto = setNumero(n.value);
			if (isNaN(Monto)) { error = "Se encontraron Montos Incorrectos"; break; }
			else detalles_ajustes += Monto + ";char:td;";
		}
		else if (n.name == "SueldoNuevo" && chk) {
			detalles_ajustes += setNumero(n.value) + ";char:tr;";
			chk = false;
		}
	}
	var len = detalles_ajustes.length; len-=9;
	detalles_ajustes = detalles_ajustes.substr(0, len);
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=ajuste_salarial&accion="+accion+"&"+post+"&detalles_ajustes="+detalles_ajustes,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	ajuste salarial (empleados)
function ajuste_salarial_emp(form, accion) {
	bloqueo(true);
	
	//	valido
	var error = "";
	if ($("#CodOrganismo").val() == "" || $("#NroResolucion").val().trim() == "" || $("#Descripcion").val().trim() == "") error = "Debe llenar los campos obligatorios";
	
	//	ajustes
	var detalles_ajustes = "";
	var frm_ajustes = document.getElementById("frm_ajustes");
	for(var i=0; n=frm_ajustes.elements[i]; i++) {
		if (n.name == "CodPersona") {
			var chk = n.checked;
			if (chk) detalles_ajustes += n.value + ";char:td;";
		}
		else if (n.name == "SueldoBasico" && chk) detalles_ajustes += setNumero(n.value) + ";char:td;";
		else if (n.name == "Porcentaje" && chk) {
			var Porcentaje = setNumero(n.value);
			if (isNaN(Porcentaje)) { error = "Se encontraron Porcentajes incorrectos"; break; }
			else detalles_ajustes += Porcentaje + ";char:td;";
		}
		else if (n.name == "Monto" && chk) {
			var Monto = setNumero(n.value);
			if (isNaN(Monto)) { error = "Se encontraron Montos Incorrectos"; break; }
			else detalles_ajustes += Monto + ";char:td;";
		}
		else if (n.name == "SueldoNuevo" && chk) {
			detalles_ajustes += setNumero(n.value) + ";char:tr;";
			chk = false;
		}
	}
	var len = detalles_ajustes.length; len-=9;
	detalles_ajustes = detalles_ajustes.substr(0, len);
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=ajuste_salarial_emp&accion="+accion+"&"+post+"&detalles_ajustes="+detalles_ajustes,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	control de procesos (prenomina)
function prenomina_procesos(form, accion) {
	bloqueo(true);
	
	//	valido
	var error = "";
	if ($("#CodOrganismo").val() == "" || $("#CodTipoNom").val() == "" || $("#Periodo").val() == "" || $("#CodTipoProceso").val() == "" || $("#FechaDesde").val().trim() == "" || $("#FechaHasta").val().trim() == "") error = "Debe llenar los campos obligatorios";
	else if (!valFecha($("#FechaDesde").val())) error = "Formato de Fecha Desde incorrecta";
	else if (!valFecha($("#FechaHasta").val())) error = "Formato de Fecha Hasta incorrecta";
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=prenomina_procesos&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}