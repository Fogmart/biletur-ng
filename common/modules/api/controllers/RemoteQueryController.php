<?php

namespace common\modules\api\controllers;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\Response;

/**
 * Контроллер удаленных запросов к БД
 *
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class RemoteQueryController extends Controller {

	const ACTION_INDEX = 'index';

	/**
	 * @inheritDoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function behaviors() {
		$behaviors = parent::behaviors();
		$behaviors['authenticator'] = [
			'class'       => CompositeAuth::class,
			'authMethods' => [
				HttpBearerAuth::class,
			],
		];

		$behaviors['verbs'] = [
			'class'   => VerbFilter::class,
			'actions' => [
				static::ACTION_INDEX => ['POST', 'GET'],
			],
		];

		return $behaviors;
	}

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex() {
		Yii::$app->response->format = Response::FORMAT_JSON;

		return [
			'result' => [],
			'errors' => false
		];
	}
}