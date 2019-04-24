<?php

namespace common\modules\api\ostrovok\components;

use Yii;
use yii\base\Component;
use yii\base\Configurable;

/**
 * Класс для взаимодействия с АПИ Островок
 *
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class OstrovokApi extends Component implements Configurable {

	/** @var string */
	public $method;

	/** @var array */
	public $params;

	/** @var int */
	protected $_keyId;

	/** @var string */
	protected $_key;

	/** @var string */
	private $_url = 'https://partner.ostrovok.ru/api/affiliate/v2/';

	//Запрос регионов и отелей
	const METHOD_MULTICOMPLETE = 'multicomplete';

	//Предварительный поиск отелей по региону или ид отеля
	const METHOD_HOTEL_RATES = 'hotel/rates';

	//Актуализация тарифов в отеле
	const METHOD_HOTEL_TEST = 'hotelpage/test_hotel';

	/** @var array Параметры, общие для всех запросов */
	const DEFAULT_PARAMS = [
		'format' => 'json',
		'lang'   => 'ru'
	];

	public function __construct($config = []) {
		if (!empty($config)) {
			Yii::configure($this, $config);
		}
		parent::__construct($config);
	}

	/**
	 * Отаправка запроса к АПИ
	 *
	 * @return bool|string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function sendRequest() {
		$userPassword = $this->_keyId . ':' . $this->_key;

		$this->params = array_merge($this->params, static::DEFAULT_PARAMS);

		$this->_url = $this->_url . $this->method . '?data=' . json_encode($this->params);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_POST, 0);
		curl_setopt($curl, CURLOPT_USERPWD, $userPassword);
		curl_setopt($curl, CURLOPT_URL, $this->_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$out = curl_exec($curl);
		curl_close($curl);

		return json_decode($out);
	}

	/**
	 * @param int $value
	 *
	 * @return $this
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function setKeyId($value) {
		$this->_keyId = $value;

		return $this;
	}

	/**
	 * @param string $value
	 *
	 * @return $this
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function setKey($value) {
		$this->_key = $value;

		return $this;
	}
}