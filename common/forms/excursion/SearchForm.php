<?php

namespace common\forms\excursion;

use common\base\helpers\StringHelper;
use common\components\CurrencySync;
use common\components\excursion\CommonExcursion;
use common\components\excursion\CommonGuide;
use common\components\excursion\CommonPrice;
use common\components\excursion\CommonPriceDiscount;
use common\components\excursion\CommonSchedule;
use common\modules\api\tripster\components\TripsterApi;
use sem\helpers\ArrayHelper;
use Yii;
use yii\base\Model;
use yii\validators\NumberValidator;
use yii\validators\SafeValidator;
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

	/** @var string Страна */
	public $country;
	const ATTR_COUNTRY = 'country';

	const SORT_POPULARITY = '-popularity';
	const SORT_PRICE = 'price';

	/** @var string */
	public $source = self::API_SOURCE_TRIPSTER;
	const ATTR_SOURCE = 'source';

	/** @var int */
	public $page = 1;
	const ATTR_PAGE = 'page';

	/** @var string */
	public $priceRange;
	const ATTR_PRICE_RANGE = 'priceRange';

	/** @var string */
	public $timeRange;
	const ATTR_TIME_RANGE = 'timeRange';

	/** @var array Минимальная и максимальная цена для фильтра */
	public $priceMinMax = [];

	/** @var array Минимальная и максимальная продолжительность для фильтра */
	public $timeMinMax = [];

	/** @var CommonExcursion[] Экскурсии */
	public $result = [];

	public $tags = [];

	/** @var int Кол-во страниц */
	public $pageCount = 0;

	/** @var bool Подгрузка ли это */
	public $isLoad = false;
	const ATTR_IS_LOAD = 'isLoad';

	const API_SOURCE_TRIPSTER = 0;

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function rules() {
		return [
			[static::ATTR_CITY, StringValidator::class],
			[static::ATTR_CITY_NAME, StringValidator::class],
			[static::ATTR_CITY_TAG, StringValidator::class],
			[static::ATTR_PAGE, NumberValidator::class],
			[static::ATTR_PRICE_RANGE, SafeValidator::class],
			[static::ATTR_TIME_RANGE, SafeValidator::class],
		];
	}

	/**
	 * @return array|bool|\common\components\excursion\CommonExcursion[]
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function search() {
		$this->result = $this->_searchTripster();

		return $this->result;
	}

	/**
	 * Поиск экскурсий по Апи Трипстер
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private function _searchTripster() {
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
		$excursions = [];

		//Если нет города то грузим первые 20, кторые отдает трипстер, иначе загружаем все по 100 штук в массив и отдаем слайсом
		if (empty($this->city) && empty($this->cityName)) {
			$params[$api::PAGE_SIZE] = 20;
			$page_results = $api->sendRequest($api::METHOD_EXPERIENCES, $params);
			$excursions = $page_results->results;
		}
		else {
			$page = 1;
			$params[$api::PAGE_SIZE] = 100;
			while (true) {
				$params[$api::PARAM_PAGE] = $page;
				$page_results = $api->sendRequest($api::METHOD_EXPERIENCES, $params);

				// Добавляем экскурсии к общему массиву экскурсий
				if (!property_exists($page_results, 'results')) {
					break;
				}

				$excursions = array_merge($excursions, $page_results->results);

				// Если это последняя страница — заканчиваем, иначе запрашиваем следующую
				if (!array_key_exists('next', $page_results)) {
					break;
				}
				$page++;
			}
		}

		foreach ($excursions as $excursion) {
			$excursion->url = $excursion->url . $api::UTM;

			$excursion->city->url = $excursion->city->url . $api::UTM;
			$excursion->city->country->url = $excursion->city->country->url . $api::UTM;
			$excursion->guide->url = $excursion->guide->url . $api::UTM;

			foreach ($excursion->tags as &$tag) {
				$this->tags[$tag->id] = $tag;
				$tag->url = $tag->url . $api::UTM;
			}
		}

		ksort($this->tags);

		$commonExcursions = [];

		//Конвертируем в Common объекты
		foreach ($excursions as $tripsterExcursion) {
			$commonExcursion = new CommonExcursion();
			$commonExcursion->id = $tripsterExcursion->id;
			$commonExcursion->name = $tripsterExcursion->title;
			$commonExcursion->annotation = $tripsterExcursion->tagline;
			$commonExcursion->url = $tripsterExcursion->url;
			$commonExcursion->sourceApi = CommonExcursion::SOURCE_API_TRIPSTER;
			$commonExcursion->instantBooking = $tripsterExcursion->instant_booking;
			$commonExcursion->childFriendly = $tripsterExcursion->child_friendly;
			$commonExcursion->maxPersons = $tripsterExcursion->max_persons;
			$commonExcursion->duration = $tripsterExcursion->duration;
			$commonExcursion->rating = $tripsterExcursion->rating;
			$commonExcursion->popularity = $tripsterExcursion->popularity;
			$commonExcursion->reviewCount = $tripsterExcursion->review_count;

			$commonExcursion->price = new CommonPrice();
			$commonExcursion->price->value = $tripsterExcursion->price->value;
			$commonExcursion->price->valueString = $tripsterExcursion->price->value_string;
			$commonExcursion->price->unitString = $tripsterExcursion->price->unit_string;
			$commonExcursion->price->currency = $tripsterExcursion->price->currency;

			switch ($commonExcursion->price->currency) {
				case 'RUB':
					$commonExcursion->price->currency = StringHelper::CURRENCY_RUB_SIGN;
					$this->priceMinMax[] = $tripsterExcursion->price->value;
					$commonExcursion->price->rubPrice = $tripsterExcursion->price->value;
					break;
				case 'EUR':
					$commonExcursion->price->currency = StringHelper::CURRENCY_EUR_SIGN;
					$rubPrice = round($tripsterExcursion->price->value * CurrencySync::getCurRate('EUR'), 0);
					$this->priceMinMax[] = $rubPrice;
					$commonExcursion->price->rubPrice = $rubPrice;
					break;
				case 'USD':
					$commonExcursion->price->currency = StringHelper::CURRENCY_USD_SIGN;
					$rubPrice = round($tripsterExcursion->price->value * CurrencySync::getCurRate('USD'), 0);
					$this->priceMinMax[] = $rubPrice;
					$commonExcursion->price->rubPrice = $rubPrice;
					break;
				default:
					$this->priceMinMax[] = $tripsterExcursion->price->value;
					$commonExcursion->price->rubPrice = $tripsterExcursion->price->value;
					break;
			}

			if (null !== $tripsterExcursion->price->discount) {
				$commonExcursion->price->discount = new CommonPriceDiscount();
				$commonExcursion->price->discount->value = $tripsterExcursion->price->discount->value;
				$commonExcursion->price->discount->oldPrice = $tripsterExcursion->price->discount->original_price;
			}

			$commonExcursion->city = $tripsterExcursion->city->name_ru;

			$commonExcursion->guide = new CommonGuide();
			$commonExcursion->guide->firstName = $tripsterExcursion->guide->first_name;
			$commonExcursion->guide->url = $tripsterExcursion->guide->url;
			$commonExcursion->guide->avatarSmall = $tripsterExcursion->guide->avatar->small;
			$commonExcursion->guide->avatarMedium = $tripsterExcursion->guide->avatar->medium;

			if (array_key_exists('photos', $tripsterExcursion)) {
				$commonExcursion->image = $tripsterExcursion->photos[0]->medium;
			}

			$commonExcursion->type = TripsterApi::COMMON_TYPE_LINK[$tripsterExcursion->type];

			$schedule = $api->getSchedule($commonExcursion->id);
			$commonSchedules = [];

			foreach ($schedule->schedule as $date => $times) {
				foreach ($times as $time) {
					$commonSchedule = new CommonSchedule();
					$commonSchedule->date = $date;

					if ($time->type == 'slot') {
						$commonSchedule->timeStart = $time->time;
					}
					else {
						$commonSchedule->timeStart = $time->time_start;
					}

					$commonSchedules[$date] = $commonSchedule;
				}
			}

			$commonExcursion->schedule = $commonSchedules;

			$commonExcursions[] = $commonExcursion;
			$this->timeMinMax[] = round($commonExcursion->duration, 0);
		}

		sort($this->priceMinMax);
		sort($this->timeMinMax);

		$this->priceMinMax = [
			$this->priceMinMax[0],
			$this->priceMinMax[count($this->priceMinMax) - 1]
		];

		$this->timeMinMax = [
			$this->timeMinMax[0],
			$this->timeMinMax[count($this->timeMinMax) - 1]
		];

		//Фильтруем массив по цене
		if (null !== ($this->priceRange)) {
			$priceRange = explode(',', $this->priceRange);
			/** @var CommonExcursion $excursion */
			foreach ($commonExcursions as $index => $excursion) {
				if ($excursion->price->rubPrice < $priceRange[0] || $excursion->price->rubPrice > $priceRange[1]) {
					unset($commonExcursions[$index]);
				}
			}
		}

		//Фильтруем массив по длительности
		if (null !== ($this->timeRange)) {
			$timeRange = explode(',', $this->timeRange);
			/** @var CommonExcursion $excursion */
			foreach ($commonExcursions as $index => $excursion) {
				if ($excursion->duration < $timeRange[0] || $excursion->duration > $timeRange[1]) {
					unset($commonExcursions[$index]);
				}
			}
		}

		//Оттдаем постранично
		return array_slice($commonExcursions, $this->page, 10);
	}

	/**
	 * Возвращает данные последнего автокомплита города для их восстановления после отправки формы
	 *
	 * @param string $cityName
	 *
	 * @return mixed
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getLastAutocompleteCityTripster($cityName = null) {
		if (null !== $cityName) {

		}

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
}