<?php

namespace common\models\scheme\t3\queries;

use yii\db\ActiveQuery;

/**
 * @author isakov.v
 *
 *
 */
class QueryRefItems extends ActiveQuery {

	/**
	 * Получение опубликованных туров для данной зоны
	 *
	 * @return $this
	 */
	public function published() {
		$this->joinWith('active', false, 'JOIN');

		return $this;
	}
}