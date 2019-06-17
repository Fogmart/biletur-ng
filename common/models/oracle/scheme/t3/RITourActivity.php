<?php

namespace common\models\oracle\scheme\t3;

use common\models\oracle\scheme\DspBaseModel;

/**
 * @author isakov.v
 *
 * Модель этапов тура из схемы T3
 *
 * Поля таблицы:
 *
 * @property int    $ID
 * @property int    $NPP
 * @property string $DAYNAME
 * @property string $ACTIVITY
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 * @property int    $ITMID
 *
 */
class RITourActivity extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{T3.RI_TOUR_ACTIVITY}}';
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