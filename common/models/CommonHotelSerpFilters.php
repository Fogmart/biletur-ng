<?php

namespace common\models;

use common\modules\api\ostrovok\models\ApiOstrovokSerpFilters;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Поля таблицы:
 * @property integer                     $id
 * @property integer                     $sort_order
 * @property string                      $title
 * @property string                      $insert_stamp
 * @property string                      $update_stamp
 *
 *
 * @property-read ApiOstrovokSerpFilters $ostrovokSerp
 */
class CommonHotelSerpFilters extends ActiveRecord {

	const ATTR_ID = 'id';
	const ATTR_SORT_ORDER = 'sort_order';
	const ATTR_TITLE = 'title';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function behaviors() {
		return [
			[
				'class'              => TimestampBehavior::class,
				'createdAtAttribute' => 'insert_stamp',
				'updatedAtAttribute' => 'update_stamp',
				'value'              => new Expression('sysdate'),
			],
		];
	}

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

	/**
	 * @return \yii\db\ActiveQuery
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getOstrovokSerp() {
		return $this->hasOne(ApiOstrovokSerpFilters::class, [ApiOstrovokSerpFilters::ATTR_COMMON_FILTER_ID => static::ATTR_ID]);
	}

	const REL_OSTROVOK_SERP = 'ostrovokSerp';
}
