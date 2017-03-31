<?php
function isNumeric($field)
{	
	$error = null;
	if(!is_int($field)){
		$error = " Debe ser un número entero";
		return $error;
	}
}
function isEmpty($field)
{	
	if(!isset($field) or empty($field) or $field == '')
	{
		return true;
	}
	return false;
	
}