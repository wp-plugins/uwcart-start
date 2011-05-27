function checkOwnButton() {
	if (document.ECTsettingsform.basket_btn_own.value != '' || document.ECTsettingsform.basket_btn_inpost_own.value != '') {
		if(confirm('Необходимо выбрать одну из стандартных кнопок добавления товара в корзину!\r\nВы согласны с этим вариантом?')) {
			document.ECTsettingsform.set_goods_qty.checked = true;
			document.ECTsettingsform.basket_btn_own.value = '';
			document.ECTsettingsform.basket_btn_inpost_own.value = '';
		}
		else {
			document.ECTsettingsform.set_goods_qty.checked = false;
		}
	}
}
		
function CheckButtonSelector(radio_group) {
	for (c=0; c<radio_group.length; c++) {
		if (radio_group[c].checked) {
			return 1
		}
	}
	return -1
}
		
function clearField() {
			
	var basket_btn_index = CheckButtonSelector(document.ECTsettingsform.basket_btn);
	var basket_btn_inpost_index = CheckButtonSelector(document.ECTsettingsform.basket_btn_inpost);
	var order_btn_index = CheckButtonSelector(document.ECTsettingsform.order_btn);
			
	if (basket_btn_index == 1) document.ECTsettingsform.basket_btn_own.value = '';
	if (basket_btn_inpost_index == 1) document.ECTsettingsform.basket_btn_inpost_own.value = '';
	if (order_btn_index == 1) {
		document.ECTsettingsform.order_btn_own.value = '';
		document.ECTsettingsform.fast_buy.checked = false;
	}
	
}
		
function checkFastBuy() {
	if (document.ECTsettingsform.fast_buy.checked == true) document.ECTsettingsform.order_btn_own.value = '';
}
		
function clearFastBuy() {
	if (document.ECTsettingsform.fast_buy.checked == true) document.ECTsettingsform.fast_buy.checked = false;
}
		
function clearRadioGroup1(mod) {
	if (mod == 1) {
		for (c=0; c<document.ECTsettingsform.basket_btn.length; c++) {
			document.ECTsettingsform.basket_btn[c].checked = false;
		}
	}
	else {
		for (c=0; c<document.ECTsettingsform.basket_btn_inpost.length; c++) {
			document.ECTsettingsform.basket_btn_inpost[c].checked = false;
		}
	}
}
		
function clearRadioGroup2() {
	for (c=0; c<document.ECTsettingsform.order_btn.length; c++) {
		document.ECTsettingsform.order_btn[c].checked = false;
	}
}

function checkECTSubmit() {
	
	var basket_btn_inpost_index = CheckButtonSelector(document.ECTsettingsform.basket_btn_inpost);	
	var basket_btn_index = CheckButtonSelector(document.ECTsettingsform.basket_btn);
	var order_btn_index = CheckButtonSelector(document.ECTsettingsform.order_btn);
	
	if (basket_btn_inpost_index == 1 || document.ECTsettingsform.basket_btn_inpost_own.value) {
		if (basket_btn_index == 1 || document.ECTsettingsform.basket_btn_own.value) {	
			if (order_btn_index == 1 || document.ECTsettingsform.order_btn_own.value) {	
				if (document.ECTsettingsform.ect_shop_id.value) {
					if (!document.ECTsettingsform.ect_currency_type.value) {
						alert('Укажите тип валюты!');
						return false;
					}
					return true;
				}
				else {
					alert('Укажите Ваш ID в сервисе EcommTools!');
					document.ECTsettingsform.ect_shop_id.style.background = '#F9F7CA';
					document.ECTsettingsform.ect_shop_id.style.border = '1px solid #FF9500';
					return false;
				}
			}
			else {
				alert('Выберите вариант кнопки оформления заказа!');
				return false;
			}
		}
		else {
			alert('Выберите вариант кнопки добавления товара в корзину в виджете!');
			return false;
		}
	}
	else {
		alert('Выберите вариант кнопки добавления товара в корзину в посте!');
		return false;
	}
}

