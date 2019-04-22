<?php

namespace frontend\controllers;

use common\components\FrontendController;
use common\components\FrontendMenuController;

/**
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class ProfileController extends FrontendMenuController {

	const ACTION_INDEX = '';

	/**
	 * Точка входа Профиль
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex() {

		return $this->render('index');
	}
}