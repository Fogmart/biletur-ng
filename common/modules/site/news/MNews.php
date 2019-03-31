<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace common\modules\site\news;


use yii\base\Module;

class MNews extends Module {
	public $controllerNamespace = 'common\modules\news\controllers';

	public function init() {
		parent::init();
	}
}