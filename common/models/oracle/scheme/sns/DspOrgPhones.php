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