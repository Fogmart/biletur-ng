<?php
namespace frontend\controllers;

use common\components\FrontendController;
use common\components\FrontendMenuController;

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


		return $this->render('index');
	}
}