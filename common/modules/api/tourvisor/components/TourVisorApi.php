<?php

namespace common\modules\api\tourvisor\components;

use Yii;
use yii\base\Component;
use yii\base\Configurable;

class TourVisorApi extends Component implements Configurable {

	private $_apiUrl;
	private $_login;
	private $_password;

	const FORMAT_JSON = 'json';
	const PARAM_FORMAT = 'format';

	const PARAM_DEPARTURE = 'departure'; //код города вылета
	const PARAM_COUNTRY = 'country'; //код страны
	const PARAM_DATE_FROM = 'datefrom'; //дата от в формате дд.мм.гггг (если не указан - текущая дата +1 день)
	const PARAM_DATE_TO = 'dateto'; //дата до в формате дд.мм.гггг (если не указан - текущая дата +8 дней). Максимальный диапазон = 2 недели (14 дней)
	const PARAM_NIGHTS_FROM = 'nightsfrom'; //ночей от (по умолчанию = 7)
	const PARAM_NIGHTS_TO = 'nightsto'; //ночей до (по умолчанию = 10)
	const PARAM_ADULTS = 'adults'; //кол-во взрослых (по умолчанию = 2)
	const PARAM_CHILD = 'child'; //кол-во детей (по умолчанию = 0)
	const PARAM_CHILD_AGE_1 = 'childage1'; //возраст 1 ребенка, лет (опционально). Младенец = 1
	const PARAM_CHILD_AGE_2 = 'childage2'; //возраст 2 ребенка, лет (опционально). Младенец = 1
	const PARAM_CHILD_AGE_3 = 'childage3'; //возраст 3 ребенка, лет (опционально). Младенец = 1
	const PARAM_STARS = 'stars'; //категория отеля (звездность) (опционально)
	const PARAM_STARS_BETTER = 'starsbetter'; //1 – показывать категории лучше указанной. по умолчанию 1 (опционально)
	const PARAM_MEAL = 'meal'; //тип питания (код) (опционально)
	const PARAM_MEAL_BETTER = 'mealbetter'; //1 – показывать питание лучше указанного. по умолчанию 1 (опционально)
	const PARAM_RATING = 'rating'; //рейтинг отеля (опционально). Используется кодировка: 0: любой, 2: >= 3.0, 3: >= 3.5, 4: >= 4.0, 5: >= 4.5  (т.е. нужно передать целое число, соотв. критерию)
	const PARAM_HOTELS = 'hotels'; //коды отелей (если несколько, то через запятую) (опционально)
	const PARAM_HOTEL_TYPES = 'hoteltypes'; //типы отелей (через запятую: active, relax, family, health, city, beach, deluxe) пример:relax,beach  (опционально).
	const PARAM_PRICE_TYPE = 'pricetype'; //тип цены. 0 – цена за номер, 1 – цена за человека (по умолчанию 0)
	const PARAM_REGIONS = 'regions';  //коды курортов (если несколько, то через запятую) (опционально)
	const PARAM_SUB_REGIONS = 'subregions'; //коды вложенных курортов (районов) (если несколько, то через запятую) (опционально). Если Вам нужен поиск по конкретному району (subregion), то соответствующий ему параметр regions указывать не нужно, иначе будет производиться поиск по всему курорту (region), который Вы указали.
	const PARAM_OPERATORS = 'operators'; // список операторов (через запятую) (опционально)
	const PARAM_PRICE_FROM = 'pricefrom'; // цена от (в рублях, опционально)
	const PARAM_PRICE_TO = 'priceto'; // цена до (в рублях, опционально)
	const PARAM_CURRENCY = 'currency'; //валюта, в которой производить выдачу результатов поиска. 0 = рубли (по-умолчанию), 1 – у.е. (USD или EUR, зависит от страны), 2 – бел.рубли, 3 – тенге
	const PARAM_HIDE_REGULAR = 'hideregular'; //1 - скрыть туры на регулярных рейсах

	/**
	 * @param array $config
	 *
	 * @author Isakov Vlad <visakov@biletur.ru>
	 *
	 * TripsterApi constructor.
	 */
	public function __construct($config = []) {
		if (!empty($config)) {
			Yii::configure($this, $config);
		}
		parent::__construct($config);
	}

	/**
	 * @param string $value
	 *
	 * @return $this
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function setApiUrl($value) {
		$this->_apiUrl = $value;

		return $this;
	}

	/**
	 * @param string $value
	 *
	 * @return $this
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function setLogin($value) {
		$this->_login = $value;

		return $this;
	}

	/**
	 * @param string $value
	 *
	 * @return $this
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function setPassword($value) {
		$this->_password = $value;

		return $this;
	}

	/**
	 * Запрос к API
	 *
	 * @param string $method
	 * @param array  $params
	 *
	 * @return bool|mixed|string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function request($method, $params = []) {
		$queryString = http_build_query(array_merge($params, [static::PARAM_FORMAT => static::FORMAT_JSON]));
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $method, $params]);
		$results = Yii::$app->cache->get($cacheKey);

		if (false === $results) {
			$curl = curl_init($this->_apiUrl . $method . '&' . $queryString);
			curl_setopt_array($curl, [
				CURLOPT_RETURNTRANSFER => true,
			]);

			$results = curl_exec($curl);

			curl_close($curl);

			$results = json_decode($results);

			Yii::$app->cache->set($cacheKey, $results, 3600 * 8);
		}

		return $results;
	}
}