function ShowConditionsInfo(current_list) {

	var choice = current_list.options[current_list.selectedIndex].value;
	var msg;

	if (choice != "") {	
		for (var i = 0; i < current_list.options.length; i++) {
			if (current_list.options[i].selected) {
				
				if (choice == 'all') msg = 'Поле "Специальный товар" заполнять обязательно!';
				if (choice == 'home') msg = 'Поле "Специальный товар" заполнять обязательно!';
				if (choice == 'post') msg = 'Если не указывать ID постов, виджет выводится во всех постах.';
				if (choice == 'post_in_category') msg = 'ID категорий указывать обязательно!';
				if (choice == 'category') msg = 'Поле "Специальный товар" заполнять обязательно!\r\nЕсли не указывать ID категорий, виджет выводится во всех категориях.';
				if (choice == 'page') msg = 'Поле "Специальный товар" заполнять обязательно!\r\nЕсли не указывать ID страниц, виджет выводится на всех страницах.';
				
				if (msg) alert(msg);
			}
		}	
	}

}

function ShowNGConditionsInfo(current_list) {

	var choice = current_list.options[current_list.selectedIndex].value;
	var msg;

	if (choice != "") {	
		for (var i = 0; i < current_list.options.length; i++) {
			if (current_list.options[i].selected) {
				
				if (choice == 'post') msg = 'Если не указывать ID постов, виджет выводится во всех постах.';
				if (choice == 'post_in_category') msg = 'ID категорий указывать обязательно!';
				if (choice == 'category') msg = 'Если не указывать ID категорий, виджет выводится во всех категориях.';
				if (choice == 'page') msg = 'Если не указывать ID страниц, виджет выводится на всех страницах.';
				
				if (msg) alert(msg);
			}
		}	
	}

}

/* pictsloader */
function ECTUploadPhoto() {
			
	if (!document.getElementById('ect_addpicts_0').value && !document.getElementById('ect_addpicts_1').value && !document.getElementById('ect_addpicts_2').value && !document.getElementById('ect_addpicts_3').value) {
		alert('Выберите фото для загрузки на своем компьютере');
		return false;
	}
	else {

		document.getElementById('ect_mainpict').innerHTML = '<p align="center" style="color:#F6A828;">Минуточку...</p>';
		return false;
	}
			/*
			document.getElementById('progressline').innerHTML = '<p align="left" style="color:#F6A828;">Минуточку...</p><img src="http://www.telegraf.in.ua/js/ui/css/excite-bike/images/progressbar.gif" style="width:280px;height:16px;" />'
			
			document.getElementById('pictbox').innerHTML = ''
			
			JsHttpRequest.query(
			  'ajax/blogger_upload_pict.php', // путь к backend-скрипту
			  {
				// передаем файл 
				'blogger_photo': document.getElementById('blogger_photo'),
				'bloggerID': document.bloggerphoto.bloggerID.value
			  },
			  
			  // Функция-обработчик, вызывается при ответе сервера. 
			  function(result, errors) {
				// результат проверки
				if (result) {
					if (result['wrong_format'] == 1) {
						document.getElementById('wrong_format').innerHTML = 'Неверный формат файла'
						document.getElementById('progressline').innerHTML = ''
					}
					else {
						if (result['res'] == 'ok') {
							var bloggerID = document.bloggerphoto.bloggerID.value
							var isphoto = document.bloggerphoto.isphoto.value
							document.getElementById('pictbox').innerHTML = 
							'<img src="http://www.telegraf.in.ua/picts/blogs/' + bloggerID + '/avatar/' + result['ava'] + '" />'
							
							document.getElementById('progressline').innerHTML = '<p align="left" style="color:#F6A828;width:80%;">' + isphoto + '</p>'
							
						}
						else {
							document.getElementById('wrong_format').innerHTML = 'Фото НЕ ЗАГРУЖЕНО'
							document.getElementById('progressline').innerHTML = ''
						}
					}
				}
			  }
			);
				
			return true
			*/
		}