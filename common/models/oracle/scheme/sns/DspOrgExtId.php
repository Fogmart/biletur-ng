<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\DspBaseModel;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы SNS.ORGS
 *
 * Поля таблицы:
 * @property int    $ID
 * @property string $ORGID
 * @property int    $EXTSYSID
 * @property string $EXTID
 *
 */
class DspOrgExtId extends DspBaseModel {

	const ATTR_ORG_ID = 'ORGID';
	const ATTR_EXT_SYS_ID = 'EXTSYSID';
	const ATTR_EXT_ID = 'EXTID';

	const EXT_SYS_SABRE = 22;

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.ORGEXTIDS}}';
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