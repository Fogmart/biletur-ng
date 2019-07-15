<?php

namespace common\models\oracle\procedures;

/**
 * @author isakov.v
 *
 *
 */
class NextKeyVal extends BaseProcedure {
	public $procedureName = 'SNS.NEXTKEYVAL';
	public $outParam = ':NEWKEY';
	public $outLength = 10;

	public function getResult() {
		return $this->params[$this->outParam];
	}
}