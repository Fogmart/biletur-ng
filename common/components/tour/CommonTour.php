<?php

namespace common\components\tour;

use BadFunctionCallException;
use common\components\RemoteImageCache;
use common\components\tour\tari\Resort;
use common\components\tour\tari\Tour as TariTour;
use common\components\tour\tourtrans\Tour;
use common\models\Country;
use common\models\ObjectFile;
use common\models\oracle\scheme\t3\RefItems;
use common\models\oracle\scheme\t3\RILaps;
use common\models\oracle\scheme\t3\RITour;
use common\models\oracle\scheme\t3\RITourWps;
use common\models\Town;
use Yii;
use yii\base\Component;
use yii\caching\TagDependency;
use yii\mongodb\Query;

class CommonTour extends Component {
	const SOURCE_BILETUR = 0;
	const SOURCE_TOURTRANS = 1;
	const SOURCE_TARI_TOUR = 2;

	/** @var int Источник тура */
	public $source;
	const ATTR_SOURCE = 'source';

	/** @var int|string Идентификатор в системе источника */
	public $sourceId;
	const ATTR_SOURCE_ID = 'sourceId';

	/** @var RefItems */
	public $sourceTourData;
	const ATTR_SOURCE_TOUR_DATA = 'sourceTourData';

	/** @var [] */
	public $sourceTourAdditionalData;
	const ATTR_SOURCE_TOUR_ADDITIONAL_DATA = 'sourceTourAdditionalData';

	/** @var string */
	public $title;

	/** @var string */
	public $beginDate;
	const ATTR_BEGIN_DATE = 'beginDate';

	/** @var string */
	public $endDate;
	const ATTR_END_DATE = 'endDate';

	/** @var array Минимальная[0]/максимальная[1] цена тура */
	public $priceMinMax;
	const ATTR_PRICE_MIN_MAX = 'priceMinMax';

	/** @var string */
	public $imageOld;
	const ATTR_IMAGE_OLD = 'imageOld';

	/** @var string */
	public $image;
	const ATTR_IMAGE = 'image';

	/** @var string[] Дополнительные фотографии, если есть */
	public $additionalImages = [];
	const ATTR_ADDITIONAL_IMAGES = 'additionalImages';

	/** @var string Описание */
	public $description;
	const ATTR_DESCRIPTION = 'description';

	/** @var int Общее Кол-во дней */
	public $daysCount;
	const ATTR_DAYS = 'daysCount';

	/** @var bool Флаг горячего тура */
	public $isHotTour = false;
	const ATTR_IS_HOT_TOUR = 'isHotTour';

	/** @var bool Флаг прямого рейса */
	public $isDirectFlight = false;
	const ATTR_IS_DIRECT_FLIGHT = 'isDirectFlight';

	/** @var string */
	public $sourceUrl;
	const ATTR_URL = 'url';

	/** @var \common\components\tour\CommonTourWayPoint[] */
	public $wayPoints = [];
	const ATTR_WAY_POINTS = 'wayPoints';

	/** @var \common\components\tour\CommonLap[] */
	public $activeLaps = [];
	const ATTR_ACTIVE_LAPS = 'activeLaps';

	/**
	 * Конвертация тура к общему представлению
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function prepare() {
		if (null === $this->source) {
			throw new BadFunctionCallException('Не задан параметр source');
		}
		if (null === $this->sourceId) {
			throw new BadFunctionCallException('Не задан параметр sourceId');
		}

		//Каждому источнику соответствующий метод
		switch ($this->source) {
			case static::SOURCE_BILETUR:
				$this->_prepareBiletur();
				break;
			case static::SOURCE_TOURTRANS:
				$this->_prepareTourtrans();
				break;
			case static::SOURCE_TARI_TOUR:
				$this->_prepareTariTour();
				break;
			default:
				throw new BadFunctionCallException('Не задан обработчик для источника');
				break;
		}
	}

	/**
	 * Конвертация тура Билетур к общему представлению
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private function _prepareBiletur() {
		/** @var RefItems $tour */
		$tour = $this->sourceTourData;

