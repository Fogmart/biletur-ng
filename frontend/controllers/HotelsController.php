<?php

namespace frontend\controllers;

use common\components\FrontendMenuController;
use common\forms\hotels\SearchForm;
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
	const ACTION_FIND_BY_NAME = 'find-by-name';

	/**
	 * Точка входа Отели
	 *
	 * @return string
	 * @throws
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
	 * Запрос отелей и регионов для автокомплита
	 *
	 * @param string $q
	 *
	 *
	 * @return \common\modules\api\ostrovok\components\objects\HotelAutocomplete[] | \common\modules\api\ostrovok\components\objects\RegionAutocomplete[]
	 *
	 * @throws \common\modules\api\ostrovok\exceptions\OstrovokResponseException
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionFindByName($q) {
		Yii::$app->response->format = Response::FORMAT_JSON;

		$cacheKey = Yii::$app->cache->buildKey([$q]);

		/** @var \common\modules\api\ostrovok\components\objects\OstrovokResponse $response */
		$response = Yii::$app->cache->get($cacheKey);
		if (false === $response) {
			$api = Yii::$app->ostrovokApi;
			$api->method = OstrovokApi::METHOD_MULTICOMPLETE;
			$api->params = ['query' => $q];
			$response = $api->sendRequest();

			Yii::$app->cache->set($cacheKey, $response, 3600 * 24 * 7);
		}

		if (null === $response) {
			return [];
		}

		if (null !== $response->error) {
			throw new OstrovokResponseException('Ошибка запроса к API');
		}

		/** @var \common\modules\api\ostrovok\components\objects\Autocomplete $autocompleteData */
		$autocompleteData = $response->result;

		$hotels = $data = $autocompleteData->hotels;

		$regions = $data = $autocompleteData->regions;

		$result = [];

		//Разделитель "Отели"
		$result['results'][] = [
			'id'     => null,
			'text'   => 'Отели',
			'source' => SearchForm::API_SOURCE_OSTROVOK,
			'type'   => 'devider'
		];

		foreach ($hotels as $id => $object) {
			$result['results'][] = [
				'id'     => implode('|', [$object->id, SearchForm::OBJECT_TYPE_HOTEL, $object->region_name]),
				'text'   => $object->name . ', ' . $object->region_name,
				'source' => SearchForm::API_SOURCE_OSTROVOK,
				'type'   => 'item'
			];
		}

		//Разделитель "Регионы"
		$result['results'][] = [
			'id'     => null,
			'text'   => 'Города, регионы',
			'source' => SearchForm::API_SOURCE_OSTROVOK,
			'type'   => 'devider'
		];

		foreach ($regions as $id => $object) {
			$result['results'][] = [
				'id'     => implode('|', [$object->id, SearchForm::OBJECT_TYPE_REGION, $object->country]),
				'text'   => $object->name . ', ' . $object->country,
				'source' => SearchForm::API_SOURCE_OSTROVOK,
				'type'   => 'item'
			];
		}

		$cacheKey = Yii::$app->cache->buildKey(['lastAutocompleteOstrovok', Yii::$app->session->id]);
		Yii::$app->cache->set($cacheKey, $result, 3600 / 2);

		return $result;
	}
}