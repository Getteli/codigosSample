/*
 Este Script JS devera ser add a pagina que deseja mostrar o banner de instalacao em IOS, e deve ser colocado abaixo do script addtohomescreen.js
 Criado por: GETTELI.01
*/
// var IOS pega o dispositivo da apple
var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
// var mobile pega se e mobile
var mobile = navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i);

// se iOS for true = usando iphone, se nao usando android
if ( iOS && mobile ) {
	addToHomescreen({
		detectHomescreen: true,
		modal: true,
		lifespan: 0,
		displayPace: 1440,
		message: 'Para adicionar este app à tela de início: clique <span class="ios7ai ath-ai"></span> e então <strong>Tela de início</strong>.',
		startDelay: 1
	});
}