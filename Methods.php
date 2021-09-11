
<!-- CACHE PARA LOGIN -->
<?php
	// charset
	header('Content-Type: text/html; charset=utf-8');
	// conexao com o bd
	include_once('conection.php');
	// inicia a sessao
	session_start();
	// impede erros de NOTICE a aparecer na page, pois caso as variaveis estejam vazias dera erro(notice)
	error_reporting(0);

	// var
	// obs: estao encriptadas
	$id_usu = $_SESSION['id_adm'];
	$email_usu = $_SESSION['email_adm'];
	$pass_usu = $_SESSION['pass_adm'];
	$page_name = basename($_SERVER['PHP_SELF']);

	// verifica se o usuario esta logado, se nao apaga o cache e volta ao inicio
	try{
		if(
			(!isset ($id_usu) == true) and
			(!isset ($email_usu) == true) and
			(!isset ($pass_usu) == true)
		){
			session_destroy(); // destroi a sessao
			// apaga os dados que estiverem em cache
			setcookie('id_adm',"", time()-3600);
			setcookie('email_adm',"", time()-3600);
			setcookie('pass_adm',"", time()-3600);
			unset($_COOKIE['id_adm']);
			unset($_COOKIE['email_adm']);
			unset($_COOKIE['pass_adm']);
			// erro, e vai para o catch
			throw new Exception('Deslogado, continua na page');
		}else{
			if (
				$page_name == "login" || "login.php"
			) {
				header('location:inicio');
			}else{
				echo "tudo show (y'";
				// continua
			}
		}
	}catch(Exception $Error) {
			// pega o erro
			$erro = $Error->getMessage();
			// envia o erro p/ o email
			// send_email_C($erro,"try catch cache");
			// redireciona
			header('location:login.php?erro=desconectado');
	}
?>

<!-- ENCRIPTAR E DESENCRIPTAR -->
<?php
	// charset
	header('Content-Type: text/html; charset=utf-8');
	// setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	// date_default_timezone_set('America/Sao_Paulo');
	// mb_internal_encoding("UTF-8");
	// mb_http_output( "iso-8859-1" );
	// ob_start("mb_output_handler");
	// header("Content-Type: text/html; charset=ISO-8859-1",true);

	// functions para encriptar/desencriptar
	// randomiza p/ ajudar na encriptacao e desencriptacao
	function Randomizar($iv_len){
		$iv = '';
		while ($iv_len-- > 0) {
			$iv .= chr(mt_rand() & 0xff);
		}
		return $iv;
	}

	// encriptar
	function Encriptar($var, $iv_len = 16){
		$n = strlen($var);
		if ($n % 16) $var .= str_repeat("", 16 - ($n % 16));
		$i = 0;
		$en_var = Randomizar($iv_len);
		$iv = substr($en_var, 0, 512);
		while ($i < $n) {
			$Bloco = substr($var, $i, 16) ^ pack('H*', md5($iv));
			$en_var .= $Bloco;
			$iv = substr($Bloco . $iv, 0, 512);
			$i += 16;
		}
		return base64_encode($en_var);
	}

	// desencriptar
	function Desencriptar($en_var, $iv_len = 16){
		$en_var = base64_decode($en_var);
		$n = strlen($en_var);
		$i = $iv_len;
		$var = '';
		$iv = substr(substr($en_var, 0, $iv_len), 0, 512);
		while ($i < $n) {
			$Bloco = substr($en_var, $i, 16);
			$var .= $Bloco ^ pack('H*', md5($iv));
			$iv = substr($Bloco . $iv, 0, 512);
			$i += 16;
		}
		return preg_replace('/\x13\x00*$/', '', $var);
	}
	// verifica se a encriptacao esta ok
	function Verify_enc($en_var, $desen_var){
		// desencripta
		$en_var_desen = Desencriptar( $en_var );
		// se a var deencriptada for igual a variavel do usuario, entao retorna a variavel encriptada
		if ( $en_var_desen == $desen_var ) {
			return $en_var;
		}else{
			// encripta novamente a variavel normal e tenta novamente
			$en_var = Encriptar( $desen_var );
			Verify_enc($en_var, $desen_var);
		}
	}

	// ATENÇÃO, AO ENVIAR UM CONTEUDO ENCRIPTADO, ENVIE COM A CAMADA A SEGUIR:
		// base64_encode();
	// AO RECEBER, USE A SEGUINTE CAMADA PARA PODER VER O CONTEUDO ENCRIPTADO E ENTÃO DESENCRIPTE NORMALMENTE:
		// base64_decode();
?>

