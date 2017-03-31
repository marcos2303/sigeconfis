<?php
function validate($values)
{


$error = array();
	if(isEmpty($values['ObjetivoEspecifico'])){
		$error['ObjetivoEspecifico'] = 'Debe llenar el campo';
	}
	if(isEmpty($values['Condicion'])){
		$error['Condicion'] = 'Debe llenar el campo';
	}
	if(isEmpty($values['Criterio'])){
		$error['criterio'] = 'Debe llenar el campo';
	}
	if(isEmpty($values['Causas'])){
		$error['Causas'] = 'Debe llenar el campo';
	}
	if(isEmpty($values['Efectos'])){
		$error['Efectos'] = 'Debe llenar el campo';
	}
	if(isEmpty($values['Recomendaciones'])){
		$error['Recomendaciones'] = 'Debe llenar el campo';
	}
return $error;
}
