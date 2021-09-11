<?php
	// charset
	header('Content-Type: text/html; charset=utf-8');
	// conexao com o bd
	include_once('conection.php');
	// encriptar / desencriptar
	include_once('encriptar.php');

	// impede erros de NOTICE a aparecer na page, pois caso as variaveis estejam vazias dera erro(notice)
	// error_reporting(0);
	// inicia a sessao para pegar o id do usuario
	session_start();

	// var
	// dados PRODUCAO
	// obs: esta encriptada
	/*
	$id_usu = base64_decode( $_SESSION['id_usu'] );
	$id_usu = Desencriptar( $id_usu );
	*/
	// ID TESTE
	$id_usu = 1;

	$GLOBALS['path_direct'] = '_assets/_usu/_img/';
	$GLOBALS['path_here'] = '../_usu/_img/';

	// verificar a conexao, se tudo estiver certo, vai executar a linha, se nao vai informar qual o erro
	try{
		if ($conn) {
			// antes de subir uma nova imagem, excluir antiga imagem
			$query = mysqli_query($conn, " SELECT foto_usu FROM usuario WHERE id_usu = $id_usu ");
			$linha = mysqli_fetch_assoc($query);
			$profile = $linha['foto_usu'];
			// excluir imagem se existir imagem no banco
			if ($profile != '' || $profile != null || !empty($profile) ) {
				delete_image( $profile );
			}else{
				throw new Exception('ERRO');
			}
			// query a ser exec, remove o caminho no banco
			$query_remove_db = mysqli_query($conn, " UPDATE usuario SET foto_usu = '' WHERE id_usu = $id_usu ");
			// se as querys for exec, continue, se nao volta o erro
			if ( $query && $query_remove_db ) {
				// feito com exito!
				echo "Imagem removida com sucesso!";
			}else{
				throw new Exception('ERRO');
			}
		}else{
			// throw new Exception('Erro na tentativa de se conectar com o banco de dados.');
			throw new Exception('ERRO');
		}
	}catch(Exception $Error0) {
		// pega o erro
		$erro0 = $Error0->getMessage();
		// dar echo e retorna pelo ajax
		echo $erro0;
	}

	// excluir imagem
	function delete_image($pic_before){
		$path_direct = $GLOBALS['path_direct'];
		$path_here = $GLOBALS['path_here'];
		// altera o caminho pois do banco e diferente daqui para a imagem
		$pic_before = str_replace($path_direct, '', $pic_before); // retira
		$pic_before = $path_here . $pic_before; // add
		// se nao estiver vazio, se o arquivo existir, exclua a foto anterior
		if ( !empty( $pic_before ) && file_exists( $pic_before ) ) {
			// exclui
			unlink( $pic_before );	
		}
	}