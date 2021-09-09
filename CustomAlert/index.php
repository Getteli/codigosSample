<?php
	// charset
	header('Content-Type: text/html; charset=utf-8');
	// alert_personalizado
	include_once('alert_personalizado.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>ALERTA PERSONALIZADO - PHP + JS</title>
	<link rel="stylesheet" href="alert_personalizado.css">
	<style type="text/css">
	</style>
</head>
<body>
<!-- script -->
<script src="alert_personalizado.js" type="text/javascript"></script>
<script type="text/javascript">
	alert_personalizado("title","mensagem","texto do botão", false, false, true, 20000);
</script>