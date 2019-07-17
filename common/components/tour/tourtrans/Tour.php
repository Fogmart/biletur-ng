<?php

namespace common\components\tour\tourtrans;

use common\models\oracle\scheme\sns\CurrencyRates;
use common\models\Town;
use Yii;
use yii\caching\TagDependency;

/**
 * Класс для тура Туртранса
 *
 * @package common\components\tour\tourtrans
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Tour {
	const XML_URL = 'http://www.tourtrans.ru/tours.xml';
	const SITE_URL = 'http://www.tourtrans.ru';

	const COLLECTION_TOURS = 'tourtrans_tours';
	const COLLECTION_FILTERS_GEO = 'tourtrans_filter_geo';

	/** @var string */
	public $url;

	/** @var int */
	public $id;

	/** @var string */
	public $tourCode;

	/** @var string */
	public $title;

	/** @var string */
	public $image;

	/** @var int */
	public $duration;

	/** @var int */
	public $nightMoves;

	/** @var int */
	public $commission;

	/** @var int */
	public $minPrice;

	/** @var string */
	public $currency;

	/** @var string */
	public $route;

	/** @var string[] */
	public $countries;

	/** @var string[] */
	public $cities;

	/** @var string */
	public $visa;

	/** @var string */
	public $include;

	/** @var string */
	public $freeFormula;

	/** @var \common\components\tour\tourtrans\Service[] */
	public $additional;

	/** @var \common\components\tour\tourtrans\Discount[] */
	public $discounts;

	/** @var \common\components\tour\tourtrans\TourDay[] */
	public $tourDays;

	/** @var \common\components\tour\tourtrans\TourDate[] */
	public $tourDates;

	/** @var string */
	public $objectData;


	/**
	 * @param \common\components\tour\tourtrans\Tour $tour
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	/**
	 * Получение суммы в рублях с округлением
	 *
	 * @param int $round
	 *
	 * @return float|string
	 */
	public function getTotRubSumm($round = 0) {
		if ($this->currency == 'RUB') {
			return $this->minPrice;
		}

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, 'rate', $this->currency]);
		$rate = Yii::$app->cache->get($cacheKey);
		if (false === $rate) {
			$rate = round((float)CurrencyRates::getActualRate($this->currency));

			Yii::$app->cache->set($cacheKey, $rate, 3600 * 8, new TagDependency(['tags' => CurrencyRates::class]));
		}

		return round((int)$this->minPrice * $rate);
	}

	/**
	 * Загрузка туров из XML в mongoDb и установка в кэш фильтров
	 *
	 * @throws \yii\mongodb\Exception
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function loadFromXml() {
		$collection = Yii::$app->mongodb->getCollection(Tour::COLLECTION_TOURS);
		$geoCollection = Yii::$app->mongodb->getCollection(Tour::COLLECTION_FILTERS_GEO);

		if ($collection->count() > 0) {
			$collection->drop();
		}

		if ($geoCollection->count() > 0) {
			$geoCollection->drop();
		}

		$xmlTours = simplexml_load_file(Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . 'tours.xml');
		$tours = [];
		$routes = [];

		foreach ($xmlTours as $xmlTour) {
			$tour = new Tour();
			$tour->url = (string)$xmlTour->attributes()['url'];
			$tour->id = (int)$xmlTour->Id;
			$tour->tourCode = (string)$xmlTour->TourCode;
			$tour->title = (string)$xmlTour->Title;

			if (property_exists($xmlTour, 'Image')) {
				$tour->image = str_replace('76x43/', '', (string)$xmlTour->Image->attributes()['url']);
			}

			$tour->duration = (int)$xmlTour->Duration;
			$tour->nightMoves = (int)$xmlTour->NightMoves;
			$tour->commission = (int)$xmlTour->Comission;
			$tour->currency = (string)$xmlTour->Currency;
			$tour->minPrice = (int)$xmlTour->MinPrice;
			$tour->minPrice = $tour->getTotRubSumm();
			$tour->route = (string)$xmlTour->Route;
			$tour->countries = (array)$xmlTour->Countries->Country;
			$tour->visa = (string)$xmlTour->visa;
			$tour->include = (string)$xmlTour->Include;
			$tour->freeFormula = (string)$xmlTour->FreeFormula;

			foreach ($xmlTour->Additional->Service as $xmlService) {
				$service = new Service();
				$service->serviceName = (string)$xmlService->ServiceName;
				$service->servicePrice = (string)$xmlService->ServicePrice;

				$tour->additional[] = $service;
			}

			foreach ($xmlTour->Discounts->Discount as $xmlDiscount) {
				$discount = new Discount();
				$discount->name = (string)$xmlDiscount->Name;
				$discount->price = (int)$xmlDiscount->Price;
				$discount->currency = (string)$xmlDiscount->Currency;

				$tour->discounts[] = $discount;
			}

			foreach ($xmlTour->TourDays->TourDay as $xmlTourDay) {
				$tourDay = new TourDay();
				$tourDay->num = (int)$xmlTourDay->Num;
				$tourDay->title = (string)$xmlTourDay->Title;
				$tourDay->text = (string)$xmlTourDay->Text;
			}

			foreach ($xmlTour->TourDates->TourDate as $xmlTourDate) {
				$tourDate = new TourDate();
				$tourDate->date = (string)$xmlTourDate->Date;
				$tourDate->placesLeft = (int)$xmlTourDate->PlacesLeft;
				foreach ($xmlTourDate->Hotels->Hotel as $xmlHotel) {
					$hotel = new Hotel();
					$hotel->hotelName = (string)$xmlHotel->HotelName;
					$hotel->hotelCategory = (string)$xmlHotel->HotelCategory;
					foreach ($xmlHotel->AccomodationTypes->AccomodationType as $xmlAccomodationType) {
						$accomondationType = new AccomodationType();
						$accomondationType->room = (string)$xmlAccomodationType->Room;
						$accomondationType->category = (string)$xmlAccomodationType->Category;
						$accomondationType->guests = (string)$xmlAccomodationType->Guests;
						$accomondationType->nights = (string)$xmlAccomodationType->Nights;
						$accomondationType->board = (string)$xmlAccomodationType->Board;
						$accomondationType->price = (int)$xmlAccomodationType->Price;

						$hotel->accomodationTypes[] = $accomondationType;
					}

					$tourDate->hotels[] = $hotel;
				}
				$tourDate->minPrice = (int)$xmlTourDate->MinPrice;

				$tour->tourDates[] = $tourDate;
			}

			$tour->objectData = json_encode($tour);


			$route = str_replace(["*"], '', $tour->route);
			$route = explode("–", $route);
			$cities = [];

			foreach ($route as $index => $place) {
				$cacheKey = Yii::$app->cache->buildKey(['$town', trim($place), 3]);
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

				$routes['country_' . $town->country->name] = $town->country->name;
				$routes[$town->old_id] = $town->name;
				$cities[] = $town->old_id;
			}

			$tour->cities = $cities;
			$tours[] = $tour;
		}

		$cacheKey = Yii::$app->cache->buildKey([Tour::COLLECTION_FILTERS_GEO]);
		Yii::$app->cache->set($cacheKey, $routes, null);

		Yii::$app->mongodb->createCommand()->batchInsert(Tour::COLLECTION_TOURS, $tours);

		TagDependency::invalidate(Yii::$app->cache, [static::class]);
	}
}