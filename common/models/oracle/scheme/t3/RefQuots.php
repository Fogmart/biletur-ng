<?php

namespace common\models\oracle\scheme\t3;

use common\models\oracle\scheme\DspBaseModel;
use common\interfaces\InvalidateModels;
use common\models\oracle\scheme\sns\CurrencyRates;
use yii\caching\TagDependency;
use yii\db\ActiveQuery;
use Yii;

/**
 * @author isakov.v
 *
 * Модель таблицы RefQuots туров из схемы T3
 *
 * Поля таблицы:
 *
 * @property int    $ID
 * @property int    $CMPLXPARID
 * @property int    $ITMID
 * @property string $BEGDATE
 * @property string $ENDDATE
 * @property string $INDIVIDUAL
 * @property string $SUPID
 * @property string $SUPNAME
 * @property string $DOGID
 * @property string $DOGNUM
 * @property string $NAME
 * @property string $GRPNAME
 * @property string $CRNCY
 * @property string $QUOTSUM
 * @property string $CNVPCNT
 * @property string $CNVSUM
 * @property int    $OURCNVTYPE
 * @property string $OURCNVPCNT
 * @property string $OURCNVSUM
 * @property int    $IBPCNT
 * @property string $IBPSUM
 * @property string $IBSSUM
 * @property int    $I2BPCNT
 * @property string $I2BPSUM
 * @property string $I2BSSUM
 * @property int    $EBPCNT
 * @property string $EBPSUM
 * @property string $EBSSUM
 * @property string $BONUSSUM
 * @property string $TOTSUM
 * @property string $NOTDOGSUM
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 * @property string $VALIDATORID
 * @property string $WHNVALID
 * @property string $CID
 * @property int    $ISCMPLX
 * @property string $PAYCRNCY
 * @property int    $ISVALID
 * @property string $TOTSUMRUB
 * @property string $FIXEDTOTSUMRUB
 * @property string $UNIT
 * @property string $SRCQUOTID
 * @property string $PRSNINQUOT
 * @property string $SUPVATRATE
 * @property string $SUPVATSUM
 * @property string $OURVATRATE
 * @property string $OURVATSUM
 * @property string $TOTVATSUMRUB
 * @property string                                                  $EXTPAYCOST
 *
 *
 * @property-read \common\models\oracle\scheme\t3\ConvPcnts      $convertProcent
 * @property-read \common\models\oracle\scheme\sns\CurrencyRates $currencyRate
 */
class RefQuots extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{T3.REFQUOTS}}';
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

	/**
	 * Процент конвертации
	 * @return ActiveQuery
	 */
	public function getConvertProcent() {
		return $this->hasOne(ConvPcnts::className(), ['CONVTYPE' => 'OURCNVTYPE']);
	}

	/**
	 * Курс валюты
	 * @return ActiveQuery
	 */
	public function getCurrencyRate() {
		return $this->hasOne(CurrencyRates::className(), ['CCODE' => 'CRNCY'])
			//->where('RATEDATE = trunc(sysdate)');todo как лучше? вдруг на сегодня еще нет курса?
			->where("RATEDATE IN (SELECT MAX(RATEDATE) FROM " . CurrencyRates::tableName() . ")"); //todo может взять последний?

	}

	/**
	 * Получение суммы в рублях с округлением
	 *
	 * @param int $round
	 *
	 * @return float|string
	 */
	public function getTotRubSumm($round = 0) {
		if ($this->CRNCY == 'RUB') {
			return $this->TOTSUM;
		}

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, 'rate']);
		$rate = Yii::$app->cache->get($cacheKey);
		if (false === $rate) {
			$rate = round((float)$this->currencyRate->RATE);

			Yii::$app->cache->set($cacheKey, $rate, 3600 * 8, new TagDependency(['tags' => CurrencyRates::class]));
		}

		return round((int)$this->TOTSUM * $rate);
	}
}