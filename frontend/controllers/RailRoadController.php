<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace frontend\controllers;


use common\components\FrontendController;

class RailRoadController extends FrontendController {

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