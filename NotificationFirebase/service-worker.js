/* SERVICE WORKER CRIADO E TESTADO POR DOUGLAS (@Getteli_01 OR Getteli.01) - ILION TECNOLOGIA */

if ('serviceWorker' in navigator) {
	window.addEventListener('load', function() {
		// registre com o nome firebase-messaging-sw.js se for usar NOTIFICACAO FIREBASE E registre com o nome normal service-worker.js para WEB-APP
		navigator.serviceWorker.register('firebase-messaging-sw.js').then(function(registration) {
			// Registro foi bem sucedido
			// console.log('ServiceWorker registration successful with scope: ', registration.scope);
			// alert('ServiceWorker registration successful with scope: ', registration.scope);
		}, function(error) {
			// registração falhou :(
			// console.log('ServiceWorker registration failed: ', error);
			// alert('ServiceWorker registration failed: ', error);
		});
		navigator.serviceWorker.register('service-worker.js').then(function(registration) {
			// Registro foi bem sucedido
			// console.log('ServiceWorker 2 registrado!');
			// alert('ServiceWorker registration successful with scope: ', registration.scope);
		}, function(error) {
			// registração falhou :(
			// console.log('ServiceWorker registration failed: ', error);
			// alert('ServiceWorker registration failed: ', error);
		});
	});
}

/* Copyright 2015 Google Inc. Todos os direitos reservados. Licenciado sob a Licença Apache, Versão 2.0 (a "Licença"); você não pode usar este arquivo, exceto em conformidade com a Licença. Você pode obter uma cópia da Licença em http://www.apache.org/licenses/LICENSE-2.0 A menos que exigido pela lei aplicável ou acordado por escrito, o software distribuído sob a Licença é distribuído em uma base "COMO ESTÁ", SEM GARANTIAS OU CONDIÇÕES DE QUALQUER TIPO, expressas ou implícitas. Veja a Licença para o idioma específico que governa as permissões e limitações sob a licença. */

'use strict';

/* Incrementar CACHE_VERSION inicia o evento de instalação e força o cache anterior recursos a serem armazenados em cache novamente. */

const CACHE_VERSION = 1;
let CURRENT_CACHES = {
	offline: 'offline-v' + CACHE_VERSION
};

const OFFLINE_URL = 'offline.html';

function createCacheBustedRequest(url) {
	let request = new Request(url, {cache: 'reload'});
	/* Veja https://fetch.spec.whatwg.org/#concept-request-mode Isso ainda não é suportado no Chrome a partir do M48, por isso, precisamos verificar explicitamente se a opção cache: 'reload' tivesse algum efeito. */
	if ('cache' in request) {
		return request;
	}
	// Se {cache: 'reload'} não tiver efeito, adicione um parâmetro de URL para impedimento de cache.
	let bustedUrl = new URL(url, self.location.href);
	bustedUrl.search += (bustedUrl.search ? '&' : '') + 'cachebust=' + Date.now();
	return new Request(bustedUrl);
}

self.addEventListener('install', event => {
	event.waitUntil(
		/* Não podemos usar cache.add () aqui, pois queremos que OFFLINE_URL seja a chave de cache, mas o URL real que acabamos solicitando pode incluir um parâmetro de impedimento de cache. */
		fetch(createCacheBustedRequest(OFFLINE_URL)).then(function(response) {
			return caches.open(CURRENT_CACHES.offline).then(function(cache) {
				return cache.put(OFFLINE_URL, response);
			});
		})
	);
});

self.addEventListener('activate', event => {
	/* Exclui todos os caches que não são nomeados em CURRENT_CACHES. Embora haja apenas um cache neste exemplo, a mesma lógica manipulará o caso em que existem vários caches com versão. */
	let expectedCacheNames = Object.keys(CURRENT_CACHES).map(function(key) {
		return CURRENT_CACHES[key];
	});
	event.waitUntil(
		caches.keys().then(cacheNames => {
			return Promise.all(
				cacheNames.map(cacheName => {
					if (expectedCacheNames.indexOf(cacheName) === -1) {
						/* Se esse nome de cache não estiver presente na matriz de nomes de cache "esperados", depois apaga-o. console.log ('Excluindo o cache de data:', cacheName); */
						return caches.delete(cacheName);
					}
				})
			);
		})
	);
});

self.addEventListener('fetch', event => {
	/* Nós só queremos chamar event.respondWith () se este for um pedido de navegação para uma página HTML. request.mode de 'navigate' infelizmente não é suportado no Chrome versões anteriores a 49, precisamos incluir um fallback menos preciso, que verifica uma solicitação GET com um cabeçalho Accept: text / html. */
	if (event.request.mode === 'navigate' || (event.request.method === 'GET' && event.request.headers.get('accept').includes('text/html'))) {
		// console.log ('Manipulando evento para', event.request.url);
		event.respondWith(
			fetch(event.request).catch(error => {
				/*O catch só é acionado se fetch () lançar uma exceção, o que provavelmente acontece devido ao servidor estar inacessível. Se fetch () retornar uma resposta HTTP válida com um código de resposta no 4xx ou no 5xx range, o catch () NÃO será chamado. Se você precisar de manuseio personalizado para 4xx ou 5xx erros, consulte https://github.com/GoogleChrome/samples/tree/gh-pages/service-worker/fallback-response console.log('Fetch falhou; retornando a página off-line. ', error); */
				return caches.match(OFFLINE_URL);
			})
		);
	}
	/* Se a condição if () for falsa, esse manipulador de busca não interceptará a solicitação. Se houver outros manipuladores de busca registrados, eles terão a chance de ligar event.respondWith (). Se nenhum manipulador de busca chamar event.respondWith (), a solicitação será manuseado pelo navegador como se não houvesse envolvimento de trabalhadores de serviços. */
});

/* A PARTIR DAQUI SERVE PARA 68 A CIMA. O chrome ver 67 para baixo, mostra o banner ao usuario automaticamente (que atenda aos criterios), para o 68 o chrome dev diz que não exibirá automaticamente, mas haverá o beforeinstallprompt para chamar uma funcao de um elemento do front para mostrar ao usuario que ele pode add a tela inicial. NO MOMENTO (21/07), MESMO SEM A FUNÇÃO beforeinstallprompt o banner é exibido ao usuario (tanto na versão aual - 67, quanto na versão beta posterior - 68) */

// variavel para armazenar o e do beforeinstallprompt para o btn
/*var deferredPrompt;

window.addEventListener('beforeinstallprompt', function(e) {
	e.preventDefault();
	alert('primeiro');
	showAddToHomeScreen();
	deferredPrompt = e;
	console.log('pode instalar');
	alert('pode instalar');
});*/

// function para mostrar o btn de add a tela inicio, para o usuario
/*function showAddToHomeScreen() {
	var btnAdd = document.querySelector('#btnAdd');
	btnAdd.style.display = "block";
	btnAdd.addEventListener("click", addToHomeScreen);
}*/

// function para o click do btn de add a tela inicio
/*function addToHomeScreen() {
	var btnAdd = document.querySelector('#btnAdd');
	btnAdd.style.display = 'none';
	deferredPrompt.prompt();
	deferredPrompt.userChoice.then(function(choiceResult){
		if (choiceResult.outcome === 'accepted') {
			console.log('User accepted the A2HS prompt');
		} else {
			console.log('User dismissed the A2HS prompt');
		}
		deferredPrompt = null;
	});
}*/