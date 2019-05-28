<?php

namespace frontend\controllers;

use common\components\FrontendMenuController;
use common\forms\excursion\SearchForm;
use common\modules\api\tripster\components\TripsterApi;
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
	 * @param string|null $city
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex($city = null) {
		$form = new SearchForm();
		$form->cityName = $city;

		if (Yii::$app->request->isPjax) {
			$form->load(Yii::$app->request->post());
		}

		$form->search();

		return $this->render('index', ['form' => $form]);
	}

	/**
	 *Виджет Экскурсии
	 *
	 * @param string|null $city
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionWidget($city = null) {
		$this->layout = '/widget';

		$form = new SearchForm();
		$form->cityName = $city;

		if (Yii::$app->request->isPjax) {
			$form->load(Yii::$app->request->post());
		}

		$form->search();

		return $this->render('index', ['form' => $form]);
	}


	/**
	 * @param string $q
	 * @param string $needType
	 *
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionFindByName($q, $needType = 'city') {
		Yii::$app->response->format = Response::FORMAT_JSON;

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $q, $needType]);
		$result = Yii::$app->cache->get($cacheKey);

		if (false === $result) {
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
				if ($type !== $needType) {
					continue;
				}

				foreach ($items as $item) {
					$item->title = str_replace('<em>', '', $item->title);
					$item->title = str_replace('</em>', '', $item->title);
					$result['results'][] = [
						'id'     => $item->id,
						'text'   => $item->title . ($type === $api::AUTOCOMPLETE_TYPE_CITY_TAG ? '[' . $item->experience_count . ']' : ''),
						'source' => SearchForm::API_SOURCE_TRIPSTER,
						'url'    => $item->url,
						'type'   => 'item'
					];
				}
			}
			Yii::$app->cache->set($cacheKey, $result, null);
		}

		$cacheKey = Yii::$app->cache->buildKey([TripsterApi::class . $needType, Yii::$app->session->id]);
		Yii::$app->cache->set($cacheKey, $result, 3600 / 2);

		return $result;
	}
}