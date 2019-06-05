<?php
namespace common\modules\order;

use yii\base\Module;

/**
 * Модуль заказа сайта
 *
 * @package common\modules\profile
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class MOrder extends Module {
	public $controllerNamespace = 'common\modules\order\controllers';

	public function init() {
		parent::init();
	}
}