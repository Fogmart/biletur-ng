<?php
namespace frontend\controllers;

use common\components\FrontendController;
use common\components\FrontendMenuController;

/**
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class HotelsController extends FrontendMenuController {

	const ACTION_INDEX = '';

	/**
	 * Точка входа Отели
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex() {


		return $this->render('index');
	}
}