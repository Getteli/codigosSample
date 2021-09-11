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
		$desabled_btn_pic = 'disabled';
	}else{
		$desabled_btn_pic = '';
	}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>UP foto</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#F51E65">
	<!-- jquery -->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
	<style type="text/css">
	label{
		display: grid;
	}
	label > img{
		width: 200px;
	}
	img{
	    /*transform: rotate(180deg);*/
	    width: 100%;
	}
	</style>
</head>
<body>

	<label>
		<input type="file" capture="camera" accept="file" id="cameraInput" name="foto_usu" />
		<img id="foto_usu_preview" src="<?php echo $foto_db ?>">
	</label> <!-- photo user -->
	<hr/>
	<button id="rem_pic" <?php echo $desabled_btn_pic ?> >Remover foto</button>

<!-- scripts -->
<script type="text/javascript">
	$(document).ready(function () {

		// ao mudar o valor do input de add foto (clicando no label de add nova foto, executa a funcao)
		$("#cameraInput").change(function(){
			// pega o id
			var fup = document.getElementById('cameraInput');
			// pega o valor dentro do input
			var fileName = fup.value;
			// pega a extensao
			var extension = fileName.substring(fileName.lastIndexOf('.') + 1);
			// coloca a extansao em capslock
			var ext = extension.toUpperCase();
			// se estiver vazio volta
			if (fileName == "" || fileName == null) {
				return false;
			}
			// formato
			if(
				ext == "JPG" || ext == "JPEG" || ext == "PNG" || ext == "BITMAP"
			){
				// e uma imagem valida
				send_img(this);
				return true;
			}else{
				// nao e uma imagem valida, volta ao default
				alert('nao e uma foto');
				$('#foto_usu_preview').attr('src', "_assets/_img/default.png");
				return false;
			}
		});

		// clique para remover a foto, tanto preview, db e sistema
		$("#rem_pic").on('click',function(){
			remove_picture();
		});

		// add foto
		function send_img(r_img) {
			if (r_img.files && r_img.files[0]) {
				// var
				var reader = new FileReader();
				reader.onload = function (e) {
						// ao entrar para ler o arquivo, executa a funcao get_dimension, para pegar o tamanho da imagem
						get_dimension(e.target.result, function(result_dimension){
						if ( result_dimension == 'portrait' ) {
							//Início do Comando AJAX
							$.ajax({
								url: '_assets/_php/upload_foto.php',
								// Aqui você deve preencher o tipo de dados que será lido,
								type:'POST',
								data: ({foto_usu: e.target.result }),
								// SUCESS é referente a função que será executada caso
								// ele consiga ler a fonte de dados com sucesso.
								// O parâmetro dentro da função se refere ao nome da variável
								// que você vai dar para ler esse objeto.
								success: function(resposta){
									console.log(resposta);
									// Confere se houve erro e o imprime
									if(resposta == "ERRO"){
										alert('erro');
										// volta ao default
										$('#foto_usu_preview').attr('src', "_assets/_img/default.png");
										return false;
									}else{
										alert('foi');
										// foto foi upada com sucesso
										// add ao preview
										$('#foto_usu_preview').attr('src', e.target.result);
										$('#foto_usu_previewd').attr('src', e.target.result);
										$('#foto_usu_previewm').attr('src', e.target.result);
										return true;
									}
								}
							});
						}else{
							alert('Gire o seu dispositivo ou selecione uma outra imagem em modo RETRATO.');
							return false;
						}
					});
				}
				// faz a leitura do arquivo
				reader.readAsDataURL(r_img.files[0]);
			}
		}

		// funcao para pegar o tamanho da imagem, parametro file = imagem base64, rd e o retorno para ser usado
		function get_dimension(file, rd) {
			// var
			var img = new Image();
			// faz a leitura da imagem
			img.onload = function(){
				// se for menor largo que alto ou igual, e uma imagem em modo retrato ou um quadrado, se nao e modo paisagem
				if ( img.width <= img.height ) {
					alert(img.width + '/' + img.height);
					rd('portrait');
				}else{
					alert(img.width + '/' + img.height);
					rd('landscape');
				}
			};
			// add a imagem a var
			img.src = file;
		}

		// remove a imagem do bd, do sistema e do preview
		function remove_picture() {
			//Início do Comando AJAX
			$.ajax({
				url: '_assets/_php/remove_foto.php',
				// SUCESS é referente a função que será executada caso
				// ele consiga ler a fonte de dados com sucesso.
				// O parâmetro dentro da função se refere ao nome da variável
				// que você vai dar para ler esse objeto.
				success: function(resposta){
					// Confere se houve erro e o imprime
					if(resposta == "ERRO"){
						alert('erro');
						return false;
					}else{
						alert('foi');
						// foto foi removida com sucesso
						// add o default ao  preview
						$('#foto_usu_preview').attr('src', "_assets/_img/default.png");
						return true;
					}
				}
			});
		}

	}) // fim Document.ready
</script>