<?php

namespace console\controllers;

use common\components\SyncData;
use common\components\tour\tourtrans\AccomodationType;
use common\components\tour\tourtrans\Discount;
use common\components\tour\tourtrans\Hotel;
use common\components\tour\tourtrans\Service;
use common\components\tour\tourtrans\Tour;
use common\components\tour\tourtrans\TourDate;
use common\components\tour\tourtrans\TourDay;
use common\models\CommonHotelMeal;
use common\models\CommonHotelSerpFilters;
use common\models\Country;
use common\models\Filial;
use common\models\Org;
use common\models\Place;
use common\models\Town;
use common\modules\api\ostrovok\components\OstrovokApi;
use common\modules\api\ostrovok\models\ApiOstrovokMeal;
use common\modules\api\ostrovok\models\ApiOstrovokSerpFilters;
use common\modules\news\models\News;
use Yii;
use yii\console\Controller;

/**
 * Контроллер синхронизации данных с Ораклом ДСП и другими внешними источниками
 *
 * @package app\commands
 *
 * @author  Исаков Владислав <isakov.vi@dns-shop.ru>
 */
class SyncController extends Controller {

	/**
	 * Синхронизация данных с ДСП
	 *
	 *
	 * @author Исаков Владислав
	 */
	public function actionIndex() {
		//Синхронизация новостей
		SyncData::execute(News::class);

		//Синхронизация стран
		SyncData::execute(Country::class);

		//Синхронизация городов
		SyncData::execute(Town::class);

		//Синхронизация организаций
		SyncData::execute(Org::class);

		//Синхронизация филиалов
		SyncData::execute(Filial::class);

		//Синхронизация мест
		SyncData::execute(Place::class);
	}

	/**
	 * Обновление базы данных ip адресов //раз в неделю
	 *
	 * @throws \yii\base\Exception
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionUpdateIpGeoBase() {
		Yii::$app->ipgeobase->updateDB();
	}

	/**
	 * Скачивание дампа отелей островка. //раз в неделю
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionOstrovokHotelsDump() {
		$api = Yii::$app->ostrovokApi;
		$api->method = OstrovokApi::METHOD_HOTEL_GET_DUMP;

		/** @var \common\modules\api\ostrovok\components\objects\OstrovokResponse $response */
		//$response = $api->sendRequest();
		//file_put_contents(Yii::getAlias('@temp') . 'ostrovok-hotels.zst', file_get_contents($response->data['url']));
		//unzstd partner_feed_ru.json.zst ostrovok.json

		$handle = fopen(Yii::getAlias('@temp') . DIRECTORY_SEPARATOR . 'ostrovok.json', "r");
		$collection = Yii::$app->mongodb->getCollection('api_ostrovok_hotel');

		//дропаем коллекцию
		//$collection->drop();
		print_r($handle) . PHP_EOL;
		if ($handle) {
			$count = 0;
			$objects = [];
			//Пройдем по файлу курсором построково, чтобы не завалить сервер
			while (($line = fgets($handle)) !== false) {
				$objects[] = json_decode($line);
				//Для ускорения грузим пачками, по сколько влезает в оперативку(кол-во объектов настраивается в конфиге)
				if ($count === Yii::$app->ostrovokApi->insertBatchCount) {
					Yii::$app->mongodb->createCommand()->batchInsert('api_ostrovok_hotel', $objects);
					$count = 0;
					$objects = [];
				}
				$count++;
			}
			fclose($handle);
		}
		else {
			echo 'error';
		}

