<?php
namespace common\modules\profile\controllers;

use common\components\FrontendController;
use common\modules\message\models\Message;
use Yii;
use yii\db\Expression;

/**
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class ProfileController extends FrontendController {

	/**
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex() {
		$this->layout = '/common';
		$this->view->title = 'Профиль' . $this->view->title;

		return $this->render('index');
	}
}