<?php

namespace console\controllers;

use common\base\helpers\Dump;
use common\components\SyncData;
use common\models\CommonHotelMeal;
use common\models\CommonHotelSerpFilters;
use common\models\Country;
use common\models\Filial;
use common\models\Org;
use common\models\Place;
use common\models\Town;
use common\modules\api\ostrovok\models\ApiOstrovokMeal;
use common\modules\api\ostrovok\models\ApiOstrovokSerpFilters;
use common\modules\api\ostrovok\components\OstrovokApi;
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
	 * Синхронизация
	 *
	 *
	 * @author Исаков Владислав <isakov.vi@dns-shop.ru>
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
	 * Обновление базы данных ip адресов
	 *
	 * @throws \yii\base\Exception
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionUpdateIpGeoBase() {
		\Yii::$app->ipgeobase->updateDB();
	}

	/**
	 * Скачивание дама отелей островка. //раз в неделю
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionOstrovokHotelsDump() {
		$api = Yii::$app->ostrovokApi;
		$api->method = OstrovokApi::METHOD_HOTEL_GET_DUMP;

		/** @var \common\modules\api\ostrovok\components\objects\OstrovokResponse $response */
		$response = $api->sendRequest();
		Dump::dDie($response);


		file_get_contents($response->data['url']);
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
					$commonMeal->title =  $titles->ru;
					$commonMeal->save();
				}

				/** @var CommonHotelSerpFilters $commonSerpFilter */
				$commonMeal = CommonHotelMeal::find()
					->andWhere([CommonHotelMeal::ATTR_TITLE =>  $titles->ru])
					->one();

				$mealInBase->common_filter_id = $commonMeal->id;
			}

			$mealInBase->save();
		}
	}
}
