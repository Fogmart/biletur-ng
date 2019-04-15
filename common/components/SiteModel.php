<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace common\components;


use yii\db\ActiveRecord;

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