		$collection->createIndex(['id']);
	}

	/**
	 * Загрузка фильтров из API островок. //раз в неделю
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionOstrovokSerpFilter() {
		$api = Yii::$app->ostrovokApi;
		$api->method = OstrovokApi::METHOD_SERP_FILTERS;
		/** @var \common\modules\api\ostrovok\components\objects\OstrovokResponse $response */
		$response = $api->sendRequest();
		/** @var \common\modules\api\ostrovok\components\objects\SerpFilter[] $serps */
		$serps = $response->result;
		foreach ($serps as $serp) {
			$serpInBase = ApiOstrovokSerpFilters::find()
				->where([ApiOstrovokSerpFilters::ATTR_UID => $serp->uid])
				->one();

			if (null === $serpInBase) {
				$serpInBase = new ApiOstrovokSerpFilters();
			}

			$serpInBase->title = $serp->title;
			$serpInBase->slug = $serp->slug;
			$serpInBase->sort_order = $serp->sort_order;
			$serpInBase->lang = $serp->lang;
			$serpInBase->uid = $serp->uid;

			//Если нет связки, или новый то поищем по имени общий фильтр для привязки или создаим его если нет
			if (null === $serpInBase->common_filter_id) {
				$commonSerpFilter = CommonHotelSerpFilters::find()
					->andWhere([CommonHotelSerpFilters::ATTR_TITLE => $serp->title])
					->one();

				if (null === $commonSerpFilter) {
					$commonSerpFilter = new CommonHotelSerpFilters();
					$commonSerpFilter->title = $serp->title;
					$commonSerpFilter->sort_order = $serp->sort_order;
					$commonSerpFilter->save();
				}

				/** @var CommonHotelSerpFilters $commonSerpFilter */
				$commonSerpFilter = CommonHotelSerpFilters::find()
					->andWhere([CommonHotelSerpFilters::ATTR_TITLE => $serp->title])
					->one();

				$serpInBase->common_filter_id = $commonSerpFilter->id;
			}

			$serpInBase->save();
		}
	}

	/**
	 * Загрузка вариантов питания API островок. //раз в неделю
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionOstrovokMeal() {
		$api = Yii::$app->ostrovokApi;
		$api->method = OstrovokApi::METHOD_MEALS;
		/** @var \common\modules\api\ostrovok\components\objects\OstrovokResponse $response */
		$response = $api->sendRequest();

		$meals = $response->result;
		foreach ($meals as $slug => $titles) {
			$mealInBase = ApiOstrovokMeal::find()
				->where([ApiOstrovokMeal::ATTR_SLUG => $slug])
				->one();

			if (null === $mealInBase) {
				$mealInBase = new ApiOstrovokMeal();
			}

			$mealInBase->title = $titles->ru;
			$mealInBase->slug = $slug;

			//Если нет связки, или новый то поищем по имени общий фильтр для привязки или создаим его если нет
			if (null === $mealInBase->common_filter_id) {
				/** @var CommonHotelMeal $commonMeal */
				$commonMeal = CommonHotelMeal::find()
					->andWhere([CommonHotelMeal::ATTR_TITLE => $titles->ru])
					->one();

				if (null === $commonMeal) {
					$commonMeal = new CommonHotelMeal();
					$commonMeal->title = $titles->ru;
					$commonMeal->save();
				}

				/** @var CommonHotelSerpFilters $commonSerpFilter */
				$commonMeal = CommonHotelMeal::find()
					->andWhere([CommonHotelMeal::ATTR_TITLE => $titles->ru])
					->one();

				$mealInBase->common_filter_id = $commonMeal->id;
			}

			$mealInBase->save();
		}
	}

	/**
	 * Закгрузка туров Туртранс в mongoDb
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionLoadTourTrans() {
		$xmlTours = simplexml_load_file(Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . 'tours.xml');

		foreach ($xmlTours as $xmlTour) {
			$tour = new Tour();
			$tour->url = (string)$xmlTour->attributes()['url'];
			$tour->id = (int)$xmlTour->Id;
			$tour->tourCode = (string)$xmlTour->TourCode;
			$tour->title = (string)$xmlTour->Title;
			$tour->image = (string)$xmlTour->Image->attributes()['url'];
			$tour->duration = (int)$xmlTour->Duration;
			$tour->nightMoves = (int)$xmlTour->NightMoves;
			$tour->commission = (int)$xmlTour->Comission;
			$tour->minPrice = (int)$xmlTour->MinPrice;
			$tour->currency = (string)$xmlTour->Currency;
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
				$tourDate->date = $xmlTourDate->Date;
				$tourDate->placesLeft = $xmlTourDate->PlacesLeft;
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
				$tourDate->minPrice = $xmlTourDate->MinPrice;

				$tour->tourDates[] = $tourDate;
			}

			print_r($tour);

			die;
		}

		//print_r($xml);
	}


}
