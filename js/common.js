var body 	  = document.body,
	arrConfig = [ // конфиг: в каждой строке 1-ый элемент - кнопка, 2-й - соответствующий класс
		['butt-2', 		 'nav-opened2'],	// подписка
		['butt-modal',   'nav-opened' ],	// конфиденциальность
		['butt-modal-3', 'nav-opened3'],	// оферта
		['butt-modal-4', 'nav-opened4']		// отказ от ответственности
	];

// навешиваем события на каждую значимую кнопку (добавляем/удаляем класс при клике по кнопкам)
for(i in arrConfig) {
	(function( elem, i ) {
		var dopBtn = document.querySelector('.'+elem[0]);// дополнительные кнопки: политика конфиденциальности, оферта и проч.
		if(i != 0) { // обходим нестандартность присвоенных классов... Всё из-за Андрея!
			if(dopBtn) {// на случай, если на странице нет оферты и т.п. (чтоб не вываливалась ошибка, ломающая сценарий)
				dopBtn.onclick = function() { toggleBodyClass(elem[1]) }
			}
		}
	})(arrConfig[i], i)
}
// удаление класса при клике по крестику попапа
[].forEach.call(document.querySelectorAll('.close'), function(elem, i, arr){
	elem.onclick = function() { removeClass(); }
});
// удаление класса при клике по кнопке Esc
body.onkeyup = function (e){ if(e.keyCode == 27) { removeClass() } }

////////////////////////////////////////////////////////////////////
function toggleBodyClass(extClass) {
	body.classList.contains(extClass) ? body.classList.remove(extClass) : body.classList.add(extClass);
}
function removeClass() {
	for(i in arrConfig) {
    	body.classList.remove(arrConfig[i][1])
    }
}