<?php

namespace common\models\oracle\scheme\sns\queries;

use Yii;
use yii\db\ActiveQuery;

/**
 * @author isakov.v
 *
 * Переопределенный класс для запросов рейсов для табло
 *
 */
class QueryAPFlights extends ActiveQuery {

	/**
	 * Получение рейсов для табло
	 *
	 * @param string $apCode    - код аэропорта
	 * @param string $direction - направление рейса (A/D) Arrival/Departure
	 *
	 * @return $this
	 */
	public function getFlightsToBoard($apCode, $direction) {
		$this->andWhere(
			"nvl(hide,0) = 0
														and ad_type = :direction
														and apcode = :apCode
														and
														(
															(to_date(to_char(plandate, 'DD.MM.YYYY') || ' ' || plantime, 'DD.MM.YYYY hh24:mi')
																between (sysdate - :timeDeepFrom / 24) and (sysdate + :timeDeep / 24) )
															or (dtype = 1 and to_date(to_char(FactDate, 'DD.MM.YYYY') || ' ' || FactTime, 'DD.MM.YYYY hh24:mi') >= (sysdate - :timeDeepFrom / 24))
															or (dtype = 2 and plandate <= trunc(sysdate))
															or (dtype = 3 and plandate = trunc(sysdate))
															or (dtype = 4)
														)",
			[
				':direction'    => $direction,
				':apCode'       => $apCode,
				':timeDeepFrom' => Yii::$app->params['flightsStatusBoard']['timeDeepFrom'],
				':timeDeep'     => Yii::$app->params['flightsStatusBoard']['timeDeep']
			]
		);

		return $this;
	}
}