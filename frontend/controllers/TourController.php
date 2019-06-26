<?php
namespace frontend\controllers;

use common\components\FrontendMenuController;
use common\forms\tour\SearchForm;
use Yii;

/**
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class TourController extends FrontendMenuController {

	const ACTION_INDEX = '';

	/**
	 * Точка входа Туры
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex() {
		$form = new SearchForm();

		if (Yii::$app->request->isPjax) {
			$form->load(Yii::$app->request->post());
		}

		$form->search();

		return $this->render('index', ['form' => $form]);
	}
}