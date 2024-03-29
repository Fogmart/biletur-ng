<?php

namespace backend\controllers;

use common\base\helpers\Dump;
use common\models\LoginForm;
use common\models\User;
use frontend\models\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends BackendController {
	const ACTION_INDEX = 'index';

	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'actions' => ['login', 'error'],
						'allow'   => true,
					],
					[
						'actions' => ['logout', 'index'],
						'allow'   => true,
						'roles'   => ['@'],
					],
				],
			],
			'verbs'  => [
				'class'   => VerbFilter::class,
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actions() {
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return string
	 */
	public function actionIndex() {
		return $this->render('index');
	}

	/**
	 * Login action.
	 *
	 * @return string
	 */
	public function actionLogin() {

		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			if (false !== mb_stripos($model->username, '@airagency.ru')) {
				//Авторизация по LDAP
				$ldapUser = Yii::$app->ldap->authenticate($model->username, $model->password);
				if (false !== $ldapUser) {
					$siteUser = User::find()
						->andWhere([User::ATTR_USER_NAME => $model->username])
						->one();

					if (null === $siteUser) {
						$signupForm = new SignupForm();
						$signupForm->username = $model->username;
						$signupForm->password = $model->password;
						$signupForm->signup();
					}
				}

				Dump::dDie($ldapUser);
			}

			if ($model->login()) {
				return $this->goBack();
			}
		}

		$model->password = '';
		return $this->render('login', [
			'model' => $model,
		]);

	}

	/**
	 * Logout action.
	 *
	 * @return string
	 */
	public function actionLogout() {
		Yii::$app->user->logout();

		return $this->goHome();
	}
}
