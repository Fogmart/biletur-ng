<?php

namespace common\forms\excursion;

use common\components\excursion\CommonExcursion;
use common\modules\api\tripster\components\TripsterApi;
use sem\helpers\ArrayHelper;
use Yii;
use yii\base\Model;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

/**
 * Общая форма поиска экскурсий
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class SearchForm extends Model {

	/** @var int Город ID */
	public $city;
	const ATTR_CITY = 'city';

	/** @var string Город  название */
	public $cityName;
	const ATTR_CITY_NAME = 'cityName';

	/** @var int Рубрика */
	public $cityTag;
	const ATTR_CITY_TAG = 'cityTag';

	/** @var string */
	public $source = self::API_SOURCE_TRIPSTER;
	const ATTR_SOURCE = 'source';

	public $page = 1;
	const ATTR_PAGE = 'page';

	/** @var CommonExcursion[] Экскурсии */
	public $result = [];

	public $tags = [];

	/** @var int Кол-во страниц */
	public $pageCount = 0;

	const API_SOURCE_TRIPSTER = 0;

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function rules() {
		return [
			[static::ATTR_CITY, RequiredValidator::class, 'message' => 'Пожалуйста, выберите город'],
			[static::ATTR_CITY_TAG, StringValidator::class],
			[static::ATTR_PAGE, NumberValidator::class],
		];
	}

	public function search() {
		$this->result = $this->searchTripster();

		return $this->result;
	}

	/**
	 * Поиск экскурсий по Апи Трипстер
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function searchTripster() {
		$params = [];
		$api = Yii::$app->tripsterApi;

		if (null !== $this->city) {
			$params[$api::PARAM_CITY_ID] = $this->city;
		}
		elseif (null !== $this->cityName) {
			$params[$api::PARAM_CITY_NAME_RU] = $this->cityName;
		}

		if (null !== $this->cityTag) {
			$params[$api::PARAM_CITY_TAG] = $this->cityTag;
		}

		if (null !== $this->page && !empty($this->page)) {
			$params[$api::PARAM_PAGE] = $this->page;
		}

		$params[$api::PARAM_SORTING] = '-popularity';

		$response = $api->sendRequest($api::METHOD_EXPERIENCES, $params);

		foreach ($response->results as $excursion) {
			$excursion->url = $excursion->url . $api::UTM;

			$excursion->city->url = $excursion->city->url . $api::UTM;
			$excursion->city->country->url = $excursion->city->country->url . $api::UTM;
			$excursion->guide->url = $excursion->guide->url . $api::UTM;

			foreach ($excursion->tags as &$tag) {
				$this->tags[$tag->id] = $tag;
				$tag->url = $tag->url . $api::UTM;
			}
		}

		$this->pageCount = ceil((int)$response->count / $api::PAGE_SIZE);

		return $response;
	}

	/**
	 * Возвращает данные последнего автокомплита города для их восстановления после отправки формы
	 *
	 * @return mixed
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getLastAutocompleteCityTripster() {
		$cacheKey = Yii::$app->cache->buildKey([TripsterApi::class . TripsterApi::AUTOCOMPLETE_TYPE_CITY, Yii::$app->session->id]);
		$result = Yii::$app->cache->get($cacheKey);

		if (false === $result) {
			$result = [];
		}
		else {
			$result = ArrayHelper::map($result['results'], 'id', 'text');
		}

		return $result;
	}

	/*public function getLastAutocompleteCityTagTripster() {
		$cacheKey = Yii::$app->cache->buildKey([TripsterApi::class . TripsterApi::AUTOCOMPLETE_TYPE_CITY_TAG, Yii::$app->session->id]);
		$result = Yii::$app->cache->get($cacheKey);

		if (false === $result) {
			$result = [];
		}
		else {
			$result = ArrayHelper::map($result['results'], 'id', 'text');
		}

		return $result;
	}*/

}