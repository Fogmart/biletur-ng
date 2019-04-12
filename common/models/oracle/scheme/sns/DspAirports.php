<?php

namespace common\models\oracle\scheme\sns;

use common\interfaces\InvalidateModels;
use common\models\oracle\scheme\DspBaseModel;

/**
 *
 * @author isakov.v
 *
 * Модель аэропортов
 *
 * Поля таблицы:
 * @property string $CITYID
 * @property string $CODE
 * @property string $TOWNCODE
 * @property string $IATACODE
 * @property string $RNAME
 * @property string $ICAOCODE
 * @property string $ENAME
 * @property string $STATECODE
 * @property string $REGIONCODE
 * @property string $AFTNCODE
 * @property int    $GMTSHIFT
 * @property int    $SMRSHIFT
 * @property string $URL
 * @property string $INFO_PHONES
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 *
 */
class DspAirports extends DspBaseModel implements InvalidateModels {
	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.AIRPORTS}}';
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
		return 'WHNUPD';
	}

}