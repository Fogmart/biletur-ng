<?php
/**
 * @author Исаков Владислав <isakov.vi@dns-shop.ru>
 */

namespace common\modules\rbac\rules;

use yii\rbac\Rule;

class AdminRule extends Rule {
	public $name = "AdminRule";

	public function execute($user, $item, $params) {
		return true;
	}
}