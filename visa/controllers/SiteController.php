<?php
namespace visa\controllers;

use common\components\FrontendController;

/**
 * Site controller
 */
class SiteController extends FrontendController {
	/**
	 * {@inheritdoc}
	 */
	public function actions() {
		return [
			'error'   => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class'           => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		return $this->render('index');
	}
}
