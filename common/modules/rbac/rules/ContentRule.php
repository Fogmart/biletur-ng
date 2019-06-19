<?php
/**
 * @author Исаков Владислав <isakov.vi@dns-shop.ru>
 */

namespace common\modules\rbac\rules;

use yii\rbac\Rule;

class ContentRule extends Rule {
	public $name = "ContentRule";

	public function execute($user, $item, $params) {
		return true;
	}
}