<?php
/**
 * @author Исаков Владислав <isakov.vi@dns-shop.ru>
 */

namespace common\modules\rbac\rules;

use yii\rbac\Rule;

class CallCenterRule extends Rule {
	public $name = "CallCenterRule";

	public function execute($user, $item, $params) {
		return true;
	}
}