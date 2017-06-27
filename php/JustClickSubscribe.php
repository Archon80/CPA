<?php
class JustClickSubscribe
{
	// 1-ая основная функция: ДОБАВЛЕНИЕ пользователя
	public static function addUser($data)
	{
		$user_rs = $data['admin'];		
		$send_data = self::createSendData($data);
		$send_data['hash'] = self::GetHash($send_data, $user_rs);

		self::LogBefore($send_data);	// данные в АПИ

		//$resp = json_decode(self::Send('http://prodavecokon.justclick.ru/api/AddLeadToGroup', $send_data));
		$resp = self::Send('http://prodavecokon.justclick.ru/api/AddLeadToGroup', $send_data);

		self::LogAfter($resp);			// ответ из АПИ

		if( !self::CheckHash($resp, $user_rs) )
		{
			return "Ошибка! Подпись к ответу не верна!";
			exit;
		}

		if($resp->error_code == 0)	return "Пользователь {$data['user']['userEmail']} добавлен в группу {$send_data['rid[0]']}. Ответ сервиса: {$resp->error_code}";
		else 						return "Ошибка код:{$resp->error_code} - описание: {$resp->error_text}";
	}

	//************************************** СЛУЖЕБНЫЕ ФУНКЦИИ ОБЩЕГО НАЗНАЧЕНИЯ ***********************************//

	// отправляем запрос в API сервиса 
	private static function Send($url, $data)
	{			
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // выводим ответ в переменную

		$res = curl_exec($ch);
		curl_close($ch);

		return $res;
		/*
		$curlInit = curl_init('http://justclick.ru');
	    curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
	    curl_setopt($curlInit,CURLOPT_HEADER,true);
	    curl_setopt($curlInit,CURLOPT_NOBODY,true);
	    curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
	    $response = curl_exec($curlInit);
	    curl_close($curlInit);
	    return $response;
	    */
	}

	// формируем подпись к передаваемым в API данным
	private static function GetHash($params, $user_rs)
	{
		$params = http_build_query($params);
		$user_id = $user_rs['user_id'];
		$secret = $user_rs['user_rps_key'];
		$params = "{$params}::{$user_id}::{$secret}";
		
		return md5($params);
	}

	// проверяем полученную подпись к ответу
	private static function CheckHash($resp, $user_rs)
	{		
		$secret = $user_rs['user_rps_key'];
		$code   = $resp->error_code;
		$text   = $resp->error_text;
		$hash   = md5("$code::$text::$secret");

		if($hash == $resp->hash)	return true; 	// подпись верна
		else 						return false; 	// подпись не верна
	}

	// формируем объект $send_data
	private static function createSendData($data)
	{
		$send_data = array(
			'rid[0]' 		    => $data['user']['group'],
			'lead_email' 	    => $data['user']['userEmail'],
			'activation' 	    => false
		);
		// дописываем в $send_data ютм-метки объекта $data, переданного в сценарий 
		foreach ($data['user'] as $key => $value)
        {
            // кроме этих двух полей - остальные гет-параметры являются ютм-метками
            if ($key != 'userEmail' && $key != 'group')
            {
                $send_data[ 'utm['.$key.']' ] = $value;
            }
        }
        return $send_data;
	}

	// логируем данные, которые пойдут в АПИ
	private static function LogBefore($send_data)
	{
		/*
		// отладочные логи
		if (file_exists("out.log")) { unlink("out.log"); }            // каждый раз пишем логи заново
        $str_to_out_log .= PHP_EOL.'Итоговые данные, отправляемые в ДжастКлик: '.json_encode($send_data);
        file_put_contents("out.log", $str_to_out_log, FILE_APPEND );
        */
        $str_to_out_log = PHP_EOL.'Итоговые данные, отправляемые в ДжастКлик: '. date(DATE_RFC2822) . ' ' . json_encode($send_data).PHP_EOL.PHP_EOL;
        file_put_contents("to_api.log", $str_to_out_log, FILE_APPEND );
	}

	// логируем данные, которые пришли из АПИ
	private static function LogAfter($resp)
	{
		/*
		// отладочные логи
		if (file_exists("out.log")) { unlink("out.log"); }            // каждый раз пишем логи заново
        $str_to_out_log .= PHP_EOL.'Итоговые данные, отправляемые в ДжастКлик: '.json_encode($send_data);
        file_put_contents("out.log", $str_to_out_log, FILE_APPEND );
        */
        $str_to_out_log = PHP_EOL.'Ответ АПИ: '. date(DATE_RFC2822) . ' ' . json_encode($resp).PHP_EOL.PHP_EOL;
        file_put_contents("from_api.log", $str_to_out_log, FILE_APPEND );
	}



	// ($)

}