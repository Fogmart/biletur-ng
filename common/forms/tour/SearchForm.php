<?php

namespace common\forms\tour;

use common\components\tour\CommonTour;
use common\components\tour\tourtrans\Tour;
use common\models\oracle\scheme\t3\RefItems;
use common\models\oracle\scheme\t3\RIAd;
use common\models\oracle\scheme\t3\RITourWps;
use common\models\oracle\scheme\t3\TourTypes;
use Yii;
use yii\base\Model;
use yii\caching\TagDependency;
use yii\db\Expression;
use yii\mongodb\Query;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

/**
 * Форма поиска туров
 *
 * @package common\forms\tour
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class SearchForm extends Model {

	/** @var string Страна/город(по справочнику Билетур) */
	public $tourTo;
	const ATTR_TOUR_TO = 'tourTo';

	/** @var string Тип тура(по справочнику Билетур) */
	public $filterTourType;
	const ATTR_FILTER_TOUR_TYPE = 'filterTourType';

	/** @var string */
	public $filterDaysCount;
	const FILTER_DAYS_COUNT = 'filterDaysCount';

	/** @var string Из какого города(по справочнику Билетур) */
	public $fromCity;
	const ATTR_FROM_CITY = 'fromCity';

	/** @var string Город в точке маршрута */
	public $cityInWayPoint;
	const ATTR_CITY_IN_WAY_POINT = 'cityInWayPoint';

	/** @var string */
	public $priceRange;
	const ATTR_PRICE_RANGE = 'priceRange';

	/** @var array */
	public $priceMinMax;
	const ATTR_PRICE_MIN_MAX = 'priceMinMax';

	/** @var array */
	public $daysMinMax;
	const ATTR_DAYS_MIN_MAX = 'daysMinMax';

	/** @var string */
	public $hotelClass;
	const ATTR_HOTEL_CLASS = 'hotelClass';

	/** @var int */
	public $sortBy = self::SORT_TYPE_DEFAULT;
	const ATTR_SORT_BY = 'sortBy';

	/** @var int */
	public $sortDaysBy = self::SORT_TYPE_MIN;
	const ATTR_SORT_DAYS_BY = 'sortDaysBy';

	/** @var int */
	public $count = 0;
	const ATTR_COUNT = 'count';

	/** @var bool Подгрузка ли это при скролле */
	public $isLoad = false;
	const ATTR_IS_LOAD = 'isLoad';

	/** @var CommonTour[] */
	public $result;

	/** @var int Сортировка от меньшего */
	const SORT_TYPE_MIN = 0;

	/** @var int Сортировка от большего */
	const SORT_TYPE_MAX = 1;

	/** @var int Сортировка по-молчанию(Первые наши туры) */
	const SORT_TYPE_DEFAULT = 2;

	//Параметры пагинации
	const ITEMS_PER_PAGE = 15;

	/**
	 * @inheritDoc
	 *
	 * @author Isakov Vlad <visakov@biletur.ru>
	 *
	 */
	public function __construct($config = []) {

		$this->priceMinMax = static::_getMinMaxPrices();
		$this->daysMinMax = static::_getMinMaxDays();
		if (empty($this->priceRange)) {
			$this->priceRange = implode(',', $this->priceMinMax);
		}

		parent::__construct($config);
	}

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function attributeLabels() {
		return [
			static::ATTR_FROM_CITY        => 'Из города',
			static::ATTR_TOUR_TO          => 'Страна, курорт, город',
			static::ATTR_FILTER_TOUR_TYPE => 'Вид отдыха'
		];
	}

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function rules() {
		return [
			[static::ATTR_FROM_CITY, StringValidator::class],
			[static::ATTR_TOUR_TO, StringValidator::class],
			[static::ATTR_FILTER_TOUR_TYPE, SafeValidator::class],
			[static::ATTR_PRICE_RANGE, SafeValidator::class],
			[static::FILTER_DAYS_COUNT, SafeValidator::class],

			[static::ATTR_SORT_BY, SafeValidator::class],
			[static::ATTR_SORT_DAYS_BY, SafeValidator::class],
			[static::ATTR_COUNT, SafeValidator::class],
		];
	}

	/**
	 * Поиск
	 *
	 * @param bool $onlyBiletur
	 *
	 * @return \common\components\tour\CommonTour[]
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function search($onlyBiletur = false) {

		$this->result = $this->_searchBiletur();

		if ($onlyBiletur) {
			$this->_filter();
			$this->_sort();
			$this->_slice();

			return $this->result;
		}

		$this->result = array_merge($this->result, $this->_searchTransTour());

		//Фильтруем
		$this->_filter();

		//Сортируем
		$this->_sort();

		//Разрезаем на пагинацию для подгрузку аяксом
		$this->_slice();

		return $this->result;
	}

	/**
	 * Поиск и преобразование к общей структуре отображения туров Билетур
	 *
	 * @return CommonTour[]
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private function _searchBiletur() {
		$query = RefItems::find();
		$query->joinWith(RefItems::REL_ACTIVE, true, 'INNER JOIN');
		$query->andWhere([RefItems::tableName() . '.' . RefItems::ATTR_ACTIVE => 1]);
		$query->andWhere(['>', RefItems::tableName() . '.' . RefItems::ATTR_END_DATE, new Expression('sysdate')]);

		if (!empty($this->tourTo)) {
			$query->joinWith(RefItems::REL_WPS);
			if (false !== strpos($this->tourTo, 'country_')) {
				$query->andWhere(['LIKE', RITourWps::tableName() . '.' . RITourWps::ATTR_COUNTRY, str_replace('country_', '', $this->tourTo)]);
			}
			else {
				$query->andWhere([RITourWps::tableName() . '.' . RITourWps::ATTR_CITY_ID => $this->tourTo]);
			}

			$query->andWhere([RITourWps::tableName() . '.' . RITourWps::ATTR_DESTINATION_POINT => 1]);
		}

		//Фильтр по типу тура
		if (!empty($this->filterTourType)) {
			$query->joinWith(RefItems::REL_TYPES);
			$query->andWhere([TourTypes::tableName() . '.' . TourTypes::ATTR_ID => (int)$this->filterTourType]);
		}

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, '$tours', $this->tourTo, $this->filterTourType, $this->cityInWayPoint, $this->priceRange]);
		/** @var RefItems[] $tours */
		$tours = Yii::$app->cache->get($cacheKey);
		if (false === $tours) {
			$tours = $query->all();
			Yii::$app->cache->set($cacheKey, $tours, 3600 * 8, new TagDependency(['tags' => [RefItems::class, RITourWps::class]]));
		}

		$commonTours = [];
		foreach ($tours as $tour) {
			$commonTour = new CommonTour([
					CommonTour::ATTR_SOURCE_ID        => $tour->ID,
					CommonTour::ATTR_SOURCE           => CommonTour::SOURCE_BILETUR,
					CommonTour::ATTR_SOURCE_TOUR_DATA => $tour
				]
			);
			//Приводим данные тура к общему объекту
			$commonTour->prepare();
			$commonTours[] = $commonTour;
		}

		return $commonTours;
	}

	/**
	 * Поиск туров Туртранс(mongoDb)
	 *
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private function _searchTransTour() {
		$commonTours = [];
		$query = new Query();

		/** @var \common\components\tour\tourtrans\Tour[] $tours */
		$query = $query->select([])->from(Tour::COLLECTION_TOURS);

		if (!empty($this->tourTo)) {
			if (false !== strpos($this->tourTo, 'country_')) {
				$query->andWhere(['in', 'countries', str_replace('country_', '', $this->tourTo)]);
			}
			else {
				$query->andWhere(['in', 'cities', $this->tourTo]);
			}
		}

		$query->limit(300);
		$tours = $query->all();

		foreach ($tours as $tour) {
			$tour = json_decode($tour['objectData']);
			if ($tour->minPrice == 0) {
				continue;
			}

			$commonTour = new CommonTour([
					CommonTour::ATTR_SOURCE_ID        => $tour->id,
					CommonTour::ATTR_SOURCE           => CommonTour::SOURCE_TOURTRANS,
					CommonTour::ATTR_SOURCE_TOUR_DATA => $tour
				]
			);

			//Приводим данные тура к общему объекту
			$commonTour->prepare();
			$commonTours[] = $commonTour;
		}

		return $commonTours;
	}

	/**
	 * Общая фильтрация
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private function _filter() {
		$filterPrice = explode(',', $this->priceRange);
		$filteredTours = [];
		foreach ($this->result as $commonTour) {
			//Фильтруем по ценам
			if (null === $commonTour->priceMinMax) {
				continue;
			}

			if (count($commonTour->priceMinMax) === 2) {
				if ((int)$commonTour->priceMinMax[0] < $filterPrice[0]) {
					continue;
				}
				if ((int)$commonTour->priceMinMax[0] > $filterPrice[1]) {
					continue;
				}
			}
			else {
				if ((int)$commonTour->priceMinMax[0] < $filterPrice[0]) {
					continue;
				}
				if ((int)$commonTour->priceMinMax[0] > $filterPrice[1]) {
					continue;
				}
			}

			//Фильтруем по кол-ву дней
			if (!empty($this->filterDaysCount)) {
				$filterDays = explode(',', $this->filterDaysCount);
				$filterDaysMin = $filterDays[0];
				$filterDaysMax = $filterDays[1];
				if ((int)$commonTour->daysCount < (int)$filterDaysMin || (int)$commonTour->daysCount > (int)$filterDaysMax) {
					continue;
				}
			}

			$filteredTours[] = $commonTour;
		}

		$this->result = $filteredTours;
	}

	/**
	 * Сортировка результата
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private function _sort() {
		//Сортировка по цене
		switch ($this->sortBy) {
			case static::SORT_TYPE_MIN:
				usort($this->result, function ($a, $b) {
					return (int)$a->priceMinMax[0] > (int)$b->priceMinMax[0];
				});
				break;
			case static::SORT_TYPE_MAX:
				usort($this->result, function ($a, $b) {
					return (int)$a->priceMinMax[0] < (int)$b->priceMinMax[0];
				});
				break;
			default:
				usort($this->result, function ($a, $b) {
					return (int)$a->source > (int)$b->source;
				});
				break;
		}
	}

	/**
	 * Пагинация результата
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private function _slice() {
		//Если не подгрузка то отдаём с первого элемента, иначе берем номер из формы
		if (false === $this->isLoad) {
			$this->count = 0;
		}

		$this->result = array_slice($this->result, $this->count, static::ITEMS_PER_PAGE);
	}

	/**
	 * Возможные направления туров
	 *
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getTourToPaths() {
		$result = [];
		$paths = RITourWps::getActiveRegions();

		foreach ($paths as $country => $cities) {
			$result['country_' . $country] = $country;
			foreach ($cities as $id => $city) {
				$result[$id] = $city;
			}
		}

		$cacheKey = Yii::$app->cache->buildKey([Tour::COLLECTION_FILTERS_GEO]);
		$tourtransRoutes = Yii::$app->cache->get($cacheKey);
		//Если нет фильтров туртранса, то перезагрузим данные в mongoDb и установим их
		if (false === $tourtransRoutes) {
			Tour::loadFromXml();
			$tourtransRoutes = Yii::$app->cache->get($cacheKey);
		}

		$result = array_merge($result, $tourtransRoutes);

		$result = array_unique($result);

		return $result;
	}

	/**
	 * Максимальная и минимальная цена доступных туров
	 *
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private static function _getMinMaxPrices() {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, 1]);
		$priceMinMaxArray = Yii::$app->cache->get($cacheKey);
		if (false === $priceMinMaxArray) {
			$priceMinMaxArray = [];
			$query = RefItems::find();
			$query->andWhere([RefItems::tableName() . '.' . RefItems::ATTR_ACTIVE => 1]);
			$query->andWhere(['>', RefItems::tableName() . '.' . RefItems::ATTR_END_DATE, new Expression('sysdate')]);
			$query->joinWith(RefItems::REL_ACTIVE);
			$tours = $query->all();
			/** @var RefItems[] $tours */
			foreach ($tours as $tour) {
				$minMax = $tour->quotsSummMinMax(false);
				if (null === $minMax) {
					continue;
				}
				if (is_array($minMax)) {
					$priceMinMaxArray[] = (int)$minMax[0];
				}
				else {
					$priceMinMaxArray[] = (int)$minMax;
				}
			}

			$query = new Query();

			//Цены туртранса
			$tourTransTours = $query->select(['minPrice'])->from(Tour::COLLECTION_TOURS)->all();
			foreach ($tourTransTours as $tourTransTour) {
				$priceMinMaxArray[] = $tourTransTour['minPrice'];
			}

			sort($priceMinMaxArray);

			Yii::$app->cache->set($cacheKey, $priceMinMaxArray, 3600 * 8, new TagDependency(['tags' => [RefItems::class, RIAd::class, Tour::class]]));
		}


		return [$priceMinMaxArray[0], $priceMinMaxArray[count($priceMinMaxArray) - 1]];
	}

	/**
	 * Минимальное/максимальное кол-во дней в турах
	 *
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private static function _getMinMaxDays() {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, 5]);
		$daysMinMaxArray = Yii::$app->cache->get($cacheKey);
		if (false === $daysMinMaxArray) {
			$daysMinMaxArray = [];
			$query = RefItems::find();
			$query->joinWith(RefItems::REL_ACTIVE, true, 'INNER JOIN');
			$query->andWhere([RefItems::tableName() . '.' . RefItems::ATTR_ACTIVE => 1]);
			$query->andWhere(['>', RefItems::tableName() . '.' . RefItems::ATTR_END_DATE, new Expression('sysdate')]);

			$query->joinWith(RefItems::REL_WPS);
			/** @var RefItems[] $tours */
			$tours = $query->all();
			foreach ($tours as $tour) {
				$days = 0;
				foreach ($tour->wps as $wps) {
					if ($wps->NPP == 1) {
						continue;
					}
					if ($wps->COUNTRY == null) {
						continue;
					}
					if ($wps->CITYID == null) {
						continue;
					}

					$days = $days + (int)$wps->NDAYS;
				}
				$daysMinMaxArray[$tour->ID] = $days;
			}

			//Кол-во дней туров туртранса
			$query = new Query();
			$tourTransTours = $query->select(['id', 'duration'])->from(Tour::COLLECTION_TOURS)->all();
			foreach ($tourTransTours as $tourTransTour) {
				$daysMinMaxArray['tt_' . $tourTransTour['id']] = $tourTransTour['duration'];
			}

			$daysMinMaxArray = array_unique($daysMinMaxArray);

			sort($daysMinMaxArray);

			Yii::$app->cache->set($cacheKey, $daysMinMaxArray, 3600 * 8, new TagDependency(['tags' => [RefItems::class, RITourWps::class, Tour::class]]));
		}

		return [$daysMinMaxArray[0], $daysMinMaxArray[count($daysMinMaxArray) - 1]];
	}

	/**
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getTypes() {
		return TourTypes::getActive();
	}
}