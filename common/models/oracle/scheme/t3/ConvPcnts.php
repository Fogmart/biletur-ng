<?php

namespace common\models\oracle\scheme\t3;

use common\models\oracle\scheme\DspBaseModel;

/**
 * @author isakov.v
 *
 * Процент конвертации валют
 *
 * Поля таблицы:
 *
 * @property int    $ITMID
 * @property string $CCODE
 * @property int    $CONVTYPE
 * @property int    $CONVPCNT
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOCHNG
 * @property string $WHNCHNG
 */
class ConvPcnts extends DspBaseModel {
	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{T3.CONVPCNTS}}';
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
		return 'WHNCHNG';
	}
}