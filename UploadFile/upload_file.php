<?php

	// var
	$doc_up  = $_POST['doc_up'];
	$doc_name  = $_POST['doc_name'];

	/* funcoes necessarias para o upload de arquivos, add esse arquivo e acima add os metodos, arquivos e etc para subir o arquivo para o sistema e banco. Mude o caminho para o necessario, o return no final e o caminho do arquivo para subir ao banco. A cima com os metodos, coloque o try, pega o arquivo em base64 do front, usa a funcao base64_doc com o neme desejavel, retorna o caminho e por ai ja sabe..
	ex:
	$doc = base64_doc( $doc_up, $doc_name );
	No front, o necessario e o btn com o msm nome do arquivo js, para ser chamado, e add o script tbm. VEJA O SCRIPT
	*/
	// transformar documento base64 em um arquivo pdf e enviar para pasta
	function base64_doc( $doc, $nome_file ) {
		$doc = str_replace('data:application/pdf;base64,', '', $doc);
		$doc = str_replace('', '+', $doc);
		// remove a extensao do nome
		$nome_file = substr($nome_file, 0, strripos($nome_file, "."));
		// codifica o base64 para file
		$data = base64_decode($doc);
		// path para subir ao servidor
		$path_direct = '_assets/_usu/_docs/';
		$path_here = '../_usu/_docs/';
		// envia ao servidor
		$mime_type = finfo_buffer(finfo_open(), $data, FILEINFO_MIME_TYPE); // extract mime type
		$extension = mime2ext($mime_type); // extract extension from mime type
		$newName = uniqid ( time () ) . "_" . $nome_file .'.'. $extension; // rename file as a unique name
		$file_dir = $path_here . $newName;
		try {
			file_put_contents($file_dir, $data); // save
			// name a ser enviado ao banco
			$new_doc = str_replace($path_here, '', $file_dir); // retira
			$new_doc = $path_direct . $new_doc; // add
		}catch (Exception $e) {
			return "erro";
		}
		// Obter conteúdo do arquivo do path
		$pdf_base64_handler = fopen($file_dir,'r');
		$pdf_content = fread ($pdf_base64_handler,filesize($file_dir));
		// Escrever dados de volta ao arquivo doc
		$file = fopen ($file_dir,'w');
		fwrite ($file,$data);
		// fecha o arquivo
		fclose ($file);
		// retorna o caminho para subir ao banco
		return $doc; // retorna o caminho para enviar ao banco, a imagem é alterada pela funcao redimencionarImagem, mas o caminho/nome permanece o mesmo
	}

	// function para pegar o formato do arquivo
	function mime2ext($mime){
		$all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp","image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp","image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp","application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg","image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],"wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],"ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg","video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],"kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],"rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application","application\/x-jar"],"zip":["application\/x-zip","application\/zip","application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],"7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],"svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],"mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],"webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],"pdf":["application\/pdf","application\/octet-stream"],"pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],"ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office","application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],"xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],"xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel","application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],"xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo","video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],"log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],"wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],"tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop","image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],"mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar","application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40","application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],"cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary","application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],"ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],"wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],"dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php","application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],"swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],"mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],"rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],"jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],"eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],"p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],"p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
		$all_mimes = json_decode($all_mimes,true);
		foreach ($all_mimes as $key => $value) {
			if(array_search($mime,$value) !== false) return $key;
		}
		return false;
	}