<?
/*$dbhost="192.168.2.3";  // host del MySQL (generalmente localhost)
$dbusuario="admin"; // ingresar el nombre de usuario para acceder a la base
$dbpassword="ceda3697"; // password de acceso para el usuario
$db="siaceda";        // Seleccionamos la base con la cual trabajar
$conexion = mysql_connect($dbhost, $dbusuario, $dbpassword);
mysql_select_db($db, $conexion);*/

$dbhost="localhost";  // host del MySQL (generalmente localhost)
$dbusuario="root"; // ingresar el nombre de usuario para acceder a la base
$dbpassword="root"; // password de acceso para el usuario
$db="siaceda";        // Seleccionamos la base con la cual trabajar
$conexion = mysql_connect($dbhost, $dbusuario, $dbpassword);
mysql_select_db($db, $conexion);
?>