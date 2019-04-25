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
	public $params = [];

	/** @var int */
	protected $_keyId;

	/** @var string */
	protected $_key;

	/** @var string */
	protected $_urlV2;

	/** @var string */
	protected $_urlV3;

	//Запрос регионов и отелей
	const METHOD_MULTICOMPLETE = 'multicomplete';

	//запрос типов питания
	const METHOD_MEALS = 'meals';

	// Запрос значений фильтров тарифов
	const METHOD_SERP_FILTERS = 'serp_filters';

	//Предварительный поиск отелей по региону или ид отеля
	const METHOD_HOTEL_RATES = 'hotel/rates';

	//Актуализация тарифов в отеле
	const METHOD_HOTEL_TEST = 'hotelpage/test_hotel';

	//Актуализация дампа статики отелей
	const METHOD_HOTEL_GET_DUMP = 'hotel/info/dump/';

	//Список отелей в регионе
	const METHOD_REGION_HOTEL_LIST = 'region/hotel/list';

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

		switch ($this->method) {
			case static::METHOD_HOTEL_GET_DUMP:
				$url = $this->_urlV3;
				break;
			default:
				$url = $this->_urlV2;
				break;
		}

		$url = $url . $this->method . '?data=' . json_encode($this->params);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_POST, 0);
		curl_setopt($curl, CURLOPT_USERPWD, $userPassword);
		curl_setopt($curl, CURLOPT_URL, $url);
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

	/**
	 * @param int $value
	 *
	 * @return $this
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function setUrlV2($value) {
		$this->_urlV2 = $value;

		return $this;
	}

	/**
	 * @param int $value
	 *
	 * @return $this
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function setUrlV3($value) {
		$this->_urlV3 = $value;

		return $this;
	}
}