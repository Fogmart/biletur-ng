<?php
namespace common\modules\profile;

use yii\base\Module;

/**
 * Модуль профиля
 *
 * @package common\modules\profile
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class MProfile extends Module {
	public $controllerNamespace = 'common\modules\profile\controllers';

	public function init() {
		parent::init();
	}
}