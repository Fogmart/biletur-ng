<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\DspBaseModel;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы ORGPHONES
 *
 * Поля таблицы:
 *
 * @property string $ID
 * @property string $ORGID
 * @property string $STAFFID
 * @property string $DEPID
 * @property string $PLACEID
 * @property string $PHONETYPE
 * @property string $CNTRYPCOD
 * @property string $CITYPCODE
 * @property string $PHONENUM
 * @property string $PHONEUSE
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOCHNG
 * @property string $WHNCHNG
 * @property int    $HIDEINWEB
 * @property string $LOCID
 * @property string $BEGDATE
 * @property string $ENDDATE
 * @property string $SRVCTYPEID
 *
 */
class DspOrgPhones extends DspBaseModel {

	const ATTR_ID = 'ID';
	const ATTR_ORGID = 'ORGID';
	const ATTR_STAFFID = 'STAFFID';
	const ATTR_DEPID = 'DEPID';
	const ATTR_PLACEID = 'PLACEID';
	const ATTR_PHONETYPE = 'PHONETYPE';
	const ATTR_CNTRYPCOD = 'CNTRYPCOD';
	const ATTR_CITYPCODE = 'CITYPCODE';
	const ATTR_PHONENUM = 'PHONENUM';
	const ATTR_PHONEUSE = 'PHONEUSE';
	const ATTR_WHOCRT = 'WHOCRT';
	const ATTR_WHNCRT = 'WHNCRT';
	const ATTR_WHOCHNG = 'WHOCHNG';
	const ATTR_WHNCHNG = 'WHNCHNG';
	const ATTR_HIDEINWEB = 'HIDEINWEB';
	const ATTR_LOCID = 'LOCID';
	const ATTR_BEGDATE = 'BEGDATE';
	const ATTR_ENDDATE = 'ENDDATE';
	const ATTR_SRVCTYPEID = 'SRVCTYPEID';
	const ATTR_HIDEINDSP = 'HIDEINDSP';
	const ATTR_NORM_NUM = 'NORM_NUM';

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.ORGPHONES}}';
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
		return 'WHNCHNG';
	}
}