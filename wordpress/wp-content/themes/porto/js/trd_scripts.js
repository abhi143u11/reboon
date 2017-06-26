var $ = jQuery.noConflict();

suggest_count = 0;
input_initial_value = '';
suggest_selected = 0;
var res_window = '';		// окно вывода поиска по размерам
var psa_loader = '';
// данные для ввода размеров телефона для поиска
img_phoneForInputSizes = '<div class="new-psa-searcher"><p class="info-mess-searcher">'
                         //+ trd_getPhraseFromVocabulary('Kannst du keine passende Hülle für dich finden? Fülle die Felder aus:')
                         + 'Kannst du keine passende Hülle für dich finden? Fülle die Felder aus:'
                         + '</p><div class="mobile-bg"><div class="input2">'
						 + '<input type="text" placeholder="Höhe" id="height" class="ps-trd-input">'
						 + '<span>mm</span></div><div class="input3"><input type="text" placeholder="Tiefe" id="depth" class="ps-trd-input">'
						 + '<span>mm</span></div><div class="input1"><input type="text" placeholder="Breite" id="width" class="ps-trd-input">'
						 + '<span>mm</span></div><a href="#" id="trd_search_size_link" class="btn btn-right" onClick="trd_picking_by_sizes(); return false;">Finde dein Hülle</a></div></div>';
						 
/* TRD ----------------------------SEARCH-----------------------------------*/
$(document).ready(function(){
	var arrPhones;				// массив для хранения всех телефонов
	trd_getAllPhones();
	
	// читаем ввод с клавиатуры
  $("#InputSearch").keyup(function(e){	  	  
    e.preventDefault();
	
	if($('.result').hasClass('trd_search_animate')) {
		$('.result').removeClass('trd_search_animate');
	} // if
    // определяем какие действия нужно делать при нажатии на клавиатуру
    switch(e.keyCode) {
      // игнорируем нажатия на эти клавишы
      case 13:  // enter
      case 27:  // escape
      case 38:  // стрелка вверх
      case 40:  // стрелка вниз
      break;

      default:
        // производим поиск только при вводе более 1 символа
        if($(this).val().length > 1){

          input_initial_value = $(this).val(); // введенный текст          
          searchModelsInArray($(this).val());
        }else{
           $('.result-window').hide();
		   $('.result').removeClass('trd_search_animate');
        } // if
      break;
    }  // switch		
  });	// keyup
  
  // делаем обработку клика по лупе
  $('.search-action').click(function(){	  
	  getboons();
  });
  
  // делаем обработку клика по подсказке
    $('body').on('click', '.result-window ul li',function(e){
		e.preventDefault(); 		
      // ставим текст в input поиска
      $('#InputSearch').val($(this).text());  
      // прячем слой подсказки
      $('.result-window').fadeOut(350).html('');
      suggest_count = 0;
      getboons();
    }); // on
  
  // если кликаем в любом месте сайта, нужно спрятать подсказку и окно результата
    $('section, div').click(function(event){	
		if($(this).hasClass('result') || ($(this).hasClass('result-window') && !$(this).hasClass('overflow'))) {
			event.stopPropagation();
		}else{
		  $('.result-window').hide();
		  $('.result').removeClass('trd_search_animate');		  
		}		
    }); // click
	
	// если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
    $('#InputSearch').click(function(event){      
      if(suggest_count && $(this).val().length > 1)
        $('.result-window').show();
      event.stopPropagation();
    }); // click
	
	//считываем нажатие клавиш, уже после вывода подсказки
	$("#InputSearch").keydown(function(I){		
		switch(I.keyCode) {
			// по нажатию клавиш прячем подсказку
			case 13: // enter				
				$('.result-window').hide();
				suggest_count = 0;
				getboons();
				return false;
			break;
			// делаем переход по подсказке стрелочками клавиатуры
			case 38: // стрелка вверх
			case 40: // стрелка вниз			
				I.preventDefault();
				if(suggest_count){
					//делаем выделение пунктов в слое, переход по стрелочкам
					key_activate( I.keyCode-39 );
				} // if
			break;	
		} // switch
	}); // keydown	
}); // ready

