<?php

namespace common\models;

use common\components\SiteModel;

/**
 * Поля таблицы:
 * @property integer $ip_begin
 * @property integer $ip_end
 * @property string  $country_code
 * @property integer $city_id
 */
class GeobaseIp extends SiteModel {

	const ATTR_IP_BEGIN = 'ip_begin';
	const ATTR_IP_END = 'ip_end';
	const ATTR_COUNTRY_CODE = 'country_code';
	const ATTR_CITY_ID = 'city_id';

	public function attributeLabels() {
		return [
			static::ATTR_IP_BEGIN     => 'ip_begin',
			static::ATTR_IP_END       => 'ip_end',
			static::ATTR_COUNTRY_CODE => 'country_code',
			static::ATTR_CITY_ID      => 'city_id',
		];
	}
}
