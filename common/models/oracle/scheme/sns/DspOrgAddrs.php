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
 * @property string $ID
 * @property string $ORGID
 * @property string $ACTIVE
 * @property string $COUNTRY
 * @property string $ZIP
 * @property string $REGION
 * @property string $DISTRICT
 * @property string $CITY
 * @property string $STREET
 * @property string $HOUSE
 * @property string $BLOCK
 * @property string $ROOM
 * @property string $REMARK
 * @property string $ADDRESS
 *
 *
 *
 *
 */
class DspOrgAddrs extends DspBaseModel {
	const ATTR_ORGID = 'ORGID';
	const ATTR_ADDR_TYPE_ID = 'ADDRTYPEID';
	const ATTR_ACTIVE = 'ACTIVE';

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.ORGADDRS}}';
	}
}