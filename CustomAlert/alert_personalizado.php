<?php
	// charset
	header('Content-Type: text/html; charset=utf-8');
	// recebe as variaveis do erro ou info
	$erro = filter_input(INPUT_GET , 'erro');
	$info = filter_input(INPUT_GET , 'info');
	// var
	$title_a = '';
	$txt_a = '';
	$btn_a = '';
	$nr = '';
	// verifica se ha erro
	if ( !empty( $erro ) || !empty( $info ) ) {
		// verifica o motivo (a cada novo erro, apenas add o seu proprio case)
		if ( !empty( $erro ) ) {
		switch ( $erro ) {
			case 'tipo de erro':
				$title_a = 'erro';
				$txt_a = 'Desconhecido';
				$btn_a = 'continuar';
				$nr = false; // false = no refresh / true = refresh
				break;

			default:
				$title_a = 'erro';
				$txt_a = 'Desconhecido';
				$btn_a = 'continuar';
				$nr = true; // false = no refresh / true = refresh
				break;
		}
		}
		// verifica o motivo (a cada novo info, apenas add o seu proprio case)
		if ( !empty( $info ) ) {
		switch ( $info ) {
			case 'tipo de info':
				$title_a = 'informação';
				$txt_a = 'Desconhecido';
				$btn_a = 'continuar';
				$nr = false; // false = no refresh / true = refres
				break;

			default:
				$title_a = 'informação';
				$txt_a = 'Desconhecido';
				$btn_a = 'continuar';
				$nr = true; // false = no refresh / true = refresh
				break;
		}
		}
?>
<!-- alerta -->
<div id="bg_alert_person">
	<div id="alert_person">
		<span id="close_alert">&#10006;</span>
		<div id="body_alert">
			<h3><?php echo $title_a ?></h3>
			<p><?php echo $txt_a ?></p>
		</div>
		<button id="confirm_alert"><?php echo $btn_a ?></button>
	</div>
</div>
<!-- script -->
<script type="text/javascript">
	var alert_div = document.getElementById('alert_person');
	var bg_div = document.getElementById('bg_alert_person');
	var btn_alert = document.getElementById('confirm_alert');
	var x_alert = document.getElementById('close_alert');

	// ------------------------------------------------------------ BUTOES ENTRAR ----------------------------------------------------------------

	btn_alert.addEventListener('click', function close_alert(){
		alert_div.parentNode.removeChild(alert_div);
		bg_div.parentNode.removeChild(bg_div);
		<?php
		// se for true, da refresh
		if ($nr || $nr == '') {
		?>
			window.location = window.location.href.split("?")[0];
		<?php } ?>
	})
	x_alert.addEventListener('click', function close_alert(){
		alert_div.parentNode.removeChild(alert_div);
		bg_div.parentNode.removeChild(bg_div);
		<?php
		// se for true, da refresh
		if ($nr || $nr == '') {
		?>
			window.location = window.location.href.split("?")[0];
		<?php } ?>
	})
	btn_alert.focus(); // ao aparecer, dar foco no btn
</script>
<?php
	} // fecha o if
?>