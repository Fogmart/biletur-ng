<?php
namespace frontend\controllers;

use common\components\FrontendController;

/**
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class AviaController extends FrontendController {

	const ACTION_INDEX = '';

	/**
	 * Точка входа Авиа
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex() {


		return $this->render('index');
	}
}