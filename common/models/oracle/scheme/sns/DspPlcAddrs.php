<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\DspBaseModel;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы PLCADDRS
 *
 * Поля таблицы:
 * @property int    $ID
 * @property string $PLACEID
 * @property int    $ADDRTYPEID
 * @property string $BEGDATE
 * @property string $ENDDATE
 * @property int    $ACTIVE
 * @property int    $DFLT
 * @property string $COUNTRYID
 * @property string $COUNTRY
 * @property string $ZIP
 * @property string $REGION
 * @property string $DISTRICT
 * @property string $CITYID
 * @property string $CITY
 * @property string $STRTTYPE
 * @property string $STREET
 * @property string $BLOCK
 * @property string $BLDG
 * @property string $HOUSE
 * @property string $ROOM
 * @property string $REMARK
 * @property string $ADDRESS
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 *
 */
class DspPlcAddrs extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.PLCADDRS}}';
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 24;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'WHNUPD';
	}
}