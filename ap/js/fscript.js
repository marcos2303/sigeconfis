// JavaScript Document

//	VALIDO SI EL TIPO DE SERVICIO ES AFECTO A IMPUESTO O NO
function afectaTipoServicioObligacion(CodTipoServicio) {
	$.ajax({
		type: "POST",
		url: "../lib/fphp_funciones_ajax.php",
		data: "accion=afectaTipoServicio&CodTipoServicio="+CodTipoServicio,
		async: false,
		success: function(resp) {
			//	activo/desactivo afecto de la distribucion
			if (resp.trim() == "S") {
				$(".FlagNoAfectoIGV").removeAttr("checked");
				if (document.getElementById("FlagDistribucionManual").checked) $(".FlagNoAfectoIGV").removeAttr("disabled");
			} else {
				$("#lista_impuesto").html("");
				//$("#btInsertarImpuesto").attr("disabled", "disabled");
				//$("#btQuitarImpuesto").attr("disabled", "disabled");
				$(".FlagNoAfectoIGV").attr("disabled", "disabled").attr("checked", "checked");
			}
			//	actualizo valores
			actualizarMontosObligacion();
		}
	});
}
//	--------------------------------------

//	funcion para cargar un ajax e imprimir la respuesta en otro objeto
function mostrarDocumentosObligacion() {
	var CodProveedor = $("#fCodProveedor").val();
	var DocumentoClasificacion = $("#fDocumentoClasificacion").val();
	//	detalles documento
	var detalles_documento = "";
	var frm_documento = document.getElementById("frm_documentos");
	for(var i=0; n=frm_documento.elements[i]; i++) {
		if (n.name == "documento" && n.checked) detalles_documento += n.value + ";";
	}
	var len = detalles_documento.length; len--;
	detalles_documento = detalles_documento.substr(0, len);
	
	//	envio los datos por ajax
	$.ajax({
		type: "POST",
		url: "lib/fphp_funciones_ajax.php",
		data: "accion=mostrarDocumentosObligacion&detalles_documento="+detalles_documento+"&CodProveedor="+CodProveedor+"&DocumentoClasificacion="+DocumentoClasificacion,
		async: false,
		success: function(resp) {
			$("#lista_detalles").html(resp);
		}
	});
}
//	--------------------------------------

//	muestro el tab de distribucion de la obligacion
function mostrarTabDistribucionObligacion() {
	var Anio = $("#Anio").val();
	var CodPresupuesto = $("#CodPresupuesto").val();
	var CodOrganismo = $("#CodOrganismo").val();
	var MontoImpuesto = new Number(setNumero($("#MontoImpuesto").val()));
	if (document.getElementById("FlagPresupuesto").checked) var FlagPresupuesto = "S"; else var FlagPresupuesto = "N";
	if (document.getElementById("FlagCompromiso").checked) var FlagCompromiso = "S"; else var FlagCompromiso = "N";
	//	detalles
	var detalles = "";
	var frm_detalles = document.getElementById("frm_distribucion");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "cod_partida") detalles += n.value + ";char:td;";
		else if (n.name == "CodCuenta") detalles += n.value + ";char:td;";
		else if (n.name == "CodCuentaPub20") detalles += n.value + ";char:td;";
		else if (n.name == "Monto") {
			var Monto = parseFloat(setNumero(n.value));
			if (isNaN(Monto) || Monto <= 0) Monto = 0;
			detalles += Monto + ";char:tr;";
		}
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	if (detalles != "") {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=mostrarTabDistribucionObligacion&detalles="+detalles+"&MontoImpuesto="+MontoImpuesto+"&FlagPresupuesto="+FlagPresupuesto+"&FlagCompromiso="+FlagCompromiso+"&CodOrganismo="+CodOrganismo+"&CodPresupuesto="+CodPresupuesto+"&Anio="+Anio,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");
				$("#lista_cuentas").html(partes[0]);
				$("#lista_cuentas20").html(partes[1]);
				$("#lista_partidas").html(partes[2]);
				mostrarTab("tab", 4, 5);
			}
		});
	} else mostrarTab("tab", 4, 5);
}
//	--------------------------------------

//	exportar registro a txt
function registro_compra_seniat_txt(form, nombre_archivo) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + n.value.trim() + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
	}
	
	//	ajax
	$.ajax({
		type: "POST",
		url: "ap_registro_compra_seniat_txt.php",
		data: post+"&nombre_archivo="+nombre_archivo,
		async: false,
		success: function(resp) {
			window.open("../lib/descargar_txt.php?nombre_archivo="+nombre_archivo, "ap_registro_compra_seniat_txt", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=100, width=100, left=500, top=500, resizable=yes");
		}
	});
}
//	--------------------------------------

