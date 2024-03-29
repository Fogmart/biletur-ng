<?php

namespace frontend\controllers;

use common\components\Environment;
use common\components\FrontendController;
use common\models\LoginForm;
use common\models\Town;
use common\modules\news\models\News;
use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends FrontendController {
	const ACTION_LOGIN = 'login';
	const ACTION_LOGOUT = 'logout';
	const ACTION_REGISTER = 'signup';
	const ACTION_SET_CITY = 'set-city';

	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::class,
				'only'  => [static::ACTION_LOGOUT, static::ACTION_REGISTER],
				'rules' => [
					[
						'actions' => [static::ACTION_REGISTER],
						'allow'   => true,
						'roles'   => ['?'],
					],
					[
						'actions' => [static::ACTION_LOGOUT],
						'allow'   => true,
						'roles'   => ['@'],
					],
				],
			],
		];
	}

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
			'thumb'   => 'iutbay\yii2imagecache\ThumbAction',
		];
	}

	/**
	 * @param int $id
	 *
	 * @return \yii\web\Response
	 *
	 * @throws \yii\web\NotFoundHttpException
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionSetCity($id) {
		/** @var Town $city */
		$city = Town::find()->where([Town::ATTR_ID => $id])->one();
		if (null === $city) {
			throw new NotFoundHttpException('Город не найден');
		}

		Yii::$app->env->setCityById($city->old_id);

		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * Displays homepage.
	 *
	 * @return mixed
	 */
	public function actionIndex() {

		$internalField = News::getInternalInvalidateField();
		$lastChangedDate = News::find()
			->select(new Expression('MAX("' . News::getInternalInvalidateField() . '") as "' . $internalField . '"'))
			->one();

		return $this->render('index');
	}

	/**
	 * Logs in a user.
	 *
	 * @return mixed
	 */
	public function actionLogin() {
		$this->layout = 'common';
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		}
		else {
			$model->password = '';

			return $this->render('login', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Logs out the current user.
	 *
	 * @return mixed
	 */
	public function actionLogout() {
		Yii::$app->user->logout();

		return $this->goHome();
	}

	/**
	 * Displays contact page.
	 *
	 * @return mixed
	 */
	public function actionContact() {
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
				Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
			}
			else {
				Yii::$app->session->setFlash('error', 'There was an error sending your message.');
			}

			return $this->refresh();
		}
		else {
			return $this->render('contact', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Displays about page.
	 *
	 * @return mixed
	 */
	public function actionAbout() {
		return $this->render('about');
	}

	/**
	 * Signs user up.
	 *
	 * @return mixed
	 */
	public function actionSignup() {
		$this->layout = 'common';
		$model = new SignupForm();
		if ($model->load(Yii::$app->request->post()) && $model->signup()) {
			Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');

			return $this->goHome();
		}

		return $this->render('signup', [
			'model' => $model,
		]);
	}

	/**
	 * Requests password reset.
	 *
	 * @return mixed
	 */
	public function actionRequestPasswordReset() {
		$model = new PasswordResetRequestForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail()) {
				Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

				return $this->goHome();
			}
			else {
				Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
			}
		}

		return $this->render('requestPasswordResetToken', [
			'model' => $model,
		]);
	}

	/**
	 * Resets password.
	 *
	 * @param string $token
	 *
	 * @return mixed
	 * @throws BadRequestHttpException
	 */
	public function actionResetPassword($token) {
		try {
			$model = new ResetPasswordForm($token);
		}
		catch (InvalidArgumentException $e) {
			throw new BadRequestHttpException($e->getMessage());
		}

		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			Yii::$app->session->setFlash('success', 'New password saved.');

			return $this->goHome();
		}

		return $this->render('resetPassword', [
			'model' => $model,
		]);
	}

	/**
	 * Verify email address
	 *
	 * @param string $token
	 *
	 * @return yii\web\Response
	 * @throws BadRequestHttpException
	 */
	public function actionVerifyEmail($token) {
		try {
			$model = new VerifyEmailForm($token);
		}
		catch (InvalidArgumentException $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
		if ($user = $model->verifyEmail()) {
			if (Yii::$app->user->login($user)) {
				Yii::$app->session->setFlash('success', 'Your email has been confirmed!');

				return $this->goHome();
			}
		}

		Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');

		return $this->goHome();
	}

	/**
	 * Resend verification email
	 *
	 * @return mixed
	 */
	public function actionResendVerificationEmail() {
		$model = new ResendVerificationEmailForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail()) {
				Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

				return $this->goHome();
			}
			Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
		}

		return $this->render('resendVerificationEmail', [
			'model' => $model
		]);
	}
}
