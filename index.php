<?php
session_start();
session_name("SIGECONFIS");
session_destroy();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>SISTEMA DE GESTIÓN FISCAL</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link href="imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />
<link rel="stylesheet" href="css/estilo.css" type="text/css" />
<link rel="stylesheet" href="imagenes/mm_restaurant1.css" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>
<body bgcolor="#630000">

<div id="bloqueo" style="height:99%; width:99%; position:absolute; display:none;"></div>
<div id="validar" style="display:none; position:absolute; left:15%; top:60%;">
<form name="frmentrada" id="frmentrada" onsubmit="return validacionUsuario();">
<input type="hidden" name="modulo" id="modulo" />
<div style="width:600px; background-color: #630000;"></div>

<table class="" style="background-color: #fff;">
	<tr>
		<td><img src='imagenes/locked.png' width="100"></img></td>
		<td>
			<table width="800" class="" border="0">
				<tr>
					<td class="tagForm" style="background-color: #ccc;">Usuario:</td>
					<td><input name="usuario" type="text" id="usuario" size="30" maxlength="20" onchange="cargarOrganismos();" /></td>
				</tr>
				<tr>
					<td class="tagForm" style="background-color: #ccc;">Contrase&ntilde;a:</td>
					<td><input name="clave" type="password" id="clave" size="30" maxlength="20" onfocus="cargarOrganismos();" /></td>
				</tr>
				<tr>
					<td class="tagForm" style="background-color: #ccc;">Organismo:</td>
					<td>
						<select name="organismo" id="organismo" style="width:250px;">
							<option value=""></option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="submit" value="Aceptar" onclick="cargarOrganismos();" />
						<input type="button" value="Cancelar" onclick="location.href='index.php'" />
					</td>
				</tr>
			</table>			
		</td>
	</tr>	
</table>

</form>
</div>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr bgcolor="#630000">
        <td width="10%" nowrap="nowrap"></td>
        <td width="80%" height="" colspan="2" class="logo" nowrap="nowrap">
			<img src="imagenes/header-gif.gif" width="100%" height="100"></img>
        <!--Sistema Integral Administrativo <span class="tagline">Contraloría Metropolitana de Caracas</span>--></td>
        <td width="10%"></td>
	</tr>

	<tr bgcolor="#444444">
        <td width="15" nowrap="nowrap">&nbsp;</td>
        <td height="36" colspan="2" id="navigation" nowrap="nowrap" class="navText"><span class="tagline">Contraloría Metropolitana de Caracas</span></td>
        <td></td>
	</tr>

	<tr bgcolor="#ffffff">
	<td width="15" valign="top"><img src="imagenes/mm_spacer.gif" alt="" width="15" height="1" border="0" /></td>
	<td width="35" valign="top"><img src="imagenes/mm_spacer.gif" alt="" width="35" height="1" border="0" /></td>
	<td width="710" valign="top"><br />
	<table border="0" cellspacing="0" cellpadding="2" width="700">
        <tr>
			<td colspan="7" class=""><h1>Módulos del Sistema</h1></td>
        </tr>
	<!--<tr>
         <td width="22%" height="110"><a onclick="abrirModulo('rh');" href="javascript:;"><img src="imagenes/menu_rrhh.jpg" alt="Entrar a Recursos Humanos" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td>&nbsp;</td>
		  <td width="22%" height="110"><a onclick="abrirModulo('nomina');" href="javascript:;"><img src="imagenes/foto_rrhh.jpg" alt="Entrar a Nómina" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td>&nbsp;</td>
		  <td width="22%" height="110"><a onclick="abrirModulo('lg');" href="javascript:;"><img src="imagenes/menu_lg.jpg" alt="Entrar a Logística" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td>&nbsp;</td>
		  <td width="22%" height="110"><a onclick="abrirModulo('pv');" href="javascript:;"><img src="imagenes/menu_pv.png" alt="Entrar a Presupuesto" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td>&nbsp;</td>
		  <td width="22%" height="110">&nbsp;</td>
        </tr>
		<tr>
          <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('rh');" href="javascript:;">Recursos Humanos</a></td>
		  <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('nomina');" href="javascript:;">N&oacute;mina</a><br />
		  </td>
		 <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('lg');" href="javascript:;">Log&iacute;stica</a><br />
		  </td>
		 <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('pv');" href="javascript:;">Presupuesto</a><br />
		  </td>
		 <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap">&nbsp;<br />
		  </td>
        </tr>-->
		<tr>
			<td colspan="7">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="6"><a onclick="abrirModulo('pf');" href="javascript:;"><img src="imagenes/auditoria_cmc.png" alt="Entrar a Planificaci&oacute;n Fiscal" width="110" height="110" style="" /></a></td>
			<td height="110"><a onclick="abrirModulo('cp');" href="javascript:;"><img src="imagenes/archivos.png" alt="Entrar a Control de Documentos" width="110" height="110" style="" /></a></td>
		</tr>
		<tr>
			<td colspan="6" valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('pf');" href="javascript:;">Planificaci&oacute;n Fiscal</a><br /></td>
		
			<td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('cp');" href="javascript:;">Control de Documentos</a><br /></td>
		
		</tr>
	<!--<tr>
         
                    
                <td height="110"><a onclick="abrirModulo('ap');" href="javascript:;"><img src="imagenes/menu_ap.png" alt="Entrar a Cuentas por Pagar" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td>&nbsp;</td>
		  <td height="110"><a onclick="abrirModulo('ac');" href="javascript:;"><img src="imagenes/menu_ac.png" alt="Entrar a Contabilidad" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td>&nbsp;</td>
		  <td height="110"><a onclick="abrirModulo('af');" href="javascript:;"><img src="imagenes/menu_af.png" alt="Entrar a Activos Fijos" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td>&nbsp;</td>
		  <td height="110"><a onclick="abrirModulo('pf');" href="javascript:;"><img src="imagenes/menu_pf.jpg" alt="Entrar a Planificaci&oacute;n Fiscal" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td>&nbsp;</td>
		  <td height="110"><a onclick="abrirModulo('cp');" href="javascript:;"><img src="imagenes/menu_doc.jpg" alt="Entrar a Control de Documentos" width="110" height="110" style="border-color:#999999" /></a></td>
        </tr>
        <tr>
          <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('ap');" href="javascript:;">Cuentas por Pagar</a><br />
		  </td>
		  <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('ac');" href="javascript:;">Contabilidad</a><br />
		  </td>
		 <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('af');" href="javascript:;">Activos Fijos</a><br />
		  </td>
		 <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('pf');" href="javascript:;">Planificaci&oacute;n Fiscal</a><br />
		  </td>
		 <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('cp');" href="javascript:;">Control de Documentos</a><br />
		  </td>
        </tr>
		<tr>
			<td colspan="7">&nbsp;</td>
		</tr>-->
      </table>	</td>
	<td>&nbsp;</td>
	</tr>

	<tr>
	<td width="15">&nbsp;</td>
    <td width="35">&nbsp;</td>
    <td width="710">&nbsp;</td>
	<td width="100%">&nbsp;</td>
  </tr>
</table>
</body>
</html>
