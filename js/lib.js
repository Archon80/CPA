// установка указанного класса указанному элементу
function setClass(elem, className)
{
	elem.classList.add(className);
	return 0;
}

// проверка - имеется ли указанный класс в вказанном элементе
function hasClass(elem, className)
{
	return elem.classList.contains(className);
}

// удаление указанного класса у указанного элемента
function deleteClass(elem, className)
{
	elem.classList.remove(className);
	return 0;
}

// проверить, является ли элемент текстовым полем
function isInput(elem)
{
	if (elem.nodeType == 1 && elem.type == 'text') {
		return true;
	}
	return false;
}

// проверить, является ли элемент текстовым полем ДЛЯ ОТПРАВКИ ПОЧТЫ
function isEmail(elem)
{
	if (elem.nodeType == 1 && elem.type == 'email' ) {
		return true;
	}
	return false;
}

// узнать, является ли элемент ФОРМОЙ
function isForm(elem)
{
	if (elem.nodeType == 1 && elem.tagName == 'FORM') {
		return true;
	}
	return false;
}

// узнать, является ли элемент ОБЪЕКТОМ
function isObject(elem)
{
	if(
        ( typeof elem !== 'object' ) ||
        ( Array.isArray(elem) ) ||
        ( elem.length !== undefined )
	) { return false; }

	return true;
}