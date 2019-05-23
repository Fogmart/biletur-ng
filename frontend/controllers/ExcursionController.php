<?php

namespace frontend\controllers;

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

		if (null === $response) {
			return [];
		}

		$typeItems = [];

		foreach ($response as $item) {
			$typeItems[$item->type][] = $item;
		}

		$result = [];

		foreach ($typeItems as $type => $items) {
			$result['results'][] = [
				'id'     => null,
				'text'   => $api::AUTOCOMPLETE_TYPE_NAMES[$type],
				'source' => SearchForm::API_SOURCE_TRIPSTER,
				'type'   => 'devider'
			];

			foreach ($items as $item) {
				$result['results'][] = [
					'id'     => $item->id,
					'text'   => $item->title . ($type === $api::AUTOCOMPLETE_TYPE_CITY_TAG ? '[' . $item->experience_count . ']' : ''),
					'source' => SearchForm::API_SOURCE_TRIPSTER,
					'url'    => $item->url,
					'type'   => 'item'
				];
			}
		}

		return $result;
	}
}