<?php
namespace frontend\controllers;

use common\components\tour\CommonTour;
use yii\web\Controller;

/**
 * Контроллер для обработки старых ссылок
 * Делаем 301 редирект на новые адреса, чтобы поисковики узнавали, что старые ссылки переехали
 *
 * @author Исаков Владислав <visakov@biletur.ru>
 */
class OldLinksController extends Controller {
	public function beforeAction($action) {
		$this->layout = false;
		return parent::beforeAction($action);
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

	public function actionHotels() {
		return $this->redirect(['/hotels/'], 301);
	}

	public function actionTour($id) {
		return $this->redirect(TourController::getActionUrl(TourController::ACTION_VIEW, ['id' => $id, 'src' => CommonTour::SOURCE_BILETUR]), 301);
	}

	public function actionOrdPayInfo() {
		return $this->redirect(['/ord-pay-info/'], 301);
	}

	public function actionBackCall() {
		return $this->redirect(['/back-call/'], 301);
	}

	public function actionVacancy() {
		return $this->redirect(['/vacancy/'], 301);
	}

	public function actionPartners() {
		return $this->redirect(['/partners/'], 301);
	}

	public function actionAgencyStruct() {
		return $this->redirect(['/agency-struct/'], 301);
	}

	public function actionFilials() {
		return $this->redirect(['/filials/'], 301);
	}

	public function actionCommendations() {
		return $this->redirect(['/commendations/'], 301);
	}

	public function actionAwards() {
		return $this->redirect(['/awards/'], 301);
	}
}