// выбираем все элементы, которые подходят для запроса
function searchModelsInArray(str) {	
	str = str.trim().toUpperCase();	// перевод строки запроса в верхний регистр (для удобства сравнения)
	words = str.split(' ');		// разделение строки запроса по пробелам
	arr = [];           		// массив для результата
	
// проверяем все элементы на соответствие запросу
	for (i = 0; i < arrPhones.length; i++){
		matches = 0;			// количество слов, которые есть в названии из строки
		for(j = 0; j < words.length; j++){
			// если в названии есть это слово
			if(arrPhones[i].toUpperCase().indexOf(words[j]) != -1){
				// увеличиваем количество на 1
				matches++;
			} // if
		} // for j
		// если все слова есть в названии, то добавляем название в список
		if(matches == words.length) {
			newElement = arrPhones[i];			
			arr.push(newElement);	
		} // if
	} // for i
		
	showWindowResult(arr);
} // searchModelsInArray

// вывод окна-подсказки
function showWindowResult(arr) {
	if(arr.length > 0){
		suggest_count = arr.length;
		// перед показом слоя подсказки, его обнуляем
		$(".result-window").html("").show();
		$('.result-window').append('<ul>');
		for(var i in arr){
			if(arr[i] != ''){
				// добавляем слою позиции
				$('.result-window ul').append('<li><a href="#">'+arr[i]+'</a></li>');
			}
		}
		$('.result-window').append('</ul>');
	}else{		
		res_window = ".result-window";
		psa_loader = ".psa-loader";
		$(res_window).html(img_phoneForInputSizes).show();		
	} // if
	scrollForResult();
} // insertInWindoeResult
	
// ajax запрос на поиск и возврат чехлов для выбранного телефона
function getboons(){
	$('.psa-loader').show();
	model = $("#InputSearch").val();    
	$.ajax({
	  type: "POST",
	  url: TRDJS.ajax_url,            
	  data: {
		action: 'trd_get_acceptPhones',
		"query": encodeURIComponent(model)
	  },
	  success: function(res){		  
		  if(res.indexOf("</div>")+1 > 1 || res.indexOf("</p>")+1 > 1) {
			$('.psa-loader').hide();
			$('.result .psa-container').html("");
			$('.result').addClass('trd_search_animate');
			$('.result .psa-container').append(res);
		  }else{
			$("body").prepend("<div class='trd_overflow'><div><span class='gps_ring'></span><span>Bitte warten...</span></div></div>");
			window.location.replace(res);		  
		  } // if
	  } // success
	}); // ajax
} // getboons 

// создание массива с телефонами
function trd_getAllPhones() {	
	$.ajax({		
		url: TRDJS.ajax_url, 		
		type: 'POST',		
		data: {			
			action: 'trd_get_allPhones'						
		}, 
		success: function(res) {
			arrPhones = null;
			arrPhones = res.split(",");				
		}
	});
}

// устанавливать скролл для окна результата
function scrollForResult(){
  if ($('.result-window ul').height() > 3) {
      $('.result-window').addClass('overflow');
  }else {
      $('.result-window').removeClass('overflow');
  } // if
} // scrollForResult

function key_activate(n){
    $('.result-window ul li').eq(suggest_selected-1).removeClass('active');
    
    if(n == 1 && suggest_selected < suggest_count){		
      suggest_selected++;
    }else if(n == -1 && suggest_selected > 0){
      suggest_selected--;	  
    }
    
    // если есть элементы для отображения
    if( suggest_selected > 0){
      // функция скроллинга
      if(suggest_selected > 8){
      scrollLock = (suggest_selected - 8) * 37.07;        
        $('.result-window').scrollTop(scrollLock);        
      }else{ $('.result-window').scrollTop(0);}

      $('.result-window ul li').eq(suggest_selected-1).addClass("active");           
      $("#InputSearch").val($('.result-window ul li').eq(suggest_selected-1).text());
    } else {      
      $("#InputSearch").val(input_initial_value);
    }    
  } // key_activate
