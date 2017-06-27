/*
    Сбор данных подписки (почта пользователя и utm-метки) и отправка их в php-сценарий по адресу php/handler.php
*/
var subscribeCPA = new SubscribeCPA(),              	// создаем экземпляр класса подписки (скрипт js/class_SubscribeCPA.js)null,            // экземпляр класса подписки
    utm_params   = subscribeCPA.parseGETparameters();   // объект ютм-меток - используется ниже в двух функциях

subscribeCPA.crutch(utm_params);                        // устанавливаем возможность работы ИХ попапа

document.querySelector('.widget2-subscribe-form').addEventListener("submit", function(e)
{
    
    e.preventDefault();                 // временная отмена отправки формой данных (редирект ломает работу скриптов)

    var userEmail    = document.querySelector('.email').value,                  // почта, которую ввел пользователь
        dataToServer = subscribeCPA.createDataToServer(utm_params, userEmail);  // подготовка данных
    
    console.log(dataToServer);
    subscribeCPA.sendAJAX('php/handler.php', dataToServer, e.target);           // отправка данных на сервер
    //e.target.submit();    
    subscribeCPA.showLoadingStatus();   // отображаем статус во время выполнения действий подписки в ДжастКлик (сплоть до редиректа)
    return false;
}, false);



