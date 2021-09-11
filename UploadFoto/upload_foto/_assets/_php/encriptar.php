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