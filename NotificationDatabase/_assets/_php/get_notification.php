<?php
	//fara a conexao com banco de dados
	include_once 'conection.php';

	$query_notification = mysqli_query($conn, " SELECT COUNT(*) AS total FROM notification WHERE visu_not = 0 ");
	$count = mysqli_fetch_assoc($query_notification);

	if($count['total'] == "0") {
	}else{
		echo $count['total'];
	}