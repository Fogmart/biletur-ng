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

	const ATTR_ID = 'ID';
	const ATTR_CODE = 'CODE';
	const ATTR_NAME = 'NAME';
	const ATTR_RNAME = 'RNAME';
	const ATTR_ENAME = 'ENAME';
	const ATTR_SHWINGUIDE = 'SHWINGUIDE';
	const ATTR_YNDXWTHRID = 'YNDXWTHRID';
	const ATTR_FLAGNAME = 'FLAGNAME';
	const ATTR_ID_AURA = 'ID_AURA';
	const ATTR_WHOCRT = 'WHOCRT';
	const ATTR_WHNCRT = 'WHNCRT';
	const ATTR_WHOUPD = 'WHOUPD';
	const ATTR_WHNUPD = 'WHNUPD';

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