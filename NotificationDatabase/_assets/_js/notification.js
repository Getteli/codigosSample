$(document).ready(function() {

	// var vazia, para armazenar o resultado 
	var notificationBefore, notificationNow;
	var title = document.title;
	var viewScreen = $('#n-notification');
	var bodyhtml = document.getElementsByTagName('body')[0];
	var audio = new Audio('_assets/_sound/notification.mp3') || new Audio('_assets/_sound/notification.ogg'); // audio da notificacao

	// PEGA O NUMERO DA NOTIFICACAO ANTES DE ATUALIZAR
	function beforeNot() {
		notificationBefore = viewScreen.html();
	}

	// FUNÇÃO PARA O TITULO 
	function getNotification() {
		$.get("_assets/_php/get_notification.php", function(resultado){

			if (resultado == "" || resultado == null) {
				var newTitle = ''+ title; // atualiza titulo
				document.title = newTitle; // add a alteracao do titulo
				viewScreen.html(resultado); // coloca o resultado no mensageiro
			}else{
				var newTitle = '(' + resultado + ') ' + title; // atualiza o titulo
				document.title = newTitle; // add a alteracao do titulo
				viewScreen.html(resultado); // coloca o resultado no mensageiro
				notificationNow = resultado;
				// se agora for maior que antes E agora for diferente de 0 E agora for diferente de antes, faca
				if ( (notificationNow > notificationBefore) &&
					(notificationNow != 0) &&
					(notificationNow != notificationBefore)
				){
					audio.play(); // toca o som de notificacao
					notification(); // exibi a notificacao
				}
			}
		})
	}

	// atualiza para pegar a notificacao do bd
	function newUpdate() {
		update = setInterval(getNotification, 5000);
	}

	// exibi a notificacao
	function notification() {
		// background
		var bg_div = document.createElement("div");
		bg_div.setAttribute("class", "bg_notification_person");

		// notification
		var not = document.createElement("div");
		not.setAttribute("class", "notification_person");	

		// btn X
		var btn_x = document.createElement("span");
		btn_x.setAttribute("class", "close_notification");
		btn_x.setAttribute("id", "close_notification");
		btn_x.innerHTML = "&#10006;";

		// body notification
		var not_body = document.createElement("div");
		not_body.setAttribute("class", "body_notification");

		// title
		var title_body = document.createElement("h3");
		// title_body.innerHTML = 'title';

		// msg
		var msg_body = document.createElement("p");
		msg_body.innerHTML = 'Nova notificação';
		
		// add um ao outro
		not_body.appendChild(title_body); // add ao not
		not_body.appendChild(msg_body); // add ao not
		not.appendChild(btn_x); // add ao not
		not.appendChild(not_body); // add ao not
		bg_div.appendChild(not); // add ao not
		document.body.appendChild(bg_div);

		// adiciona a funcao para poder excluir a notificacao em 3 segundos apos aparecer
		setTimeout(function () {
			btn_x.addEventListener('click', function close_notification(){
				close(bg_div);
			});
		}, 3000);

		// a notificacao aparece apos 10 segundos
		setTimeout(function () {
			close(bg_div);
		}, 10000);

	}

	// fecha a notificacao, remove do corpo
	function close(bg_div) {
		bg_div.parentNode.removeChild(bg_div);
	}

	// EXECUTA AS FUNCOES NECESSARIAS
	beforeNot();
	getNotification();

	setInterval(beforeNot, 700);
	setInterval(getNotification, 900);

	bodyhtml.onload = newUpdate;
});