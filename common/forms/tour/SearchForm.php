<?php

namespace common\forms\tour;

use common\components\tour\CommonLap;
use common\components\tour\CommonTour;
use common\components\tour\CommonTourWayPoint;
use common\models\oracle\scheme\t3\RefItems;
use common\models\oracle\scheme\t3\RILaps;
use common\models\oracle\scheme\t3\RITour;
use common\models\oracle\scheme\t3\RITourWps;
use yii\base\Model;
use yii\caching\TagDependency;
use yii\validators\NumberValidator;
use yii\validators\StringValidator;
use Yii;

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

	/** @var int */
	public $sortBy;
	const ATTR_SORT_BY = 'sortBy';

	/** @var CommonTour[] */
	public $result;

	/** @var int Сортировка от дешевых */
	const SORT_TYPE_PRICE_MIN = 0;

	/** @var int Сортировка от дорогих */
	const SORT_TYPE_PRICE_MAX = 1;

	//Параметры пагинации
	const ITEMS_PER_PAGE = 20;

	public $page = 0;
	public $pageSize = self::ITEMS_PER_PAGE;
	public $pagination;

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
			[static::ATTR_SORT_BY, NumberValidator::class],
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
		/** @todo тут дальше ищем другие туры по апи, мерджим с нашими и т.д. */


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

			$cacheKey = Yii::$app->cache->buildKey([__METHOD__, '$tour->description', $tour->ID]);
			$description = Yii::$app->cache->get($cacheKey);
			if (false === $description) {
				$description = $tour->description;
				Yii::$app->cache->set($cacheKey, $description, 3600 * 8, new TagDependency(['tags' => RITour::class]));
			}

			$commonTour = new CommonTour();
			$commonTour->source = CommonTour::SOURCE_BILETUR;
			$commonTour->sourceId = $tour->ID;
			$commonTour->title = trim(strip_tags($tour->NAME));
			$commonTour->description = strip_tags((null === $description ? '' : $description->DESCRIPTION));
			$commonTour->priceMinMax = $tour->quotsSummMinMax();
			$commonTour->imageOld = (null !== $description ? $description->URL_IMG : null);
			$commonTour->image = $commonTour->getImage();

			//Заполняем точки маршрута
			$cacheKey = Yii::$app->cache->buildKey([__METHOD__, '$tour->wps', $tour->ID]);
			$wps = Yii::$app->cache->get($cacheKey);
			if (false === $wps) {
				$wps = $tour->wps;

				Yii::$app->cache->set($cacheKey, $wps, 3600 * 8, new TagDependency(['tags' => RITourWps::class]));
			}

			foreach ($wps as $wayPoint) {
				$commonWayPoint = new CommonTourWayPoint();
				$commonWayPoint->cityId = $wayPoint->CITYID;
				$commonWayPoint->country = $wayPoint->COUNTRY;
				$commonWayPoint->number = $wayPoint->NPP;
				$commonWayPoint->daysCount = $wayPoint->NDAYS;

				$commonTour->wayPoints[] = $commonWayPoint;
			}

			//Заполняем активные заезды
			$cacheKey = Yii::$app->cache->buildKey([__METHOD__, '$tour->activeLaps', $tour->ID]);
			$activeLaps = Yii::$app->cache->get($cacheKey);
			if (false === $activeLaps) {
				$activeLaps = $tour->activeLaps;

				Yii::$app->cache->set($cacheKey, $activeLaps, 3600 * 8, new TagDependency(['tags' => RILaps::class]));
			}

			foreach ($activeLaps as $activeLap) {
				$commonLap = new CommonLap();
				$commonLap->id = $activeLap->ID;
				$commonLap->startDate = $activeLap->BEGDATE;
				$commonLap->endDate = $activeLap->ENDDATE;

				$commonTour->activeLaps[] = $commonLap;
			}

			$commonTours[] = $commonTour;
		}

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
}