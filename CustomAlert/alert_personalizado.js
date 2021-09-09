// titulo, mensagem, botão, se quer recarregar a pagina, se quer os parametros ao recarregar a pagina, se quer botão
function alert_personalizado(title, msg, btn, reload, noparameter, hasbtn, time) {
	// fundo
	var bg_div = document.createElement("div");
	bg_div.setAttribute("id", "bg_alert_person");
	document.body.appendChild(bg_div); // add ao body
	// div alert
	var alert_div = document.createElement("div");
	alert_div.setAttribute("id", "alert_person");
	// btn X
	var btn_x = document.createElement("span");
	btn_x.setAttribute("id", "close_alert");
	btn_x.innerHTML = "&#10006;";
	alert_div.appendChild(btn_x); // add ao alerta
	// body alert
	var alert_body = document.createElement("div");
	alert_body.setAttribute("id", "body_alert");
	alert_div.appendChild(alert_body); // add ao alerta
	// title
	var title_body = document.createElement("h3");
	title_body.innerHTML = title;
	alert_body.appendChild(title_body); // add ao alerta
	// msg
	var msg_body = document.createElement("p");
	msg_body.innerHTML = msg;
	alert_body.appendChild(msg_body); // add ao alerta
	if (hasbtn || hasbtn == null) {
		// button alert
		var alert_btn = document.createElement("button");
		alert_btn.setAttribute("id", "confirm_alert");
		alert_btn.innerHTML = btn;
		alert_div.appendChild(alert_btn); // add ao alerta
		alert_btn.focus(); // ao aparecer, dar foco no btn
	}
	bg_div.appendChild(alert_div); // add ao body
	// funcao com o necessario para fechar o alert
	function close() {
		alert_div.parentNode.removeChild(alert_div);
		bg_div.parentNode.removeChild(bg_div);
		if (reload && noparameter) {
			window.location = window.location.href.split("?")[0];
		}else if (reload) {
			window.location.reload();
		}
	}
	// clicks
	if (hasbtn || hasbtn == null) {
		alert_btn.addEventListener('click', function alert_btn(){
			close();
		})
	}
	btn_x.addEventListener('click', function btn_x(){
		close();
	})
	// se existir time, entao fecha sozinho no tempo indicado
	if (time || time !== null) {
		setTimeout(function(){ close(); }, time);
	}
}