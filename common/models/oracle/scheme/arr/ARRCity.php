<?php

namespace common\models\oracle\scheme\arr;

use common\models\oracle\scheme\DspBaseModel;
use common\models\oracle\scheme\sns\DspTowns;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveQuery;

/**
 * @author isakov.v
 *
 * Модель городов из схемы ARR
 *
 * Поля таблицы:
 * @property int    $CITY_ID
 * @property int    $COU_ID
 * @property int    $DSTR_ID
 * @property string $CITY_CODE
 * @property string $CITY_ICAO
 * @property string $CITY_IATA
 * @property string $SNAME_RUS
 * @property string $LNAME_LAT
 * @property string $CITYID
 * @property string $NSO_SID
 * @property string $SNAP_DT
 *
 */
class ARRCity extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{ARR.CITY}}';
	}

	/**
	 * Получение города АРР по идентификатору с кешированием
	 *
	 * @param $id
	 *
	 * @return mixed|null|static
	 */
	public static function getById($id) {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $id]);
		$city = Yii::$app->cache->get($cacheKey);
		if (false === $city) {
			$city = self::findOne($id);
			Yii::$app->cache->set($cacheKey, $city, null, new TagDependency([self::class]));
		}

		return $city;
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 24 * 7; //раз в неделю
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'SNAP_DT';
	}

	/**
	 * Связка с городом из таблицы DspTowns
	 *
	 * @return ActiveQuery
	 */
	public function getTown() {
		return $this->hasOne(DspTowns::class, ['ID' => 'CITYID']);
	}

	/**
	 * Связка с аэропортом из таблицы ARRAirports
	 *
	 * @return ActiveQuery
	 */
	public function getAirports() {
		return $this->hasMany(ARRAirport::class, ['CITY_ID' => 'CITY_ID']);
	}

	/**
	 * Выборка только городов имеющих активные для расписания аэропорты отправления
	 * @return mixed
	 */
	public function getHaveMonitoredAirports() {
		return $this->hasMany(ARRAirport::class, ['CITY_ID' => 'CITY_ID'])
			->where('ARR.AIRPORT.AP_ID IN(SELECT AP_ID FROM MONITORED_AIRPORTS WHERE ACTIVE = 1)');
	}

	/**
	 * Выборка возможных городов назначения для расписания
	 * @return mixed
	 */
	public function getPossibleArrivalAirports() {
		return $this->hasMany(ARRAirport::class, ['CITY_ID' => 'CITY_ID'])
			->where(
				'ARR.AIRPORT.AURA_ID IN (SELECT ARV_AP_ID FROM ' . ARRFlightsSeg::tableName() . ' SEG
										JOIN ' . ARRFlights::tableName() . ' F ON SEG.FL_ID = F.FL_ID AND F.BEG_DT >= SYSDATE
										WHERE SEG.DEP_AP_ID IN (SELECT AURA_ID from ' . ARRAirport::tableName() . ' WHERE
															AP_ID IN (SELECT AP_ID FROM MONITORED_AIRPORTS WHERE ACTIVE = 1)
														)
										)'
			);
	}

}