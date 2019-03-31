<?php

namespace common\components;

use Yii;
use yii\web\Controller;

/**
 * @author isakov.v
 * Контроллер, поддерживающий мультиязычность,
 * модифицирует путь до вьюшки в зависимости от языка окружения
 *
 */
class FrontendController extends Controller {
	/**
	 * @inheritdoc
	 */
	public function getViewPath() {
		return parent::getViewPath() . '/' . Yii::$app->env->getLanguage();
	}
}