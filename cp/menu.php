<?php
include($_SERVER['DOCUMENT_ROOT']."/sigeconfis/lib/fphp.php");
?>
<link rel="stylesheet" href="<?=$_PARAMETRO["PATHSIA"]?>js/jquery-ui-1.11.4.custom/jquery-ui.css">
<link rel="stylesheet" href="<?=$_PARAMETRO["PATHSIA"]?>js/jquery-ui-1.11.4.custom/jquery-ui.theme.css">
<script src="<?=$_PARAMETRO["PATHSIA"]?>js/jquery-ui-1.11.4.custom/external/jquery/jquery.js"></script>
<script src="<?=$_PARAMETRO["PATHSIA"]?>js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
	<table width="100%" border="0">
		<tr>
			<td>
				<ul class="menu">

					<li><span></span>Correspondencia
						<ul>
						<li>Entrada de Documentos
							<ul>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpe_entradaextnuevo.php?limit=0&regresar=framemain&concepto=01-0001"><span class="ui-icon ui-icon-document"></span>Nuevo Documento</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpe_entrada.php?limit=0&concepto=01-0002"><span class="ui-icon ui-icon-clipboard"></span>Lista Documento</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpe_atenderext.php?limit=0&concepto=01-0003"><span class="ui-icon ui-icon-lightbulb"></span>Atender Documento</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpe_distribucionext.php?limit=0&concepto=01-0004"><span class="ui-icon ui-icon-check"></span>Listar Distribución</a></li>
							</ul>							
						</li>
						<li><span></span>Salida de Documentos
							<ul>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpe_salidanuevo.php?limit=0&regresar=framemain&concepto=01-0005"><span class="ui-icon ui-icon-document"></span>Nuevo Documento</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpe_salidalista.php?limit=0&concepto=01-0006"><span class="ui-icon ui-icon-clipboard"></span>Lista Documento</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpe_salidapreparar.php?limit=0&concepto=01-0007"><span class="ui-icon ui-icon-lightbulb"></span>Preparar Documento</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpe_salidaenvio.php?limit=0&concepto=01-0008"><span class="ui-icon ui-icon-lightbulb"></span>Envío Documento</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpe_salidadist.php?limit=0&concepto=01-0009"><span class="ui-icon ui-icon-check"></span>Listar Distribución</a></li>							
							</ul>
						</li>
						<li><span></span>Documentos Internos
							<ul>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpi_docinternonuevos.php?limit=0&regresar=framemain&concepto=01-0010"><span class="ui-icon ui-icon-document"></span>Nuevo Documento</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpi_docinternoslista.php?limit=0&concepto=01-0011"><span class="ui-icon ui-icon-clipboard"></span>Lista Documento</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpi_docinternosprep.php?limit=0&concepto=01-0012"><span class="ui-icon ui-icon-lightbulb"></span>Preparar Documento</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpi_docinternosenvio.php?limit=0&concepto=01-0013"><span class="ui-icon ui-icon-lightbulb"></span>Envío Documento</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpi_docinternosdist.php?limit=0&concepto=01-0014"><span class="ui-icon ui-icon-check"></span>Listar Distribución</a></li>
							</ul>
						</li>
						<li><span></span>Dependencia
							<ul>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpi_depenrecibido.php?limit=0&concepto=01-0015"><span class="ui-icon ui-icon-document"></span>Documentos Recibidos</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpi_depenenviado.php?limit=0&concepto=01-0016"><span class="ui-icon ui-icon-clipboard"></span>Documentos Enviados</a></li>
							</ul>
						</li>
						</ul>
					</li>
					<li>Reportes
						<ul>
							<li>Entrada de Documentos
								<ul>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/rp_entradadocumentoslista.php?limit=0&concepto=06-0001">Lista Documentos</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/rp_entradadistdocumento.php?limit=0&concepto=06-0002">Distribución por Documento</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/rp_entradadistdetalle.php?limit=0&concepto=06-0003">Distribución</a></li>
								</ul>
							</li>
							<li>Salida de Documentos
								<ul>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/rp_documentosexternoslista.php?limit=0&concepto=06-0004">Lista Documentos</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/rp_documentosexternosdistsalida.php?limit=0&concepto=06-0005">Distribución por Documentos</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/rp_docsalidadistribucion.php?limit=0&concepto=06-0006">Distribución</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/rp_docsalidahistxdoc.php?limit=0&concepto=06-0007">Histórico por Documento</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/rp_docsalidamensajero.php?limit=0&concepto=06-0011">Distribución por Mensajero</a></li>
								</ul>
							</li>
							<li>Documentos Internos
								<ul>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/rp_documentosinternoslista.php?limit=0&concepto=06-0008">Lista Documentos</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/rp_documentosinternosdistxdoc.php?limit=0&concepto=06-0009">Distribución por Documento</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/rp_documentosinternosdistribucion.php?limit=0&concepto=06-0010">Distribución</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<li>Maestros
						<ul>
							<li>Del Sistema SIA
								<ul>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/personas.php?limit=0&concepto=04-0007">Personas</a></li>
									<li>Propios del Sistema
										<ul>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/aplicaciones.php?limit=0&concepto=04-0001">Aplicaciones</a></li>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/parametros.php?limit=0&concepto=04-0002">Parámetros</a></li>
										</ul>
									</li>
									<li>Entes Externos
										<ul>

											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_organismos_externos_lista&filtrar=default&concepto=04-0003">Organismos</a></li>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_dependencias_externas_lista&filtrar=default&concepto=04-0004">Dependencias</a></li>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cp_tipocorrespondencia.php?limit=0&concepto=04-0005">Tipo Correspondencia</a></li>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/cpe_particular.php?limit=0&concepto=04-0006">Particular</a></li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
					</li>
					<li>Administración
						<ul>
							<li>Seguridad
								<ul>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/usuarios.php?limit=0&concepto=05-0001">Maestro de Usuarios</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/usuarios_autorizaciones.php?limit=0&concepto=05-0002">Dar Autorizaciones a Usuarios</a></li>
								</ul>
							</li>
							<li>Seguridad Alterna
								<ul>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>cp/seguridad_alterna.php?limit=0&concepto=05-0003">Dar Autorizaciones a Usuarios</a></li>
								</ul>
							</li>
						</ul>
					</li>
				</ul>
			</td>
			<td>
				<?php if(isset($_SESSION['ORGANISMO_ACTUAL'])):?>
				<label class="cerrar"><strong>Organismo: </strong><?php echo $_SESSION['ORGANISMO_ACTUAL'];?></label>
				<?php endif;?>
				<?php if(isset($_SESSION['USUARIO_ACTUAL'])):?>
				<label class="cerrar"><strong>Usuario:</strong> <?php echo $_SESSION['USUARIO_ACTUAL'];?></label>
						
				<?php endif;?>
				
			</td>
			<td align="right" width="200">
				<a href="<?=$_PARAMETRO["PATHSIA"]?>index.php" class="cerrar">[Cerrar sesión]</a>
			</td>
		</tr>
	</table>
<script>
	$(function() {
		$( ".menu" ).menu({
			blur: function( event, ui ) {
				$( ".menu" ).menu( "collapse" );
			},
			icons: { submenu: "ui-icon-circle-triangle-e" }
		});
	});
</script>
<style>
	.ui-menu { width: 200px; font-size: 11px !important; background-color: #CCCCCC !important;}
</style>