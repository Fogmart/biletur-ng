<?php

namespace common\models\oracle\scheme\arr;

use common\models\oracle\scheme\DspBaseModel;
use yii\db\ActiveQuery;

/**
 * @author isakov.v
 *
 * Модель сегоментов рейсов из схемы ARR
 *
 * Поля таблицы:
 *
 * @property int    $FL_ID
 * @property int    $FL_SEG_NO
 * @property int    $DEP_AP_ID
 * @property string $DEP_TM
 * @property int    $DEP_OFFSET
 * @property int    $ARV_AP_ID
 * @property string $ARV_TM
 * @property int    $ARV_OFFSET
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 *
 */
class ARRFlightsSeg extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{ARR.FLIGHTS_SEG}}';
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 3;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'WHNUPD';
	}

	/**
	 * Получение аэропорта отправления
	 * @return ActiveQuery
	 */
	public function getDepartureAirport() {
		return $this->hasOne(ARRAirport::class, ['AURA_ID' => 'DEP_AP_ID']);
	}

	/**
	 * Получение аэропорта назначения
	 * @return ActiveQuery
	 */
	public function getArrivalAirport() {
		return $this->hasOne(ARRAirport::class, ['AURA_ID' => 'ARV_AP_ID']);
	}

	/**
	 * Получение рейса сегмента
	 * @return ActiveQuery
	 */
	public function getFlight() {
		return $this->hasOne(ARRFlights::class, ['FL_ID' => 'FL_ID']);
	}

}