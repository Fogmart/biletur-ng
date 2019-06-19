<?php
/**
 * @author Исаков Владислав <isakov.vi@dns-shop.ru>
 */

namespace common\modules\rbac\rules;

use yii\rbac\Rule;

class SeoRule extends Rule {
	public $name = "SeoRule";

	public function execute($user, $item, $params) {
		return true;
	}
}