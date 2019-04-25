<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Поля таблицы:
 * @property integer $id
 * @property integer $sort_order
 * @property string  $title
 * @property string  $insert_stamp
 * @property string  $update_stamp
 */
class CommonHotelSerpFilters extends ActiveRecord {

	const ATTR_ID = 'id';
	const ATTR_SORT_ORDER = 'sort_order';
	const ATTR_TITLE = 'title';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';


	public static function tableName() {
		return 'common_hotel_serp_filters';
	}

	public function attributeLabels() {
		return [
			static::ATTR_ID           => 'id',
			static::ATTR_SORT_ORDER   => 'sort_order',
			static::ATTR_TITLE        => 'title',
			static::ATTR_INSERT_STAMP => 'insert_stamp',
			static::ATTR_UPDATE_STAMP => 'update_stamp',
		];
	}
}
