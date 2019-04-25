<?php 

namespace common\modules\api\models;

use yii\db\ActiveRecord;

/**

* Поля таблицы:
* @property integer $id
* @property string $lang
* @property string $slug
* @property integer $sort_order
* @property integer $common_filter_id
* @property string $title
* @property string $insert_stamp
* @property string $update_stamp
*/

class ApiOstrovokSerpFilters extends ActiveRecord {

	const ATTR_ID = 'id';
	const ATTR_LANG = 'lang';
	const ATTR_SLUG = 'slug';
	const ATTR_SORT_ORDER = 'sort_order';
	const ATTR_COMMON_FILTER_ID = 'common_filter_id';
	const ATTR_TITLE = 'title';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';


	public static function tableName() {
		return 'api_ostrovok_serp_filters';
	}

	public function attributeLabels() {
		return [
			static::ATTR_ID => 'id',
			static::ATTR_LANG => 'lang',
			static::ATTR_SLUG => 'slug',
			static::ATTR_SORT_ORDER => 'sort_order',
			static::ATTR_COMMON_FILTER_ID => 'common_filter_id',
			static::ATTR_TITLE => 'title',
			static::ATTR_INSERT_STAMP => 'insert_stamp',
			static::ATTR_UPDATE_STAMP => 'update_stamp',
		];
	}
}
