<?php
include("../lib/fphp.php");
?>
<link rel="stylesheet" href="<?=$_PARAMETRO["PATHSIA"]?>js/jquery-ui-1.11.4.custom/jquery-ui.css">
<link rel="stylesheet" href="<?=$_PARAMETRO["PATHSIA"]?>js/jquery-ui-1.11.4.custom/jquery-ui.theme.css">
<script src="<?=$_PARAMETRO["PATHSIA"]?>js/jquery-ui-1.11.4.custom/external/jquery/jquery.js"></script>
<script src="<?=$_PARAMETRO["PATHSIA"]?>js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>

	<table width="100%" border="0">
		<tr>
			<td>
				<ul class="menu">
					<li><a target ="" href="<?=$_PARAMETRO["PATHSIA"]?>pf/index.php"><span class="ui-icon ui-icon-home"></span>Inicio</a></li></li>
					<li><span></span>Actuación Fiscal
						<ul>
						<li>Planificación Fiscal
							<ul>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_form&opcion=nuevo&action=framemain&concepto=01-0001"><span class="ui-icon ui-icon-document"></span>Nueva Planificación</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_lista&filtrar=default&lista=todos&concepto=01-0002"><span class="ui-icon ui-icon-clipboard"></span>Listar Planificación</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_lista&filtrar=default&lista=revisar&concepto=01-0003"><span class="ui-icon ui-icon-lightbulb"></span>Revisar Planificación</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_lista&filtrar=default&lista=aprobar&concepto=01-0004"><span class="ui-icon ui-icon-check"></span>Aprobar Planificación</a></li>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_lista&filtrar=default&lista=replanificar&concepto=01-0004"><span class="ui-icon ui-icon-calculator"></span>Replanificar Actuaciones</a></li>
							</ul>
							<li>Evaluación de riesgos
								<ul>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_riesgos_lista&filtrar=default&lista=todos&concepto=01-0002"><span class="ui-icon ui-icon-document"></span>Listar análisis de riesgo</a></li>
									
                                                                        <li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_riesgos_lista&filtrar=default&lista=revisar&concepto=01-0002"><span class="ui-icon ui-icon-lightbulb"></span>Revisar análisis de riesgo</a></li>
                                                                        <li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_riesgos_lista&filtrar=default&lista=aprobar&concepto=01-0002"><span class="ui-icon ui-icon-check"></span>Aprobar análisis de riesgo</a></li>
								</ul>
							</li>	
							<li>Cédula de Hallazgos
								<ul>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_hallazgos_lista&filtrar=default&lista=todos&concepto=01-0002"><span class="ui-icon ui-icon-document"></span>Nueva</a></li>
									<!--<li><span class="ui-icon ui-icon-check"></span>Aprobación</li>-->

								</ul>
							</li>							
						</li>
						<li><span></span>Detalle de Actividades
									<ul>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_actividades&lista=terminar&filtrar=default&concepto=01-0005"><span class="ui-icon ui-icon-calculator"></span>Ejecución de Actividades</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_actividades&lista=listar&filtrar=default&lista=todos&concepto=01-0006"><span class="ui-icon ui-icon-clipboard"></span>Listar detalle de Actividades</a></li>
									</ul>
						</li>
						<li>Prórrogas
									<ul>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_prorrogas_form&opcion=nuevo&action=framemain&concepto=01-0007"><span class="ui-icon ui-icon-document"></span>Nueva Prórroga</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_prorrogas_lista&filtrar=default&lista=todos&concepto=01-0008"><span class="ui-icon ui-icon-clipboard"></span>Listar Prórroga</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_prorrogas_lista&filtrar=default&lista=revisar&concepto=01-0009"><span class="ui-icon ui-icon-lightbulb"></span>Revisar Prórroga</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_prorrogas_lista&filtrar=default&lista=aprobar&concepto=01-0010"><span class="ui-icon ui-icon-check"></span>Aprobar Prórroga</a></li>
									</ul>
						</li>
						</ul>
					</li>
					<li><span></span>Potestad Investigativa
						<ul>
						<li>Val. Jurídicas
							<ul>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_lista&filtrar=default&lista=generar&concepto=02-0001"><span class="ui-icon ui-icon-document"></span>Listar Actuaciones Fiscales</a></li>
								<li>Planificación
									<ul>										
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_lista&filtrar=default&lista=todos&concepto=02-0002"><span class="ui-icon ui-icon-document"></span>Listar Planificación</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_lista&filtrar=default&lista=revisar&concepto=02-0003"><span class="ui-icon ui-icon-document"></span>Revisar Planificación</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_lista&filtrar=default&lista=aprobar&concepto=02-0004"><span class="ui-icon ui-icon-document"></span>Aprobar Planificación</a></li>
									</ul>
									
								</li>
								<li>Detalle de actividades
									<ul>										
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_actividades&lista=terminar&filtrar=default&concepto=02-0005"><span class="ui-icon ui-icon-document"></span>Ejecución de actividades</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_actividades&lista=listar&filtrar=default&lista=todos&concepto=02-0006"><span class="ui-icon ui-icon-document"></span>Listar detalle de actividades</a></li>
									</ul>
									
								</li>
								<li>Prórrogas
									<ul>										
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_prorrogas_form&opcion=nuevo&action=framemain&concepto=02-0007"><span class="ui-icon ui-icon-document"></span>Nueva Prórroga</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_prorrogas_lista&filtrar=default&lista=todos&concepto=02-0008"><span class="ui-icon ui-icon-document"></span>Listar Prórroga</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_prorrogas_lista&filtrar=default&lista=revisar&concepto=02-0009"><span class="ui-icon ui-icon-document"></span>Revisar Prórroga</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_prorrogas_lista&filtrar=default&lista=aprobar&concepto=02-0010"><span class="ui-icon ui-icon-document"></span>Aprobar Prórroga</a></li>

									</ul>
									
								</li>
							</ul>
							
							
						</li>
						<li>Potestad Investigativa
							<ul>
								<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_lista&filtrar=default&lista=generar&concepto=02-0011"><span class="ui-icon ui-icon-document"></span>Listar Valoraciones Jurídicas</a></li>
								<li>Planificación
									<ul>										
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_lista&filtrar=default&lista=todos&concepto=02-0012"><span class="ui-icon ui-icon-document"></span>Listar Planificación</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_lista&filtrar=default&lista=revisar&concepto=02-0013"><span class="ui-icon ui-icon-document"></span>Revisar Planificación</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_lista&filtrar=default&lista=aprobar&concepto=02-0014"><span class="ui-icon ui-icon-document"></span>Aprobar Planificación</a></li>
									</ul>
									
								</li>
								<li>Detalle de actividades
									<ul>										
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_actividades&lista=terminar&filtrar=default&concepto=02-0015"><span class="ui-icon ui-icon-document"></span>Ejecución Actividades</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_actividades&lista=listar&filtrar=default&lista=todos&concepto=02-0016"><span class="ui-icon ui-icon-document"></span>Listar Detalle de Actividades</a></li>
									</ul>
									
								</li>
								<li>Prórrogas
									<ul>										
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_prorrogas_form&opcion=nuevo&action=framemain&concepto=02-0017"><span class="ui-icon ui-icon-document"></span>Nueva Prórroga</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_prorrogas_lista&filtrar=default&lista=todos&concepto=02-0018"><span class="ui-icon ui-icon-document"></span>Listar Prórroga</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_prorrogas_lista&filtrar=default&lista=revisar&concepto=02-0019"><span class="ui-icon ui-icon-document"></span>Revisar Prórroga</a></li>
										<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_prorrogas_lista&filtrar=default&lista=aprobar&concepto=02-0020"><span class="ui-icon ui-icon-document"></span>Aprobar Prórroga</a></li>

									</ul>
									
								</li>
							</ul>
							
							
						</li>

						</ul>
					</li>
					<li>Reportes
						<ul>
							<li>Actuaciones Fiscales
								<ul>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/pf_pdf_actuacion_fiscal_filtro.php?fproceso=01&pdf=planificacion&concepto=04-0001">Planificación</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/pf_pdf_actuacion_fiscal_filtro.php?fproceso=01&pdf=ejecucion&concepto=04-0002">Ejecución</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/pf_pdf_actuacion_fiscal_organo_filtro.php?fproceso=01&pdf=ejecucion&concepto=04-0002">Actuaciones por Órgano/Ente</a></li>

								</ul>
							</li>
							<li>Potestad Investigativa
								<ul>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/pf_pdf_actuacion_fiscal_filtro.php?fproceso=02&pdf=planificacion&concepto=04-0003">Planificación</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/pf_pdf_actuacion_fiscal_filtro.php?fproceso=02&pdf=ejecucion&concepto=04-0004">Ejecución</a></li>

								</ul>
							</li>
						</ul>
					</li>
					<li>Maestros
						<ul>
							<li>Del Sistema
								<ul>
									<!--<li>Propios del Sistema
										<ul>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=aplicaciones_lista&filtrar=default&concepto=05-0001">Aplicaciones</a></li>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=parametros_lista&filtrar=default&concepto=05-0002">Parámetros</a></li>
										</ul>
									</li>-->
									<li>Relacionado a Personas
										<ul>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=personas_lista&filtrar=default&concepto=05-0003">Personas</a></li>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=organismos_lista&filtrar=default&concepto=05-0004">Organismos</a></li>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=dependencias_lista&filtrar=default&concepto=05-0005">Dependencias</a></li>
										</ul>
									</li>
									<li>Otros Maestros
										<ul>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=paises_lista&filtrar=default&concepto=05-0011">Países</a></li>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=estados_lista&filtrar=default&concepto=05-0012">Estados</a></li>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=municipios_lista&filtrar=default&concepto=05-0013">Municipios</a></li>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=ciudades_lista&filtrar=default&concepto=05-0014">Ciudades</a></li>
											<!--<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=tipos_pago_lista&filtrar=default&concepto=05-0015">Tipos de Pago</a></li>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=bancos_lista&filtrar=default&concepto=05-0016">Bancos</a></li>
											<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=unidad_tributaria_lista&filtrar=default&concepto=05-0024">Unidad Tributaria</a></li>-->
										</ul>
									</li>
								</ul>
							</li>
							<li>Relacionados a Planificación
								<ul>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_procesos_lista&filtrar=default&concepto=05-0017">Procesos</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_fases_lista&filtrar=default&concepto=05-0018">Fases</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actividades_lista&filtrar=default&concepto=05-0019">Actividades</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_tipo_actuacion_fiscal_lista&filtrar=default&concepto=05-0020">Tipos de Actuación Fiscal</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>rh/feriados.php">Feriados</a></li>
								</ul>
								
							</li>
							<li>Entes Externos Sujetos a Control
								<ul>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_organismos_externos_lista&filtrar=default&concepto=05-0021">Organismos Externos</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_dependencias_externas_lista&filtrar=default&concepto=05-0022">Dependencias Externas</a></li>

								</ul>
							</li>
							<!--<li>Otros
								<ul>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=miscelaneos_lista&filtrar=default&concepto=05-0023">Misceláneos</a></li>
								</ul>
							</li>-->
						</ul>
					</li>
					<li>Administración
						<ul>
							<li>Seguridad
								<ul>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=usuarios&filtrar=default&concepto=06-0001">Maestro de Usuarios</a></li>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=autorizaciones&filtrar=default&concepto=06-0002">Dar Autorizaciones a Usuarios</a></li>
								</ul>
							</li>
							<li>Seguridad Alterna
								<ul>
									<li><a target ="main" href="<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=alterna&filtrar=default&concepto=06-0003">Dar Autorizaciones a Usuarios</a></li>
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