<?php

namespace common\models\oracle\scheme\sns;

use common\interfaces\InvalidateModels;
use common\models\queries\QueryAirports;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Состав цены для блока чартера
 *
 * Поля таблицы:
 * @property int                                          $PRICEID
 * @property int                                          $BLOCKID
 * @property string                                       $PARTPRICE
 *
 * @property-read \common\models\scheme\sns\CharterPrices $price
 */
class CharterPriceItems extends ActiveRecord implements InvalidateModels {
	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.CHRT_PRCITMS}}';
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 10;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'ROWNUM';
	}

	public function getPrice() {
		return $this->hasOne(CharterPrices::className(), ['ID' => 'PRICEID'])
			->where('nvl(' . CharterPrices::tableName() . '.FORINTUSE, 0) = 0
					AND nvl(' . CharterPrices::tableName() . '.CNFRMQTY, 0) < nvl(' . CharterPrices::tableName() . '.LMTQTY, 0)');
	}
}