<?php

namespace common\components;

use common\base\helpers\Dump;
use Yii;
use yii\web\Controller;

/**
 * @author isakov.v
 * Контроллер, поддерживающий мультиязычность,
 * модифицирует путь до вьюшки в зависимости от языка окружения
 *
 */
class FrontendController extends Controller {
	public function init() {
		parent::init();

		Dump::dDie('ddd');

	}

	/**
	 * @inheritdoc
	 */
	/*public function getViewPath() {
		return parent::getViewPath() . '/' . Yii::$app->env->getLanguage();
	}*/
}