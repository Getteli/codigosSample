<?php
	// charset
	header('Content-Type: text/html; charset=utf-8');
	setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	date_default_timezone_set('America/Sao_Paulo');
	// conexao com o bd
	include_once('conection.php');
	// variaveis
	include_once('var.php');
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

	$action = filter_input(INPUT_POST , 'action');
	$id = filter_input(INPUT_POST , 'id');

	// verificar a conexao, se tudo estiver certo, vai executar a linha, se nao vai informar qual o erro
	try{

		// REMOVE DO CARRINHO

		// se existir sessao (item no cache/carrinho), faca
		if ( isset( $action ) && $action == "remove" ) {
			$id_produto = $id; // recebe o id q veio pelo post
			for ($i=0; $i <= count($_SESSION['itens']) ; $i++) { 
				if ( $_SESSION['itens'][$i]['id'] == $id_produto ) {
					$_SESSION['itens'][$i]['qtd'] -= 1; // remove 1
					$_SESSION['itens'][$i]['preco_t'] = $_SESSION['itens'][$i]['preco'] * $_SESSION['itens'][$i]['qtd']; // diminui um prod
					// se for 0, entao remove o item do carrinho
					if ( $_SESSION['itens'][$i]['qtd'] == 0 ) {
						unset($_SESSION['itens'][$i]);
						break;
					}
				}
			}
		}

	}catch(Exception $Error0) {
		// pega o erro
		$erro0 = $Error0->getMessage();
		// informa
		$enviar_erro = send_email_erro($email_sender, "Erro de script", $erro0, "tentativa de add ao carrinho", "carrinho");
		if ( $enviar_erro ) {
			// header('location:../../login.php?erro=banco');
		}else{
			// header('location:../../login.php?erro=banco');
		}
	}