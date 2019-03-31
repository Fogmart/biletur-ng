<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\DspBaseModel;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы SNS.ORGDOGS
 *
 * Поля таблицы:
 * @property string $ID
 * @property string $ORGID
 * @property string $DOGNUM
 * @property string $DOGDATE
 * @property string $ENDDATE
 *
 *
 *
 *
 */
class DspOrgDogs extends DspBaseModel {
	const ATTR_ORGID = 'ORGID';
	const ATTR_DOG_NUM = 'DOGNUM';
	const ATTR_DOG_DATE = 'DOGDATE';
	const ATTR_END_DATE = 'ENDDATE';

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.ORGDOGS}}';
	}
}