<?php

namespace common\models\oracle\scheme\sns;

use common\interfaces\InvalidateModels;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель чартеров
 *
 * Поля таблицы:
 * @property int                                  $ID
 * @property string                               $NAME
 * @property string                               $ORGID
 * @property string                               $DOGID
 * @property string                               $CRNCY
 * @property int                                  $CRNCYRATE
 * @property string                               $CRNCYDATE
 * @property string                               $REMARKS
 * @property int                                  $ACTIVE
 * @property string                               $MINDATE
 * @property string                               $MAXDATE
 * @property int                                  $TOTLMTQTY
 * @property int                                  $TOTSALEQTY
 * @property int                                  $TOTUSEQTY
 * @property string                               $TOTCOSTSUM
 * @property string                               $TOTSALESUMRUB
 * @property string                               $WHOCRT
 * @property string                               $WHNCRT
 * @property string                               $WHOUPD
 * @property string                               $WHNUPD
 * @property string                               $PLNSALESUM
 * @property string                               $FCTPAYSUM
 * @property string                               $PLNPAYSUM
 * @property string                               $DPTCITYID
 * @property string                               $ARVCITYID
 * @property int                                  $MEMOID
 * @property string                               $PUB_NOTE
 *
 * @property-read \common\models\scheme\sns\Towns $departureTown
 * @property-read \common\models\scheme\sns\Towns $arrivalTown
 */
class Charters extends ActiveRecord implements InvalidateModels {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.CHARTERS}}';
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 2;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'WHNUPD';
	}

	public function getDepartureTown() {
		return $this->hasOne(OraTowns::className(), ['ID' => 'DPTCITYID']);
	}

	public function getArrivalTown() {
		return $this->hasOne(OraTowns::className(), ['ID' => 'ARVCITYID']);
	}
}