<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace common\modules\api;


use yii\base\Module;

class MApi extends Module {
	public $controllerNamespace = 'common\modules\api\controllers';

	public function init() {
		parent::init();
	}
}