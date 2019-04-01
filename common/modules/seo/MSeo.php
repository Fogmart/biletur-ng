<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace common\modules\site\news;


use yii\base\Module;

class MSeo extends Module {
	public $controllerNamespace = 'common\modules\seo\controllers';

	public function init() {
		parent::init();
	}
}