		if (null === $tour) {
			$tour = RefItems::find()->where([RefItems::ATTR_ID => $this->sourceId])->one();
		}

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, '$tour->description', $tour->ID]);
		$description = Yii::$app->cache->get($cacheKey);
		if (false === $description) {
			$description = $tour->description;
			Yii::$app->cache->set($cacheKey, $description, 3600 * 24, new TagDependency(['tags' => RITour::class]));
		}

		$this->title = trim(strip_tags($tour->NAME));
		$this->sourceId = $tour->ID;
		$this->description = strip_tags((null === $description ? '' : $description->DESCRIPTION));
		$this->priceMinMax = $tour->quotsSummMinMax();
		$this->source = CommonTour::SOURCE_BILETUR;
		$this->imageOld = (null !== $description ? $description->URL_IMG : null);
		$this->image = $this->getImage();

		//Заполняем точки маршрута
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, '$tour->wps', $tour->ID]);
		$wps = Yii::$app->cache->get($cacheKey);
		if (false === $wps) {
			$wps = $tour->wps;

			Yii::$app->cache->set($cacheKey, $wps, 3600 * 24, new TagDependency(['tags' => RITourWps::class]));
		}

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, '$commonTour->wayPoints', $tour->ID, 2]);
		$this->wayPoints = Yii::$app->cache->get($cacheKey);
		if (false === $this->wayPoints) {
			$this->wayPoints = [];
			/** @var RITourWps[] $wps */
			foreach ($wps as $wayPoint) {
				$commonWayPoint = new CommonTourWayPoint();
				$commonWayPoint->cityId = $wayPoint->CITYID;
				$commonWayPoint->cityName = Town::getNameByOldId($commonWayPoint->cityId);
				$commonWayPoint->country = $wayPoint->COUNTRY;
				$commonWayPoint->number = $wayPoint->NPP;
				$commonWayPoint->daysCount = $wayPoint->NDAYS;
				$commonWayPoint->countryFlagImage = $wayPoint->getFlagImage();

				if (1 === $commonWayPoint->number && $commonWayPoint->country == 'Россия') {
					continue;
				}

				if (0 === $commonWayPoint->daysCount) {
					continue;
				}

				if (null === $commonWayPoint->cityName) {
					continue;
				}

				if (null === $commonWayPoint->country) {
					continue;
				}
				$this->wayPoints[$commonWayPoint->country][] = $commonWayPoint;
			}

			Yii::$app->cache->set($cacheKey, $this->wayPoints, 3600 * 24, new TagDependency(['tags' => RITourWps::class]));
		}

		$this->daysCount = 0;
		foreach ($this->wayPoints as $country => $wayPoints) {
			foreach ($wayPoints as $wps) {
				$this->daysCount = (int)$this->daysCount + (int)$wps->daysCount;
			}
		}

		//Возьмем доп.фото по точкам маршрута
		/*$keywords = [];
		foreach ($this->wayPoints as $wayPoint) {
			$keywords[] = $wayPoint->cityName;
		}

		$imageArray = [];
		foreach ($keywords as $keyword) {
			$imageArray = array_merge($imageArray, RemoteImageCache::getRandomImages($keyword));
		}

		foreach ($imageArray as $url) {
			$this->additionalImages[] = [
				'url' => RemoteImageCache::getImage($url, null, null, true, false),
				//'src' => RemoteImageCache::getImage($url, '100', 'img-rounded', true),
			];
		}*/

		//Заполняем активные заезды
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, '$tour->activeLaps', $tour->ID]);
		$activeLaps = Yii::$app->cache->get($cacheKey);
		if (false === $activeLaps) {
			$activeLaps = $tour->activeLaps;

			Yii::$app->cache->set($cacheKey, $activeLaps, 3600 * 24, new TagDependency(['tags' => RILaps::class]));
		}

		foreach ($activeLaps as $activeLap) {
			$commonLap = new CommonLap();
			$commonLap->id = $activeLap->ID;
			$commonLap->startDate = $activeLap->BEGDATE;
			$commonLap->endDate = $activeLap->ENDDATE;

			$this->activeLaps[] = $commonLap;
		}
	}

	/**
	 * Конвертация тура Туртранса
	 *
	 *
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private function _prepareTourtrans() {
		/** @var \common\components\tour\tourtrans\Tour $tour */
		$tour = $this->sourceTourData;
		$this->title = $tour->title;
		$this->source = CommonTour::SOURCE_TOURTRANS;
		$this->sourceId = $tour->id;
		$this->description = $tour->include;
		$this->priceMinMax = [$tour->minPrice, $tour->minPrice];
		$this->imageOld = Tour::SITE_URL . $tour->image;
		$this->wayPoints = [];
		$this->daysCount = $tour->duration;

		$route = str_replace(["*"], '', $tour->route);
		$route = preg_replace("/\([^)]+\)/", "", $route);
		$route = explode("–", $route);

		foreach ($route as $index => $place) {
			$cacheKey = Yii::$app->cache->buildKey(['$town', trim($place), 2]);
			$town = Yii::$app->cache->get($cacheKey);
			if (false === $town) {
				/** @var Town $town */
				$town = Town::find()
					->andWhere(['LIKE', Town::tableName() . '.' . Town::ATTR_NAME, trim($place)])
					->joinWith(Town::REL_COUNTRY, true, 'INNER JOIN')
					->one();

				Yii::$app->cache->set($cacheKey, $town, null);
			}
			if (null === $town) {
				continue;
			}

			$commonWayPoint = new CommonTourWayPoint();
			$commonWayPoint->cityId = $town->old_id;
			$commonWayPoint->cityName = $town->name;
			$commonWayPoint->country = $town->country->name;
			$commonWayPoint->number = $index;
			$commonWayPoint->daysCount = 1;
			$commonWayPoint->countryFlagImage = $this->getFlagImageByCountryName($commonWayPoint->country);

			$this->wayPoints[$commonWayPoint->country][] = $commonWayPoint;
		}

		if (null === $tour->tourDates) {
			return;
		}

		foreach ($tour->tourDates as $tourDate) {
			$commonLap = new CommonLap();
			$commonLap->id = md5($tour->id . $tourDate->date);
			$commonLap->startDate = $tourDate->date;
			$commonLap->endDate = $tourDate->date;
			$this->activeLaps[] = $commonLap;
		}
	}

	/**
	 * Конвертация Таритур
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private function _prepareTariTour() {
		/** @var \common\components\tour\tari\Tour $tour */
		$tour = $this->sourceTourData;
		$this->title = $tour[TariTour::ATTR_TOUR_NAME];
		$this->description = $tour[TariTour::ATTR_DESCRIPTION];

		//$this->daysCount = (int)$this->sourceTourAdditionalData[count($this->sourceTourAdditionalData) - 1]['Day'];
		$this->beginDate = $tour[TariTour::ATTR_TOUR_DATE];
		$this->endDate = $tour[TariTour::ATTR_TOUR_DATE];
		$this->imageOld = $tour[TariTour::ATTR_IMAGE];
		RemoteImageCache::getImage($this->imageOld, '195', 'img-rounded', true, true, false);
		$this->sourceUrl = $tour[TariTour::ATTR_SPO_URL];
		$this->priceMinMax = [$tour[TariTour::ATTR_PRICE], $tour[TariTour::ATTR_PRICE]];

		$query = new Query();
		$query->select([])->from(Yii::$app->tariApi::COLLECTION_RESORTS);
		$query->andWhere([Resort::ATTR_ID => (string)$tour[TariTour::ATTR_RESORT_ID]]);
		$resort = $query->one();

		if (false === $resort) {
			return;
		}

		$wayPoint = new CommonTourWayPoint();
		$wayPoint->cityId = $resort[Resort::ATTR_BILETUR_CITY_ID];
		$wayPoint->cityName = $resort[Resort::ATTR_NAME];
		$wayPoint->country = $resort[Resort::ATTR_COUNTRY_NAME];
		$wayPoint->countryFlagImage = $this->getFlagImageByCountryName($wayPoint->country);
		$wayPoint->number = 1;
		$wayPoint->daysCount = 0;
		$this->wayPoints[$wayPoint->country][] = $wayPoint;
		//Dump::dDie($this->wayPoints);
	}

	/**
	 * Получение изображения, если привязано
	 *
	 * @return string|null
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getImage() {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, static::class, $this->sourceId]);
		$objectFile = Yii::$app->cache->get($cacheKey);
		if (false === $objectFile) {
			$objectFile = ObjectFile::findOne([ObjectFile::ATTR_OBJECT => static::class, ObjectFile::ATTR_OBJECT_ID => $this->sourceId]);

			Yii::$app->cache->set($cacheKey, $objectFile, null, new TagDependency(['tags' => RefItems::class]));
		}

		if (null === $objectFile) {
			return null;
		}

		return $objectFile->getWebUrl();
	}

	/**
	 * Поиск флага по названию страны
	 *
	 * @param string $name
	 *
	 * @return mixed|string|null
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getFlagImageByCountryName($name) {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $name]);
		$flagImage = Yii::$app->cache->get($cacheKey);
		if (false === $flagImage) {
			/** @var Country $country */
			$country = Country::find()
				->andWhere([Country::ATTR_NAME => $name])
				->one();

			if (null !== $country) {
				$flagImage = $country->getFlagImage();
			}
			else {
				$flagImage = null;
			}

			Yii::$app->cache->set($cacheKey, $flagImage, null);
		}

		return $flagImage;
	}
}