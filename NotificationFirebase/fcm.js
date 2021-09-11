// dados do firebase
// Initialize Firebase
var config = {
	apiKey: "AIzaSyDvdJLhorIZUqoL-41R6-AOE2BNiZVNnMg",
	authDomain: "padrao-6b954.firebaseapp.com",
	databaseURL: "https://padrao-6b954.firebaseio.com",
	projectId: "padrao-6b954",
	storageBucket: "padrao-6b954.appspot.com",
	messagingSenderId: "1041998696883"
};
// inicializa com as configuracaoes a cima
firebase.initializeApp(config);
// variavel que recebe do firebase, com a funcao de notificacao
var messaging = firebase.messaging();

// permissao para enviar notificacao ao usuario
messaging.requestPermission().then(function() {
	console.log('Permissão de notificação concedida.');
	// TODO(developer): Retrieve an Instance ID token for use with FCM.
	if ( isTokenSentToServer() ){
		console.log('Token já salvo.');
	}else{
		getRegToken();
	}
}).catch(function(err) {
	console.log('Não é possível obter permissão para notificar. ', err);
});
// pega o token do usuario e registra
function getRegToken() {
	messaging.getToken().then(function(currentToken) {
		if (currentToken){
			saveToken(currentToken); // salva o token (via ajax) no banco de dados
			console.log(currentToken);
			setTokenSentToServer(true); // salva em cache true = existe
		}else{
			// Show permission request.
			console.log('Nenhum token de ID da instância disponível. Solicitar permissão para gerar um.');
			setTokenSentToServer(false); // salva em cache false = nao existe
		}
	}).catch(function(err) {
		console.log('Ocorreu um erro ao recuperar o token. ', err);
		setTokenSentToServer(false); // salva em cache false = nao existe
	});
}
// salva o token em cache para nao salvar outro igual
function setTokenSentToServer(sent) {
	window.localStorage.setItem('sentToServer', sent ? 1 : 0);
}
// verifica se o token ja existe E esta salvao em cache
function isTokenSentToServer() {
	return window.localStorage.getItem('sentToServer') == 1;
}
// funcao para salvar o token no banco de dados, via ajax para o banco de dados
function saveToken(currentToken){
	$.ajax({
		url: '_assets/_php/saveToken.php', // arquivo
		data: ({
			token: currentToken // token do usuario
		}),
		type: "POST",
		beforeSend: function(){
			// carregando
		}
	}).done(function(result){
		if ( result == "salvo" ) {
			// feito
			console.log('token salvo..');
		}else{
			// erro
			console.log(result);
		}
	}).fail(function(jqXHR, ajaxOptions, thrownError){
		console.log('falha ao enviar via ajax o token para o banco de dados');
	});
}
// NOTIFICACAO EM PRIMEIRO PLANO
messaging.onMessage(function(payLoad) {
	console.log('mensagem recebida. ', payLoad ); // informa
	// dados da notificacao
	n_title = payLoad.data.title; // titulo
	// dados
	n_option = {
		body: payLoad.data.body, // corpo
		icon: payLoad.data.icon, // icone
		badge: payLoad.data.badge, // icone da bandeija para mobile
		requireInteraction: true // nao vai sair ate que o usuario interaja com a notificacao
	};
	// link
	Click = payLoad.data.click_action;

	// se for mobile exibi a notificacao usando este codigo, pois no mobile a new Notification nao funciona
	if ( verifyMobile() ) {

		// navigator.serviceWorker.register('service-worker.js');
		navigator.serviceWorker.ready.then(function(registration) {
			registration.showNotification(n_title,n_option);
		});

	}else{

		var notification = new Notification(n_title,n_option);
		notification.onclick = function(event) {
			// executa a funcao do click, abrir link, fechar e tce
			not_click_desk(notification,event,Click);
		}

	} // fim verificacao
});

// manipula a notificacao DESK
function not_click_desk(notification,event,link) {
	/**** START notificationFocusWindow ****/
	// abre o link
	window.open(link, '_blank');
	// fecha a notificacao
	notification.close();
	// event.waitUntil(self.clients.openWindow("https://www.google.com.br")); // forma de onclick simples
}

// verifica se e mobile ou nao
function verifyMobile() {
	/* Via JAVASCRIPT, tentarei pegar se é dispositivo movel ou desk */
	if( navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i) ){
		// É MOBILE!
		return true;
	}else{
		//  NÃO É MOBILE
		return false;
	}
}