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
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Sacolao dos Irmãos - Carrinho</title>
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
<a href="produto.php">Produto</a>
<br/>
<a href="inicio.php">Inicio</a>
<hr/>
<?php

if ( count( $_SESSION['itens'] ) == 0 ) {
	echo "Carrinho vazio. <br/>
	<a href='produto.php'>comprar</a>";
}else{
	foreach ($_SESSION['itens'] as $produto) {
		// var
		$id = $produto['id'];
		$qtd = $produto['qtd'];
		$nome_prod = $produto['nome'];
		$preco_prod_t = $produto['preco_t'];
		$preco_prod = $produto['preco'];
		// pesquisa o produto - se necessario
		// $query_get = mysqli_query($conn, " SELECT 
		// 	id_prod,nome_prod,id_cat,qtd_prod,preco_prod,img_prod
		// 	FROM estoque
		// 	WHERE id_prod = '$id' ");
		// $linha_carrinho = mysqli_fetch_assoc( $query_get );
		// // var
		// $nome_prod = $linha_carrinho['nome_prod'];
		// $preco_prod = $linha_carrinho['preco_prod'] * $qtd;
		// total a pagar
		$total += $preco_prod_t;
		// salva o total no cache
		$_SESSION['total'] = $total;
		// exibi
		echo 'Nome: ' .$nome_prod. ' | ';
		echo 'Quantidade: ' .$qtd. ' | ';
		echo 'Preço: R$ ' . number_format($preco_prod_t, 2, ",", "."). ' | ';
		echo '(unid. R$ ' . number_format($preco_prod, 2, ",", "."). ') | ';
		echo ' <a href="#" class="del_prod" id="'.$id.'">Remover item</a> <br/>';
	}
		echo "<hr/>";
		echo "Total: " . number_format($total, 2, ",", ".");
		echo "<hr/>";
}
?>
<form action="finalizar.php" method="POST">
	<label>
		<input type="text" name="desc_ped" placeholder="adicione alguma informação a mais, se desejar.">
	</label>
	<button type="submit">Finalizar</button>
</form>

<!-- script -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="_assets/_js/alert_personalizado.js"></script>
<!-- A2HS -->
<script type="text/javascript" src="_assets/_js/addtohomescreen.js"></script>
<script type="text/javascript" src="_assets/_js/a2hs.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		// var
		var del_prod = $('.del_prod');

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
						alert_personalizado("Feito","Produto removido do carrinho","", true, false, false, true);
						return true;
					}
				}
			});
		}

		del_prod.on('click', function () {
			// alert(this.id);
			del_carrinho(this.id);
		});

	});
</script>