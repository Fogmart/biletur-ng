<?php

namespace common\models\oracle\scheme\sns;

use common\interfaces\InvalidateModels;
use yii\db\ActiveRecord;

/**
 * @author isakov.v
 *
 * Курсы валют валют
 *
 * Поля таблицы:
 *
 * @property string $RATEDATE
 * @property string $NCODE
 * @property string $CCODE
 * @property string $IN_COUNT
 * @property string $RATE
 * @property string $WHOUPD
 * @property string $WHNUPD
 */
class CurrencyRates extends ActiveRecord implements InvalidateModels {
	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.CRNCYRATES}}';
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
		return 'ROWNUM';
	}
}