<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\DspBaseModel;

/**
 *
 * @author isakov.v
 *
 * Модель лучших тарифов
 *
 * Поля таблицы:
 * @property string $CITYID
 * @property string $ID
 * @property string $AKCODE
 * @property string $SRCCITYID
 * @property string $SRCCITYNM
 * @property string $TGTCITYID
 * @property string $TGTCITYNM
 * @property string $BEGDATE
 * @property string $ENDDATE
 * @property string $OW_FARE
 * @property string $RT_FARE
 * @property string $GDS
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 * @property string $REMARK
 * @property string $CRNCY
 * @property string $OW_AK_TAX
 * @property string $AK_TAX_CRNCY
 * @property string $OW_AG_TAX
 * @property string $AG_TAX_CRNCY
 * @property string $RT_AK_TAX
 * @property string $RT_AG_TAX
 *
 */
class DspMinfares extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.MINFARES}}';
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 12;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'WHNUPD';
	}

	public function getCarriers() {
		return $this->hasOne(DspCarriers::class, ['IATACCODE' => 'AKCODE']);
	}
}