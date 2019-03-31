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
 * @property string $BANK
 * @property string $ACNTNUM
 * @property string $BIC
 * @property string $KS
 *
 */
class DspOrgAcnts extends DspBaseModel {
	const ATTR_ORGID = 'ORGID';
	const ATTR_BANK = 'BANK';
	const ATTR_ACTN_NUM = 'ACNTNUM';
	const ATTR_BIC = 'BIC';
	const ATTR_KS = 'KS';

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.ORGACNTS}}';
	}
}