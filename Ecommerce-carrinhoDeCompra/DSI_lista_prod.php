<?php
	// charset
	header('Content-Type: text/html; charset=utf-8');
	// conection
	// include_once('conection.php');
	// include_once('filter_prod.php');

	while( $linha_db = mysqli_fetch_assoc( $query_filter ) ){
	// var
	$id_prod = $linha_db['id_prod'];
	$nome_prod = $linha_db['nome_prod'];
	$qtd_prod = $linha_db['qtd_prod'];
	$preco_prod = $linha_db['preco_prod'];
?>
	<!-- nome -->
	<label style="display: flex;">
		<h4 class="enter_usu" id="<?php echo $id_prod ?>"><?php echo $nome_prod ?> - qtd: <?php echo $qtd_prod ?></h4>
		<p style="margin-left: 10px;margin-top: 20px;">R$ <?php echo number_format($preco_prod, 2, ",", ".") ?></p>
		<a href="#" id="<?php echo $id_prod ?>" class="add_prod" style="margin-left: 10px;margin-top: 20px;">add ao carrinho</a>
		<a href="#" id="<?php echo $id_prod ?>" class="del_prod" style="margin-left: 10px;margin-top: 20px;">remover</a>
	</label>
<?php
	}
	if (mysqli_num_rows($query_filter) == 0) {
		echo "<h2>Sem resultados</h2>";
	}
?>