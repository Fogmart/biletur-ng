<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace frontend\controllers;

use yii\web\Controller;

/**
 * Контроллер для обработки старых ссылок
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class OldLinksController extends Controller {
	public function beforeAction($action) {
		$this->layout = false;
		return parent::beforeAction($action); // TODO: Change the autogenerated stub
	}

	public function actionAgency() {
		return $this->redirect(['/about/'], 301);
	}

	public function actionAccounts() {
		return $this->redirect(['/about/accounts/'], 301);
	}

	public function actionAdvertising() {
		return $this->redirect(['/about/advertising/'], 301);
	}

	public function actionAvia() {
		return $this->redirect(['/avia/'], 301);
	}

	public function actionRailRoad() {
		return $this->redirect(['/rail-road/'], 301);
	}
}