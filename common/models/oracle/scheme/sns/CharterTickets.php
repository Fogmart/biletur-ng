<?php

namespace common\models\oracle\scheme\sns;

use common\components\helpers\OraHelper;
use common\interfaces\InvalidateModels;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель билетов чартеров
 *
 * Поля таблицы:
 *
 * @property int    $ID
 * @property int    $CHRTID
 * @property int    $CHRTPRSNID
 * @property string $STAFFID
 * @property int    $PAYSTATUS
 * @property int    $TKTSTATUS
 * @property string $PLNPAYSUM
 * @property string $PLNPAYDATE
 * @property string $FCTPAYSUM
 * @property string $BLTYPE_ID
 * @property string $BL_CODE
 * @property string $BL_SER
 * @property string $BL_NO
 * @property int    $SECTQTY
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 * @property string $WHNTKT
 * @property string $FULLPAYDATE
 * @property string $FCTTKTCOST
 *
 */
class CharterTickets extends ActiveRecord implements InvalidateModels {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.CHRT_TICKETS}}';
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