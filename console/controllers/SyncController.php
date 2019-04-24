<?php

namespace console\controllers;

use common\components\SyncData;
use common\models\Country;
use common\models\Filial;
use common\models\Org;
use common\models\Place;
use common\models\Town;
use common\modules\api\ostrovok\components\OstrovokApi;
use common\modules\news\models\News;
use Yii;
use yii\console\Controller;
use common\base\helpers\Dump;

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
	 * Скачивание дама отелей островка. Обновляется раз в неделю.
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
}
