<?php

namespace common\forms\tour;

use common\base\helpers\Dump;
use common\components\tour\CommonTour;
use common\models\oracle\scheme\t3\RefItems;
use common\models\oracle\scheme\t3\RITourWps;
use Yii;
use yii\base\Model;
use yii\caching\TagDependency;
use yii\validators\NumberValidator;
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
	public $tourType;
	const ATTR_TOUR_TYPE = 'tourType';

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

	/** @var int */
	public $sortBy;
	const ATTR_SORT_BY = 'sortBy';

	/** @var int  */
	public $count = 0;
	const ATTR_COUNT = 'count';

	/** @var bool Подгрузка ли это при скролле */
	public $isLoad = false;
	const ATTR_IS_LOAD = 'isLoad';

	/** @var CommonTour[] */
	public $result;

	/** @var int Сортировка от дешевых */
	const SORT_TYPE_PRICE_MIN = 0;

	/** @var int Сортировка от дорогих */
	const SORT_TYPE_PRICE_MAX = 1;

	//Параметры пагинации
	const ITEMS_PER_PAGE = 15;

	public function __construct($config = []) {

		$this->priceMinMax = static::_getMinMaxPrices();
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
			static::ATTR_FROM_CITY => 'Из города',
			static::ATTR_TOUR_TO   => 'Страна, курорт, город',
			static::ATTR_TOUR_TYPE => 'Вид отдыха'
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
			[static::ATTR_TOUR_TYPE, StringValidator::class],
			[static::ATTR_PRICE_RANGE, SafeValidator::class],

			[static::ATTR_SORT_BY, SafeValidator::class],
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
			return $this->result;
		}

		//TODO тут дальше ищем другие туры по апи, мерджим с нашими и т.д.


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
		$filterPrice = explode(',', $this->priceRange);

		$query = RefItems::find();
		$query->joinWith(RefItems::REL_ACTIVE);

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

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, '$tours', $this->tourTo, $this->tourType, $this->cityInWayPoint]);
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

			$commonTour->prepare();

			//Фильтруем по ценам
			if (null === $commonTour->priceMinMax) {
				continue;
			}

			if (count($commonTour->priceMinMax) === 2) {
				if ((int)str_replace(' ', '', $commonTour->priceMinMax[0]) < $filterPrice[0]) {
					continue;
				}
				if ((int)str_replace(' ', '', $commonTour->priceMinMax[0]) > $filterPrice[1]) {
					continue;
				}
			}
			else {
				if ((int)str_replace(' ', '', $commonTour->priceMinMax[0]) < $filterPrice[0]) {
					continue;
				}
				if ((int)str_replace(' ', '', $commonTour->priceMinMax[0]) > $filterPrice[1]) {
					continue;
				}
			}

			$commonTours[] = $commonTour;
		}

		//Слайсим для подгрузки
		if (false === $this->isLoad) { //Если не подгрузка то отдаём с первого элемента, иначе берем номер из формы
			$this->count = 0;
		}

		$commonTours = array_slice($commonTours, $this->count, static::ITEMS_PER_PAGE);

		return $commonTours;
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

		return $result;
	}

	private static function _getMinMaxPrices() {
		$priceMinMaxArray = [];
		$query = RefItems::find();
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
		sort($priceMinMaxArray);

		return [$priceMinMaxArray[0], $priceMinMaxArray[count($priceMinMaxArray) - 1]];
	}
}