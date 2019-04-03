<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace common\modules\message;

use yii\base\Module;

/**
 * Встраиваемый модуль сообщений
 *
 * @package common\modules\message
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class MMessage extends Module {
	public $controllerNamespace = 'common\modules\message\controllers';

	public function init() {
		parent::init();
	}
}