<?php
namespace frontend\controllers;

use common\components\FrontendMenuController;
use common\modules\api\ostrovok\components\OstrovokApi;
use common\modules\api\ostrovok\exceptions\OstrovokResponseException;
use Yii;
use yii\web\Response;

/**
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class HotelsController extends FrontendMenuController {

	const ACTION_INDEX = '';

	/**
	 * Точка входа Отели
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex() {


		return $this->render('index');
	}

	/**
	 * @param string $q
	 *
	 * @return \common\modules\api\ostrovok\components\objects\HotelAutocomplete[]
	 *
	 * @throws \common\modules\api\ostrovok\exceptions\OstrovokResponseException
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionFindByName($q) {
		Yii::$app->response->format = Response::FORMAT_JSON;

		$api = Yii::$app->ostrovokApi;
		$api->method = OstrovokApi::METHOD_MULTICOMPLETE;
		$api->params = ['query' => $q];

		/** @var \common\modules\api\ostrovok\components\objects\OstrovokResponse $response */
		$response = $api->sendRequest();

		if (null === $response) {
			return [];
		}

		if (null !== $response->error) {
			throw new OstrovokResponseException('Ошибка запроса к API');
		}

		/** @var \common\modules\api\ostrovok\components\objects\Autocomplete $autocompleteData */
		$autocompleteData = $response->result;

		return $autocompleteData->hotels;
	}

}