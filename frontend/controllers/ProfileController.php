<?php

namespace frontend\controllers;

use common\components\FrontendController;
use common\components\FrontendMenuController;
use yii2mod\rbac\filters\AccessControl;

/**
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class ProfileController extends FrontendMenuController {

	const ACTION_INDEX = '';

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function behaviors() {
		$behaviors = [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'] //Разрешаем доступ только залогиненным пользователям, остальные при попытке зайти отправляются на логин
					]
				]
			],
		];

		return $behaviors;
	}

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