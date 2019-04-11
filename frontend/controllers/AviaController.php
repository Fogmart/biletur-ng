<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace frontend\controllers;


use common\components\FrontendController;

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