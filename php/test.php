<?php
echo "точка входа";

require_once 'JustClickSubscribe2.php';
echo "подключили класс";
$data_to_api= array(
	"admin" => array("user_id" => "prodavecokon", "user_rps_key"=>"6067040551137e729d651aab1d3de829"), 
	"user"=>array("userEmail" => "orcmusic@yandex.ru", "group"=>"dom_tinvest_free_cpa")
	);
//'{"admin":{"user_id":"prodavecokon","user_rps_key":"6067040551137e729d651aab1d3de829"},"user":{"userEmail":"orcmusic@yandex.ru","group":"dom_tinvest_free_cpa"}}';

/*echo '<pre>';
var_dump($data_to_api);
echo '</pre>';
echo $data_to_api["admin"]["user_id"];
*/
echo "сформировали данные";
$result = JustClickSubscribe::addUser($data_to_api);
echo "возврат";
//echo "$result";
?>
