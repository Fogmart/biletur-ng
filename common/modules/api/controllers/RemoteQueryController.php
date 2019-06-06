<?php

namespace common\modules\api\controllers;

use Yii;
use yii\caching\TagDependency;
use yii\db\Exception;
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
	const ACTION_INVALIDATE_TAG = 'invalidate-tag';

	public function init() {
		parent::init();
		Yii::$app->response->format = Response::FORMAT_JSON;
	}

	/**
	 * @inheritDoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function behaviors() {
		$behaviors = parent::behaviors();
		$behaviors['authenticator'] = [
			'class' => HttpBearerAuth::class,
		];

		$behaviors['verbs'] = [
			'class'   => VerbFilter::class,
			'actions' => [
				static::ACTION_INDEX          => ['POST', 'GET'],
				static::ACTION_INVALIDATE_TAG => ['POST', 'GET'],
			],
		];

		return $behaviors;
	}

	/**
	 * Удаленный запрос в БД дсп
	 *
	 * @return array
	 *
	 * @throws \yii\db\Exception
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex() {
		$isUsedCache = true;
		$params = Yii::$app->request->post();
		$sql = strtoupper($params['sql']);
		$invalidateTime = $params['invalidateTime'];
		$invalidateTag = $params['invalidateTag'];

		$connection = Yii::$app->dbDsp;
		$command = $connection->createCommand($sql);

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, md5($sql)]);
		$result = Yii::$app->cache->get($cacheKey);
		$error = false;
		if (false === $result) {
			try {
				$result = $command->queryAll();
			}
			catch (Exception $exception) {
				$error = $exception->getMessage();
			}

			$isUsedCache = false;

			Yii::$app->cache->set($cacheKey, $result, $invalidateTime, new TagDependency(['tags' => $invalidateTag]));
		}

		return [
			'result'      => $result,
			'isUsedCache' => $isUsedCache,
			'error'       => $error
		];
	}

	/**
	 * Сброс тэга
	 *
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionInvalidateTag() {
		$params = Yii::$app->request->post();
		$invalidateTag = $params['invalidateTag'];

		TagDependency::invalidate(Yii::$app->cache, $invalidateTag);

		return [
			'result' => 'success',
		];
	}
}