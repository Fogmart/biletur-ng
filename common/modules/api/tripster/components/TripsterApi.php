<?php

namespace common\modules\api\tripster\components;

use common\components\excursion\CommonExcursion;
use Yii;
use yii\base\Component;
use yii\base\Configurable;

class TripsterApi extends Component implements Configurable {

	private $_token;

	private $_url;

	/** @var string Автокомплит по городам @see https://tripster.atlassian.net/wiki/spaces/affiliates/pages/1185677313 */
	const METHOD_SEARCH = 'search/site';

	/** @var string Страны */
	const METHOD_COUNTRIES = 'countries';

	/** @var string Города */
	const METHOD_CITIES = 'cities';

	/** @var string Эксукрсии */
	const METHOD_EXPERIENCES = 'experiences';

	/** @var string Рубрики экскурсий */
	const METHOD_CITY_TAGS = 'citytags';

	/** @var string Отзывы для экскурсии */
	const SUB_METHOD_REVIEWS = 'reviews';

	const PARAM_CITY_NAME_RU = 'city__name_ru';
	const PARAM_CITY_ID = 'city';
	const PARAM_CITY_TAG = 'citytag';
	const PARAM_SORTING = 'sorting';
	const PARAM_PAGE = 'page';

	const TYPE_GROUP = 'group';
	const TYPE_PRIVATE = 'private';

	const COMMON_TYPE_LINK = [
		self::TYPE_GROUP   => CommonExcursion::TYPE_GROUP,
		self::TYPE_PRIVATE => CommonExcursion::TYPE_PRIVATE,
	];

	/** @var string Страны */
	const AUTOCOMPLETE_TYPE_COUNTRY = 'country';

	/** @var string Города */
	const AUTOCOMPLETE_TYPE_CITY = 'city';

	/** @var string Рубрики */
	const AUTOCOMPLETE_TYPE_CITY_TAG = 'citytag';

	/** @var string Экскурсии */
	const AUTOCOMPLETE_EXCURSION = 'experience';

	const AUTOCOMPLETE_TYPE_NAMES = [
		self::AUTOCOMPLETE_EXCURSION     => 'Популярные экскурсии',
		self::AUTOCOMPLETE_TYPE_CITY     => 'Города',
		self::AUTOCOMPLETE_TYPE_COUNTRY  => 'Страны',
		self::AUTOCOMPLETE_TYPE_CITY_TAG => 'Рубрики',
	];

	const UTM = '?exp_partner=biletur&utm_source=biletur&utm_campaign=affiliates&utm_medium=api';

	const PAGE_SIZE = 20;

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
	public function setToken($value) {
		$this->_token = $value;

		return $this;
	}

	/**
	 * @param string $value
	 *
	 * @return $this
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function setUrl($value) {
		$this->_url = $value;

		return $this;
	}

	/**
	 * Запрос к АПИ
	 *
	 * @param string $resource
	 * @param array  $parameters
	 *
	 * @return mixed
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function sendRequest($resource, array $parameters = []) {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $resource, $parameters, 3]);
		$results = Yii::$app->cache->get($cacheKey);
		if (false === $results) {
			$query_string = http_build_query($parameters);
			$curl = curl_init("$this->_url/$resource/?$query_string");

			curl_setopt_array($curl, [
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER     => [
					'Authorization: Token ' . $this->_token,
					'Content-Type: application/json'
				]
			]);

			$results = curl_exec($curl);

			curl_close($curl);

			$results = json_decode($results);

			Yii::$app->cache->set($cacheKey, $results, 3600 * 24 * 7);
		}

		return $results;
	}

	/**
	 * Получение расписания для экскурссии
	 *
	 * @param int $id
	 *
	 * @return mixed
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getSchedule($id) {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $id]);
		$results = Yii::$app->cache->get($cacheKey);
		if (false === $results) {
			$curl = curl_init($this->_url . '/experiences/' . $id . '/schedule/');

			curl_setopt_array($curl, [
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER     => [
					'Authorization: Token ' . $this->_token,
					'Content-Type: application/json'
				]
			]);

			$results = curl_exec($curl);

			curl_close($curl);

			$results = json_decode($results);

			Yii::$app->cache->set($cacheKey, $results, 3600 * 24);
		}

		return $results;
	}
}