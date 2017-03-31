/// --------------------------------------------------------------------------------- */ 
/// ---------- ////////// REPOPRTE LIBRO DIARIO
/// --------------------------------------------------------------------------------- */
function enabledRPPeriodo(form){
 if(form.chkPeriodo.checked) form.fPeriodo.disabled = false;
 else{form.fPeriodo.disabled = true; form.fPeriodo.value = '';}
}
function enabledRPContabilidad(form){
 if(form.chkContabilidad.checked) form.fContabilidad.disabled = false;
 else{form.fContabilidad.disabled = true; form.fContabilidad.value = '';}
}
function enabledRPVoucher(form){
 if(form.chkVoucher.checked) form.fVoucher.disabled = false;
 else{form.fVoucher.disabled = true; form.fVoucher.value = '';}
}
function enabledRPCuenta(form){
 if(form.chkCuenta.checked){ form.fCuentaDesde.disabled= false; form.fCuentaHasta.disabled=false;}
 else{ form.fCuentaDesde.disabled = true; form.fCuentaHasta.disabled = true; form.fCuentaDesde.value = ''; form.fCuentaHasta.value='';}
}
/// --------------------------------------------------------------------------------- */
/// ---------------	FUNCION QUE PERMITE MOSTRAR PDF REPORTE LIBRO DIARIO
function filtroReporteLibroDiario(form, limit) {

	var filtro1="";
	var codorganismo = document.getElementById("forganismo").value; 
	var Periodo = document.getElementById("fPeriodo").value; 
	if(form.chkorganismo.checked) filtro1+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkPeriodo.checked) filtro1+=" and a.Periodo=*"+form.fPeriodo.value+"*";
	else{ 
	     var PeriodoA = "0000-00";
		 var PeriodoB = "9999-99";
	     filtro1+=" and a.Periodo>=*"+PeriodoA+"*"+" and a.Periodo<=*"+PeriodoB+"*";
	}
	if(form.chkContabilidad.checked) filtro1+=" and a.CodContabilidad=*"+form.fContabilidad.value+"*";
	if(form.chkVoucher.checked) filtro1+=" and a.Voucher=*"+form.fVoucher.value+"*";

	var pagina="rp_librodiariopdf.php?filtro1="+filtro1+"&Periodo="+Periodo;
			cargarPagina(form, pagina);
}
/// --------------------------------------------------------------------------------- */
/// ---------------	FUNCION QUE PERMITE MOSTRAR PDF REPORTE LIBRO DIARIO
function filtroReporteLibroMayor(form, limit) {

	var filtro1="";
	var filtro2="";
	var Periodo = document.getElementById("fPeriodo").value;
	var codorganismo = document.getElementById("forganismo").value;
	if(form.chkorganismo.checked) filtro1+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkPeriodo.checked){
	   //if(Periodo==''){
	   //	  var PeriodoA = "0000-00";
	   //	  var PeriodoB = "9999-99";
	   //     filtro2+=" and a.Periodo>=*"+PeriodoA+"*"+" and a.Periodo<=*"+PeriodoB+"*";
	   //}else 
	   filtro1+=" and a.Periodo=*"+form.fPeriodo.value+"*";
	}
	if(form.chkContabilidad.checked) filtro1+=" and a.CodContabilidad=*"+form.fContabilidad.value+"*";
	if(form.chkCuenta.checked){ 
	   filtro1+=" and a.Voucher>=*"+form.fVoucher.value+"*";
	   filtro1+=" and a.Voucher<=*"+form.fVoucher.value+"*";
	}

	var pagina="rp_libromayorpdf.php?filtro1="+filtro1+"&Periodo="+Periodo;
			cargarPagina(form, pagina);
}
/// --------------------------------------------------------------------------------- */
/// ---------------	FUNCION QUE PERMITE MOSTRAR PDF REPORTE BALANCE COMPROBACION
function filtroReporteLibroDeComprobacion(form, limit) {
    var periodobusqueda = document.getElementById("fperiodo").value; 
	
	var filtro1="";
	var codorganismo = document.getElementById("forganismo").value;
	if(form.chkorganismo.checked) filtro1+=" and CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkContabilidad.checked) filtro1+=" and CodContabilidad=*"+form.fContabilidad.value+"*";
	if(form.chkperiodo.checked) filtro1+=" and Periodo=*"+form.fperiodo.value+"*";
	
	//alert(filtro1);
	var pagina="rp_balancecomprobacionpdf.php?filtro1="+filtro1+"&periodobusqueda="+periodobusqueda;
			cargarPagina(form, pagina);
}
/// --------------------------------------------------------------------------------- */
function enabledFechaRpBalanceComprobacion(form){
  if(form.chkPeriodo.checked){ form.fdesde.disabled = false; form.fhasta.disabled = false;}
  else{ form.fdesde.disabled=true; form.fdesde.value=''; form.fhasta.disabled=true; form.fhasta.value='';}
}
/// --------------------------------------------------------------------------------- */
/// ---------------	FUNCION QUE PERMITE MOSTRAR PDF REPORTE BALANCE COMPROBACION
/// --------------- SUMAS Y SALDOS
function filtroReporteLibroComprobacionSumasSaldos(form, limit) {

	var filtro1="";
	var codorganismo = document.getElementById("forganismo").value;
	if(form.chkorganismo.checked) filtro1+=" and ac.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkContabilidad.checked) filtro1+=" and ac.CodLibroCont=*"+form.fContabilidad.value+"*";
	
	if(form.chkPeriodo.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		//var fdesde = fdesde.split("-");
		    //fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0]; //alert(fd);
		
		//var fhasta = fhasta.split("-"); 
		    //fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
			fd = document.getElementById("fdesde").value;
			fh = document.getElementById("fhasta").value
		
	  filtro1+=" and ac.Periodo>=*"+fdesde+"*"+"and ac.Periodo <=*"+fhasta+"*";
	}else{
		fd = "0000-00"; fh = "9999-99";
	  filtro1+=" and ac.Periodo>=*"+fd+"*"+"and ac.Periodo <=*"+fh+"*";
	}

	var pagina="rp_balancesumassaldospdf.php?filtro1="+filtro1+"&fd="+fd+"&fh="+fh;;
			cargarPagina(form, pagina);
}
/// --------------------------------------------------------------------------------- */
/// ---------------	FUNCION QUE PERMITE MOSTRAR PDF REPORTE BALANCE COMPROBACION
/// MENSUAL DE CUENTAS DEL MAYOR
function filtroReporteLibroDeComprobacionMovMensual(form, limit) {
    var periodobusqueda = document.getElementById("fperiodo").value; 
	
	var filtro1="";
	var codorganismo = document.getElementById("forganismo").value;
	if(form.chkorganismo.checked) filtro1+=" and CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkContabilidad.checked) filtro1+=" and CodContabilidad=*"+form.fContabilidad.value+"*";
	if(form.chkperiodo.checked) filtro1+=" and Periodo<=*"+form.fperiodo.value+"*";
	
	//alert(filtro1);
	var pagina="rp_balancecomprobacionmovmensualpdf.php?filtro1="+filtro1+"&periodobusqueda="+periodobusqueda;
			cargarPagina(form, pagina);
}
/// --------------------------------------------------------------------------------- */
/// ---------------	FUNCION QUE PERMITE MOSTRAR PDF REPORTE BALANCE COMPROBACION
/// MENSUAL DE CUENTAS DEL MAYOR
function filtroReporteLibroDeComprobacionMensual(form, limit) {
    var periodobusqueda = document.getElementById("fperiodo").value; 
	
	var filtro1="";
	var codorganismo = document.getElementById("forganismo").value;
	if(form.chkorganismo.checked) filtro1+=" and CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkContabilidad.checked) filtro1+=" and CodContabilidad=*"+form.fContabilidad.value+"*";
	if(form.chkperiodo.checked) filtro1+=" and Periodo=*"+form.fperiodo.value+"*";
	
	//alert(filtro1);
	var pagina="rp_balancecomprobacionmensualpdf.php?filtro1="+filtro1+"&periodobusqueda="+periodobusqueda;
			cargarPagina(form, pagina);
}
/// --------------------------------------------------------------------------------- */
function rp_balancecomprobacion(form,tab){
   var filtro1="";
	/*if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkActivo.checked) filtro+=" and Activo=*"+form.fActivo.value+"*";
	if(form.checkDependencia.checked) filtro+=" and Dependencia=*"+form.fDependencia.value+"*";
	if(form.chkFAprobacion.checked){ 
	   filtro+=" and FechaAprobacion>=*"+form.fFechaAprobacionDesde.value+"*";
	   filtro+=" and FechaAprobacion<=*"+form.fFechaAprobacionHasta.value+"*";
	}
	if(form.chekcCosto.checked) filtro+=" and CentroCosto=*"+form.centro_costos.value+"*";
	if(form.chkFPreparacion.checked){ 
	   filtro+=" and FechaPreparacion>=*"+form.fFechaPreparacionDesde.value+"*";
	   filtro+=" and FechaPreparacion<=*"+form.fFechaPreparacionHasta.value+"*";
	}
	if(form.chkubicacionActual.checked) filtro+=" and Ubicacion=*"+form.fub_actual.value+"*";
	if(form.chkubicacionAnterior.checked) filtro+=" and UbicacionAnterior=*"+form.fub_anterior.value+"*";*/
	
	var form = document.getElementById("frmentrada");
	if (tab == "saldo_anterior_acumulado") form.action = "rp_balancesaldoanterioracumuladopdf.php";
	else if (tab == "debe_haber") form.action = "rp_balancedebehaberpdf.php";
	else if (tab == "debe_haber_acumulado") form.action = "rp_balancedebehaberacumuladopdf.php";
	else if (tab == "Acumulado") form.action = "rp_balanceacumuladopdf.php";
	form.submit();
}
/// --------------------------------------------------------------------------------- */
/// --------------------------------------------------------------------------------- */
/// --------------------------------------------------------------------------------- */
/// --------------------------------------------------------------------------------- */
/// --------------------------------------------------------------------------------- */