/* TRD ----------------------------SEARCH-----------------------------------*/

/* TRD ----------------------------SEARCH-FOOTER-----------------------------------*/
$(document).ready(function(){	
	// читаем ввод с клавиатуры
  $("#InputSearch_footer").keyup(function(e){	  	  
    e.preventDefault();
	if($('.result_footer').hasClass('trd_search_animate_footer')) {
		$('.result_footer').removeClass('trd_search_animate_footer');
	} // if
	
    // определяем какие действия нужно делать при нажатии на клавиатуру
    switch(e.keyCode) {
      // игнорируем нажатия на эти клавишы
      case 13:  // enter
      case 27:  // escape
      case 38:  // стрелка вверх
      case 40:  // стрелка вниз
      break;

      default:
        // производим поиск только при вводе более 1 символа
        if($(this).val().length > 1){

          input_initial_value = $(this).val(); // введенный текст          
          searchModelsInArray_footer($(this).val());
        }else{
           $('.result-window_footer').hide();
		   $('result_footer').removeClass("trd_search_animate_footer");
		}
      break;
    }  // switch		
  });	// keyup
  
  // делаем обработку клика по лупе
  $('.search-action_footer').click(function(){	  
	  getboons_footer();
  });
  
  // делаем обработку клика по подсказке
    $('body').on('click', '.result-window_footer ul li',function(e){
		e.preventDefault(); 		
      // ставим текст в input поиска
      $('#InputSearch_footer').val($(this).text());  
      // прячем слой подсказки
      $('.result-window_footer').fadeOut(350).html('');
      suggest_count = 0;
      getboons_footer();
    }); // on
  
  // если кликаем в любом месте сайта, нужно спрятать подсказку и окно результата
    $('section, div').click(function(event){				
		if($(this).hasClass('result_footer') || ($(this).hasClass('result-window_footer') && !$(this).hasClass('overflow'))) {
			event.stopPropagation();
		}else{
		  $('.result-window_footer').hide();
		  $('.result_footer').removeClass('trd_search_animate_footer');
		}		
    }); // click	
	
	// если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
    $('#InputSearch_footer').click(function(event){      
      if(suggest_count && $(this).val().length > 1)
        $('.result-window_footer').show();
      event.stopPropagation();
    }); // click
	
	//считываем нажатие клавиш, уже после вывода подсказки
	$("#InputSearch_footer").keydown(function(I){		
		switch(I.keyCode) {
			// по нажатию клавиш прячем подсказку
			case 13: // enter				
				$('.result-window_footer').hide();
				suggest_count = 0;
				getboons_footer();
				return false;
			break;
			// делаем переход по подсказке стрелочками клавиатуры
			case 38: // стрелка вверх
			case 40: // стрелка вниз			
				I.preventDefault();
				if(suggest_count){
					//делаем выделение пунктов в слое, переход по стрелочкам
					key_activate_footer( I.keyCode-39 );
				} // if
			break;	
		} // switch
	}); // keydown	
}); // ready

// выбираем все элементы, которые подходят для запроса
function searchModelsInArray_footer(str) {
    str = str.trim().toUpperCase();	// перевод строки запроса в верхний регистр (для удобства сравнения)
    words = str.split(' ');		// разделение строки запроса по пробелам
    arr = [];           		// массив для результата

// проверяем все элементы на соответствие запросу
    for (i = 0; i < arrPhones.length; i++){
        matches = 0;			// количество слов, которые есть в названии из строки
        for(j = 0; j < words.length; j++){
            // если в названии есть это слово
            if(arrPhones[i].toUpperCase().indexOf(words[j]) != -1){
                // увеличиваем количество на 1
                matches++;
            } // if
        } // for j
        // если все слова есть в названии, то добавляем название в список
        if(matches == words.length) {
            newElement = arrPhones[i];
            arr.push(newElement);
        } // if
    } // for i
	
	showWindowResult_footer(arr);
} // searchModelsInArray_footer

