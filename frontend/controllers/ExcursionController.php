<?php
namespace frontend\controllers;

use common\base\helpers\Dump;
use common\components\FrontendMenuController;
use common\forms\excursion\SearchForm;
use Yii;
use yii\web\Response;

/**
 * Контроллер экскурсий
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class ExcursionController extends FrontendMenuController {

	const ACTION_INDEX = '';
	const ACTION_FIND_BY_NAME = 'find-by-name';

	/**
	 * Точка входа Экскурсии
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex() {
		$form = new SearchForm();

		if (Yii::$app->request->isPjax) {
			$form->load(Yii::$app->request->post());
			$form->search();
		}

		return $this->render('index', ['form' => $form]);
	}


	/**
	 * @param string $q
	 *
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionFindByName($q) {
		Yii::$app->response->format = Response::FORMAT_JSON;

		$api = Yii::$app->tripsterApi;

		$response = $api->sendRequest($api::METHOD_SEARCH, ['query' => $q]);

		return $response;
	}
}