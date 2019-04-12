<?php

namespace common\models\oracle\scheme\sns;

use common\components\helpers\OraHelper;
use common\interfaces\InvalidateModels;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы CHRT_TKTSECTS
 *
 * Поля таблицы:
 * @property int    $ID
 * @property int    $CHRTTKTID
 * @property int    $BLOCKID
 * @property int    $PRICEID
 * @property string $PLNPAYSUM
 * @property string $FCTPAYSUM
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 * @property int    $DSP_ID
 * @property int    $DSPCONN_ID
 * @property string $WHNCNFRM
 * @property int    $STATUS
 *
 */
class CharterTicketSects extends ActiveRecord implements InvalidateModels {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.CHRT_TKTSECTS}}';
	}

	public function init() {
		parent::init();
		$this->ID = OraHelper::getNextSeqVal('SQ_Charters');
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 5;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'WHNUPD';
	}

}