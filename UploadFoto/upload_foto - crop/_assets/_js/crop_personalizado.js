$(document).ready(function () {

	var x_crop = document.getElementById('close_crop');
	var crop_cont = $('.bg_cont_crop');
	var crop_div = $('.container_crop_img')

	// recebe o container do crop, cria o viewport (area de corte) e o tipo, cria no onload mesmo
	var crop = crop_div.croppie({
		// pode ser um circulo ou um quadrado, e escolher a altura e largura
		viewport: {
			width: 300,
			height: 300,
			type: 'square' // circle
		},
		enableOrientation: true
	});

	// ao mudar o valor do input de add foto (clicando no label de add nova foto, executa a funcao)
	$("#cameraInput").change(function(){
		set_crop(this);
		crop_cont.show();
	});

	// gire a imagem
	$(".rotate_img_crop").on('click',function(){
		crop.croppie('rotate', parseInt($(this).data('deg')));
	});

	// ao clicar para cortar a imagem, pega o resultado via base64 e envia para o banco, via function
	$(".cut_img_crop").on('click',function(){
		crop.croppie('result', 'base64').then(function(img_cut) {
			send_img(img_cut);
			$('#fup').attr('src', img_cut);
		});
	});

	// clique para remover a foto, tanto preview, db e sistema
	$("#rem_pic").on('click',function(){
		remove_picture();
	});

	// fechar o crop
	x_crop.addEventListener('click', function close_alert(){
		close_crop();
	})

	// add a imagem ao crop
	function set_crop(input) {
		if (input.files && input.files[0]) {
			// var
			var reader = new FileReader();
			reader.onload = function (e) {
				// add a imagem ao crop, usando via base64
				crop.croppie('bind', {
					url: e.target.result
				});
			}
			// faz a leitura do arquivo
			reader.readAsDataURL(input.files[0]);
		}
	}

	// add foto
	function send_img(result_bs64) {
		if (result_bs64) {
			//Início do Comando AJAX
			$.ajax({
				url: '_assets/_php/upload_foto.php',
				// Aqui você deve preencher o tipo de dados que será lido,
				type:'POST',
				data: ({foto_usu: result_bs64 }),
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
						$('#fup').attr('src', "_assets/_img/default.png");
						close_crop();
						return false;
					}else{
						alert('foi');
						// foto foi upada com sucesso
						// add ao preview
						$('#fup').attr('src', result_bs64);
						close_crop();
						return true;
					}
				}
			});
		}else{ return false; }
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
					$('#fup').attr('src', "_assets/_img/default.png");
					return true;
				}
			}
		});
	}

	// funcao que junta todo o necessario para fechar a div do crop
	function close_crop() {
		crop_cont.hide();
	}

}) // fim Document.ready