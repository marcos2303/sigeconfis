<?php
@session_start();
//	FUNCION PARA CONECTARSE CON EL SERVIDO MYSQL
function connect() {
        if(!isset($_SESSION["MYSQL_HOST"])  or !isset($_SESSION["MYSQL_USER"]) or !isset($_SESSION["MYSQL_CLAVE"])){
            die('Inicie sesión nuevamente');
        }
	mysql_connect($_SESSION["MYSQL_HOST"], $_SESSION["MYSQL_USER"], $_SESSION["MYSQL_CLAVE"]) or die ("NO SE PUDO CONECTAR CON EL SERVIDOR MYSQL!");
mysql_select_db($_SESSION["MYSQL_BD"]) or die ("¡NO SE PUDO CONECTAR CON LA BASE DE DATOS!");
	mysql_query("SET NAMES 'utf8'");
}
?>