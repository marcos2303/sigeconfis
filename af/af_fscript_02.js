// JavaScript Document
//// ---------------------------------------------------------------------------------------------
//// 			FUNCION QUE PERMITE ACTIVAR RADIOS
//// ---------------------------------------------------------------------------------------------
function chekeador(form,id){ 
  if(id=="radio2"){ 
     form.radio1.checked=false;
	 document.getElementById("radio").value = "I"; 
  }
  if(id=="radio1"){ 
    form.radio2.checked=false;
	document.getElementById("radio").value = "A"; 
  }
}
//// ---------------------------------------------------------------------------------------------
//// 	FUNCION QUE PERMITE ACTIVAR CAMPOS DE AF_ACTIVOSMENORES
//// ---------------------------------------------------------------------------------------------
function enabledUbicacionActivosMenores(form){
 if(form.checkUbicacion.checked){ 
    form.fubicacion2.disabled=false; form.btUbicacion.disabled = false; document.getElementById('ubicacionactivo').style.visibility='visible';
}else{ 
    form.fubicacion2.disabled=true; form.fubicacion2.value=''; form.btUbicacion.disabled = true; form.fubicacion.value=''; 
	document.getElementById('ubicacionactivo').style.visibility='hidden';}
}
//// FUNCION QUE PERMITE ACTIVAR Y DESCTIVAR CAMPOS EN RP INVENTARIO A LA FECHA
function enabledRpAFecha(form){
 if(form.chkfecha.checked){
    form.fecha_desde.disabled=false; form.fecha_hasta.disabled=false;
 }else{
   form.fecha_desde.disabled=true; form.fecha_hasta.disabled=true; form.fecha_desde.value=''; form.fecha_hasta.value='';
 }
}
//// ---------------------------------------------------------------------------------------------
//// ----------		FUNCION QUE PERMITE GUARDAR REGISTRO TRANSACCION BAJA
//// ---------------------------------------------------------------------------------------------
function guardarTransaccionBaja(form, Modulo){ 
   //alert(Modulo);

  var Activo= document.getElementById("nro_activo").value;
  var Organismo = document.getElementById("codorganismo").value;
  if(Modulo!='Nuevo') var CodTransaccionBaja = document.getElementById("codtransaccionbaja").value;

if(Modulo!='Anular'){
  var TipoTransaccion = document.getElementById("tipobaja").value;
  var Dependencia = document.getElementById("coddependencia").value;
  var Fecha = document.getElementById("f_actual").value;
  var FechaBaja = document.getElementById("f_baja").value;
  var CentroCosto = document.getElementById("codcentrocosto").value;
  var Responsable = document.getElementById("codresponsable").value;
  var ConceptoMovimiento = document.getElementById("conceptoMovimiento").value;
  var CodigoInterno = document.getElementById("codigo_interno").value;
  var Categoria = document.getElementById("codcategoria").value;
  var Ubicacion = document.getElementById("codubicacion").value;
  var Comentario = document.getElementById("comentario").value;
  var MontoLocal = setNumero(document.getElementById("monto_local").value); 
  var Resolucion = document.getElementById("nro_documento").value; 
  var FacturaNumero = document.getElementById("nrofactura").value;
  //var FechaIngreso = document.getElementById("FechaIngreso").value;
 // var NumeroOrden = document.getElementById("NumeroOrden").value;
  //var CodTransaccionBaja = document.getElementById("CodTransaccionBaja").value;
  
  var MotivoTraslado = document.getElementById("motivoTrasladoExterno").value;
  if(Modulo=='Nuevo')var PreparadoPor = document.getElementById("prepor").value;
  var regresar = document.getElementById("regresar").value; //alert('regresa=='+regresar)
  
  
  if(form.radio2.checked) var FlagExterno='S';
  if(form.flagContabilizado.checked) var ContabilizadoFlag = 'S'; else var ContabilizadoFlag = 'N';
}
  if(Modulo!='Nuevo') var Usuario = document.getElementById("usuario_actual").value;
  if(Modulo=='Anular'){
	  var motivo_anular = document.getElementById("motivo_anular").value;
	  var estado = document.getElementById("estado").value;
  }
   
  //alert("accion=guardarTransaccionBaja&Activo="+Activo+"&Organismo="+Organismo+"&Dependencia="+Dependencia+"&TipoTransaccion="+TipoTransaccion+"&Fecha="+Fecha+"&CentroCosto="+CentroCosto+"&Responsable="+Responsable+"&ConceptoMovimiento="+ConceptoMovimiento+"&CodigoInterno="+CodigoInterno+"&Categoria="+Categoria+"&Ubicacion="+Ubicacion+"&Comentario="+Comentario+"&MontoLocal="+MontoLocal+"&Resolucion="+Resolucion+"&FacturaNumero="+FacturaNumero+"&ContabilizadoFlag="+ContabilizadoFlag+"&Modulo="+Modulo+"&CodTransaccionBaja="+CodTransaccionBaja+"&FlagExterno="+FlagExterno+"&MotivoTraslado="+MotivoTraslado+"&PreparadoPor="+PreparadoPor+"&Usuario="+Usuario+"&motivo_anular="+motivo_anular+"&estado="+estado+"&FechaIngreso="+FechaIngreso+"&FechaBaja="+FechaBaja+"&NumeroOrden="+NumeroOrden+"&CodTransaccionBaja="+CodTransaccionBaja+"&OrdenSecuencia="+OrdenSecuencia);

 var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=guardarTransaccionBaja&Activo="+Activo+"&Organismo="+Organismo+"&Dependencia="+Dependencia+"&TipoTransaccion="+TipoTransaccion+"&Fecha="+Fecha+"&CentroCosto="+CentroCosto+"&Responsable="+Responsable+"&ConceptoMovimiento="+ConceptoMovimiento+"&CodigoInterno="+CodigoInterno+"&Categoria="+Categoria+"&Ubicacion="+Ubicacion+"&Comentario="+Comentario+"&MontoLocal="+MontoLocal+"&Resolucion="+Resolucion+"&FacturaNumero="+FacturaNumero+"&ContabilizadoFlag="+ContabilizadoFlag+"&Modulo="+Modulo+"&CodTransaccionBaja="+CodTransaccionBaja+"&FlagExterno="+FlagExterno+"&MotivoTraslado="+MotivoTraslado+"&PreparadoPor="+PreparadoPor+"&Usuario="+Usuario+"&motivo_anular="+motivo_anular+"&estado="+estado+"&FechaBaja="+FechaBaja);
  
  ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			if (resp != "")alert(resp); 
            else if((Modulo=='Nuevo')||(Modulo=='Modificar'))cargarPagina(form, document.getElementById("regresar").value+".php?limit=0");
				 else window.close();
		}
	}
	return false;
}
//// ---------------------------------------------------------------------------------------------
//// ----------		MOSTRAR REPORTE INVENTARIO X DEPENDENCIA
//// ---------------------------------------------------------------------------------------------
function cargarInventarioxDependencia(form){
 
	var filtro="";
	if(form.chkorganismo.checked) filtro+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkCatClasf.checked) filtro+=" and a.Clasificacion LIKE *"+form.fClasificacion.value+"%"+"*";
	if(form.checkUbicacion.checked) filtro+=" and a.Ubicacion LIKE *"+form.fubicacion.value+"%"+"*";
	if(form.checkDependencia.checked) filtro+=" and a.CodDependencia=*"+form.fDependencia.value+"*";
	if(form.chkclasificacionpub20.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fCodclasficacionPub20.value+"%"+"*";
	if(form.checkSituacionActivo.checked) filtro+=" and a.SituacionActivo=*"+form.fSituacionActivo.value+"*";
	if(form.chekcCosto.checked) filtro+=" and a.CentroCosto=*"+form.centro_costos.value+"*";
	if(form.chkEstado.checked) filtro+=" and a.Estado=*"+form.fEstado.value+"*";
	if(form.chkBienes.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fBienes.value+"%"+"*";
	
    var pagina_mostrar="af_rpinventarioxdependenciapdf.php?filtro="+filtro;
        form.target = "af_rpinventarioxdependenciapdf";				
				cargarPagina(form, pagina_mostrar);
}
//// ---------------------------------------------------------------------------------------------
//// ----------		MOSTRAR REPORTE INVENTARIO ACTIVOS COSTO
//// ---------------------------------------------------------------------------------------------
function cargarInventarioActivosLista(form){

	var filtro="";
	if(form.chkorganismo.checked) filtro+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkCatClasf.checked) filtro+=" and a.Clasificacion LIKE *"+form.fClasificacion.value+"%"+"*";
	if(form.checkUbicacion.checked) filtro+=" and a.Ubicacion LIKE *"+form.ubicacion.value+"%"+"*";
	if(form.checkDependencia.checked) filtro+=" and a.CodDependencia=*"+form.fDependencia.value+"*";
	if(form.chkclasificacionpub20.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fCodclasficacionPub20.value+"%"+"*";
	if(form.checkSituacionActivo.checked) filtro+=" and a.SituacionActivo=*"+form.fSituacionActivo.value+"*";
	if(form.chekcCosto.checked) filtro+=" and a.CentroCosto=*"+form.centro_costos.value+"*";
	if(form.chkEstado.checked) filtro+=" and a.Estado=*"+form.fEstado.value+"*";
	if(form.chkBienes.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fBienes.value+"%"+"*";
	
    var pagina_mostrar="af_rpinventarioactivoscostopdf.php?filtro="+filtro;
        form.target = "af_rpinventarioactivoscostopdf";				
				cargarPagina(form, pagina_mostrar);
}
//// ---------------------------------------------------------------------------------------------
//// ----------		MOSTRAR REPORTE INVENTARIO ACTIVOS COSTO GENERAL
//// ---------------------------------------------------------------------------------------------
function cargarInventarioActivosListaGen(form){

	var filtro="";
	if(form.chkorganismo.checked) filtro+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkCatClasf.checked) filtro+=" and a.Clasificacion LIKE *"+form.fClasificacion.value+"%"+"*";
	if(form.checkUbicacion.checked) filtro+=" and a.Ubicacion LIKE *"+form.ubicacion.value+"%"+"*";
	if(form.checkDependencia.checked) filtro+=" and a.CodDependencia=*"+form.fDependencia.value+"*";
	if(form.chkclasificacionpub20.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fCodclasficacionPub20.value+"%"+"*";
	if(form.checkSituacionActivo.checked) filtro+=" and a.SituacionActivo=*"+form.fSituacionActivo.value+"*";
	if(form.chekcCosto.checked) filtro+=" and a.CentroCosto=*"+form.centro_costos.value+"*";
	if(form.chkEstado.checked) filtro+=" and a.Estado=*"+form.fEstado.value+"*";
	if(form.chkBienes.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fBienes.value+"%"+"*";
	
    var pagina_mostrar="af_rpinventarioactivoscostogenpdf.php?filtro="+filtro;
        form.target = "af_rpinventarioactivoscostogenpdf";				
				cargarPagina(form, pagina_mostrar);
}

//// ---------------------------------------------------------------------------------------------
//// ----------		MOSTRAR REPORTE INVENTARIO ACTIVOS COSTO
//// ---------------------------------------------------------------------------------------------
function cargarFormularioBM_1(form){

	var filtro="";
	if(form.chkorganismo.checked) filtro+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkCatClasf.checked) filtro+=" and a.Clasificacion LIKE *"+form.fClasificacion.value+"%"+"*";
	if(form.checkUbicacion.checked) filtro+=" and a.Ubicacion LIKE *"+form.fubicacion.value+"%"+"*";
	if(form.checkDependencia.checked) filtro+=" and a.CodDependencia=*"+form.fDependencia.value+"*";
	if(form.chkclasificacionpub20.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fClasificacionPub20.value+"%"+"*";
	if(form.checkSituacionActivo.checked) filtro+=" and a.SituacionActivo=*"+form.fSituacionActivo.value+"*";
	if(form.chekcCosto.checked) filtro+=" and a.CentroCosto=*"+form.centro_costos.value+"*";
	if(form.chkEstado.checked) filtro+=" and a.Estado=*"+form.fEstado.value+"*";
	if(form.chkBienes.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fBienes.value+"%"+"*";
	
    var pagina_mostrar="af_rpformulariobm_1pdf.php?filtro="+filtro;
        form.target = "af_rpformulariobm_1pdf";				
				cargarPagina(form, pagina_mostrar);
}
function cargarFormularioBM_2(form){

	var filtro="";
	if(form.chkorganismo.checked) filtro+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkCatClasf.checked) filtro+=" and a.Clasificacion LIKE *"+form.fClasificacion.value+"%"+"*";
	if(form.checkUbicacion.checked) filtro+=" and a.Ubicacion LIKE *"+form.fubicacion.value+"%"+"*";
	if(form.checkDependencia.checked) filtro+=" and a.CodDependencia=*"+form.fDependencia.value+"*";
	if(form.chkclasificacionpub20.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fCodclasficacionPub20.value+"%"+"*";
	if(form.checkSituacionActivo.checked) filtro+=" and a.SituacionActivo=*"+form.fSituacionActivo.value+"*";
	if(form.chekcCosto.checked) filtro+=" and a.CentroCosto=*"+form.centro_costos.value+"*";
	if(form.chkEstado.checked) filtro+=" and a.Estado=*"+form.fEstado.value+"*";
	if(form.chkBienes.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fBienes.value+"%"+"*";
	
    var pagina_mostrar="af_rpformulariobm_2pdf.php?filtro="+filtro;
        form.target = "af_rpformulariobm_2pdf";				
				cargarPagina(form, pagina_mostrar);
}

//// ---------------------------------------------------------------------------------------------
//// ----------		MOSTRAR REPORTE INVENTARIO A LA FECHA
//// ---------------------------------------------------------------------------------------------
function cargarInventarioAFecha(form){
 
	var filtro="";
	if(form.chkorganismo.checked) filtro+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkCatClasf.checked) filtro+=" and a.Clasificacion LIKE *"+form.fClasificacion.value+"%"+"*";
	if(form.checkUbicacion.checked) filtro+=" and a.Ubicacion LIKE *"+form.fubicacion.value+"%"+"*";
	if(form.checkDependencia.checked) filtro+=" and a.CodDependencia=*"+form.fDependencia.value+"*"; else var dep="1";
	if(form.chkclasificacionpub20.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fCodclasficacionPub20.value+"%"+"*";
	if(form.checkSituacionActivo.checked) filtro+=" and a.SituacionActivo=*"+form.fSituacionActivo.value+"*";
	if(form.chekcCosto.checked) filtro+=" and a.CentroCosto=*"+form.centro_costos.value+"*";
	if(form.chkEstado.checked) filtro+=" and a.Estado=*"+form.fEstado.value+"*";
	if(form.chkBienes.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fBienes.value+"%"+"*";
	
	
	if(form.chkfecha.checked) filtro+=" and a.FechaIngreso=*"+form.fBienes.value+"%"+"*";
	
    var pagina_mostrar="af_rpinventarioalafechapdf.php?filtro="+filtro+"&dep="+dep;
        form.target = "af_rpinventarioalafechapdf";				
				cargarPagina(form, pagina_mostrar);
}
//// ---------------------------------------------------------------------------------------------
//// ----------		MOSTRAR REPORTE INVENTARIO A LA FECHA
//// ---------------------------------------------------------------------------------------------
function cargarInventarioAFGeneral(form){
 
	var filtro="";
	if(form.chkorganismo.checked) filtro+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkCatClasf.checked) filtro+=" and a.Clasificacion LIKE *"+form.fClasificacion.value+"%"+"*";
	if(form.checkUbicacion.checked) filtro+=" and a.Ubicacion LIKE *"+form.fubicacion.value+"%"+"*";
	if(form.checkDependencia.checked) filtro+=" and a.CodDependencia=*"+form.fDependencia.value+"*"; else var dep="1";
	if(form.chkclasificacionpub20.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fCodclasficacionPub20.value+"%"+"*";
	if(form.checkSituacionActivo.checked) filtro+=" and a.SituacionActivo=*"+form.fSituacionActivo.value+"*";
	if(form.chekcCosto.checked) filtro+=" and a.CentroCosto=*"+form.centro_costos.value+"*";
	if(form.chkEstado.checked) filtro+=" and a.Estado=*"+form.fEstado.value+"*";
	if(form.chkBienes.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fBienes.value+"%"+"*";
	
	
	if(form.chkfecha.checked) filtro+=" and a.FechaIngreso=*"+form.fBienes.value+"%"+"*";
	
    var pagina_mostrar="af_rpinventariogeneralpdf.php?filtro="+filtro+"&dep="+dep;
        form.target = "af_rpinventariogeneralpdf";				
				cargarPagina(form, pagina_mostrar);
}
//// ---------------------------------------------------------------
///           
//// ---------------------------------------------------------------
/*function Distribucion(form,id){
	var distribucion = document.getElementById(id).value;
	    document.getElementById("distribucion").value= distribucion;
	if(distribucion!=""){
		//document.getElementById("mostrar").style.visibility= "visible";
		var visible = 'style="visibility:visible"';
		var tipobaja = document.getElementById("tipobaja").value;
		cargarPagina(form,"af_bajactivosnuevo.php?distribucion="+distribucion+"&visible="+visible+"&tipobaja="+tipobaja);
		
	}else document.getElementById("mostrar").style.visibility= "hidden";	
		
}*/
//// ---------------------------------------------------------------------------------------------
//// ----------		ACTIVAR TABLA DE FORMULARIO
//// ---------------------------------------------------------------------------------------------
function ActivarTable(form,valor){
	var valor = document.getElementById(valor).value; //alert(valor);
if(valor!=""){
  document.getElementById("mostrar").style.visibility = 'visible';
  document.getElementById("scrool").style.display = 'block';
}else{ 
   document.getElementById("mostrar").style.visibility = 'hidden'; 
   document.getElementById("scrool").style.display = 'none';
}
}
//// --------------------------------------------------------------------------------------------
function formatoMoneda(fld, milSep,decSep, e){
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
   // alert(whichCode);
    //if (whichCode == 13) return true; // Enter 
    
    //key = String.fromCharCode(whichCode); // Get key value from key code
    //alert(whichCode);
    
    if(whichCode!=8) //PARA QUE PERMITA ACEPTAR LA TECHA <- (BORRAR)
    {
    	key = String.fromCharCode(whichCode); // Get key value from key code
    	//alert(strCheck.indexOf(key));
    	if (strCheck.indexOf(key) == -1) return false; // Not a valid key
    	len = fld.value.length;    	
   		// alert(len);
    }
    
    else len = fld.value.length-1; //PARA QUE PERMITA BORRAR
   // alert(len);
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != 44)) break; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key;
    len = aux.length;
    if (len == 0) fld.value = '0,00'; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) 
    { 
	     aux2 = ''; 
	     for (j = 0, i = len - 3; i >= 0; i--) 
	     { 
		      if (j == 3) 
		      { 
			       aux2 += milSep; 
			       j = 0; 
		      } 
		      aux2 += aux.charAt(i); 
		      j++; 
	     } 
	     fld.value = ''; 
	     len2 = aux2.length; 
	     for (i = len2 - 1; i >= 0; i--) 
	      	fld.value += aux2.charAt(i); 
	     fld.value += decSep + aux.substr(len - 2, len);
    } //decSep +
    return false;
}
//// ---------------------------------------------------------------------------------------------
//// ----------								ANULAR REGISTROS 
//// ---------------------------------------------------------------------------------------------
function anularReg(form, pagina, accion){
 var codigo=form.registro.value;
 var Estado = document.getElementById("estado").value;
 
	if (codigo=="") msjError(1000);
	else if(Estado=='AP')alert("¡Este registro no puede ser anulado por estar en estado AProbado!");
	else{
	   var anular=confirm("¡Esta seguro de anular este registro!");
	     if(anular){
			 cargarOpcion(form, pagina+"&Estado="+Estado,'BLANK', 'height=300, width=850, left=250, top=50, resizable=no');
	    
		 }
	}
}

function anularRegist(form, pagina, accion){
 var codigo=form.registro.value;
	if (codigo=="") msjError(1000);
	else{
	   var anular=confirm("¡Esta seguro de anular este registro!");
	     if(anular){
	       var ajax=nuevoAjax();
			ajax.open("POST", "gmactivofijo.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("accion=accion&codigo="+codigo);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var error=ajax.responseText;
					if (error!=0) alert ("¡"+error+"!");
					else cargarPagina(form, pagina+"&limit="+limit);
				}
			}
	    
		 }
	}
}
//// ---------------------------------------------------------------------------------------------
//// ----------								 
//// ---------------------------------------------------------------------------------------------
function muestra_detalle(form, valor){
  //alert(form);
  var cont = document.getElementById("cont").value; //alert(cont);
  
  var ajax=nuevoAjax();
			ajax.open("POST", "gmactivofijo.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("accion=tab2"+"&valor="+valor+"&cont="+cont);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					//alert(ajax.responseText);
					 document.getElementById("tab2_cargar").innerHTML=ajax.responseText;
				}
			}
}
