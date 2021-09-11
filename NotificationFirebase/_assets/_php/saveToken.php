<?php
	// conection
	include_once('conection.php');

	// recebe o token do fcm
	$token = filter_input(INPUT_POST , 'token');

	// verificar a conexao, se tudo estiver certo, vai executar a linha, se nao vai informar qual o erro
	try{
		if ($conn) {
			// query a ser pesquisada
			$query = mysqli_query($conn, " INSERT INTO usuario (id_usu, nome, token, dt) VALUES (null, 'nome', '$token', CONVERT_TZ(NOW(), 'UTC', 'America/Sao_Paulo') ) ");
			// se o email e senha forem compativeis, entao loga
			try {
				if ( $query ) {
					echo "salvo";
				}else{
					// informa o erro e vai para o catch
					throw new Exception('Erro ao salvar o token no banco de dados');
				}
			}catch(Exception $Error1) {
				// pega o erro
				$erro1 = $Error1->getMessage();
				// informa
				echo $erro1;
			}
		}else{
			throw new Exception('Erro na tentativa de se conectar com o banco de dados.');
		}
	}catch(Exception $Error0) {
		// pega o erro
		$erro0 = $Error0->getMessage();
		// informa
		echo $erro0;
	}