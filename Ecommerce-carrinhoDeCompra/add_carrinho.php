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
	$total = '';

	$a_produto = [];

	// verificar a conexao, se tudo estiver certo, vai executar a linha, se nao vai informar qual o erro
	try{

		// ADD AO CARRINHO

		// se nao existir nenhuma sessao (nenhum item no cache/carrinho), transforma em array
		if ( !isset( $_SESSION['itens'] ) ) {
			$_SESSION['itens'] = [];
		}
		// se existir a acao sendo feita
		if ( isset($action) && $action == 'add' ) {
			$id_produto = $id;
			// se ainda nao existir este produto no carrinho (informando o id dele)
			for ($i=0; $i <= count($_SESSION['itens']) ; $i++) {

				// pesquisa o produto
				$query_get = mysqli_query($conn, " SELECT 
					id_prod,nome_prod,id_cat,qtd_prod,preco_prod,img_prod
					FROM estoque
					WHERE id_prod = '$id_produto' ");
				$linha_carrinho = mysqli_fetch_assoc( $query_get );
				// var
				$nome_prod = $linha_carrinho['nome_prod'];
				$preco_prod = $linha_carrinho['preco_prod'];


				if ( !isset( $_SESSION['itens'][$i]['id'] ) ) {
					$a_produto['id'] = $id_produto; // id
					$a_produto['qtd'] = 1; // quantidade
					$a_produto['preco_t'] = $preco_prod * $a_produto['qtd']; // preco total = qtd x o preco unitario
					$a_produto['preco'] = $preco_prod; // preco unidade
					$a_produto['nome'] = $nome_prod; // nome

					$_SESSION['itens'][$i] = $a_produto;
					break;
				}
				if( $_SESSION['itens'][$i]['id'] == $id_produto ){
					$_SESSION['itens'][$i]['id'] = $id_produto;
					$_SESSION['itens'][$i]['qtd'] += 1;
					$_SESSION['itens'][$i]['preco_t'] = $preco_prod * $_SESSION['itens'][$i]['qtd'];
					$_SESSION['itens'][$i]['preco'] = $preco_prod;
					$_SESSION['itens'][$i]['nome'] = $nome_prod;
					break;
				}
			}
			return true;
		}
	}catch(Exception $Error0) {
		// pega o erro
		$erro0 = $Error0->getMessage();
		// informa
		$enviar_erro = send_email_erro($email_sender, "Erro de script", $erro0, "tentativa de add ao carrinho", "carrinho");
		if ( $enviar_erro ) {
			// header('location:../../login.php?erro=banco');
			return false;
		}else{
			return false;
		}
	}