// вывод окна-подсказки
function showWindowResult_footer(arr) {
	if(arr.length > 0){
		suggest_count = arr.length;
		// перед показом слоя подсказки, его обнуляем
		$(".result-window_footer").html("").show();
		$('.result-window_footer').append('<ul>');
		for(var i in arr){
			if(arr[i] != ''){
				// добавляем слою позиции
				$('.result-window_footer ul').append('<li><a href="#">'+arr[i]+'</a></li>');
			}
		}
		$('.result-window_footer').append('</ul>');
	}else{
		res_window = ".result-window_footer";	
		psa_loader = ".psa-loader_footer";
		$(res_window).html(img_phoneForInputSizes).show();		
	} // if
	scrollForResult_footer();
} // insertInWindoeResult_footer
	
// ajax запрос на поиск и возврат чехлов для выбранного телефона
function getboons_footer(){
	$('.psa-loader_footer').show();
	model = $("#InputSearch_footer").val();    
	$.ajax({
	  type: "POST",
	  url: TRDJS.ajax_url,            
	  data: {
		action: 'trd_get_acceptPhones',
		"query": encodeURIComponent(model)
	  },
	  success: function(res){		  
		  if(res.indexOf("</div>")+1 > 1 || res.indexOf("</p>")+1 > 1) {
			$('.psa-loader_footer').hide();
			$('.result_footer .psa-container').html("");
			$('.result_footer').addClass('trd_search_animate_footer');
			$('.result_footer .psa-container').append(res);
		  }else{
			  $("body").prepend("<div class='trd_overflow'><div>Bitte warten...</div></div>");			  
			  window.location.replace(res);		  
		  } // if
	  } // success
	}); // ajax
} // getboons_footer

// устанавливать скролл для окна результата
function scrollForResult_footer(){
  if ($('.result-window_footer ul').height() > 3) {
      $('.result-window_footer').addClass('overflow');
  }else {
      $('.result-window_footer').removeClass('overflow');
  } // if
} // scrollForResult_footer

// действие для стрелок
function key_activate_footer(n){	
    $('.result-window_footer ul li').eq(suggest_selected-1).removeClass('active');
    
    if(n == 1 && suggest_selected < suggest_count){		
      suggest_selected++;
    }else if(n == -1 && suggest_selected > 0){
      suggest_selected--;	  
    }
    
    // если есть элементы для отображения
    if( suggest_selected > 0){
      // функция скроллинга
      if(suggest_selected > 8){
      scrollLock = (suggest_selected - 8) * 37.07;        
        $('.result-window_footer').scrollTop(scrollLock);        
      }else{ $('.result-window_footer').scrollTop(0);}

      $('.result-window_footer ul li').eq(suggest_selected-1).addClass("active");           
      $("#InputSearch_footer").val($('.result-window_footer ul li').eq(suggest_selected-1).text());
    } else {      
      $("#InputSearch_footer").val(input_initial_value);
    }    
  } // key_activate_footer
/* TRD ----------------------------SEARCH-FOOTER-----------------------------------*/

