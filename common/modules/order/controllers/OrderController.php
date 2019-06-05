<?php

namespace common\modules\order\controllers;

use common\components\FrontendController;
use common\models\User;
use common\modules\profile\models\Profile;
use Yii;

/**
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class OrderController extends FrontendController {

	/**
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex() {
		$this->layout = '/common';

		return $this->render('index');
	}

	public function actionUpdate() {

	}
}