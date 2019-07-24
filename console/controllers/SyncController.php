<?php
namespace console\controllers;

use common\base\helpers\DateHelper;
use common\components\SyncData;
use common\components\tour\tari\Program;
use common\components\tour\tari\Resort;
use common\components\tour\tourtrans\Tour;
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
		if ($collection->count() > 0) {
			$collection->drop();
		}

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
	 * Закрузка туров Туртранс в mongoDb
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionLoadTourTrans() {
		//$command = 'wget -c -T 60 -P ' . Yii::getAlias('@tourTransData') . ' ' . Tour::XML_URL;
		//exec($command, $output, $status);

		Tour::loadFromXml();
	}

	/**
	 * Загрузка справочников ТариТур в mongoDb
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionLoadTariRefs() {
		/** @var \common\components\tour\tari\City[]|[] $cities */
		$cities = Yii::$app->tariApi->request(Yii::$app->tariApi::METHOD_GET_CITIES);

		/** @var \common\components\tour\tari\Hotel[]|[] $hotels */
		$hotels = Yii::$app->tariApi->request(Yii::$app->tariApi::METHOD_GET_HOTELS);

		/** @var \common\components\tour\tari\Country[]|[] $countries */
		$countries = Yii::$app->tariApi->request(Yii::$app->tariApi::METHOD_GET_COUNTRIES);

		/** @var \common\components\tour\tari\Resort[] $resorts */
		$resorts = Yii::$app->tariApi->request(Yii::$app->tariApi::METHOD_GET_RESORTS);

		$params[Yii::$app->tariApi::PARAM_DATE_FROM] = date(DateHelper::INTL_FORMAT_DATE_RU);

		/** @var \common\components\tour\tari\TourDesc[] $tariTours */
		$tariTours = Yii::$app->tariApi->request(Yii::$app->tariApi::METHOD_TOURS_GET_NAMES_DESC, $params);

		$citiesCollection = Yii::$app->mongodb->getCollection(Yii::$app->tariApi::COLLECTION_CITIES);
		$hotelCollection = Yii::$app->mongodb->getCollection(Yii::$app->tariApi::COLLECTION_HOTELS);
		$countriesCollection = Yii::$app->mongodb->getCollection(Yii::$app->tariApi::COLLECTION_COUNTRIES);
		$resortCollection = Yii::$app->mongodb->getCollection(Yii::$app->tariApi::COLLECTION_RESORTS);
		$tourProgramCollection = Yii::$app->mongodb->getCollection(Yii::$app->tariApi::COLLECTION_TOUR_PROGRAMS);

		//Дропаем коллекции
		if ($citiesCollection->count() > 0) {
			$citiesCollection->drop();
		}

		if ($hotelCollection->count() > 0) {
			$hotelCollection->drop();
		}

		if ($countriesCollection->count() > 0) {
			$countriesCollection->drop();
		}

		if ($resortCollection->count() > 0) {
			$resortCollection->drop();
		}

		if ($tourProgramCollection->count() > 0) {
			$tourProgramCollection->drop();
		}

		//Загружаем новые данные
		Yii::$app->mongodb->createCommand()->batchInsert(Yii::$app->tariApi::COLLECTION_CITIES, $cities);
		Yii::$app->mongodb->createCommand()->batchInsert(Yii::$app->tariApi::COLLECTION_HOTELS, $hotels);
		Yii::$app->mongodb->createCommand()->batchInsert(Yii::$app->tariApi::COLLECTION_COUNTRIES, $countries);

		$tourPrograms = [];
		foreach ($tariTours as $tariTour) {
			$tariTourProgram = new Program();
			$tariTourProgram->tourId = (int)$tariTour->TourID;
			$tariTourProgram->steps = Yii::$app->tariApi->request(Yii::$app->tariApi::METHOD_TOURS_GET_PROGRAM, [Yii::$app->tariApi::PARAM_ID => $tariTour->TourID]);
			$tourPrograms[] = $tariTourProgram;
		}
		print_r($tourPrograms);
		Yii::$app->mongodb->createCommand()->batchInsert(Yii::$app->tariApi::COLLECTION_TOUR_PROGRAMS, $tourPrograms);

		$commonResorts = [];
		foreach ($resorts as $resort) {
			$commonResort = new Resort();
			$commonResort->id = $resort->id;
			$commonResort->name = $resort->name;
			$commonResort->countryId = $resort->countryId;
			$commonResort->bileturCityId = Town::getOldIdByName($resort->name);
			$commonResort->countryName = Town::getCountryNameByName($resort->name);
			if (null === $commonResort) {
				continue;
			}

			$commonResorts[] = $commonResort;
		}
		Yii::$app->mongodb->createCommand()->batchInsert(Yii::$app->tariApi::COLLECTION_RESORTS, $commonResorts);

	}
}
