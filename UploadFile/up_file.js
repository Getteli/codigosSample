// add arquivo
function send_file(r_file, nome_file) {
	if (r_file.files && r_file.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			// alert(e.target.result);
			//Início do Comando AJAX
			$.ajax({
				url: '_assets/_php/upload_file.php',
				// Aqui você deve preencher o tipo de dados que será lido,
				type:'POST',
				data: ({
					doc_up: e.target.result,
					doc_name: nome_file
				}),
				// SUCESS é referente a função que será executada caso
				// ele consiga ler a fonte de dados com sucesso.
				// O parâmetro dentro da função se refere ao nome da variável
				// que você vai dar para ler esse objeto.
				success: function(resposta){
					// Confere se houve erro e o imprime
					if(resposta == "ERRO"){
						alert_personalizado(resposta,"Erro ao subir arquivo, tente novamente ou mude o arquivo.","ok");
						return false;
					}else{
						alert_personalizado('concluido',resposta,'continuar', true, true);
						return true;
					}
				}
			});
			// alert('foto: ' + e.target.result);
		}
		reader.readAsDataURL(r_file.files[0]);
	}
}

// ao mudar o valor do input de add file
$("#doc_usu").change(function(){
	var fup = document.getElementById('doc_usu');
	var fileName = fup.value;
	var extension = fileName.substring(fileName.lastIndexOf('.') + 1);
	var ext = extension.toUpperCase();
	var nome_file = $('#doc_usu').val().replace(/C:\\fakepath\\/i, '');
	// se estiver vazio volta
	if (fileName == "" || fileName == null) {
		return false;
	}
	// formato
	if(
		ext == "PDF"
	){
		// e uma imagem valida
		send_file(this, nome_file);
		return true;
	}else{
		// nao e uma imagem valida, volta ao default
		alert_personalizado("não é um Arquivo","O arquivo escolhido não é arquivo válido, tente um PDF, por favor.","ok");
		return false;
	}
});