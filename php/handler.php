<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once 'JustClickSubscribe.php';

class Handler
{
    // основная функция
    public static function main()
    {
        //self::log('dataToServer');                              // пишем логи входных параметров
        self::checkExtData();                                   // проверяем обязательные входные параметры (почта пользователя)
        $data_to_api = self::create_data_to_API();              // формируем объект с данными для отправки
        $result = JustClickSubscribe::addUser($data_to_api);    // передаем сформированный объект в АПИ-сценарий
        echo($result);                                          // выводим результат
        //self::log('serverAnswer', $result);
    }

    // проверка обязательных параметров: почта пользователя должна быть прислана, без этого скрипт не будет работать
    private static function checkExtData()
    {
        // проверка обязательных параметров (пока что это только почта юзера)
        if( !isset($_GET['email']) || $_GET['email'] == '' )
        {
            echo 'Данные почты не пришли в РНР-файл.';
            exit();
        }
    }

    // из данных, поступивших в сценарий, формируем объект данных по протоколу Джаста
    private static function create_data_to_API()
    {
        $data = array(
            'admin' =>  array(
                'user_id'       => 'prodavecokon',
                'user_rps_key'  => '6067040551137e729d651aab1d3de829',
            ),
            'user'  =>  array(
                'userEmail'    => $_GET['email'],
                'group'        => 'dom_tinvest_free_cpa'
            )
        );

        // автодобавление присланных ютм-меток в объект данных $data по ключу user
        foreach ($_GET as $key => $value)
        {
            // кроме этих двух полей - остальные гет-параметры являются ютм-метками
            if ($key != 'derParol' && $key != 'email')
            {
                $data['user'][$key] = $_GET[$key];
            }
        }

        return $data;
    }

    // в логи пишем объект данных, который пришел в массиве $_GET от клиента
    private static function log($cnt, $result)
    {
        if ($cnt == 'dataToServer') {
            // логи входных параметров - контроль передачи utm- и aff-параметров
            if (file_exists("inn.log")) { unlink("inn.log"); }
            $str_to_inn_log = json_encode($_GET);
            file_put_contents("inn.log", 'Данные, которые пришли из РНР-скрипта: '.$str_to_inn_log, FILE_APPEND );
        }
        elseif ($cnt == 'serverAnswer') {
            file_put_contents("server_answer.log", PHP_EOL.'Ответ сервера: '.$result, FILE_APPEND );
        }
    }
}

Handler::main();