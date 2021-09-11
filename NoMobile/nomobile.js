// SCRIPT PARA IMPEDIR SITE DE MOSTRAR CONTEUDO EM DISPOSITIVO MOBILE OU DESK QUE SEJAM MENOR QUE 1000PX
// CASO JQUERY NAO FUNCIONE OU OUTRO ERRO
// js
var tamanho = 999; // escolha o tamanho em que nao sera mostrado o conteudo
if (window.jQuery == undefined){
	window.onresize = function(event) {
		if(window.innerWidth <= tamanho) { // se for menor que 1000px nao mostra o conteudo e bloq
			// var
			var div_bodyjs = document.getElementsByTagName("BODY")[0];
			var nomobilejs = document.querySelectorAll('.nomobile')[0];
			// remove o conteudo do html
			div_bodyjs.parentNode.removeChild(div_bodyjs);
			// exibi msg
			nomobilejs.style.display = "block";
		}
	} // fim onresize
} // fim if jquery erro
// jquery
$(document).ready(function () {
	$(window).resize(function(){
		if(window.innerWidth <= tamanho) { // se for menor que 1000px nao mostra o conteudo e bloq
			// var
			var div_body = $(document.body);
			var nomobile = $('.nomobile');
			// remove o conteudo do html
			div_body.remove();
			// exibi msg
			nomobile.fadeIn();
		}
	});
}); // fim document.ready

/* Via JAVASCRIPT, tentarei pegar se é dispositivo movel ou desk
se for pc (portanto diferente de qualquer outro dispositivo), aparece o titulo nos btns do rodape */
if( !( navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i) ) ){
	// nao é mobile
}else{
	if(window.innerWidth <= tamanho) { // se for menor que 1000px nao mostra o conteudo e bloq
		// var
		var div_bodyjs = document.getElementsByTagName("BODY")[0];
		var nomobilejs = document.querySelectorAll('.nomobile')[0];
		// remove o conteudo do html
		div_bodyjs.parentNode.removeChild(div_bodyjs);
		// exibi msg
		nomobilejs.style.display = "block";
	}
}