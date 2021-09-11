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
	$foto  = $_POST['foto_usu'];
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
			// arquivo ja modificado
			$profile = base64_jpg( $foto ); // usa a funcao base64_jpg para criar a imagem
			// se der false como resultado da conversao bas64 para imagem, retorna erro.
			if ($profile == false) {
				throw new Exception('ERRO');
			}
			// antes de subir uma nova imagem, excluir antiga imagem
			$query_before = mysqli_query($conn, " SELECT foto_usu FROM usuario WHERE id_usu = $id_usu ");
			$linha_before = mysqli_fetch_assoc($query_before);
			$profile_before = $linha_before['foto_usu'];
			// excluir imagem se existir imagem no banco
			if ($profile_before != '' || $profile_before != null || !empty($profile_before) ) {
				delete_image( $profile_before );
			}

			// query a ser exec
			$query = mysqli_query($conn, " UPDATE usuario SET foto_usu = '$profile' WHERE id_usu = $id_usu ");
			// se as 2 querys ou so uma for exec, continue, se nao volta o erro
			if ( $query || ($query && $query_before) ) {
				// feito com exito!
				echo "Imagem alterada com sucesso!";
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

	// transformar imagem base64 em um arquivo jpg e enviar para pasta
	function base64_jpg( $image ) {
		$path_direct = $GLOBALS['path_direct'];
		$path_here = $GLOBALS['path_here'];
		$type = "png";
		// remove jpg, png, jpeg
		$image = str_replace('data:image/png;base64,', '', $image);
		$image = str_replace('data:image/jpg;base64,', '', $image);
		$image = str_replace('data:image/jpeg;base64,', '', $image);
		$image = str_replace('', '+', $image);
		// $data = base64_decode($image1);
		$data = base64_decode($image);
		$f = finfo_open();
		$mime_type = finfo_buffer($f, $data, FILEINFO_MIME_TYPE);
		// se for png, muda a ext no nome e faz a alteracao necessaria
		if ($mime_type == "image/png") {
			// list($type, $image) = explode(';', $image);
			// list($type, $image) = explode(',', $image);
			$newName = uniqid ( time () ) . '.' . 'png';
		}else{
			$newName = uniqid ( time () ) . '.' . 'jpg';
		}
		// path para subir ao servidor
		$path = $path_here . $newName;
		// envia ao servidor
		file_put_contents($path , $data);
		// executa a funcao de redimensionar a imagem, enviando o caminho da imagem original ja enviada ao servidor, novo tamanho e o novo nome (nao redimensionar, por enquanto )
		$resize_img = resize_img($path, $newName, 0, '', ''); // como e 0, sera dividio o tamanho, entao nao precisa colocar a largura e altura desejavel
		// $resize_img = $path;

		// name a ser enviado ao banco
		$new_pic = str_replace($path_here, '', $path); // retira
		$new_pic = $path_direct . $new_pic; // add
		// se ao redimens for true, tudo certo, returna
		if ($resize_img) {
			return $new_pic; // retorna o caminho para enviar ao banco, a imagem e alterada pela funcao resize_img, mas o caminho/nome permanece o mesmo.
		}else{
			return false;
		}
	}

	// redimensionar image parametros (o caminho da imagem, o nome, o tipo = se for 0 diminui a imagem pela metade, se for 1 coloca um tamanho fixo)
	function resize_img($imagem, $nome, $type_cut, $largura, $altura){
		$path_here = $GLOBALS['path_here'];
		// Verifica extensão do arquivo
		$extensao = strrchr($imagem, '.');
		switch($extensao) {
			case '.png':
				$funcao_cria_imagem = 'imagecreatefrompng';
				$funcao_salva_imagem = 'imagepng';
				break;
			case '.jpg':
			case '.jpeg':
				$funcao_cria_imagem = 'imagecreatefromjpeg';
				$funcao_salva_imagem = 'imagejpeg';
				break;
			default:
				return 'Erro. Tipo de arquivo não aceito';
				exit;
				break;
		}
		// Cria um identificador para nova imagem
		$imagem_original = $funcao_cria_imagem($imagem);
		// Salva o tamanho antigo da imagem
		list($largura_antiga, $altura_antiga) = getimagesize($imagem);
		// Se o type for 0, sera dividido pela metade o tamanho, se for fixo use o tamanho que foi passado.
		if ( $type_cut == 0 ) {
			$largura = $largura_antiga / 2;
			$altura = $altura_antiga / 2;
		}
		// echo list($largura_antiga, $altura_antiga);
		// Cria uma nova imagem com o tamanho indicado
		// Esta imagem servirá de base para a imagem a ser reduzida
		$imagem_tmp = imagecreatetruecolor($largura, $altura);
		// manter transparencia se for png
		imagealphablending( $imagem_tmp, false );
		imagesavealpha( $imagem_tmp, true );
		// Faz a interpolação da imagem base com a imagem original
		imagecopyresampled($imagem_tmp, $imagem_original, 0, 0, 0, 0, $largura, $altura, $largura_antiga, $altura_antiga);
		// Salva a nova imagem. Ao efetuar, pelo fato de terem o mesmo nome, e excluido a imagem original
		$resultado = $funcao_salva_imagem($imagem_tmp, $path_here . $nome);
		// Libera memoria
		imagedestroy($imagem_original);
		imagedestroy($imagem_tmp);
		if($resultado){
			// feito com exito!
			return true;
		}else{
			// erro
			return false;
		}
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