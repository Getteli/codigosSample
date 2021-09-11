<?php
	// charset
	header('Content-Type: text/html; charset=utf-8');
	// conexao com o bd
	include_once('_assets/_php/conection.php');

	// query TESTE, para a producao sera feito o file details
	$query = mysqli_query($conn, " SELECT 
		foto_usu
		FROM usuario WHERE id_usu = 1 ");
	// pega a linha
	$linha = mysqli_fetch_assoc($query);
	$foto_db = $linha['foto_usu'];
	// se foto for vazia, desativa o botao de remover foto
	if (empty($foto_db)) {
		$foto_db = '_assets/_img/default.png';
		$desabled_btn_pic = 'disabled';
	}else{
		$desabled_btn_pic = '';
	}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>UP foto com crop</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#F51E65">
	<!-- jquery -->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="_assets/_style/croppie.min.css">
	<link rel="stylesheet" type="text/css" href="_assets/_style/crop_personalizado.min.css">
	<style type="text/css">
		label.lb_preview{
			display: grid;
			position: relative;
			width: 200px;
		}

		#fup{
			width: 200px;
			border-radius: 40px;
		}

		.none{
			display: none;
		}
	</style>
</head>
<body>

	<!-- conteudo mixto, leia os comentarios dentro -->
	<label class="lb_preview">
		<!-- CONTEUDO NECESSARIO PARA ADICIONAR AO POR EM PRODUCAO EM UM PROJETO -->
		<input type="file" capture="camera" class="none" accept="file" id="cameraInput" name="foto_usu" />
		<!-- apenas para exibir -->
		<img id="fup" src="<?php echo $foto_db ?>">
		<!-- para clicar para add a imagem, bom para ter ideia de por nos projetos -->
		<span class="add_new_crop"></span>
	</label> <!-- photo user -->

	<hr/>

	<!-- CONTEUDO NECESSARIO PARA ADICIONAR AO POR EM PRODUCAO EM UM PROJETO -->
	<!-- btn pare remover foto -->
	<button id="rem_pic" class="btns_crop remove_crop" <?php echo $desabled_btn_pic ?> >Remover</button>
	<!-- div para o crop -->
	<div class="bg_cont_crop" class="none">
		<div class="back_crop">
			<span id="close_crop">&#10006;</span>
			<div class="container_crop_img"></div>
			<div class="cont_btn_crop">
				<button class="btns_crop rotate_img_crop" data-deg="90">Girar</button>
				<button class="btns_crop cut_img_crop">salvar</button>
			</div>
		</div>
	</div>

<!-- scripts -->
<script type="text/javascript" src="_assets/_js/croppie.min.js"></script>
<script type="text/javascript" src="_assets/_js/crop_personalizado.min.js"></script>