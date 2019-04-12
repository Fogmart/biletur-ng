<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\DspBaseModel;
use common\interfaces\InvalidateModels;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы STFEMAILS
 *
 * Поля таблицы:
 * @property string $ID
 * @property string $STAFFID
 * @property string $EMAIL
 * @property int    $ISACTIVE
 * @property int    $ISPUBLIC
 * @property int    $ISPRIVATE
 * @property string $WHNCRT
 * @property string $WHOCRT
 * @property string $WHNCHNG
 * @property string $WHOCHNG
 * @property int    $FORSMS
 * @property string $WHNUPD
 * @property string $WHOUPD
 *
 */
class DspStaffEmails extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.STFEMAILS}}';
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 10;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'WHNCHNG';
	}

}