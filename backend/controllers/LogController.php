<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\LogYii;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

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
