<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\DspBaseModel;

/**
 * @author isakov.v
 *
 * Модель перевозчиков
 *
 *
 * Поля таблицы:
 * @property int    $ID
 * @property int    $ID_AURA
 * @property string $SIRENACODE
 * @property string $IATACCODE
 * @property string $IATANCODE
 * @property string $ICAOCODE_RUS
 * @property string $ICAOCODE_LAT
 * @property string $ACC_CODE
 * @property int    $ORGID_AURA
 * @property string $ORGID
 * @property string $BEG_DT
 * @property string $END_DT
 * @property string $SNAME
 * @property string $RNAME
 * @property string $ENAME
 * @property string $IATA_DT
 * @property string $BTWNCODE
 * @property string $CHARTER
 * @property string $CNTRYCODE
 * @property string $IMGLOGO
 * @property string $WEBURL
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 * @property string $REPR_PHONE
 * @property string $HASTECHINFO
 *
 */
class DspCarriers extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.CARRIERS}}';
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