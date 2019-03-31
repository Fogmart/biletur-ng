<?php

namespace common\models\oracle\scheme\sns\queries;

use common\models\scheme\arr\ARRAirport;
use yii\db\ActiveQuery;

/**
 * @author isakov.v
 *
 * Переопределенный класс для запросов рейсов ARR
 *
 */
class QueryARRFlights extends ActiveQuery {

	/**
	 * Получение рейсов из города отправления во все города
	 *
	 * @param            $townId
	 * @param array|null $condition
	 * @param array|null $params
	 *
	 * @return $this
	 */
	public function getFlightsToAnywhere($townId, $condition, $params) {
		$apIds = implode(',', ARRAirport::getAirportsByTown($townId));

		$defaultCondition = 'ARR.FLIGHTS_SEG.DEP_AP_ID IN (' . $apIds . ')';
		if ($condition != null) {
			$defaultCondition .= ' AND ' . implode(' AND ', $condition);
		}
		$this->joinWith(['segments.departureAirport.city.town', 'segments.arrivalAirport.city.town'])->where(
			$defaultCondition,
			$params
		);

		return $this;
	}

	/**
	 * Получение рейсов из города в определенный город
	 *
	 * @param string     $townIdFrom
	 * @param string     $townIdTo
	 * @param array|null $condition
	 * @param array|null $params
	 *
	 * @return $this
	 */
	public function getFlightsToAirport($townIdFrom, $townIdTo, $condition = null, $params = null) {

		$fromApIds = implode(',', ARRAirport::getAirportsByTown($townIdFrom));
		$toApIds = implode(',', ARRAirport::getAirportsByTown($townIdTo));

		$defaultCondition = 'ARR.FLIGHTS_SEG.DEP_AP_ID IN (' . $fromApIds . ') AND ARR.FLIGHTS_SEG.ARV_AP_ID IN (' . $toApIds . ')';
		if ($condition != null) {
			$defaultCondition .= ' AND ' . implode(' AND ', $condition);
		}

		$this->joinWith(['segments.departureAirport.city.town', 'segments.arrivalAirport.city.town'], true, 'JOIN')
			->where($defaultCondition, $params);

		return $this;
	}

}