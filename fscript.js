﻿// CREO ESTE METODO PARA PODER ELIMINAR LOS ESPACIOS EN BLANCOS AL PRINCIPIO Y AL FINAL DE CADA CAMPO 
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g,'') }

//	FUNCION QUE ME PERMITE CREAR UN NUEVO OBJETO AJAX
function nuevoAjax() { 
	/* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo, por	lo que se puede copiar tal como esta aqui */
	var xmlhttp=false; 
	try 
	{ 
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 
	}
	catch(e)
	{ 
		try
		{
			// Creacion del objeto AJAX para IE 
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
		} 
		catch(E) { xmlhttp=false; }
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp=new XMLHttpRequest(); } 
	return xmlhttp;
}

function validacionUsuario() {
	
	cargarOrganismos();
	var usuario=document.getElementById("usuario").value; usuario=usuario.trim();
	var clave=document.getElementById("clave").value; clave=clave.trim();
	var organismo=document.getElementById("organismo").value; organismo=organismo.trim();
	var modulo=document.getElementById("modulo").value; modulo=modulo.trim();
	if (organismo=="") alert("¡DEBE SELECCIONAR EL ORGANISMO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=VALIDAR&usuario="+usuario+"&clave="+clave+"&organismo="+organismo+"&modulo="+modulo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				if (resp=="ERROR") { alert ("¡NOMBRE DE USUARIO O CONTRASEÑA INCORRECTA!"); }
				else if (resp=="INACTIVO") { alert ("¡USUARIO INACTIVO!"); }
				else {
					
					//alert(modulo + "/index.php");
					location.href= modulo+ "/index.php";
					
					//window.open(modulo, 'modulo'+modulo, 'toolbar=yes, menubar=yes, location=no, scrollbars=yes, height=1024, width=1280, left=0, top=0, resizable=yes') 
				}
			}
		}
	}
	return false;
}

function cargarOrganismos() {
	var usuario=document.getElementById("usuario").value; usuario=usuario.trim();
	var modulo=document.getElementById("modulo").value; modulo=modulo.trim();
	var selectDestino=document.getElementById("organismo");
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=ORGANISMOS&usuario="+usuario+"&modulo="+modulo);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==1) {
			// Mientras carga elimino la opcion "" y pongo una que dice "Cargando..."
			selectDestino.length=0;
			var nuevaOpcion=document.createElement("option");
			nuevaOpcion.value="";
			nuevaOpcion.innerHTML="Cargando...";
			selectDestino.appendChild(nuevaOpcion);
			selectDestino.disabled=true;	
		}
		if (ajax.readyState==4)	{
			
			selectDestino.parentNode.innerHTML=ajax.responseText;
		}
	}
}

function abrirModulo(modulo) {
	document.getElementById('modulo').value=modulo; 
	document.getElementById('bloqueo').style.display='block'; 
	document.getElementById('validar').style.display='block'; 
	document.getElementById('usuario').focus();	
}

function limpiarOrganismo() {
	var organismo=document.getElementById("organismo");
	organismo.length=0;
	var nuevaOpcion=document.createElement("option");
	nuevaOpcion.value="";
	nuevaOpcion.innerHTML="";
	select.appendChild(nuevaOpcion);
}
