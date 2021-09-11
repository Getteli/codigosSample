<?php
	// charset
	header('Content-Type: text/html; charset=utf-8');
	// conexao com o bd
	include_once('_assets/_php/conection.php');
	// conexao com o cache
	include_once('_assets/_php/cache.php');
	// alert
	include_once('_assets/_php/alert_personalizado.php');
	// details user
	include_once('_assets/_php/details.php');
	// filtro de busca
	include_once('_assets/_php/filter_prod.php');

	// lista de categorias
	$query_cat = mysqli_query($conn, " SELECT 
		id_cat,nome_cat
		FROM categorias ");

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Sacolao dos Irmãos - Comprar</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="Descricao">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="_assets/_img/.png">
	<link rel="apple-touch-icon" href="_assets/_img/.png">
	<meta name="theme-color" content="#">
	<meta name="mobile-web-app-capable" content="yes">
	<!-- manifest / sw-->
	<link rel="manifest" href="manifest.json">
	<script type="text/javascript" src="service-worker.js"></script>
	<link rel="stylesheet" type="text/css" href="_assets/_css/alert_personalizado.css">
	<!-- A2HS -->
	<link rel="stylesheet" type="text/css" href="_assets/_css/addtohomescreen.css">
	<script type="text/javascript" src="_assets/_js/addtohomescreen.js"></script>
	<style type="text/css">
		label{
			display: block;
		}
		#container_list{
			border: 1px solid black;
			width: 100%;
			height: 200px;
			display: block;
			overflow-y: scroll;
		}
		h4{
			/*background-color: lightblue;*/
		}
	</style>
</head>
<body>
<a href="carrinho.php">Carrinho</a>
<br/>
<a href="inicio.php">Inicio</a>
	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="GET" class="center_new flex">
		<input type="text" name="parameter" placeholder="digite o que deseja procurar">
		<button type="submit">Pesquisar</button>
	</form>
	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="GET" class="center_new flex">
		<select name="tipo" class="" onchange="this.form.submit()">>
			<option value="">Categoria</option>
			<?php
			while( $linha_cat = mysqli_fetch_assoc( $query_cat ) ){
				$id_cat = $linha_cat['id_cat'];
				$nome_Cat = $linha_cat['nome_cat'];
			?>
			<option value="<?php echo $id_cat ?>" <?php if ( $tipo == $id_cat ) { echo 'selected="selected"'; } ?> ><?php echo $nome_Cat ?></option>
			<?php } ?>
		</select>
	</form>
	<div id="container_list">
	<div class="" id="grid_list">
		<!-- busca usuario no banco -->
		<?php
			include_once('_assets/_php/DSI_lista_prod.php');
		?>
	</div>
	</div>

<!-- script -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="_assets/_js/alert_personalizado.js"></script>
<!-- A2HS -->
<script type="text/javascript" src="_assets/_js/addtohomescreen.js"></script>
<script type="text/javascript" src="_assets/_js/a2hs.js"></script>
<script type="text/javascript">
	$(document).ready(function () {

		// var
		var cont = $('#container_list');
		var add_prod = $('.add_prod');
		var del_prod = $('.del_prod');

		// carregar dados
		function loadMoreData(last_id){
			$.ajax({
				url: '_assets/_php/DSI_prod.php',
				data: ({
					last_id: last_id,
					parameter: '<?php echo $parameter ?>',
					tipo: '<?php echo $tipo ?>'
				}),
				type: "GET",
				beforeSend: function(){
					// $('.ajax-load').show();
				}
			}).done(function(data){
				if ( data == 'false' ) {
					// termina o resultado
					alert_personalizado('Lista','Acabou a lista', '', false, false, false);
				}else{
					// alert(data);
					// $('.ajax-load').hide();
					$("#grid_list").append(data);
					// alert(data);
				}
			}).fail(function(jqXHR, ajaxOptions, thrownError){
				// alert('FAIÔ');
			});
		}
		// ao rolar para o final da div, chamar mais linhas via ajax
		jQuery(function($) {
			cont.on('scroll', function() {
				if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
					var last_id = $(".enter_usu:last").attr("id");
					// alert(last_id);
					loadMoreData(last_id);
				}
			})
		});

		// add produto ao carrinho
		function add_carrinho(id) {
			// alert(e.target.result);
			//Início do Comando AJAX
			$.ajax({
				url: '_assets/_php/add_carrinho.php',
				// Aqui você deve preencher o tipo de dados que será lido,
				type:'POST',
				data: ({
					action: 'add',
					id: id
				}),
				// SUCESS é referente a função que será executada caso
				// ele consiga ler a fonte de dados com sucesso.
				// O parâmetro dentro da função se refere ao nome da variável
				// que você vai dar para ler esse objeto.
				success: function(resposta){
					// Confere se houve erro e o imprime
					if(resposta == "false"){
						alert_personalizado("ERRO","Erro ao add produto no carrinho, tente novamente ou entre em contato com o suporte","ok");
						return false;
					}else{
						alert_personalizado("concluido","Feito","continuar");
						return true;
					}
				}
			});
		}

		add_prod.on('click', function () {
			add_carrinho(this.id);
		});

		// remover produto do carrinho
		function del_carrinho(id) {
			// alert(e.target.result);
			//Início do Comando AJAX
			$.ajax({
				url: '_assets/_php/del_carrinho.php',
				// Aqui você deve preencher o tipo de dados que será lido,
				type:'POST',
				data: ({
					action: 'remove',
					id: id
				}),
				// SUCESS é referente a função que será executada caso
				// ele consiga ler a fonte de dados com sucesso.
				// O parâmetro dentro da função se refere ao nome da variável
				// que você vai dar para ler esse objeto.
				success: function(resposta){
					// Confere se houve erro e o imprime
					if(resposta == "false"){
						alert_personalizado("ERRO","Erro ao remover produto do carrinho, tente novamente ou entre em contato com o suporte","ok");
						return false;
					}else{
						alert_personalizado("concluido","Feito","continuar");
						return true;
					}
				}
			});
		}

		del_prod.on('click', function () {
			del_carrinho(this.id);
		});

	});
</script>