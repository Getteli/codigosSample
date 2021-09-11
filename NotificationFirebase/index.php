<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>FCM - NOTIFICATION</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="Teste para notification via firebase">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="img/teste0.png">
	<link rel="apple-touch-icon" href="img/teste0.png">
	<meta name="theme-color" content="#4B4B4D">
	<meta name="mobile-web-app-capable" content="yes">
	<!-- manifest / sw-->
	<link rel="manifest" href="manifest.json">
	<script type="text/javascript" src="service-worker.js"></script>
	<!-- jquery -->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
	<!-- FCM -->
	<!-- o service-worker registra um sw com o nome do firebase-messaging-sw.js para citar o arquivo firebase-messaging-sw.js para/com a notificacao em segundo plano -->
	<!-- <script src="https://www.gstatic.com/firebasejs/5.4.0/firebase.js"></script> -->
	<script src="https://www.gstatic.com/firebasejs/5.4.0/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/5.4.0/firebase-messaging.js"></script>
	<script type="text/javascript" src="fcm.js"></script>
	<script type="text/javascript">
	</script>
	<style type="text/css">
		body{
			background-color: #4b4b4d;
			font-family: arial, sans-serif;
			color: #fff;
			font-weight: normal;
		}
	</style>
</head>
<body>
<h1>FCM - NOTIFICATION</h1>