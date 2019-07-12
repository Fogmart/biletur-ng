<?php

namespace common\models;

use common\components\SiteModel;

/**
 * Поля таблицы:
 * @property integer $id
 * @property string  $name
 * @property integer $region_id
 * @property string  $latitude
 * @property string  $longitude
 */
class GeobaseCity extends SiteModel {

	const ATTR_ID = 'id';
	const ATTR_NAME = 'name';
	const ATTR_REGION_ID = 'region_id';
	const ATTR_LATITUDE = 'latitude';
	const ATTR_LONGITUDE = 'longitude';

	/*public static function tableName() {
		return '{{%geobase_city}';
	}*/

	public function attributeLabels() {
		return [
			static::ATTR_ID        => 'id',
			static::ATTR_NAME      => 'name',
			static::ATTR_REGION_ID => 'region_id',
			static::ATTR_LATITUDE  => 'latitude',
			static::ATTR_LONGITUDE => 'longitude',
		];
	}
}
