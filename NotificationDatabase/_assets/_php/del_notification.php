<?php
// inicia a sessao
session_start();
//fara a conexao com banco de dados
include_once 'conexao.php';
//recupera as informacoes enviadas pelo cadastro
$id_noti = filter_input(INPUT_POST, "id");
$id_usu = filter_input(INPUT_POST, "id_usu");

// verificar a conexao, se tudo estiver certo, vai executar a linha e enviar o novo registro, se nao vai informar qual o erro
if ($con) {
	// atualiza o rate apos a media, com seu novo numero de rate
	$query = mysqli_query($con, "DELETE FROM notificacao WHERE id_usu_noti = '$id_usu' AND id_noti='$id_noti'");
}else{
	// erro no banco
$erro = mysqli_error($con);
//email da qis
$from = "suporte@qiseventos.com.br";
// envia um e-mail informando o erro
if (PHP_VERSION_ID < 50600) {
	iconv_set_encoding('input_encoding', 'UTF-8');
	iconv_set_encoding('output_encoding', 'UTF-8');
	iconv_set_encoding('internal_encoding', 'UTF-8');
} else {
	ini_set('default_charset', 'UTF-8');
}

$headers = "MIME-Version: 1.1\r\n";
$headers .= "From: QIS@scripts_interno\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$headers  .= "Content-type: text/html; charset=iso-8859-1 \r\n";
('Content-type: text/html; charset=iso-8859-1 \r\n');

$subject = "[QIS] EXCLUIR";
$mensagem1  = "Mensagem enviado pelo site da QISeventos em : SCRIPT DE EXCLUIR NOTIFICACAO<br />
ERRO: {$erro}";

$subject = utf8_decode($subject);
$mensagem1 = utf8_decode($mensagem1);

$send_contact=mail($from, $subject, $mensagem1, $headers);
}