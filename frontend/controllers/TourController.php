<?php
namespace frontend\controllers;

use common\components\FrontendController;

/**
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class TourController extends FrontendController {

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