<?php

namespace common\models\oracle\scheme\sns\queries;

use common\models\scheme\sns\Places;
use yii\db\ActiveQuery;

/**
 * @author isakov.v
 *
 *
 */
class QueryTowns extends ActiveQuery {

	/**
	 * Получение городов отправления для расписания, которые включены для мониторинга
	 * @return $this
	 */
	public function getMonitoredDepartureTownsToFlights() {
		$this->joinWith('arrCity.haveMonitoredAirports', true, 'JOIN');

		return $this;
	}

	/**
	 * Получение всех возможных городов назначения в которые есть рейсы из включенных городов отправления,
	 * кроме самих городов отправления
	 *
	 * @return $this
	 */
	public function getPossibleArrivalTownsToFlights() {
		$this->joinWith('arrCity.possibleArrivalAirports', true, 'JOIN')->orderBy('RNAME');

		return $this;
	}

	/**
	 * Получение городов присутствия
	 * @return $this
	 */
	public function getActiveTowns() {
		$this->andWhere('ID IN (SELECT CITYID FROM ' . Places::tableName() . ' where active = 1)')->orderBy('RNAME');

		return $this;
	}
}