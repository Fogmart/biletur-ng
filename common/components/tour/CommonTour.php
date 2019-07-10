<?php

namespace common\components\tour;

use common\base\helpers\Dump;
use common\components\RemoteImageCache;
use common\models\ObjectFile;
use common\models\oracle\scheme\t3\RefItems;
use common\models\oracle\scheme\t3\RILaps;
use common\models\oracle\scheme\t3\RITour;
use common\models\oracle\scheme\t3\RITourWps;
use common\models\Town;
use Yii;
use yii\base\Component;
use yii\caching\TagDependency;

class CommonTour extends Component {
	const SOURCE_BILETUR = 0;

	/** @var int` */
	public $source;
	const ATTR_SOURCE = 'source';

	/** @var int|string Идентификатор в системе источника */
	public $sourceId;
	const ATTR_SOURCE_ID = 'sourceId';

	/** @var RefItems */
	public $sourceTourData;
	const ATTR_SOURCE_TOUR_DATA = 'sourceTourData';

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
			throw new \BadFunctionCallException('Не задан параметр source');
		}
		if (null === $this->sourceId) {
			throw new \BadFunctionCallException('Не задан параметр sourceId');
		}

		switch ($this->source) {
			case static::SOURCE_BILETUR:
				$this->_prepareBiletur();
				break;
			default:
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
			Yii::$app->cache->set($cacheKey, $description, 3600 * 8, new TagDependency(['tags' => RITour::class]));
		}

		$this->title = trim(strip_tags($tour->NAME));
		$this->description = strip_tags((null === $description ? '' : $description->DESCRIPTION));
		$this->priceMinMax = $tour->quotsSummMinMax();
		$this->imageOld = (null !== $description ? $description->URL_IMG : null);
		$this->image = $this->getImage();
		//Заполняем точки маршрута
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, '$tour->wps', $tour->ID]);
		$wps = Yii::$app->cache->get($cacheKey);
		if (false === $wps) {
			$wps = $tour->wps;

			Yii::$app->cache->set($cacheKey, $wps, 3600 * 8, new TagDependency(['tags' => RITourWps::class]));
		}

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, '$commonTour->wayPoints', $tour->ID]);
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

				if (1 === $commonWayPoint->number) {
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
				$this->wayPoints[] = $commonWayPoint;
			}

			Yii::$app->cache->set($cacheKey, $this->wayPoints, 3600 * 8, new TagDependency(['tags' => RITourWps::class]));
		}

		$this->daysCount = 0;
		foreach ($this->wayPoints as $wps) {
			$this->daysCount = (int)$this->daysCount + (int)$wps->daysCount;
		}

		//Возьмем доп.фото по точкам маршрута
		$keywords = [];
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

			$this->activeLaps[] = $commonLap;
		}
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
}