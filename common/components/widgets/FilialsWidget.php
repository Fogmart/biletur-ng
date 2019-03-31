<?php

namespace common\components\widgets;

use common\models\scheme\sns\Filials;
use yii\base\Widget;

/**
 * @author isakov.v
 *
 * Виджет филиалов
 */
class FilialsWidget extends Widget {
	public $cityId;
	private $filials;

	public function init() {
		parent::init();

	}

	public function run() {
		$this->filials = Filials::getAll($this->cityId);

		return $this->render('filials', ['filials' => $this->filials]);
	}
}