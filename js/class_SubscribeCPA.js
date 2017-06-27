/*
    Данный класс используется для подписки в JustClick.

    1. Сбор информации для подписки (почта пользователя и ютм-метки)
    2. Отправка ее с помощью AJAX в подписной php-скрипт.
*/
function SubscribeCPA()
{
    /*
        ФОРМИРОВАНИЕ ОБЪЕКТА ДАННЫХ ДЛЯ ОТПРАВКИ НА СЕРВЕР
            принимает:
                        - объект параметров подписки
                        - название метода отправки данных
            возвращает:
                        - гет-строку с данными подписки (почта пользователя и ютм-метки)
    */
    this.createDataToServer = function(arrUTM, userEmail)
    {
        var totalString = 
                        '?derParol=' + 'subscribeJustClick' +
                        '&email='    + userEmail;

        for(var elem in arrUTM)
        {
            totalString += '&' + elem + '=' + arrUTM[elem];
        }
        
        // если ютм-меток не было, удаляем символы '&=' в конце строки
        if( /&=$/.test(totalString) ) {
            totalString = totalString.slice(0, -2);
        }
        //console.log(totalString);
        
        return totalString;
    }

    /*
        КОСТЫЛЬ К ИХ СКРИПТАМ:
            принимает:  - не принимает параметров
            выполняет:  - добавляем класс "nav-opened2" в body (если есть гет-параметр subscribe=done)
            возвращает:
                        - 1, если в адресной строке браузера есть гет-параметр subscribe=done
                        - 0, если такого гет-параметра нет                        
    */
    this.crutch = function(utm_params)
    {
        if(utm_params.subscribe == 'done')
        {
            document.body.classList.add('nav-opened2');
            return 1;
        }
        return 0;
    }

    // создаем объект для работы с AJAX
    this.getXmlHttpRequest = function()
    {
        if (window.XMLHttpRequest)
        {
            try { return new XMLHttpRequest(); } catch (e){}
        } 
        else if (window.ActiveXObject)
        {
            try { return new ActiveXObject('Msxml2.XMLHTTP'); }    catch (e){}    
            try { return new ActiveXObject('Microsoft.XMLHTTP'); } catch (e){}
        }
        return null;
    }

    /*
        ПАРСИНГ ГЕТ-СТРОКИ БРАУЗЕРА        
            принимает: не принимает параметров        
            возвращает:
                        - если есть ютм-метки - объект с гет-данными (ключи объекта - имена гет-параметров, значения - значения гет-параметров)
                        - если нет меток - возвращает false 
    */
    this.parseGETparameters = function()
    {
       
        var gets = window.location.search.replace(/&/g, '&').substring(1).split('&');

        if (gets.length > 0)
        {
            var result = {};

            for (var i = 0; i < gets.length; i++)
            {
              var get = gets[i].split('=');
              result[get[0]] = typeof(get[1]) == 'undefined' ? '' : get[1];
            }
            console.log( 'result = ', result );
            return result;
        }

    }

    /*
        ОТПРАВКА ДАННЫХ ПОДПИСКИ В РНР-СКРИПТЫ
            принимает
                        - путь до php-файла
                        - гет-строку с данными подписки
                        - DOM-объект формы (для инициации отправки данных в гет-респонс после отправки данных в джастклик)
            возвращает 
                        - статус операции
    */
    this.sendAJAX = function(path, dataToServer, currentForm)
    {
        var request = this.getXmlHttpRequest();              // объект XMLHttpRequest

        request.onreadystatechange = function()
        {
            if (request.readyState == 4)
            {
                //console.log(request.responseText);
                currentForm.submit();
            }            
        };
        request.open("GET", path+dataToServer, true);   // запрос на сервер - подготовка (взят адрес текущей страницы)
        request.send();                                 // запрос на сервер - ответ
        //console.log('path+dataToServer = ', path+dataToServer);

        return false;
    }

    // отображаем статус во время выполнения действий подписки в ДжастКлик
    this.showLoadingStatus = function()
    {
        // вставляем прогресс-бар, чтоб юзеры не нервничали
        var dinamicPopupCPA = document.body;
        dinamicPopupCPA.style.cursor = 'wait';
        dinamicPopupCPA.title = 'Подождите несколько секунд, ваш запрос обрабатывается...';

        return true;
    }
}

