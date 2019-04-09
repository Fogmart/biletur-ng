<?php
namespace common\modules\api\ostrovok\components;

use yii\base\Component;

/**
 * Класс для взаимодействия с АПИ Островок
 *
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Query extends Component {
	/** @var string */
	public $url = 'https://partner.ostrovok.ru/api/affiliate/v2/';

	/** @var string */
	public $method;

	/** @var string */
	public $params;

	//Запрос регионов и отелей
	const METHOD_MULTICOMPLETE = 'multicomplete';

	//Предварительный поиск отелей по региону или ид отеля
	const METHOD_HOTEL_RATES = 'hotel/rates';

	//Актуализация тарифов в отеле
	const METHOD_HOTEL_TEST = 'hotelpage/test_hotel';

	/**
	 * Отаправка запроса к АПИ
	 *
	 * @return bool|string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function sendRequest() {
		$url = $this->url . $this->method . '?data=' . json_encode($this->params);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$out = curl_exec($curl);
		curl_close($curl);

		return $out;
	}
}