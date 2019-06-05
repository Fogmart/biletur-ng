<?php

namespace common\modules\profile\controllers;

use common\components\FrontendController;
use common\models\User;
use common\modules\profile\models\Profile;
use Yii;

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

		$model = Profile::findOne([Profile::ATTR_USER_ID => Yii::$app->user->id]);

		if (null === $model) {
			/** @var User $user */
			$user = User::findOne([User::ATTR_ID => Yii::$app->user->id]);

			$model = new Profile();
			$model->user_id = $user->id;
			$model->email = $user->email;
			$model->save();
		}

		if (Yii::$app->request->isPjax) {
			$model->load(Yii::$app->request->post());
			$model->save();
		}
		sleep(1);

		return $this->render('index', ['model' => $model]);
	}

	public function actionUpdate() {

	}
}