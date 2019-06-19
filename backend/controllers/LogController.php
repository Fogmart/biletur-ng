<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\LogYii;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\modules\rbac\rules\Permissions;

/**
 * Class LogController
 *
 * @package backend\controllers
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class LogController extends BackendController {
	const ACTION_INDEX = '';
	const ACTION_CLEAR = 'clear';

	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'actions' => [
							'index',
							static::ACTION_INDEX,
							static::ACTION_CLEAR,
						],
						'allow'   => true,
						'roles'   => [Permissions::ROLE_ADMIN],
					],
				],
			],
		];
	}

	/**
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex() {
		$log = LogYii::find()->orderBy([LogYii::ATTR_LOG_TIME => SORT_DESC])->all();

		return $this->render('index', ['log' => $log]);
	}

	/**
	 * Очистка логов
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionClear() {
		LogYii::deleteAll(null);

		return $this->redirect(static::getActionUrl(static::ACTION_INDEX));
	}
}
