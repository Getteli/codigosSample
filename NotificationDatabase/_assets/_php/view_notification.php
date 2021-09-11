<?php
// inicia a sessao
session_start();
//fara a conexao com banco de dados
include_once 'conection.php';

// verificar a conexao, se tudo estiver certo, vai executar a linha e enviar o novo registro, se nao vai informar qual o erro
if ($conn) {
	// atualiza o rate apos a media, com seu novo numero de rate
	$query = mysqli_query($conn, "UPDATE notification SET visu_noti = '1' WHERE id_not = '$id_not'");
}else{
}