<!-- ENVIAR EMAIL E METODO DE MANTER DADOS EM CACHE KEEPLOGIN  -->
<?php

	// function que armazena os dados em cache
	function keeplogin($id_usu,$email_usu,$pass_usu){
		$lifetime = 3600 * 240000; // define para 10.000 dias
		header("Cache-Control: max-age=$lifetime");
		// servidor deve manter os dados da sessão por pelo menos 10.000 dias
		ini_set('session.gc_maxlifetime', $lifetime);

		// cada cliente deve lembrar o id da sua sessão por EXATAMENTE 10.000 dias
		session_set_cookie_params($lifetime);
		session_start();

		// coloca os dados necessarios em seu cache (cookies e session)
		setcookie('id_usu',$id_usu,time()+$lifetime);
		setcookie('email_usu',$email_usu,time()+$lifetime);
		setcookie('pass_usu',$pass_usu,time()+$lifetime);

		$_SESSION['id_usu'] = $id_usu;
		$_SESSION['email_usu'] = $email_usu;
		$_SESSION['pass_usu'] = $pass_usu;

	}

	// function para enviar email info erro
	function send_email_L($error, $type){
		date_default_timezone_set("Brazil/East"); // default time zone
		$to = "NOSSO_EMAIL";
		// verifica a versao do php para o charset
		if (PHP_VERSION_ID < 50600) {
			iconv_set_encoding('input_encoding', 'UTF-8');
			iconv_set_encoding('output_encoding', 'UTF-8');
			iconv_set_encoding('internal_encoding', 'UTF-8');
		}else{
			ini_set('default_charset', 'UTF-8');
		}
		// cabecalho
		$headers = "MIME-Version: 1.1\r\n";
		$headers .= "From: NAME_PROJETO@scripts_interno\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers  .= "Content-type: text/html; charset=iso-8859-1 \r\n";
		('Content-type: text/html; charset=iso-8859-1 \r\n');
		// assunto
		$subject = "[NAME_PROJETO] ERRO";
		// mensagem
		$mensagem  = "Mensagem enviado pelo site do(a) NAME_PROJETO em : SCRIPT DE LOGIN.<br/>
		EM: {$type} <br/>
		ERRO: {$error}";
		// encapsulando uft8
		$subject = utf8_decode($subject);
		$mensagem = utf8_decode($mensagem);
		// enviando msg
		$send_contact = mail($to, $subject, $mensagem, $headers);  
		// redireciona para a pagina de erro, front para o usuario
		if ( $send_contact ) {
			header('location:error.php');
		}
	}
?>

<!-- INVERTER A DATA DE PADRAO BR PARA US -->
<?php
	function inverteData($data){
	    if(count(explode("/",$data)) > 1){
	        return implode("-",array_reverse(explode("/",$data)));
	    }elseif(count(explode("-",$data)) > 1){
	        return implode("/",array_reverse(explode("-",$data)));
	    }
	}

	$data = '17/04/2012';

	$novadata = inverteData($data);

	echo "A nova data é: " . $novadata;
?>

<!-- RETIRAR ACENTO -->
<?php
	function sanitizeString($str) {
	    $str = preg_replace('/[áàãâä]/ui', 'a', $str);
	    $str = preg_replace('/[éèêë]/ui', 'e', $str);
	    $str = preg_replace('/[íìîï]/ui', 'i', $str);
	    $str = preg_replace('/[óòõôö]/ui', 'o', $str);
	    $str = preg_replace('/[úùûü]/ui', 'u', $str);
	    $str = preg_replace('/[ç]/ui', 'c', $str);
	    // $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
	    $str = preg_replace('/[^a-z0-9]/i', '_', $str);
	    $str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
	    return $str;
	}

	// cria a url personalizavel 
	$aux = $nome.time(); //cadeia de numeros aleatorios
	$parte = explode(' ', $nome); //mais o primeiro nome do usuario
	$url_persona = sanitizeString($parte[0]) . substr(md5($aux),0,6);

	echo $url_persona;
?>

<!-- RETIRAR PONTOS E TRAÇOS -->
<?php

	// retirar pontos do cpf
	$cpf = "110.136.717-24";

	function tratarcpf($cpf){

		$cpf = str_replace('.', '', $cpf);
		$cpf = str_replace('-', '', $cpf);

		return $cpf;
	}


	echo tratarcpf($cpf);
?>

<!-- TRATAR NUMERO TELEFONE -->
<?php

	//separar ddd e retirar os tracos do telefone
	$numero = "(11) 8696-4552";

	function tratar_n($numero){

		$numero = strrev($numero);
		$numero = substr($numero ,0,strrpos($numero ,')'));
		$numero = strrev($numero);
		$numero = str_replace(' ', '', $numero);
		$numero = str_replace('-', '', $numero);

		return $numero;
	}

	function ddd($numero){

	$arr = explode(' ', $numero); // transforma a string em array.
	$arrN = array();
	// pego o array, corta e quebra
	foreach($arr as $item){
	$valor = explode(')', $item); // quebra o elemento atual em um array com duas posições,
	//onde o indice zero é a chave e o um o valor em $arrN
	$arrN[$valor[0]] = $valor[0];
	}

	foreach ($arrN as $value) {
		if($value[1] == "0"){
			return $value[2] . $value[3];
		}else{
			return $value[1] . $value[2];
		}
		break;
	}

	}

	echo "numero sem espaco e traco = " . tratar_n($numero) . "<br/>";

	echo "ddd = " . ddd($numero) . "<br/>";
?>

