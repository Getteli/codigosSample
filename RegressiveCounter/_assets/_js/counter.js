	// variaveis
	var aa = 2022;
	var mm = 9;
	var dd = 14;
	var hh = 22;
	var mi = 0;
	var occasion = "Casamento";
	var message_on_occasion = "É hoje";
	var countdownwidth = '510px';
	var countdownheight = '200px';
	var countdownbgcolor = '#fff';
	var opentags = '<font face="Verdana" size="5" color="#000000">';
	var closetags = '</font>';
	var montharray = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
	var crosscount = '';

	// seta as varaiveis com a data atual das variaveis acima
	function setcountdown(theyear,themonth,theday,hour,minutes) {
		yr = theyear;
		mo = themonth;
		da = theday;
		h = hour;
		m = minutes;
	}
	// funcao para comecar o contador
	function start_countdown(){
		if ( document.layers ){
			document.countdownnsmain.visibility = "show";
		}else if ( document.all || document.getElementById ){
			crosscount = document.getElementById && !document.all ? document.getElementById("countdownie") : countdownie;
			countdown();
		}
	}
	// verifica e add um zero a esquerda 
	function n_digit(number) {
		num = number.toString().length;
		if (num == 2) {
			return number;
		}else{
			return "0" + number;
		}
	}


	function countdown(){
		// variaveis
		var today = new Date();
		var todayy = today.getYear();
		if ( todayy < 1000 ){
			todayy += 1900;
		}
		var todaym = today.getMonth();
		var todayd = today.getDate();
		var todayh = today.getHours();
		var todaymin = today.getMinutes();
		var todaysec = today.getSeconds();
		var todaystring = montharray[todaym] + " " + todayd + ", " + todayy + " " + todayh + ":" + todaymin + ":" + todaysec;
		futurestring = montharray[mo-1] + " " + da + ", " + yr +" "+ h + ":" + m;
		dd = Date.parse( futurestring ) - Date.parse( todaystring );
		dday = Math.floor( dd / ( 60*60*1000*24 )*1 );
		dhour = Math.floor( ( dd % ( 60*60*1000*24 ) ) / ( 60*60*1000 )*1 );
		dmin = Math.floor( ( ( dd % ( 60*60*1000*24 ) ) % ( 60*60*1000 ) ) / ( 60*1000 )*1 );
		dsec = Math.floor( ( ( ( dd % ( 60*60*1000*24 ) ) % ( 60*60*1000 ) ) % ( 60*1000 ) ) /1000*1 );

		// se no dia da ocasiao
		if( dday <= 0 && dhour <= 0 && dmin <= 0 && dsec <= 1 && todayd == da ){
			if ( document.layers ){
				document.countdownnsmain.document.countdownnssub.document.write( opentags + message_on_occasion + closetags );
				document.countdownnsmain.document.countdownnssub.document.close();
			}else if ( document.all||document.getElementById ){
				crosscount.innerHTML = opentags + message_on_occasion + closetags;
				return;
			}
		} else if ( dday <= -1 ){ // se passou dia da ocasião
			if ( document.layers ){
				document.countdownnsmain.document.countdownnssub.document.write( opentags + " Occasion already passed! " + closetags );
				document.countdownnsmain.document.countdownnssub.document.close();
			}else if ( document.all || document.getElementById ){
				crosscount.innerHTML = opentags + " Já passou " + closetags;
				return;
			}
		} else{ // mais, se ainda não
			if (document.layers){
			document.countdownnsmain.document.countdownnssub.document.write( opentags + dday + " days, " + dhour + " hours, " + dmin + " minutes, and " + dsec + " " + occasion + closetags );
			document.countdownnsmain.document.countdownnssub.document.close();
			} else if ( document.all || document.getElementById ){
				crosscount.innerHTML = opentags + n_digit( dday ) + " dias, " + dhour + " horas, " + dmin + " minutos e " + dsec + " segundos para o <b>" + occasion + closetags;
				/*
				variaveis a retornar:

				dday - dias
				dhour - horas
				dmin - minutos
				dsec - segundos

				*/
			}
		}

		setTimeout( " countdown() " , 1000 );
	}

	// escreve na tela o span
	if ( document.all || document.getElementById ){
		document.write('<span id="countdownie" style="width:' + countdownwidth + '; background-color:' + countdownbgcolor + '"></span>');
	}

	// chama a funcao para setar as variaveis
	setcountdown(aa,mm,dd, hh, mi);

	// inicia o contador assim q a pagina carrega
	window.onload = start_countdown;