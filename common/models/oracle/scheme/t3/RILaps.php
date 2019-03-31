<?php

namespace common\models\oracle\scheme\t3;

use common\models\oracle\scheme\DspBaseModel;

/**
 * @author isakov.v
 *
 * Модель заездов для туров из схемы T3
 *
 * Поля таблицы:
 *
 * @property int    $ID
 * @property int    $ITMID
 * @property string $BEGDATE
 * @property string $ENDDATE
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 * @property int    $ACTIVE
 * @property int    $STOPSALE
 */
class RILaps extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{T3.RI_LAPS}}';
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