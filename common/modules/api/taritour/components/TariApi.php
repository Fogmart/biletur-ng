<?php

namespace common\modules\api\taritour\components;

use Yii;
use yii\base\Component;
use yii\base\Configurable;

/**
 * API ТариТур
 *
 * @package common\modules\api\taritour\components
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class TariApi extends Component implements Configurable {

	const FORMAT_JSON = 'json';

	const PARAM_COUNT = 'count';
	const PARAM_COUNTRY_ID = 'countryId';
	const PARAM_DEPART_CITY_ID = 'departCityId';
	const PARAM_DATE_FROM = 'dateFrom';
	const PARAM_DATE_TO = 'dateTo';
	const PARAM_ADULTS_COUNT = 'adults';
	const PARAM_KIDS_COUNT = 'kids ';
	const PARAM_KIDS_AGES = 'kidsAges';
	const PARAM_NIGHTS_MIN = 'nightsMin';
	const PARAM_NIGHTS_MAX = 'nightsMax';
	const PARAM_RESORTS = 'resorts';
	const PARAM_HOTEL_CATEGORIES = 'hotelCategories';
	const PARAM_HOTELS = 'hotels';
	const PARAM_MEALS = 'meals';
	const PARAM_CURRENCY_ID = 'currencyId';
	const PARAM_PRICE_MIN = 'priceMin';
	const PARAM_PRICE_MAX = 'priceMax';
	const PARAM_HOTEL_IS_NOT_IN_STOP = 'hotelIsNotInStop';
	const PARAM_FORMAT = 'format';

	const METHOD_TOURS_GET_TOURS = 'tours.gettours';
	const METHOD_TOURS_GET_TOURS_2 = 'GetTours';
	const METHOD_TOURS_GET_PROGRAM = 'tours.gettourprog';
	const METHOD_TOURS_GET_TYPES = 'tours.gettourtypes';
	const METHOD_TOURS_GET_NAMES = 'tours.gettoursname';
	const METHOD_TOURS_GET_NAMES_DESC = 'tours.gettoursnamedesc';
	const METHOD_TOURS_GET_LAPS = 'tours.gettourtrips';
	const METHOD_TOURS_GET_BY_HOTEL = 'tours.gettoursbyhotel';

	const METHOD_GET_CITIES = 'cities.getcities';
	const METHOD_GET_CURRENCY = 'curs.getcurs';
	const METHOD_GET_COUNTRIES = 'countries.getcountries';

	private $_apiUrl;

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
	 * Запрос к API
	 *
	 * @param string $method
	 * @param array  $params
	 *
	 * @return bool|mixed|string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function request($method, $params) {
		$queryString = http_build_query(array_merge($params, [static::PARAM_FORMAT => static::FORMAT_JSON]));

		$curl = curl_init($this->_apiUrl . $method . '&' . $queryString);

		$results = curl_exec($curl);

		curl_close($curl);

		$results = json_decode($results);

		return $results;
	}
}