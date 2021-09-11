<?php
	// conection
	include_once('conection.php');

	// chave do projeto no firebase
	DEFINE('SERVER_API_KEY', 'AIzaSyBmJKnx49CETmQsFTmc8i0bI5rL7QpiqcU');

	// verificar a conexao, se tudo estiver certo, vai executar a linha, se nao vai informar qual o erro
	try{
		if ($conn) {
			// busca os tokens necessarios (todos ou quais desejar)
			$query = mysqli_query($conn, " SELECT nome,token FROM usuario");
			// passa pela query pegando os tokens e enviando a notificacao
			while ( $linha = mysqli_fetch_assoc($query) ) {
				$id_machine[] = $linha['token']; // token
			}
			// apos o while pegar todos os tokens e por dentro de um array, envia uma notificacao
			echo sendPush( $id_machine );
		}else{
			throw new Exception('Erro na tentativa de se conectar com o banco de dados.');
		}
	}catch(Exception $Error0) {
		// pega o erro
		$erro0 = $Error0->getMessage();
		// informa
		echo $erro0;
	}

	// funcao para enviar notificacao
	function sendPush($tokens){
		// chave do servidor
		$header = array(
			'Authorization: key=' . SERVER_API_KEY,
			'Content-Type: Application/json'
		);

		// mensagem com todos os dados
		$msg = array(
			'title' => 'Titulo teste agora funcao', // titulo
			'body' => 'hello world FCM, agora ti venci', // corpo
			'icon' => 'https://ea4f8718.ngrok.io/img/teste0.png', // icone
			'badge' => 'https://ea4f8718.ngrok.io/img/badge.png', // icone da bandeija de notificacao (mobile)
			// 'image' => '../../img/teste1.png', // envia imagem no corpo caso necessario
			'click_action' => 'info.php', // onClick, pagina para ir
			// action para o background, normalmente ver e fechar a notificacao
			'action' => 'Open',
			'action_title' => 'Ver',
			'action_icon' => 'https://ea4f8718.ngrok.io/img/open.png',
			'action1' => 'Close',
			'action_title1' => 'Fechar',
			'action_icon1' => 'https://ea4f8718.ngrok.io/img/close.png'
		);
		// payload possui o token e os dados
		$payload = array(
			'registration_ids' => $tokens,
			'data' => $msg
		);
		// envia a notificacao via cURL
		$curl = curl_init();

		// esta parte é apenas em localhost para teste, pois https nao funciona
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curl, CURLOPT_CAINFO, 'C:\Users\Douglas\Desktop\SERVIDORES ILION\usbwebserver versao v8.6\apache2\bin\arquivocertificado.crt');
		// opcoes da conexao para envio
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://fcm.googleapis.com/fcm/send", // link que faz o envio pelo fcm
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode( $payload ),
			CURLOPT_HTTPHEADER => $header
		));

		$response = curl_exec($curl); // executa a conexao
		$err = curl_error($curl); // erro de notificacao

		curl_close($curl); // fecha a conexao

		// retorna se houve um erro
		if ($err) {
			return "cURL error #:" . $err;
		}else{
			return $response;
		}
	}