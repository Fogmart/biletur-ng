<?php

namespace common\components;

use Yii;
use yii\web\Controller;
use common\base\helpers\StringHelper;

/**
 * @author isakov.v
 * Контроллер, поддерживающий мультиязычность,
 * модифицирует путь до вьюшки в зависимости от языка окружения
 *
 */
class FrontendController extends Controller {
	public function init() {
		parent::init();
	}


	/**
	 * Получение ссылки на указанное действие исходя из контроллера.
	 *
	 * @author Isakov Vladislav
	 *
	 * @param string $actionName   Название действия
	 * @param array  $actionParams Дополнительные параметры
	 *
	 *
	 * @return string
	 */
	public static function getActionUrl($actionName, array $actionParams = []) {
		$prefix = null;

		$controllerName = preg_replace('/Controller$/', '', StringHelper::basename(static::className()));
		$controllerName = mb_strtolower(preg_replace('~(?!\b)([A-Z])~', '-\\1', $controllerName)); // Преобразуем название контроллера к формату url (aaa-bbb-ccc-..)

		$actionParams[0] = implode('/', [
			$controllerName,
			$actionName,
		]);
		$actionParams[0] = '/' . $prefix . $actionParams[0];

		$url = Yii::$app->urlManager->createUrl($actionParams);

		return $url;
	}
}