/*-------------------------TRD Поиск с выбором модели------------------------------*/
curBrand = -1;					// текущий выбранный бренд
$(document).ready(function(){
	// выбор бренда в списке
	$("#brand").on('change', function(){
		curBrand = $("#brand :selected").val();
		// если выбрали какой-то бренд
		if (curBrand != -1){
			// получем телефоны по бренду и помещаем в #model
			$('.psa-loader_searchtrd').show();			
			$.ajax({		
				url: TRDJS.ajax_url, 		
				type: 'POST',		
				data: {			
					action: 'trd_get_phonesByModel',
					"model": encodeURIComponent(curBrand)					
				}, 
				success: function(res) {
					$('.psa-loader_searchtrd').hide();					
					$('#model').html("");
					$('#model').append(res);
					$('#model').removeAttr("disabled");
					$("#brand").blur();
				}			
			}); // ajax 
		}else{
			// очищаем #model
			$('#model').html("");			
			$('#model').append("<option value='-1'>Modell</option>");
			$('#model').attr("disabled","disabled");
		} // if
	});// change
	
	// открытие списка брендов
	$("#brand").focus(function(){
		$('#model').attr("disabled","disabled");
		$("#brand [value='-1']").attr("selected", "selected");
		$('.resultByModel').removeClass('trd_search_animate');
	});
	
	// выбор модели
	$("#model").change(function(){
		$('.psa-loader_searchtrd').show();
		phone = $("#model :selected").val();
		$.ajax({
			type: "POST",
			url: TRDJS.ajax_url,            
			data: {
				action: 'trd_get_acceptPhones',
				"query": encodeURIComponent(phone)
		},
		success: function(res){			
			$('.psa-loader_searchtrd').hide();		
			if(res.indexOf("div")+1 > 1) {			
			$('.resultByModel .psa-container').html("");
			$('.resultByModel').addClass('trd_search_animate');
			$('.resultByModel .psa-container').append(res);
		  }else{
			  $("body").prepend("<div class='trd_overflow'><div>Bitte warten...</div></div>");
			  window.location.replace(res);		  
		  } // if
		} // success			
		}); // ajax
	}); // change
}); // ready
/*-------------------------TRD Поиск с выбором модели-------------------*/

/*-------------------------TRD удаление title при наведении на ссылки (картинки)-------------------*/
$(document).ready(function(){
	setTimeout(function(){		
		$('a').each(function(){
			$(this).attr("title","");
		});
	},10000);
});
/*-------------------------TRD удаление title при наведении на ссылки (картинки)-------------------*/

/*-------------------------TRD Подбор телефона по размеру-------------------*/
$(document).ready(function(){
	// ограничение на ввод только цифр и точки
	$("body").on('keypress', '.ps-trd-input', function(e){
				// ввод не цифр				      ввод не точки		ввод не запятой
		if((e.keyCode < 48 || e.keyCode > 57) && e.keyCode != 46 && e.keyCode != 44){
			e.preventDefault();
		} // if		
	}); // on	
}); // ready

function trd_picking_by_sizes(){	
	height = $("#height").val();
	width = $("#width").val();
	depth = $("#depth").val();
	// удалить результат прошлого поиска по размеру
	$(".size-search-result").remove();
	
	if(height > 0 && width > 0 && depth > 0){	
		$(psa_loader).show();		
		$.ajax({
			type: "POST",
			url: TRDJS.ajax_url,            
			data: {
				action: 'trd_searchSize',
				"height": encodeURIComponent(height),
				"width": encodeURIComponent(width),
				"depth": encodeURIComponent(depth)
			},
			success: function(res){								
				if(res.indexOf("div")+1 > 1) {			
				$(res_window).html(res);
			  }else{
				  $("body").prepend("<div class='trd_overflow'><div>Bitte warten...</div></div>");
				  window.location.replace(res);		  
			  } // if
			  $(psa_loader).hide();
			} // success			
		}); // ajax			
	}else{
		alert("Füllen Sie alle Felder aus");
	} // if
} // trd_picking_by_sizes()
/*-------------------------TRD Подбор телефона по размеру-------------------*/

/*-------------------------TRD Возвращает переведенную фразу из словаря-------------------*/
function trd_getPhraseFromVocabulary(str) {
    var query = window.location.search.substring(1);
    //console.log(query);
   // var qs = parse_query_string(query);
    //console.log(qs);
    result = '';
    $.ajax({
        type: "POST",
        url: TRDJS.ajax_url,
        async: false,
        data: {
            action: 'trd_getVocabulary',
            "string": encodeURI(str)
        },
        success: function(res){
           // console.log(res);
            result = res;
        } // success
    }); // ajax

    //console.log(result);
    return result;
}
/*-------------------------TRD Возвращает переведенную фразу из словаря-------------------*/

