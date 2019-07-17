<?php

namespace common\models\oracle\scheme\sns;

use common\interfaces\InvalidateModels;
use common\models\oracle\scheme\DspBaseModel;

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
class CurrencyRates extends DspBaseModel implements InvalidateModels {

	const ATTR_CURRENCY_CODE = 'CCODE';
	const ATTR_RATE = 'RATE';
	const ATTR_RATE_DATE = 'RATEDATE';
	const ATTR_WHNUPD = 'WHNUPD';
	const ATTR_WHOUPD = 'WHOUPD';

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

	/**
	 * Получение актуального курса для валюты
	 *
	 * @param string $currency
	 *
	 * @return mixed|string
	 *
	 * @throws \yii\base\InvalidConfigException
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getActualRate($currency) {
		$rate = static::find()
			->andWhere([static::ATTR_CURRENCY_CODE => $currency])
			->orderBy([static::ATTR_RATE_DATE => SORT_DESC])
			->one();

		return $rate->RATE;
	}
}