//	exportar registro a txt
function registro_compra_seniat_excel(form, nombre_archivo) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + n.value.trim() + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
	}
	//	abrir archivo
	window.open("ap_registro_compra_seniat_excel.php?nombre_archivo="+nombre_archivo+"&"+post, "ap_registro_compra_seniat_excel", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=100, width=100, left=500, top=500, resizable=yes");
}
//	--------------------------------------

//	
function cajaChicaMontoPagado(id) {
	var CodTipoServicio = $("#CodTipoServicio_"+id).val();
	var MontoPagado = setNumero($("#MontoPagado_"+id).val());
	if (!isNaN(MontoPagado)) {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=cajaChicaMontoPagado&CodTipoServicio="+CodTipoServicio+"&MontoPagado="+MontoPagado,
			async: false,
			success: function(resp) {
				var datos = resp.split("|");
				$("#MontoAfecto_"+id).val(datos[0]).formatCurrency();
				$("#MontoNoAfecto_"+id).val(datos[1]).formatCurrency();
				$("#MontoImpuesto_"+id).val(datos[2]).formatCurrency();
				caja_chica_totales();
			}
		});
	}
}
//	--------------------------------------

//	
function cajaChicaMontoAfecto(id) {
	var CodTipoServicio = $("#CodTipoServicio_"+id).val();
	var MontoAfecto = setNumero($("#MontoAfecto_"+id).val());
	var MontoNoAfecto = setNumero($("#MontoNoAfecto_"+id).val());
	if (!isNaN(MontoAfecto)) {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=cajaChicaMontoAfecto&CodTipoServicio="+CodTipoServicio+"&MontoAfecto="+MontoAfecto+"&MontoNoAfecto="+MontoNoAfecto,
			async: false,
			success: function(resp) {
				var datos = resp.split("|");
				$("#MontoPagado_"+id).val(datos[0]).formatCurrency();
				$("#MontoImpuesto_"+id).val(datos[1]).formatCurrency();
				caja_chica_totales();
			}
		});
	}
}
//	--------------------------------------

//	muestro el tab de distribucion de la caja chica
function mostrarTabDistribucionCajaChica() {
	var Periodo = $("#Periodo").val();
	var FlagCajaChica = $("#FlagCajaChica").val();
	var NroCajaChica = $("#NroCajaChica").val();
	var CodPresupuesto = $("#CodPresupuesto").val();
	var CodOrganismo = $("#CodOrganismo").val();
	var MontoImpuesto = setNumero($("#MontoImpuesto").val());
	//	detalles
	var detalles_conceptos = "";
	var frm_conceptos = document.getElementById("frm_conceptos");
	for(var i=0; n=frm_conceptos.elements[i]; i++) {
		if (n.name == "MontoAfecto") var MontoAfecto = setNumero(n.value);
		else if (n.name == "MontoNoAfecto") {
			var MontoNoAfecto = setNumero(n.value);
			var Monto = MontoAfecto + MontoNoAfecto;
			detalles_conceptos += Monto + ";char:td;";
		}
		else if (n.name == "CodPartida") detalles_conceptos += n.value + ";char:td;";
		else if (n.name == "CodCuenta") detalles_conceptos += n.value + ";char:td;";
		else if (n.name == "CodCuentaPub20") detalles_conceptos += n.value + ";char:td;";
		else if (n.name == "Distribucion") detalles_conceptos += n.value + ";char:tr;";
	}
	var len = detalles_conceptos.length; len-=9;
	detalles_conceptos = detalles_conceptos.substr(0, len);
	
	if (detalles_conceptos != "") {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=mostrarTabDistribucionCajaChica&detalles_conceptos="+detalles_conceptos+"&Periodo="+Periodo+"&CodPresupuesto="+CodPresupuesto+"&CodOrganismo="+CodOrganismo+"&NroCajaChica="+NroCajaChica+"&FlagCajaChica="+FlagCajaChica+"&MontoImpuesto="+MontoImpuesto,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");
				$("#lista_cuentas").html(partes[0]);
				$("#lista_cuentas20").html(partes[1]);
				$("#lista_partidas").html(partes[2]);
				mostrarTab("tab", 3, 3);
			}
		});
	} else mostrarTab("tab", 3, 3);
}
//	--------------------------------------