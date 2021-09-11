<?php
	// charset
	header('Content-Type: text/html; charset=utf-8');
	setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	date_default_timezone_set('America/Sao_Paulo');
	// conexao com o bd
	include_once('conection.php');
	// variaveis
	include_once('var.php');
	// details
	include_once('details.php');
	// email
	include_once('email.php');
	// template email
	$template = file_get_contents("template_email.html");
	// encriptar / desencriptar
	include_once('encriptar.php');

	// inicia a sessao para pegar o id do usuario
	session_start();

	// obs: esta encriptada
	$id_usu = base64_decode( $_SESSION['id_usu'] );
	$id_usu = Desencriptar( $id_usu );

	$pedido = $_SESSION['itens'];
	$pedido = convert_string($pedido);
	$total = $_SESSION['total'];
	$desc_ped = filter_input(INPUT_POST , 'desc_ped');

	// verificar a conexao, se tudo estiver certo, vai executar a linha, se nao vai informar qual o erro
	try{
		if ($conn) {
			// criar pedido
			$query = mysqli_query($conn, " INSERT INTO pedido 
				(pedido, id_usu, valor_total, desc_ped, active_ped)
				VALUES
				('$pedido', '$id_usu', '$total', '$desc_ped', '0') ");

			if ( $query ) {
				// diminui a qnt do produto no banco
				foreach ($_SESSION['itens'] as $produto) {
					// var
					$id_prod = $produto['id'];
					$qtd_prod = $produto['qtd'];
					// atualiza a quantidade de cada produto
					$query_up = mysqli_query($conn, " UPDATE estoque SET qtd_prod = qtd_prod - ".$qtd_prod." WHERE id_prod = '$id_prod' ");
				}
				// limpar o carrinho
				unset($_SESSION['itens']);
				// enviar email de sucesso
				// $body = 'texto sei la, mostra o pedido..';
				// send_email($email_sender, $subject_email_finalizado, $title_email_finalizado, $body, $email_usu, $nome_db, $template);
				header('location:../../carrinho.php?info=finalizado');
			}else{
				throw new Exception('Erro na tentativa de finalizar pedido.');
			}
		}else{
			throw new Exception('Erro na tentativa de se conectar com o banco de dados.');
		}
	}catch(Exception $Error0) {
		// pega o erro
		$erro0 = $Error0->getMessage();
		// informa
		// $enviar_erro = send_email_erro($email_sender, "Erro de script", $erro0, "tentativa de finalizar compra", "carrinho");
		if ( $enviar_erro ) {
			// header('location:../../login.php?erro=banco');
		}else{
			// header('location:../../login.php?erro=banco');
		}
	}