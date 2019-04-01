<?php
namespace console\controllers;

use common\components\SyncData;
use common\modules\news\models\News;
use yii\console\Controller;

/**
 * Контроллер синхронизации данных с Ораклом ДСП
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

	}
}
