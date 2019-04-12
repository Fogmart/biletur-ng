<?php

namespace common\models\oracle\scheme\sns;

use common\interfaces\InvalidateModels;
use common\models\scheme\sns\RRShedulePeriod;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы расписания жд
 *
 * Поля таблицы:
 * @property string               $ID
 * @property string               $ID_RRS
 * @property string               $ID_RRSP
 * @property string               $TRAINNUM
 * @property string               $TRAINTYPE
 * @property string               $ROUTE
 * @property string               $DISPATCH
 * @property string               $MOWTIME
 * @property string               $LOCTIME
 * @property string               $WHOCRT
 * @property string               $WHNCRT
 * @property string               $WHOUPD
 * @property string               $WHNUPD
 *
 * @property-read RRShedulePeriod $period
 */
class RRShedule extends ActiveRecord implements InvalidateModels {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.RRSHEDULLE}}';
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

	public function getPeriod() {
		return $this->hasOne(RRShedulePeriod::className(), ['ID_RRSP' => 'ID_RRSP']);
	}
}