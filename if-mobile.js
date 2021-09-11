/* Via JAVASCRIPT, tentarei pegar se é dispositivo movel ou desk */
if( !( navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i) ) ){
	//  NÃO É MOBILE
}else{
	// É MOBILE!
}

// var IOS pega o dispositivo da apple
var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
// var mobile pega se e mobile
var mobile = navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i);

// se iOS for true = usando iphone, se nao usando android
if ( iOS && mobile ) {
	// É MOBILE E É IOS
}else{

}