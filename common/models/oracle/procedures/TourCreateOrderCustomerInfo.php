<?php

namespace common\models\oracle\procedures;

/**
 * @author isakov.v
 *
 *
 */
class TourCreateOrderCustomerInfo extends BaseProcedure {
	public $procedureName = 'TOUR.CRT_ORDCUSTOMER_INFO';
	public $outParam = ':P_WIWID';
	public $outLength = 10;

	public function getResult() {
		return $this->params[$this->outParam];
	}
}