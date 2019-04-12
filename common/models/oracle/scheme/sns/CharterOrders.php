<?php

namespace common\models\oracle\scheme\sns;

use common\components\helpers\OraHelper;
use common\interfaces\InvalidateModels;
use common\models\procedures\NextKeuVal;
use common\models\procedures\NextKeyVal;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы ChrtOrders
 *
 * Поля таблицы:
 * @property int    $ID
 * @property int    $CHRTID
 * @property int    $ORDNUM
 * @property string $CUSTEMAIL
 * @property string $CUSTCITY
 * @property string $PREFPLACEID
 * @property string $STAFFID
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 * @property string $REMARKS
 * @property string $PLNPAYSUM
 * @property string $FCTPAYSUM
 * @property int    $SECTQTY
 * @property string $WIWPRSNID
 * @property string $REFERER
 * @property string $PRVPRSNID
 * @property string $MINDATE
 * @property string $MAXDATE
 * @property string $RCVSDID
 * @property string $PRVACCEPTED
 * @property string $PRVORDID
 *
 */
class CharterOrders extends ActiveRecord implements InvalidateModels {
	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.CHRT_ORDERS}}';
	}

	public function init() {
		parent::init();
		$this->ID = OraHelper::getNextSeqVal('SQ_Charters');

		$procedure = new NextKeyVal();
		$procedure->params = [
			':S_OWNORID' => self::ORG_ID,
			':S_SYSTEM'  => self::SYSTEM_ID,
			':S_KEYNAME' => 'ORDNUM',
			':NEWKEY'    => '',
		];
		$procedure->call();
		$this->ORDNUM = $procedure->getResult();
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

	const ROLE_MANAGER = '/M/';
	const ORG_ID = '0000000001';
	const SYSTEM_ID = 'CHARTERS';


}