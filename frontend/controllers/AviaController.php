<?php

namespace frontend\controllers;

use common\base\helpers\StringHelper;
use common\components\FrontendMenuController;
use common\forms\etm\SearchForm;
use common\models\oracle\scheme\arr\ARRAirport;
use common\modules\api\etm\components\EtmApi;
use common\modules\api\etm\query\Offers;
use Yii;
use yii\db\Expression;
use yii\web\Response;

/**
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class AviaController extends FrontendMenuController {
	const ACTION_INDEX = '';
	const ACTION_GET_RESULT = 'get-result';
	const ACTION_GET_AIRPORT = 'get-airport';

	/**
	 * Точка входа Авиа
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex() {
		$form = new SearchForm();

		if (Yii::$app->request->isPjax) {
			$form->load(Yii::$app->request->post());
			if (false === $form->validate()) {
				return $this->render('index', ['form' => $form]);
			}

			$form->search();
		}

		return $this->render('index', ['form' => $form]);
	}

	/**
	 * Запрос результата поиска по идентификатору запроса ETM
	 *
	 * @param int $requestId
	 *
	 * @return \common\modules\api\etm\response\offers\OffersResponse
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionGetResult($requestId) {
		$this->layout = false;
		Yii::$app->response->format = Response::FORMAT_JSON;

		$query = new Offers();
		$query->request_id = $requestId;
		$query->currency = 'RUB';
		$query->sort = Offers::SORT_PRICE;

		/** @var \common\modules\api\etm\response\offers\OffersResponse $response */
		$response = Yii::$app->etmApi->sendRequest(EtmApi::METHOD_OFFERS, $query, false);

		return $response;
	}

	/**
	 * Autocomplete аэропортов
	 *
	 * @param string $q
	 *
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionGetAirport($q) {
		$this->layout = false;
		Yii::$app->response->format = Response::FORMAT_JSON;

		/** @var ARRAirport[] $airports */
		$airports = ARRAirport::find()
			->select([ARRAirport::ATTR_AP_IATA, ARRAirport::ATTR_S_NAME])
			->andWhere(['LIKE', new Expression('upper("' . ARRAirport::ATTR_S_NAME . '")'), mb_strtoupper($q)])
			->andWhere(['IS NOT', ARRAirport::ATTR_AP_IATA, null])
			->all();

		$result = [];

		foreach ($airports as $airport) {
			$result['results'][] = [
				'id'   => $airport->AP_IATA,
				'text' => StringHelper::ucfirst($airport->SNAME)
			];
		}

		return $result;
	}
}