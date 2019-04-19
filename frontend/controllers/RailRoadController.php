<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace frontend\controllers;

use common\components\FrontendMenuController;

class RailRoadController extends FrontendMenuController {

	const ACTION_INDEX = '';

	/**
	 * Точка входа ЖД билетов
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex() {


		return $this->render('index');
	}
}