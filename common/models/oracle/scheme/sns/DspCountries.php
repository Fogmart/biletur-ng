<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\DspBaseModel;

/**
 *
 * @author isakov.v
 *
 * Модель стран
 *
 * Поля таблицы:
 * @property string $ID
 * @property string $CODE
 * @property string $NAME
 * @property string $RNAME
 * @property string $ENAME
 * @property string $SHWINGUIDE
 * @property string $YNDXWTHRID
 * @property string $FLAGNAME
 * @property string $ID_AURA
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 *
 */
class DspCountries extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.COUNTRIES}}';
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 48;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'WHNUPD';
	}

}