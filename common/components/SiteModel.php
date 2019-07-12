<?php
namespace common\components;

use yii\db\ActiveRecord;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */
class SiteModel extends ActiveRecord {
	/**
	 * @return array|string[]
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function primaryKey() {
		return ['id'];
	}
}