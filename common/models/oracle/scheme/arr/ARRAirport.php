<?php

namespace common\models\oracle\scheme\arr;

use common\models\oracle\scheme\DspBaseModel;
use common\components\helpers\LArray;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * @author isakov.v
 *
 * Модель аэропортов из схемы ARR
 *
 * Поля таблицы:
 *
 * @property int    $AP_ID
 * @property string $AP_CODE
 * @property string $AP_IATA
 * @property string $AP_ICAO
 * @property int    $CITY_ID
 * @property string $SNAME
 * @property int    $AURA_ID
 * @property int    $CITYID
 * @property string $NSO_SID
 * @property string $PREFORD
 * @property string $SNAP_DT
 * @property string $SNAME_LAT
 * @property string $NAME_RU
 * @property string $NAME_EN
 * @property string $WHNUPD
 *
 */
class ARRAirport extends DspBaseModel {

	/**
	 * Получение массива идентификаторов АУРЫ аэрпортов для города ДСП
	 *
	 * @param string $townId
	 *
	 * @return array
	 */
	public static function getAirportsByTown($townId) {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $townId]);
		$rows = Yii::$app->cache->get($cacheKey);
		if (false === $rows) {
			$rows = (new Query())
				->select('AURA_ID')
				->from(self::tableName())
				->where('CITY_ID IN (SELECT CITY_ID FROM ' . ARRCity::tableName() . ' WHERE CITYID = :townId)', [':townId' => $townId])
				->all();

			Yii::$app->cache->set($cacheKey, $rows, null, new TagDependency([ARRAirport::class]));
		}

		return LArray::extract($rows, 'AURA_ID');
	}

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{ARR.AIRPORT}}';
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

	/**
	 * Получение аэропорта назначения
	 * @return ActiveQuery
	 */
	public function getCity() {
		return $this->hasOne(ARRCity::class, ['CITY_ID' => 'CITY_ID']);
	}
}