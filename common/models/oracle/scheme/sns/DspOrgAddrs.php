<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\DspBaseModel;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы SNS.ORGADDRS
 *
 * Поля таблицы:
 * @property string                                         $ID
 * @property string                                         $ORGID
 * @property string                                         $ACTIVE
 * @property string                                         $COUNTRY
 * @property string                                         $ZIP
 * @property string                                         $REGION
 * @property string                                         $DISTRICT
 * @property string                                         $CITY
 * @property string                                         $STREET
 * @property string                                         $HOUSE
 * @property string                                         $BLOCK
 * @property string                                         $ROOM
 * @property string                                         $REMARK
 * @property string                                         $ADDRESS
 *
 *
 * @property-read \common\models\oracle\scheme\sns\DspTowns $city
 *
 */
class DspOrgAddrs extends DspBaseModel {
	const ATTR_ORGID = 'ORGID';
	const ATTR_ADDR_TYPE_ID = 'ADDRTYPEID';
	const ATTR_ACTIVE = 'ACTIVE';
	const ATTR_CITY_ID = 'CITYID';
	const ATTR_REGION = 'REGION';
	const ATTR_STREET = 'STREET';
	const ATTR_HOUSE = 'HOUSE';

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.ORGADDRS}}';
	}

	/**
	 * @return \yii\db\ActiveQuery
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getCity() {
		return $this->hasOne(DspTowns::class, [DspTowns::ATTR_ID => static::ATTR_CITY_ID]);
	}

	const REL_CITY = 'city';

}