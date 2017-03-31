<?php
$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
$navegador = preg_match("/Firefox/", $HTTP_USER_AGENT, $coincidencias, PREG_OFFSET_CAPTURE, 3);
if ($navegador==1) {
	echo "
	<html>
	<head>
	<title>Sistema Integral Administrativo [S.I.A]x</title>
	</head>
	<frameset id='frmSet' frameborder='no' border='0' rows='38px, *'>
	<frame noresize scrolling='no'  src='menu.php'>
	<frame noresize src='framemain.php' name='main' id='main'>
	</frameset>
	<noframes></noframes>
	<body></body>
	</html>";
} else {
	$navegador = preg_match("/Opera/", $HTTP_USER_AGENT, $coincidencias, PREG_OFFSET_CAPTURE, 3);
	if ($navegador==1) {
		echo "
		<html>
		<head>
		<title>Sistema Integral Administrativo [S.I.A]xx</title>
		</head>
		<frameset id='frmSet' frameborder='no' border='0' rows='30px, *'>
		<frame noresize scrolling='no'  src='menu.php'>
		<frame noresize src='framemain.php' name='main' id='main'>
		</frameset>
		<noframes></noframes>
		<body></body>
		</html>";
	} else {
            die;
		echo "
                <html>
                <head>
                <title>Sistema Integral Administrativo [S.I.A]x</title>
                </head>
                <frameset id='frmSet' frameborder='no' border='0' rows='38px, *'>
                <frame noresize scrolling='no'  src='menu.php'>
                <frame noresize src='framemain.php' name='main' id='main'>
                </frameset>
                <noframes></noframes>
                <body></body>
                </html>";
	}
}



?>