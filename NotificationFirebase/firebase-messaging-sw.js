/*
	o script EM SI, foi importado do github do firebase
*/
// importa o script do firebase, necessario para a notificacao apenas
importScripts('https://www.gstatic.com/firebasejs/5.4.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/5.4.0/firebase-messaging.js');
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
// NOTIFICACAO EM SEGUNDO PLANO
messaging.setBackgroundMessageHandler(function(payLoad) {
	console.log('[firebase-messaging-sw.js] Received background message ', payLoad);
	// Customize notification here
	// payLoad.data.<dados>, recebe do firebase, o que foi enviado via php, os dados
	var notificationTitle = payLoad.data.title; // titulo
	// dados em opcoes
	var notificationOptions = {
		body: payLoad.data.body, // corpo
		icon: payLoad.data.icon, // icone
		badge: payLoad.data.badge, // icone da bandeija (mobile)
		// acoes, cliques e opcoes, se necessario (ps: se houver acoes,lembre-se de por uma virgula na opcao acima)
		actions:[
			{
				action: payLoad.data.action,
				title: payLoad.data.action_title,
				icon: payLoad.data.action_icon
			},
			{
				action: payLoad.data.action1,
				title: payLoad.data.action_title1,
				icon: payLoad.data.action_icon1
			}
		],
		requireInteraction: true // nao vai sair ate que o usuario interaja com a notificacao
	};
	// link
	Click = payLoad.data.click_action;

	// executa a notificacao
	return self.registration.showNotification(notificationTitle,notificationOptions);
});
// [END background_handler]
// evento que verifica o ONCLICK, fecha a notificacao E verifica se a pagina ja esta aberta
self.addEventListener('notificationclick', function(event) {
	/**** START notificationFocusWindow ****/
	switch( event.action ){
		case 'Open':
			open_link(event,Click);
		break;

		case 'Close':
			// fecha a notificacao
			event.notification.close();
		break;

		default:
			// fecha a notificacao
			event.notification.close();
		break;
	}
	// fecha a notificacao
	event.notification.close();
	// event.waitUntil(self.clients.openWindow("https://www.google.com.br")); // forma de onclick simples
});

/**** FUNCAO PARA VERIFICAr SE A PAGINA JA ESTA ABERTA, PARA DAR FOCO OU ABRIR UMA NOVA ABA/JANELA ****/
function open_link(event,link) {
	const urlToOpen = new URL(link, self.location.origin).href; // a pagina precisa ser direto dos arquivos, link com https/caminho direto, nao funciona
	/**** END urlToOpen ****/
	/**** START clientsMatchAll ****/
	const promiseChain = clients.matchAll({
		type: 'window',
		includeUncontrolled: true
	})
	/**** END clientsMatchAll ****/
	/**** START searchClients ****/
	.then((windowClients) => {
		let matchingClient = null;
		for (let i = 0; i < windowClients.length; i++) {
			const windowClient = windowClients[i];
			if (windowClient.url === urlToOpen) {
				matchingClient = windowClient;
				break;
			}
		}
		if (matchingClient) {
			return matchingClient.focus();
		}else{
			return clients.openWindow(urlToOpen);
		}
	});
	/**** END searchClients ****/
	return event.waitUntil(promiseChain);
}
/**** VERIFICA SE A PAGINA JA ESTA ABERTA, PARA DAR FOCO OU ABRIR UMA NOVA ABA/JANELA ****/