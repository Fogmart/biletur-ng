<?php
namespace common\models\queries;
use yii\db\ActiveQuery;
use common\models\Place;

class QueryTown extends ActiveQuery {

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
	public function activeTowns() {
		return $this->andWhere('"id" IN (SELECT "town_id" FROM ' . Place::tableName() . ' where "is_published" = 1)')
			->orderBy('r_name');
	}
}