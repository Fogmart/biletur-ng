<?php

namespace common\models\oracle\scheme\t3;

use common\models\oracle\scheme\DspBaseModel;

/**
 * @author isakov.v
 *
 * Связка персонала с турами
 *
 * Поля таблицы:
 *
 * @property int    $ID
 * @property int    $ITMID
 * @property string $STAFFID
 * @property string $STFNAME
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 *
 */
class RIStaff extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{T3.RI_STAFF}}';
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 1;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'WHNUPD';
	}
}