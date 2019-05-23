<?php

namespace common\forms\excursion;

use common\modules\api\tripster\components\TripsterApi;
use sem\helpers\ArrayHelper;
use Yii;
use yii\base\Model;
use yii\validators\RequiredValidator;
use common\components\excursion\CommonExcursion;
use yii\validators\StringValidator;

/**
 * Общая форма поиска экскурсий
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class SearchForm extends Model {

	/** @var string int Город */
	public $city;
	const ATTR_CITY = 'city';

	/** @var int Рубрика */
	public $cityTag;
	const ATTR_CITY_TAG = 'cityTag';

	/** @var string */
	public $source = self::API_SOURCE_TRIPSTER;
	const ATTR_SOURCE = 'source';

	/** @var CommonExcursion[] Экскурсии */
	public $result = [];

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


		return [];
	}

	/**
	 * Возвращает данные последнего автокомплита для их восстановления после отправки формы
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

	public function getLastAutocompleteCityTagTripster() {
		$cacheKey = Yii::$app->cache->buildKey([TripsterApi::class . TripsterApi::AUTOCOMPLETE_TYPE_CITY_TAG, Yii::$app->session->id]);
		$result = Yii::$app->cache->get($cacheKey);

		if (false === $result) {
			$result = [];
		}
		else {
			$result = ArrayHelper::map($result['results'], 'id', 'text');
		}

		return $result;
	}

}